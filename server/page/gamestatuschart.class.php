<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";

class gamestatuschartPage extends Page
{
	public function __construct() {
		$this->title="Status Charts";
	}
	
	public function buildHtmlBody(){
		$output="";
	
	//TODO: add code for presets
	/*
if(isset($_GET['preset'])){
	switch($_GET['preset']) { 
		case "7days":
			break;
		case "2weeks":
			break;
		case "30days":
			break;
		case "ThisMonth":
			break;
		case "1year":
			break;
	}
}
*/

if(isset($_GET['start'])){
	$startDate=strtotime($_GET['start']);
} else {
	//$startDate=mktime(0, 0, 0, 1, 1, date("Y"));
	$startDate=mktime(0, 0, 0, 1, 1, 2000);
}

if(isset($_GET['end'])){
	$endDate=strtotime($_GET['end']);
} else {
	$endDate=time();
}

//$settings=$this->data()->getSettings();
$calculations = $this->data()->getCalculations();

$conn=get_db_connection();

$History=getHistoryCalculations("",$conn,$startDate,$endDate);
$activity=getActivityCalculations("",$History,$conn);

$PreHistory=getHistoryCalculations("",$conn,mktime(0, 0, 0, 1, 1, 2000),$startDate-60*60*24);
$Preactivity=getActivityCalculations("",$PreHistory,$conn);

$conn->close();	

//$calculations=reIndexArray($calculations,"Game_ID");
$output .= '<table><tr><td valign=top>
Date Range: '. date("Y/m/d",$startDate) .' - '. date("Y/m/d",$endDate).'
<ul>
<li><a href="'. $_SERVER['SCRIPT_NAME'].'">All Time</a></li>
<li>Year</li><ul>';
$useyear=date("Y");
do {
	$output .= "<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". $useyear."-1-1&end=". $useyear.">-12-31'>". $useyear."</a>";
	if($useyear==date("Y")) { 
		$output .= " This Year"; 
	}
	$output .= "</li>";
	$useyear--;
} while($useyear>=2013);
	
$output .= "</ul>
<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m-d",strtotime("1 Year Ago"))."&end=". date("Y-m-d")."'>365 Days</a></li>
<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m")."-1&end=". date("Y-m-t")."'>This Month</a></li>
<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m-d",strtotime("1 Month Ago"))."&end=". date("Y-m-d")."'>30 Days</a></li>
<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m-d",strtotime("2 Weeks Ago"))."&end=". date("Y-m-d")."'>2 Weeks</a></li>
<li class='hidden'><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m-d",strtotime("14 Days Ago"))."&end=". date("Y-m-d")."'>14 Days</a></li>
<li class='hidden'><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y")."-1-1&end=". date("Y")."-12-31'>This Week</a></li>
<li><a href='". $_SERVER['SCRIPT_NAME']."?start=". date("Y-m-d",strtotime("7 Days Ago"))."&end=". date("Y-m-d")."'>7 Days</a></li>
</ul>

<table>
<thead>
<tr>
<th>Games</th>
<th>Purchased</th>
<th>Last Play</th>
<th>Achievements</th>
<th>Status</th>
<th>Last Rating</th>
<th>Last Beat</th>
<th>Grand Total</th>
</tr>
</thead>
<tbody>";
//DONE: Add calculation wiget for GOTY - This exists on chartdata.php
//TODO: Some filtered lists show negative playtime values?

foreach ($calculations as $key => &$row) {
	$currentTime=(isset($activity[$row['Game_ID']]['GrandTotal'])?$activity[$row['Game_ID']]['GrandTotal']:0);
	$preTime=(isset($Preactivity[$row['Game_ID']]['GrandTotal'])?$Preactivity[$row['Game_ID']]['GrandTotal']:0);
	$row['currentTime']=$currentTime-$preTime;
	$Sortby1[$key]  = $row['currentTime'];
}
array_multisort($Sortby1, SORT_DESC, $calculations);

foreach ($calculations as $gameRow){
	if(isset($activity[$gameRow['Game_ID']]['lastplay'])){
		$LastPlay=strtotime($activity[$gameRow['Game_ID']]['lastplay']);
	} else {
		$LastPlay=0;
	}
	
	if($gameRow['Playable']==true AND(
	 ($gameRow['PurchaseDateTime']->getTimestamp()>=$startDate 
	 AND $gameRow['PurchaseDateTime']->getTimestamp()<=$endDate) 
	 OR ($LastPlay>=$startDate AND $LastPlay<=$endDate))
	 ){
		$usestatus= (isset($activity[$gameRow['Game_ID']]['Status']) ? $activity[$gameRow['Game_ID']]['Status'] : $gameRow['Status']);
		$output .= '<tr class="'. $gameRow['Status'].'">
		<td class="text"><a href="viewgame.php?id='. $gameRow['Game_ID'].'" target="_blank">'. $gameRow['Title'].'</a></td>
		<td class="numeric">'. $gameRow['PurchaseDateTime']->Format("m/d/Y").'</td>
		<td class="numeric">'. ($LastPlay==0 ? "":date("m/d/Y",$LastPlay)).'</td>
		<td class="numeric">'. (isset($activity[$gameRow['Game_ID']]['Achievements']) ? $activity[$gameRow['Game_ID']]['Achievements'] : 0).'</td>
		<td class="text">'. $usestatus.'</td>
		<td class="numeric">'. (isset($activity[$gameRow['Game_ID']]['Review']) ? $activity[$gameRow['Game_ID']]['Review'] : 0).'</td>
		<td class="numeric">'. (isset($activity[$gameRow['Game_ID']]['LastBeat']) ? $activity[$gameRow['Game_ID']]['LastBeat'] : 0).'</td>
		<td class="numeric">'. timeduration($gameRow['currentTime'],"seconds").'</td>
		</tr>';

		if(!isset($statuscount[$usestatus])){
			$statuscount[$usestatus]=0;
		}
		$statuscount[$usestatus]++;
	 }
}
	
//var_dump($activity);
/* * /
foreach ($activity as $totals) {
	$LastPlay=strtotime($totals['lastplay']);
	
	if(($calculations[$totals['ID']]['PurchaseDate']>=$startDate 
	 AND $calculations[$totals['ID']]['PurchaseDate']<=$endDate) 
	 OR ($LastPlay>=$startDate AND $LastPlay<=$endDate)){
		$output .= "<tr class=\"" . $totals['Status'] . "\">";
		$output .= "<td class=\"text\"><a href='viewgame.php?id=".$totals['ID']."'>" . $calculations[$totals['ID']]['Title'] . "</a></td>";
		$output .= "<td>".date("m/d/Y",$calculations[$totals['ID']]['PurchaseDate'])."</td>";
		$output .= "<td class=\"numeric\">" . date("m/d/Y",$LastPlay) . "</td>";
		$output .= "<td class=\"numeric\">" . $totals['Achievements'] . "</td>";
		$output .= "<td class=\"text\">" . $totals['Status'] . "</td>";
		$output .= "<td class=\"numeric\">" . $totals['Review'] . "</td>";
		$output .= "<td class=\"numeric\">" . $totals['LastBeat'] . "</td>";
		$output .= "<td class=\"numeric\">" . timeduration($totals['GrandTotal'],"seconds") . "</td>";
		$output .= "</tr>";
		
		if(!isset($statuscount[$totals['Status']])){
			$statuscount[$totals['Status']]=0;
		}
		$statuscount[$totals['Status']]++;
	}
	
	
	//var_dump($totals);
	//$output .= "<br><br>";
	
	unset($totals);
}
/* */

$output .= '</tbody>
</table>
</td><td valign=top><table>';

$GoogleChartDataString="['Status',		'Count']";
$GoogleChartDataString2="['Status',		'Count']";

/* * /
foreach ($statuscount as $key => $count){
	$output .= "<tr><td>".$key ."</td><td>".$count ."</td></tr>";
	$GoogleChartDataString.=",
	  ['".$key."',	".$count."]";
}
/* */
if(!isset($statuscount['Done'])){$statuscount['Done']=0;} 
if(!isset($statuscount['Active'])){$statuscount['Active']=0;}
if(!isset($statuscount['On Hold'])){$statuscount['On Hold']=0;}
if(!isset($statuscount['Inactive'])){$statuscount['Inactive']=0;}
if(!isset($statuscount['Unplayed'])){$statuscount['Unplayed']=0;}
if(!isset($statuscount['Never'])){$statuscount['Never']=0;}
if(!isset($statuscount['Broken'])){$statuscount['Broken']=0;}

$output .= '<tr class="Done">    <td>Done</td>    <td>'. $statuscount['Done'].'</td></tr>
<tr class="Active">  <td>Active</td>  <td>'. $statuscount['Active'].'</td></tr>
<tr class="On Hold"> <td>On Hold</td> <td>'. $statuscount['On Hold'].'</td></tr>
<tr class="Inactive"><td>Inactive</td><td>'. $statuscount['Inactive'].'</td></tr>
<tr class="Unplayed"><td>Unplayed</td><td>'. $statuscount['Unplayed'].'</td></tr>
<tr class="Never">   <td>Never</td>   <td>'. $statuscount['Never'].'</td></tr>
<tr class="Broken">  <td>Broken</td>  <td>'. $statuscount['Broken'].'</td></tr>
</table>';

$GoogleChartDataString.=",\n\r	['Done',		".$statuscount['Done']."]";
$GoogleChartDataString.=",\n\r	['Active',		".$statuscount['Active']."]";
$GoogleChartDataString.=",\n\r	['On Hold',		".$statuscount['On Hold']."]";
$GoogleChartDataString.=",\n\r	['Inactive',	".$statuscount['Inactive']."]";
$GoogleChartDataString.=",\n\r	['Unplayed',	".$statuscount['Unplayed']."]";
$GoogleChartDataString.=",\n\r	['Never',		".$statuscount['Never']."]";
$GoogleChartDataString.=",\n\r	['Broken',		".$statuscount['Broken']."]";

$GoogleChartDataString2.=",\n\r	['Done',		".$statuscount['Done']."]";
$GoogleChartDataString2.=",\n\r	['Active',		".$statuscount['Active']."]";
$GoogleChartDataString2.=",\n\r	['On Hold',		".$statuscount['On Hold']."]";
$GoogleChartDataString2.=",\n\r	['Inactive',	".$statuscount['Inactive']."]";
$GoogleChartDataString2.=",\n\r	['Unplayed',	".$statuscount['Unplayed']."]";

	/***** Dynamic Chart *****/
//$output .= $GoogleChartDataString;
	//$GoogleChartDataString="";
/* * /
	  $GoogleChartDataString="
	  ['Category', 'Count of Games'],
	  ['Played',	10],
	  ['UnPlayed',	30]
	  ";
	  

/* */

$output .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">';
  $output .= "google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	". $GoogleChartDataString."
	]);

	var data2 = google.visualization.arrayToDataTable([
	".$GoogleChartDataString2."
	]);
	
	var options = {
	  title: 'All Games by Status'
	};

	var options2 = {
	  title: 'All Games by Status'
	};
	
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));

	chart.draw(data, options);
	chart2.draw(data2, options2);
  }
</script>";
	
$output .= '<div id="piechart" style="width: 900px; height: 500px;"></div>
<div id="piechart2" style="width: 900px; height: 500px;"></div>
</td></tr></table>';
	
		return $output;
	}
}	