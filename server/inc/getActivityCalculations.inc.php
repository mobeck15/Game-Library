<?php
/*
 *  GL5 Version - Need to re-work for GL6
 */
 
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

function getActivityCalculations($gameID="",$historytable="",$connection=false){
	//This function is getting called twice from viewgame.php
	if($connection==false){
		require $GLOBALS['rootpath']."/inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	
	$settings=getsettings($conn);
	if($historytable=="") {
		$historytable=getHistoryCalculations($gameID,$conn);
	}
	if($connection==false){
		$conn->close();	
	}	
	
	if($historytable<>false){
		foreach ($historytable as $row) {
			//Group the history records by GameID
			$historyByGame[$row['GameID']][]=$row;
		}
		unset($historytable);
		
		//Walk through each game.
		foreach ($historyByGame as $game) {
			//Walk through each history record for each game.
			foreach ($game as $value) {
				$totals[$value['GameID']]['ID']=$value['GameID'];
				$totals[$value['GameID']]['Games']=$value['Game'];
				
				if(!isset($totals[$value['GameID']]['totalHrs'])) {$totals[$value['GameID']]['totalHrs'] =0 ;}
				if(!isset($totals[$value['GameID']]['weekPlay'])) {$totals[$value['GameID']]['weekPlay'] =0 ;}
				if(!isset($totals[$value['GameID']]['monthPlay'])){$totals[$value['GameID']]['monthPlay']=0 ;}
				if(!isset($totals[$value['GameID']]['yearPlay'])) {$totals[$value['GameID']]['yearPlay'] =0 ;}
				if(!isset($totals[$value['GameID']]['WeekAchievements'])) {$totals[$value['GameID']]['WeekAchievements'] =0 ;}
				if(!isset($totals[$value['GameID']]['MonthAchievements'])){$totals[$value['GameID']]['MonthAchievements']=0 ;}
				if(!isset($totals[$value['GameID']]['YearAchievements'])) {$totals[$value['GameID']]['YearAchievements'] =0 ;}

				if ($value['FinalCountHours']==true){
					if($value['Elapsed'] >= $settings['MinPlay'] && $value['Total'] >= $settings['MinTotal']) {
						if(!isset($totals[$value['GameID']]['firstplay'])){
							$totals[$value['GameID']]['firstplay']=$value['Timestamp'];
							$totals[$value['GameID']]['firstPlayDateTime']=new DateTime($value['Timestamp']);
						}
						
						$totals[$value['GameID']]['lastplay']=$value['Timestamp'];
						$totals[$value['GameID']]['lastPlayDateTime']=new DateTime($value['Timestamp']);
						$totals[$value['GameID']]['elapsed'] = $value['Elapsed'];
						
					}
					
					//echo "<br>totalhrs: ";
					//var_dump($totals[$value['GameID']]['totalHrs']);
					//echo "elapsed: ";
					//var_dump($value['Elapsed']);
					
					if($value['Elapsed'] == "") {$value['Elapsed']=0;}
					$totals[$value['GameID']]['totalHrs'] += $value['Elapsed'];
					if(strtotime($value['Timestamp']) >= strtotime("-7 Days Midnight")) {
						$totals[$value['GameID']]['weekPlay'] += $value['Elapsed'];
					}
					if(strtotime($value['Timestamp']) >= strtotime("-1 month Midnight")) {
						$totals[$value['GameID']]['monthPlay'] += $value['Elapsed'];
					}
					if(strtotime($value['Timestamp']) >= strtotime("-1 year Midnight")) {
						$totals[$value['GameID']]['yearPlay'] += $value['Elapsed'];
					}
				}
				
				if(!isset($totals[$value['GameID']]['Achievements'])) {$totals[$value['GameID']]['Achievements']=0;}
				if ($value['Achievements']<>"" && $totals[$value['GameID']]['Achievements'] != $value['Achievements']) {
					if(strtotime($value['Timestamp']) >= strtotime("-7 Days Midnight")) {
						//var_dump($totals[$value['GameID']]['WeekAchievements']); echo " += "; var_dump($value['Achievements']); echo " - "; var_dump($totals[$value['GameID']]['Achievements']); echo "<br>";
						$totals[$value['GameID']]['WeekAchievements'] += $value['Achievements'] - $totals[$value['GameID']]['Achievements'];
					}
					if(strtotime($value['Timestamp']) >= strtotime("-1 month Midnight")) {
						$totals[$value['GameID']]['MonthAchievements'] += $value['Achievements'] - $totals[$value['GameID']]['Achievements'];
					}
					if(strtotime($value['Timestamp']) >= strtotime("-1 year Midnight")) {
						$totals[$value['GameID']]['YearAchievements'] += $value['Achievements'] - $totals[$value['GameID']]['Achievements'];
					}
					$totals[$value['GameID']]['Achievements'] = $value['Achievements'];
				}

				if(!isset($totals[$value['GameID']]['Status'])) {$totals[$value['GameID']]['Status']="Active";}
				if ($value['Status']<>"") {
					$totals[$value['GameID']]['Status'] = $value['Status'];
				}
				
				if(!isset($totals[$value['GameID']]['Review'])) {$totals[$value['GameID']]['Review']="";}
				if ($value['Review']<>"") {
					$totals[$value['GameID']]['Review'] = $value['Review'];
				}

				if(!isset($totals[$value['GameID']]['LastBeat'])) {$totals[$value['GameID']]['LastBeat']="";}
				if ($value['kwBeatGame']==true) {
					$totals[$value['GameID']]['LastBeat'] = $value['Timestamp'];
				}
				//$totals[$value['GameID']]['Basegame']=$value['UseGame'];
				$totals[$value['GameID']]['Basegame']=$value['ParentGame'];
				$totals[$value['GameID']]['LaunchDate']=$value['LaunchDate'];
				
			}
			
			if(!isset($totals[$value['GameID']]['firstplay'])){$totals[$value['GameID']]['firstplay']="";}
			if(!isset($totals[$value['GameID']]['lastplay'])) {$totals[$value['GameID']]['lastplay'] ="";}
			if(!isset($totals[$value['GameID']]['firstPlayDateTime'])){$totals[$value['GameID']]['firstPlayDateTime']=null;}
			if(!isset($totals[$value['GameID']]['lastPlayDateTime'])) {$totals[$value['GameID']]['lastPlayDateTime'] =null;}
			if(!isset($totals[$value['GameID']]['elapsed']))  {$totals[$value['GameID']]['elapsed']  =0 ;}

			$grandTotals[$totals[$value['GameID']]['Basegame']][$value['GameID']]['GameID']    =$value['GameID'];
			$grandTotals[$totals[$value['GameID']]['Basegame']][$value['GameID']]['LaunchDate']=strtotime($totals[$value['GameID']]['LaunchDate']);
			$grandTotals[$totals[$value['GameID']]['Basegame']][$value['GameID']]['PlayTime']  =$totals[$value['GameID']]['totalHrs'];
		}

		//DONE: Play total needs to be added to unplayable DLC so they can get $/hr calculations
		foreach ($grandTotals as $basegame) {
			foreach ($basegame as $game) {
				if(!isset($totals[$game['GameID']]['GrandTotal'])) {$totals[$game['GameID']]['GrandTotal']=0;}				
				foreach ($basegame as $otherGame) {
					if($otherGame['LaunchDate'] >=  $game['LaunchDate']) {
						$totals[$game['GameID']]['GrandTotal'] += $otherGame['PlayTime'];
					}
				}
			}
		}
	} else {
		$totals=false;
	}
	
	return($totals);
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getActivityCalculations.inc") {
	$GLOBALS['rootpath']="..";
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Activity Calculations Inc Test";
	echo Get_Header($title);
	
	$lookupgame=lookupTextBox("Product", "ProductID", "id", "Game", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupgame["header"];
	if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		?>
		Please specify a game by ID.
		<form method="Get">
			<?php echo $lookupgame["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupgame["lookupBox"];
	} else {
		//$actcalculations=reIndexArray(getActivityCalculations(""),"GameID");
		$actcalculations=getActivityCalculations("");
		echo arrayTable($actcalculations[$_GET['id']]);
	}
	echo Get_Footer();
}
?>