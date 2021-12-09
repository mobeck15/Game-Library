<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Group by Top Level";
echo Get_Header($title);

$conn=get_db_connection();

$settings=getsettings($conn);
	
$calculations=getCalculations("",$conn);

if(isset($_GET['Group'])){
	$topList=getTopList($_GET['Group'],$conn,$calculations);
} else {
	$_GET['Group']="Bundle";
	$topList=getTopList("",$conn,$calculations);
}
$conn->close();	

$calculations=reIndexArray($calculations,"Game_ID");
//TODO: Trading cards are showing up in top level bundles. Why?
?>
<ul>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Bundle'>Bundles</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Keyword'>Keyword</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Series'>Series</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Store'>Store</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=DRM'>DRM</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=OS'>OS</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Library'>Library</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Developer'>Developer</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Publisher'>Publisher</a></li>
	<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=AlphaSort'>First Letter</a></li>
	<li>Rating</li><ul>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Review'>Review</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Want'>Want</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Meta'>Metascore</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=Meta10'>(1-10)</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=UMeta'>User Metascore</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=UMeta10'>(1-10)</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=SteamR'>Steam Rating</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=SteamR10'>(1-10)</a></li>
	</ul>
	<li>Purchase</li><ul>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=PYear'>Year</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=PMonth'>Month</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=PMonthNum'>#</a></li>
		<li class="hidden"><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=PWeek'>Week</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=PWeekNum'>#</a></li>
	</ul>
	<li>Launch</li><ul>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=LYear'>Year</a></li>
		<li><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=LMonth'>Month</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=LMonthNum'>#</a></li>
		<li class="hidden"><a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=LWeek'>Week</a> <a href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Group=LWeekNum'>#</a></li>
	</ul>
</ul>
<?php
	//echo $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];
	
	if(isset($_GET['Group'])){
		echo "<p>Group by: " . $_GET['Group'];
	}
?>	
	<table>
	<thead>
	<tr>
	<th class="hidden">ID</th>
	<th>Title</th>
	<th>Paid</th>
	<th class="hidden">Return</th>
	<th class="hidden">Mod Paid</th>
	<th>Date</th>
	<th># of Items</th>
	<th># of Games</th>
	<th>Avg Want</th>
	<th>Avg Cost / Game</th>
	<th>Active</th>
	<th>Total Launch Price</th>
	<th>Total MSRP</th>
	<th>Total Historic Low</th>
	<th>Total Hours</th>
	<th>Total Launch Price of played</th>
	<th>Total MSRP of played</th>
	<th>Total Historic Low of played</th>
	<th>Played Vs. Paid</th>
	<th>$/Hr</th>
	<th>Inactive Games</th>
	<th>Unplayed Games</th>
	<th>Active Games</th>
	<th>Unfinished</th>
	<th>Unplayed & Inactive</th>
	<th>% played</th>
	<th>To Beat avg</th>
	<th>To Beat avg</th>
	<th>Least Played</th>
	<th>Most Played</th>
	<th>Filter</th>

	<tr>
	</thead>
	<tbody>
<?php
	//var_dump($topList);
	
	foreach ($topList as $key => $row) {
		$Sortby1[$key]  = $row['Title'];
	}
	array_multisort($Sortby1, SORT_ASC, $topList);

	foreach($topList as $key => &$top){
		$top['key']=$key;
		if($top['ID']<>"Total"){
			echo echoRow($top);
		}
	}

	//echo echoRow($topList['syberia']);
	//echo echoRow($topList['humble store']);
?>
	</tbody>
	<tfoot>
	<?php echo echoRow($topList['Total']); ?>
	</tfoot>
	</table>

	<a name='#Detail'></a>
	
<?php
	if(isset($_GET['Detail'])){
		//TODO: Add game type and rating to played games in detail table.
		//TODO: Add show/hide feature to detail table for playable games.
		
		//var_dump($_GET['Detail']);
		//var_dump($topList);
		?>
		Detail of <?php echo $topList[$_GET['Detail']]['Title']; ?> Bundle.
		<table>
		
		<tr>
		<thead>
		<th>Game Title</th>
		<th>Active</th>
		<th>Time Played</th>
		<th>Time Left To Beat</th>
		<th>Metascore</th>
		<th>Want</th>
		<th>Playable</th>
		<th>Count</th>
		</tr>
		</thead>

		<tbody>
		<tr class="hidden"><td colspan = 100>
		<?php echo print_r($topList[$_GET['Detail']],false); ?>
		</td></tr>
		<?php foreach ($topList[$_GET['Detail']]['Products'] as $game){ ?>
			<tr class="<?php echo $calculations[$game]['Status']; ?>">
			<td class="text"><a href='viewgame.php?id=<?php echo $game; ?>' target='_blank'><?php echo $calculations[$game]['Title']; ?></a></td>
			<td class="text"><?php echo $calculations[$game]['Status']; ?></td>
			<td class="numeric"><?php echo timeduration($calculations[$game]['totalHrs'],"seconds"); ?></td>
			<td class="numeric"><?php echo timeduration($calculations[$game]['TimeLeftToBeat'],"hours"); ?></td>
			<td class="numeric"><?php echo $calculations[$game]['Metascore']; ?></td>
			<td class="numeric"><?php echo $calculations[$game]['Want']; ?></td>
			<td class="numeric"><?php echo booltext($calculations[$game]['Playable']); ?></td>
			<td class="numeric"><?php echo booltext($calculations[$game]['CountGame']); ?></td>
			</tr>
		<?php } ?>
		</tbody>
		</table>
	<?php }
echo Get_Footer();

function echoRow($top){
	if($top['Filter']==1){
		$output  = "\n\n<tr class=\"Inactive\">\r\n";
	} else {
		$output  = "\n\n<tr >\r\n";
	}
	//$output .= "\t<td class=\"numeric\">".$top['ID']."</td>";
	//$id=$top['ID']; // Broken when sorting the list
	$id=$top['key']; // Needed to get the right bundle after sorting.
	$output .= "\t<td><a href='".$_SERVER['SCRIPT_NAME']."?Group=".$_GET['Group']."&Detail=".$id."##Detail'>".$top['Title']."</a></td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['Paid'],2)."</td>\r\n";
	//$output .= "\t<td class=\"numeric\">". "" ."</td>\r\n"; //Return
	//$output .= "\t<td class=\"numeric\">". "" ."</td>\r\n"; //Mod Paid
	$output .= "\t<td class=\"numeric\">";
	
	//TODO: Not owned has no sequence - causes problems
	//if ($top['PurchaseDate'] =="") {$top['PurchaseDate']=0;}
	$output .= combinedate($top['PurchaseDate'],$top['PurchaseTime'],$top['PurchaseSequence']);
	$output .= "</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['ItemCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['GameCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".($top['AvgWant']<>0 ? round($top['AvgWant'],2):"")."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".round($top['AvgCost'],2)."</td>\r\n";
	$output .= "\t<td class=\"text\">".booltext($top['Active'])."</td>\r\n";
	//$output .= "\t<td class=\"text\">".$top['Active']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['TotalLaunch'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['TotalMSRP'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['TotalHistoric'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".timeduration($top['TotalHours'],"seconds")."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['TotalLaunchPlayed'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['TotalMSRPPlayed'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".$top['TotalHistoricPlayed']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['PlayVPay'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">$".round($top['PayHr'],2)."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['InactiveCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['UnplayedCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['ActiveCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['IncompleteCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".$top['UnplayedInactiveCount']."</td>\r\n";
	$output .= "\t<td class=\"numeric\">".round($top['PctPlayed'],2)."%</td>\r\n";
	if($top['ID']=="Total") {
		$output .= "\t<td class=\"numeric\">".round($top['BeatAvg'],2)."%</td>\r\n";
		$output .= "\t<td class=\"numeric\">".round($top['BeatAvg2'],2)."%</td>\r\n";
	} else {
		$output .= "\t<td class=\"numeric\">". $top['BeatAvg'] ."</td>\r\n"; //Beat Avg
		$output .= "\t<td class=\"numeric\">". $top['BeatAvg2'] ."</td>\r\n"; //Beat Avg2
	}
	$output .= "\t<td class=\"text\"><a href='viewgame.php?id=".$top['leastPlay']['ID']."' target='_blank'>".$top['leastPlay']['Name']."</a></td>\r\n";
	$output .= "\t<td class=\"text\"><a href='viewgame.php?id=".$top['mostPlay']['ID']."' target='_blank'>".$top['mostPlay']['Name']."</a></td>\r\n";
	$output .= "\t<td class=\"text\">".booltext($top['Filter'])."</td>\r\n";
	
	//$output .= "\t<td>".print_r($top['Products'],true)."</td>\r\n";
	$output .= "</tr>\r\n";
	//$output .= "<tr><td colspan=100>".print_r($top['Products'],true)."</td></tr>\r\n";
	//$output .= "<tr><td colspan=100>".print_r($top['RawData'],true)."</td></tr>\r\n";	
	//$output .= "<tr><td colspan=100>".print_r($top,true)."</td></tr>\r\n";
	
	return $output;
}

?>