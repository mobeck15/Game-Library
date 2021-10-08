<?php
include "inc/php.ini.inc.php";
include "inc/functions.inc.php";

$title="Play Next";
echo Get_Header($title);

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
	
	if($toprow['GameCount']<=$toprow['UnplayedCount']){
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
?>
<table><tr><td valign=top>

<?php
echo unplayedlist($UnPlayedList,"Unplayed Bundles",$topList);
echo unplayedlist($OverPaidList,"Overpaid Bundles",$topList);
echo unplayedlist($BeatAvgList,"Bundles Under Average played",$topList);
echo unplayedlist($BeatAvg2List,"Bundles Under Average played (2)",$topList);
echo unplayedlist($OneUnPlayedList,"Bundles with 1 game left",$topList);

function unplayedlist($bundlelist,$title,$topList){
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
} ?>
</td>

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
		<tbody>
		<?php
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
			?>
			<tr>
				<td><a href='viewgame.php?id=<?php echo $game['GameID']; ?>' target='_blank'><?php echo $calculations[$game['GameID']]['Title']; ?></a></td>
				<td><?php echo $calculations[$game['GameID']]['Metascore']; ?></td>
				<td><?php echo $calculations[$game['GameID']]['UserMetascore']; ?></td>
				<td><?php echo $game['TotalMetascore']; ?></td>
				<td><?php echo nl2br($game['Bundles']); ?></td>
				<td><?php echo $calculations[$game['GameID']]['HistoricLow']; ?></td>
				<td><?php echo sprintf("%.2f",$game['PlayVPay']); ?></td>
				<td><?php echo $game['Unplayed']; ?></td>
				<td><?php echo sprintf("%.2f",$game['points']); ?></td>
			</tr>
			<?php $Sortby1[$key]  = $calculations[$game['GameID']]['Metascore']; 
		} ?>
		</tbody>
	</table>
</td>

<td valign=top>

<?php
//TODO: change these four lists into a function call - will have to move the sortby1($key) statement out and double the loops but will be self contained.

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
echo "<table>";
echo "<thead>";
echo "<tr><th>Sort by critic</th><th>critic</th></tr>";
echo "</thead>";
echo "<tbody>";
foreach($AllGamesList as $key => $game){
	if($calculations[$game['GameID']]['Metascore']<>0){
		echo "<tr>";
		echo "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		echo "<td>".$calculations[$game['GameID']]['Metascore']."</td>";
		echo "</tr>";
	}
	
	$Sortby1[$key]  = $calculations[$game['GameID']]['UserMetascore'];
}
echo "</tbody>";
echo "</table>";

echo "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
echo "<table>";
echo "<thead>";
echo "<tr><th>Sort by user</th><th>user</th></tr>";
echo "</thead>";
echo "<tbody>";
foreach($AllGamesList as $key => $game){
	if($calculations[$game['GameID']]['UserMetascore']<>0){
		echo "<tr>";
		echo "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		echo "<td>".$calculations[$game['GameID']]['UserMetascore']."</td>";
		echo "</tr>";
	}
	
	$Sortby1[$key]  = $game['TotalMetascore'];
}
echo "</tbody>";
echo "</table>";

echo "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
echo "<table>";
echo "<thead>";
echo "<tr><th>Sort by Total Critic</th><th>Total</th></tr>";
echo "</thead>";
echo "<tbody>";
foreach($AllGamesList as $key => $game){
	if($game['TotalMetascore']<>0){
		echo "<tr>";
		echo "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		echo "<td>".$game['TotalMetascore']."</td>";
		echo "</tr>";
	}
	
	$Sortby1[$key] = $game['points'];
}
echo "</tbody>";
echo "</table>";

echo "</td><td valign=top>";

array_multisort($Sortby1, SORT_DESC, $AllGamesList);
unset($Sortby1);
echo "<table>";
echo "<thead>";
echo "<tr><th>Sort Points</th><th>Points</th></tr>";
echo "</thead>";
echo "<tbody>";
foreach($AllGamesList as $key => $game){
	if($game['points']<>0){
		echo "<tr>";
		echo "<td><a href='viewgame.php?id=".$game['GameID']."' target='_blank'>".$calculations[$game['GameID']]['Title']."</a></td>";
		echo "<td>".sprintf("%.2f",$game['points'])."</td>";
		echo "</tr>";
	}
}
echo "</tbody>";
echo "</table>";

echo "</td></tr></table>";
echo Get_Footer(); ?>