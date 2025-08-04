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

function getTopList($group,$connection=false,$calc=false,$minGroupSize=2){
	if($connection==false){
		$conn = get_db_connection();
	} else {
		$conn = $connection;
	}
	
	if($calc==false){
		$data = new dataSet();
		$calculations = $data->getCalculations();
	} else {
		$calculations=$calc;
	}
	//$calculations=reIndexArray($calculations,"Game_ID");
	
	switch($group){
		default:
		case "Bundle":
			//$items=getAllItems("",$conn);
			//$purchases=getPurchases("",$conn,"","");
			$purchaseobj=new Purchases("",$conn,"","");
			$purchases=$purchaseobj->getPurchases();
			
			foreach($purchases as $row) {
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
				/* Singles */
				/* */
			}
			break;
		case "Keyword":
			$sql="SELECT * FROM `gl_keywords`";
			if($result = $conn->query($sql)) {
				$KeywordList=array();
				while($row = $result->fetch_assoc()) {
					$keyID=strtolower($row['Keyword']);
					if(!in_array($keyID,$KeywordList)){
						$KeywordList[]=$keyID;
						//$keywords[$row2['ProductID']][$row2['KwType']]=$row2['Keyword'] ;
						$top[$keyID]['ID']=$keyID;
						$top[$keyID]['Title']=$row['Keyword'];
					}

					if(!isset($top[$keyID]['PurchaseDate'])){
						$top[$keyID]['PurchaseDate']=0;
						$top[$keyID]['PurchaseTime']=0;
						$top[$keyID]['PurchaseSequence']=0;
						$top[$keyID]['Paid']=0;
					}
					$getPurchaseTime=$calculations[$row['ProductID']]['PurchaseDateTime']->getTimestamp();
					if($getPurchaseTime<$top[$keyID]['PurchaseDate']){
						$top[$keyID]['PurchaseDate']=$getPurchaseTime; //@codeCoverageIgnore
					}
					
					$top[$keyID]['Paid']+=$calculations[$row['ProductID']]['AltSalePrice'];
					$top[$keyID]['Products'][$row['ProductID']]=$row['ProductID'];
					
					$top[$keyID]['RawData']=$row;
					
				} 
				
				foreach ($top as $key => $row) {
					$Sortby1[$key]  = strtolower($row['ID']);
				}
				array_multisort($Sortby1, SORT_ASC, $top);
				
				/* Ceate Row for NO KEYWRODS */
				foreach ($calculations as $key => $row) {
					if(!isset($calculations['allKeywords']) OR $calculations['allKeywords']==""){
						$top['None']['ID']="None";
						$top['None']['Title']="No Keywords";
						if(!isset($top['None']['PurchaseDate'])){
							$top['None']['PurchaseDate']=0;
							$top['None']['PurchaseTime']=0;
							$top['None']['PurchaseSequence']=0;
							$top['None']['Paid']=0;
						}
						if(isset($row['PurchaseDateTime'])){
							$getPurchaseTime=$row['PurchaseDateTime']->getTimestamp();
						} else {
							$getPurchaseTime=0; //@codeCoverageIgnore
						}
						if($getPurchaseTime<$top['None']['PurchaseDate']){
							$top['None']['PurchaseDate']=$getPurchaseTime; //@codeCoverageIgnore
						}
						
						
						$top['None']['Paid']+=$row['AltSalePrice'];
						$top['None']['Products'][$row['Game_ID']]=$row['Game_ID'];
					}
					if(isset($top['None'])){
						$top['None']['RawData']="";
					}
				}
				/* */
			} else {
				//@codeCoverageIgnoreStart
				$keywords=false;
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
				//@codeCoverageIgnoreEnd
			}
	
			break;
		case "Series":
			$SeriesList=array();
			foreach ($calculations as $key => $row) {
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
					$getPurchaseTime=$row['PurchaseDateTime']->getTimestamp();
				} else {
					$getPurchaseTime=0; //@codeCoverageIgnore
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
			foreach ($calculations as $key => $row) {
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
						$getPurchaseTime=$row['PurchaseDateTime']->getTimestamp();
					} else {
						$getPurchaseTime=0; //@codeCoverageIgnore
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
			/* */
			
			break;
		case "Store":
			//$purchases=getPurchases("",$conn,"","");
			$purchaseobj=new Purchases("",$conn);
			$purchases=$purchaseobj->getPurchases();
			$storeList=array();
			foreach($purchases as $row) {
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
				/* Singles */
				/* */
			}
			break;
		case "DRM":
		case "OS":
		case "Library":
			$GroupList=array();
			//$d=0;
			foreach ($calculations as $key => $row) {
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
			break;
		case "Meta10": //Metascore (1-10)
		case "UMeta10": //User Metascore (1-10)
		case "SteamR10": //Steam Rating (1-10)
			$factor=10;
		case "Review":
		case "Want":
		case "Meta": //Metascore
		case "UMeta": //User Metascore
		case "SteamR": //Steam Rating
			if(!isset($factor)){
				$factor=100;
			}
			if($group=="Meta" OR $group=="Meta10"){$group="Metascore";}
			if($group=="UMeta" OR $group=="UMeta10"){$group="UserMetascore";}
			if($group=="SteamR" OR $group=="SteamR10"){$group="SteamRating";}
			$GroupList=array();
			//$d=0;
			foreach ($calculations as $key => $row) {
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
			break;
		case "PYear": //Purchase Year
		case "LYear": //Launch Year
		case "PMonth": //Purchase Month
		case "LMonth": //Launch Month
		case "PMonthNum": //Purchase Month Number
		case "LMonthNum": //Launch Month Number
			if($group=="PYear"     OR $group=="LYear") {$dateformat="Y";}
			if($group=="PMonthNum" OR $group=="LMonthNum") {$dateformat="m";}
			if($group=="PMonth"    OR $group=="LMonth") {$dateformat="Y-m";}
			
			if($group=="PYear" OR $group=="PMonth" OR $group=="PMonthNum"){$group="PurchaseDateTime";}
			if($group=="LYear" OR $group=="LMonth" OR $group=="LMonthNum"){$group="LaunchDate";}
			
			$BundleList=array();
			$GroupList=array();
			foreach ($calculations as $key => $row) {
				if($group=="LaunchDate") {
					//$usedate=strtotime( $row[$group]);
					$GroupID=date($dateformat,$row[$group]->getTimestamp());
				} elseif ($group=="PurchaseDateTime") {
					$GroupID=date($dateformat,$row[$group]->getTimestamp());
				} else {
					//$usedate=$row[$group];
					//Unreachable
					$GroupID=date($dateformat,0+$row[$group]); //@codeCoverageIgnore
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
			break;
		

		//case "PWeek": //Purchase Week
		//case "PWeekNum": //Purchase Week Number
		//case "LWeek": //Launch Week
		//case "LWeekNum": //Launch Week Number
		//TODO: Group by Developer Publisher and Alphasort don't work yet.
		//case "Developer": 
		//case "Publisher": 
		//case "AlphaSort": //First Letter
			break;
	}
	
	if($connection==false){
		$conn->close();	
	}
	
	$GrandTotalWant=0;
	//$TotalLaunch=0;
	//$TotalMSRP=0;
	//$TotalHistoric=0;
	//$TotalHours=0;
	
	foreach ($top as $key => &$row) {
		//TODO: Need to acually calulate ModPaid
		$row['ModPaid']=$row['Paid']; 
		$row['ItemCount']=0;
		$row['GameCount']=0;
		$totalWant=0;
		$row['Active']=false;
		$row['TotalLaunch']=0;
		$row['TotalMSRP']=0;
		$row['TotalHistoric']=0;
		$row['TotalHours']=0;
		$row['TotalLaunchPlayed']=0;
		$row['TotalMSRPPlayed']=0;
		$row['TotalHistoricPlayed']=0;
		$row['InactiveCount']=0;
		$row['UnplayedCount']=0;
		$row['ActiveCount']=0;
		$row['IncompleteCount']=0;
		$row['UnplayedInactiveCount']=0;
		
		
		foreach($row['Products'] as $product) {
			if ($calculations[$product]['CountGame']==true){
				$row['ItemCount']++;

				$row['TotalLaunch']+=$calculations[$product]['LaunchPrice'];
				$row['TotalMSRP']+=$calculations[$product]['MSRP'];
				$row['TotalHistoric']+=$calculations[$product]['HistoricLow'];
				$row['TotalHours']+=$calculations[$product]['GrandTotal'];

				//$TotalLaunch+=$calculations[$product]['LaunchPrice'];
				//$TotalMSRP+=$calculations[$product]['MSRP'];
				//$TotalHistoric+=$calculations[$product]['HistoricLow'];
				//$TotalHours+=$calculations[$product]['GrandTotal'];
				
				if($calculations[$product]['GrandTotal']>0){
					$row['TotalLaunchPlayed']+=$calculations[$product]['LaunchPrice'];
					$row['TotalMSRPPlayed']+=$calculations[$product]['MSRP'];
					$row['TotalHistoricPlayed']+=$calculations[$product]['HistoricLow'];
				}
				
				//if($calculations[$product]['Type']=="Game"){
				if($calculations[$product]['Playable']==true){
					if(!isset($row['leastPlay']['ID'])){
						$row['leastPlay']['ID']=$product;
						$row['leastPlay']['Name']=$calculations[$product]['Title'];
						$row['leastPlay']['hours']=$calculations[$product]['GrandTotal'];
					} elseif($calculations[$product]['GrandTotal']<$row['leastPlay']['hours']){
						$row['leastPlay']['ID']=$product;
						$row['leastPlay']['Name']=$calculations[$product]['Title'];
						$row['leastPlay']['hours']=$calculations[$product]['GrandTotal'];
					}
					if(!isset($row['mostPlay']['ID'])){
						$row['mostPlay']['ID']=$product;
						$row['mostPlay']['Name']=$calculations[$product]['Title'];
						$row['mostPlay']['hours']=$calculations[$product]['GrandTotal'];
					} elseif($calculations[$product]['GrandTotal']>$row['mostPlay']['hours']){
						$row['mostPlay']['ID']=$product;
						$row['mostPlay']['Name']=$calculations[$product]['Title'];
						$row['mostPlay']['hours']=$calculations[$product]['GrandTotal'];
					}
					$row['GameCount']++;
					$totalWant+=$calculations[$product]['Want'];
					$GrandTotalWant+=$calculations[$product]['Want'];
					
					if($calculations[$product]['Active']==true){
						$row['Active']=true;
						$row['ActiveCount']++;
					} else {
						$row['InactiveCount']++;
						if($calculations[$product]['GrandTotal']==0){
							$row['UnplayedInactiveCount']++;
						}
					}
					
					if($calculations[$product]['GrandTotal']==0){
						$row['UnplayedCount']++;
					}
					
					if($calculations[$product]['Status']<>"Done"){
						$row['IncompleteCount']++;
					}
				}
			}
		}
		
		if(!isset($row['leastPlay']['ID'])){
			$row['leastPlay']['ID']="";
			$row['leastPlay']['Name']="";
			$row['leastPlay']['hours']="";
		}
		if(!isset($row['mostPlay']['ID'])){
			$row['mostPlay']['ID']="";
			$row['mostPlay']['Name']="";
			$row['mostPlay']['hours']="";
		}

		if($row['TotalHours']<>0){
			$row['PayHr']=$row['Paid']/($row['TotalHours']/60/60);
		} else {
			$row['PayHr']=0;
		}
		
		if(!isset($total)){
			$total['ID']="Total";
			$total['Title']="Total";
			$total['PurchaseDate']=0;
			$total['PurchaseTime']=0;
			$total['PurchaseSequence']=0;
			$total['Paid']=0;
			$total['ModPaid']=0;
			$total['Products']=array();
			$total['GameCount']=0;
			$total['ItemCount']=0;
			$total['Active']=0;
			$total['TotalLaunch']=0;
			$total['TotalMSRP']=0;
			$total['TotalHistoric']=0;
			$total['TotalHours']=0;
			$total['TotalLaunchPlayed']=0;
			$total['TotalMSRPPlayed']=0;
			$total['TotalHistoricPlayed']=0;
			$total['InactiveCount']=0;
			$total['UnplayedCount']=0;
			$total['ActiveCount']=0;
			$total['IncompleteCount']=0;
			$total['UnplayedInactiveCount']=0;
			$total['Filter']=0;
		}

		$row['PlayVPay']=$row['ModPaid']-$row['TotalHistoricPlayed'];
		if($row['GameCount']<>0){
			$row['AvgWant']=$totalWant/$row['GameCount'];
			$row['AvgCost']=$row['Paid']/$row['GameCount'];
			$row['PctPlayed']=(1-$row['UnplayedCount']/$row['GameCount'])*100;
			$total['PctiPlayed2source'][]=$row['PctPlayed'];
		} else {
			$row['AvgWant']=0;
			$row['AvgCost']=0;
			$row['PctPlayed']=100;
		}
		
		if($row['Active']==1){
			$total['Active']=1;
		}

		$total['Paid']+=$row['Paid'];
		$total['ModPaid']+=$row['ModPaid'];
		$total['ItemCount']+=$row['ItemCount'];
		$total['GameCount']+=$row['GameCount'];
		$total['TotalLaunch']+=$row['TotalLaunch'];
		$total['TotalMSRP']+=$row['TotalMSRP'];
		$total['TotalHistoric']+=$row['TotalHistoric'];
		$total['TotalHours']+=$row['TotalHours'];
		$total['TotalLaunchPlayed']+=$row['TotalLaunchPlayed'];
		$total['TotalMSRPPlayed']+=$row['TotalMSRPPlayed'];
		$total['TotalHistoricPlayed']+=$row['TotalHistoricPlayed'];
		$total['InactiveCount']+=$row['InactiveCount'];
		$total['UnplayedCount']+=$row['UnplayedCount'];
		$total['ActiveCount']+=$row['ActiveCount'];
		$total['IncompleteCount']+=$row['IncompleteCount'];
		$total['UnplayedInactiveCount']+=$row['UnplayedInactiveCount'];

		if(!isset($total['leastPlay']['ID'])){
			if($row['leastPlay']['ID']==""){
				//@codeCoverageIgnoreStart
				$total['leastPlay']['ID']=null;	
				$total['leastPlay']['Name']=null;
				$total['leastPlay']['hours']=null;
				//@codeCoverageIgnoreEnd
			} else {
				$total['leastPlay']['ID']=$row['leastPlay']['ID'];
				$total['leastPlay']['Name']=$row['leastPlay']['Name'];
				$total['leastPlay']['hours']=$row['leastPlay']['hours'];
			}
		} elseif($row['leastPlay']['hours']<$total['leastPlay']['hours'] && $row['leastPlay']['ID']<>""){
			$total['leastPlay']['ID']=$row['leastPlay']['ID'];
			$total['leastPlay']['Name']=$row['leastPlay']['Name'];
			$total['leastPlay']['hours']=$row['leastPlay']['hours'];
		}
		if(!isset($total['mostPlay']['ID'])){
			if($row['mostPlay']['ID']=="") {
				//@codeCoverageIgnoreStart
				$total['mostPlay']['ID']=null;
				$total['mostPlay']['Name']=null;
				$total['mostPlay']['hours']=null;
				//@codeCoverageIgnoreEnd
			} else {
				$total['mostPlay']['ID']=$row['mostPlay']['ID'];
				$total['mostPlay']['Name']=$row['mostPlay']['Name'];
				$total['mostPlay']['hours']=$row['mostPlay']['hours'];
			}
		} elseif($row['mostPlay']['hours']>$total['mostPlay']['hours'] && $row['mostPlay']['ID']<>""){
			$total['mostPlay']['ID']=$row['mostPlay']['ID'];
			$total['mostPlay']['Name']=$row['mostPlay']['Name'];
			$total['mostPlay']['hours']=$row['mostPlay']['hours'];
		}
	}

	$total['PlayVPay']=$total['ModPaid']-$total['TotalHistoricPlayed'];
	$total['PayHr'] = ($total['TotalHours']==0 ? 0 : $total['Paid']/($total['TotalHours']/60/60));
	$total['BeatAvg'] = ($total['GameCount']==0 ? 0 : $total['PctPlayed']=(1-$total['UnplayedCount']/$total['GameCount'])*100);
	$total['BeatAvg2']= (!isset($total['PctiPlayed2source']) ? 0 : array_sum($total['PctiPlayed2source']) / count($total['PctiPlayed2source']));
	
	$total['AvgWant']=($total['GameCount']==0 ? 0 : $GrandTotalWant/$total['GameCount']);
	$total['AvgCost']=($total['GameCount']==0 ? 0 : $total['Paid']/$total['GameCount']);
	
	foreach ($top as $key => &$row) {
		//iferror(if('% played'>'BeatAvg',0,roundup('BeatAvg'/(1/'# of Games'))-('# of Games'-'Unplayed Games')))
		if($row['PctPlayed']>$total['BeatAvg']){
			$row['BeatAvg']=0;
		} else {
			$row['BeatAvg']=ceil(($total['BeatAvg']/100)/(1/$row['GameCount']))-($row['GameCount']-$row['UnplayedCount']);
		}
		if($row['PctPlayed']>$total['BeatAvg2']){
			$row['BeatAvg2']=0;
		} else {
			$row['BeatAvg2']=ceil(($total['BeatAvg2']/100)/(1/$row['GameCount']))-($row['GameCount']-$row['UnplayedCount']);
		}
		if($row['BeatAvg']==1 OR $row['BeatAvg2']==1){
			$row['Filter']=1;
		} else {
			$row['Filter']=0;
		}
	}
	
	$top['Total']=$total;
		
	return $top;
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getTopList.inc") {
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
		//$actcalculations=reIndexArray(getTopList(""),"GameID");
		$toplist=getTopList("");
		echo arrayTable($toplist[$_GET['id']]);
	}
	echo Get_Footer();
}
?>