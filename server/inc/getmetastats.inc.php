<?php
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ",E_USER_WARNING  );
}
$GLOBALS[__FILE__]=1;
require_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getPurchases.class.php";



function makeStatTable($MetaFilter,$filter){
	$output="";
	$output .= "<table width=100%>";
	$output .= "<thead>";
	$output .= "<tr>";
	$output .= "<th></th>";
	$output .= "<th></th>";
	$output .= "<th>Count</th>";
	//$output .= "<th>Total</th>";
	$output .= "<th>Notes</th>";
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
		$output .= makeStatRow($filter,"Release"     ,"LaunchDate"       ,"yellow1","Date",5);
		$output .= makeStatRow($filter,"Purchased"   ,"PurchaseDateTime" ,"yellow2");
		$output .= makeStatRow($filter,"Date Added"  ,"AddedDateTime"    ,"yellow1");
		$output .= makeStatRow($filter,"Last Played" ,"lastPlayDateTime" ,"yellow2");
		$output .= makeStatRow($filter,"First Played","firstPlayDateTime","yellow1");

		$output .= makeStatRow($filter,"Count"           ,"SteamAchievements","blue1","Achievements",4);
		$output .= makeStatRow($filter,"Earned"          ,"Achievements"     ,"blue2");
		$output .= makeStatRow($filter,"Percent Complete","AchievementsPct"  ,"blue1");
		$output .= makeStatRow($filter,"Left"            ,"AchievementsLeft" ,"blue2");
		
		$output .= makeStatRow($filter,"Play Time"             ,"totalHrs"      ,"yellow1","Time",4);
		$output .= makeStatRow($filter,"Total Play Time"       ,"GrandTotal"    ,"yellow2");
		$output .= makeStatRow($filter,"Time to Beat"          ,"TimeToBeat"    ,"yellow1");
		$output .= makeStatRow($filter,"Remaining Time to Beat","TimeLeftToBeat","yellow2");

		$output .= makeStatRow($filter,"Metascore"     ,"Metascore"    ,"blue1","Reviews",4);
		$output .= makeStatRow($filter,"User Metascore","UserMetascore","blue2");
		$output .= makeStatRow($filter,"Steam Rating"  ,"SteamRating"  ,"blue1");
		$output .= makeStatRow($filter,"Review"        ,"Review"       ,"blue2");

		$output .= makeStatRow($filter,"Price"                 ,"LaunchPrice"      ,"yellow1","Launch",7);
		$output .= makeStatRow($filter,"Variance from MSRP $"  ,"LaunchVariance"   ,"yellow2");
		$output .= makeStatRow($filter,"Variance from MSRP %"  ,"LaunchVariancePct","yellow1");
		$output .= makeStatRow($filter,"$/hr"                  ,"Launchperhr"      ,"yellow2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","LaunchLess1"      ,"yellow1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"LaunchLess2"      ,"yellow2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"Launchperhrbeat"  ,"yellow1");

		$output .= makeStatRow($filter,"Price"                 ,"MSRP"         ,"blue1","MSRP",5);
		$output .= makeStatRow($filter,"$/hr"                  ,"MSRPperhr"    ,"blue2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","MSRPLess1"    ,"blue1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"MSRPLess2"    ,"blue2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"MSRPperhrbeat","blue1");

		$output .= makeStatRow($filter,"Price"                 ,"HistoricLow"        ,"yellow1","Historic Low",7);
		$output .= makeStatRow($filter,"Variance from MSRP $"  ,"HistoricVariance"   ,"yellow2");
		$output .= makeStatRow($filter,"Variance from MSRP %"  ,"HistoricVariancePct","yellow1");
		$output .= makeStatRow($filter,"$/hr"                  ,"Historicperhr"      ,"yellow2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","HistoricLess1"      ,"yellow1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"HistoricLess2"      ,"yellow2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"Historicperhrbeat"  ,"yellow1");

		$output .= makeStatRow($filter,"Price"                 ,"Paid"           ,"blue1","Paid",7);
		$output .= makeStatRow($filter,"Variance from MSRP $"  ,"PaidVariance"   ,"blue2");
		$output .= makeStatRow($filter,"Variance from MSRP %"  ,"PaidVariancePct","blue1");
		$output .= makeStatRow($filter,"$/hr"                  ,"Paidperhr"      ,"blue2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","PaidLess1"      ,"blue1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"PaidLess2"      ,"blue2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"Paidperhrbeat"  ,"blue1");

		$output .= makeStatRow($filter,"Price"                 ,"SalePrice"      ,"yellow1","Sale Price",7);
		$output .= makeStatRow($filter,"Variance from MSRP $"  ,"SaleVariance"   ,"yellow2");
		$output .= makeStatRow($filter,"Variance from MSRP %"  ,"SaleVariancePct","yellow1");
		$output .= makeStatRow($filter,"$/hr"                  ,"Saleperhr"      ,"yellow2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","SaleLess1"      ,"yellow1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"SaleLess2"      ,"yellow2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"Saleperhrbeat"  ,"yellow1");

		$output .= makeStatRow($filter,"Price"                 ,"AltSalePrice"      ,"blue1","Alt Sale",7);
		$output .= makeStatRow($filter,"Variance from MSRP $"  ,"AltSaleVariance"   ,"blue2");
		$output .= makeStatRow($filter,"Variance from MSRP %"  ,"AltSaleVariancePct","blue1");
		$output .= makeStatRow($filter,"$/hr"                  ,"Altperhr"          ,"blue2");
		$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr","AltLess1"          ,"blue1");
		$output .= makeStatRow($filter,"1 Hrs Reduces $/hr by" ,"AltLess2"          ,"blue2");
		$output .= makeStatRow($filter,"$/hr to Beat"          ,"Altperhrbeat"      ,"blue1");
	}

	if($MetaFilter=="meta" OR $MetaFilter=="both") {
		$output .= makeHeaderRow("METASTATS");
		
		$output .= makeStatRow($filter,"Hrs to next position $/hr","LaunchHrsNext1" ,"yellow1","Launch",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"LaunchHrsNext2" ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"LaunchLess2"    ,"yellow1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $5.00/hr"          ,"LaunchHrs5"     ,"yellow1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"launchhrsavg"   ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"launchhrsmean"  ,"yellow1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"launchhrsmedian","yellow1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"launchhrsgame"  ,"yellow2");

		$output .= makeStatRow($filter,"Hrs to next position $/hr","MSRPHrsNext1" ,"blue1","MSRP",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"MSRPHrsNext2" ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"MSRPLess2"    ,"blue1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $3.00/hr"          ,"MSRPHrs3"     ,"blue1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"msrphrsavg"   ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"msrphrsmean"  ,"blue1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"msrphrsmedian","blue1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"msrphrsgame"  ,"blue2");

		$output .= makeStatRow($filter,"Hrs to next position $/hr","HistoricHrsNext1" ,"yellow1","Historic Low",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"HistoricHrsNext2" ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"HistoricLess2"    ,"yellow1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $3.00/hr"          ,"HistoricHrs3"     ,"yellow1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"histhrsavg"   ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"histhrsmean"  ,"yellow1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"histhrsmedian","yellow1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"histhrsgame"  ,"yellow2");

		$output .= makeStatRow($filter,"Hrs to next position $/hr","PaidHrsNext1" ,"blue1","Paid",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"PaidHrsNext2" ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"PaidLess2"    ,"blue1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $3.00/hr"          ,"PaidHrs3"     ,"blue1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"paidhrsavg"   ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"paidhrsmean"  ,"blue1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"paidhrsmedian","blue1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"paidhrsgame"  ,"blue2");

		$output .= makeStatRow($filter,"Hrs to next position $/hr","SaleHrsNext1" ,"yellow1","Sale",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"SaleHrsNext2" ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"SaleLess2"    ,"yellow1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $3.00/hr"          ,"SaleHrs3"     ,"yellow1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"salehrsavg"   ,"yellow2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"salehrsmean"  ,"yellow1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"salehrsmedian","yellow1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"salehrsgame"  ,"yellow2");

		$output .= makeStatRow($filter,"Hrs to next position $/hr","AltHrsNext1" ,"blue1","Alt Sale",6);
		$output .= makeStatRow($filter,"Hrs to next active $/hr"  ,"AltHrsNext2" ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to $0.01 Less $/hr"   ,"AltLess2"    ,"blue1"); //Duplicate from above rows.
		$output .= makeStatRow($filter,"Hrs to $3.00/hr"          ,"AltHrs3"     ,"blue1");
		$output .= makeStatRow($filter,"Hrs to Avg/hr"            ,"althrsavg"   ,"blue2");
		//$output .= makeStatRow($filter,"Hrs to Mean/hr"           ,"althrsmean"  ,"blue1"); //Mean not working yet
		$output .= makeStatRow($filter,"Hrs to Median/hr"         ,"althrsmedian","blue1");
		$output .= makeStatRow($filter,"Hrs to XgameX $/hr"       ,"althrsgame"  ,"blue2");
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
	$usekey=objectTranslator($statname);
	
	//TODO: Change this funciton to use price objects. - IN PROGRESS
	foreach ($calculations as $key => $row) {
		if(countrow($row)) {
			$usevalue=methodTranslator($statname, $usekey, $row);
			
			if (isset($row[$usekey]) && $row[$usekey] <> null) {
				$dataset[] = array (
					"Game_ID" => $row['Game_ID'],
					$statname => $usevalue
				);
			}
		}
	}
	if($dataset == array())
	{
		trigger_error($statname . " has empty dataset");
	}
	
	array_multisort (array_column($dataset, $statname), SORT_DESC, $dataset);
	
	return $dataset;
}

function valueTranslator($row, $statname) {
	$usekey=objectTranslator($statname);
	if (isset($row[$usekey]) && $row[$usekey] <> null) {
		return methodTranslator($statname, $usekey, $row);
	}
	return null;
}

function objectTranslator($statname) {
	switch ($statname) {
		case "LaunchVariance":
		case "LaunchVariancePct":
		case "Launchperhrbeat":
		case "Launchperhr":
		case "LaunchLess1":
		case "LaunchLess2":
			return 'LaunchPriceObj';
		case "MSRPperhrbeat":
		case "MSRPperhr":
		case "MSRPLess1":
		case "MSRPLess2":
			return 'MSRPPriceObj';
		case "HistoricVariance":
		case "HistoricVariancePct":
		case "Historicperhrbeat":
		case "Historicperhr":
		case "HistoricLess1":
		case "HistoricLess2":
			return 'HistoricPriceObj';
		case "PaidVariance":
		case "PaidVariancePct":
		case "Paidperhrbeat":
		case "Paidperhr":
		case "PaidLess1":
		case "PaidLess2":
			return 'PaidPriceObj';
		case "SaleVariance":
		case "SaleVariancePct":
		case "Saleperhrbeat":
		case "Saleperhr":
		case "SaleLess1":
		case "SaleLess2":
			return 'SalePriceObj';
		case "AltSaleVariance":
		case "AltSaleVariancePct":
		case "Altperhrbeat":
		case "Altperhr":
		case "AltLess1":
		case "AltLess2":
			return 'AltPriceObj';
		default:
			return $statname;
	}
}

function methodTranslator($statname, $usekey, $row) {
	switch ($statname) {
		case "LaunchVariance":
		case "HistoricVariance":
		case "PaidVariance":
		case "SaleVariance":
		case "AltSaleVariance":
			return $row[$usekey]->getVarianceFromMSRP();
		case "LaunchVariancePct":
		case "HistoricVariancePct":
		case "PaidVariancePct":
		case "SaleVariancePct":
		case "AltSaleVariancePct":
			return $row[$usekey]->getVarianceFromMSRPpct();
		case "Launchperhrbeat":
		case "MSRPperhrbeat":
		case "Historicperhrbeat":
		case "Paidperhrbeat":
		case "Saleperhrbeat":
		case "Altperhrbeat":
			return $row[$usekey]->getPricePerHourOfTimeToBeat();
		case "Launchperhr":
		case "MSRPperhr":
		case "Historicperhr":
		case "Paidperhr":
		case "Saleperhr":
		case "Altperhr":
			return $row[$usekey]->getPricePerHourOfTimePlayed();
		case "LaunchLess1":
		case "MSRPLess1":
		case "HistoricLess1":
		case "PaidLess1":
		case "SaleLess1":
		case "AltLess1":
			return $row[$usekey]->getPricePerHourOfTimePlayedReducedAfter1Hour();
		case "LaunchLess2":
		case "MSRPLess2":
		case "HistoricLess2":
		case "PaidLess2":
		case "SaleLess2":
		case "AltLess2":
			return $row[$usekey]->getHoursTo01LessPerHour();
		default:
			return isset($row[$usekey]) ? $row[$usekey] : null;
	}
}

function printStatRow2($stats){
	$calculations=reIndexArray(getCalculations(),"Game_ID");
	
	if(($stats['Print']['Total'] ?? "") == "") {
		return ""; //@codeCoverageIgnore
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
		$stats['salehrsgame']=$row;
		$stats['salehrsmedian']=$row;
		$stats['salehrsmean']=$row;
		$stats['salehrsavg']=$row;
		$stats['paidhrsgame']=$row;
		$stats['paidhrsmedian']=$row;
		$stats['paidhrsmean']=$row;
		$stats['paidhrsavg']=$row;
		$stats['histhrsgame']=$row;
		$stats['histhrsmedian']=$row;
		$stats['histhrsmean']=$row;
		$stats['histhrsavg']=$row;
		$stats['msrphrsgame']=$row;
		$stats['msrphrsmedian']=$row;
		$stats['msrphrsmean']=$row;
		$stats['msrphrsavg']=$row;
		$stats['launchhrsmedian']=$row;
		$stats['launchhrsgame']=$row;
		$stats['launchhrsavg']=$row;
				
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

		$stats['HistoricLow']=getStatRow($filter,'HistoricLow');
		$stats['HistoricVariance']=getStatRow($filter,'HistoricVariance');
		$stats['HistoricVariancePct']=getStatRow($filter,'HistoricVariancePct');
		$stats['Historicperhr']=getStatRow($filter,'Historicperhr');
		$stats['HistoricLess1']=getStatRow($filter,'HistoricLess1');
		$stats['HistoricLess2']=getStatRow($filter,'HistoricLess2');
		$stats['Historicperhrbeat']=getStatRow($filter,'Historicperhrbeat');

		$stats['Paid']=getStatRow($filter,'Paid');
		$stats['PaidVariance']=getStatRow($filter,'PaidVariance');
		$stats['PaidVariancePct']=getStatRow($filter,'PaidVariancePct');
		$stats['Paidperhr']=getStatRow($filter,'Paidperhr');
		$stats['PaidLess1']=getStatRow($filter,'PaidLess1');
		$stats['PaidLess2']=getStatRow($filter,'PaidLess2');
		$stats['Paidperhrbeat']=getStatRow($filter,'Paidperhrbeat');

		$stats['SalePrice']=getStatRow($filter,'SalePrice');
		$stats['SaleVariance']=getStatRow($filter,'SaleVariance');
		$stats['SaleVariancePct']=getStatRow($filter,'SaleVariancePct');
		$stats['Saleperhr']=getStatRow($filter,'Saleperhr');
		$stats['SaleLess1']=getStatRow($filter,'SaleLess1');
		$stats['SaleLess2']=getStatRow($filter,'SaleLess2');
		$stats['Saleperhrbeat']=getStatRow($filter,'Saleperhrbeat');

		$stats['AltSalePrice']=getStatRow($filter,'AltSalePrice');
		$stats['AltSaleVariance']=getStatRow($filter,'AltSaleVariance');
		$stats['AltSaleVariancePct']=getStatRow($filter,'AltSaleVariancePct');
		$stats['Altperhr']=getStatRow($filter,'Altperhr');
		$stats['AltLess1']=getStatRow($filter,'AltLess1');
		$stats['AltLess2']=getStatRow($filter,'AltLess2');
		$stats['Altperhrbeat']=getStatRow($filter,'Altperhrbeat');
		
		$stats['LaunchHrsNext1']=getStatRow($filter,'LaunchHrsNext1');
		$stats['LaunchHrsNext2']=getStatRow($filter,'LaunchHrsNext2');
		$stats['LaunchHrs5']=getStatRow($filter,'LaunchHrs5');

		$stats['MSRPHrsNext1']=getStatRow($filter,'MSRPHrsNext1');
		$stats['MSRPHrsNext2']=getStatRow($filter,'MSRPHrsNext2');
		$stats['MSRPHrs3']=getStatRow($filter,'MSRPHrs3');

		$stats['HistoricHrsNext1']=getStatRow($filter,'HistoricHrsNext1');
		$stats['HistoricHrsNext2']=getStatRow($filter,'HistoricHrsNext2');
		$stats['HistoricHrs3']=getStatRow($filter,'HistoricHrs3');

		$stats['PaidHrsNext1']=getStatRow($filter,'PaidHrsNext1');
		$stats['PaidHrsNext2']=getStatRow($filter,'PaidHrsNext2');
		$stats['PaidHrs3']=getStatRow($filter,'PaidHrs3');

		$stats['SaleHrsNext1']=getStatRow($filter,'SaleHrsNext1');
		$stats['SaleHrsNext2']=getStatRow($filter,'SaleHrsNext2');
		$stats['SaleHrs3']=getStatRow($filter,'SaleHrs3');

		$stats['AltHrsNext1']=getStatRow($filter,'AltHrsNext1');
		$stats['AltHrsNext2']=getStatRow($filter,'AltHrsNext2');
		$stats['AltHrs3']=getStatRow($filter,'AltHrs3');

		$GLOBALS["METASTATS"] = $stats;
		return $stats;
	}
}

function makeDetailTable($filter,$statname){
	$dataset=makeStatDataSet($filter,$statname);
	$statrow=getStatRow($filter,$statname);
	
	$output = DetailDataTable($dataset,$statrow);
	$output .= arrayTable($statrow);
	
	return $output;
	
}

function getStatRow($filter,$statname){
	//STUB to support metastats overhaul
	if (isset($GLOBALS["METASTATS"][$statname]))
	{
		return $GLOBALS["METASTATS"][$statname];
	} else {
		
		$row['Title']=$statname;
		
		//Always true?
		if($statname<>null) {
			$dataset=makeStatDataSet($filter,$statname);
			$val=getOnlyValues($dataset,$statname);
			
			$onlydata=$val['basedata'];
			$onlydatamode=$val['modedata'];
			
			$row['Total']=count($onlydata);
			$row['Sum']=array_sum($onlydata);

			//Average (Mean)
			if($row['Total']==0) {
				trigger_error($statname . " Total not set");
				return;
			} else {
				$row['Average']=$row['Sum']/$row['Total'];
			}
			
			//Median
			$middleVal = floor(($row['Total'] - 1) / 2);
			if($row['Total'] % 2) { 
				$row['Median']=$onlydata[$middleVal];
			} else {
				$row['Median']=(($onlydata[$middleVal] + $onlydata[$middleVal+1]) / 2);				
			}
			
			//Mode
			$values = array_count_values($onlydatamode); 
			//var_dump($values);
			$row['Mode']= array_search(max($values), $values);
			
			//Harmonic Mean (Not working)
			//$row['HarMean']=stats_harmonic_mean($onlydata);  //only in PECL library
			$row['HarMean']=null;
			
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
					case "HistoricLow":
					case "HistoricVariance":
					case "HistoricVariancePct":
					case "Historicperhrbeat":
					case "Historicperhr":
					case "HistoricLess1":
					case "HistoricLess2":
					case "Paid":
					case "PaidVariance":
					case "PaidVariancePct":
					case "Paidperhr":
					case "PaidLess1":
					case "PaidLess2":
					case "Paidperhrbeat":
					case "SalePrice":
					case "SaleVariance":
					case "SaleVariancePct":
					case "Saleperhr":
					case "SaleLess1":
					case "SaleLess2":
					case "Saleperhrbeat":
					case "AltSalePrice":
					case "AltSaleVariance":
					case "AltSaleVariancePct":
					case "Altperhr":
					case "AltLess1":
					case "AltLess2":
					case "Altperhrbeat":
					case "LaunchHrsNext1":
					case "LaunchHrsNext2":
					case "LaunchHrs5":
					case "MSRPHrsNext1":
					case "MSRPHrsNext2":
					case "MSRPHrs3":
					case "HistoricHrsNext1":
					case "HistoricHrsNext2":
					case "HistoricHrs3":
					case "PaidHrsNext1":
					case "PaidHrsNext2":
					case "PaidHrs3":
					case "SaleHrsNext1":
					case "SaleHrsNext2":
					case "SaleHrs3":
					case "AltHrsNext1":
					case "AltHrsNext2":
					case "AltHrs3":
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
				$row['Print']['Average']=date('Y&#8209;m&#8209;d', round($row['Average'],0));
				//$row['Print']['HarMean']=date('Y-m-d', $row['HarMean']);
				$row['Print']['HarMean']=null;
				$row['Print']['Median']=date('Y&#8209;m&#8209;d', round($row['Median'],0));
				$row['Print']['Mode']=date('Y&#8209;m&#8209;d', $row['Mode']);
				$row['Print']['Max1']=date('Y&#8209;m&#8209;d', $row['Max1']);
				$row['Print']['Max2']=date('Y&#8209;m&#8209;d', $row['Max2']);
				$row['Print']['Min1']=date('Y&#8209;m&#8209;d', $row['Min1']);
				$row['Print']['Min2']=date('Y&#8209;m&#8209;d', $row['Min2']);
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
				$row['Print']['HarMean']=null;
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
			case "LaunchLess2":
			case "Launchperhrbeat":
			case "MSRP":
			case "MSRPperhr":
			case "MSRPLess2":
			case "MSRPperhrbeat":
			case "HistoricLow":
			case "HistoricVariance":
			case "HistoricVariancePct":
			case "Historicperhrbeat":
			case "Historicperhr":
			case "HistoricLess2":
			case "Paid":
			case "PaidVariance":
			case "PaidVariancePct":
			case "Paidperhr":
			case "PaidLess2":
			case "Paidperhrbeat":
			case "SalePrice":
			case "SaleVariance":
			case "SaleVariancePct":
			case "Saleperhr":
			case "SaleLess2":
			case "Saleperhrbeat":
			case "AltSalePrice":
			case "AltSaleVariance":
			case "AltSaleVariancePct":
			case "Altperhr":
			case "AltLess2":
			case "Altperhrbeat":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=sprintf("$%.2f", $row['Average']);
				//$row['Print']['HarMean']=sprintf("%.2f", $row['HarMean']);
				$row['Print']['HarMean']=null;
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
				$row['Print']['HarMean']=null;
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
			case "LaunchLess1":
			case "MSRPLess1":
			case "HistoricLess1":
			case "PaidLess1":
			case "SaleLess1":
			case "AltLess1":
			case "LaunchHrsNext1":
			case "LaunchHrsNext2":
			case "LaunchHrs5":
			case "MSRPHrsNext1":
			case "MSRPHrsNext2":
			case "MSRPHrs3":
			case "HistoricHrsNext1":
			case "HistoricHrsNext2":
			case "HistoricHrs3":
			case "PaidHrsNext1":
			case "PaidHrsNext2":
			case "PaidHrs3":
			case "SaleHrsNext1":
			case "SaleHrsNext2":
			case "SaleHrs3":
			case "AltHrsNext1":
			case "AltHrsNext2":
			case "AltHrs3":
				$row['Print']['Total']=number_format($row['Total'],0);
				$row['Print']['Average']=timeduration($row['Average'],"seconds");
				//$row['Print']['HarMean']=timeduration($row['HarMean'],"seconds");
				$row['Print']['HarMean']=null;
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
				$row['Print']['HarMean']=null;
				$row['Print']['Median']=timeduration($row['Median'],"hours");
				$row['Print']['Mode']=timeduration($row['Mode'],"hours");
				$row['Print']['Max1']=timeduration($row['Max1'],"hours");
				$row['Print']['Max2']=timeduration($row['Max2'],"hours");
				$row['Print']['Min1']=timeduration($row['Min1'],"hours");
				$row['Print']['Min2']=timeduration($row['Min2'],"hours");
				break;
			default:
				//var_dump($statname);
				$row['Print']['Total']=null;
				$row['Print']['Average']=null;
				$row['Print']['HarMean']=null;
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
			case "HistoricLow":
			case "HistoricVariance":
			case "HistoricVariancePct":
			case "Historicperhrbeat":
			case "Historicperhr":
			case "HistoricLess1":
			case "HistoricLess2":
			case "Paid":
			case "PaidVariance":
			case "PaidVariancePct":
			case "Paidperhr":
			case "PaidLess1":
			case "PaidLess2":
			case "Paidperhrbeat":
			case "SalePrice":
			case "SaleVariance":
			case "SaleVariancePct":
			case "Saleperhr":
			case "SaleLess1":
			case "SaleLess2":
			case "Saleperhrbeat":
			case "AltSalePrice":
			case "AltSaleVariance":
			case "AltSaleVariancePct":
			case "Altperhr":
			case "AltLess1":
			case "AltLess2":
			case "Altperhrbeat":
			case "LaunchHrsNext1":
			case "LaunchHrsNext2":
			case "LaunchHrs5":
			case "MSRPHrsNext1":
			case "MSRPHrsNext2":
			case "MSRPHrs3":
			case "HistoricHrsNext1":
			case "HistoricHrsNext2":
			case "HistoricHrs3":
			case "PaidHrsNext1":
			case "PaidHrsNext2":
			case "PaidHrs3":
			case "SaleHrsNext1":
			case "SaleHrsNext2":
			case "SaleHrs3":
			case "AltHrsNext1":
			case "AltHrsNext2":
			case "AltHrs3":
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
			case "LaunchLess2":
			case "Launchperhrbeat":
			case "MSRP":
			case "MSRPperhr":
			case "MSRPLess2":
			case "MSRPperhrbeat":
			case "HistoricLow":
			case "HistoricVariance":
			case "HistoricVariancePct":
			case "Historicperhrbeat":
			case "Historicperhr":
			case "HistoricLess2":
			case "Paid":
			case "PaidVariance":
			case "PaidVariancePct":
			case "Paidperhr":
			case "PaidLess2":
			case "Paidperhrbeat":
			case "SalePrice":
			case "SaleVariance":
			case "SaleVariancePct":
			case "Saleperhr":
			case "SaleLess2":
			case "Saleperhrbeat":
			case "AltSalePrice":
			case "AltSaleVariance":
			case "AltSaleVariancePct":
			case "Altperhr":
			case "AltLess2":
			case "Altperhrbeat":
				$onlydata[]=$statrow[$statname];
				$onlydatamode[]=sprintf("%.2f", $statrow[$statname]);
				break;
			case "LaunchLess1":
			case "MSRPLess1":
			case "HistoricLess1":
			case "PaidLess1":
			case "SaleLess1":
			case "AltLess1":
			case "LaunchHrsNext1":
			case "LaunchHrsNext2":
			case "LaunchHrs5":
			case "MSRPHrsNext1":
			case "MSRPHrsNext2":
			case "MSRPHrs3":
			case "HistoricHrsNext1":
			case "HistoricHrsNext2":
			case "HistoricHrs3":
			case "PaidHrsNext1":
			case "PaidHrsNext2":
			case "PaidHrs3":
			case "SaleHrsNext1":
			case "SaleHrsNext2":
			case "SaleHrs3":
			case "AltHrsNext1":
			case "AltHrsNext2":
			case "AltHrs3":
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