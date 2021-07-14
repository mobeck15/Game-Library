<?php
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/php.ini.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/functions.inc.php";

$title="View All History";
echo Get_Header($title);

$conn=get_db_connection();

if(!isset($_GET['num'])) {
	//TODO: add function to allow date range selection
	//TODO: add pageination
	?>
	<form method="Get" >
	How Many: <input type="number" name="num" value=30> <br>
	Sort By: <select name="Sort">
		<option value="Played">Played</option>
		<option value="Purchased">Purchased</option>
		<option value="Days">Days</option>
	</select></br>
	<input type="checkbox" name="Free" value="Free"> Count Free Games</br>
	<input type="checkbox" name="Never" value="Never"> Count Never</br>
	<input type="checkbox" name="Beat" value="Beat"> Count Beat</br>
	<input type="submit" value="Submit">
	</form>
	<?php
} else {
	//var_dump($_GET);
	
	$settings=getsettings($conn);
	$historytable=getHistoryCalculations("",$conn);
	?>
	<table>
	<thead>
	<tr>
	<th class="hidden">ID</th>
	<th>Timestamp</th>
	<th>Game</th>
	<th>System</th>
	<th>Data</th>
	<th>Time</th>
	<th>Notes</th>
	<th class="hidden">Achievements</th>
	<th class="hidden">Achievement Type</th>
	<th class="hidden">Levels</th>
	<th class="hidden">Level Type</th>
	<th class="hidden">Status</th>
	<th class="hidden">Review</th>
	<th>Keywords</th>
	<th class="hidden">Row Type</th>
	<th class="hidden">Previous Start</th>
	<th>Elapsed</th>
	<th class="hidden">Prev Total (System)</th>
	<th>Total (System)</th>
	<th class="hidden">Prev Total</th>
	<th>Total</th>
	<th>Final Status</th>
	<th>Final Rating</th>
	<th class="hidden">Count Game</th>
	<th class="hidden">Base Game</th>
	<th class="hidden">Launch Date</th>
	<th class="hidden">Final Count All</th>
	<th>Final Count Hours</th>
	<th>Use Game</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	
	if($_GET['Sort']=="Played") {
		$sortby="Timestamp";

		foreach ($historytable as $key => $row) {
			$sortarray[$key]  = strtotime($row["Timestamp"]);
		}
		
		array_multisort($sortarray, SORT_DESC, $historytable);
	}
	
	
	$index=1;
	foreach ($historytable as $row) {
		if($index>$_GET['num']) {break;}
		if ($row['Data']<>"Start/Stop") {$index++;}
		?>
		<tr  class="<?php echo $row['FinalStatus']; ?>">
		<td class="hidden numeric"><a href='addhistory.php?HistID=<?php echo $row['HistoryID']; ?>.' target=_blank><?php echo $row['HistoryID']; ?></a></td>
		<td class="numeric"><a href='addhistory.php?HistID=<?php echo $row['HistoryID']; ?>.' target=_blank><?php echo str_replace(" ", "&nbsp;", $row['Timestamp']); ?></a></td>
		<td class="text"><a href='viewgame.php?id=<?php echo $row['GameID']; ?>'><?php echo $row['Game']; ?></a></td>
		<td class="text"><?php echo str_replace(" ", "&nbsp;", $row['System']); ?></td>
		<td class="text"><?php echo str_replace(" ", "&nbsp;", $row['Data']); ?></td>
		<td class="numeric"><?php echo timeduration($row['Time'],"hours"); ?></td>
		<td class="text"><?php echo nl2br($row['Notes']); ?></td>
		<td class="hidden numeric"><?php echo $row['Achievements']; ?></td>
		<td class="hidden text"><?php echo $row['AchievementType']; ?></td>
		<td class="hidden numeric"><?php echo $row['Levels']; ?></td>
		<td class="hidden text"><?php echo $row['LevelType']; ?></td>
		<td class="hidden text"><?php echo $row['Status']; ?></td>
		<td class="hidden numeric"><?php echo $row['Review']; ?></td>
		<td class="text"><?php echo $row['KeyWords']; ?></td>
		<td class="hidden text"><?php echo $row['RowType']; ?></td>
		<td class="hidden numeric"><?php if( isset($row['prevstart'])) {echo date("n/j/Y H:i:s",$row['prevstart']);} ?></td>
		<td class="numeric"><?php echo timeduration($row['Elapsed'],"seconds"); ?></td>
		<td class="hidden numeric"><?php echo timeduration($row['prevTotSys'],"seconds"); ?></td>
		<td class="numeric"><?php echo timeduration($row['totalSys'],"seconds"); ?></td>
		<td class="hidden numeric"><?php echo timeduration($row['prevTotal'],"seconds"); ?></td>
		<td class="numeric"><?php echo timeduration($row['Total'],"seconds"); ?></td>
		<td class="text"><?php echo str_replace(" ", "&nbsp;", $row['FinalStatus']); ?></td>
		<td class="numeric"><?php echo $row['finalRating']; ?></td>
		<td class="hidden text"><?php echo $row['Count']; ?></td>
		<td class="hidden numeric"><a href='viewgame.php?id=<?php echo $row['ParentGameID']; ?>'><?php echo $row['ParentGameID']; ?></a></td>
		<td class="hidden numeric"><?php echo $row['LaunchDate']; ?></td>
		<td class="hidden text"><?php echo $row['FinalCountAll']; ?></td>
		<td class="text"><?php echo boolText($row['FinalCountHours']); ?></td>
		<td class="numeric"><a href='viewgame.php?id=<?php echo $row['UseGame']; ?>'><?php echo $row['UseGame']; ?></a></td>
		</tr>
		<?php
		//var_dump($row);
	} ?>
	</tbody>
	</table>
	
<?php } ?>

<?php echo Get_Footer(); ?>
