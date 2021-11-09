<?php
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ",E_USER_WARNING  );
}
$GLOBALS[__FILE__]=1;


function makeStatTable($MetaFilter,$filter){
	$output="";
	$output .= "<table width=110%>";
	$output .= "<thead>";
	$output .= "<tr>";
	$output .= "<th></th>";
	$output .= "<th></th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Math</th>";
	$output .= "<th>Average (Mean)</th>";
	$output .= "<th>Game</th>";
	//$output .= "<th>Harmonic Mean</th>";
	//$output .= "<th>Game</th>";
	$output .= "<th>Median</th>";
	$output .= "<th>Game</th>";
	$output .= "<th>Mode</th>";
	$output .= "<th>Game</th>";
	$output .= "<th>Most</th>";
	$output .= "<th>Game</th>";
	$output .= "<th>Second Most</th>";
	$output .= "<th>Game</th>";
	$output .= "<th>Least</th>";
	$output .= "<th>Game</th>";
	$output .= "<th>Second Least</th>";
	$output .= "<th>Game</th>";
	//$output .= "<th>Debug</th>";
	$output .= "</tr>";
	$output .= "</thead>";
	
	$output .= "<tbody>";
	
	//var_dump($filter);
	//If Base Stats or Both Base and Meta are requested, show Base Stats
	if($MetaFilter=="base" OR $MetaFilter=="both") {
		$output .= makeHeaderRow("STATS");
		$output .= makeGameCountRow($filter,"blue1");
		/* Launch Date */
		$output .= makeStatRow($filter,"Release","LaunchDate","yellow1","Date",5);
		$output .= makeStatRow($filter,"Purchased","PurchaseDateTime","yellow2");
		$output .= makeStatRow($filter,"Date Added","AddedDateTime","yellow1");
		$output .= makeStatRow($filter,"Last Played","lastPlayDateTime","yellow2");
		$output .= makeStatRow($filter,"First Played","firstPlayDateTime","yellow1");

		$output .= makeStatRow($filter,"Count","SteamAchievements","blue1","Achievements",4);
		$output .= makeStatRow($filter,"Earned","Achievements","blue2");
		$output .= makeStatRow($filter,"Percent Complete","AchievementsPct","blue1");
		$output .= makeStatRow($filter,"Left","AchievementsLeft","blue2");
		
		$output .= makeStatRow($filter,"Play Time","totalHrs","yellow1","Time",4);
		$output .= makeStatRow($filter,"Total Play Time","GrandTotal","yellow2");
		$output .= makeStatRow($filter,"Time to Beat","TimeToBeat","yellow1");
		$output .= makeStatRow($filter,"Remaining Time to Beat","TimeLeftToBeat","yellow2");

		$output .= makeStatRow($filter,"Metascore","Metascore","blue1","Reviews",4);
		$output .= makeStatRow($filter,"User Metascore","UserMetascore","blue2");
		$output .= makeStatRow($filter,"Steam Rating","SteamRating","blue1");
		$output .= makeStatRow($filter,"Review","Review","blue2");

		$output .= makeStatRow($filter,"Price","LaunchPrice","yellow1","Launch",7);
		$output .= makeStatRow($filter,"Variance from MSRP $","LaunchVariance","yellow2");
		$output .= makeStatRow($filter,"Variance from MSRP %","LaunchVariancePct","yellow1");
		$output .= makeStatRow($filter,"$/hr","Launchperhr","yellow2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","LaunchLess1","yellow1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by","LaunchLess2","yellow2");
		$output .= makeStatRow($filter,"$/hr to Beat","Launchperhrbeat","yellow1");

		$output .= makeStatRow($filter,"Price","MSRP","blue1","MSRP",5);
		$output .= makeStatRow($filter,"$/hr","MSRPperhr","blue2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","MSRPLess1","blue1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by","MSRPLess2","blue2");
		$output .= makeStatRow($filter,"$/hr to Beat","MSRPperhrbeat","blue1");
	}
	
	return $output;
}

function makeGameCountRow($filter,$color) {
	$output = "<tr class='$color'>";
	$output .= "<th colspan=2>Games</th>";
	$output .= "<td class='numeric' >".number_format(countgames($filter),0)."</td>";
	$output .= "<td colspan=17></td>";
	$output .= "</tr>";
	
	return $output;
}

function makeStatRow($filter,$rowname,$datakey,$color,$Heading="", $height=1) {
	$printDetailLinks=true;
	//var_dump($filter);
	$stats=getmetastats($filter);
	
	$output = "<tr class='$color'>";
	if($Heading<>"") {
		$output .= "<th rowspan=$height class='$color'>$Heading</th>";
	}
	$output .= "<th>$rowname";
	if($printDetailLinks){ $output .= " (<a href='?filter=".$_GET['filter']."&meta=".$_GET['meta']."&stat=".$stats[$datakey]['Title']."#detail'>?</a>)"; }
	$output .= "</th>";
	$output .= printStatRow2($stats[$datakey]);
	$output .= "</tr>";
	
	return $output;
}

function countgames($filter) {
	$calculations=getCalculations();
	$gamecount=0;
	foreach ($calculations as $key => $row) {
		$countrow=countrow($row);
		
		if($countrow) {
			$gamecount++;	
		}
	}
	
	return $gamecount;
}

function makeHeaderRow($label) { 
	$output = "<tr><th colspan=100><hr>$label</th></tr>";
	return $output;
}

function countrow($row) {
	$settings=getSettings();
	$countrow=false;
	
	if($row['Playable']==true) {
		$countrow=true;
		//var_dump($filter);
		//var_dump($row['Status']);
		//var_dump($row['Playable']);
		//var_dump($row['Paid']);
		//var_dump($settings['CountFree']);
		//var_dump($settings['status'][$row['Status']]['Count']);
		//if(($filter=="All" OR $row['Status']==$filter) AND $row['Playable']==true){
		if($settings['CountFree']==false AND $row['Paid']<=0) {
			$countrow=false;
		//} elseif($settings['CountNever']==false AND $row['Status']=="Never") {
			//$countrow=false;
		} elseif($settings['status'][$row['Status']]['Count']==false) {
			$countrow=false;
		} else {
			//echo " COUNTED";
			$countrow=true;
		}
		//echo "<br>\n";	
	}
	
	return $countrow;
}

function makeStatDataSet($filter,$statname) {
	$calculations=getCalculations();
	$dataset=array();
	
	//TODO: Change this funciton to use price objects. - IN PROGRESS
	foreach ($calculations as $key => $row) {
		if(countrow($row)) {
			switch ($statname) {
				case "LaunchLess2":
					if (isset($row['LaunchPriceObj']) && $row['LaunchPriceObj'] <> null) {
						$dataset[] = array (
							"Game_ID" => $row['Game_ID'],
							$statname => $row['LaunchPriceObj']->getHoursTo01LessPerHour()
						);
					}
					break;
				default:
					if (isset($row[$statname]) && $row[$statname] <> null) {
						$dataset[] = array (
							"Game_ID" => $row['Game_ID'],
							$statname => $row[$statname]
						);
					}
					break;
			}
		}
	}
	array_multisort (array_column($dataset, $statname), SORT_DESC, $dataset);
	
	return $dataset;
}

function printStatRow2($stats){
	$calculations=reIndexArray(getCalculations(),"Game_ID");
	
	if($stats['Print']['Total'] == "") {
		return "";
	}
	
	//Total
	$output = "<td class='value'>".$stats['Print']['Total']."</td>";
	//Math
	$output .= "<td></td>";
	//Average (Mean)
	$output .= "<td class='value'>".$stats['Print']['Average']."</td>";
	//Average (Mean) - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['AverageGameID']."'>".$calculations[$stats['AverageGameID']]['Title']."</a></td>";
	//Harmonic Mean
	//$output .= "<td class='value'>".$stats['Print']['HarMean']."</td>";
	//Harmonic Mean - Game
	//$output .= "<td></td>";
	//Median
	$output .= "<td class='value'>".$stats['Print']['Median']."</td>";
	//Median - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['MedianGameID']."'>".$calculations[$stats['MedianGameID']]['Title']."</a></td>";
	//Mode
	$output .= "<td class='value'>".$stats['Print']['Mode']."</td>";
	//Mode - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['ModeGameID']."'>".$calculations[$stats['ModeGameID']]['Title']."</a></td>";
	//Max1
	$output .= "<td class='value'>".$stats['Print']['Max1']."</td>";
	//Max1 - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['Max1GameID']."'>".$calculations[$stats['Max1GameID']]['Title']."</a></td>";
	//Max2
	$output .= "<td class='value'>".$stats['Print']['Max2']."</td>";
	//Max2 - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['Max2GameID']."'>".$calculations[$stats['Max2GameID']]['Title']."</a></td>";
	//Min1
	$output .= "<td class='value'>".$stats['Print']['Min1']."</td>";
	//Min1 - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['Min1GameID']."'>".$calculations[$stats['Min1GameID']]['Title']."</a></td>";
	//Min2
	$output .= "<td class='value'>".$stats['Print']['Min2']."</td>";
	//Min2 - Game
	$output .= "<td class='text'><a href='viewgame.php?id=".$stats['Min2GameID']."'>".$calculations[$stats['Min2GameID']]['Title']."</a></td>";
	return $output;
}

function getmetastats($filter){
	//var_dump($filter);
	//STUB to support metastats overhaul
	if (isset($GLOBALS["METASTATS"]) && count($GLOBALS["METASTATS"]) == 98)
	{
		return $GLOBALS["METASTATS"];
	} else {
		
		$row=getStatRow($filter,null);
		
		$stats['althrsgame']=$row; //getStatRow('althrsgame');
		$stats['althrsmedian']=$row;
		$stats['althrsmean']=$row;
		$stats['althrsavg']=$row;
		$stats['AltHrs3']=$row;
		$stats['AltHrsNext2']=$row;
		$stats['AltHrsNext1']=$row;
		$stats['salehrsgame']=$row;
		$stats['salehrsmedian']=$row;
		$stats['salehrsmean']=$row;
		$stats['salehrsavg']=$row;
		$stats['SaleHrs3']=$row;
		$stats['SaleHrsNext2']=$row;
		$stats['SaleHrsNext1']=$row;
		$stats['paidhrsgame']=$row;
		$stats['paidhrsmedian']=$row;
		$stats['paidhrsmean']=$row;
		$stats['paidhrsavg']=$row;
		$stats['PaidHrs3']=$row;
		$stats['PaidHrsNext2']=$row;
		$stats['PaidHrsNext1']=$row;
		$stats['histhrsgame']=$row;
		$stats['histhrsmedian']=$row;
		$stats['histhrsmean']=$row;
		$stats['histhrsavg']=$row;
		$stats['HistoricHrs3']=$row;
		$stats['HistoricHrsNext2']=$row;
		$stats['HistoricHrsNext1']=$row;
		$stats['msrphrsgame']=$row;
		$stats['msrphrsmedian']=$row;
		$stats['msrphrsmean']=$row;
		$stats['msrphrsavg']=$row;
		$stats['MSRPHrs3']=$row;
		$stats['MSRPHrsNext2']=$row;
		$stats['MSRPHrsNext1']=$row;
		$stats['launchhrsgame']=$row;
		$stats['launchhrsmedian']=$row;
		$stats['launchhrsmean']=$row;
		$stats['launchhrsavg']=$row;
		$stats['LaunchHrs5']=$row;
		$stats['LaunchHrsNext2']=$row;
		$stats['LaunchHrsNext1']=$row;
		$stats['Altperhrbeat']=$row;
		$stats['AltLess1']=$row;
		$stats['AltLess2']=$row;
		$stats['Altperhr']=$row;
		$stats['AltSaleVariancePct']=$row;
		$stats['AltSalePrice']=$row;
		$stats['AltSaleVariance']=$row;
		$stats['AltSalePrice']=$row;
		$stats['Saleperhrbeat']=$row;
		$stats['SaleLess1']=$row;
		$stats['SaleLess2']=$row;
		$stats['Saleperhr']=$row;
		$stats['SaleVariancePct']=$row;
		$stats['SaleVariance']=$row;
		$stats['SalePrice']=$row;
		$stats['Paidperhrbeat']=$row;
		$stats['PaidLess1']=$row;
		$stats['PaidLess2']=$row;
		$stats['Paidperhr']=$row;
		$stats['PaidVariancePct']=$row;
		$stats['PaidVariance']=$row;
		$stats['Paid']=$row;
		$stats['Historicperhrbeat']=$row;
		$stats['HistoricLess1']=$row;
		$stats['HistoricLess2']=$row;
		$stats['Historicperhr']=$row;
		$stats['HistoricVariancePct']=$row;
		$stats['HistoricVariance']=$row;
		$stats['HistoricLow']=$row;
				
		$stats['PurchaseDateTime']=getStatRow($filter,'PurchaseDateTime');
		$stats['LaunchDate']=getStatRow($filter,'LaunchDate');
		$stats['AddedDateTime']=getStatRow($filter,'AddedDateTime');
		$stats['firstPlayDateTime']=getStatRow($filter,'firstPlayDateTime');
		$stats['lastPlayDateTime']=getStatRow($filter,'lastPlayDateTime');
		
		$stats['SteamAchievements']=getStatRow($filter,'SteamAchievements');
		$stats['Achievements']=getStatRow($filter,'Achievements');
		$stats['AchievementsPct']=getStatRow($filter,'AchievementsPct');
		$stats['AchievementsLeft']=getStatRow($filter,'AchievementsLeft');
		
		$stats['totalHrs']=getStatRow($filter,'totalHrs');
		$stats['GrandTotal']=getStatRow($filter,'GrandTotal');
		$stats['TimeToBeat']=getStatRow($filter,'TimeToBeat');
		$stats['TimeLeftToBeat']=getStatRow($filter,'TimeLeftToBeat');
		
		$stats['Metascore']=getStatRow($filter,'Metascore');
		$stats['UserMetascore']=getStatRow($filter,'UserMetascore');
		$stats['SteamRating']=getStatRow($filter,'SteamRating');
		$stats['Review']=getStatRow($filter,'Review');
		
		$stats['LaunchPrice']=getStatRow($filter,'LaunchPrice');
		$stats['LaunchVariance']=getStatRow($filter,'LaunchVariance');
		$stats['LaunchVariancePct']=getStatRow($filter,'LaunchVariancePct');
		$stats['Launchperhr']=getStatRow($filter,'Launchperhr');
		$stats['LaunchLess1']=getStatRow($filter,'LaunchLess1');
		$stats['LaunchLess2']=getStatRow($filter,'LaunchLess2');
		$stats['Launchperhrbeat']=getStatRow($filter,'Launchperhrbeat');

		$stats['MSRP']=getStatRow($filter,'MSRP');
		$stats['MSRPperhr']=getStatRow($filter,'MSRPperhr');
		$stats['MSRPLess1']=getStatRow($filter,'MSRPLess1');
		$stats['MSRPLess2']=getStatRow($filter,'MSRPLess2');
		$stats['MSRPperhrbeat']=getStatRow($filter,'MSRPperhrbeat');

		$GLOBALS["METASTATS"] = $stats;
		return $stats;
	}
}

function makeDetailTable($filter,$statname){
	$dataset=makeStatDataSet($filter,$statname);
	$statrow=getStatRow($filter,$statname);
	
	echo DetailDataTable($dataset,$statrow);
	echo arrayTable($statrow);
	
}

function getStatRow($filter,$statname){
	//STUB to support metastats overhaul
	if (isset($GLOBALS["METASTATS"][$statname]))
	{
		return $GLOBALS["METASTATS"][$statname];
	} else {
		
		$row['Title']=$statname;
		
		if($statname==null) {
			$row['HarMean']=null; //.00001;
			$row['Median']=null; //.00001;
			$row['Total']=.00001;
			$row['Average']=null; //.00001;
			$row['Mode']=null; //.00001;
			$row['Max1']=null; //.00001;
			$row['Max2']=null; //.00001;
			$row['Min1']=null; //.00001;
			$row['Min2']=null; //.00001;

			$row['MedianGameID']=null; //.00001;
			$row['TotalGameID']=null;
			$row['AverageGameID']=null; //.00001;
			$row['ModeGameID']=null; //.00001;
			$row['Max1GameID']=null; //.00001;
			$row['Max2GameID']=null; //.00001;
			$row['Min1GameID']=null; //.00001;
			$row['Min2GameID']=null; //.00001;
		} else {
			$dataset=makeStatDataSet($filter,$statname);
			$val=getOnlyValues($dataset,$statname);
			//var_dump($val); echo "<br>\n";
			$onlydata=$val['basedata'];
			$onlydatamode=$val['modedata'];
			
			$row['Total']=count($onlydata);
			$row['Sum']=array_sum($onlydata);

			//Average (Mean)
			//var_dump($row); echo "<br>\n";
			if($row['Total']==0) {echo $statname . " Total not set<br>\n";}
			$row['Average']=$row['Sum']/$row['Total'];
			
			//Median
			$middleVal = floor(($row['Total'] - 1) / 2);
			if($row['Total'] % 2) { 
				$row['Median']=$onlydata[$middleVal];
			} else {
				$row['Median']=(($onlydata[$middleVal] + $onlydata[$middleVal+1]) / 2);				
			}
			
			//Mode
			$values = array_count_values($onlydatamode); 
			$row['Mode']= array_search(max($values), $values);
			
			//Harmonic Mean (Not working)
			//$row['HarMean']=stats_harmonic_mean($onlydata);  //only in PECL library
			
			foreach ($dataset as $statrow) {
				switch ($statname) {
					case "LaunchDate":
					case "PurchaseDateTime":
					case "AddedDateTime":
					case "lastPlayDateTime":
					case "firstPlayDateTime":
						if($row['Average']<=$statrow[$statname]->getTimestamp()){
							$row['AverageGameID']=$statrow['Game_ID'];
						}
						
						if($row['Median']<=$statrow[$statname]->getTimestamp()){
							$row['MedianGameID']=$statrow['Game_ID'];
						}
						
						if($row['Mode']<=$statrow[$statname]->getTimestamp()){
							$row['ModeGameID']=$statrow['Game_ID'];
						}
						break;
					case "SteamAchievements":
					case "Achievements":
					case "AchievementsLeft":
					case "AchievementsPct":
					case "totalHrs":
					case "GrandTotal":
					case "TimeToBeat":
					case "TimeLeftToBeat":
					case "Metascore":
					case "UserMetascore":
					case "SteamRating":
					case "Review":
					case "LaunchPrice":
					case "LaunchVariance":
					case "LaunchVariancePct":
					case "Launchperhr":
					case "LaunchLess1":
					case "LaunchLess2":
					case "Launchperhrbeat":
					case "MSRP":
					case "MSRPperhr":
					case "MSRPLess1":
					case "MSRPLess2":
					case "MSRPperhrbeat":
						if($row['Average']<=$statrow[$statname]){
							$row['AverageGameID']=$statrow['Game_ID'];
						}
						
						if($row['Median']<=$statrow[$statname]){
							$row['MedianGameID']=$statrow['Game_ID'];
						}
						
						if($row['Mode']<=$statrow[$statname]){
							$row['ModeGameID']=$statrow['Game_ID'];
						}
						break;
				}
			}
			
			$row['Max1']=$onlydata[0];
			$row['Max1GameID']=$dataset[0]['Game_ID'];
			$row['Max2']=$onlydata[1];
			$row['Max2GameID']=$dataset[1]['Game_ID'];
			$row['Min1']=$onlydata[$row['Total']-1];
			$row['Min1GameID']=$dataset[$row['Total']-1]['Game_ID'];
			$row['Min2']=$onlydata[$row['Total']-2];
			$row['Min2GameID']=$dataset[$row['Total']-2]['Game_ID'];
			
		}
		
		switch ($statname) {
			//Date Object
			case "LaunchDate":
			case "PurchaseDateTime":
			case "AddedDateTime":
			case "lastPlayDateTime":
			case "firstPlayDateTime":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=date('Y-m-d', $row['Average']);
				//$row['Print']['HarMean']=date('Y-m-d', $row['HarMean']);
				$row['Print']['Median']=date('Y-m-d', $row['Median']);
				$row['Print']['Mode']=date('Y-m-d', $row['Mode']);
				$row['Print']['Max1']=date('Y-m-d', $row['Max1']);
				$row['Print']['Max2']=date('Y-m-d', $row['Max2']);
				$row['Print']['Min1']=date('Y-m-d', $row['Min1']);
				$row['Print']['Min2']=date('Y-m-d', $row['Min2']);
				break;
			//Integers
			case "SteamAchievements":
			case "Achievements":
			case "AchievementsLeft":
			case "Metascore":
			case "UserMetascore":
			case "SteamRating":
			case "Review":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=number_format($row['Average'],2);
				//$row['Print']['HarMean']=number_format($row['HarMean'],0);
				$row['Print']['Median']=number_format($row['Median'],0);
				$row['Print']['Mode']=number_format($row['Mode'],0);
				$row['Print']['Max1']=number_format($row['Max1'],0);
				$row['Print']['Max2']=number_format($row['Max2'],0);
				$row['Print']['Min1']=number_format($row['Min1'],0);
				$row['Print']['Min2']=number_format($row['Min2'],0);
				break;
			//Currency
			case "LaunchPrice":
			case "LaunchVariance":
			case "LaunchVariancePct":
			case "Launchperhr":
			case "LaunchLess1":
			case "LaunchLess2":
			case "Launchperhrbeat":
			case "MSRP":
			case "MSRPperhr":
			case "MSRPLess1":
			case "MSRPLess2":
			case "MSRPperhrbeat":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=sprintf("$%.2f", $row['Average']);
				//$row['Print']['HarMean']=sprintf("%.2f", $row['HarMean']);
				$row['Print']['Median']=sprintf("$%.2f", $row['Median']);
				$row['Print']['Mode']=sprintf("$%.2f", $row['Mode']);
				$row['Print']['Max1']=sprintf("$%.2f", $row['Max1']);
				$row['Print']['Max2']=sprintf("$%.2f", $row['Max2']);
				$row['Print']['Min1']=sprintf("$%.2f", $row['Min1']);
				$row['Print']['Min2']=sprintf("$%.2f", $row['Min2']);
				break;
			//Percent
			case "AchievementsPct":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=sprintf("%.2f%%", $row['Average']);
				//$row['Print']['HarMean']=sprintf("%.2f%%", $row['HarMean']);
				$row['Print']['Median']=sprintf("%.2f%%", $row['Median']);
				$row['Print']['Mode']=$row['Mode'];
				$row['Print']['Max1']=sprintf("%.2f%%", $row['Max1']);
				$row['Print']['Max2']=sprintf("%.2f%%", $row['Max2']);
				$row['Print']['Min1']=sprintf("%.2f%%", $row['Min1']);
				$row['Print']['Min2']=sprintf("%.2f%%", $row['Min2']);
				break;
			//Duration from Seconds
			case "totalHrs":
			case "GrandTotal":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=timeduration($row['Average'],"seconds");
				//$row['Print']['HarMean']=timeduration($row['HarMean'],"seconds");
				$row['Print']['Median']=timeduration($row['Median'],"seconds");
				$row['Print']['Mode']=timeduration($row['Mode'],"seconds");
				$row['Print']['Max1']=timeduration($row['Max1'],"seconds");
				$row['Print']['Max2']=timeduration($row['Max2'],"seconds");
				$row['Print']['Min1']=timeduration($row['Min1'],"seconds");
				$row['Print']['Min2']=timeduration($row['Min2'],"seconds");
				break;
			//Duration from hours
			case "TimeToBeat":
			case "TimeLeftToBeat":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=timeduration($row['Average'],"hours");
				//$row['Print']['HarMean']=timeduration($row['HarMean'],"hours");
				$row['Print']['Median']=timeduration($row['Median'],"hours");
				$row['Print']['Mode']=timeduration($row['Mode'],"hours");
				$row['Print']['Max1']=timeduration($row['Max1'],"hours");
				$row['Print']['Max2']=timeduration($row['Max2'],"hours");
				$row['Print']['Min1']=timeduration($row['Min1'],"hours");
				$row['Print']['Min2']=timeduration($row['Min2'],"hours");
				break;
			default:
				$row['Print']['Total']=null;
				$row['Print']['Average']=null;
				//$row['Print']['HarMean']=null;
				$row['Print']['Median']=null;
				$row['Print']['Mode']=null;
				$row['Print']['Max1']=null;
				$row['Print']['Max2']=null;
				$row['Print']['Min1']=null;
				$row['Print']['Min2']=null;
				break;
		}
		
		$GLOBALS["METASTATS"][$statname] = $row;
		
		return $row;
	}
}

function DetailDataTable($dataset,$statrow){
	$calculations=reIndexArray(getCalculations(),"Game_ID");
	
	$output = "<div><table>";
	$output .= "<thead>";
	$output .= "<tr><th>Key</th><th>" . $statrow['Title'] . "</th><th>ID</th><th>Title</th><th>Stat</th></tr>";
	$output .= "</thead>";
	$output .= "<tbody>";
	foreach ($dataset as $key=>$row) {
		$output .= "<tr><td>$key</td>";
		foreach ($row as $label=>$value) {
			if($label <> "Game_ID") {
		switch ($statrow['Title']) {
			case "LaunchDate":
			case "PurchaseDateTime":
			case "AddedDateTime":
			case "lastPlayDateTime":
			case "firstPlayDateTime":
				$output .= "<td>" . $value->Format("Y-m-d H:i:s (") . $value->getTimestamp() . ")</td>";
				break;
			case "SteamAchievements":
			case "Achievements":
			case "AchievementsLeft":
			case "AchievementsPct":
			case "totalHrs":
			case "GrandTotal":
			case "TimeToBeat":
			case "TimeLeftToBeat":
			case "Metascore":
			case "UserMetascore":
			case "SteamRating":
			case "Review":
			case "LaunchPrice":
			case "LaunchVariance":
			case "LaunchVariancePct":
			case "Launchperhr":
			case "LaunchLess1":
			case "LaunchLess2":
			case "Launchperhrbeat":
			case "MSRP":
			case "MSRPperhr":
			case "MSRPLess1":
			case "MSRPLess2":
			case "MSRPperhrbeat":
				$output .= "<td>$value</td>";
				break;
		}
				
			}
		}	
		$output .= "<td>".$row['Game_ID']."</td>";
		$output .= "<td><a href='viewgame.php?id=".$row['Game_ID']."'>".$calculations[$row['Game_ID']]['Title']."</a></td>";
		//$output .= "<td>".$calculations[$row['Game_ID']][$stat]."</td>";
		$output .= "<td>";
		if($row['Game_ID']==$statrow['AverageGameID']) {
			$output .= " Average: " . $statrow['Average'];
		}
		if($row['Game_ID']==$statrow['MedianGameID']) {
			$output .= " Median: " . $statrow['Median'];
		}
		if($row['Game_ID']==$statrow['ModeGameID']) {
			$output .= " Mode: " . $statrow['Mode'];
		}
		if($row['Game_ID']==$statrow['Max1GameID']) {
			$output .= " Max1: " . $statrow['Max1'];
		}
		if($row['Game_ID']==$statrow['Max2GameID']) {
			$output .= " Max2: " . $statrow['Max2'];
		}
		if($row['Game_ID']==$statrow['Min1GameID']) {
			$output .= " Min1: " . $statrow['Min1'];
		}
		if($row['Game_ID']==$statrow['Min2GameID']) {
			$output .= " Min2: " . $statrow['Min2'];
		}
		$output .= "</td>";
		$output .= "</tr>";
	}
	$output .= "</tbody>";
	$output .= "</table></div>";

	return $output;

}

function getOnlyValues($dataset,$statname) {
	//var_dump($dataset);
	//var_dump($statname); echo "<br>\n";
	$onlydata=array(); 
	foreach ($dataset as $statrow) {
		switch ($statname) {
			case "LaunchDate":
			case "PurchaseDateTime":
			case "AddedDateTime":
			case "lastPlayDateTime":
			case "firstPlayDateTime":
				$onlydata[]=$statrow[$statname]->getTimestamp();
				break;
			case "SteamAchievements":
			case "Achievements":
			case "AchievementsLeft":
			case "Metascore":
			case "UserMetascore":
			case "SteamRating":
			case "Review":
				$onlydata[]=$statrow[$statname];
				break;
			case "totalHrs":
			case "GrandTotal":
			case "TimeToBeat":
			case "TimeLeftToBeat":
			case "LaunchPrice":
			case "LaunchVariance":
			case "LaunchVariancePct":
			case "Launchperhr":
			case "LaunchLess1":
			case "Launchperhrbeat":
			case "MSRP":
			case "MSRPperhr":
			case "MSRPLess1":
			case "MSRPLess2":
			case "MSRPperhrbeat":
				$onlydata[]=$statrow[$statname];
				//$onlydatamode[]=timeduration($statrow[$statname],"seconds");
				$onlydatamode[]=sprintf("%.2f", $statrow[$statname]);
				break;
			case "LaunchLess2":
			//var_dump($statrow);
				$onlydata[]=$statrow[$statname];
				//$onlydatamode[]=timeduration($statrow[$statname],"seconds");
				$onlydatamode[]=sprintf("%.2f", $statrow[$statname]);
				break;
			case "AchievementsPct":
				$onlydata[]=$statrow[$statname];
				$onlydatamode[]=sprintf("%.2f%%", $statrow[$statname]);
				break;
		}
	}
	if(!isset($onlydatamode)){
		$onlydatamode=$onlydata;
	}
	
	$return['basedata'] = $onlydata;
	$return['modedata'] = $onlydatamode;
	
	return $return;
}


?>