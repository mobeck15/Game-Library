<?php
/*
 *  GL5 Version - Need to re-work for GL6
 */

//TODO: Paid total should include DLC (this will make free games with paid DLC show as not free)
//TODO: Re-evaluate how parent game is calculated
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

function getCalculations($gameID="",$connection=false,$start=false,$end=false){
	
	if (isset($GLOBALS["CALCULATIONS"]))
	{
		//echo "CALCULATIONS DEFINED ";
		return $GLOBALS["CALCULATIONS"];
	} else {
		/*
		if(isset($GLOBALS[__FUNCTION__])){
			trigger_error("Function already called once; ".__FUNCTION__.". ");
			debug_print_backtrace();
		}
		$GLOBALS[__FUNCTION__]=1;
		*/
		
		if($connection==false){
			include "auth.inc.php";
			$conn = new mysqli($servername, $username, $password, $dbname);
		} else {
			$conn = $connection;
		}
		
		$games=getGames($gameID,$conn);
		$items=getAllItems($gameID,$conn);
		$settings=getsettings($conn);
		$history=getHistoryCalculations($gameID,$conn);
		$activity=getActivityCalculations($gameID,$history,$conn);
		$keywords=getKeywords($gameID,$conn);
		$purchases=getPurchases("",$conn,$items,$games);
		
		//$history=regroupArray($history,'ParentGameID');
		foreach ($history as $row) {
			//Group the history records by GameID
			$historyByGame[$row['ParentGameID']][]=$row;
		}

		//var_dump($activity[514]);
		
		if($connection==false){
			$conn->close();	
		}
		
		$gameIndex=makeIndex($games,"Game_ID");
		$purchaseIndex=makeIndex($purchases,"TransID");

		$itemsbyGame=regroupArray($items,"ProductID");
		
		foreach ($games as &$game) {
			//if(!isset($game['Debug'])) {$game['Debug']="<b>".$game['Title'].":</b>";}

			//DONE in getActivityCalculations.php: Play total needs to be added to unplayable DLC so they can get $/hr calculations
			//This isn't neededed unless we want to capture all values as if parent game:
			/*
			if (isset($activity[$game['ParentGameID']])){
				$game['firstplay']=$activity[$game['ParentGameID']]['firstplay'];
				$game['firstplaysort']=strtotime($activity[$game['ParentGameID']]['firstplay']);
				$game['lastplay']=$activity[$game['ParentGameID']]['lastplay'];
				$game['lastplaySort']=strtotime($activity[$game['ParentGameID']]['lastplay']);
				$game['Achievements']=$activity[$game['ParentGameID']]['Achievements'];
				$game['Status']=$activity[$game['ParentGameID']]['Status'];
				$game['Review']=$activity[$game['ParentGameID']]['Review'];
				$game['LastBeat']=$activity[$game['ParentGameID']]['LastBeat'];
				$game['totalHrs']=$activity[$game['ParentGameID']]['totalHrs'];
				$game['GrandTotal']=$activity[$game['ParentGameID']]['GrandTotal'];
			} 
			*/
			
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
				$game['ParentGame']="Not Found";
			}
			
			if ($game['SteamAchievements']=="") {$game['SteamAchievements']=0;}
			//var_dump($game['SteamAchievements']); echo " - "; var_dump($game['Achievements']); echo "<br>";
			$game['AchievementsLeft']=$game['SteamAchievements']-$game['Achievements'];
			$game['AchievementsPct']=0;
			if($game['SteamAchievements']<>0){
				$game['AchievementsPct']=($game['Achievements']/$game['SteamAchievements'])*100;
			} 
			$game['Active']=$settings['status'][$game['Status']]['Active'];
			$game['CountGame']=$settings['status'][$game['Status']]['Count'];
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
			//$game['PurchaseDate']=0;
			//$game['PrintPurchaseDate']="";
			$game['Paid']=0;
			$game['Inactive']=false;
			$game['Key']="";
			$game['DrmFree']=false;
			$game['DrmFreeSize']=0;
			$game['DrmFreeLibrary']="";
			$game['MainLibrary']="";
			$game['OtherLibrary']=true;

			if (isset($itemsbyGame[$game['Game_ID']])){
				/* DEBUG * /
				if ($game['Game_ID'] == 20) {
					echo "<pre>"; var_dump ($itemsbyGame[$game['Game_ID']]); echo "</pre>";
				}
				/* END Debug */
				foreach($itemsbyGame[$game['Game_ID']] as $record){
					if(!isset($game['Bundles'][$record['TransID']])){
						if(isset($purchases[$purchaseIndex[$record['TransID']]])){
							$useBundleID=$purchases[$purchaseIndex[$record['TransID']]]['TopBundleID'];
							if(!isset($game['TopBundleIDs'][$useBundleID])){
								$game['FirstBundle']=$useBundleID;
								$game['TopBundleIDs'][$useBundleID]=$useBundleID;
								$game['PurchaseDateTime']=$purchases[$purchaseIndex[$record['TransID']]]['PurchaseDateTime'];
								
								/* DEBUG * /
								if ($game['Game_ID'] == 20) {
									echo "<b>DEBUG:</b><br>";
									echo "Game ID: " . $game['Game_ID'] . " (" . $game['Title'] . ")<br>";
									echo "Paid: " . $game['Paid'] . "<br>";
									echo "Bundle Paid: " . $purchases[$purchaseIndex[$useBundleID]]['Paid'] . "<br>";
									echo "<hr>";
								}
								/* END DEBUG */
								
								if($game['PrintBundles']==""){
									//$game['PrintBundles'] .= "(" . $useBundleID .") ";
									$game['PrintBundles'] .= $purchases[$purchaseIndex[$useBundleID]]['Title'];
									$game['BundlePrice']=sprintf("%.2f",$purchases[$purchaseIndex[$useBundleID]]['Paid']);
									//DONE: change Paid calculation to use only the first bundle ****PAID**** -- Fixed in getPurchases.inc.php & getallitems|utility.inc.php
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
							$useBundleID=null;
							$game['PrintBundles']="Not Found";
							$game['BundlePrice']="N/A";
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
					
					$game['AddedDateTime']=$record['AddedDateTime'];
					
					//if($game['PurchaseDate']==0 OR $record['AddedTimeStamp'] < $game['PurchaseDate']) {
						//$game['PurchaseDate']=$record['AddedTimeStamp'];
						//$game['PrintPurchaseDate']=$record['PrintAddedTimeStamp'];
					//}
				}
				$game['Platforms']=trim($game['Platforms'] ,"\n\r, ");
				$game['PrintBundles']=trim($game['PrintBundles'] ,"\n\r| ");
				$game['Paid']=sprintf("%.2f",$game['Paid']);
				//$game['Bundles']=print_r($itemsbyGame[$game['Game_ID']],true);
				//$game['Platforms']=print_r($itemsbyGame[$game['Game_ID']],true);
				//$game['PurchaseDate']=print_r($itemsbyGame[$game['Game_ID']],true);
			} else {
				$game['PrintBundles']="Not Found";
				$game['Platforms']="Not Found";
				//$game['PurchaseDate']="Not Found";
				//$game['PrintPurchaseDate']="Not Found";
			}
			
			if($game['totalHrs']==0 && isset($historyByGame[$game['ParentGameID']])){
				foreach ($historyByGame[$game['ParentGameID']] as $parentgame){
					if(strtotime($parentgame['Timestamp'])>=$game['PurchaseDateTime']->getTimestamp() //strtotime($game['PrintPurchaseDate']) 
						&& $parentgame['FinalCountHours']==true){
						//$game['totalHrs']+=$parentgame['Elapsed'];
						
						if($parentgame['Elapsed']=="") {$parentgame['Elapsed']=0;}
						//var_dump($game['GrandTotal']); echo " += "; var_dump($parentgame['Elapsed']); echo "<br>";
						$game['GrandTotal']+=$parentgame['Elapsed'];
					}
				}
			}
			
			//if(!is_numeric($game['PurchaseDate'])) {$game['PurchaseDate']=0;}
			$game['DaysSincePurchaseDate']=daysSinceDate($game['PurchaseDateTime']->getTimestamp());

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
			/*
			$game['LaunchVariancePct']  =getVariancePct($game['LaunchPrice'] ,$game['MSRP']);
			$game['LaunchVariance']     =getVariance   ($game['LaunchPrice'] ,$game['MSRP']);
			$game['HistoricVariancePct']=getVariancePct($game['HistoricLow'] ,$game['MSRP']);
			$game['HistoricVariance']   =getVariance   ($game['HistoricLow'] ,$game['MSRP']);
			$game['SaleVariancePct']    =getVariancePct($game['SalePrice']   ,$game['MSRP']);
			$game['SaleVariance']       =getVariance   ($game['SalePrice']   ,$game['MSRP']);
			$game['AltSaleVariancePct'] =getVariancePct($game['AltSalePrice'],$game['MSRP']);
			$game['AltSaleVariance']    =getVariance   ($game['AltSalePrice'],$game['MSRP']);
			$game['PaidVariancePct']    =getVariancePct($game['Paid']        ,$game['MSRP']);
			$game['PaidVariance']       =getVariance   ($game['Paid']        ,$game['MSRP']);
			*/
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
			
			/*
			$game['Launchperhrbeat']  =getPriceperhour($game['LaunchPrice'] ,$game['TimeToBeat']*60*60);
			$game['MSRPperhrbeat']    =getPriceperhour($game['MSRP']        ,$game['TimeToBeat']*60*60);
			$game['Saleperhrbeat']    =getPriceperhour($game['SalePrice']   ,$game['TimeToBeat']*60*60);
			$game['Altperhrbeat']     =getPriceperhour($game['AltSalePrice'],$game['TimeToBeat']*60*60);
			$game['Paidperhrbeat']    =getPriceperhour($game['Paid']        ,$game['TimeToBeat']*60*60);
			$game['Historicperhrbeat']=getPriceperhour($game['HistoricLow'] ,$game['TimeToBeat']*60*60);

			$game['Launchperhr']  =getPriceperhour($game['LaunchPrice'],$game['GrandTotal']);
			$game['MSRPperhr']    =getPriceperhour($game['MSRP']       ,$game['GrandTotal']);
			$game['Currentperhr'] =getPriceperhour($game['CurrentMSRP'],$game['GrandTotal']);
			$game['Historicperhr']=getPriceperhour($game['HistoricLow'],$game['GrandTotal']);
			$game['Paidperhr']    =getPriceperhour($game['Paid']       ,$game['GrandTotal']);
			$game['Saleperhr']    =getPriceperhour($game['SalePrice']  ,$game['GrandTotal']);
			$game['Altperhr']     =getPriceperhour($game['AltSalePrice']  ,$game['GrandTotal']);
			
			$game['LaunchLess1']  =getLessXhour($game['LaunchPrice'],$game['GrandTotal'],$settings['XhourGet']);
			$game['MSRPLess1']    =getLessXhour($game['MSRP']       ,$game['GrandTotal'],$settings['XhourGet']);
			$game['CurrentLess1'] =getLessXhour($game['CurrentMSRP'],$game['GrandTotal'],$settings['XhourGet']);
			$game['HistoricLess1']=getLessXhour($game['HistoricLow'],$game['GrandTotal'],$settings['XhourGet']);
			$game['PaidLess1']    =getLessXhour($game['Paid']       ,$game['GrandTotal'],$settings['XhourGet']);
			$game['SaleLess1']    =getLessXhour($game['SalePrice']  ,$game['GrandTotal'],$settings['XhourGet']);
			$game['AltLess1']     =getLessXhour($game['AltSalePrice']  ,$game['GrandTotal'],$settings['XhourGet']);
			
			$game['LaunchLess2']  =getHourstoXless($game['LaunchPrice'],$game['GrandTotal'],$settings['LessStat']);
			$game['MSRPLess2']    =getHourstoXless($game['MSRP']       ,$game['GrandTotal'],$settings['LessStat']);
			$game['CurrentLess2'] =getHourstoXless($game['CurrentMSRP'],$game['GrandTotal'],$settings['LessStat']);
			$game['HistoricLess2']=getHourstoXless($game['HistoricLow'],$game['GrandTotal'],$settings['LessStat']);
			$game['PaidLess2']    =getHourstoXless($game['Paid']       ,$game['GrandTotal'],$settings['LessStat']);
			$game['SaleLess2']    =getHourstoXless($game['SalePrice']  ,$game['GrandTotal'],$settings['LessStat']);
			$game['AltLess2']     =getHourstoXless($game['AltSalePrice']  ,$game['GrandTotal'],$settings['LessStat']);
			*/
			
			$game['TimeLeftToBeat']=getTimeLeft($game['TimeToBeat'],$game['GrandTotal'],$game['Status']);
			/*
			$game['TimeLeftToBeat']=$game['TimeToBeat']-($game['GrandTotal']/60/60);
			if($game['TimeLeftToBeat']<0 || $game['Status']=="Done"){
				$game['TimeLeftToBeat']=0;
			}
			*/
			
			$game['LastPlayORPurchaseValue']=max($game['lastplaySort'],$game['AddedDateTime']->getTimestamp());
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
			
			$game['LaunchHrs5']  =getHrsToTarget($game['LaunchPriceObj']->getPrice(),  $game['GrandTotal']  ,5);
			$game['MSRPHrs3']    =getHrsToTarget($game['MSRPPriceObj']->getPrice(),         $game['GrandTotal']  ,3);
			$game['PaidHrs3']    =getHrsToTarget($game['PaidPriceObj']->getPrice(),         $game['GrandTotal']  ,3);
			$game['HistoricHrs3']=getHrsToTarget($game['HistoricPriceObj']->getPrice(),  $game['GrandTotal']  ,.3);
			$game['AltHrs3']	 =getHrsToTarget($game['AltPriceObj']->getPrice(), $game['GrandTotal']  ,3);
			$game['SaleHrs3']	 =getHrsToTarget($game['SalePriceObj']->getPrice(),    $game['GrandTotal']  ,3);
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
			//$game['MSRPVariance']    = $game['MSRPPriceObj']->getVarianceFromMSRP();
			//$game['CurrentVariance'] = $game['CurrentPriceObj']->getVarianceFromMSRP();
			$game['HistoricVariance']= $game['HistoricPriceObj']->getVarianceFromMSRP();
			$game['PaidVariance']    = $game['PaidPriceObj']->getVarianceFromMSRP();
			$game['SaleVariance']    = $game['SalePriceObj']->getVarianceFromMSRP();
			$game['AltSaleVariance']     = $game['AltPriceObj']->getVarianceFromMSRP();

			$game['LaunchVariancePct']  = $game['LaunchPriceObj']->getVarianceFromMSRPpct();
			//$game['MSRPVariancePct']    = $game['MSRPPriceObj']->getVarianceFromMSRPpct();
			//$game['CurrentVariancePct'] = $game['CurrentPriceObj']->getVarianceFromMSRPpct();
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
		}
		
		$GLOBALS["CALCULATIONS"] = $games;
		//const CALCULATIONS = 1; //$games;
		
		return $games;
	}
}

function getPriceSort($SourceArray,$SortObject,$onlyActive=false){
	foreach ($SourceArray as $key => $row){
		if($onlyActive==true) {
			if($row['Active']==true){
				$SortArray[$key] = $row[$SortObject]->getPricePerHourOfTimePlayed();
			}
		} else {
			$SortArray[$key] = $row[$SortObject]->getPricePerHourOfTimePlayed();
		}
		
	}
	$SortArray=array_unique($SortArray);
	array_multisort($SortArray, SORT_DESC );
	
	return $SortArray;
}



class PriceCalculation {
    public $price; 	//Currency

	private $MSRP;
	private $HoursPlayed;
	private $HoursToBeat;

    public function __construct($price, $HoursPlayed, $HoursToBeat=null, $MSRP=null )
    {
        $this->price = $price;
        $this->MSRP = $MSRP;
        $this->HoursPlayed = $HoursPlayed;
        $this->HoursToBeat = $HoursToBeat;
    }

    public function getPrice($printformat=false)
	{return $printformat ? $this->printCurrencyFormat($this->price) : $this->price;}
	
    public function getVarianceFromMSRP($printformat=false)
    {
		$output=$this->getVariance($this->price ,$this->MSRP);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getVarianceFromMSRPpct($printformat=false)
    {
		$output=$this->getVariancePct($this->price ,$this->MSRP);
		return $printformat ?  $this->printPercentFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimeToBeat($printformat=false)
    {
		$output=$this->getPriceperhour($this->price, $this->HoursToBeat*60*60);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimePlayed($printformat=false)
    {
		$output=$this->getPriceperhour($this->price, $this->HoursPlayed);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}
	
    public function getPricePerHourOfTimePlayedReducedAfter1Hour($printformat=false)
    {
		$output=$this->getLessXhour($this->price, $this->HoursPlayed,1);
		return $printformat ? $this->printCurrencyFormat($output) : $output;
	}

    public function getHoursTo01LessPerHour($printformat=false)
    {
		$output=$this->getHourstoXless($this->price, $this->HoursPlayed,0.01);
		return $printformat ? $this->printDurationFormat($output) : $output;
	}
	
	
	private function getVariance($price,$msrp) {
		$variance=0;
		if($msrp<>0){
			$variance=$price-$msrp;
		}
		return $variance;
	}

	private function getVariancePct($price,$msrp) {
		$variance=0;
		if($msrp<>0){
			$variance=(1-($price/$msrp))*100;
		}
		return $variance;
	}
	
	private function getPriceperhour($price,$hours){
		if(($hours/60/60)<1){
			$priceperhour=$price;
		} else {
			$priceperhour=$price/($hours/60/60);
		}
		return $priceperhour;
	}

	private function getLessXhour($price,$time,$xhour=1){
		$hours=$time/60/60;
		if($hours<1){
			$priceperhour=$price;
		} else {
			$priceperhour=$price/$hours;
		}
		
		if($xhour+$hours==0) {
			$LessXhour=0;
		} else {
			$LessXhour=$priceperhour-($price/(max($xhour,$hours)+$xhour));
		}
		
		return $LessXhour;
	}

	private function getHourstoXless($price,$time,$xless=.01){
		$priceperhour=getPriceperhour($price,$time);
		$hoursxless=getHrsToTarget($price,$time,$priceperhour-$xless);
		
		return $hoursxless;
	}

	private function getHrsToTarget($CalcValue,$time,$target){
		if($target>0){
			$hourstotarget= $CalcValue/$target-$time/60/60;
		} else {
			$hourstotarget=0;
		}
		
		return $hourstotarget;
	}
	
	private function printCurrencyFormat($price) 
	{return sprintf("$%.2f", $price);}
	private function printPercentFormat($price) 
	{return sprintf("%.2f%%", $price);}
	private function printDurationFormat($price) 
	{return timeduration($price,"hours");}

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