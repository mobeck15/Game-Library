<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
include_once $GLOBALS['rootpath']."/inc/gettoplist.inc.php";

class playnextPage extends Page
{
	public function __construct() {
		$this->title="Play Next";
	}
	
	public function buildHtmlBody(){
		$output="";

$BeatAvgList=array();
$BeatAvg2List=array();
$OneUnPlayedList=array();
$UnPlayedList = array();
$OverPaidList = array();

$conn=get_db_connection();
$settings=getsettings($conn);
$calculations=getCalculations("",$conn);
$topList=getTopList('Bundle',$conn,$calculations);
$conn->close();	

$calculations=reIndexArray($calculations,"Game_ID");

foreach($topList as $key => $toprow){
	$countrow=false;
	
	if($toprow['TotalHistoricPlayed']<$toprow['ModPaid'] && $toprow['UnplayedCount']>0){
		$OverPaidList[]['BundleKey']=$key;
		$countrow=true;
	}
	
	if($toprow['UnplayedCount']>0 && $toprow['GameCount']<=$toprow['UnplayedCount']){
		$UnPlayedList[]['BundleKey']=$key;
		$countrow=true;
	}

	if($toprow['BeatAvg']==1){
		$BeatAvgList[]['BundleKey']=$key;
		$countrow=true;
	}

	if($toprow['BeatAvg2']==1){
		$BeatAvg2List[]['BundleKey']=$key;
		$countrow=true;
	}
	
	if($toprow['UnplayedCount']==1){
		$OneUnPlayedList[]['BundleKey']=$key;
		$countrow=true;
	}
	
	if($countrow==true){
		foreach($toprow['RawData']['GamesinBundle'] as $BundleGame){
			if($calculations[$BundleGame['GameID']]['GrandTotal']==0 
			  && $calculations[$BundleGame['GameID']]['Playable']==true
			  && $calculations[$BundleGame['GameID']]['CountGame']==true){
				$AllGamesList[$BundleGame['GameID']]['GameID']=$BundleGame['GameID'];
				if(!isset($AllGamesList[$BundleGame['GameID']]['Bundles'])){
					$AllGamesList[$BundleGame['GameID']]['Bundles']=$toprow['Title'];
				} else {
					$AllGamesList[$BundleGame['GameID']]['Bundles'].=" \n ".$toprow['Title'];
				}
				if(!isset($AllGamesList[$BundleGame['GameID']]['PlayVPay'])){
					$AllGamesList[$BundleGame['GameID']]['PlayVPay']=$toprow['PlayVPay'];
				} else {
					$AllGamesList[$BundleGame['GameID']]['PlayVPay']+=$toprow['PlayVPay'];
				}
				if(!isset($AllGamesList[$BundleGame['GameID']]['Unplayed'])){
					$AllGamesList[$BundleGame['GameID']]['Unplayed']=$toprow['UnplayedCount'];
				} else {
					$AllGamesList[$BundleGame['GameID']]['Unplayed']+=$toprow['UnplayedCount'];
				}
			}
		}
		
		//var_dump($toprow);break;
	}
}
$output .= "<table><tr><td valign=top>";

$output .= $this->unplayedlist($UnPlayedList,"Unplayed Bundles",$topList);
$output .= $this->unplayedlist($OverPaidList,"Overpaid Bundles",$topList);
$output .= $this->unplayedlist($BeatAvgList,"Bundles Under Average played",$topList);
$output .= $this->unplayedlist($BeatAvg2List,"Bundles Under Average played (2)",$topList);
$output .= $this->unplayedlist($OneUnPlayedList,"Bundles with 1 game left",$topList);

$output .= "</td>

<td valign=top>
	<table>
		<thead>
			<tr>
				<th>Play Next List</th>
				<th>Metacritic</th>
				<th>Metauser</th>
				<th>Total</th>
				<th>Bundle</th>
				<th>Game Price</th>
				<th>Played Vs. Paid</th>
				<th>Unplayed</th>
				<th>Points</th>
			</tr>
		</thead>
		<tbody>";

		foreach($AllGamesList as $key => &$game){
			if($calculations[$game['GameID']]['Metascore']==0){
				if($calculations[$game['GameID']]['UserMetascore']==0) {
					$game['TotalMetascore']=0;
				} else {
					$game['TotalMetascore']=($calculations[$game['GameID']]['UserMetascore']*2);
				}
			} elseif ($calculations[$game['GameID']]['UserMetascore']==0){
				$game['TotalMetascore']=($calculations[$game['GameID']]['Metascore']*2);
			} else {
				$game['TotalMetascore']=($calculations[$game['GameID']]['Metascore']+$calculations[$game['GameID']]['UserMetascore']);
			}
			
			if($calculations[$game['GameID']]['HistoricLow']>=$game['PlayVPay']){
				$game['points']=$game['Unplayed'];
			} else {
				$game['points']=$calculations[$game['GameID']]['HistoricLow']/$game['PlayVPay'];
			}
			
			//var_dump($game);

			$output .= "<tr>
				<td><a href='viewgame.php?id=". $game['GameID']."' target='_blank'>". $calculations[$game['GameID']]['Title']."</a></td>
				<td>". $calculations[$game['GameID']]['Metascore']."</td>
				<td>". $calculations[$game['GameID']]['UserMetascore']."</td>
				<td>". $game['TotalMetascore']."</td>
				<td>". nl2br($game['Bundles'])."</td>
				<td>". $calculations[$game['GameID']]['HistoricLow']."</td>
				<td>". sprintf("%.2f",$game['PlayVPay'])."</td>
				<td>". $game['Unplayed']."</td>
				<td>". sprintf("%.2f",$game['points'])."</td>
			</tr>";
			$Sortby1[$key] = $calculations[$game['GameID']]['Metascore']; 
			$Sortby2[$key] = $calculations[$game['GameID']]['UserMetascore'];
			$Sortby3[$key] = $game['TotalMetascore'];
			$Sortby4[$key] = $game['points'];
		}
		$output .= "</tbody>
	</table>
</td>

<td valign=top>";


//TODO: Fixed? change these four lists into a function call - will have to move the sortby1($key) statement out and double the loops but will be self contained.
//TODO: Test this fix by adding metacritic data to unplayed games
/* * /
array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
$output .= "<table>";
$output .= "<thead>";
$output .= "<tr><th>Sort by critic</th><th>critic</th></tr>";
$output .= "</thead>";
$output .= "<tbody>";
foreach($AllGamesList as $key => $game){
	if($calculations[$game['GameID']]['Metascore']<>0){
		$output .= "<tr>";
		$output .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		$output .= "<td>".$calculations[$game['GameID']]['Metascore']."</td>";
		$output .= "</tr>";
	}
	
	$Sortby1[$key]  = $calculations[$game['GameID']]['UserMetascore'];
}
$output .= "</tbody>";
$output .= "</table>";

$output .= "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
$output .= "<table>";
$output .= "<thead>";
$output .= "<tr><th>Sort by user</th><th>user</th></tr>";
$output .= "</thead>";
$output .= "<tbody>";
foreach($AllGamesList as $key => $game){
	if($calculations[$game['GameID']]['UserMetascore']<>0){
		$output .= "<tr>";
		$output .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		$output .= "<td>".$calculations[$game['GameID']]['UserMetascore']."</td>";
		$output .= "</tr>";
	}
	
	$Sortby1[$key]  = $game['TotalMetascore'];
}
$output .= "</tbody>";
$output .= "</table>";

$output .= "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
$output .= "<table>";
$output .= "<thead>";
$output .= "<tr><th>Sort by Total Critic</th><th>Total</th></tr>";
$output .= "</thead>";
$output .= "<tbody>";
foreach($AllGamesList as $key => $game){
	if($game['TotalMetascore']<>0){
		$output .= "<tr>";
		$output .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		$output .= "<td>".$game['TotalMetascore']."</td>";
		$output .= "</tr>";
	}
	
	$Sortby1[$key] = $game['points'];
}
$output .= "</tbody>";
$output .= "</table>";

$output .= "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
$output .= "<table>";
$output .= "<thead>";
$output .= "<tr><th>Sort Points</th><th>Points</th></tr>";
$output .= "</thead>";
$output .= "<tbody>";
foreach($AllGamesList as $key => $game){
	if($game['points']<>0){
		$output .= "<tr>";
		$output .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		$output .= "<td>".sprintf("%.2f",$game['points'])."</td>";
		$output .= "</tr>";
	}
}
$output .= "</tbody>";
$output .= "</table>";


/* */
$output .= "<td valign=top>".$this->playnexttable($Sortby1,$AllGamesList,"Sort by critic","critic","Metascore",$calculations)."</td>";
$output .= "<td valign=top>".$this->playnexttable($Sortby2,$AllGamesList,"Sort by user","user","UserMetascore",$calculations)."</td>";
$output .= "<td valign=top>".$this->playnexttable($Sortby3,$AllGamesList,"Sort by Total Critic","Total","TotalMetascore",$calculations)."</td>";
$output .= "<td valign=top>".$this->playnexttable($Sortby4,$AllGamesList,"Sort Points","Points","points",$calculations)."</td>";


$output .= "</td></tr></table>";

		return $output;
	}
	
	private function unplayedlist($bundlelist,$title,$topList){
		$output="";
		if (isset($bundlelist)){ 
			$output .= "\r\n<table>\r\n";
			$output .= "\t<thead>\r\n";
			$output .= "\t\t<tr><th>$title</th><th>Unplayed Games</th></tr>\r\n";
			$output .= "\t</thead>\r\n";
			$output .= "\t<tbody>\r\n";
			foreach($bundlelist as $key){
				$output .= "\t\t<tr>\r\n";
				$output .= "\t\t\t<td>".$topList[$key['BundleKey']]['Title']."</td>\r\n";
				$output .= "\t\t\t<td>". $topList[$key['BundleKey']]['UnplayedCount']. "</td>\r\n";
				$output .= "\t\t</tr>\r\n";
			}
			$output .= "\t</tbody>\r\n";
			$output .= "</table>\r\n";
		}
		return $output;
	} 

	private function playnexttable($sortby, $gamelist, $title, $caption, $chekfield, $calculations) {
		$output="";
		array_multisort($sortby, SORT_DESC, $gamelist);
		$output .= "<table>";
		$output .= "<thead>";
		$output .= "<tr><th>$title</th><th>$caption</th></tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		foreach($gamelist as $key => $game){
			if(isset($game[$chekfield])) {
				$checkvalue=$game[$chekfield];
			} else {
				$checkvalue=$calculations[$game['GameID']][$chekfield];
			}
			if($checkvalue<>0){
				$output .= "<tr>";
				$output .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
				$output .= "<td>".sprintf("%.2f",$checkvalue)."</td>";
				$output .= "</tr>";
			}
			unset($checkvalue);
		}
		$output .= "</tbody>";
		$output .= "</table>";
		
		return $output;
	}
	
}	