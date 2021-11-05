<?php
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/php.ini.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/functions.inc.php";

$title="Steam API All Games";
echo Get_Header($title);

$conn=get_db_connection();

include "inc/auth.inc.php";

$hitory=getHistoryCalculations("",$conn);
$games=getCalculations("",$conn);
$steamindex=makeIndex($games,"SteamID");
$conn->close();

foreach($hitory as $historyrow){
	if($historyrow['System']=="Steam"){
		$lastrecord[$historyrow['GameID']]=$historyrow;
		if ($historyrow['BaseGame']==1){
			$lastrecord[$historyrow['ParentGameID']]=$historyrow;
		}
	}
}
//print_r($historyrow);

$resultarray=GetOwnedGames();
?>
	<table>
	<thead>
	<tr>
	<th rowspan=2>Title</th>
	<th colspan=2>Playtime Forever</th>
	<th colspan=2>Playtime two weeks</th>
	<th rowspan=2>Total Time</th>
	<th colspan=4>Last Time Entry</th>
	</tr>
	<tr>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Time</th>
	<th style='top:77px;'>Keywords</th>
	</tr>
	</thead>
	<tbody>

	<?php 

	$missing_count=0;
	$missing_ids=array();
	
	
	foreach ($resultarray['response']['games'] as $key => $row) {
		$Sortby1[$key]  = $row['playtime_forever'];
	}
	array_multisort($Sortby1, SORT_DESC, $resultarray['response']['games']);

	foreach($resultarray['response']['games'] as $row){
		$rowclass="unknown";
		if(isset($steamindex[$row['appid']])){
			$thisgamedata=$games[$steamindex[$row['appid']]];
		}
		if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']]['Time'])) {
			if(round($lastrecord[$thisgamedata['Game_ID']]['Time']*60)==round($row['playtime_forever'])) {
				$rowclass="greenRow";
				//$rowclass="hidden";
			} else {
				$rowclass="redRow";
			}
		} 
		
		if ($row['playtime_forever']==0){
			$rowclass="hidden";
		}
	?>
		<tr class='<?php echo $rowclass; ?>'>
	<?php	
		if(isset($steamindex[$row['appid']])){
			//$thisgamedata=$games[$steamindex[$row['appid']]];
			?>
			<td>
			<a href='addhistory.php?GameID=<?php echo $thisgamedata['Game_ID']; ?>' target='_blank'>+</a>
			<?php
			if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']]['Time'])) { ?>
				<a href='addhistory.php?HistID=<?php echo $lastrecord[$thisgamedata['Game_ID']]['HistoryID']; ?>' target='_blank'>edit last</a>
			<?php } ?>
			
			<a href='http://store.steampowered.com/app/<?php echo $row['appid']; ?>'><?php echo $thisgamedata['Title']; ?></a>
			</td>
		<?php } else { ?>
			<td>&nbsp;&nbsp;&nbsp;<a href='http://store.steampowered.com/app/<?php echo $row['appid']; ?>'>MISSING</a></td>
			<?php
			$missing_count++;
			$missing_ids[]=$row['appid'];
			unset($thisgamedata);
		} ?>
		<td><?php echo $row['playtime_forever']; ?></td>
		<td><?php echo round($row['playtime_forever']/60,1); ?></td>
		<?php if(isset($row['playtime_2weeks'])){ ?>
			<td><?php echo $row['playtime_2weeks']; ?></td>
			<td><?php echo round($row['playtime_2weeks']/60,1); ?></td>
		<?php } else { ?>
			<td></td>
			<td></td>
		<?php }
		if (isset($thisgamedata)){ ?>
			<td><?php echo timeduration($thisgamedata['GrandTotal'],"seconds"); ?></td>
		<?php } else { ?>
			<td></td>
		<?php } 

		if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']])) { ?>
			<td><?php echo ($lastrecord[$thisgamedata['Game_ID']]['Time']*60); ?></td>
			<td><?php echo round($lastrecord[$thisgamedata['Game_ID']]['Time'],1); ?></td>
			<td><?php echo timeduration($lastrecord[$thisgamedata['Game_ID']]['Time'],"hours"); ?></td>
			<td><?php echo $lastrecord[$thisgamedata['Game_ID']]['KeyWords']; ?></td>
		<?php } else { ?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<?php } ?>
		
		<td class="hidden"><?php print_r($thisgamedata); ?> </td>
		</tr>
	<?php } ?>
	
	</tbody>
	</table>

	<br>
	
	Missing <?php echo $missing_count; ?> Games from library
	<table border=0>
	<thead>
	<tr>
	<th>Store</th>
	<th>DB</th>
	<th>ID</th>
	<th>Title</th>
	</tr>
	</thead>
	<tbody>
	
	<?php
	foreach($missing_ids as $id) {
		
		$schemaresultarray=GetSchemaForGame($id);
		//$appdetails=GetAppDetails($id);
		//$stats=GetUserStatsForGame($id);
		//$playerach=GetPlayerAchievements($id);
		?>
		<tr>
		
		<td><a href='http://store.steampowered.com/app/<?php echo $id; ?>' target='_blank'>Steam Store</a></td>
		<td><a href='https://steamdb.info/app/<?php echo $id; ?>' target='_blank'>Steam DB</a></td>
		<td><?php echo $id; ?></td>
		<?php if (isset($schemaresultarray['game']['gameName'])) { ?>
			<td><?php echo $schemaresultarray['game']['gameName']; ?></td>
		<?php } else { 
		//GetAppDetails($game['SteamID'])
		//$appdetails['data']['name']
		//CANCELLED: get the real name of the game somehow. All API options seem ineffective. 
		?>
			<td>Removed from Steam</td>
		<?php } ?>
		<td class="hidden"><?php print_r($schemaresultarray); ?> </td>
		<td class="hidden"><?php //print_r($appdetails); ?> </td>
		<td class="hidden"><?php //print_r($stats); ?> </td>
		<td class="hidden"><?php //print_r($playerach); ?> </td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

<?php echo Get_Footer(); ?>
