<?php
/*
 *  GL5 Version - Need to re-work for GL6
 */

//TODO: Update getCalculations function to GL5 version
//TODO: Alt paid needs some corrections in the calculation for when games have zero hours.
//TODO: Alt paid needs some corrections in the calculation for when a bundle has zero total hours.
//TODO: Play total needs to be added to unplayable DLC so they can get $/hr calculations
//TODO: Paid total should include DLC (this will make free games with paid DLC show as not free)
//TODO: Load and parse settings.
//TODO: Copy this control function to other inc files.
//TODO: Re-evaluate how parent game is calculated
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

function getCalculations($gameID="",$connection=false,$start=false,$end=false){
	if(isset($GLOBALS[__FUNCTION__])){
		trigger_error("Function already called once; ".__FUNCTION__.". ");
		debug_print_backtrace();
	}
	$GLOBALS[__FUNCTION__]=1;
	
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

	//var_dump($activity);
	
	if($connection==false){
		$conn->close();	
	}
	
	$gameIndex=makeIndex($games,"Game_ID");
	$purchaseIndex=makeIndex($purchases,"TransID");

	$itemsbyGame=regroupArray($items,"ProductID");
	
	foreach ($games as &$game) {
		//if(!isset($game['Debug'])) {$game['Debug']="<b>".$game['Title'].":</b>";}
		
		if (isset($activity[$game['Game_ID']])){
			$game['firstplay']=$activity[$game['Game_ID']]['firstplay'];
			$game['firstplaysort']=strtotime($activity[$game['Game_ID']]['firstplay']);
			$game['lastplay']=$activity[$game['Game_ID']]['lastplay'];
			$game['lastplaySort']=strtotime($activity[$game['Game_ID']]['lastplay']);
			$game['Achievements']=$activity[$game['Game_ID']]['Achievements'];
			$game['Status']=$activity[$game['Game_ID']]['Status'];
			$game['Review']=$activity[$game['Game_ID']]['Review'];
			$game['LastBeat']=$activity[$game['Game_ID']]['LastBeat'];
			$game['totalHrs']=$activity[$game['Game_ID']]['totalHrs'];
			$game['GrandTotal']=$activity[$game['Game_ID']]['GrandTotal'];
		} else {
			$game['firstplay']="";
			$game['firstplaysort']=0;
			$game['lastplay']="";
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
		$game['PurchaseDate']=0;
		$game['PrintPurchaseDate']="";
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
								//TODO: change Paid calculation to use only the first bundle ****PAID****
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
				
				if($game['PurchaseDate']==0 OR $record['AddedTimeStamp'] < $game['PurchaseDate']) {
					$game['PurchaseDate']=$record['AddedTimeStamp'];
					$game['PrintPurchaseDate']=$record['PrintAddedTimeStamp'];
				}
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
			$game['PurchaseDate']="Not Found";
			$game['PrintPurchaseDate']="Not Found";
		}
		
		if($game['totalHrs']==0 && isset($historyByGame[$game['ParentGameID']])){
			foreach ($historyByGame[$game['ParentGameID']] as $parentgame){
				if(strtotime($parentgame['Timestamp'])>=strtotime($game['PrintPurchaseDate']) 
					&& $parentgame['FinalCountHours']==true){
					//$game['totalHrs']+=$parentgame['Elapsed'];
					
					if($parentgame['Elapsed']=="") {$parentgame['Elapsed']=0;}
					//var_dump($game['GrandTotal']); echo " += "; var_dump($parentgame['Elapsed']); echo "<br>";
					$game['GrandTotal']+=$parentgame['Elapsed'];
				}
			}
		}
		
		
		if(!is_numeric($game['PurchaseDate'])) {$game['PurchaseDate']=0;}
		//var_dump($game['PurchaseDate']);
		$game['DaysSincePurchaseDate']=floor((time()-$game['PurchaseDate']) / (60 * 60 * 24));

		$game['itemsinbundle']=0;
		$game['SalePrice']=0;
		$game['AltSalePrice']=0;

		//Set SalePrice and AltSalePrice ****
		$firstbundledate=time();
		if(isset($game['TopBundleIDs'])){
			foreach($game['TopBundleIDs'] as $bundleID){
				foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $game2) {
					if($game2['GameID']==$game['Game_ID']){
						//$game['Debug'].=$game2['Debug'];
						$game['SalePrice'] +=  $game2['SalePrice'];
						$game['AltSalePrice'] +=  $game2['AltSalePrice'];
					}
				}
			}
		}
		$game['LaunchVariancePct']  =
		$game['LaunchVariance']     =
		$game['SaleVariancePct']    =
		$game['SaleVariance']       =
		$game['HistoricVariancePct']=
		$game['HistoricVariance']   =
		$game['AltSaleVariancePct'] =
		$game['AltSaleVariance']    =
		$game['PaidVariancePct']    =
		$game['PaidVariance']       = 0;
		if($game['MSRP']<>0){
			$game['LaunchVariancePct']=(1-($game['LaunchPrice']/$game['MSRP']))*100;
			$game['LaunchVariance']=$game['LaunchPrice']-$game['MSRP'];
			$game['HistoricVariancePct']=(1-($game['HistoricLow']/$game['MSRP']))*100;
			$game['HistoricVariance']=$game['HistoricLow']/$game['MSRP'];
			$game['SaleVariancePct']=(1-($game['SalePrice']/$game['MSRP']))*100;
			$game['SaleVariance']=$game['SalePrice']/$game['MSRP'];
			$game['AltSaleVariancePct']=(1-($game['AltSalePrice']/$game['MSRP']))*100;
			$game['AltSaleVariance']=$game['AltSalePrice']/$game['MSRP'];
			$game['PaidVariancePct']=(1-($game['Paid']/$game['MSRP']))*100;
			$game['PaidVariance']=$game['Paid']-$game['MSRP'];
		}

	}
	unset ($game);
	/*
	foreach ($games as &$game) {
		
	}
	
	unset ($game);
	
	
	*/
	
	foreach ($games as &$game) {
		
		//$game['SalePrice']=sprintf("%.2f",$game['SalePrice']);
		
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
		
		$game['TimeLeftToBeat']=$game['TimeToBeat']-($game['GrandTotal']/60/60);
		if($game['TimeLeftToBeat']<0 || $game['Status']=="Done"){
			$game['TimeLeftToBeat']=0;
		}
		
		$game['LastPlayORPurchaseValue']=max($game['lastplaySort'],$game['PurchaseDate']);
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

	}
	unset ($game);
	
	$sortbyLaunch=array_unique(getSortArray($games,'Launchperhr'));
	array_multisort($sortbyLaunch, SORT_DESC );
	$sortbyMSRP=array_unique(getSortArray($games,'MSRPperhr'));
	array_multisort($sortbyMSRP, SORT_DESC );
	$sortbyCurrent=array_unique(getSortArray($games,'Currentperhr'));
	array_multisort($sortbyCurrent, SORT_DESC );
	$sortbyHistoric=array_unique(getSortArray($games,'Historicperhr'));
	array_multisort($sortbyHistoric, SORT_DESC );
	$sortbyPaid=array_unique(getSortArray($games,'Paidperhr'));
	array_multisort($sortbyPaid, SORT_DESC );
	$sortbySale=array_unique(getSortArray($games,'Saleperhr'));
	array_multisort($sortbySale, SORT_DESC );
	$sortbyAlt=array_unique(getSortArray($games,'Altperhr'));
	array_multisort($sortbyAlt, SORT_DESC );
	
	$sortbyActiveLaunch=array_unique(getActiveSortArray($games,'Launchperhr'));
	array_multisort($sortbyActiveLaunch, SORT_DESC );
	$sortbyActiveMSRP=array_unique(getActiveSortArray($games,'MSRPperhr'));
	array_multisort($sortbyActiveMSRP, SORT_DESC );
	$sortbyActiveCurrent=array_unique(getActiveSortArray($games,'Currentperhr'));
	array_multisort($sortbyActiveCurrent, SORT_DESC );
	$sortbyActiveHistoric=array_unique(getActiveSortArray($games,'Historicperhr'));
	array_multisort($sortbyActiveHistoric, SORT_DESC );
	$sortbyActivePaid=array_unique(getActiveSortArray($games,'Paidperhr'));
	array_multisort($sortbyActivePaid, SORT_DESC );
	$sortbyActiveSale=array_unique(getActiveSortArray($games,'Saleperhr'));
	array_multisort($sortbyActiveSale, SORT_DESC );
	$sortbyActiveAlt=array_unique(getActiveSortArray($games,'Altperhr'));
	array_multisort($sortbyActiveAlt, SORT_DESC );
	
	//var_dump($sortbyAlt);
	//echo "<hr>";
	//var_dump($sortbyActiveAlt);
	
	foreach ($games as &$game) {
		//if($game['Game_ID']==419){
		$game['LaunchHrsNext1']  =getHrsNextPosition($game['LaunchPrice']  ,$sortbyLaunch  ,$game['GrandTotal']);
		$game['MSRPHrsNext1']    =getHrsNextPosition($game['MSRP']         ,$sortbyMSRP    ,$game['GrandTotal']);
		$game['CurrentHrsNext1'] =getHrsNextPosition($game['CurrentMSRP']  ,$sortbyCurrent ,$game['GrandTotal']);
		$game['HistoricHrsNext1']=getHrsNextPosition($game['HistoricLow']  ,$sortbyHistoric,$game['GrandTotal']);
		$game['PaidHrsNext1']    =getHrsNextPosition($game['Paid']         ,$sortbyPaid    ,$game['GrandTotal']);
		$game['SaleHrsNext1']    =getHrsNextPosition($game['SalePrice']    ,$sortbySale    ,$game['GrandTotal']);
		$game['AltHrsNext1']     =getHrsNextPosition($game['AltSalePrice'] ,$sortbyAlt  ,$game['GrandTotal']);
		
		$game['LaunchHrsNext2']  =getHrsNextPosition($game['LaunchPrice']  ,$sortbyActiveLaunch  ,$game['GrandTotal']);
		$game['MSRPHrsNext2']    =getHrsNextPosition($game['MSRP']         ,$sortbyActiveMSRP    ,$game['GrandTotal']);
		$game['CurrentHrsNext2'] =getHrsNextPosition($game['CurrentMSRP']  ,$sortbyActiveCurrent ,$game['GrandTotal']);
		$game['HistoricHrsNext2']=getHrsNextPosition($game['HistoricLow']  ,$sortbyActiveHistoric,$game['GrandTotal']);
		$game['PaidHrsNext2']    =getHrsNextPosition($game['Paid']         ,$sortbyActivePaid    ,$game['GrandTotal']);
		$game['SaleHrsNext2']    =getHrsNextPosition($game['SalePrice']    ,$sortbyActiveSale    ,$game['GrandTotal']);
		$game['AltHrsNext2']     =getHrsNextPosition($game['AltSalePrice'] ,$sortbyActiveAlt  ,$game['GrandTotal']);
		
		$game['LaunchHrs5']  =getHrsToTarget($game['LaunchPrice'],  $game['GrandTotal']  ,5);
		$game['MSRPHrs3']    =getHrsToTarget($game['MSRP'],         $game['GrandTotal']  ,3);
		$game['PaidHrs3']    =getHrsToTarget($game['Paid'],         $game['GrandTotal']  ,3);
		$game['HistoricHrs3']=getHrsToTarget($game['HistoricLow'],  $game['GrandTotal']  ,.3);
		$game['AltHrs3']	 =getHrsToTarget($game['AltSalePrice'], $game['GrandTotal']  ,3);
		$game['SaleHrs3']	 =getHrsToTarget($game['SalePrice'],    $game['GrandTotal']  ,3);
		/* * /
		} else {
		$game['LaunchHrsNext1']  =0;
		$game['MSRPHrsNext1']    =0;
		$game['CurrentHrsNext1'] =0;
		$game['HistoricHrsNext1']=0;
		$game['PaidHrsNext1']    =0;
		$game['SaleHrsNext1']    =0;
		$game['AltHrsNext1']     =0;
		
		$game['LaunchHrsNext2']  =0;
		$game['MSRPHrsNext2']    =0;
		$game['CurrentHrsNext2'] =0;
		$game['HistoricHrsNext2']=0;
		$game['PaidHrsNext2']    =0;
		$game['SaleHrsNext2']    =0;
		$game['AltHrsNext2']     =0;
		} /* */
	}
	//var_dump($game['AltHrsNext2']);
	return $games;
}
?>