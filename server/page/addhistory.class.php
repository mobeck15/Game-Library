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
	
	public functino steamMode(){
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
			updateHistory($datarow[1],$timestamp);
		} else {
			insertHistory($datarow,$timestamp);
		}
	}
}
