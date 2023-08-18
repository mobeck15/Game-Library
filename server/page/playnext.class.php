<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/dataSet.class.php";

class playnextPage extends Page
{
	public function __construct() {
		$this->title="Play Next";
	}
	
	public function buildHtmlBody(){
		$output="";
		$calculations = $this->data()->getCalculations();
		$topList = $this->data()->getTopBundles(); 

		$AllGamesList = $this->makeAllGamesList($topList,$calculations);
		
		$output .= "<table><tr><td valign=top>";

		$output .= $this->unplayedlist($this->makeUnPlayedList($topList),"Unplayed Bundles",$topList);
		$output .= $this->unplayedlist($this->makeOverPaidList($topList),"Overpaid Bundles",$topList);
		$output .= $this->unplayedlist($this->makeBeatAvgList($topList),"Bundles Under Average played",$topList);
		$output .= $this->unplayedlist($this->makeBeatAvg2List($topList),"Bundles Under Average played (2)",$topList);
		$output .= $this->unplayedlist($this->makeOneUnPlayedList($topList),"Bundles with 1 game left",$topList);

		$output .= "</td><td valign=top>";
	
		$output .= $this->buildAllGamesTable($AllGamesList,$calculations);
	
		$output .= "</td>";

		$sortby = $this->makeSortKeys($AllGamesList,$calculations);

		$output .= "<td valign=top>".$this->playnexttable($sortby[1],$AllGamesList,"Sort by critic","critic","Metascore",$calculations)."</td>";
		$output .= "<td valign=top>".$this->playnexttable($sortby[2],$AllGamesList,"Sort by user","user","UserMetascore",$calculations)."</td>";
		$output .= "<td valign=top>".$this->playnexttable($sortby[3],$AllGamesList,"Sort by Total Critic","Total","TotalMetascore",$calculations)."</td>";
		$output .= "<td valign=top>".$this->playnexttable($sortby[4],$AllGamesList,"Sort Points","Points","points",$calculations)."</td>";

		$output .= "</td></tr></table>";

		return $output;
	}
	
	private function buildAllGamesTable($AllGamesList,$calculations) {
		$output = "<table>
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

		foreach($AllGamesList as $key => $game){
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
		}
		$output .= "</tbody>
	</table>";
	
	return $output;
	}
	
	private function makeOverPaidList($topList) {
		$OverPaidList = array();
		foreach($topList as $key => $toprow){
			if($toprow['TotalHistoricPlayed']<$toprow['ModPaid'] && $toprow['UnplayedCount']>0){
				$OverPaidList[]['BundleKey']=$key;
			}
		}
		
		return $OverPaidList;
	}
	
	private function makeUnPlayedList($topList) {
		$UnPlayedList = array();
		foreach($topList as $key => $toprow){
			if($toprow['UnplayedCount']>0 && $toprow['GameCount']<=$toprow['UnplayedCount']){
				$UnPlayedList[]['BundleKey']=$key;
			}
		}
		
		return $UnPlayedList;
	}
	
	private function makeBeatAvgList($topList) {
		$BeatAvgList = array();
		foreach($topList as $key => $toprow){
			if($toprow['BeatAvg']==1){
				$BeatAvgList[]['BundleKey']=$key;
			}
		}
		
		return $BeatAvgList;
	}
	
	private function makeBeatAvg2List($topList) {
		$BeatAvg2List = array();
		foreach($topList as $key => $toprow){
			if($toprow['BeatAvg2']==1){
				$BeatAvg2List[]['BundleKey']=$key;
				$countrow=true;
			}
		}
		
		return $BeatAvg2List;
	}
	
	private function makeOneUnPlayedList($topList) {
		$OneUnPlayedList = array();
		foreach($topList as $key => $toprow){
			if($toprow['UnplayedCount']==1){
				$OneUnPlayedList[]['BundleKey']=$key;
				$countrow=true;
			}
		}
		
		return $OneUnPlayedList;
	}
	
	private function createAllGamesList($topList,$calculations) {
		$AllGamesList=array();
		foreach($topList as $key => $toprow){
			$countrow=false;
			
			if($toprow['TotalHistoricPlayed']<$toprow['ModPaid'] && $toprow['UnplayedCount']>0
				OR $toprow['UnplayedCount']>0 && $toprow['GameCount']<=$toprow['UnplayedCount']
				OR $toprow['BeatAvg']==1
				OR $toprow['BeatAvg2']==1
				OR $toprow['UnplayedCount']==1){
				$countrow=true;
			}
			
			if($countrow==true && isset($toprow['RawData'])){
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
			}
		}
		
		return $AllGamesList;
	}
	
	private function updateAllGamesList($AllGamesList,$calculations) {
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
		}
		
		return $AllGamesList;
	}
	
	
	private function makeAllGamesList($topList,$calculations) {
		$AllGamesList = $this->createAllGamesList($topList,$calculations);
		$AllGamesList = $this->updateAllGamesList($AllGamesList,$calculations);
		
		return $AllGamesList;
	}
	
	private function makeSortKeys($AllGamesList,$calculations) {
		$sortby = array(
			1 => array(),
			2 => array(),
			3 => array(),
			4 => array()
		);
		
		foreach($AllGamesList as $key => $game){
			$sortby[1][$key] = $calculations[$game['GameID']]['Metascore']; 
			$sortby[2][$key] = $calculations[$game['GameID']]['UserMetascore'];
			$sortby[3][$key] = $game['TotalMetascore'];
			$sortby[4][$key] = $game['points'];
		}
		
		return $sortby;
	}
	
	private function unplayedlist($bundlelist,$title,$topList){
		$output="";
		$output2="";
		
		if (isset($bundlelist)){ 
			foreach($bundlelist as $key){
				$output2 .= "\t\t<tr>\r\n";
				$output2 .= "\t\t\t<td>".$topList[$key['BundleKey']]['Title']."</td>\r\n";
				$output2 .= "\t\t\t<td>". $topList[$key['BundleKey']]['UnplayedCount']. "</td>\r\n";
				$output2 .= "\t\t</tr>\r\n";
			}
			if($output2 <> "") {
				$output .= "\r\n<table>\r\n";
				$output .= "\t<thead>\r\n";
				$output .= "\t\t<tr><th>$title</th><th>Unplayed Games</th></tr>\r\n";
				$output .= "\t</thead>\r\n";
				$output .= "\t<tbody>\r\n";
				$output .= $output2;
				$output .= "\t</tbody>\r\n";
				$output .= "</table>\r\n";
			}
		}
		return $output;
	} 

	private function playnexttable($sortby, $gamelist, $title, $caption, $chekfield, $calculations) {
		$output="";
		$output2="";
		
		array_multisort($sortby, SORT_DESC, $gamelist);
		
		foreach($gamelist as $key => $game){
			if(isset($game[$chekfield])) {
				$checkvalue=$game[$chekfield];
			} else {
				$checkvalue=$calculations[$game['GameID']][$chekfield];
			}
			if($checkvalue<>0){
				$output2 .= "<tr>";
				$output2 .= "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
				$output2 .= "<td>".sprintf("%.2f",$checkvalue)."</td>";
				$output2 .= "</tr>";
			}
			unset($checkvalue);
		}
		
		if($output2 <> "") {
			$output .= "<table>";
			$output .= "<thead>";
			$output .= "<tr><th>$title</th><th>$caption</th></tr>";
			$output .= "</thead>";
			$output .= "<tbody>";
			$output .= $output2;
			$output .= "</tbody>";
			$output .= "</table>";
		}
		
		return $output;
	}
	
}	