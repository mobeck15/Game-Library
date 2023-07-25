<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getTopList.inc.php";

class wastePage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="Waste";
	}
	
	public function buildHtmlBody(){
		$output="";
		
	$conn=get_db_connection();
	$settings=getsettings($conn);
	$calculations=getCalculations("",$conn);
	$topList=getTopList('Bundle',$conn,$calculations);
	$items=getAllItems("",$conn);
	$conn->close();	
	
	$calculations=reIndexArray($calculations,"Game_ID");
	
	//var_dump($topList);
	$BundleCount=
	$GameCount=
	$PriceDiff=
	$NeverHours=
	$FreeHours=
	$DupeCount=
	$upBundleCount=
	$upGames=
	$upSpent=0;
	
	foreach($topList as $key => $toprow){
		if($toprow['TotalHistoricPlayed']<$toprow['ModPaid']){
			$BundleCount++;
			$GameCount+=$toprow['UnplayedCount'];
			$PriceDiff+=$toprow['ModPaid']-$toprow['TotalHistoricPlayed'];
			$OverPaidList[]['BundleKey']=$key;
			//var_dump($toprow);
			//break;
		}
		
		if($toprow['GameCount']<=$toprow['UnplayedCount']){
			$UnPlayedList[]['BundleKey']=$key;
			if($toprow['UnplayedCount']>0){
				$upBundleCount++;
				$upSpent+=$toprow['ModPaid'];
				$upGames+=$toprow['UnplayedCount'];	
			}
		}
		//var_dump($toprow);
		//break;
	}

	foreach($calculations as $calcrow){
		if($calcrow['Status']=="Never"){
			$NeverHours+=$calcrow['GrandTotal'];
		}
		
		if($calcrow['Paid']==0){
			$FreeHours+=$calcrow['GrandTotal'];
		}
	}
	
	foreach($items as $itemrow){
		if($itemrow['Library']=="Inactive"){
			$DupeCount++;
			//var_dump($itemrow);
			//break;
		}
	}
	
	//$top['Title']
	//$top['key']
	
	$output .= '<table>
	<thead>
	<tr><th colspan=2>Unplayed Bundles</th></tr>
	</thead>
	<tbody>
	<tr>
	<th>Bundles</th><td>'. $upBundleCount.'</td>
	</tr>
	<tr>
	<th>Games</th><td>'. $upGames.'</td>
	</tr>
	<tr>
	<th>Spent</th><td>$'. sprintf("%.2f",$upSpent).'</td>
	</tr>
	<tr>

	<table>
	<thead>
	<tr>
	<th>Biggest Unplayed</th><th>Paid</th><th>Unplayed</th>
	</tr>
	</thead>
	<tbody>';
	foreach($UnPlayedList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']){
			$output .= '<tr>
			<td>'. $topList[$key['BundleKey']]['Title'].'</td>
			<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']).'</td>
			<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
			</tr>';
		}
	} 
	$output .= '</tbody>
	</table>

	<table>
	<thead>
	<tr>
	<th>Oldest Unplayed</th><th>Purchased</th>
	</tr>
	</thead>
	<tbody>';
	foreach($UnPlayedList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']){
			$output .= '<tr>
			<td>'. $topList[$key['BundleKey']]['Title'].'</td>
			<td>'. combinedate($topList[$key['BundleKey']]['PurchaseDate'],$topList[$key['BundleKey']]['PurchaseTime'],$topList[$key['BundleKey']]['PurchaseSequence']).'</td>
			<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
			</tr>';
		}
	}
	$output .= '</tbody>
	</table>
	
	<table>
	<thead>';
	//TODO: add detail list of overpaid bundles
	//TODO: add detail list of overpaid games in bundles
	$output .= '<tr><th colspan=2>Overpaid Bundles</th></tr>
	</thead>
	<tbody>
	<tr>
	<th>Bundles</th><td>'. $BundleCount.'</td>
	</tr>
	<tr>
	<th>Unplayed games in Bundles</th><td>'. $GameCount.'</td>
	</tr>
	<tr>
	<th>Price Diff</th><td>$'. sprintf("%.2f",$PriceDiff).'</td>
	</tr>
	<tr>
	<th>Hours on NEVER</th><td>'. timeduration($NeverHours,"seconds").'</td>
	</tr>
	<tr>
	<th>Hours on Free</th><td>'. timeduration($FreeHours,"seconds").'</td>
	</tr>
	<tr>
	<th>Untraded Dupes</th><td>'. $DupeCount.'</td>
	</tr>
	</tbody>
	</table>
	
	<table>
	<thead>
	<tr>
	<th>Biggest Overpaid</th><th>Cost Diff</th>
	<th>Mod Paid</th>
	<th>Total Historic Price of Played</th>
	<th>Unplayed Games</th>
	</tr>
	</thead>
	<tbody>';
	
	foreach ($OverPaidList as $key => $row) {
		$topList[$row['BundleKey']]['diff']= 
		$Sortby1[$key]  = ($topList[$row['BundleKey']]['ModPaid']-$topList[$row['BundleKey']]['TotalHistoricPlayed']);
	}
	array_multisort($Sortby1, SORT_DESC, $OverPaidList);
	
	$GamesfromOverpaid = array();
	
	foreach($OverPaidList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']>0){
			foreach($topList[$key['BundleKey']]['RawData']['GamesinBundle'] as $BundleGame){
				if($calculations[$BundleGame['GameID']]['GrandTotal']==0){
					$GamesfromOverpaid[$BundleGame['GameID']]['GameID']=$BundleGame['GameID'];
					$GamesfromOverpaid[$BundleGame['GameID']]['LowPrice']=$BundleGame['HistoricLow'];
				}
			}
			
			//var_dump($key);
			$output .= '<tr>
			<td>'. $topList[$key['BundleKey']]['Title'].'</td>
			<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['diff']).'</td>
			<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']).'</td>
			<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['TotalHistoricPlayed']).'</td>
			<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
			</tr>';
		}
	}
	$output .= '</tbody>
	</table>

	<table>
	<thead>
	<tr>
	<th>Games from Overpaid Bundles</th><th>Low price</th>
	</tr>
	</thead>
	<tbody>';
	
	//TODO: add detail to overpaid games table including play link and item type.

	$Sortby1 = array();
	foreach ($GamesfromOverpaid as $key => $row) {
		$Sortby1[$key]  = ($calculations[$row['GameID']]['HistoricLow']);
	}
	array_multisort($Sortby1, SORT_DESC, $GamesfromOverpaid);

	foreach($GamesfromOverpaid as $row){
			$output .= '<tr>
			<td><a href="viewgame.php?id='. $row['GameID'].'" target="_blank">'. $calculations[$row['GameID']]['Title'].'</a></td>
			<td>'. $calculations[$row['GameID']]['HistoricLow'].'</td>
			</tr> ';
	}	
	$output .= '</tbody>
	</table>';
		return $output;
	}
}	