<?php
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/php.ini.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/functions.inc.php";

$title="Activity";
echo Get_Header($title);

include "inc/functions.inc.php";

$conn=get_db_connection();

$settings=getsettings($conn);
$History=getHistoryCalculations("",$conn);
$activity=getActivityCalculations("",$History,$conn);
$conn->close();	

//TODO: Add show/hide columns functions
//TODO: Add sorting functions
//TODO: Add list filters
?>
<table>
<thead>
<tr>
<th>Games</th>
<th>First Play</th>
<th>Last Play</th>
<th class="hidden">Last time</th>
<th class="hidden">Total Hrs</th>
<th>Achievements</th>
<th>Status</th>
<th>Last Rating</th>
<th>Last Beat</th>
<th class="hidden">Base Game</th>
<th class="hidden">Launch Date</th>
<th>Grand Total</th>
<th class="hidden">Week Play</th>
<th class="hidden">Month Play</th>
<th class="hidden">Year Play</th>
<th class="hidden">Week Achievements</th>
<th class="hidden">Month Achievements</th>
<th class="hidden">Year Achievements</th>
<th class="hidden">Add</th>
</tr>
</thead>
<tbody>
<?php
//var_dump($activity);
	
foreach ($activity as $totals) { ?>
	<tr class="<?php echo $totals['Status']; ?>">
	<td class="text"><a href='viewgame.php?id=<?php echo $totals['ID']; ?>' target='_blank'><?php echo $totals['Games']; ?></a></td>
	<td class="numeric"><?php echo $totals['firstplay']; ?></td>
	<td class="numeric"><?php echo $totals['lastplay']; ?></td>
	<td class="hidden numeric"><?php echo timeduration($totals['elapsed'],"seconds"); ?></td>
	<td class="hidden numeric"><?php echo timeduration($totals['totalHrs'],"seconds"); ?></td>
	<td class="numeric"><?php echo $totals['Achievements']; ?></td>
	<td class="text"><?php echo $totals['Status']; ?></td>
	<td class="numeric"><?php echo $totals['Review']; ?></td>
	<td class="numeric"><?php echo $totals['LastBeat']; ?></td>
	<td class="hidden numeric"><a href='viewgame.php?id=<?php echo $totals['Basegame']; ?>'><?php echo $totals['Basegame']; ?></a></td>
	<td class="hidden numeric"><?php echo $totals['LaunchDate']; ?></td>
	<td class="numeric"><?php echo timeduration($totals['GrandTotal'],"seconds"); ?></td>
	<td class="hidden numeric"><?php echo timeduration($totals['weekPlay'],"seconds"); ?></td>
	<td class="hidden numeric"><?php echo timeduration($totals['monthPlay'],"seconds"); ?></td>
	<td class="hidden numeric"><?php echo timeduration($totals['yearPlay'],"seconds"); ?></td>
	<td class="hidden numeric"><?php echo $totals['WeekAchievements']; ?></td>
	<td class="hidden numeric"><?php echo $totals['MonthAchievements']; ?></td>
	<td class="hidden numeric"><?php echo $totals['YearAchievements']; ?></td>
	<td class="hidden numeric"><a href='addhistory.php?GameID=<?php echo $totals['ID']; ?>' target='_blank'>Add</a></td>
	</tr>
	<?php
	
	//var_dump($totals);
	//echo "<br><br>";
	unset($totals);
} ?>
</tbody>
</table>

<?php echo Get_Footer(); ?>