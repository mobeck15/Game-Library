<?php
include "inc/php.ini.inc.php";
include "inc/functions.inc.php";

$title="Status Charts";
echo Get_Header($title);

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

$conn=get_db_connection();

$settings=getsettings($conn);
$History=getHistoryCalculations("",$conn,$startDate,$endDate);
$activity=getActivityCalculations("",$History,$conn);

$PreHistory=getHistoryCalculations("",$conn,mktime(0, 0, 0, 1, 1, 2000),$startDate-60*60*24);
$Preactivity=getActivityCalculations("",$PreHistory,$conn);

$calculations=getCalculations("",$conn);
$conn->close();	

$calculations=reIndexArray($calculations,"Game_ID");
?>
<table><tr><td valign=top>
Date Range: <?php echo date("Y/m/d",$startDate); ?> - <?php echo date("Y/m/d",$endDate); ?>
<ul>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>'>All Time</a></li>
<li>Year</li><ul>
<?php 
$useyear=date("Y");
do {
	?>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo $useyear; ?>-1-1&end=<?php echo $useyear; ?>-12-31'><?php echo $useyear; ?></a>
	<?php if($useyear==date("Y")) { echo "This Year"; } ?>
	</li>
	<?php
	$useyear--;
} while($useyear>=2013)
?>
</ul>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m-d",strtotime("1 Year Ago")); ?>&end=<?php echo date("Y-m-d"); ?>'>365 Days</a></li>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m"); ?>-1&end=<?php echo date("Y-m-t"); ?>'>This Month</a></li>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m-d",strtotime("1 Month Ago")); ?>&end=<?php echo date("Y-m-d"); ?>'>30 Days</a></li>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m-d",strtotime("2 Weeks Ago")); ?>&end=<?php echo date("Y-m-d"); ?>'>2 Weeks</a></li>
<li class="hidden"><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m-d",strtotime("14 Days Ago")); ?>&end=<?php echo date("Y-m-d"); ?>'>14 Days</a></li>
<li class="hidden"><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y"); ?>-1-1&end=<?php echo date("Y"); ?>-12-31'>This Week</a></li>
<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?start=<?php echo date("Y-m-d",strtotime("7 Days Ago")); ?>&end=<?php echo date("Y-m-d"); ?>'>7 Days</a></li>
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
<tbody>
<?php	
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
	 ($gameRow['PurchaseDate']>=$startDate 
	 AND $gameRow['PurchaseDate']<=$endDate) 
	 OR ($LastPlay>=$startDate AND $LastPlay<=$endDate))
	 ){
		$usestatus= (isset($activity[$gameRow['Game_ID']]['Status']) ? $activity[$gameRow['Game_ID']]['Status'] : $gameRow['Status']);
		 ?>
		<tr class="<?php echo $gameRow['Status']; ?>">
		<td class="text"><a href='viewgame.php?id=<?php echo $gameRow['Game_ID']; ?>' target='_blank'><?php echo $gameRow['Title']; ?></a></td>
		<td class="numeric"><?php echo date("m/d/Y",$gameRow['PurchaseDate']); ?></td>
		<td class="numeric"><?php echo ($LastPlay==0 ? "":date("m/d/Y",$LastPlay)); ?></td>
		<td class="numeric"><?php echo (isset($activity[$gameRow['Game_ID']]['Achievements']) ? $activity[$gameRow['Game_ID']]['Achievements'] : 0); ?></td>
		<td class="text"><?php echo $usestatus; ?></td>
		<td class="numeric"><?php echo (isset($activity[$gameRow['Game_ID']]['Review']) ? $activity[$gameRow['Game_ID']]['Review'] : 0); ?></td>
		<td class="numeric"><?php echo (isset($activity[$gameRow['Game_ID']]['LastBeat']) ? $activity[$gameRow['Game_ID']]['LastBeat'] : 0); ?></td>
		<td class="numeric"><?php echo timeduration($gameRow['currentTime'],"seconds"); ?></td>
		</tr>
		<?php
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
		echo "<tr class=\"" . $totals['Status'] . "\">";
		echo "<td class=\"text\"><a href='viewgame.php?id=".$totals['ID']."'>" . $calculations[$totals['ID']]['Title'] . "</a></td>";
		echo "<td>".date("m/d/Y",$calculations[$totals['ID']]['PurchaseDate'])."</td>";
		echo "<td class=\"numeric\">" . date("m/d/Y",$LastPlay) . "</td>";
		echo "<td class=\"numeric\">" . $totals['Achievements'] . "</td>";
		echo "<td class=\"text\">" . $totals['Status'] . "</td>";
		echo "<td class=\"numeric\">" . $totals['Review'] . "</td>";
		echo "<td class=\"numeric\">" . $totals['LastBeat'] . "</td>";
		echo "<td class=\"numeric\">" . timeduration($totals['GrandTotal'],"seconds") . "</td>";
		echo "</tr>";
		
		if(!isset($statuscount[$totals['Status']])){
			$statuscount[$totals['Status']]=0;
		}
		$statuscount[$totals['Status']]++;
	}
	
	
	//var_dump($totals);
	//echo "<br><br>";
	
	unset($totals);
}
/* */

?>
</tbody>
</table>
</td><td valign=top><table>
<?php

$GoogleChartDataString="['Status', 'Count']";
$GoogleChartDataString2="['Status', 'Count']";

/* * /
foreach ($statuscount as $key => $count){
	echo "<tr><td>".$key ."</td><td>".$count ."</td></tr>";
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
?>
<tr class="Done">    <td>Done</td>    <td><?php echo $statuscount['Done'];     ?></td></tr>
<tr class="Active">  <td>Active</td>  <td><?php echo $statuscount['Active'];   ?></td></tr>
<tr class="On Hold"> <td>On Hold</td> <td><?php echo $statuscount['On Hold'];  ?></td></tr>
<tr class="Inactive"><td>Inactive</td><td><?php echo $statuscount['Inactive']; ?></td></tr>
<tr class="Unplayed"><td>Unplayed</td><td><?php echo $statuscount['Unplayed']; ?></td></tr>
<tr class="Never">   <td>Never</td>   <td><?php echo $statuscount['Never'];    ?></td></tr>
<tr class="Broken">  <td>Broken</td>  <td><?php echo $statuscount['Broken'];   ?></td></tr>
</table>
<?php

$GoogleChartDataString.=",
  ['Done',	".$statuscount['Done']."]";
$GoogleChartDataString.=",
  ['Active',	".$statuscount['Active']."]";
$GoogleChartDataString.=",
  ['On Hold',	".$statuscount['On Hold']."]";
$GoogleChartDataString.=",
  ['Inactive',	".$statuscount['Inactive']."]";
$GoogleChartDataString.=",
  ['Unplayed',	".$statuscount['Unplayed']."]";
$GoogleChartDataString.=",
  ['Never',	".$statuscount['Never']."]";
$GoogleChartDataString.=",
  ['Broken',	".$statuscount['Broken']."]";

$GoogleChartDataString2.=",
  ['Done',	".$statuscount['Done']."]";
$GoogleChartDataString2.=",
  ['Active',	".$statuscount['Active']."]";
$GoogleChartDataString2.=",
  ['On Hold',	".$statuscount['On Hold']."]";
$GoogleChartDataString2.=",
  ['Inactive',	".$statuscount['Inactive']."]";
$GoogleChartDataString2.=",
  ['Unplayed',	".$statuscount['Unplayed']."]";

	/***** Dynamic Chart *****/
//echo $GoogleChartDataString;
	//$GoogleChartDataString="";
/* * /
	  $GoogleChartDataString="
	  ['Category', 'Count of Games'],
	  ['Played',	10],
	  ['UnPlayed',	30]
	  ";
	  

/* */

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	<?php echo $GoogleChartDataString; ?>
	]);

	var data2 = google.visualization.arrayToDataTable([
	<?php echo $GoogleChartDataString2; ?>
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
</script>
	
<div id="piechart" style="width: 900px; height: 500px;"></div>
<div id="piechart2" style="width: 900px; height: 500px;"></div>
</td></tr></table>
	
<?php echo Get_Footer(); ?>