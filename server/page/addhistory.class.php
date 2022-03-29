<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)

class activityPage extends Page
{

	public function __construct() {
		$this->title="Add History";
	}
	
	public function buildHtmlBody(){
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
			captureInsert($_POST['datarow'],$_POST['timestamp']);
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
		
		
		return $htmloutput;
	}
	
	public function UpdateList(){
		
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
			updateHistory($datarow[1],$timestamp);
		} else {
			insertHistory($datarow,$timestamp);
		}
	}
}
