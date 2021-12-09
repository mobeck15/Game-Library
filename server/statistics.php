<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
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
	/* METASTATS */
	
	if($_GET['meta']=="meta" OR $_GET['meta']=="both") {
		$target1=3;
		$target2=.3;
		$TargetGameID=514;
		//$game="Skyrim";
		
		$criteria['Value']=0;
		$datatype="Duration";
		$datatype2='hours';

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