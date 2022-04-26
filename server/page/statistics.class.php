<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getmetastats.inc.php";

class statisticsPage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="Statistics";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$output .= "
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

</style>";

if(isset($_GET['filter'])) {
	$calculations=reIndexArray(getCalculations(),"Game_ID");
	$output .= makeStatTable($_GET['meta'],$_GET['filter']);
	
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
		$output .= makeHeaderRow("META-METASTATS");
		$output .= "<tr class='yellow1'>";
		$output .= "<th rowspan=3 class='yellow0'>Launch of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/*$output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
//		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['LaunchPrice']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['LaunchPrice']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['LaunchPrice']['Median'])."</td>"; 
		$output .= "</tr>";

		$output .= "<tr class='blue1'>";
		$output .= "<th rowspan=3 class='blue0'>MSRP of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/* $output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
//		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['MSRP']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['MSRP']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['MSRP']['Median'])."</td>"; 
		$output .= "</tr>";

		
		
		$output .= "<tr class='yellow1'>";
		$output .= "<th rowspan=3 class='yellow0'>Historic of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/* $output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>";*/
//		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['HistoricLow']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['HistoricLow']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['HistoricLow']['Median'])."</td>"; 
		$output .= "</tr>";

		$output .= "<tr class='blue1'>";
		$output .= "<th rowspan=3 class='blue0'>Paid of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/* $output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>";*/
//		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['Paid']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['Paid']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['Paid']['Median'])."</td>"; 
		$output .= "</tr>";

		$output .= "<tr class='yellow1'>";
		$output .= "<th rowspan=3 class='yellow0'>Sale of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/*$output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>";*/
//		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['SalePrice']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['SalePrice']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='yellow1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['SalePrice']['Median'])."</td>"; 
		$output .= "</tr>";

		$output .= "<tr class='blue1'>";
		$output .= "<th rowspan=3 class='blue0'>Alt Sale of all</th>";
		/* Duplicate stat from above * /
		$output .= "<th>Hrs to next position $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>"; */
		/*$output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to next position 1 hour gets $/hr</th>";
		$statname="";
		$output .= "<td></td>";
		$output .= "<td></td>"; 
		//$output .= printStatRow($StatPlan[$statname],$Statistics[$StatPlan[$statname]['datakey']],$calculations);
		$output .= "</tr>";*/
//		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Avg/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['AltSalePrice']['Average'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue2'>";
		$output .= "<th>Hrs to Mean/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['AltSalePrice']['HarMean'])."</td>"; 
		$output .= "</tr>";
		$output .= "<tr class='blue1'>";
		$output .= "<th>Hrs to Median/hr</th>";
		$output .= "<td></td>";
		$output .= "<td>".sprintf("$%.2f",$stats['AltSalePrice']['Median'])."</td>"; 
		$output .= "</tr>";

	}
	
	$output .= "</tbody>";
	$output .= "</table>";
	
	if(isset($_GET['stat'])) {
		$output .= "<a name='detail'>";
		$output .= makeDetailTable($_GET['filter'],$_GET['stat']);
	}
	
} else {
	$output .= '<form action="statistics.php" method="GET">
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
	</form>';
}
		return $output;
	}
}	