<?php
//TODO: Paid total should include DLC (this will make free games with paid DLC show as not free)
//TODO: Re-evaluate how parent game is calculated
$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/PriceCalculation.class.php";
include_once $GLOBALS['rootpath']."/inc/getGames.class.php";

function getCalculations($gameID="",$connection=false,$start=false,$end=false){
	
	if (isset($GLOBALS["CALCULATIONS"]))
	{
		//TODO: Global storage works for default parameters but is unpredictable when parameters are used.
		return $GLOBALS["CALCULATIONS"];
	} else {
		if($connection==false){
			$conn = get_db_connection();
		} else {
			$conn = $connection;
		}
		
		$games=getGames($gameID,$conn);
		$items=getAllItems($gameID,$conn);
		$settings=getsettings($conn);
		$history=getHistoryCalculations($gameID,$conn);
		$activity=getActivityCalculations($gameID,$history,$conn);
		$keywords=getKeywords($gameID,$conn);
		//$purchases=getPurchases("",$conn,$items,$games);
		$purchaseobj=new Purchases("",$conn,$items,$games);
		$purchases=$purchaseobj->getPurchases();

		foreach ($history as $row) {
			//Group the history records by GameID
			$historyByGame[$row['ParentGameID']][]=$row;
		}

		if($connection==false){
			$conn->close();	
		}
		
		$gameIndex=makeIndex($games,"Game_ID");
		$purchaseIndex=makeIndex($purchases,"TransID");

		$itemsbyGame=regroupArray($items,"ProductID");
		
		foreach ($games as &$game) {
			if (isset($activity[$game['Game_ID']])){
				$game['firstPlayDateTime']=$activity[$game['Game_ID']]['firstPlayDateTime'];
				$game['firstplay']=$activity[$game['Game_ID']]['firstplay'];
				$game['firstplaysort']=strtotime($activity[$game['Game_ID']]['firstplay']);
				$game['lastPlayDateTime']=$activity[$game['Game_ID']]['lastPlayDateTime'];
				$game['lastplay']=$activity[$game['Game_ID']]['lastplay'];
				$game['lastplaySort']=strtotime($activity[$game['Game_ID']]['lastplay']);
				$game['Achievements']=$activity[$game['Game_ID']]['Achievements'];
				$game['Status']=$activity[$game['Game_ID']]['Status'];
				$game['Review']=$activity[$game['Game_ID']]['Review'];
				$game['LastBeat']=$activity[$game['Game_ID']]['LastBeat'];
				$game['totalHrs']=$activity[$game['Game_ID']]['totalHrs'];
				$game['GrandTotal']=$activity[$game['Game_ID']]['GrandTotal'];
			} else {
				$game['firstplay']=null;
				$game['firstplaysort']=0;
				$game['lastplay']=null;
				$game['lastplaySort']=0;
				$game['Achievements']=0;
				$game['Status']="Unplayed";
				$game['Review']="";
				$game['LastBeat']="";
				$game['totalHrs']=0;
				$game['GrandTotal']=0;
			}
			
			if (isset($gameIndex[$game['ParentGameID']])){
				$game['ParentGame']=$games[$gameIndex[$game['ParentGameID']]]['Title'];
			} else {
				$game['ParentGame']="Not Found"; //@codeCoverageIgnore
			}
			
			if ($game['SteamAchievements']=="") {$game['SteamAchievements']=0;}
			$game['AchievementsLeft']=$game['SteamAchievements']-$game['Achievements'];
			$game['AchievementsPct']=0;
			if($game['SteamAchievements']<>0){
				$game['AchievementsPct']=($game['Achievements']/$game['SteamAchievements'])*100;
			} 
			$game['Active']=$settings['status'][$game['Status']]['Active'] ?? False;
			$game['CountGame']=$settings['status'][$game['Status']]['Count'] ?? False;
			$game['allKeywords']="";
			
			if (isset($keywords[$game['Game_ID']])){
				$game['keywords']=$keywords[$game['Game_ID']];
				$lastvalue="";
				foreach ($keywords[$game['Game_ID']] as $type => $value){
					foreach ($value as $kw) {
						if($lastvalue<>$type){
							if($lastvalue<>""){
								$game['allKeywords'] .= "\r\n";
							}
							$game['allKeywords'] .= $type .": ";
						} else {
							$game['allKeywords'] .= ", ";
						}
						$game['allKeywords'] .= $kw;
						$lastvalue=$type;
					}
				}
				trim($game['allKeywords'] ,"\n\r, ");
			} else {
				$game['keywords']['Genre']="";
			}
			
			$game['PrintBundles']="";
			$game['Platforms']="";
			$game['Paid']=0;
			$game['Inactive']=false;
			$game['Key']="";
			$game['DrmFree']=false;
			$game['DrmFreeSize']=0;
			$game['DrmFreeLibrary']="";
			$game['MainLibrary']="";
			$game['OtherLibrary']=true;

			if (isset($itemsbyGame[$game['Game_ID']])){
				foreach($itemsbyGame[$game['Game_ID']] as $record){
					if(!isset($game['Bundles'][$record['TransID']])){
						if(isset($purchases[$purchaseIndex[$record['TransID']]])){
							$useBundleID=$purchases[$purchaseIndex[$record['TransID']]]['TopBundleID'];
							if(!isset($game['TopBundleIDs'][$useBundleID])){
								$game['TopBundleIDs'][$useBundleID]=$useBundleID;
								if(!isset($game['AddedDateTime']) OR $game['AddedDateTime'] > $record['AddedDateTime']) {
									$game['FirstBundle']=$useBundleID;
									$game['AddedDateTime']=$record['AddedDateTime'];
									$game['PurchaseDateTime']=$purchases[$purchaseIndex[$record['TransID']]]['PurchaseDateTime'];
								}
								
								if($game['PrintBundles']==""){
									//$game['PrintBundles'] .= "(" . $useBundleID .") ";
									$game['PrintBundles'] .= $purchases[$purchaseIndex[$useBundleID]]['Title'];
									$game['BundlePrice']=sprintf("%.2f",$purchases[$purchaseIndex[$useBundleID]]['Paid']);
									$game['Paid'] +=$purchases[$purchaseIndex[$useBundleID]]['Paid'];
								} else {
									$game['PrintBundles'] .= "<span class='duplicate'>";
									//$game['PrintBundles'] .= "(" . $useBundleID .") ";
									$game['PrintBundles'] .= $purchases[$purchaseIndex[$useBundleID]]['Title'];
									$game['PrintBundles'] .= "</span>";
									//This line adds the paid price of all duplicate bundles.
									$game['Paid'] +=$purchases[$purchaseIndex[$useBundleID]]['Paid'];
								}
								$game['PrintBundles'] .= "| ";
							}
						} else {
							//@codeCoverageIgnoreStart
							$useBundleID=null;
							$game['PrintBundles']="Not Found";
							$game['BundlePrice']="N/A";
							//@codeCoverageIgnoreEnd
						}
					}
					$game['Bundles'][$record['TransID']] = $record['TransID'];
					if($record['Library']<>$record['DRM']){
						$game['Platforms'] .= $record['Library']." ".$record['DRM']." ". $record['OS']."\r\n";
					} else {
						$game['Platforms'] .= $record['Library']." ". $record['OS']."\r\n";
					}
					$game['OS'][$record['OS']]=$record['OS'];
					$game['Library'][$record['Library']]=$record['Library'];
					$game['DRM'][$record['DRM']]=$record['DRM'];
					
					if($record['DRM']=="DRM Free") {
						$game['DrmFree']=true;
						$game['DrmFreeSize']=$record['SizeMB'];
						$game['DrmFreeLibrary']=$record['Library'];
					} 
					
					if($game['MainLibrary']==""){
						$game['MainLibrary']=$record['Library'];
					}
					
					if($record['DRM']=="DRM Free Android"){
						$game['OtherLibrary']=false;
					}
					
					if($record['Library']=="Steam" || 
					  $record['Library']=="GOG" ||
					  $record['Library']=="Origin" ||
					  $record['Library']=="Download" ) {
						$game['MainLibrary']=$record['Library'];
						$game['OtherLibrary']=false;
					}
					
					if($record['Library']=="Inactive") {
						$game['Inactive']=true;
						$game['Key']=$record['ActivationKey'];
					}
				}
				$game['Platforms']=trim($game['Platforms'] ,"\n\r, ");
				$game['PrintBundles']=trim($game['PrintBundles'] ,"\n\r| ");
				$game['Paid']=sprintf("%.2f",$game['Paid']);
			} else {
				//@codeCoverageIgnoreStart
				$game['PrintBundles']="Not Found";
				$game['Platforms']="Not Found";
				//@codeCoverageIgnoreEnd
			}
			
			if($game['totalHrs']==0 && isset($historyByGame[$game['ParentGameID']])){
				foreach ($historyByGame[$game['ParentGameID']] as $parentgame){
					if(strtotime($parentgame['Timestamp'])>=$game['PurchaseDateTime']->getTimestamp() //strtotime($game['PrintPurchaseDate']) 
						&& $parentgame['FinalCountHours']==true){
						
						if($parentgame['Elapsed']=="") {$parentgame['Elapsed']=0;}
						$game['GrandTotal']+=$parentgame['Elapsed'];
					}
				}
			}
			
			//TODO: Lots of code freaks out for games without and associated item entry (should never happen any way but needs graceful failure)
			if(isset($game['PurchaseDateTime'])) {
				$game['DaysSincePurchaseDate']=daysSinceDate($game['PurchaseDateTime']->getTimestamp());
			} else {
				$game['DaysSincePurchaseDate']=0; //@codeCoverageIgnore
			}

			$game['SalePrice']=0;
			$game['SalePriceFormula']="";
			$game['AltSalePrice']=0;

			//Set SalePrice and AltSalePrice ****
			if(isset($game['TopBundleIDs'])){
				foreach($game['TopBundleIDs'] as $bundleID){
					foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $game2) {
						if($game2['GameID']==$game['Game_ID']){
							$game['SalePrice'] +=  $game2['SalePrice'];
							if ($game['SalePriceFormula']!="") { $game['SalePriceFormula'] .= " + "; }
							$game['SalePriceFormula'] .= $game2['SalePriceFormula'];
							$game['AltSalePrice'] +=  $game2['AltSalePrice'];
						}
					}
				}
			}
		}
		unset ($game);
		
		foreach ($games as &$game) {
			$game['LaunchPriceObj']= new PriceCalculation($game['LaunchPrice'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['MSRPPriceObj']= new PriceCalculation($game['MSRP'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['CurrentPriceObj']= new PriceCalculation($game['CurrentMSRP'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['HistoricPriceObj']= new PriceCalculation($game['HistoricLow'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['SalePriceObj']= new PriceCalculation($game['SalePrice'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['AltPriceObj']= new PriceCalculation($game['AltSalePrice'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			$game['PaidPriceObj']= new PriceCalculation($game['Paid'], $game['GrandTotal'], $game['TimeToBeat'], $game['MSRP']);
			unset($game['LaunchPrice']);
			unset($game['MSRP']);
			unset($game['CurrentMSRP']);
			unset($game['HistoricLow']);
			unset($game['SalePrice']);
			unset($game['AltSalePrice']);
			unset($game['Paid']);
			
			$game['TimeLeftToBeat']=getTimeLeft($game['TimeToBeat'],$game['GrandTotal'],$game['Status']);
			/*
			$game['TimeLeftToBeat']=$game['TimeToBeat']-($game['GrandTotal']/60/60);
			if($game['TimeLeftToBeat']<0 || $game['Status']=="Done"){
				$game['TimeLeftToBeat']=0;
			}
			*/
			
			//TODO: LastPlayORPurchaseValue includes duplicate purchases when it should only take the max of first purchase or last played.
			if(isset($game['AddedDateTime'])) {
				$game['LastPlayORPurchaseValue']=max($game['lastplaySort'],$game['AddedDateTime']->getTimestamp());
			} else {
				//@codeCoverageIgnoreStart
				$game['LastPlayORPurchaseValue']=max($game['lastplaySort'],0);
				//@codeCoverageIgnoreEnd
			}
			$game['LastPlayORPurchase']=getCleanStringDate($game['LastPlayORPurchaseValue']);
			$game['DaysSinceLastPlay']=daysSinceDate($game['lastplaySort']);
			$game['DaysSinceLastPlayORPurchase']=daysSinceDate($game['LastPlayORPurchaseValue']);
			/*
			if(date("H:i:s",$game['LastPlayORPurchaseValue']) == "00:00:00") {
				$game['LastPlayORPurchase']= date("n/j/Y",$game['LastPlayORPurchaseValue']);
			} else {
				$game['LastPlayORPurchase']= date("n/j/Y H:i:s",$game['LastPlayORPurchaseValue']);
			}
			
			if($game['lastplaySort']>0){
				$game['DaysSinceLastPlay']=floor((time()-$game['lastplaySort']) / (60 * 60 * 24));
			} else {
				$game['DaysSinceLastPlay']="";
			}
			$game['DaysSinceLastPlayORPurchase']=floor((time()-$game['LastPlayORPurchaseValue']) / (60 * 60 * 24));
			*/
		}
		unset ($game);
		
		$sortbyLaunch=getPriceSort($games,'LaunchPriceObj');
		$sortbyMSRP=getPriceSort($games,'MSRPPriceObj');
		$sortbyCurrent=getPriceSort($games,'CurrentPriceObj');
		$sortbyHistoric=getPriceSort($games,'HistoricPriceObj');
		$sortbyPaid=getPriceSort($games,'PaidPriceObj');
		$sortbySale=getPriceSort($games,'SalePriceObj');
		$sortbyAlt=getPriceSort($games,'AltPriceObj');

		$sortbyActiveLaunch=getPriceSort($games,'LaunchPriceObj',true);
		$sortbyActiveMSRP=getPriceSort($games,'MSRPPriceObj',true);
		$sortbyActiveCurrent=getPriceSort($games,'CurrentPriceObj',true);
		$sortbyActiveHistoric=getPriceSort($games,'HistoricPriceObj',true);
		$sortbyActivePaid=getPriceSort($games,'PaidPriceObj',true);
		$sortbyActiveSale=getPriceSort($games,'SalePriceObj',true);
		$sortbyActiveAlt=getPriceSort($games,'AltPriceObj',true);
		
		foreach ($games as &$game) {
			$game['LaunchHrsNext1']  =getHrsNextPosition($game['LaunchPriceObj']->getPrice()  ,$sortbyLaunch  ,$game['GrandTotal']);
			$game['MSRPHrsNext1']    =getHrsNextPosition($game['MSRPPriceObj']->getPrice()         ,$sortbyMSRP    ,$game['GrandTotal']);
			$game['CurrentHrsNext1'] =getHrsNextPosition($game['CurrentPriceObj']->getPrice()  ,$sortbyCurrent ,$game['GrandTotal']);
			$game['HistoricHrsNext1']=getHrsNextPosition($game['HistoricPriceObj']->getPrice()  ,$sortbyHistoric,$game['GrandTotal']);
			$game['PaidHrsNext1']    =getHrsNextPosition($game['PaidPriceObj']->getPrice()         ,$sortbyPaid    ,$game['GrandTotal']);
			$game['SaleHrsNext1']    =getHrsNextPosition($game['SalePriceObj']->getPrice()    ,$sortbySale    ,$game['GrandTotal']);
			$game['AltHrsNext1']     =getHrsNextPosition($game['AltPriceObj']->getPrice() ,$sortbyAlt  ,$game['GrandTotal']);
			
			$game['LaunchHrsNext2']  =getHrsNextPosition($game['LaunchPriceObj']->getPrice()  ,$sortbyActiveLaunch  ,$game['GrandTotal']);
			$game['MSRPHrsNext2']    =getHrsNextPosition($game['MSRPPriceObj']->getPrice()         ,$sortbyActiveMSRP    ,$game['GrandTotal']);
			$game['CurrentHrsNext2'] =getHrsNextPosition($game['CurrentPriceObj']->getPrice()  ,$sortbyActiveCurrent ,$game['GrandTotal']);
			$game['HistoricHrsNext2']=getHrsNextPosition($game['HistoricPriceObj']->getPrice()  ,$sortbyActiveHistoric,$game['GrandTotal']);
			$game['PaidHrsNext2']    =getHrsNextPosition($game['PaidPriceObj']->getPrice()         ,$sortbyActivePaid    ,$game['GrandTotal']);
			$game['SaleHrsNext2']    =getHrsNextPosition($game['SalePriceObj']->getPrice()    ,$sortbyActiveSale    ,$game['GrandTotal']);
			$game['AltHrsNext2']     =getHrsNextPosition($game['AltPriceObj']->getPrice() ,$sortbyActiveAlt  ,$game['GrandTotal']);
			/*
			$game['LaunchHrs5']  =getHrsToTarget($game['LaunchPriceObj']->getPrice(),  $game['GrandTotal']  ,5);
			$game['MSRPHrs3']    =getHrsToTarget($game['MSRPPriceObj']->getPrice(),         $game['GrandTotal']  ,3);
			$game['PaidHrs3']    =getHrsToTarget($game['PaidPriceObj']->getPrice(),         $game['GrandTotal']  ,3);
			$game['HistoricHrs3']=getHrsToTarget($game['HistoricPriceObj']->getPrice(),  $game['GrandTotal']  ,.3);
			$game['AltHrs3']	 =getHrsToTarget($game['AltPriceObj']->getPrice(), $game['GrandTotal']  ,3);
			$game['SaleHrs3']	 =getHrsToTarget($game['SalePriceObj']->getPrice(),    $game['GrandTotal']  ,3);
			*/
		}
		
		foreach ($games as &$game) {
			$game['DIVIDER']  ="---------------- Below fields are only present for backward compatability ----------------";

			$game['LaunchPrice'] = $game['LaunchPriceObj']->getPrice();
			$game['MSRP']= $game['MSRPPriceObj']->getPrice();
			$game['CurrentMSRP']= $game['CurrentPriceObj']->getPrice();
			$game['HistoricLow']= $game['HistoricPriceObj']->getPrice();
			$game['SalePrice']= $game['SalePriceObj']->getPrice();
			$game['AltSalePrice']= $game['AltPriceObj']->getPrice();
			$game['Paid']= $game['PaidPriceObj']->getPrice();
			
			$game['LaunchVariance']  = $game['LaunchPriceObj']->getVarianceFromMSRP();
			$game['HistoricVariance']= $game['HistoricPriceObj']->getVarianceFromMSRP();
			$game['PaidVariance']    = $game['PaidPriceObj']->getVarianceFromMSRP();
			$game['SaleVariance']    = $game['SalePriceObj']->getVarianceFromMSRP();
			$game['AltSaleVariance']     = $game['AltPriceObj']->getVarianceFromMSRP();

			$game['LaunchVariancePct']  = $game['LaunchPriceObj']->getVarianceFromMSRPpct();
			$game['HistoricVariancePct']= $game['HistoricPriceObj']->getVarianceFromMSRPpct();
			$game['PaidVariancePct']    = $game['PaidPriceObj']->getVarianceFromMSRPpct();
			$game['SaleVariancePct']    = $game['SalePriceObj']->getVarianceFromMSRPpct();
			$game['AltSaleVariancePct']     = $game['AltPriceObj']->getVarianceFromMSRPpct();

			$game['Launchperhrbeat']  = $game['LaunchPriceObj']->getPricePerHourOfTimeToBeat();
			$game['MSRPperhrbeat']    = $game['MSRPPriceObj']->getPricePerHourOfTimeToBeat();
			$game['Currentperhr'] = $game['CurrentPriceObj']->getPricePerHourOfTimeToBeat();
			$game['Historicperhrbeat']= $game['HistoricPriceObj']->getPricePerHourOfTimeToBeat();
			$game['Paidperhrbeat']    = $game['PaidPriceObj']->getPricePerHourOfTimeToBeat();
			$game['Saleperhrbeat']    = $game['SalePriceObj']->getPricePerHourOfTimeToBeat();
			$game['Altperhrbeat']     = $game['AltPriceObj']->getPricePerHourOfTimeToBeat();

			$game['Launchperhr']  = $game['LaunchPriceObj']->getPricePerHourOfTimePlayed();
			$game['MSRPperhr']    = $game['MSRPPriceObj']->getPricePerHourOfTimePlayed();
			$game['Currentperhr'] = $game['CurrentPriceObj']->getPricePerHourOfTimePlayed();
			$game['Historicperhr']= $game['HistoricPriceObj']->getPricePerHourOfTimePlayed();
			$game['Paidperhr']    = $game['PaidPriceObj']->getPricePerHourOfTimePlayed();
			$game['Saleperhr']    = $game['SalePriceObj']->getPricePerHourOfTimePlayed();
			$game['Altperhr']     = $game['AltPriceObj']->getPricePerHourOfTimePlayed();

			$game['LaunchLess1']  = $game['LaunchPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['MSRPLess1']    = $game['MSRPPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['CurrentLess1'] = $game['CurrentPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['HistoricLess1']= $game['HistoricPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['PaidLess1']    = $game['PaidPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['SaleLess1']    = $game['SalePriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();
			$game['AltLess1']     = $game['AltPriceObj']->getPricePerHourOfTimePlayedReducedAfter1Hour();

			$game['LaunchLess2']  = $game['LaunchPriceObj']->getHoursTo01LessPerHour();
			$game['MSRPLess2']    = $game['MSRPPriceObj']->getHoursTo01LessPerHour();
			$game['CurrentLess2'] = $game['CurrentPriceObj']->getHoursTo01LessPerHour();
			$game['HistoricLess2']= $game['HistoricPriceObj']->getHoursTo01LessPerHour();
			$game['PaidLess2']    = $game['PaidPriceObj']->getHoursTo01LessPerHour();
			$game['SaleLess2']    = $game['SalePriceObj']->getHoursTo01LessPerHour();
			$game['AltLess2']     = $game['AltPriceObj']->getHoursTo01LessPerHour();
			
			$game['LaunchHrs5']  =$game['LaunchPriceObj']->getHoursToDollarPerHour(5);
			$game['MSRPHrs3']    =$game['MSRPPriceObj']->getHoursToDollarPerHour(3);
			$game['PaidHrs3']    =$game['PaidPriceObj']->getHoursToDollarPerHour(3);
			$game['HistoricHrs3']=$game['HistoricPriceObj']->getHoursToDollarPerHour(3);
			$game['AltHrs3']	 =$game['AltPriceObj']->getHoursToDollarPerHour(3);
			$game['SaleHrs3']	 =$game['SalePriceObj']->getHoursToDollarPerHour(3);
			
		}
		
		$GLOBALS["CALCULATIONS"] = $games;
		return $games;
	}
}

function getPriceSort($SourceArray,$SortObject,$onlyActive=false){
	foreach ($SourceArray as $key => $row){
		if($onlyActive==true && $row['Active']==true) {
			$SortArray[$key] = $row[$SortObject]->getPricePerHourOfTimePlayed();
		} else {
			$SortArray[$key] = $row[$SortObject]->getPricePerHourOfTimePlayed();
		}
	}
	$SortArray=array_unique($SortArray);
	array_multisort($SortArray, SORT_DESC );
	
	return $SortArray;
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getCalculations.inc") {
	$GLOBALS['rootpath']="..";
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Calculations Inc Test";
	echo Get_Header($title);
	
	$lookupgame=lookupTextBox("Product", "ProductID", "id", "Game", $GLOBALS['rootpath']."/ajax/search.ajax.php");
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
		$calculations=reIndexArray(getCalculations(""),"Game_ID");
		echo arrayTable($calculations[$_GET['id']]);
	}
	echo Get_Footer();
}
?>