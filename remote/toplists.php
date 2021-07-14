<?php
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/php.ini.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/functions.inc.php";

$title="Top Lists";
echo Get_Header($title);

$conn=get_db_connection();

$settings=getsettings($conn);
$calculations=getCalculations("",$conn);
$conn->close();	

//var_dump($settings);

if(isset($_GET['topx']) && $_GET['topx']>5){
	$Topxgames=$_GET['topx'];
} else {
	$Topxgames=25;
}

?>
<style>
tr.colorrow td {
	color: 000000;
}

tr.colorrow td a {
	color: 000000;
}

</style>
<form>
	<input type="numeric" name="topx" value="<?php echo $Topxgames; ?>">
	Hide Free Games: <label class="switch"><input type="checkbox" name="CountFree" value='0'<?php 
	if($settings['CountFree']==0) { echo " CHECKED "; }
	?>><span class="slider round"></span></label>
	<input type="submit">
</form>

<table><tr><td>
<table>
<thead>
<tr><th>Top Played</th>	<th>Game</th>	<th>Hours</th>	<th>Percent</th>	<th>Left</th>	<th>Hrs Diff</th>	<th>Big Diff</th></tr>
</thead>
<tbody>

<?php
$totalhours=0;
$totalgames=0;
foreach ($calculations as $key => $row) {
	$Sortby1[$key]  = $row['GrandTotal'];
	if($row['CountGame'] && $row['Playable']
	&& (0+$row['Paid']>0 OR $settings['CountFree']==true)
	){
	echo " ";
		$totalgames++;
		$totalhours+=$row['totalHrs'];
	}
}
array_multisort($Sortby1, SORT_DESC, $calculations);

$index=0;
$nextUp=0;
$tophours=0;
$GoogleChartDataString="['Game', 'Time Played']";
$GoogleChartDataString2="['Game', 'Time Played']";
foreach ($calculations as $key => $row) {
	if($row['CountGame'] && $row['Playable'] //){
	  && $row['ParentGameID'] == $row['Game_ID']
	  && (0+$row['Paid']>0 OR $settings['CountFree']==true)
){
		//var_dump($row); break;
		$index++;
		$color="#ffffff";
		if($index<=5){
			$color="#cfe2f3";
		} elseif($index<=11){
			$color="#d9d2e9";
		} elseif($index<=18){
			$color="#fce5cd";
		} elseif($index<=25){
			$color="#f4cccc";
		}
		
		$timeleft=$row['TimeLeftToBeat'];
		if($timeleft<0){
			$timeleft=0;
		}
		if($row['Status']=="Done"){
			$left="Done";
		} else {
			$left=timeduration($timeleft,"hours");
		}
		
		if($index==1){
			$hrdiff="";
			$bigdiff="";
			$nextUp=$row['GrandTotal'];
		} else {
			$hrdiff=timeduration($nextUp,"seconds");
			$bigdiff=timeduration(($nextUp-$row['GrandTotal']+6*60),"seconds");
			
			if($prevrow['Status']<>"Done" && $row['Status']=="Done"){
				$nextUp=$row['GrandTotal'];
			}
		}

		if($index<=$Topxgames){
			$tophours+=$row['totalHrs'];
			$targethrs=$row['GrandTotal'];
			$GoogleChartDataString.=",\r\n\t\t\t  [".json_encode($row['Title']).",	".$row['GrandTotal']."]";
			?>
			<tr class='colorrow' style='background-color: <?php echo $color; ?>'>
		<?php } else if($index>$Topxgames && $row['Status']<>"Done" && $row['GrandTotal']>0){
			$hrdiff=
			$bigdiff=timeduration(($targethrs-$row['GrandTotal']+6*60),"seconds"); ?>
			<tr >
		<?php } else { ?>
			<tr class="hidden">
		<?php } ?>
			<td class="numeric"><?php echo $index;     ?></td>
			<td class="text"><a href='viewgame.php?id=<?php echo $row['Game_ID']; ?>' target='_blank'><?php echo $row['Title']; ?></a></td>
			<td class="numeric"><?php echo timeduration($row['GrandTotal'],"seconds"); ?></td>
			<td class="numeric"><?php echo sprintf("%.2f%%", ($row['GrandTotal']/$totalhours) * 100); ?></td>
			<td class="numeric"><?php echo $left;      ?></td>
			<td class="numeric"><?php echo $hrdiff;    ?></td>
			<td class="numeric"><?php echo $bigdiff;   ?></td>
			</tr>
			<?php
			$GoogleChartDataString2.=",\r\n\t\t\t  [".json_encode($row['Title']).",	".$row['GrandTotal']."]";

		if($index==$Topxgames){
			?>
			<tr>
			<th></td>
			<th class="text"><?php echo ($totalgames-$Topxgames); ?> Other Games</td>
			<th class="numeric"><?php echo timeduration(($totalhours-$tophours),"seconds"); ?></td>
			<th class="numeric"><?php echo sprintf("%.2f%%", (($totalhours-$tophours)/$totalhours) * 100); ?></td>
			<th></td>
			<th></td>
			<th></td>
			</tr>
			<?php
		}
		/* */
		$prevrow=$row;
	}
}

?>
</tbody>
</table>
</td><td valign='top'>
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
	  title: 'top 25 Played Games'
	};

	var options2 = {
	  title: 'all Played Games'
	 ,sliceVisibilityThreshold: <?php echo $targethrs/$totalhours; ?>//.0062
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