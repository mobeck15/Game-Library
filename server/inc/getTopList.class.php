<?php
//TODO: Update getTopList function to GL5 version
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getGames.class.php";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getPurchases.class.php";
include_once $GLOBALS['rootpath']."/inc/dataSet.class.php";

class TopList {
	private $purchasesArray;
	private $dataSet;
	
	public function __construct(dataSet $ds = null) {
		$this->dataSet = $ds ?? new dataSet();
	}
	
	public function buildTopListArray($group,$minGroupSize=2)
	{
		$top = [];
		
		switch($group){
			default:
				//Group by bundle if group parameter is not recognized
			case "Bundle":
				$top = $this->buildBundleTopList();
				break;
			case "Keyword":
				$top = $this->buildKeywordTopList();
				break;
			case "Series":
				$top = $this->buildSeriesTopList($minGroupSize);
				break;
			case "Store":
				$top = $this->buildStoreTopList();
				break;
			case "DRM":
			case "OS":
			case "Library":
				$top = $this->buildSimpleTopList($group);
				break;
			case "Meta10": //Metascore (1-10)
			case "UMeta10": //User Metascore (1-10)
			case "SteamR10": //Steam Rating (1-10)
				$top = $this->buildRatingTopList($group,10);
				break;
			case "Review":
			case "Want":
			case "Meta": //Metascore
			case "UMeta": //User Metascore
			case "SteamR": //Steam Rating
				$top = $this->buildRatingTopList($group,100);
				break;
			case "PYear": //Purchase Year
			case "LYear": //Launch Year
			case "PMonth": //Purchase Month
			case "LMonth": //Launch Month
			case "PMonthNum": //Purchase Month Number
			case "LMonthNum": //Launch Month Number
				$top = $this->buildDateTopList($group);
				break;
				
			//case "PWeek": //Purchase Week
			//case "PWeekNum": //Purchase Week Number
			//case "LWeek": //Launch Week
			//case "LWeekNum": //Launch Week Number
			//TODO: Group by Developer Publisher and Alphasort don't work yet.
			//case "Developer": 
			//case "Publisher": 
			//case "AlphaSort": //First Letter
			//	break;
		}

		$top = $this->calculateStatistics($top);

        return $top;
	}
	
	private function calculateStatistics(array $top): array
	{
		$GrandTotalWant = 0;
		$total = $this->initializeTotalRow();
		
		// Collect all products for the Total row
		$allProducts = [];

		foreach ($top as $key => &$row) {
			$this->initializeRowStats($row);
			
			$totalWant = 0;
			$products = is_array($row['Products']) ? array_values($row['Products']) : [];
			foreach ($products as $product) {
				if ($this->isCountableGame($product)) {
					$this->updateRowWithProductStats($row, $product, $totalWant, $GrandTotalWant);
					// Add to the total products list
					$allProducts[$product] = $product;
				}
			}

			$this->finalizeRowStats($row, $totalWant);
			$this->updateTotalWithRow($total, $row);
		}

		// Now treat Total like any other row - add all products and process them
		$total['Products'] = $allProducts;
		$this->initializeRowStats($total);
		
		$totalWant = 0;
		foreach ($allProducts as $product) {
			if ($this->isCountableGame($product)) {
				$this->updateRowWithProductStats($total, $product, $totalWant, $GrandTotalWant);
			}
		}
		
		$this->finalizeRowStats($total, $totalWant);
		$this->updateBeatAverageStats($top, $total);

		$top['Total'] = $total;
		return $top;
	}

	private function initializeTotalRow(): array
	{
		return [
			'ID' => 'Total',
			'Title' => 'Total',
			'PurchaseDate' => 0,
			'PurchaseTime' => 0,
			'PurchaseSequence' => 0,
			'Paid' => 0,
			'ModPaid' => 0,
			'Products' => [],
			'GameCount' => 0,
			'ItemCount' => 0,
			'Active' => 0,
			'TotalLaunch' => 0,
			'TotalMSRP' => 0,
			'TotalHistoric' => 0,
			'TotalHours' => 0,
			'TotalLaunchPlayed' => 0,
			'TotalMSRPPlayed' => 0,
			'TotalHistoricPlayed' => 0,
			'InactiveCount' => 0,
			'UnplayedCount' => 0,
			'ActiveCount' => 0,
			'IncompleteCount' => 0,
			'UnplayedInactiveCount' => 0,
			'Filter' => 0,
			'leastPlay' => ['ID' => '', 'Name' => '', 'hours' => PHP_INT_MAX],
			'mostPlay' => ['ID' => '', 'Name' => '', 'hours' => 0]
		];
	}

	private function initializeRowStats(array &$row): void
	{
		$row['ModPaid'] = $row['Paid'];
		$row['ItemCount'] = 0;
		$row['GameCount'] = 0;
		$row['Active'] = false;
		$row['TotalLaunch'] = 0;
		$row['TotalMSRP'] = 0;
		$row['TotalHistoric'] = 0;
		$row['TotalHours'] = 0;
		$row['TotalLaunchPlayed'] = 0;
		$row['TotalMSRPPlayed'] = 0;
		$row['TotalHistoricPlayed'] = 0;
		$row['InactiveCount'] = 0;
		$row['UnplayedCount'] = 0;
		$row['ActiveCount'] = 0;
		$row['IncompleteCount'] = 0;
		$row['UnplayedInactiveCount'] = 0;
	}

	private function isCountableGame(string $productId): bool
	{
		$calcs = $this->dataSet->getCalculations();
		if (!isset($calcs[$productId])) {
			return false;
		}
		// Accept 1, "1", true, etc.
		return (bool) ($calcs[$productId]['CountGame'] ?? false);
	}

	private function updateRowWithProductStats(array &$row, string $productId, int &$totalWant, int &$GrandTotalWant): void
	{
		$calcs = $this->dataSet->getCalculations();
		if (!isset($calcs[$productId])) {
			// optionally log/debug here
			return;
		}
		$calc = $calcs[$productId];

		$row['ItemCount']++;

		$row['TotalLaunch'] += $calc['LaunchPrice'];
		$row['TotalMSRP'] += $calc['MSRP'];
		$row['TotalHistoric'] += $calc['HistoricLow'];
		$row['TotalHours'] += $calc['GrandTotal'];

		if ($calc['GrandTotal'] > 0) {
			$row['TotalLaunchPlayed'] += $calc['LaunchPrice'];
			$row['TotalMSRPPlayed'] += $calc['MSRP'];
			$row['TotalHistoricPlayed'] += $calc['HistoricLow'];
		}

		if (!empty($calc['Playable'])) {
			$this->updatePlayTracking($row, $productId, $calc);
			$row['GameCount']++;
			$totalWant += $calc['Want'];
			$GrandTotalWant += $calc['Want'];

			if ($calc['Active'] == true) {
				$row['Active'] = true;
				$row['ActiveCount']++;
			} else {
				$row['InactiveCount']++;
				if ($calc['GrandTotal'] == 0) {
					$row['UnplayedInactiveCount']++;
				}
			}

			if ($calc['GrandTotal'] == 0) {
				$row['UnplayedCount']++;
			}

			if ($calc['Status'] !== 'Done') {
				$row['IncompleteCount']++;
			}
		}
	}

	private function updatePlayTracking(array &$row, string $productId, array $calc): void
	{
		// Least play
		if (!isset($row['leastPlay']['ID']) || $calc['GrandTotal'] < $row['leastPlay']['hours']) {
			$row['leastPlay'] = [
				'ID' => $productId,
				'Name' => $calc['Title'],
				'hours' => $calc['GrandTotal'],
			];
		}

		// Most play
		if (!isset($row['mostPlay']['ID']) || $calc['GrandTotal'] > $row['mostPlay']['hours']) {
			$row['mostPlay'] = [
				'ID' => $productId,
				'Name' => $calc['Title'],
				'hours' => $calc['GrandTotal'],
			];
		}
	}

	private function finalizeRowStats(array &$row, int $totalWant): void
	{
		if (!isset($row['leastPlay']['ID'])) {
			$row['leastPlay'] = ['ID' => '', 'Name' => '', 'hours' => ''];
		}
		if (!isset($row['mostPlay']['ID'])) {
			$row['mostPlay'] = ['ID' => '', 'Name' => '', 'hours' => ''];
		}

		$row['PayHr'] = ($row['TotalHours'] == 0 ? 0 : $row['Paid'] / ($row['TotalHours'] / 3600));
		$row['PlayVPay'] = $row['ModPaid'] - $row['TotalHistoricPlayed'];

		if ($row['GameCount'] > 0) {
			$row['AvgWant'] = $totalWant / $row['GameCount'];
			$row['AvgCost'] = $row['Paid'] / $row['GameCount'];
			$row['PctPlayed'] = (1 - $row['UnplayedCount'] / $row['GameCount']) * 100;
		} else {
			$row['AvgWant'] = 0;
			$row['AvgCost'] = 0;
			$row['PctPlayed'] = 100;
		}
	}

	private function updateTotalWithRow(array &$total, array $row): void
	{
		foreach ([
			'Paid', 'ModPaid', 'ItemCount', 'GameCount',
			'TotalLaunch', 'TotalMSRP', 'TotalHistoric', 'TotalHours',
			'TotalLaunchPlayed', 'TotalMSRPPlayed', 'TotalHistoricPlayed',
			'InactiveCount', 'UnplayedCount', 'ActiveCount',
			'IncompleteCount', 'UnplayedInactiveCount'
		] as $field) {
			$total[$field] += $row[$field];
		}

		if ($row['Active'] == 1) {
			$total['Active'] = 1;
		}
	}

	private function updateBeatAverageStats(array &$top, array $total): void
	{
		foreach ($top as $key => &$row) {
			if ($row['PctPlayed'] > ($total['BeatAvg'] ?? 0)) {
				$row['BeatAvg'] = 0;
			} else {
				$row['BeatAvg'] = ceil((($total['BeatAvg'] ?? 0) / 100) / (1 / $row['GameCount'])) - ($row['GameCount'] - $row['UnplayedCount']);
			}

			if ($row['PctPlayed'] > ($total['BeatAvg2'] ?? 0)) {
				$row['BeatAvg2'] = 0;
			} else {
				$row['BeatAvg2'] = ceil((($total['BeatAvg2'] ?? 0) / 100) / (1 / $row['GameCount'])) - ($row['GameCount'] - $row['UnplayedCount']);
			}

			if ($row['BeatAvg'] == 1 || $row['BeatAvg2'] == 1) {
				$row['Filter'] = 1;
			} else {
				$row['Filter'] = 0;
			}
		}
	}

	private function buildBundleTopList(): array
	{
		$top = [];
		
		foreach($this->dataSet->getPurchases() as $row) {
			if($row['TransID']==$row['BundleID'] && isset($row['ProductsinBunde'])){
				$top[$row['TransID']]['ID']=$row['TransID'];
				$top[$row['TransID']]['Title']=$row['Title'];
				$top[$row['TransID']]['PurchaseDate']=$row['PurchaseDate'];
				$top[$row['TransID']]['PurchaseTime']=$row['PurchaseTime'];
				$top[$row['TransID']]['PurchaseSequence']=$row['Sequence'];
				$top[$row['TransID']]['Paid']=$row['Paid'];
				$top[$row['TransID']]['Products']=$row['ProductsinBunde'];
				
				$top[$row['TransID']]['RawData']=$row;
			}
		}
		return $top;
	}
	
	private function buildKeywordTopList(): array
	{
		// Use Keywords class to fetch all keywords
		$keywords = $this->dataSet->getKeywords();
		
		$top = [];
		$keywordSeen = [];

		foreach ($keywords as $productId => $types) {
			foreach ($types as $type => $keywordList) {
				foreach ($keywordList as $keyword) {
					$keyID = strtolower($keyword);

					// First time seeing this keyword? Initialize
					if (!in_array($keyID, $keywordSeen, true)) {
						$keywordSeen[] = $keyID;
						$top[$keyID]['ID']    = $keyID;
						$top[$keyID]['Title'] = $keyword;
					}

					// Initialize purchase fields if missing
					if (!isset($top[$keyID]['PurchaseDate'])) {
						$top[$keyID]['PurchaseDate']    = 0;
						$top[$keyID]['PurchaseTime']    = 0;
						$top[$keyID]['PurchaseSequence'] = 0;
						$top[$keyID]['Paid']            = 0;
					}

					// Get purchase date/time from calculations
					$purchaseTime = $this->toTimestamp($this->dataSet->getCalculations()[$productId]['PurchaseDateTime'] ?? null);

					if ($purchaseTime < $top[$keyID]['PurchaseDate']) {
						$top[$keyID]['PurchaseDate'] = $purchaseTime;
					}

					// Add paid amount
					$top[$keyID]['Paid'] += $this->dataSet
						->getCalculations()[$productId]['AltSalePrice'];

					// Track which products had this keyword
					$top[$keyID]['Products'][$productId] = $productId;

					// Keep raw data for now
					$top[$keyID]['RawData'] = [
						'ProductID' => $productId,
						'KwType'    => $type,
						'Keyword'   => $keyword
					];
				}
			}
		}

		// Sort by keyword ID
		$sortKeys = array_map('strtolower', array_column($top, 'ID'));
		array_multisort($sortKeys, SORT_ASC, $top);

		// Add "None" keyword
		$top = $this->addNoneKeyword($top);

		return $top;
	}
	
	private function addNoneKeyword(array $top): array
	{
		/* Ceate Row for NO KEYWRODS */
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			if(!isset($this->dataSet->getCalculations()['allKeywords']) OR $this->dataSet->getCalculations()['allKeywords']==""){
				$top['None']['ID']="None";
				$top['None']['Title']="No Keywords";
				if(!isset($top['None']['PurchaseDate'])){
					$top['None']['PurchaseDate']=0;
					$top['None']['PurchaseTime']=0;
					$top['None']['PurchaseSequence']=0;
					$top['None']['Paid']=0;
				}
				if(isset($row['PurchaseDateTime'])){
					$getPurchaseTime = $this->toTimestamp($row['PurchaseDateTime'] ?? null);
				} else {
					$getPurchaseTime=0;
				}
				if($getPurchaseTime < $top['None']['PurchaseDate']){
					$top['None']['PurchaseDate']=$getPurchaseTime;
				}
				
				$top['None']['Paid']+=$row['AltSalePrice'];
				$top['None']['Products'][$row['Game_ID']]=$row['Game_ID'];
			}
			if(isset($top['None'])){
				$top['None']['RawData']="";
			}
		}
		return $top;
	}
	
	private function buildSeriesTopList($minGroupSize): array
	{
		$SeriesList=array();
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			$keyID=strtolower($row['Series']);
			if(!in_array($keyID,$SeriesList)){
				$SeriesList[$keyID]=$keyID;
				$top[$keyID]['ID']=$keyID;
				$top[$keyID]['Title']=$row['Series'];
				//$top[$keyID]['numGames']=0;
				//$top[$keyID]['Debug']="";
			}
			//TODO: Something is wrong with the date value in Series
			if(!isset($top[$keyID]['PurchaseDate'])){
				$top[$keyID]['PurchaseDate']=time();
				$top[$keyID]['PurchaseTime']=0;
				$top[$keyID]['PurchaseSequence']=0;
				$top[$keyID]['Paid']=0;
			}
			if(isset($row['PurchaseDateTime'])){
				$getPurchaseTime = $this->toTimestamp($row['PurchaseDateTime'] ?? null);
			} else {
				$getPurchaseTime=0; 
			}
			if($getPurchaseTime<$top[$keyID]['PurchaseDate']){
				$top[$keyID]['PurchaseDate']=$getPurchaseTime;
			}
			
			$top[$keyID]['Paid']+=$row['AltSalePrice'];
			$top[$keyID]['Products'][$row['Game_ID']]=$row['Game_ID'];
			
			if(!isset($top[$keyID]['numGames'])){
				$top[$keyID]['numGames']=0;
			}
			if ($row['CountGame']==true && $row['Playable']==true){
			//if ($row['Playable']==true){
				$top[$keyID]['numGames']++;
				//$top[$keyID]['Debug'].="<br> Count " . $row['Title'] . " = " . $top[$keyID]['numGames'] . "<br>";
			
			}
			
			//$top[$keyID]['RawData'][]=$row;
		}
		
		/* Don't Include Series with only one game */
		foreach ($top as $key => $row) {
			//if(count($row['Products'])>1) {
			if($row['numGames']>$minGroupSize-1) {
				$Sortby1[$key]  = strtolower($row['ID']);
			} else {
				unset($top[$key]);
				unset($SeriesList[$keyID]);
			}
		}
		
		array_multisort($Sortby1, SORT_ASC, $top);
		/* */
		
		/* Create a recored for all Single Games */
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			if(!in_array($keyID,$SeriesList)){
				$top['None']['ID']="None";
				$top['None']['Title']="Single Game";
				if(!isset($top['None']['PurchaseDate'])){
					$top['None']['PurchaseDate']=time();
					$top['None']['PurchaseTime']=0;
					$top['None']['PurchaseSequence']=0;
					$top['None']['Paid']=0;
				}
				if(isset($row['PurchaseDateTime'])){
					$getPurchaseTime = $this->toTimestamp($row['PurchaseDateTime'] ?? null);
				} else {
					$getPurchaseTime=0;
				}
				if($getPurchaseTime<$top['None']['PurchaseDate']){
					$top['None']['PurchaseDate']=$getPurchaseTime;
				}
				
				$top['None']['Paid']+=$row['AltSalePrice'];
				$top['None']['Products'][$row['Game_ID']]=$row['Game_ID'];
			}
			if(isset($top['None'])){
				$top['None']['RawData']="";
			}
		}
		
		return $top;
	}
	
	private function buildStoreTopList(): array
	{
		$storeList=array();
		foreach($this->dataSet->getPurchases() as $row) {
			$StoreID=strtolower($row['Store']);
			if($row['TransID']==$row['BundleID'] && isset($row['ProductsinBunde'])){
				if(!in_array($StoreID,$storeList)){
					$storeList[]=$StoreID;
					$top[$StoreID]['ID']=$StoreID;
					$top[$StoreID]['Title']=$row['Store'];
					$top[$StoreID]['PurchaseDate']=$row['PurchaseDate'];
					$top[$StoreID]['PurchaseTime']=$row['PurchaseTime'];
					$top[$StoreID]['PurchaseSequence']=$row['Sequence'];
					$top[$StoreID]['Paid']=0;
					$top[$StoreID]['Products']=array();
					
					//$top[$StoreID]['RawData']=$row;
				} 
				
				$top[$StoreID]['Paid']+=$row['Paid'];
				$top[$StoreID]['Products']=array_merge((array)$top[$StoreID]['Products'],(array)$row['ProductsinBunde']);
			}
		}
		
		return $top;
	}
	
	private function buildSimpleTopList($group): array
	{
		$GroupList=array();
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			foreach ($row[$group] as $setkey => $set) {
				$GroupID = isset($set) && $set !== null ? strtolower($set) : "";
				if(!in_array($GroupID,$GroupList)){
					$GroupList[]=$GroupID;
					$top[$GroupID]['ID']=$GroupID;
					$top[$GroupID]['Title']=$set;
					$top[$GroupID]['PurchaseDate']=0;
					$top[$GroupID]['PurchaseTime']=0;
					$top[$GroupID]['PurchaseSequence']=0;
				}
				if (!isset($top[$GroupID]['Paid'])) {
					$top[$GroupID]['Paid']=0;
					$top[$GroupID]['Products']=array();
				}
				
				$top[$GroupID]['Paid']+=$row['Paid'];
				$top[$GroupID]['Products'][$row['Game_ID']]=$row['Game_ID'];
			}
		}
		
		return $top;
	}
	
	private function buildRatingTopList($group,$factor): array
	{
		if($group=="Meta" OR $group=="Meta10"){$group="Metascore";}
		if($group=="UMeta" OR $group=="UMeta10"){$group="UserMetascore";}
		if($group=="SteamR" OR $group=="SteamR10"){$group="SteamRating";}
		$GroupList=array();
		//$d=0;
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			$set=ceil(((double)$row[$group]/100)*$factor);
			$GroupID=strtolower($set);
			if(!in_array($GroupID,$GroupList)){
				$GroupList[]=$GroupID;
				$top[$GroupID]['ID']=$GroupID;
				$top[$GroupID]['Title']=$set;
				$top[$GroupID]['PurchaseDate']=0;
				$top[$GroupID]['PurchaseTime']=0;
				$top[$GroupID]['PurchaseSequence']=0;
			}
			if (!isset($top[$GroupID]['Paid'])) {
				$top[$GroupID]['Paid']=0;
				$top[$GroupID]['Products']=array();
			}
			
			$top[$GroupID]['Paid']+=$row['Paid'];
			$top[$GroupID]['Products'][$row['Game_ID']]=$row['Game_ID'];
		}
		
		return $top;
	}
	
	private function buildDateTopList($group): array
	{
		if($group=="PYear"     OR $group=="LYear") {$dateformat="Y";}
		if($group=="PMonthNum" OR $group=="LMonthNum") {$dateformat="m";}
		if($group=="PMonth"    OR $group=="LMonth") {$dateformat="Y-m";}
		
		if($group=="PYear" OR $group=="PMonth" OR $group=="PMonthNum"){$group="PurchaseDateTime";}
		if($group=="LYear" OR $group=="LMonth" OR $group=="LMonthNum"){$group="LaunchDate";}
		
		$BundleList=array();
		$GroupList=array();
		foreach ($this->dataSet->getCalculations() as $key => $row) {
			
			//$GroupID=date($dateformat,$row[$group]);
			
			if($group=="LaunchDate") {
				$GroupID=date($dateformat,$row[$group]->getTimestamp());
			} elseif ($group=="PurchaseDateTime") {
				$GroupID=date($dateformat,$row[$group]->getTimestamp());
			}
			if(!in_array($GroupID,$GroupList)){
				$GroupList[]=$GroupID;
				$top[$GroupID]['ID']=$GroupID;
				$top[$GroupID]['Title']=$GroupID;
				$top[$GroupID]['PurchaseDate']=0;
				$top[$GroupID]['PurchaseTime']=0;
				$top[$GroupID]['PurchaseSequence']=0;
			}
			if (!isset($top[$GroupID]['Paid'])) {
				$top[$GroupID]['Paid']=0;
				$top[$GroupID]['Products']=array();
			}
			//TODO: Add logic to check if the bundle game was included in has already been counted.
			//if(!in_array(xxx,$BundleList)){}
			
			$top[$GroupID]['Paid']+=$row['Paid'];
			$top[$GroupID]['Products'][$row['Game_ID']]=$row['Game_ID'];
		}
		
		return $top;
	}

	private function toTimestamp($value): int
	{
		if ($value instanceof \DateTimeInterface) {
			return $value->getTimestamp();
		}
		if (is_numeric($value)) {
			return (int) $value;
		}
		return 0;
	}
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getTopList.class") {
	$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? "..";
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Top List Inc Test";
	echo Get_Header($title);
	
	//TODO: only top bundles are valid but all bundles are returned by the lookup prompt.
	$lookupgame=lookupTextBox("Product", "ProductID", "id", "Trans", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupgame["header"];
	if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		?>
		Please specify a game by ID.
		<form method="Get">
			<?php echo $lookupgame["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupgame["lookupBox"];
	} else {
		$listObj=new TopList();
		$toplist=$listObj->buildTopListArray("");
		echo arrayTable($toplist[$_GET['id']]);
	}
	echo Get_Footer();
}
?>