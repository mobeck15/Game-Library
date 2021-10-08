<?php
include "inc/php.ini.inc.php";
include "inc/functions.inc.php";

$title="Chart Data (Calendar)";
echo Get_Header($title);

$conn=get_db_connection();
$settings=getsettings($conn);

?>
<form>
	View by: <input type="radio" id="Month" name="group" value="month" 
	<?php if (!isset($_GET['group']) or $_GET['group']!="year") { echo " CHECKED"; } ?>
	>
	<label for="Month">Month</label>
	|
	<input type="radio" id="Year" name="group" value="year"
	<?php if (isset($_GET['group']) && $_GET['group']=="year") { echo " CHECKED"; } ?>
	>
	<label for="Year">Year</label>
	|
	Hide Free Games: <label class="switch"><input type="checkbox" name="CountFree" value='0'<?php 
	if($settings['CountFree']==0) { echo " CHECKED "; }
	?>><span class="slider round"></span></label>
	<?php if (isset($_GET['detail'])) { ?>
	<input type="hidden" id="Detail" name="detail" value="<?php echo $_GET['detail']; ?>">
	<?php } ?>
	<input type="submit">
</form>

<table>
<thead><tr>
<th class="hidden" rowspan=2>Month</th>
<th class="hidden" rowspan=2>Mon#</th>
<th class="hidden" rowspan=2>Year</th>
<th rowspan=2>Date</th>
<th rowspan=2>Spending</th>
<th rowspan=2>Games</th>
<th rowspan=2>Avg Game $</th>
<th class="hidden" rowspan=2>Total$</th>
<?php 
if(isset($_GET['group']) && $_GET['group']=="year") {
echo "<th rowspan=2>$/Yr</th>
<th rowspan=2>Games/Yr</th>";
} else {
echo "<th rowspan=2>$/Mo</th>
<th rowspan=2>Games/Mo</th>";
}
?>
<th rowspan=2>New Play</th>
<th rowspan=2>Avg all $</th>
<th rowspan=2>Spent</th>
<th rowspan=2>Earned</th>
<th rowspan=2>Hours</th>
<th rowspan=2>$/hr</th>
<th colspan=3>Unplayed</th>
<th class="hidden" colspan=3>Incomplete Data</th>
<th colspan=3>New Data</th>
	
</tr><tr>
<th style='top:77px;'>Variance</th>
<th style='top:77px;'>Balance</th>
<?php 
if(isset($_GET['group']) && $_GET['group']=="year") {
echo "<th style='top:77px;'>This Year</th>";
} else {
echo "<th style='top:77px;'>This Month</th>";
}
?>
	
<th style='top:77px;'>Variance</th>
<th style='top:77px;'>Balance</th>
<?php 
if(isset($_GET['group']) && $_GET['group']=="year") {
echo "<th style='top:77px;'>This Year</th>";
} else {
echo "<th style='top:77px;'>This Month</th>";
}
?>

<th class="hidden">Debug</th>
</tr></thead>
<tbody>
<?php
	if(isset($_GET['group']) && $_GET['group']=="year") {
		$sql="SELECT DISTINCT Year(`DateAdded`) as Year FROM `gl_items` ";
		$groupbyyear=true;
		$dateformat="Y";
		$dateformat2="Y";
	} else {
		$sql="SELECT DISTINCT Year(`DateAdded`) as Year, MONTH(`DateAdded`) as Month FROM `gl_items` ";
		$groupbyyear=false;
		$dateformat="Y-n";
		$dateformat2="m/Y";
	}
	$sql .="\r\nwhere `DateAdded` is not null 
	ORDER by `DateAdded` ASC";
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				$key=$row['Year'];
				
				//DEBUG
				//echo "Key:" . $key . "<br>";
				
				if($groupbyyear==false){ 
					$key.="-".$row['Month']; 
					$date=mktime(0, 0, 0, $row['Month'], 10,$row['Year']);
					$chart[$key]['MonthNum']=$row['Month'];
				} else {
					$date=mktime(0, 0, 0, 1, 10,$row['Year']);
					$chart[$key]['MonthNum']=1;
				}
				$chart[$key]['Date']=date($dateformat2, $date);
				$chart[$key]['Month']=date('F', $date);
				$chart[$key]['Year']=$row['Year'];
				$chart[$key]['Spent']=0;
				$chart[$key]['Earned']=0;
				$chart[$key]['Spending']=0;
			}
		}else {
		}
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	$sql="SELECT * FROM `gl_transactions` where `PurchaseDate` is not null ORDER by `PurchaseDate` ASC, `PurchaseTime` ASC, `Sequence` ASC";
	if($result = $conn->query($sql)){
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				$key=date($dateformat, strtotime($row['PurchaseDate']));

				$date=strtotime($row['PurchaseDate']);
				if(!isset($chart[$key]['Date'])){$chart[$key]['Date']=date($dateformat2, $date);}
				if(!isset($chart[$key]['Month'])){$chart[$key]['Month']=date('F', $date);}
				if(!isset($chart[$key]['MonthNum'])){$chart[$key]['MonthNum']=date('n', $date);}
				if(!isset($chart[$key]['Year'])){$chart[$key]['Year']=date('Y', $date);}
				
				if(!isset($chart[$key]['Spent'])){$chart[$key]['Spent']=0;}
				if(!isset($chart[$key]['Earned'])){$chart[$key]['Earned']=0;}
				if(!isset($chart[$key]['Spending'])){$chart[$key]['Spending']=0;}
				if($row['BundleID']==$row['TransID']) {
					if($row['Paid']>0){
						$chart[$key]['Spent']+=$row['Paid'];
					} else {
						$chart[$key]['Earned']+=$row['Paid'];
					}
					//Debug Earned Data
					//if(!isset($Debug[$key])){$Debug[$key]="";}
					//$Debug[$key].=$chart[$key]['Spent']."\r\n";
					//$Debug[$key].=$row['Title']."\r\n";
					$chart[$key]['Spending']=$chart[$key]['Spent']+$chart[$key]['Earned'];
				}
			}
		}
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}

	//Settings gotten earlier.
	//$settings=getsettings($conn);
	$calculations=getCalculations("",$conn);
	foreach($calculations as $key => $row) {
		$key=date($dateformat, strtotime($row['PrintPurchaseDate']));
		if($row['CountGame']==true && $row['Playable']==true
		  && (0+$row['Paid']>0 OR $settings['CountFree']==true)){
			
			if(!isset($chart[$key]['Games'])) {$chart[$key]['Games']=0;}
			$chart[$key]['Games']++;
			
			//Debug Purchase Count
			//if(!isset($Debug[$key])){$Debug[$key]="";}
			//if($row['firstplay']=="") {
			//	$Debug[$key].=$row['Title']."\r\n";
			//}
			
			//print_r ($row);
			
			if (isset($_GET['detail']) AND $key==$_GET['detail']) {
				$detail['purchased'][$row['Game_ID']]['ID']=$row['Game_ID'];
				$detail['purchased'][$row['Game_ID']]['Game']=$row['Title'];
				$detail['purchased'][$row['Game_ID']]['Played']="Unplayed";
				$detail['purchased'][$row['Game_ID']]['SteamID']=$row['SteamID'];
			}
			
			
			if($row['firstplay']<>""){
				$key2=date($dateformat, strtotime($row['firstplay']));
				if(!isset($chart[$key2]['Date'])){
					$chart[$key2]['Date']=date($dateformat2, strtotime($row['firstplay']));
					$chart[$key2]['Month']=date('F', strtotime($row['firstplay']));
					$chart[$key2]['MonthNum']=date('n', strtotime($row['firstplay']));
					$chart[$key2]['Year']=date('Y', strtotime($row['firstplay']));
				}
				
				if(!isset($chart[$key2]['NewPlay'])) {$chart[$key2]['NewPlay']=0;}
				$chart[$key2]['NewPlay']++;
				
				if(!isset($chart[$key]['PlayedThisMonth'])) {$chart[$key]['PlayedThisMonth']=0;}
				$chart[$key]['PlayedThisMonth']++;
				
				//Debug newplay
				//if(!isset($Debug[$key2])){$Debug[$key2]="";}
				//$Debug[$key2].=$row['Title']."\r\n";
				
				if(isset($detail['purchased'][$row['Game_ID']])) {
					$detail['purchased'][$row['Game_ID']]['Played']="Played";
				}
			}
			
			if($row['DateUpdated']<>""){
				$key2=date($dateformat, strtotime($row['DateUpdated']));

				if(!isset($chart[$key2]['NewData'])) {$chart[$key2]['NewData']=0;}
				$chart[$key2]['NewData']++;
				
				//$key3=date($dateformat, strtotime($row['DateUpdated']));
				if(!isset($chart[$key]['DataThisMonth'])) {$chart[$key]['DataThisMonth']=0;}
				$chart[$key]['DataThisMonth']++;
				
			}

		}

	}
	
	$history=getHistoryCalculations("",$conn);
	foreach($history as $key => $row) {
		if($row['FinalCountHours']==true){
			$date=strtotime($row['Timestamp']);
			$key=date($dateformat, $date);
			
			if(!isset($chart[$key]['Hours'])){$chart[$key]['Hours']=0;}
			if($row['Elapsed']=="") {$row['Elapsed']=0;}
			
			//var_dump($chart[$key]['Hours']); echo " += "; var_dump($row['Elapsed']); echo " ;<br>";
			$chart[$key]['Hours']+=$row['Elapsed'];
			
			if(!isset($chart[$key]['Date'])){
				$chart[$key]['MonthNum']=date('n', strtotime($row['Timestamp']));
				$chart[$key]['Year']=date('Y', strtotime($row['Timestamp']));
				$chart[$key]['Date']=date($dateformat2, strtotime($row['Timestamp']));
				$chart[$key]['Month']=date('F', strtotime($row['Timestamp']));
			}

			//Debug Play Time
			//if(!isset($Debug[$key])){$Debug[$key]="";}
			//$Debug[$key].=$row['Game']."\r\n";//."/t".$Debug[$key].=$row['Elapsed'];//"\r\n"; Game
			//$Debug[$key].=$row['Elapsed']."\r\n";//."/t".$Debug[$key].=$row['Elapsed'];//"\r\n"; Game
			//print_r($row);
			
			if (isset($_GET['detail']) AND $key==$_GET['detail']) {
				$detail['played'][$row['GameID']]['Game']=$row['Game'];
				if (isset($detail['played'][$row['GameID']]['Time'])) {
					$detail['played'][$row['GameID']]['Time']+=$row['Elapsed'];
				} else {
					$detail['played'][$row['GameID']]['Time']=$row['Elapsed'];
				}
			}
		}
	}

	unset ($key);
	unset ($row);
	
	foreach ($chart as $key => &$row) {
			/*  * /
			$key=date($dateformat, $date);
			if(!isset($chart[$key]['Date'])){
					$chart[$key]['Date']=date($dateformat2, $date);
					$chart[$key]['Month']=date('F', $date);
					$chart[$key]['MonthNum']=date('n', $date);
					$chart[$key]['Year']=date('Y', $date);
			}
			/*  */
		$year[$key]  = $row['Year'];
		$month[$key] = $row['MonthNum'];

		if(!isset($row['Games'])){$row['Games']=0;}
		if(!isset($row['NewPlay'])){$row['NewPlay']=0;}
		$row['Variance']=$row['NewPlay']-$row['Games'];
		
		if(!isset($row['NewData'])){$row['NewData']=0;}
		$row['DataVariance']=$row['NewData']-$row['Games'];
	}
	
	unset ($key);
	unset ($row);
	
	array_multisort($year, SORT_ASC, $month, SORT_ASC, $chart);
	$Rchart=array_reverse($chart);
	$prevBalance=0;
	$prevDBalance=0;
	foreach ($Rchart as $key => $row) {
		$prevBalance += $row['Variance']*-1;
		$chart[$key]['Balance']=$prevBalance;

		$prevDBalance += $row['DataVariance']*-1;
		$chart[$key]['DataBalance']=$prevDBalance;
	}
	
	$mCount=0;
	$TotalSpending=0;
	$TotalGames=0;
	$TotalPlay=0;
	$TotalSpent=0;
	$TotalEarned=0;
	$TotalHours=0;
	$GoogleChartDataString="";
	foreach($chart as $key => $row) {
		if(!isset($row['Spending'])){$row['Spending']=0;}
		if(!isset($row['Spent'])){$row['Spent']=0;}
		if(!isset($row['Earned'])){$row['Earned']=0;}
		if(!isset($row['Hours'])){$row['Hours']=0;}
		if(!isset($row['PlayedThisMonth'])) {$row['PlayedThisMonth']=0;}
		if(!isset($row['DataThisMonth'])) {$row['DataThisMonth']=0;}

		$TotalPlay+=$row['NewPlay'];
		$TotalSpent+=$row['Spent'];
		$TotalEarned+=$row['Earned'];
		$TotalHours+=$row['Hours'];
		$row['leftThisMonth']=$row['Games']-$row['PlayedThisMonth'];
		$row['DataleftThisMonth']=$row['Games']-$row['DataThisMonth'];
		
		$TotalSpending+=$row['Spending'];
		$row['TotalSpend']=$TotalSpending;

		$TotalGames+=$row['Games'];
		$row['TotalGames']=$TotalGames;

		if($row['TotalGames']>0){
			$row['AvgTotal']=$row['TotalSpend']/$row['TotalGames'];
		} else {
			$row['AvgTotal']=$row['TotalSpend'];
		}
		
		if($row['Games']>0){
			$row['Avg']=$row['Spending']/$row['Games'];
		} else {
			$row['Avg']=$row['Spending'];
		}

		if($mCount>0 OR $row['TotalSpend']<>0){
			$mCount++;
			$row['AvgSpent']=$row['TotalSpend']/$mCount;
			$row['AvgGames']=$row['TotalGames']/$mCount;
		} else {
			$row['AvgSpent']=0;
			$row['AvgGames']=0;
		}
		if($row['Hours']<>0){
			$row['CostHour']=$row['Spending']/($row['Hours']/60/60);
		} else {
			$row['CostHour']=$row['Spending'];
		}
		
		//DEBUG:
		//var_dump($row); echo "<br>";
		//echo "Year=". $row['Date'];
		//echo " - key=". $key;
		//echo " - detail=".$_GET['detail']."<br>";
		
		if (isset($_GET['detail']) && ($_GET['detail']==$key or $_GET['detail']==$row['Date'])) { ?>
			<tr class='Selected'>
		<?php } else { ?>
			<tr>
		<?php } ?>
		<td class="hidden"><?php echo $row['Month']; ?></td>
		<td class="hidden"><?php echo $row['MonthNum']; ?></td>
		<td class="hidden"><?php echo $row['Year']; ?></td>
		<?php if(!isset($row['Date']) || $row['Date']=="") {$row['Date']="Blank";}
		$countparm="";
		if(isset($_GET['CountFree'])) {$countparm="&CountFree=".$_GET['CountFree'];}
		?>
		<td class="numeric"><a href='<?php echo $_SERVER['PHP_SELF'];?>?detail=<?php 
			if($groupbyyear==true){
				echo $row['Date']."&group=year";
			} else {
				echo $key.$countparm;
			} 
			//TODO: Update this to read the settings value instead of _GET
			if(isset($_GET['CountFree'])) {
				echo "&CountFree=".$_GET['CountFree'];
			}
			?>'><?php echo $row['Date'];?></a></td>
		<td class="numeric">$<?php echo number_format($row['Spending'], 2); ?></td>
		<td class="numeric"><?php echo $row['Games']; ?></td>
		<td class="numeric">$<?php echo number_format($row['Avg'], 2); ?></td>
		<td class="hidden numeric">$<?php echo number_format($row['TotalSpend'], 2); ?></td>
		<td class="numeric">$<?php echo number_format($row['AvgSpent'], 2); ?></td>
		<td class="numeric"><?php echo number_format($row['AvgGames'],0); ?></td>
		<td class="numeric"><?php echo $row['NewPlay']; ?></td>
		<td class="numeric">$<?php echo number_format($row['AvgTotal'], 2); ?></td>
		<td class="numeric">$<?php echo number_format($row['Spent'], 2); ?></td>
		<td class="numeric">$<?php echo number_format($row['Earned'], 2); ?></td>
		<td class="numeric"><?php echo timeduration($row['Hours'],"seconds"); ?></td>
		<td class="numeric">$<?php echo number_format($row['CostHour'], 2); ?></td>
		<?php
		if($row['Variance']>=0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['Variance']."</td>";
		if($row['Balance']<=0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['Balance']."</td>";
		if($row['leftThisMonth']==0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['leftThisMonth']."</td>";
		
		
		if($row['DataVariance']>=0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['DataVariance']."</td>";
		if($row['DataBalance']<=0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['DataBalance']."</td>";
		if($row['DataleftThisMonth']==0) {
			$cellcolor="greenCell";
		} else {
			$cellcolor="redCell";
		}
		echo "<td class=\"numeric $cellcolor\">".$row['DataleftThisMonth']."</td>";
		
		
		//echo "<td>".$row['NewData']."</td>";
		
		
		//echo "<td></td>";
		//echo "<td>$key ".print_r($row,true)."</td>";
		
		//echo"<td>";		Var_dump($key);		echo"</td>";
		echo "</tr>";
		
		if($row['Balance']>1) {$lastBalance=$row['Balance'];}
		if($row['leftThisMonth']>1) {$lastLeft=$row['leftThisMonth'];}
		if($row['DataBalance']>1) {$lastData=$row['DataBalance'];}
		if($row['DataleftThisMonth']>1) {$lastDataLeft=$row['DataleftThisMonth'];}
		
		if($row['Year']>=2013){
		$GoogleChartDataString.="[new Date(".$row['Year'].", ".($row['MonthNum']-1) ."), 
			".$row['Spending'].", 
			".$row['Games'].", 
			".$row['AvgSpent'].", 
			".$row['AvgGames'].", 
			".$row['NewPlay']."], ";
		}
	}
	
	echo "</tbody>";
	
	/***** Footer *****/
	
	if(isset($_GET['group']) && $_GET['group']=="year") {
		$daysTilNextMonth = floor((strtotime('first day of next year') - strtotime("Today")) / (24 * 3600));
	} else {
		$daysTilNextMonth = floor((strtotime('first day of next month') - strtotime("Today")) / (24 * 3600));
	}

	
	echo "<tfoot>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td colspan=2>Goal/Day</td>";
	echo "<td></td>";
	echo "<td colspan=2>Goal/Day</td>";
	echo "</tr>";
	
	echo "<tr>";
	//echo "<td>ALL</td>";
	//echo "<td>ALL</td>";
	//echo "<td>ALL</td>";
	echo "<th class=\"text\">Totals</th>";
	echo "<td class=\"numeric\">$".number_format($TotalSpending, 2)."</td>";
	echo "<td class=\"numeric\">".$TotalGames."</td>";
	echo "<td class=\"numeric\">$".number_format($TotalSpending/$TotalGames, 2)."</td>";
	//echo "<td class=\"numeric\">$".number_format($row['TotalSpend'], 2)."</td>";
	echo "<td class=\"numeric\">$".number_format($row['AvgSpent'], 2)."</td>";
	echo "<td class=\"numeric\">".number_format($row['AvgGames'],0)."</td>";
	echo "<td class=\"numeric\">".$TotalPlay."</td>";
	echo "<td class=\"numeric\">$".number_format($row['AvgTotal'], 2)."</td>";
	echo "<td class=\"numeric\">$".number_format($TotalSpent, 2)."</td>";
	echo "<td class=\"numeric\">$".number_format($TotalEarned, 2)."</td>";
	echo "<td class=\"numeric\">".timeduration($TotalHours,"seconds")."</td>";
	echo "<td class=\"numeric\">$".number_format($TotalSpending/($TotalHours/60/60), 2)."</td>";
	echo "<td class=\"numeric\">".($TotalPlay-$TotalGames)."</td>"; //Unplayed Variance
	echo "<td class=\"numeric\">".ceil($lastBalance/$daysTilNextMonth)."</td>"; //Unplayed Balance
	echo "<td class=\"numeric\">".ceil($lastLeft/$daysTilNextMonth)."</td>"; //Unplayed This x
	echo "<td class=\"numeric\"></td>";
	echo "<td class=\"numeric\">".ceil($lastData/$daysTilNextMonth)."</td>";
	echo "<td class=\"numeric\">".ceil($lastDataLeft/$daysTilNextMonth)."</td>";
	//echo "<td>$key ".print_r($row,true)."</td>";
	echo "</tr>";
	echo "</tfoot>";
	echo "</table>";

	$conn->close();

	/***** Dynamic Chart *****/
	//echo $GoogleChartDataString;
		//$GoogleChartDataString="";
	/* * /
	      $GoogleChartDataString="
		[new Date(2010, 5), 0, 1, 0, 0, 1],    
        [new Date(2013, 5), 0, 6, 22, 17, 0],   ";
		/* * /
		  $GoogleChartDataString.="
        [new Date(2013, 7), 121.48, 32, 121.48, 39, 9],
        [new Date(2013, 8), 0, 0, 60.74, 20, 1],
        [new Date(2013, 9), 15.27, 22, 45.58, 20, 10],
        [new Date(2013, 10), 34.91, 58, 42.92, 30, 8],
        [new Date(2013, 11), 73.63, 55, 49.06, 35, 10],
        [new Date(2013, 12), 78.65, 87, 53.99, 44, 11],
        [new Date(2014, 1), 36.53, 36, 51.50, 42, 32],
        [new Date(2014, 2), 14.53, 59, 46.88, 45, 10],
        [new Date(2014, 3), 11.04, 30, 42.89, 43, 5],
        [new Date(2014, 4), 10.76, 38, 39.68, 42, 30],
        [new Date(2014, 5), 14.00, 39, 37.35, 42, 14],
        [new Date(2014, 6), 45.24, 52, 38.00, 43, 20],
        [new Date(2014, 7), 9.91, 25, 35.84, 42, 36],
        [new Date(2014, 8), -50.96, 35, 29.64, 41, 41],
        [new Date(2014, 9), 21.06, 45, 29.07, 41, 22],
        [new Date(2014, 10), 4.02, 25, 27.50, 40, 18],
        [new Date(2014, 11), 2.73, 11, 26.05, 39, 13],
        [new Date(2014, 12), 14.49, 24, 25.41, 38, 19],
        [new Date(2015, 1), 20.51, 39, 25.15, 38, 40],
        [new Date(2015, 2), 11.32, 30, 24.46, 37, 21],
        [new Date(2015, 3), 9.91, 31, 23.76, 37, 17],
        [new Date(2015, 4), 3.74, 17, 22.85, 36, 18],
        [new Date(2015, 5), 12.87, 33, 22.42, 36, 32],
        [new Date(2015, 6), 64.90, 37, 24.19, 36, 32],
        [new Date(2015, 7), 11.83, 28, 23.69, 36, 29],
        [new Date(2015, 8), 3.65, 16, 22.92, 35, 22],
        [new Date(2015, 9), 19.12, 25, 22.78, 35, 28],
        [new Date(2015, 10), 1.01, 13, 22.01, 34, 21],
        [new Date(2015, 11), 26.79, 12, 22.17, 33, 32],
        [new Date(2015, 12), 19.49, 49, 22.08, 34, 45],
        [new Date(2016, 1), 5.31, 15, 21.54, 33, 29],
        [new Date(2016, 2), 25.86, 9, 21.68, 32, 3],
		";
		
		  echo "<br>";
		//echo $GoogleChartDataString;
	/* */
	
	///https://developers.google.com/chart/interactive/docs/gallery/linechart
	$GoogleChartDataString=substr(trim($GoogleChartDataString), 0, -1);
	
	if($groupbyyear==true) {
		$percaption="/Yr";
		$chartmax="400";
	} else {
		$percaption="/Mo";
		$chartmax="90";
	}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawCurveTypes);

function drawCurveTypes() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Date');
      data.addColumn('number', 'Spending');
      data.addColumn('number', 'Games');
      data.addColumn('number', '$<?php echo $percaption; ?>');
      data.addColumn('number', 'Game<?php echo $percaption; ?>');
      data.addColumn('number', 'New Play');

      data.addRows([
		<?php echo $GoogleChartDataString; ?>
      ]);

      var options = {
        hAxis: {
          title: 'Date'
        },
        vAxis: {
          title: 'Amount',
		  viewWindow: {
			  //max: 130,
			  //min: -51
			  max: <?php echo $chartmax; ?>,
			  min: 0
			  
		  }
        },
        series: {
          1: {curveType: 'none'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }


    </script>	
	<p>
       <div id="chart_div" style="height:500px;"></div>

<?php		
	
	
	//echo "Days left in month: ".$daysTilNextMonth;
	
	/***** Details *****/
	echo "<br>";
	//echo nl2br($Debug['2015-1']);
	if (isset($_GET['detail'])) {
		echo "<table>";
		echo "<thead>";
		echo "<tr><th colspan=3>Detail for ".$_GET['detail']."</th></tr>";
		echo "<tr>";
		//echo "<th>Transactions</th>";
		echo "<th>Games Purchased</th><th>Games Played</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		echo "<tr>";
		
			//echo "<td valign=top><table>";
			//echo "<thead></tr>";
			//echo "<th>Bundle</th>";
			//echo "<th>Parent Bundle</th>";
			//echo "<th>Paid</th>";
			//echo "</tr></thead>";
			//echo "<tbody>";
			//echo "</tbody>";
			//echo "</table></td>";


			echo "<td valign=top><table>";
			echo "<thead></tr>";
			echo "<th>Game</th>";
			echo "<th>Played</th>";
			echo "</tr></thead>";
			echo "<tbody>";
			$purchasedgames=array();
			if(isset($detail['purchased'])){
				//DONE: Sort this table with unplayed at top, then rest in alphabetical order
				//DONE: Bug: The link to each game is broken by the alphabetical sort.

				foreach ($detail['purchased'] as $key => $row) {
					array_push($purchasedgames,$key);
					$Sortby1[$key]  = $row['Played'];
				}
				array_multisort($Sortby1, SORT_DESC, $detail['purchased']);
				unset($Sortby1);
				
				//var_dump($detail['purchased']);

				foreach($detail['purchased'] as $key => $purchased){
					echo "<tr>";
					echo "<td><a href='viewgame.php?id=".$purchased['ID']."' target='_blank'>" . $purchased['Game'] . "</a></td>"; //". print_r($purchased,true) ."
					if($purchased['SteamID']<>0) {
						echo "<td><a href='steam://run/".$purchased['SteamID']."'>" . $purchased['Played'] . "</a></td>";
					} else {
						echo "<td>" . $purchased['Played'] . "</td>";
					}
					//Link to run game: steam://run/428430
					echo "</tr>";
				}
			}
			echo "</tbody>";
			echo "</table></td>";

			echo "<td valign=top><table>";
			echo "<thead></tr>";
			echo "<th>Game</th>";
			echo "<th>Elapsed</th>";
			echo "</tr></thead>";
			echo "<tbody>";
			if(isset($detail['played'])){
				//DONE: maybe mod this table to give GOTY data: Total playtime this period (sorted by most) and hilight those that were also gained in the same period.
				//The total hours played already counts for the period queried.
				//Added color highlight for games purchased in same period.
				foreach ($detail['played'] as $key => &$row) {
					$Sortby1[$key]  = $row['Time'];
					$row['id']=$key;
				}
				//var_dump($detail['purchased']);
				array_multisort($Sortby1, SORT_DESC, $detail['played']);
				
				foreach($detail['played'] as $key => $played){
					if(in_array($played['id'],$purchasedgames)) { 
						$rowcolor="blue"; 
						echo "<tr class='greenRow'>";
					} else {
						echo "<tr>";
						$rowcolor="red"; 
					} 
					//echo "<tr style='background-color: $rowcolor'>";
					//echo "<tr>";
					echo "<td><a href='viewgame.php?id=".$played['id']."' target='_blank'>" . $played['Game'] . "</a></td>";
					echo "<td class='Numeric'>" . timeduration($played['Time'],"seconds") . " <a href='addhistory.php?GameID=".$played['id']."'>+</a></td>";
					//echo "<td>" . print_r($played,true) . "</td>";
					echo "</tr>";
				}
			}
			echo "</tbody>";
			echo "</table></td>";
		echo "</tr>";
		echo "</tbody>";
		echo "</table>";
	}

?>
<?php echo Get_Footer(); ?>