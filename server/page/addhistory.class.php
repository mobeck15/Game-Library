<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.class.php";

//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)

class addhistoryPage extends Page
{
	private $maxID;
	private $history;
	private $games;
	private $steamindex;
	private $gameIndex;
	
	private $steamAPI;
	private $steamScraper;

	private $reviewValues=array(array(1,"1 - Hated it"), array(2,"2 - Did not like it"), array(3,"3 - Liked it"), array(4,"4 - Loved it"));
	private $usedate;
	private $usetime;
	
	public function __construct() {
		$this->title="Add History";
		$this->usedate=date("Y-m-d");
		$this->usetime=date("H:i:s");
	}
	
	private function getSteamAPI(){
		if(!isset($this->steamAPI)){
			$this->steamAPI=new SteamAPI();
		}
		return $this->steamAPI;
	}
	
	public function buildHtmlBody(){
		$output="";
		//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)
		$this->maxID=$this->getDataAccessObject()->getMaxHistoryId();
		if (isset($_POST['datarow'])){
			//TODO: cleanse post data
			$this->captureInsert($_POST['datarow'],$_POST['timestamp']);
		}
		
		//determines if a game is active or not.
		//If a game is active, overrides chosen input and forces a second entry of active game.
		$GameStarted=$this->getDataAccessObject()->isGameStarted($this->getDataAccessObject()->getLatestHistory());

		$output .= "Game Started: ".booltext($GameStarted);

		$output .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		
		<br/>
		<span>   <a href="'. $_SERVER['PHP_SELF'].'">Manual Entry</a></span>
		<span> | <a href="'. $_SERVER['PHP_SELF'].'?mode=steam">Steam API</a></span>
		<span class="hidden"> | <a href="">Local Time Log</a></span>
		<span class="hidden"> | <a href="">Game Time Tracker DB</a></span>
		<span class="hidden"> | <a href="">Game Time Tracker Export</a></span>';

		if(isset($_GET['mode']) && $_GET['mode']=="steam") {
			$output .= $this->steamMode();
		} else {
			$output .= $this->manualMode($GameStarted);
		}

		return $output;

	}
	
	private function historyNotes($thisgamedata,$LastGameRecord){
		$notes="";
		if(isset($thisgamedata['SteamID']) && $thisgamedata['SteamID']>0) {
			$this->getSteamAPI()->setSteamGameID($thisgamedata['SteamID']);
			$resultarray = $this->getSteamAPI()->GetSteamAPI("GetSchemaForGame");

			if(isset($resultarray['game']['availableGameStats'])) {
				$acharray=regroupArray($resultarray['game']['availableGameStats']['achievements'],"name");
			}
			
			$userstatsarray = $this->getSteamAPI()->GetSteamAPI("GetPlayerAchievements");
			if(isset($userstatsarray['playerstats']['achievements'])){
				foreach ($userstatsarray['playerstats']['achievements'] as $achievement2){
					//Count achievements earned
					if($achievement2['achieved']==1){
						if(strtotime($LastGameRecord['Timestamp'] ?? "") < $achievement2['unlocktime'] AND $achievement2['unlocktime'] <= strtotime($this->usedate." ".$this->usetime)){
							$notes .=$acharray[$achievement2['apiname']][0]['displayName']."\r\n";
						}
					}
				}
			}
		}
		return $notes;
	}
	
	public function manualMode($GameStarted){
		//Hard Coded Default Values
		$defaultSystem="Steam";
		$defaultData="Add Time";
		$defaultStatus="Inactive";
		$defaultReview=1;
		$gameTitle="";
		$ptForever="";
		$achearned=0;

		if (isset($_GET['HistID']) && $GameStarted == False) {
			$HistoryRecord=$this->getDataAccessObject()->getHistoryRecrod($_GET['HistID']);
			//TODO: catch errors when a bad HistID is provided
			$_GET['GameID']=$HistoryRecord['GameID'];
			$this->usedate=date("Y-m-d",strtotime($HistoryRecord['Timestamp']));
			$this->usetime=date("H:i:s",strtotime($HistoryRecord['Timestamp']));
		}
		
		if (isset($_GET['GameID']) OR isset($_GET['HistID'])) {
			$this->gameIndex=makeIndex($this->getGames(),"Game_ID");
			$thisgamedata=$this->getGames()[$this->gameIndex[$_GET['GameID']]];
			$LastGameRecord=$this->dataAccessObject->getHistoryRecord($_GET['GameID']);
			//TODO: update so when editing old records the achievements are still listed.
			$notes=$this->historyNotes($thisgamedata,$LastGameRecord);
			
			if(isset($HistoryRecord) && $HistoryRecord['Notes']=="") {
				$HistoryRecord['Notes'] = $notes;
			}
		}
		
		if (isset($_GET['GameID'])) {
			if(isset($HistoryRecord)){
				$LastGameRecord=$HistoryRecord;
				$LastGameRecord['Title']=$HistoryRecord['Game'];
			} else {
				$LastGameRecord=$this->dataAccessObject->getHistoryRecord($_GET['GameID']);
				if(!isset($LastGameRecord['GameID'])){
					$LastGameRecord['Title']=$this->dataAccessObject->getProductTitle($_GET['GameID']);
					$LastGameRecord['System']="Steam";
					$LastGameRecord['Data']="New Total";
					$LastGameRecord['Status']="";
					$LastGameRecord['Review']="";
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
				$this->getSteamAPI()->setSteamGameID($LastGameRecord['SteamID']);
				$result = $this->getSteamAPI()->GetSteamAPI("GetSchemaForGame");

				$achearned=$this->countAchievements($this->getSteamAPI()->GetSteamAPI("GetUserStatsForGame"));
				
				$resultarray3 = $this->getSteamAPI()->GetSteamAPI("GetOwnedGames");
				
				foreach($resultarray3['response']['games'] as $row){
					if($row['appid']==$LastGameRecord['SteamID']){
						$ptForever=$row['playtime_forever'];
					}
				}
			}
		}
		
		$formdata['HistoryID']=$HistoryRecord['HistoryID'] ?? "";;
		$formdata['gametitle']=isset($gameTitle) ? $gameTitle : "";
		$formdata['productid']=isset($_GET['GameID']) ? $_GET['GameID'] : "";
		$formdata['timestamptoggle']=!isset($HistoryRecord) ? '<br>Ignore and use current time: <label class="switch"><input type="checkbox" name="currenttime" checked><span class="slider round"></span></label></td>' : "";
		$formdata['timestamp']=$this->usedate."T".$this->usetime;
		$formdata['defaultsystem']=$defaultSystem;
		$formdata['defaultdatatype']=$defaultData;
		$formdata['duration']=isset($HistoryRecord) ? (float)$HistoryRecord['Time'] : $ptForever;
		$formdata['notes']=isset($HistoryRecord) ? $HistoryRecord['Notes'] : (isset($notes) ? $notes : "");
		$formdata['achnotes']=(isset($notes) and $notes<>"") ? ("Achievements Earned: <br>" . nl2br($notes)) : "";
		$formdata['Source']=isset($HistoryRecord) ? $HistoryRecord['RowType'] : "Game Library 6";
		$formdata['Achievements']=isset($HistoryRecord) ? $HistoryRecord['Achievements'] : $achearned;
		$formdata['defaultStatus']=$defaultStatus;
		$formdata['defaultReview']=$defaultReview;
		$formdata['BaseGame']=(isset($HistoryRecord['BaseGame']) && $HistoryRecord['BaseGame']==1) ? " CHECKED " : "";
		$formdata['kwMinutes']=((isset($HistoryRecord['kwMinutes']) && $HistoryRecord['kwMinutes']==1) OR (isset($ptForever) AND $ptForever>0 AND !isset($HistoryRecord))) ? " CHECKED " : "";
		$formdata['kwIdle']=(isset($HistoryRecord['kwIdle']) && $HistoryRecord['kwIdle']==1) ? " CHECKED " : "";
		$formdata['kwCardFarming']=(isset($HistoryRecord['kwCardFarming']) && $HistoryRecord['kwCardFarming']==1) ? " CHECKED " : "";
		$formdata['kwBeatGame']=(isset($HistoryRecord['kwBeatGame']) && $HistoryRecord['kwBeatGame']==1) ? " CHECKED " : "";
		$formdata['kwShare']=(isset($HistoryRecord['kwShare']) && $HistoryRecord['kwShare']==1) ? " CHECKED " : "";
		$formdata['kwCheating']=(isset($HistoryRecord['kwCheating']) && $HistoryRecord['kwCheating']==1) ? " CHECKED " : "";
		$formdata['buttonvalue']=isset($HistoryRecord) ? "Update" : "Save";
		
		return $this->renderHTMLform($formdata);
	}
	
	private function countAchievements($resultarray2){
		$achearned=0;
		if(isset($resultarray2['playerstats']['achievements'])){
			foreach ($resultarray2['playerstats']['achievements'] as $achievement2){
				//Count achievements earned
				$achearned++;
			}
		}
		return $achearned;
	}
	
	private function renderHTMLform($formdata){
		$htmlform="";
		
		//$htmlform .= '<form action="'. $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'].'" method="post">';
		$htmlform .= '<form method="post">';
		$htmlform .= '<input type="hidden" name="datarow[1][update]" value="on" >';
		
		$htmlform .= '<script>
		  $(function() {
				$("#Product").autocomplete({ 
					source: "./ajax/search.ajax.php",
					select: function (event, ui) { 
						$("#ProductID").val(ui.item.id);
					} }
				);
			} );
		</script>';

		$htmlform .= '<table class="ui-widget">';
		$htmlform .= '<thead>';
		$htmlform .= '<tr><th colspan=7>New History Record</th></tr>';

		$htmlform .= '<tr>';
		$htmlform .= '<th>Field</th>';
		$htmlform .= '<th colspan=4>Value</th>';
		$htmlform .= '<th>Description</th>';
		$htmlform .= '<th>Lookup Prompt</th>';
		$htmlform .= '</tr>';
		$htmlform .= '</thead>';
		
		if($formdata['HistoryID']<>""){
			$htmlform .= $this->renderFormRow("History ID",
				'<input type="hidden" name="datarow[1][id]" value="'. $formdata['HistoryID'] .'"> '. $formdata['HistoryID'],
				"The ID of this history record.");
		}
		
		$htmlform .= $this->renderFormRow("Product ID",
			'<input type="number" name="datarow[1][ProductID]" min="0" class="auto" id="ProductID" value="'. $formdata['productid'] .'">',
			"The product this item is linked to. (GameID)",
			'(?)<input id="Product" name="datarow[1][Title]" onchange="setNotes()" size=30 value="'. $formdata['gametitle'] .'"> <input type="button" value="New">');

		$htmlform .= $this->renderFormRow("Timestamp *",
			'<input type="datetime-local" name="timestamp" id="timestamp" value='. "'".$formdata['timestamp']."'".'>' . $formdata['timestamptoggle'],
			"The Date and Time the history record will be recorded at");

		$htmlform .= $this->renderFormRow("System *",
			$this->renderDropDown('System',$formdata['defaultsystem'],$this->dataAccessObject->getSystemList()),
			"The system on which the game was played");

		$htmlform .= $this->renderFormRow("Data Type *",
			$this->renderDropDown('Data',$formdata['defaultdatatype'],$this->dataAccessObject->getHistoryDataTypes()),
			"The type of record that will be added.");

		$htmlform .= $this->renderFormRow("Duration",
			'<input type="number" name="datarow[1][hours]" min="0" id="hours" step="0.00001" value="'. $formdata['duration'] .'">',
			"How long playtime in Hours (or Minutes)");

		$htmlform .= $this->renderFormRow("Notes",
			'<textarea align=top rows=3 cols=50 name="datarow[1][notes]">'. $formdata['notes'] .'</textarea>',
			"Any notes about activity at playtime. Broken issues, Achievements earned, etc.",
			$formdata['achnotes']);
		
		$htmlform .= $this->renderFormRow("Source",
			'<input type="text" name="datarow[1][source]" id="source" value="'. $formdata['Source'] .'">',
			"Where the data for this record is coming from");
		
		$htmlform .= $this->renderFormRow("Achievements",
			'<input type="number" name="datarow[1][achievements]" min="0" id="achievements" value="'. $formdata['Achievements'] .'">',
			"Total achievements gained");

		$htmlform .= $this->renderFormRow("Status *",
			$this->renderDropDown('status',$formdata['defaultStatus'],$this->dataAccessObject->getStatusList()),
			"The status of the selected game");
		
		$htmlform .= $this->renderFormRow("Review *",
			$this->renderDropDown('review',$formdata['defaultReview'],$this->reviewValues),
			"<ol><li>Hated it</li><li>Did not like it</li><li>Liked it</li><li>Loved it</li></ol>");
			
		$htmlform .= '<tr>';
		$htmlform .= '<th rowspan=4>Keywords</th>';
		$htmlform .= $this->renderToggleCell("Base Game","basegame",$formdata['BaseGame']);
		$htmlform .= $this->renderToggleCell("Minutes","minutes",$formdata['kwMinutes']);
		$htmlform .= '<td></td>';
		$htmlform .= '</tr>';

		$htmlform .= '<tr>';
		$htmlform .= $this->renderToggleCell("Idle","idle",$formdata['kwIdle']);
		$htmlform .= $this->renderToggleCell("Card Farming","cardfarming",$formdata['kwCardFarming']);
		$htmlform .= '<td></td>';
		$htmlform .= '</tr>';

		$htmlform .= '<tr>';
		$htmlform .= $this->renderToggleCell("Beat Game","beatgame",$formdata['kwBeatGame']);
		$htmlform .= $this->renderToggleCell("Share","share",$formdata['kwShare']);
		$htmlform .= '<td></td>';
		$htmlform .= '</tr>';

		$htmlform .= '<tr>';
		$htmlform .= $this->renderToggleCell("Cheating","cheating",$formdata['kwCheating']);
		$htmlform .= '<td></td><td></td>';
		$htmlform .= '<td></td>';
		$htmlform .= '</tr>';
		
		$htmlform .= '<tr><th colspan=7><input type="submit" name="Submit" value="'. $formdata['buttonvalue'] .'"></th></tr>';
		$htmlform .= '<tr><th colspan=7>* = Required Field<br>(?) = Lookup Prompt available</th></tr>';
		$htmlform .= '</table>';
		$htmlform .= '</form>';
		
		return $htmlform;
	}
	
	private function renderFormRow($label,$inputs,$description,$notes=""){
		$htmlform  = '<tr>';
		$htmlform .= "<th>$label</th>";
		$htmlform .= "<td colspan=4>$inputs</td>";
		$htmlform .= "<td>$description</td>";
		$htmlform .= "<td>$notes</td>";
		$htmlform .= '</tr>';
		
		return $htmlform;
	}
	
	private function renderToggleCell($label,$index,$checked){
		$htmlform  = "<th>$label</th>";
		$htmlform .= "<td><label class='switch'><input type='checkbox' name='datarow[1][$index]'";
		$htmlform .= $checked;
		$htmlform .= '><span class="slider round"></span></label></td>';
		
		return $htmlform;
	}

	private function renderDropDown($label,$default,$list,$counter=1){
		$htmloutput = "<select name='datarow[$counter][$label]'>";
		foreach($list as $row){
			$selected = $default==$row[array_key_first($row)] ? " SELECTED " : "";
			$htmloutput .= "<option value='".$row[array_key_first($row)]."'".$selected.">".$row[array_key_last($row)]."</option>";
		}
		$htmloutput .= "</select>";
		
		return $htmloutput;
	}
	
	private function getGames(){
		if(!isset($this->games)){
			$this->games = getCalculations();
		}
		return $this->games;
		//return $this->data()->getCalculations();
	}

	private function getHistory(){
		if(!isset($this->history)){
			$this->history = getHistoryCalculations();
		}
		return $this->history;
		//return $this->data()->getHistory();
	}
	
	public function steamMode(){
		$this->steamindex=makeIndex($this->getGames(),"SteamID");
		$this->gameIndex=makeIndex($this->getGames(),"Game_ID");
		
		foreach($this->getHistory() as $historyrow){
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
		<th rowspan=2>New Duration</th>
		<th colspan=5>Last Time Entry</th>
		</tr>
		<tr>
		<th style='top:77px;'>Minutes</th>
		<th style='top:77px;'>Hours</th>
		<th style='top:77px;'>Minutes</th>
		<th style='top:77px;'>Hours</th>
		<th style='top:77px;'>Total Time</th>
		<th style='top:77px;'>Minutes</th>
		<th style='top:77px;'>Hours</th>
		<th style='top:77px;'>Duration</th>
		<th style='top:77px;'>Keywords</th>
		</tr>
		</thead>
		<tbody>";
		
		$resultarray=$this->getSteamAPI()->GetSteamAPI("GetRecentlyPlayedGames");
		$updatelist=array();
		
		foreach($resultarray['response']['games'] as $row){
			$data=$this->getSteamTableData($row,$lastrecord);
			$updatelist=$this->getUpdateList($row,$lastrecord,$updatelist);
			
			$htmloutput .= "<tr class='".$data['rowclass']."'>";
			
			$htmloutput .= "<td class='Text'>" . $data['title'] . "</td>"; //Title
			$htmloutput .= "<td class='numeric'>" . $data['playallmin'] . "</td>"; //Playtime Forever - Minutes
			$htmloutput .= "<td class='numeric'>" . $data['playallhrs'] . "</td>"; //Playtime Forever - Hours
			$htmloutput .= "<td class='numeric'>" . $data['play2wkmin'] . "</td>"; //Playtime two weeks - Minutes
			$htmloutput .= "<td class='numeric'>" . $data['play2wkhrs'] . "</td>"; //Playtime two weeks - Hours
			$htmloutput .= "<td class='numeric'>" . $data['duration'] . "</td>"; //Current Duration
			$htmloutput .= "<td class='numeric'>" . $data['totaltime'] . "</td>"; //Total Time
			$htmloutput .= "<td class='numeric'>" . $data['lastmin'] . "</td>"; //Last Entry - Minutes
			$htmloutput .= "<td class='numeric'>" . $data['lasthrs'] . "</td>"; //Last Entry - Hours
			$htmloutput .= "<td class='numeric'>" . $data['lasttime'] . "</td>"; //Last Entry - Time
			$htmloutput .= "<td class='Text'>" . $data['lastkw'] . "</td>"; //Last Entry - Time
			
			$htmloutput .= "</tr>";
		}
		
		$htmloutput .= "</tbody>";
		$htmloutput .= "</table>";
		
		if (count($updatelist)>0){
			$htmloutput .= $this->updatelist($updatelist);
		}
		
		return $htmloutput;
	}
	
	private function getSteamTableData($row,$lastrecord){
		$data['rowclass']=$this->getRowClass($row,$lastrecord);
		
		$data['totaltime']="&nbsp;";
		$data['lastmin']="&nbsp;";
		$data['lasthrs']="&nbsp;";
		$data['lasttime']="&nbsp;";
		$data['lastkw']="&nbsp;";
		$data['duration']="&nbsp;";
		$data['play2wkmin']="&nbsp;";
		$data['play2wkhrs']="&nbsp;";
		$data['playallmin']=$row['playtime_forever'];
		$data['playallhrs']=round($row['playtime_forever']/60,1);
		
		if($this->steamAppIDexists($row['appid'])){
			$data['title'] ="<a href='addhistory.php?GameID=" . $this->getGameAttribute($row['appid']) . "' target='_blank'>+</a>
			<a href='viewgame.php?id=" . $this->getGameAttribute($row['appid']) . "' target='_blank'>" . $this->getGameAttribute($row['appid'],'Title') . "</a>
			<span style='font-size: 70%;'>(<a href='http://store.steampowered.com/app/" . $row['appid'] ."' target='_blank'>Store</a>)</span>";
			$data['totaltime']=timeduration((float)$this->getGameAttribute($row['appid'],'GrandTotal'),"seconds");
			if (isset($lastrecord[$this->getGameAttribute($row['appid'])])) {
				$data['lastmin']=($lastrecord[$this->getGameAttribute($row['appid'])]['Time']*60);
				$data['lasthrs']=round((float)$lastrecord[$this->getGameAttribute($row['appid'])]['Time'],1);
				$data['lasttime']=timeduration((float)$lastrecord[$this->getGameAttribute($row['appid'])]['Time'],"hours");
				$data['lastkw']=$lastrecord[$this->getGameAttribute($row['appid'])]['KeyWords'];
				$data['duration']=timeduration($row['playtime_forever'] - ($lastrecord[$this->getGameAttribute($row['appid'])]['Time']*60),"minutes");
			}
		} else {
			$this->getSteamAPI()->setSteamGameID($row['appid']);
			$gamename=$this->getSteamAPI()->GetSteamAPI("GetSchemaForGame")['game']['gameName'] ?? "";
			$data['title'] ="&nbsp;&nbsp;&nbsp; <a href='http://store.steampowered.com/app/" . $row['appid'] . "' target='_blank'>MISSING: " . $gamename . "</a>";
		}
		if(isset($row['playtime_2weeks'])){
			$data['play2wkmin']=$row['playtime_2weeks'];
			$data['play2wkhrs']=round($row['playtime_2weeks']/60,1);
		}
		
		return $data;
	}
	
	private function getRowClass($row,$lastrecord){
		$rowclass="unknown";
		if($this->steamAppIDexists($row['appid'])
			&& isset($lastrecord[$this->getGameAttribute($row['appid'])]['Time']) 
			&& round($lastrecord[$this->getGameAttribute($row['appid'])]['Time']*60)==round($row['playtime_forever'])) {
			$rowclass="greenRow";
		} else {
			$rowclass="redRow";
		}
		
		return $rowclass;
	}
	
	private function getUpdateList($row,$lastrecord,$updatelist){
		if($this->steamAppIDexists($row['appid'])
			&& !(isset($lastrecord[$this->getGameAttribute($row['appid'])]['Time']) 
			&& round($lastrecord[$this->getGameAttribute($row['appid'])]['Time']*60)==round($row['playtime_forever']))) {
			$updatelist[]=array("GameID"=> $this->getGameAttribute($row['appid']) , "Time"=>$row['playtime_forever']);
		}
		
		return $updatelist;
	}
	
	private function steamAppIDexists($id){
		return isset($this->steamindex[$id]);
	}
	
	private function getGameAttribute($id,$value="Game_ID"){
		return $this->games[$this->steamindex[$id]][$value];
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
			$htmloutput .= $this->MakeRecord($record,$counter);
		}

		$htmloutput .= '<tr><td colspan=15>';
		$htmloutput .= '<input type="datetime-local" name="timestamp" id="timestamp" value=' . "'".$this->usedate."T".$this->usetime."'" . '>';
		
		$htmloutput .= '<label class="switch"><input type="checkbox" name="currenttime" checked><span class="slider round"></span></label>';
		$htmloutput .= 'Ignore and use current time';
		$htmloutput .= '</td></tr>';
		
		$htmloutput .= '<tr><td colspan=15>';
		$htmloutput .= '<input type="submit" name="Submit" value="Submit"><p>';
		$htmloutput .= '</td></tr>';
		
		$htmloutput .= '</tbody></table>';
		
		return $htmloutput;
	}
	
	public function MakeRecord($record,$counter){
		$notes="";
		$thisgamedata=$this->games[$this->gameIndex[$record['GameID']]];
		$LastGameRecord=$this->dataAccessObject->getHistoryRecord($record['GameID']);

		if(isset($thisgamedata['SteamID']) && $thisgamedata['SteamID']>0) {
			$achearned=0;
			$this->getSteamAPI()->setSteamGameID($thisgamedata['SteamID']);
			$resultarray=$this->getSteamAPI()->GetSteamAPI("GetSchemaForGame");
			if(isset($resultarray['game']['availableGameStats']['achievements'])) {
				$acharray=regroupArray($resultarray['game']['availableGameStats']['achievements'],"name");
			}

			$userstatsarray=$this->getSteamAPI()->GetSteamAPI("GetPlayerAchievements");
			if(isset($userstatsarray['playerstats']['achievements'])){
				foreach ($userstatsarray['playerstats']['achievements'] as $achievement2){
					//Count achievements earned
					if($achievement2['achieved']==1){
						
						if(strtotime($LastGameRecord['Timestamp']??"") < $achievement2['unlocktime']){
							$notes .=$acharray[$achievement2['apiname']][0]['displayName']."\r\n";
						}
						$achearned++;
					}
				}
			}
		}
		
		$htmloutput  = "<tr>";
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
		
		$htmloutput .= '<td>';
		$htmloutput .= $this->renderDropDown('status',$defaultStatus,$this->dataAccessObject->getStatusList(),$counter);
		$htmloutput .= "</td>";
		
		$htmloutput .= "<td>";
		$htmloutput .= $this->renderDropDown('review',$defaultReview,$this->reviewValues,$counter);
		
		$htmloutput .= "</td>";

		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][basegame]"       ><span class="slider round"></span></label></td>';
		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][minutes]" CHECKED><span class="slider round"></span></label></td>';
		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][idle]"           ><span class="slider round"></span></label></td>';
		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][cardfarming]"    ><span class="slider round"></span></label></td>';
		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][beatgame]"       ><span class="slider round"></span></label></td>';
		$htmloutput .= '<td class="hidden"><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][share]"          ><span class="slider round"></span></label></td>';
		$htmloutput .= '<td><label class="switch"><input type="checkbox" name="datarow[' .  $counter . '][cheating]"       ><span class="slider round"></span></label></td>';
		$htmloutput .= '</tr>';

		return $htmloutput;
	}
	
	public function captureInsert($datarow,$timestamp){
		if(isset($datarow[1]['ProductID'])) {
			$_GET['GameID']=$datarow[1]['ProductID']; 
		} else {
			$_GET['HistID']=$this->maxID;
		}
		/*	
		Post array:	array (1) {
		  [1]=>	  array(13) {
			["update"]=>		string(2)  "on"
			["id"]=>			string(4)  "9406"
			["ProductID"]=>		string(4)  "3407"
			["Title"]=>			string(14) "SOULCALIBUR VI"
			["System"]=>		string(5)  "Steam"
			["Data"]=>			string(9)  "New Total"
			["hours"]=>			string(2)  "82"
			["notes"]=>			string(0)  ""
			["source"]=>		string(14) "Game Library 5"
			["achievements"]=>	string(1)  "0"
			["status"]=>		string(8)  "Inactive"
			["review"]=>		string(1)  "1"
			["minutes"]=>		string(2)  "on"
		  }
		}
		*/
		
		$sql2="";
		if(isset($datarow[1]['id'])) {
			$this->dataAccessObject->updateHistory($datarow[1],$timestamp);
		} else {
			$this->dataAccessObject->insertHistory($datarow,$timestamp,$this->maxID);
		}
	}
}
