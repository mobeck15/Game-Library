<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";

//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)

class addhistoryPage extends Page
{
	private $dataAccessObject;
	
	public function __construct() {
		$this->title="Add History";
	}
	
	public function buildHtmlBody(){
		//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)

		$title="Add History";
		echo Get_Header($title);

		$conn=get_db_connection();

		//Hard Coded Default Values
		$usedate=date("Y-m-d");
		$usetime=date("H:i:s");
		$defaultSystem="Steam";
		$defaultData="Add Time";
		$defaultStatus="Inactive";
		$defaultReview=1;
		$gameTitle="";
		$ptForever="";
		$achearned=0;
		$reviewValues=array(1,2,3,4);

		$this->dataAccessObject= new dataAccess();
		$maxID=$this->dataAccessObject->getMaxHistoryId();
		
//		if (isset($_POST['datarow'])){
//			//TODO: cleanse post data
//			$this->captureInsert($_POST['datarow'],$_POST['timestamp']);
//		}
		
//Capture Inserts
if (isset($_POST['datarow'])){
	//var_dump($_POST['datarow']);

	//if($_POST['datarow'][1]['Data']=="Start/Stop"){
	if(isset($_POST['datarow'][1]['ProductID'])) {
		$_GET['GameID']=$_POST['datarow'][1]['ProductID']; 
	} else {
		$_GET['HistID']=$maxID;
	}


	$sql2="";
	if(isset($_POST['datarow'][1]['id'])) {
		$insertrow=$_POST['datarow'][1];
		
		$sql ="UPDATE `gl_history` ";
		$sql.="SET `Timestamp` = '" . date("Y-m-d H:i:s",strtotime($conn->real_escape_string($_POST['timestamp']))) ."', "; //Timestamp  2015/09/18 22:08:55
		$sql.=" `Game` = '".          $conn->real_escape_string($insertrow['Title'])        . "', "; //Game (Name)
		$sql.=" `System` = '" .       $conn->real_escape_string($insertrow['System'])       . "', "; //System
		$sql.=" `Data` = '" .         $conn->real_escape_string($insertrow['Data'])         . "', "; //Data
		$sql.=" `Time` = '" .         $conn->real_escape_string($insertrow['hours'])        . "', "; //Time
		$sql.=" `Notes` = '" .        $conn->real_escape_string($insertrow['notes'])        . "', "; //Notes
		$sql.=" `RowType` = '" .      $conn->real_escape_string($insertrow['source'])       . "', "; //Source / RowType
		$sql.=" `Achievements` = '" . $conn->real_escape_string($insertrow['achievements']) . "', "; //Achivements
		$sql.=" `Status` = '" .       $conn->real_escape_string($insertrow['status'])       . "', "; //Status
		$sql.=" `Review` = '" .       $conn->real_escape_string($insertrow['review'])       . "', "; //Review
		$sql.=" `BaseGame` = '" .      (isset($insertrow['basegame']) && $insertrow['basegame'] == "on" ? 1 : 0)        . "', "; //BaseGame
		$sql.=" `kwMinutes` = '" .     (isset($insertrow['minutes']) && $insertrow['minutes'] == "on" ? 1 : 0)          . "', "; //Keyword Minutes
		$sql.=" `kwIdle` = '" .        (isset($insertrow['idle']) && $insertrow['idle'] == "on" ? 1 : 0)                . "', "; //Keyword Idle
		$sql.=" `kwCardFarming` = '" . (isset($insertrow['cardfarming']) && $insertrow['cardfarming'] == "on" ? 1 : 0)  . "', "; //Keyword Card Farming
		$sql.=" `kwCheating` = '" .    (isset($insertrow['cheating']) && $insertrow['cheating'] == "on" ? 1 : 0)        . "', "; //Keyword Cheating
		$sql.=" `kwBeatGame` = '" .    (isset($insertrow['beatgame']) && $insertrow['beatgame'] == "on" ? 1 : 0)        . "', "; //Keyword Beat Game
		$sql.=" `kwShare` = '" .       (isset($insertrow['share']) && $insertrow['share'] == "on" ? 1 : 0)              . "', "; //Keyword Share
		$sql.=" `GameID` = '".        $conn->real_escape_string($insertrow['ProductID'])    ."'"; //GameID
		$sql.=" WHERE `gl_history`.`HistoryID` = ".$conn->real_escape_string($insertrow['id']);
	} else {
		$sql  = "INSERT INTO `gl_history` (";
		$sql .= "`HistoryID`, ";
		$sql .= "`Timestamp`, ";
		$sql .= "`Game`, ";
		$sql .= "`System`, ";
		$sql .= "`Data`, ";
		$sql .= "`Time`, ";
		$sql .= "`Notes`, ";
		$sql .= "`RowType`, ";
		$sql .= "`Achievements`, ";
		$sql .= "`Status`, ";
		$sql .= "`Review`, ";
		$sql .= "`BaseGame`, ";
		$sql .= "`kwMinutes`, ";
		$sql .= "`kwIdle`, ";
		$sql .= "`kwCardFarming`, ";
		$sql .= "`kwCheating`, ";
		$sql .= "`kwBeatGame`, ";
		$sql .= "`kwShare`, ";
		$sql .= "`GameID`";
		$sql .= ") VALUES ";
		
		//loop through all data rows
		foreach($_POST['datarow'] as $insertrow){
			//Check if the indicator is on for update that row.
			if(isset($insertrow['update']) && $insertrow['update']=="on"){
				//print_r($insertrow);
				if($sql2<>"") {$sql2.=",";}
				
				if(isset($insertrow['id'])) {
					$sql2.="('" . $conn->real_escape_string($insertrow['id']) . "', "; // HistoryID
				} else {
					$sql2.="('" . ($maxID) . "', "; // HistoryID
					$maxID++;
				}
				
				if( isset($_POST['currenttime']) && $_POST['currenttime'] == "on") {
					$sql2.="'" . date("Y-m-d H:i:s") . "', "; //Time
				} else {
					$sql2.="'" . date("Y-m-d H:i:s",strtotime($conn->real_escape_string($_POST['timestamp']))) ."', "; //Timestamp  2015/09/18 22:08:55
				}
				$sql2.="'".$conn->real_escape_string($insertrow['Title'])."', "; //Game (Name)
				$sql2.="'" . $conn->real_escape_string($insertrow['System']) . "', "; //System
				$sql2.="'" . $conn->real_escape_string($insertrow['Data']) . "', "; //Data
				$sql2.="'" . $conn->real_escape_string($insertrow['hours']) . "', "; //Time
				$sql2.="'" . $conn->real_escape_string($insertrow['notes']) . "', "; //Notes
				$sql2.="'" . $conn->real_escape_string($insertrow['source']) . "', "; //Source / RowType
				$sql2.="'" . $conn->real_escape_string($insertrow['achievements']) . "', "; //Achivements
				$sql2.="'" . $conn->real_escape_string($insertrow['status']) . "', "; //Status
				$sql2.="'" . $conn->real_escape_string($insertrow['review']) . "', "; //Review
				$sql2.="'" . (isset($insertrow['basegame']) && $insertrow['basegame'] == "on" ? 1 : 0)  . "', "; //BaseGame
				$sql2.="'" . (isset($insertrow['minutes']) && $insertrow['minutes'] == "on" ? 1 : 0)  . "', "; //Keyword Minutes
				$sql2.="'" . (isset($insertrow['idle']) && $insertrow['idle'] == "on" ? 1 : 0)  . "', "; //Keyword Idle
				$sql2.="'" . (isset($insertrow['cardfarming']) && $insertrow['cardfarming'] == "on" ? 1 : 0)  . "', "; //Keyword Card Farming
				$sql2.="'" . (isset($insertrow['cheating']) && $insertrow['cheating'] == "on" ? 1 : 0)  . "', "; //Keyword Cheating
				$sql2.="'" . (isset($insertrow['beatgame']) && $insertrow['beatgame'] == "on" ? 1 : 0)  . "', "; //Keyword Beat Game
				$sql2.="'" . (isset($insertrow['share']) && $insertrow['share'] == "on" ? 1 : 0)  . "', "; //Keyword Share
				$sql2.="'". $conn->real_escape_string($insertrow['ProductID']) ."')"; //GameID			
			}
		}
	}

	if ($conn->query($sql.$sql2) === TRUE) {
		echo "Record updated successfully<br>";

		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $sql.$sql2.";\r\n", FILE_APPEND | LOCK_EX);

	} else {
		trigger_error( "Running Query: " . $sql.$sql2 ,E_USER_NOTICE   );
		trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
	}
}

//determines if a game is active or not.
//If a game is active, overrides chosen input and forces a second entry of active game.
$GameStarted=false;
$sql="SELECT * FROM `gl_history` order by `Timestamp` DESC Limit 1";
if($result = $conn->query($sql)){
	if ($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$sql="SELECT count(*) as count FROM `gl_history` where `GameID`= ".$row['GameID']. " AND `Data`='Start/Stop' order by `Timestamp` DESC";
		if($result = $conn->query($sql)){
			if ($result->num_rows > 0){
				$row2 = $result->fetch_assoc();
				if($row2['count'] % 2 == 0) {
					$GameStarted=false;
				} else {
					$GameStarted=true;
					$_GET['GameID']=$row['GameID'];
				}
			}
		}
	}
}


echo "Game Started: ".booltext($GameStarted);


  echo '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<br/>
<span>   <a href="'. $_SERVER['PHP_SELF'].'">Manual Entry</a></span>
<span> | <a href="'. $_SERVER['PHP_SELF'].'?mode=steam">Steam API</a></span>
<span class="hidden"> | <a href="">Local Time Log</a></span>
<span class="hidden"> | <a href="">Game Time Tracker DB</a></span>
<span class="hidden"> | <a href="">Game Time Tracker Export</a></span>';

if(isset($_GET['mode']) && $_GET['mode']=="steam") {
	$hitory=getHistoryCalculations("",$conn);
	$games=getCalculations("",$conn);
	$steamindex=makeIndex($games,"SteamID");
	
	foreach($hitory as $historyrow){
		if($historyrow['System']=="Steam"){
			$lastrecord[$historyrow['GameID']]=$historyrow;
			if ($historyrow['BaseGame']==1){
				$lastrecord[$historyrow['ParentGameID']]=$historyrow;
			}
		}
	}

	echo "<table>
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
	<tbody>";

	$steamAPI= new SteamAPI();
	$resultarray=$steamAPI->GetSteamAPI("GetRecentlyPlayedGames");
	
	$missing_count=0;
	$updatelist=array();
	foreach($resultarray['response']['games'] as $row){
		$rowclass="unknown";
		if(isset($steamindex[$row['appid']])){
			$thisgamedata=$games[$steamindex[$row['appid']]];
		
			if(isset($lastrecord[$thisgamedata['Game_ID']]['Time']) && round($lastrecord[$thisgamedata['Game_ID']]['Time']*60)==round($row['playtime_forever'])) {
				$rowclass="greenRow";
			} else {
				$rowclass="redRow";
				$updatelist[]=array("GameID"=>$thisgamedata['Game_ID'], "Time"=>$row['playtime_forever']);
			}
		} else {
			$rowclass="redRow";
		}
		
		echo "<tr class='".$rowclass."'>";
		
		if(isset($steamindex[$row['appid']])){
			echo '<td class="Text">
				<a href="addhistory.php?GameID='. $thisgamedata['Game_ID'].'" target="_blank">+</a>
				<a href="viewgame.php?id='. $thisgamedata['Game_ID'].'" target="_blank">'. $thisgamedata['Title'].'</a>
				<span style="font-size: 70%;">(<a href="http://store.steampowered.com/app/'. $row['appid'].'" target="_blank">Store</a>)</span>
			</td>';
		} else {
			$resultarray2=$steamAPI->GetSteamAPI("GetUserStatsForGame");
			$gamename="";
			if(isset($resultarray2['playerstats']['gameName'])){
				$gamename=$resultarray2['playerstats']['gameName'];
			}
			
			echo '<td class="Text">&nbsp;&nbsp;&nbsp;MISSING: <a href="http://store.steampowered.com/app/'. $row['appid'].'" target="_blank">'. $gamename.'</a></td>';
			$missing_count++;
			$missing_ids[]=$row['appid'];
			unset($thisgamedata);
		}
		?>
		<td class="numeric"><?php echo $row['playtime_forever']; ?></td>
		<td class="numeric"><?php echo round($row['playtime_forever']/60,1); ?></td>
		<?php
		if(isset($row['playtime_2weeks'])){
			?>
			<td class="numeric"><?php echo $row['playtime_2weeks']; ?></td>
			<td class="numeric"><?php echo round($row['playtime_2weeks']/60,1); ?></td>
			<?php
		} else {
			?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<?php
		}
		if (isset($thisgamedata)){
			?>
			<td class="numeric"><?php echo timeduration($thisgamedata['GrandTotal'],"seconds"); ?></td>
			<?php
			if (isset($lastrecord[$thisgamedata['Game_ID']])) {
				?>
				<td class="numeric"><?php echo ($lastrecord[$thisgamedata['Game_ID']]['Time']*60); ?></td>
				<td class="numeric"><?php echo round($lastrecord[$thisgamedata['Game_ID']]['Time'],1); ?></td>
				<td class="numeric"><?php echo timeduration($lastrecord[$thisgamedata['Game_ID']]['Time'],"hours"); ?></td>
				<td class="Text"><?php echo $lastrecord[$thisgamedata['Game_ID']]['KeyWords']; ?></td>
				<?php
			} else {
				?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<?php
			}
		} else {
			?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<?php
		}
		
		unset($thisgamedata);
		?>
		</tr>
		<?php
	}
	
	unset($resultarray);
	

echo "	</tbody>
	</table>";

	$gameIndex=makeIndex($games,"Game_ID");

	if (count($updatelist)>0){
		?>
		<hr>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?mode=steam" method="post">
		<table class='ui-widget'>
		<thead>
			<tr>
				<th>Update</th>
				<th>Game ID</th>
				<th>Title</th>
				<th>Time</th>
				<th>Notes</th>
				<th>Achievements</th>
				<th>Status</th>
				<th>Review</th>
				<th>Base Game</th>
				<th>Minutes</th>
				<th>Idle</th>
				<th>Card Farming</th>
				<th>Beat Game</th>
				<th class='hidden'>Share</th>
				<th>Cheating</th>
			</tr>
		</thead>
		<tbody>

		<?php
		$counter=0;
		foreach($updatelist as $record){
			$counter++;
			$notes="";
			$thisgamedata=$games[$gameIndex[$record['GameID']]];
			//print_r($record);
			
			$sql="SELECT `Title`, `gl_history`.*, `SteamID` 
				FROM `gl_products` 
				left join `gl_history` on `gl_history`.`GameID` = `gl_products`.`Game_ID` 
				WHERE `Game_ID`=".$record['GameID']." 
				ORDER by `Timestamp` desc;";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					$LastGameRecord = $result->fetch_assoc();
					while(($LastGameRecord['Status']=="" OR $LastGameRecord['Review']=="") AND $row = $result->fetch_assoc()) {
						if($row['Status']<>"" AND $LastGameRecord['Status']==""){
							$LastGameRecord['Status']=$row['Status'];
						}
						
						if($row['Review']<>"" AND $LastGameRecord['Review']==""){
							$LastGameRecord['Review']=$row['Review'];
						}
					}
				}
			}

			/* */
			if(isset($thisgamedata['SteamID']) && $thisgamedata['SteamID']>0) {
				$achearned=0;
				//$resultarray=GetSchemaForGame($thisgamedata['SteamID']);
				$steamAPI= new SteamAPI($thisgamedata['SteamID']);
				$resultarray=$steamAPI->GetSteamAPI("GetSchemaForGame");
				//var_dump($resultarray['game']);
				if(isset($resultarray['game']['availableGameStats']['achievements'])) {
					$acharray=regroupArray($resultarray['game']['availableGameStats']['achievements'],"name");
				}
				//var_dump($acharray);
				
				//$userstatsarray=GetPlayerAchievements($thisgamedata['SteamID']);
				$userstatsarray=$steamAPI->GetSteamAPI("GetPlayerAchievements");
				if(isset($userstatsarray['playerstats']['achievements'])){
					foreach ($userstatsarray['playerstats']['achievements'] as $achievement2){
						//Count achievements earned
						if($achievement2['achieved']==1){
							
							if(strtotime($LastGameRecord['Timestamp']) < $achievement2['unlocktime']){
								//DONE: get names of achievements earned and add them to the notes field.
								
								//var_dump($achievement2); echo "<br>";
								//echo $thisgamedata['SteamID']. " " . $thisgamedata['Title'] . ": " . $LastGameRecord['Timestamp'] . " >?< " . date("Y-m-d h:m:s",$achievement2['unlocktime']);
								//echo " - " .$achievement2['apiname'];
								//echo " - " . $acharray[$achievement2['apiname']][0]['displayName'];
								//var_dump($acharray[$achievement2['apiname']]);
								//echo "<br>";
								
								$notes .=$acharray[$achievement2['apiname']][0]['displayName']."\r\n";
							}
							
							$achearned++;
						}
						//echo $achearned."<br>";
					}
				}
			}
			/* */
			
			//echo "<tr><td colspan=15>"; var_dump($record); echo"</td></tr>";
			//echo "<tr><td colspan=15>"; var_dump($thisgamedata); echo"</td></tr>";
			
			?>
			<tr>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][update]" CHECKED><span class="slider round"></span></label></td>
			<td><input type="number"   name="datarow[<?php echo $counter; ?>][ProductID]" value="<?php echo $record['GameID']; ?>" min="0" max="99999"></td>
			<td><a href='viewgame.php?id=<?php echo $record['GameID']; ?>' target='_blank'><?php echo $thisgamedata['Title']; ?></a></td>
			    <input type="hidden"   name="datarow[<?php echo $counter; ?>][Title]"  value='<?php echo htmlspecialchars($thisgamedata['Title']); ?>' >
			    <input type="hidden"   name="datarow[<?php echo $counter; ?>][System]" value='Steam' >
			    <input type="hidden"   name="datarow[<?php echo $counter; ?>][Data]"   value='New Total' >
			    <input type="hidden"   name="datarow[<?php echo $counter; ?>][source]" value='Game Library 6' >
			<td><input type="number"   name="datarow[<?php echo $counter; ?>][hours]"  value='<?php echo $record['Time']; ?>' min="0" max="999999"></td>
			<td><textarea align=top rows=2 cols=18 name="datarow[<?php echo $counter; ?>][notes]"><?php echo trim($notes); ?></textarea></td>
			<td><input type="number"   name="datarow[<?php echo $counter; ?>][achievements]" value='<?php echo $achearned; ?>'  min="0" max="9999"></td>
			<?php
			$defaultStatus=$LastGameRecord['Status'];
			if($defaultStatus==""){
				$defaultStatus="Inactive";
			}
			$defaultReview=$LastGameRecord['Review'];
			
			//var_dump($LastGameRecord);	echo "<p>";
			
			echo "<td><select  name=\"datarow[".$counter."][status]\">";
			$sql="SELECT `Status` FROM `gl_status` order by `Active` DESC, `Count` DESC";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						if($defaultStatus==$row['Status']) {$selected=" SELECTED "; } else {$selected="";}
						echo "<option value='".$row['Status']."'".$selected.">".$row['Status']."</option>";
					}
				}
			} else {
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
			}
			
			echo "</select></td>";
			echo "<td><Select name=\"datarow[".$counter."][review]\">";
			echo "<option value=''> </option>";
			foreach($reviewValues as $review){
				if($defaultReview==$review) {$selected=" SELECTED "; } else {$selected="";}
				echo "<option value='".$review."'".$selected.">".$review."</option>";
			}			
			echo "</select></td>";
			?>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][basegame]"       ><span class="slider round"></span></label></td>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][minutes]" CHECKED><span class="slider round"></span></label></td>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][idle]"           ><span class="slider round"></span></label></td>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][cardfarming]"    ><span class="slider round"></span></label></td>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][beatgame]"       ><span class="slider round"></span></label></td>
			<td class='hidden'><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][share]"          ><span class="slider round"></span></label></td>
			<td><label class="switch"><input type="checkbox" name="datarow[<?php echo $counter; ?>][cheating]"       ><span class="slider round"></span></label></td>
			</tr>
			<?php
			unset($thisgamedata);
		}
		?>
		<tr><td colspan=15>
		<input type="datetime-local" name="timestamp" id="timestamp" value=<?php echo "'".$usedate."T".$usetime."'"; ?>>
		
		<label class="switch"><input type="checkbox" name="currenttime" checked><span class="slider round"></span></label>
		Ignore and use current time
		</td></tr>
		
		<tr><td colspan=15>
		<input type='submit' name='Submit' value='Submit'><p>
		</td></tr>
		
		</tbody></table>
		<?php
	}
	
} else {
	
	if (isset($_GET['HistID']) && $GameStarted == False) {
		//If a game is not started and the HistID is set, load the time from the database instead.
		$sql="SELECT * FROM `gl_history` join `gl_products` on `gl_history`.`GameID` = `gl_products`.`Game_ID` WHERE `HistoryID`=".$_GET['HistID'];
		if($result = $conn->query($sql)){
			if ($result->num_rows > 0){
				$HistoryRecord = $result->fetch_assoc();
				$_GET['GameID']=$HistoryRecord['GameID'];
				$usedate=date("Y-m-d",strtotime($HistoryRecord['Timestamp']));
				$usetime=date("H:i:s",strtotime($HistoryRecord['Timestamp']));
			}
		}
	}

?>
<form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];?>" method="post">
<table class="ui-widget">
	<thead>
	<tr>
		<th colspan=7>New History Record</th>
	</tr>

	<tr>
		<th>Field</th>
		<th colspan=4>Value</th>
		<th>Description</th>
		<th>Lookup Prompt</th>
	</tr>
	</thead>

	<tr class="hidden">
	<th>Update Record</th>
	<td>
		<?php 
		//for single entry, a hidden field will always update the data.
		//for multiple entry, the chekcbox allows less than all to be selected for update.
		?>
		<input type="hidden" name="datarow[1][update]" value="on" >
		ON
	</td>
	</tr>
	
	<?php 
	if(isset($_GET['GameID'])) {
		if(isset($HistoryRecord)){
	?>
	<tr>
	<th>History ID</th>
	<td colspan=4>
		<input type="hidden" name="datarow[1][id]" value="<?php echo $HistoryRecord['HistoryID'] ?>">
		<?php echo $HistoryRecord['HistoryID'] ?>
	</td>
	<td>The ID of this history record.</td>
	</tr>
	<?php 
			$LastGameRecord=$HistoryRecord;
			$LastGameRecord['Title']=$HistoryRecord['Game'];
		} else {
			//DONE: update to scan full query and populate values for rating, status etc.
			$sql="SELECT `Title`, `gl_history`.*, `SteamID` 
				FROM `gl_products` 
				left join `gl_history` on `gl_history`.`GameID` = `gl_products`.`Game_ID` 
				WHERE `Game_ID`=".$_GET['GameID']." 
				ORDER by `Timestamp` desc;";
				//limit 1";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					$LastGameRecord = $result->fetch_assoc();
					while(($LastGameRecord['Status']=="" OR $LastGameRecord['Review']=="") AND $row = $result->fetch_assoc()) {
						if($row['Status']<>"" AND $LastGameRecord['Status']==""){
							$LastGameRecord['Status']=$row['Status'];
						}
						
						if($row['Review']<>"" AND $LastGameRecord['Review']==""){
							$LastGameRecord['Review']=$row['Review'];
						}
					}
				} else {
					$sql="SELECT `Title` FROM `gl_products` WHERE `Game_ID`=".$_GET['GameID']." limit 1";
					if($result2 = $conn->query($sql)){
						if ($result2->num_rows > 0){
							$record=$result2->fetch_assoc();
							$LastGameRecord['Title']=$record['Title'];
							$LastGameRecord['System']="Steam";
							$LastGameRecord['Data']="New Total";
							$LastGameRecord['Status']="";
							$LastGameRecord['Review']="";
						}
					}
				}
			}
		}

		$gameTitle=$LastGameRecord['Title'];
		$defaultSystem=$LastGameRecord['System'];
		$defaultData=$LastGameRecord['Data'];
		$defaultStatus=$LastGameRecord['Status'];
		$defaultReview=$LastGameRecord['Review'];

		if($defaultSystem=="") {$defaultSystem="Steam";}
		if($defaultData=="") {$defaultData="New Total";}
		
		if(isset($LastGameRecord['SteamID']) && $LastGameRecord['SteamID']>0) {
			$api = new SteamAPI($LastGameRecord['SteamID']);
			$result = $api->GetSteamAPI("GetSchemaForGame");

			$achtotal=0;
			$achearned=0;

			if(isset($resultarray['game']['availableGameStats']['achievements'])){
				foreach ($resultarray['game']['availableGameStats']['achievements'] as $achievement){
					//Count achievements
					$achtotal++;
				}
			}
			
			$resultarray2 = $api->GetSteamAPI("GetUserStatsForGame");
			//echo "---DEBUG---";
			//var_dump($resultarray2);
			//echo "---DEBUG---";

			if(isset($resultarray2['playerstats']['achievements'])){
				foreach ($resultarray2['playerstats']['achievements'] as $achievement2){
					//Count achievements earned
					$achearned++;
				}
			}	

			$resultarray3 = $api->GetSteamAPI("GetOwnedGames");
			
			foreach($resultarray3['response']['games'] as $row){
				if($row['appid']==$LastGameRecord['SteamID']){
					$ptForever=$row['playtime_forever'];
					$ptForeverHrs = round($row['playtime_forever']/60,1);
					if(isset($row['playtime_2weeks'])){
						$pt2weeks=$row['playtime_2weeks'];
						$pt2weeksHrs=round($row['playtime_2weeks']/60,1);
					}
				}
			}
		}
	}
	?>

	<tr>
		<th>Product ID</th>
		<td colspan=4><input type="number" name="datarow[1][ProductID]" min="0" class='auto' id="ProductID" value="<?php echo (isset($_GET['GameID']) ? $_GET['GameID'] : ""); ?>"></td>
		<td>The product this item is linked to. (GameID)</td>
		<td>(?)<input id="Product" name="datarow[1][Title]" onchange="setNotes()" size=30 value="<?php echo (isset($gameTitle) ? $gameTitle : ""); ?>">
		<input type="button" value="New"></td>
	</tr>
	
	<script>
	  $(function() {
			$('#Product').autocomplete({ 
				source: "./ajax/search.ajax.php",
				select: function (event, ui) { 
					$("#ProductID").val(ui.item.id);
				} }
			);
		} );
	</script>
	
	<tr>
		<th>Timestamp *</th>
		<td colspan=4><input type="datetime-local" name="timestamp" id="timestamp" value=<?php echo "'".$usedate."T".$usetime."'"; ?>>
		<?php 
		//Only include this checkbox if there is no existing history record being edited.
		if (!isset($HistoryRecord)) { ?>
		<br>
		Ignore and use current time: <label class="switch"><input type="checkbox" name="currenttime" checked><span class="slider round"></span></label></td>
		<?php } ?>
		<td>The Date and Time the history record will be recorded at
		</td>
		<td></td>
	</tr>
	<tr>
		<th>System *</th>
		<td colspan=4><select name='datarow[1][System]'>
		<?php
			$sql="SELECT DISTINCT `system` FROM `gl_history` 
			where `system` is not null
			OR `system` <>''
			order by `system`";

			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						if($defaultSystem==$row['system']) {$selected=" SELECTED "; } else {$selected="";}
						echo "<option value='".$row['system']."'".$selected.">".$row['system']."</option>";
					}
				}else {
					//$activity = false;
				}
			} else {
				//$activity = false;
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
			}		
		?>
		</select></td>
		<td>The system on which the game was played</td>
		<td></td>
	</tr>
	<tr>
		<th>Data Type *</th>
		<td colspan=4><select name='datarow[1][Data]'>
		<?php
			$sql="SELECT DISTINCT `Data` FROM `gl_history` 
			where `system` is not null
			order by `system`";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						if($defaultData==$row['Data']) {$selected=" SELECTED "; } else {$selected="";}
						echo "<option value='".$row['Data']."'".$selected.">".$row['Data']."</option>";
					}
				}else {
					//$activity = false;
				}
			} else {
				//$activity = false;
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
			}		
		?>
		</select></td>
		<td>The type of record that will be added.</td>
		<td></td>
	</tr>
	<tr>
		<th>Duration</th>
		<td colspan=4><input type="number" name="datarow[1][hours]" min="0" id="hours" step="0.00001" value="<?php echo (isset($HistoryRecord) ? (float)$HistoryRecord['Time'] : $ptForever); ?>"></td>
		<td>How long playtime in Hours (or Minutes)</td>
		<td></td>
	</tr>
	<tr>
		<th>Notes</th><?php
			//echo "<br>GAME ID: "; var_dump($_GET['GameID']);
			//echo "<br>HIST ID: "; var_dump($_GET['HistID']);
			//echo "<br>Historyrecord: "; var_dump($HistoryRecord);
			if (isset($_GET['GameID']) OR isset($_GET['HistID'])) {
				//DONE: This function throws errors after saving an edit of a history record. for missing $_GET['GameID']. Set the GameID based on HistID.
				//DONE: Achievement list does not work for game record
				//DONE: Does not pull achievement earned with appropriate timestamps. Review all calculations below.
				$notes="";
				
				//echo $_GET['GameID'];
				$games=getCalculations("",$conn);
				$gameIndex=makeIndex($games,"Game_ID");
				$thisgamedata=$games[$gameIndex[$_GET['GameID']]];
				//var_dump($thisgamedata); echo "<br>";

				$sql="SELECT `Title`, `gl_history`.*, `SteamID` 
					FROM `gl_products` 
					left join `gl_history` on `gl_history`.`GameID` = `gl_products`.`Game_ID` 
					WHERE `Game_ID`=".$_GET['GameID']." 
					AND `Timestamp` < '".date("Y-m-d H:i:s",strtotime($usedate." ".$usetime))."'
					ORDER by `gl_history`.`Timestamp` desc;";
					//echo $sql ."<p>";
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0){
						$LastGameRecord = $result->fetch_assoc();
						//echo "<p>Lastgamerecord: "; var_dump($LastGameRecord); //2020-04-18 23:54:45
						while(($LastGameRecord['Status']=="" OR $LastGameRecord['Review']=="") AND $row = $result->fetch_assoc()) {
						//while($row = $result->fetch_assoc()) {
							//echo "<p>Record: "; var_dump($LastGameRecord); //2020-04-18 23:54:45
							if($row['Status']<>"" AND $LastGameRecord['Status']==""){
								$LastGameRecord['Status']=$row['Status'];
							}
							
							if($row['Review']<>"" AND $LastGameRecord['Review']==""){
								$LastGameRecord['Review']=$row['Review'];
							}
						}
						//echo "<p>Lastgamerecord: "; var_dump($LastGameRecord); //2020-04-18 23:54:45
					} else {
						$LastGameRecord['Timestamp']=0;
					}
				}
				
				if(isset($thisgamedata['SteamID']) && $thisgamedata['SteamID']>0) {
					$achearned=0;
					$api2 = new SteamAPI($thisgamedata['SteamID']);
					$resultarray = $api2->GetSteamAPI("GetSchemaForGame");

					//var_dump($resultarray['game']);
					if(isset($resultarray['game']['availableGameStats'])) {
						$acharray=regroupArray($resultarray['game']['availableGameStats']['achievements'],"name");
					}
					//var_dump($acharray);
					
					$userstatsarray = $api2->GetSteamAPI("GetPlayerAchievements");
					if(isset($userstatsarray['playerstats']['achievements'])){
						$debug = "Last Record Time: " . $LastGameRecord['Timestamp']."<br>";
						$debug .= "Current Record Time: ". $usedate." ".$usetime . "<br>";
						$debug .= "<table><tr><th>apiname</th><th>achieved</th><th>unlocktime</th><th>afterlast</th><th>beforecurrent</th></tr>";
						foreach ($userstatsarray['playerstats']['achievements'] as $achievement2){
							//Count achievements earned
							if($achievement2['achieved']==1){
								//echo "<p>"; var_dump($achievement2);
								$debug .= "<tr><td>".$acharray[$achievement2['apiname']][0]['displayName']."</td>";
								$debug .= "<td>".$achievement2['achieved']."</td>";
								$debug .= "<td>".($achievement2['unlocktime']>0 ? date("Y-m-d h:m:s",$achievement2['unlocktime']):0)."</td>";
								$debug .= "<td>";
								if($achievement2['unlocktime'] > strtotime($LastGameRecord['Timestamp'])){ $debug .= "TRUE";}
								$debug .= "</td>";
								$debug .= "<td>";
								if($achievement2['unlocktime'] < strtotime($usedate." ".$usetime)){ $debug .= "TRUE";}
								$debug .= "</td>";
								$debug .= "</tr>";
								
								//echo "<p>". strtotime($LastGameRecord['Timestamp']) ." < " . $achievement2['unlocktime'] . " AND " . strtotime($LastGameRecord['Timestamp']) ." >= ". strtotime($usedate." ".$usetime);
								if(strtotime($LastGameRecord['Timestamp']) < $achievement2['unlocktime'] AND $achievement2['unlocktime'] <= strtotime($usedate." ".$usetime)){
									//DONE: get names of achievements earned and add them to the notes field. (single record)
									//DONE: get names of achievements earned and add them to the notes field. (past record edit)
									
									//var_dump($achievement2); echo "<br>";
									//echo $thisgamedata['SteamID']. " " . $thisgamedata['Title'] . ": " . $LastGameRecord['Timestamp'] . " >?< " . date("Y-m-d h:m:s",$achievement2['unlocktime']);
									//echo " - " .$achievement2['apiname'];
									//echo " - " . $acharray[$achievement2['apiname']][0]['displayName'];
									//var_dump($acharray[$achievement2['apiname']]);
									//echo "<br>";
									
									$notes .=$acharray[$achievement2['apiname']][0]['displayName']."\r\n";
								}
								
								$achearned++;
							}
							//echo $achearned."<br>";
						}
						$debug .= "</table>";
						
					}
				}
				if(isset($HistoryRecord) && $HistoryRecord['Notes']=="") {
					$HistoryRecord['Notes'] = $notes;
				}
			}
		?><td colspan=4><textarea align=top rows=3 cols=50 name="datarow[1][notes]"><?php
		echo (isset($HistoryRecord) ? $HistoryRecord['Notes'] : (isset($notes) ? $notes : ""));
		?></textarea></td>
		<td>Any notes about activity at playtime. Broken issues, Achievements earned, etc.</td>
		<td><?php
			if(isset($notes) and $notes<>"") {
				echo "Achievements Earned: <br>" . nl2br($notes);
			}
		?></td>
	</tr>
	<tr>
		<th>Source</th>
		<td colspan=4><input type="text" name="datarow[1][source]" id="source" value="<?php echo (isset($HistoryRecord) ? $HistoryRecord['RowType'] : "Game Library 6"); ?>"></td>
		<td>Where the data for this record is comping from</td>
		<td></td>
	</tr>
	<tr>
		<th>Achievements</th>
		<td colspan=4><input type="number" name="datarow[1][achievements]" min="0" id="achievements" value="<?php echo (isset($HistoryRecord) ? $HistoryRecord['Achievements'] : $achearned); ?>"></td>
		<td>Total achievements gained</td>
		<td></td>
	</tr>
	<tr>
		<th>Status *</th>
		<td colspan=4><select name='datarow[1][status]'>
		<?php
			$sql="SELECT `Status` FROM `gl_status` 
			order by `Active` DESC, `Count` DESC";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						if($defaultStatus==$row['Status']) {$selected=" SELECTED "; } else {$selected="";}
						echo "<option value='".$row['Status']."'".$selected.">".$row['Status']."</option>";
					}
				}
			} else {
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
			}		
		?>
		</select></td>
		<td>The status of the selected game</td>
		<td></td>
	</tr>
	<tr>
		<th>Review *</th>
		<td colspan=4><select name='datarow[1][review]'>
		<?php
			echo "<option value=''> </option>";
			foreach($reviewValues as $review){
				if($defaultReview==$review) {$selected=" SELECTED "; } else {$selected="";}
				echo "<option value='".$review."'".$selected.">".$review."</option>";
			}
		?>
		</select></td>
		<td><ol><li>Hated it</li><li>Did not like it</li><li>liked it</li><li>Loved it</li></ol></td>
		<td></td>
	</tr>
	<tr>
		<th rowspan=4>Keywords</th>
		<th>Base Game</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][basegame]" <?php
		if(isset($HistoryRecord['BaseGame']) && $HistoryRecord['BaseGame']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<th>Minutes</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][minutes]" <?php
		if((isset($HistoryRecord['kwMinutes']) && $HistoryRecord['kwMinutes']==1) OR (isset($ptForever) AND $ptForever>0 AND !isset($HistoryRecord))) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<td rowspan=4>Keywords which modify how this record is calculated</td>
		<td></td>
	</tr>

	<tr>
		<th>Idle</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][idle]" <?php
		if(isset($HistoryRecord['kwIdle']) && $HistoryRecord['kwIdle']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<th>Card Farming</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][cardfarming]" <?php
		if(isset($HistoryRecord['kwCardFarming']) && $HistoryRecord['kwCardFarming']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<td></td>
	</tr>

	<tr>
		<th>Beat Game</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][beatgame]" <?php
		if(isset($HistoryRecord['kwBeatGame']) && $HistoryRecord['kwBeatGame']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<th>Share</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][share]" <?php
		if(isset($HistoryRecord['kwShare']) && $HistoryRecord['kwShare']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<td></td>
	</tr>

	<tr>
		<th>Cheating</th>
		<td><label class="switch"><input type="checkbox" name="datarow[1][cheating]" <?php
		if(isset($HistoryRecord['kwCheating']) && $HistoryRecord['kwCheating']==1) {echo " CHECKED ";}
		?> ><span class="slider round"></span></label></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

	<tr><th colspan=7><input type="submit" name='Submit' value="<?php echo (isset($HistoryRecord) ? "Update" : "Save"); ?>"></th></tr>
	<tr><th colspan=7>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
</table>
</form>
<?php 
} //end if(else) mode=steam


}
	
	public function buildHtmlBody1(){
		$conn=get_db_connection();

		//Hard Coded Default Values
		$usedate=date("Y-m-d");
		$usetime=date("H:i:s");
		$defaultSystem="Steam";
		$defaultData="Add Time";
		$defaultStatus="Inactive";
		$defaultReview=1;
		$gameTitle="";
		$ptForever="";
		$achearned=0;
		$reviewValues=array(1,2,3,4);

		$dataobject= new dataAccess();
		$maxID=$dataobject->getMaxHistoryId();
		
		if (isset($_POST['datarow'])){
			//TODO: cleanse post data
			$this->captureInsert($_POST['datarow'],$_POST['timestamp']);
		}
		
		//determines if a game is active or not.
		//If a game is active, overrides chosen input and forces a second entry of active game.
		$GameStarted=isGameStarted();
		
		echo "Game Started: ".booltext($GameStarted);
		
		/* Features
		Default action: DONE
			Single blank input form
			Submit causes input
			
		Start/Stop in progress: DONE? -Untested
			Input form will only accept a Stop option.
			Submit causes input

		HistID provided: 
			Specific historical record is pre-loaded for edit. -DONE
			Submit results in update query. -DONE

		GameID provided: DONE? -Untested
			Previous values pre-filled for that game.
			Submit causes input

		SteamAPI Recent Games: -DONE? Untested
			List recent games via Steam API
			Compare values to history record
			Submit form with multiple rows for input
		*/

		$this->body .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<br/>
		<span>   <a href="' . $_SERVER['PHP_SELF'] . '">Manual Entry</a></span>
		<span> | <a href="' . $_SERVER['PHP_SELF'] . '?mode=steam">Steam API</a></span>
		<span class="hidden"> | <a href="">Local Time Log</a></span>
		<span class="hidden"> | <a href="">Game Time Tracker DB</a></span>
		<span class="hidden"> | <a href="">Game Time Tracker Export</a></span>';
		
		if(isset($_GET['mode']) && $_GET['mode']=="steam") {
			$this->body .= steamMode();
		}
		
		return $this->body;
	}
	
	public function steamMode(){
		$hitory=getHistoryCalculations("",$conn);
		$games=getCalculations("",$conn);
		$steamindex=makeIndex($games,"SteamID");
		
		foreach($hitory as $historyrow){
			if($historyrow['System']=="Steam"){
				$lastrecord[$historyrow['GameID']]=$historyrow;
				if ($historyrow['BaseGame']==1){
					$lastrecord[$historyrow['ParentGameID']]=$historyrow;
				}
			}
		}
		
		$htmloutput="<table>
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
			<tbody>";
			
			$steamAPI= new SteamAPI();
			$resultarray=$steamAPI->GetSteamAPI("GetRecentlyPlayedGames");
			
			$missing_count=0;
			$updatelist=array();
			
			foreach($resultarray['response']['games'] as $row){
				$rowclass="unknown";
				if(isset($steamindex[$row['appid']])){
					$thisgamedata=$games[$steamindex[$row['appid']]];
				
					if(isset($lastrecord[$thisgamedata['Game_ID']]['Time']) && round($lastrecord[$thisgamedata['Game_ID']]['Time']*60)==round($row['playtime_forever'])) {
						$rowclass="greenRow";
					} else {
						$rowclass="redRow";
						$updatelist[]=array("GameID"=>$thisgamedata['Game_ID'], "Time"=>$row['playtime_forever']);
					}
				} else {
					$rowclass="redRow";
				}
				
				$htmloutput .= "<tr class='".$rowclass."'>";
				
				if(isset($steamindex[$row['appid']])){
					$htmloutput .="<td class='Text'>
						<a href='addhistory.php?GameID=" . $thisgamedata['Game_ID'] . "' target='_blank'>+</a>
						<a href='viewgame.php?id=" . $thisgamedata['Game_ID'] . "' target='_blank'>" . $thisgamedata['Title'] . "</a>
						<span style='font-size: 70%;'>(<a href='http://store.steampowered.com/app/" . $row['appid'] ."' target='_blank'>Store</a>)</span>
					</td>";
				} else {
					$resultarray2=$steamAPI->GetSteamAPI("GetUserStatsForGame");
					$gamename="";
					if(isset($resultarray2['playerstats']['gameName'])){
						$gamename=$resultarray2['playerstats']['gameName'];
					}
					
					$htmloutput .= "<td class='Text'>&nbsp;&nbsp;&nbsp;MISSING: <a href='http://store.steampowered.com/app/" . $row['appid'] . "' target='_blank'>" . $gamename . "</a></td>";
					$missing_count++;
					$missing_ids[]=$row['appid'];
					unset($thisgamedata);
				}
				$htmloutput .= "<td class='numeric'>" . $row['playtime_forever'] . "</td>
				<td class='numeric'>" . round($row['playtime_forever']/60,1) . "</td>";
				if(isset($row['playtime_2weeks'])){
					$htmloutput .= "<td class='numeric'>" . $row['playtime_2weeks'] . "</td>
					<td class='numeric'>" . round($row['playtime_2weeks']/60,1) . "</td>";
				} else {
					$htmloutput .= "<td>&nbsp;</td>
					<td>&nbsp;</td>";
				}
				if (isset($thisgamedata)){
					$htmloutput .= "<td class='numeric'>" . timeduration($thisgamedata['GrandTotal'],"seconds") . "</td>";
					if (isset($lastrecord[$thisgamedata['Game_ID']])) {
						$htmloutput .= "<td class='numeric'>" . ($lastrecord[$thisgamedata['Game_ID']]['Time']*60) . "</td>
						<td class='numeric'>" . round($lastrecord[$thisgamedata['Game_ID']]['Time'],1) . "</td>
						<td class='numeric'>" . timeduration($lastrecord[$thisgamedata['Game_ID']]['Time'],"hours") . "</td>
						<td class='Text'>" . $lastrecord[$thisgamedata['Game_ID']]['KeyWords'] . "</td>";
					} else {
						$htmloutput .= "<td>&nbsp;</td>";
						$htmloutput .= "<td>&nbsp;</td>";
						$htmloutput .= "<td>&nbsp;</td>";
						$htmloutput .= "<td>&nbsp;</td>";
					}
				} else {
					$htmloutput .= "<td>&nbsp;</td>";
					$htmloutput .= "<td>&nbsp;</td>";
					$htmloutput .= "<td>&nbsp;</td>";
					$htmloutput .= "<td>&nbsp;</td>";
					$htmloutput .= "<td>&nbsp;</td>";
				}
				
				unset($thisgamedata);
				$htmloutput .= "</tr>";
			}
		unset($resultarray);
		$htmloutput .= "</tbody>";
		$htmloutput .= "</table>";
		
		if (count($updatelist)>0){
			$htmloutput .= updatelist($updatelist);
		} else {
			if (isset($_GET['HistID']) && $GameStarted == False) {
				//If a game is not started and the HistID is set, load the time from the database instead.
				$sql="SELECT * FROM `gl_history` join `gl_products` on `gl_history`.`GameID` = `gl_products`.`Game_ID` WHERE `HistoryID`=".$_GET['HistID'];
				if($result = $conn->query($sql)){
					if ($result->num_rows > 0){
						$HistoryRecord = $result->fetch_assoc();
						$_GET['GameID']=$HistoryRecord['GameID'];
						$usedate=date("Y-m-d",strtotime($HistoryRecord['Timestamp']));
						$usetime=date("H:i:s",strtotime($HistoryRecord['Timestamp']));
					}
				}
			}
		}
		
		return $htmloutput;
	}
	
	public function UpdateList($updatelist){
		$htmloutput = "<hr>";
		$htmloutput .= '<form action="' . $_SERVER['PHP_SELF'] . '?mode=steam" method="post">';
		$htmloutput .= "<table class='ui-widget'>
		<thead>
			<tr>
				<th>Update</th>
				<th>Game ID</th>
				<th>Title</th>
				<th>Time</th>
				<th>Notes</th>
				<th>Achievements</th>
				<th>Status</th>
				<th>Review</th>
				<th>Base Game</th>
				<th>Minutes</th>
				<th>Idle</th>
				<th>Card Farming</th>
				<th>Beat Game</th>
				<th class='hidden'>Share</th>
				<th>Cheating</th>
			</tr>
		</thead>
		<tbody>";

		$counter=0;
		foreach($updatelist as $record){
			$counter++;
			$notes="";
			$thisgamedata=$games[$gameIndex[$record['GameID']]];
			
			$sql="SELECT `Title`, `gl_history`.*, `SteamID` 
				FROM `gl_products` 
				left join `gl_history` on `gl_history`.`GameID` = `gl_products`.`Game_ID` 
				WHERE `Game_ID`=".$record['GameID']." 
				ORDER by `Timestamp` desc;";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					$LastGameRecord = $result->fetch_assoc();
					while(($LastGameRecord['Status']=="" OR $LastGameRecord['Review']=="") AND $row = $result->fetch_assoc()) {
						if($row['Status']<>"" AND $LastGameRecord['Status']==""){
							$LastGameRecord['Status']=$row['Status'];
						}
						
						if($row['Review']<>"" AND $LastGameRecord['Review']==""){
							$LastGameRecord['Review']=$row['Review'];
						}
					}
				}
			}

			if(isset($thisgamedata['SteamID']) && $thisgamedata['SteamID']>0) {
				$achearned=0;
				$steamAPI= new SteamAPI($thisgamedata['SteamID']);
				$resultarray=$steamAPI->GetSteamAPI("GetSchemaForGame");
				if(isset($resultarray['game']['availableGameStats']['achievements'])) {
					$acharray=regroupArray($resultarray['game']['availableGameStats']['achievements'],"name");
				}

				$userstatsarray=$steamAPI->GetSteamAPI("GetPlayerAchievements");
				if(isset($userstatsarray['playerstats']['achievements'])){
					foreach ($userstatsarray['playerstats']['achievements'] as $achievement2){
						//Count achievements earned
						if($achievement2['achieved']==1){
							
							if(strtotime($LastGameRecord['Timestamp']) < $achievement2['unlocktime']){
								$notes .=$acharray[$achievement2['apiname']][0]['displayName']."\r\n";
							}
							$achearned++;
						}
					}
				}
			}
			
			$htmloutput .= "<tr>";
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][update]" CHECKED><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><input type="number"   name="datarow[' . $counter . '][ProductID]" value="' . $record['GameID'] . '" min="0" max="99999"></td>';
			$htmloutput .= "<td><a href='viewgame.php?id=" . $record['GameID'] . "' target='_blank'>" . $thisgamedata['Title'] . "</a></td>";
				$htmloutput .= '<input type="hidden"   name="datarow[' .  $counter . '][Title]"  value="' . htmlspecialchars($thisgamedata['Title']) . '" >';
				$htmloutput .= '<input type="hidden"   name="datarow[' .  $counter . '][System]" value="Steam" >';
				$htmloutput .= '<input type="hidden"   name="datarow[' .  $counter . '][Data]"   value="New Total" >';
				$htmloutput .= '<input type="hidden"   name="datarow[' .  $counter . '][source]" value="Game Library 6" >';
			$htmloutput .= '<td><input type="number"   name="datarow[' .  $counter . '][hours]"  value="' . $record['Time'] . '" min="0" max="999999"></td>';
			$htmloutput .= '<td><textarea align=top rows=2 cols=18 name="datarow[' .  $counter . '][notes]">' . trim($notes) . '</textarea></td>';
			$htmloutput .= '<td><input type="number"   name="datarow[' .  $counter . '][achievements]" value="' . $achearned . '"  min="0" max="9999"></td>';

			$defaultStatus=$LastGameRecord['Status'];
			if($defaultStatus==""){
				$defaultStatus="Inactive";
			}
			$defaultReview=$LastGameRecord['Review'];
			
			$htmloutput .= '<td><select name="datarow['.$counter.'][status]">';
			$sql="SELECT `Status` FROM `gl_status` order by `Active` DESC, `Count` DESC";
			if($result = $conn->query($sql)){
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						if($defaultStatus==$row['Status']) {
							$selected=" SELECTED "; 
						} else {
							$selected="";
						}
						$htmloutput .= "<option value='".$row['Status']."'".$selected.">".$row['Status']."</option>";
					}
				}
			} else {
				trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
			}
			
			$htmloutput .= "</select></td>";
			$htmloutput .= "<td><Select name=\"datarow[".$counter."][review]\">";
			$htmloutput .= "<option value=''> </option>";
			foreach($reviewValues as $review){
				if($defaultReview==$review) {$selected=" SELECTED "; } else {$selected="";}
				$htmloutput .= "<option value='".$review."'".$selected.">".$review."</option>";
			}			
			$htmloutput .= "</select></td>";

			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][basegame]"       ><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][minutes]" CHECKED><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][idle]"           ><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][cardfarming]"    ><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][beatgame]"       ><span class="slider round"></span></label></td>';
			$htmloutput .= '<td class="hidden"><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][share]"          ><span class="slider round"></span></label></td>';
			$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][cheating]"       ><span class="slider round"></span></label></td>';
			$htmloutput .= '</tr>';

			unset($thisgamedata);
		}

		$htmloutput .= '<tr><td colspan=15>';
		$htmloutput .= '<input type="datetime-local" name="timestamp" id="timestamp" value=' . "'".$usedate."T".$usetime."'" . '>';
		
		$htmloutput .= '<label class="switch"><input type="checkbox" name="currenttime" checked><span class="slider round"></span></label>';
		$htmloutput .= 'Ignore and use current time';
		$htmloutput .= '</td></tr>';
		
		$htmloutput .= '<tr><td colspan=15>';
		$htmloutput .= '<input type="submit" name="Submit" value="Submit"><p>';
		$htmloutput .= '</td></tr>';
		
		$htmloutput .= '</tbody></table>';
		
		return $htmloutput;
	}
	
	public function captureInsert($datarow,$timestamp){
		if(isset($datarow[1]['ProductID'])) {
			$_GET['GameID']=$datarow[1]['ProductID']; 
		} else {
			$_GET['HistID']=$maxID;
		}
		/*	
		Post array:	array(1) {
		  [1]=>	  array(13) {
			["update"]=>		string(2) "on"
			["id"]=>		string(4) "9406"
			["ProductID"]=>		string(4) "3407"
			["Title"]=>		string(14) "SOULCALIBUR VI"
			["System"]=>		string(5) "Steam"
			["Data"]=>		string(9) "New Total"
			["hours"]=>		string(2) "82"
			["notes"]=>		string(0) ""
			["source"]=>		string(14) "Game Library 5"
			["achievements"]=>		string(1) "0"
			["status"]=>		string(8) "Inactive"
			["review"]=>		string(1) "1"
			["minutes"]=>		string(2) "on"
		  }
		}
		*/

		$sql2="";
		if(isset($datarow[1]['id'])) {
			$this->dataAccessObject->updateHistory($datarow[1],$timestamp);
		} else {
			$this->dataAccessObject->insertHistory($datarow,$timestamp,$maxID);
		}
	}
}
