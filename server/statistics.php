<?php
$GLOBALS['rootpath']=".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require_once $GLOBALS['rootpath']."/inc/getmetastats.inc.php";

$title="Statistics";
echo Get_Header($title);
?>
<style>
.blue0 {background-color: #0033cc;}
.blue1 {background-color: #0033cc;}
.blue2 {background-color: #0099ff;}
.yellow0 {background-color: #ff9900;}
.yellow1 {background-color: #ff9900;}
.yellow2 {background-color: #ffcc00;}
.broken1 {background-color: #737373;}
.broken2 {background-color: #999999;}
td.value {width:75;}
td.text  {width:100;}
.yellow1 td a:link {color: #000000;}
.yellow2 td a:link {color: #000000;}
.yellow1 td a:visited {color: #000000;}
.yellow2 td a:visited {color: #000000;}

</style>
<?php

if(isset($_GET['filter'])) {
	$calculations=reIndexArray(getCalculations(),"Game_ID");
	echo makeStatTable($_GET['meta'],$_GET['filter']);
	
	$stats=getmetastats($_GET['filter']);
	//If Base Stats or Both Base and Meta are requested, show Base Stats
	if($_GET['meta']=="base" OR $_GET['meta']=="both") {
		
		$datatype=$datatype2=0;
		$criteria['Operator']="gt";
		$criteria['Value']=0;		
		$criteria['round']=0;		
		
		echo "<tr class='yellow1'>";
		echo "<th rowspan=7 class='yellow0'>Historic Low</th><th>Price</th>";
		echo printStatRow2($stats['HistoricLow']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Variance from MSRP $</th>";
		echo printStatRow2($stats['HistoricVariance'],"$".number_format($stats['HistoricLow']['Total']-$stats['MSRP']['Total'],2))	;
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Variance from MSRP %</th>";
		echo printStatRow2($stats['HistoricVariancePct'],number_format($stats['HistoricLow']['Total']/$stats['MSRP']['Total'],2)."%");
		echo "</tr>";		
		echo "<tr class='yellow2'>";
		echo "<th>$/hr</th>";
		echo printStatRow2($stats['Historicperhr'],"$".number_format(getPriceperhour($stats['HistoricLow']['Total'],$stats['totalHrs']['Total']),2));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['HistoricLess2'],timeduration(getHourstoXless($stats['HistoricLow']['Total'],$stats['totalHrs']['Total'],.01),"hours"));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>1 Hrs Reduces $/hr by</th>";
		echo printStatRow2($stats['HistoricLess1'],"$".number_format(getLessXhour($stats['HistoricLow']['Total'],$stats['totalHrs']['Total'],1),4));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>$/hr to Beat</th>";
		echo printStatRow2($stats['Historicperhrbeat'],"$".number_format($stats['HistoricLow']['Total']/$stats['TimeToBeat']['Total'],2));
		echo "</tr>";
		
		echo "<tr class='blue1'>";
		echo "<th rowspan=7 class='blue0'>Paid</th><th>Price</th>";
		echo printStatRow2($stats['Paid']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Variance from MSRP $</th>";
		echo printStatRow2($stats['PaidVariance'],"$".number_format($stats['Paid']['Total']-$stats['MSRP']['Total'],2))	;
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Variance from MSRP %</th>";
		echo printStatRow2($stats['PaidVariancePct'],number_format($stats['Paid']['Total']/$stats['MSRP']['Total'],2)."%");
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>$/hr</th>";
		echo printStatRow2($stats['Paidperhr'],"$".number_format(getPriceperhour($stats['Paid']['Total'],$stats['totalHrs']['Total']),2));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['PaidLess2'],timeduration(getHourstoXless($stats['Paid']['Total'],$stats['totalHrs']['Total'],.01),"hours"));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>1 Hrs Reduces $/hr by</th>";
		echo printStatRow2($stats['PaidLess1'],"$".number_format(getLessXhour($stats['Paid']['Total'],$stats['totalHrs']['Total'],1),4));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>$/hr to Beat</th>";
		echo printStatRow2($stats['Paidperhrbeat'],"$".number_format($stats['Paid']['Total']/$stats['TimeToBeat']['Total'],2));
		echo "</tr>";
		
		echo "<tr class='yellow1'>";
		echo "<th rowspan=7 class='yellow0'>Sale Price</th><th>Price</th>";
		echo printStatRow2($stats['SalePrice']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Variance from MSRP $</th>";
		echo printStatRow2($stats['SaleVariance'],"$".number_format($stats['SalePrice']['Total']-$stats['MSRP']['Total'],2))	;
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Variance from MSRP %</th>";
		echo printStatRow2($stats['SaleVariancePct'],number_format($stats['SalePrice']['Total']/$stats['MSRP']['Total'],2)."%");
		echo "</tr>";		
		echo "<tr class='yellow2'>";
		echo "<th>$/hr</th>";
		echo printStatRow2($stats['Saleperhr'],"$".number_format(getPriceperhour($stats['SalePrice']['Total'],$stats['totalHrs']['Total']),2));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['SaleLess2'],timeduration(getHourstoXless($stats['SalePrice']['Total'],$stats['totalHrs']['Total'],.01),"hours"));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>1 Hrs Reduces $/hr by</th>";
		echo printStatRow2($stats['SaleLess1'],"$".number_format(getLessXhour($stats['SalePrice']['Total'],$stats['totalHrs']['Total'],1),4));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>$/hr to Beat</th>";
		echo printStatRow2($stats['Saleperhrbeat'],"$".number_format($stats['SalePrice']['Total']/$stats['TimeToBeat']['Total'],2));
		echo "</tr>";
		
		$datatype="Numeric";
		echo "<tr class='blue1'>";
		echo "<th rowspan=7 class='blue0'>Alt Sale</th><th>Price</th>";
		echo printStatRow2($stats['AltSalePrice']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Variance from MSRP $</th>";
		echo printStatRow2($stats['AltSaleVariance'],"$".number_format($stats['AltSalePrice']['Total']-$stats['MSRP']['Total'],2))	;
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Variance from MSRP %</th>";
		echo printStatRow2($stats['AltSaleVariancePct'],number_format($stats['AltSalePrice']['Total']/$stats['MSRP']['Total'],2)."%");
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>$/hr</th>";
		echo printStatRow2($stats['Altperhr'],"$".number_format(getPriceperhour($stats['AltSalePrice']['Total'],$stats['totalHrs']['Total']),2));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['AltLess2'],timeduration(getHourstoXless($stats['AltSalePrice']['Total'],$stats['totalHrs']['Total'],.01),"hours"));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>1 Hrs Reduces $/hr by</th>";
		echo printStatRow2($stats['AltLess1'],"$".number_format(getLessXhour($stats['AltSalePrice']['Total'],$stats['totalHrs']['Total'],1),4));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>$/hr to Beat</th>";
		echo printStatRow2($stats['Altperhrbeat'],"$".number_format($stats['AltSalePrice']['Total']/$stats['TimeToBeat']['Total'],2));
		echo "</tr>";
		/* */
	}
	/* METASTATS */
	
	if($_GET['meta']=="meta" OR $_GET['meta']=="both") {
		echo makeHeaderRow("METASTATS");
		$target1=3;
		$target2=.3;
		$TargetGameID=514;
		//$game="Skyrim";
		
		$criteria['Value']=0;
		$datatype="Duration";
		$datatype2='hours';
		echo "<tr class='yellow1'><th rowspan=8 class='yellow0'>Launch</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['LaunchHrsNext1']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['LaunchHrsNext2']);
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['LaunchLess2']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to $5.00/hr</th>";
		echo printStatRow2($stats['LaunchHrs5'],"$5.00");
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['launchhrsavg'],sprintf("$%.2f",$stats['LaunchPrice']['Average']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['launchhrsmean'],sprintf("$%.2f",$stats['LaunchPrice']['HarMean']));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['launchhrsmedian'],sprintf("$%.2f",$stats['LaunchPrice']['Median']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['launchhrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['Launchperhr']));
		echo "</tr>";
		
		echo "<tr class='blue1'><th rowspan=8 class='blue0'>MSRP</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['MSRPHrsNext1']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['MSRPHrsNext2']);
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['MSRPLess2']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to $3.00/hr</th>";
		echo printStatRow2($stats['MSRPHrs3'],"$3.00");
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['msrphrsavg'],sprintf("$%.2f",$stats['MSRP']['Average']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['msrphrsmean'],sprintf("$%.2f",$stats['MSRP']['HarMean']));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['msrphrsmedian'],sprintf("$%.2f",$stats['MSRP']['Median']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['msrphrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['MSRPperhr']));
		echo "</tr>";
		
		echo "<tr class='yellow1'><th rowspan=8 class='yellow0'>Historic Low</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['HistoricHrsNext1']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['HistoricHrsNext2']);
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['HistoricLess2']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to $3.00/hr</th>";
		echo printStatRow2($stats['HistoricHrs3'],"$3.00");
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['histhrsavg'],sprintf("$%.2f",$stats['HistoricLow']['Average']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['histhrsmean'],sprintf("$%.2f",$stats['HistoricLow']['HarMean']));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['histhrsmedian'],sprintf("$%.2f",$stats['HistoricLow']['Median']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['histhrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['Historicperhr']));
		echo "</tr>";

		echo "<tr class='blue1'><th rowspan=8 class='blue0'>Paid</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['PaidHrsNext1']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['PaidHrsNext2']);
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['PaidLess2']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to $3.00/hr</th>";
		echo printStatRow2($stats['PaidHrs3'],"$3.00");
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['paidhrsavg'],sprintf("$%.2f",$stats['Paid']['Average']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['paidhrsmean'],sprintf("$%.2f",$stats['Paid']['HarMean']));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['paidhrsmedian'],sprintf("$%.2f",$stats['Paid']['Median']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['paidhrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['Paidperhr']));
		echo "</tr>";

		
		echo "<tr class='yellow1'><th rowspan=8 class='yellow0'>Sale</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['SaleHrsNext1']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['SaleHrsNext2']);
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['SaleLess2']);
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to $3.00/hr</th>";
		echo printStatRow2($stats['SaleHrs3'],"$3.00");
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['salehrsavg'],sprintf("$%.2f",$stats['SalePrice']['Average']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['salehrsmean'],sprintf("$%.2f",$stats['SalePrice']['HarMean']));
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['salehrsmedian'],sprintf("$%.2f",$stats['SalePrice']['Median']));
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['salehrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['Saleperhr']));
		echo "</tr>";

		echo "<tr class='blue1'><th rowspan=8 class='blue0'>Alt Sale</th>";
		echo "<th>Hrs to next position $/hr</th>";
		echo printStatRow2($stats['AltHrsNext1']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to next active $/hr</th>";
		echo printStatRow2($stats['AltHrsNext2']);
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to $0.01 Less $/hr</th>";
		echo printStatRow2($stats['AltLess2']);
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to $3.00/hr</th>";
		echo printStatRow2($stats['AltHrs3'],"$3.00");
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo printStatRow2($stats['althrsavg'],sprintf("$%.2f",$stats['AltSalePrice']['Average']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo printStatRow2($stats['althrsmean'],sprintf("$%.2f",$stats['AltSalePrice']['HarMean']));
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo printStatRow2($stats['althrsmedian'],sprintf("$%.2f",$stats['AltSalePrice']['Median']));
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to <a href='viewgame.php?id=".$TargetGameID."' target='_blank'>".$calculations[$TargetGameID]['Title']."</a> $/hr</th>";
		echo printStatRow2($stats['althrsgame'],sprintf("$%.2f",$calculations[$TargetGameID]['Altperhr']));
		echo "</tr>";

		/* META-META STATS */
		echo makeHeaderRow("META-METASTATS");
		echo "<tr class='yellow1'>";
		echo "<th rowspan=3 class='yellow0'>Launch of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/*echo "<tr class='yellow2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
//		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['LaunchPrice']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['LaunchPrice']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['LaunchPrice']['Median'])."</td>"; 
		echo "</tr>";

		echo "<tr class='blue1'>";
		echo "<th rowspan=3 class='blue0'>MSRP of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/* echo "<tr class='blue2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
//		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['MSRP']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['MSRP']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['MSRP']['Median'])."</td>"; 
		echo "</tr>";

		
		
		echo "<tr class='yellow1'>";
		echo "<th rowspan=3 class='yellow0'>Historic of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/* echo "<tr class='yellow2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>";*/
//		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['HistoricLow']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['HistoricLow']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['HistoricLow']['Median'])."</td>"; 
		echo "</tr>";

		echo "<tr class='blue1'>";
		echo "<th rowspan=3 class='blue0'>Paid of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/* echo "<tr class='blue2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>";*/
//		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['Paid']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['Paid']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['Paid']['Median'])."</td>"; 
		echo "</tr>";

		echo "<tr class='yellow1'>";
		echo "<th rowspan=3 class='yellow0'>Sale of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/*echo "<tr class='yellow2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>";*/
//		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['SalePrice']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['SalePrice']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='yellow1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['SalePrice']['Median'])."</td>"; 
		echo "</tr>";

		echo "<tr class='blue1'>";
		echo "<th rowspan=3 class='blue0'>Alt Sale of all</th>";
		/* Duplicate stat from above * /
		echo "<th>Hrs to next position $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>"; */
		/*echo "<tr class='blue2'>";
		echo "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		echo "<td></td>";
		echo "<td></td>"; 
		//echo printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		echo "</tr>";*/
//		echo "<tr class='blue1'>";
		echo "<th>Hrs to Avg/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['AltSalePrice']['Average'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue2'>";
		echo "<th>Hrs to Mean/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['AltSalePrice']['HarMean'])."</td>"; 
		echo "</tr>";
		echo "<tr class='blue1'>";
		echo "<th>Hrs to Median/hr</th>";
		echo "<td></td>";
		echo "<td>".sprintf("$%.2f",$stats['AltSalePrice']['Median'])."</td>"; 
		echo "</tr>";

	}
	
	echo "</tbody>";
	echo "</table>";
	
	if(isset($_GET['stat'])) {
		echo "<a name='detail'>";
		echo makeDetailTable($_GET['filter'],$_GET['stat']);
	}
	
} else {
	?>
	<form action="statistics.php" method="GET">
	Primary Filter: <select name="filter">
		<option value="All" SELECTED >All</option>
		<option value="Active">Active</option>
		<option value="Hold">On Hold</option>
		<option value="Unplayed">Unplayed</option>
		<option value="Inactive">Inactive</option>
	</select></br>
	Metastatistics: <select name="meta">
		<option value="meta">Only Metastatistics</option>
		<option value="base">Only Base Statistics</option>
		<option value="both" SELECTED >Show Both</option>
	</select></br>
	<input type="submit" value="Submit">
	</form>
	
	<?php
}	
	
echo Get_Footer(); 
?>