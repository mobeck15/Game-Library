<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getTopList.inc.php";

class wasteStats {
	public $BundleCount;
	public $GameCount;
	public $PriceDiff;
	public $NeverHours;
	public $FreeHours;
	public $DupeCount;
	public $upBundleCount;
	public $upGames;
	public $upSpent;
	public $OverPaidList;
	public $UnPlayedList;
	
	public function __construct() {
	}
	
	public function countBundles($topList) {
		$this->BundleCount=0;
		$this->GameCount=0;
		$this->PriceDiff=0;
		$this->upBundleCount=0;
		$this->upSpent=0;
		$this->upGames=0;
		$this->OverPaidList=array();
		$this->UnPlayedList=array();
		
		foreach($topList as $key => $toprow){
			if($toprow['TotalHistoricPlayed']<$toprow['ModPaid']){
				$this->BundleCount++;
				$this->GameCount+=$toprow['UnplayedCount'];
				$this->PriceDiff+=$toprow['ModPaid']-$toprow['TotalHistoricPlayed'];
				$this->OverPaidList[]['BundleKey']=$key;
			}
			
			if($toprow['GameCount']<=$toprow['UnplayedCount']){
				$this->UnPlayedList[]['BundleKey']=$key;
				if($toprow['UnplayedCount']>0){
					$this->upBundleCount++;
					$this->upSpent+=$toprow['ModPaid'];
					$this->upGames+=$toprow['UnplayedCount'];	
				}
			}
		}
	}
	
	public function countGames($calculations) {
		$this->NeverHours=0;
		$this->FreeHours=0;
		
		foreach($calculations as $calcrow){
			if($calcrow['Status']=="Never"){
				$this->NeverHours+=$calcrow['GrandTotal'];
			}
			
			if($calcrow['Paid']==0){
				$this->FreeHours+=$calcrow['GrandTotal'];
			}
		}
	}
	
	public function countItems($items) {
		$this->DupeCount=0;
		
		foreach($items as $itemrow){
			if($itemrow['Library']=="Inactive"){
				$this->DupeCount++;
			}
		}
	}
}

class wastePage extends Page
{
	private $waste;
	
	public function __construct() {
		$this->title="Waste";
	}
	
	private function buildBigUnplayedTable($UnPlayedList,$topList) {
		$output = "";
		$output2 = "";
		
		foreach ($UnPlayedList as $key => $row) {
			$sortkey[$key]  = $topList[$row['BundleKey']]['UnplayedCount'];
		}
		//$sortkey  = array_column($UnPlayedList, 'UnplayedCount');
		array_multisort($sortkey, SORT_DESC, $UnPlayedList);
		
		foreach($UnPlayedList as $key){
			if($topList[$key['BundleKey']]['UnplayedCount']){
				$output2 .= '<tr>
				<td>'. $topList[$key['BundleKey']]['Title'].'</td>
				<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']).'</td>
				<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
				</tr>';
			}
		} 

		if($output2 <> "") {
			$output = '<table>
			<thead>
			<tr>
			<th>Biggest Unplayed</th><th>Paid</th><th>Unplayed</th>
			</tr>
			</thead>
			<tbody>';
			$output .= $output2;
			$output .= '</tbody>
			</table>';
		}
		
		return $output;
	}
	
	private function buildOldUnplayedTable($UnPlayedList,$topList) {
		$output = "";
		$output2 = "";
		
		foreach ($UnPlayedList as $key => $row) {
			$sortkey[$key]  = strtotime(combinedate($topList[$row['BundleKey']]['PurchaseDate'],$topList[$row['BundleKey']]['PurchaseTime'],$topList[$row['BundleKey']]['PurchaseSequence']));
		}
		//$sortkey  = array_column($UnPlayedList, 'PurchaseDate');
		array_multisort($sortkey, SORT_ASC, $UnPlayedList);
		
		foreach($UnPlayedList as $key){
			if($topList[$key['BundleKey']]['UnplayedCount']){
				$output2 .= '<tr>
				<td>'. $topList[$key['BundleKey']]['Title'].'</td>
				<td>'. combinedate($topList[$key['BundleKey']]['PurchaseDate'],$topList[$key['BundleKey']]['PurchaseTime'],$topList[$key['BundleKey']]['PurchaseSequence']).'</td>
				<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
				</tr>';
			}
		}
		
		if($output2 <> "") {
			$output = '<table>
			<thead>
			<tr>
			<th>Oldest Unplayed</th><th>Purchased</th><th>Unplayed</th>
			</tr>
			</thead>
			<tbody>';
			$output .= $output2;
			$output .= '</tbody>
			</table>';
		}
		
		return $output;
	}
	
	private function buildUnplayedTable($upBundleCount,$upGames,$upSpent) {
		$output = "";
		if($upBundleCount+$upGames+$upSpent <> 0) {
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
			</tbody>
			</table>';
		}
		return $output;
	}
	
	private function buildOverpaidTable($waste) {
		$output = '<table>
		<thead>';
		//TODO: add detail list of overpaid bundles
		//TODO: add detail list of overpaid games in bundles
		$output .= '<tr><th colspan=2>Overpaid Bundles</th></tr>
		</thead>
		<tbody>
		<tr>
		<th>Bundles</th><td>'. $waste->BundleCount.'</td>
		</tr>
		<tr>
		<th>Unplayed games in Bundles</th><td>'. $waste->GameCount.'</td>
		</tr>
		<tr>
		<th>Price Diff</th><td>$'. sprintf("%.2f",$waste->PriceDiff).'</td>
		</tr>
		<tr>
		<th>Hours on NEVER</th><td>'. timeduration($waste->NeverHours ?? 0,"seconds").'</td>
		</tr>
		<tr>
		<th>Hours on Free</th><td>'. timeduration($waste->FreeHours ?? 0,"seconds").'</td>
		</tr>
		<tr>
		<th>Untraded Dupes</th><td>'. $waste->DupeCount.'</td>
		</tr>
		</tbody>
		</table>';
		
		return $output;
	}
	
	private function buildBigOverpaidTable($OverPaidList,$topList,$calculations) {
		$output = "";
		$output2 = "";
		
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
				$output2 .= '<tr>
				<td>'. $topList[$key['BundleKey']]['Title'].'</td>
				<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['diff']).'</td>
				<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']).'</td>
				<td>$'. sprintf("%.2f",$topList[$key['BundleKey']]['TotalHistoricPlayed']).'</td>
				<td>'. $topList[$key['BundleKey']]['UnplayedCount'].'</td>
				</tr>';
			}
		}
		if($output2 <> "") {
			$output .= '<table>
			<thead>
			<tr>
			<th>Biggest Overpaid</th><th>Cost Diff</th>
			<th>Mod Paid</th>
			<th>Total Historic Price of Played</th>
			<th>Unplayed Games</th>
			</tr>
			</thead>
			<tbody>';
			$output .= $output2;
			$output .= '</tbody>
			</table>';
			
			$output .= $this->buildOverpaidGamesTable($GamesfromOverpaid,$calculations);
		}
		
		return $output;
	}
	
	private function buildOverpaidGamesTable($GamesfromOverpaid,$calculations) {
		$output = "";
		$output2 = "";
		
		//TODO: add detail to overpaid games table including play link and item type.

		$Sortby1 = array();
		foreach ($GamesfromOverpaid as $key => $row) {
			$Sortby1[$key]  = ($calculations[$row['GameID']]['HistoricLow']);
		}
		array_multisort($Sortby1, SORT_DESC, $GamesfromOverpaid);

		foreach($GamesfromOverpaid as $row){
				$output2 .= '<tr>
				<td><a href="viewgame.php?id='. $row['GameID'].'" target="_blank">'. $calculations[$row['GameID']]['Title'].'</a></td>
				<td>'. $calculations[$row['GameID']]['HistoricLow'].'</td>
				</tr> ';
		}
		
		if($output2 <>""){
			$output = '<table>
				<thead>
				<tr>
				<th>Games from Overpaid Bundles</th><th>Low price</th>
				</tr>
				</thead>
				<tbody>';
			$output .= $output2;
			$output .= '</tbody>
			</table>';
		}
		
		return $output;
	}
	
	public function buildHtmlBody(){
		$output="";
		
		$topList=$this->data()->getTopBundles();
		$items=$this->data()->getAllItems();
		$calculations=$this->data()->getCalculations();
		
		$this->waste = new wasteStats();
		$this->waste->countBundles($topList);
		$this->waste->countGames($calculations);
		$this->waste->countItems($items);
		
		$output .= $this->buildUnplayedTable($this->waste->upBundleCount,$this->waste->upGames,$this->waste->upSpent);
		$output .= $this->buildBigUnplayedTable($this->waste->UnPlayedList,$topList);
		$output .= $this->buildOldUnplayedTable($this->waste->UnPlayedList,$topList);
		$output .= $this->buildOverpaidTable($this->waste);
		$output .= $this->buildBigOverpaidTable($this->waste->OverPaidList,$topList,$calculations);
	
		return $output;
	}
}	