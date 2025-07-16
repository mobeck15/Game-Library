<?php
//declare(strict_types=1);
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getPurchases.class.php";
require_once $GLOBALS['rootpath']."/inc/dataAccess.class.php";

class Games {
	public function getGames($gameID="",$connection=false){
		/* if($connection==false){
			$conn = get_db_connection();
		} else {
			$conn = $connection;
		} */
		
		$dataobject= new dataAccess();
		$statement=$dataobject->getGames($gameID);
		while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$games[]=$this->CalculateGameRow($row);		
		}
		return $games;
	}
	
	private function CalculateGameRow($row){
		$row['Game_ID']=(int)$row['Game_ID'];
		
		$row['LaunchDate'] = new DateTime($row['LaunchDate']);
		//TODO: update other files to remove need for 'LaunchDateValue'
		if(strtotime($row['LowDate'] ?? "") == 0) {
			$row['LowDate'] = "";
		} else {
			$row['LowDate'] = date("n/j/Y",strtotime($row['LowDate']));
		}
		if(strtotime($row['DateUpdated'] ?? "") == 0) {
			$row['DateUpdated'] = "";
			$row['DateUpdatedSort'] = "";
		} else {
			$row['DateUpdatedSort'] = $row['DateUpdated'];
			$row['DateUpdated'] = date("n/j/Y",strtotime($row['DateUpdated']));
		}
		
		if ($row['LaunchPrice'] == 0){
			$row['LaunchPrice'] = sprintf("%.2f",$row['MSRP']);
		} else {
			$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
		}
		$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
		
		if ($row['LaunchPrice'] == 0){
			$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
		} else {
			$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
		}
		if ($row['CurrentMSRP'] == 0){
			$row['CurrentMSRP'] = sprintf("%.2f",$row['MSRP']);
		} else {
			$row['CurrentMSRP'] = sprintf("%.2f",$row['CurrentMSRP']);
		}
		if ($row['HistoricLow'] == 0){
			$row['HistoricLow'] = sprintf("%.2f",min($row['CurrentMSRP'],$row['MSRP']));
		} else {
			$row['HistoricLow'] = sprintf("%.2f",$row['HistoricLow']);
		}
		$row['SteamAchievements']=(int)$row['SteamAchievements'];
		if ($row['SteamAchievements'] == 0){
			$row['SteamAchievements'] = null;
		} 
		$row['SteamCards']=(int)$row['SteamCards'];
		if ($row['SteamCards'] == 0){
			$row['SteamCards'] = null;
		} 
		
		$row['Metascore']=(int)$row['Metascore'];
		if ($row['Metascore'] == 0){
			if($row['MetascoreID']=="") {
				$row['MetascoreID'] = 
				$row['Metascore'] = null;
			} else {
				$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/game/".$row['Metascore']."' target='_blank'>N/A</a>";
			}
		} else {
			$row['MetascoreLinkCritic']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['Metascore']."</a>";
		}
		
		$row['UserMetascore']=(int)$row['UserMetascore'];
		if ($row['UserMetascore'] == 0){
			if($row['MetascoreID']=="") {
				$row['MetascoreID'] =
				$row['UserMetascore'] = null;
			} else {
				$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>N/A</a>";
			}
		} else {
			$row['MetascoreLinkUser']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['UserMetascore']."</a>";
		}
		$row['SteamRating']=(int)$row['SteamRating'];
		if ($row['SteamRating'] == 0){
			$row['SteamRating'] = null;
		} 
		$row['SteamID']=(int)$row['SteamID'];
		if($row['SteamID']<>0) {
			$row['SteamLinks'] = "<a href='http://store.steampowered.com/app/".$row['SteamID']."' target='_blank'>Store</a>";
		} else {
			$row['SteamID']=null;
			$row['SteamLinks'] = "<a class='Search' href='http://store.steampowered.com/search/?term=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
		}
		if($row['GOGID']<>"") {
			$row['GOGLink']= "<a href='http://www.gog.com/game/". $row['GOGID'] ."' target='_blank'>Store</a>";
		} else {
			$row['GOGLink']= "<a class='Search' href='http://www.gog.com/games##search=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
		}
		if($row['DesuraID']<>"") {
			$row['DesuraLink']= "<a href='http://www.desura.com/games/".$row['DesuraID']."' target='_blank'>Store</a>";
		} else {
			$row['DesuraLink']= "<a class='Search' href='http://www.desura.com/search?q=". urlencode($row['Title']) ."&sa.x=0&sa.y=0&sa=Search' target='_blank'>Search</a>";
		}			
		if($row['isthereanydealID']<>"") {
			//$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/#/page:game/info?plain=".$row['isthereanydealID']."' target='_blank'>Info</a>";
			$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/game/".$row['isthereanydealID']."/history' target='_blank'>Info</a>";
		} else {
			$row['isthereanydealLink'] = "<a class='Search' href='http://isthereanydeal.com/search?q=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
		}
		$row['TimeToBeatID']=(int)$row['TimeToBeatID'];
		if($row['TimeToBeatID']<>0) {
			$row['TimeToBeatLink']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>Link</a>";
			if($row['TimeToBeat']==0){
				$row['TimeToBeatLink2']= "<a class='Search' href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>N/A</a>";
			} else {
				$row['TimeToBeatLink2']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>".timeduration($row['TimeToBeat'],"hours")."</a>";
			}
		} else {
			$row['TimeToBeatLink']= "";
			$row['TimeToBeatLink2']= "";
		}
		if($row['MetascoreID'] <> "") {
			$row['MetascoreLink']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>Link</a>";
		} else {
			$row['MetascoreLink']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
			$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
			$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		}
		
		$row['Want']=(int)$row['Want'];
		$row['ParentGameID']=(int)$row['ParentGameID'];
		$row['Playable']=(bool)$row['Playable'];
		
		return $row;
	}
}

class Game {
	public int $Game_ID;
	public DateTime $LaunchDate;
	
	public function __construct($GameRowArray) {
		$this->Game_ID    = (int)$GameRowArray['Game_ID'];
		$this->LaunchDate = new DateTime($GameRowArray['LaunchDate']);
	}
}

function getGames($gameID="",$connection=false){
	$gamesobj=new Games();
	return $gamesobj->getGames($gameID,$connection);
}

/* * /
function CalculateGameRow($row){
	$row['Game_ID']=(int)$row['Game_ID'];
	
	$row['LaunchDate'] = new DateTime($row['LaunchDate']);
	//TODO: update other files to remove need for 'LaunchDateValue'
	if(strtotime($row['LowDate']) == 0) {
		$row['LowDate'] = "";
	} else {
		$row['LowDate'] = date("n/j/Y",strtotime($row['LowDate']));
	}
	if(strtotime($row['DateUpdated']) == 0) {
		$row['DateUpdated'] = "";
		$row['DateUpdatedSort'] = "";
	} else {
		$row['DateUpdatedSort'] = $row['DateUpdated'];
		$row['DateUpdated'] = date("n/j/Y",strtotime($row['DateUpdated']));
	}
	
	if ($row['LaunchPrice'] == 0){
		$row['LaunchPrice'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
	}
	$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
	
	if ($row['LaunchPrice'] == 0){
		$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
	}
	if ($row['CurrentMSRP'] == 0){
		$row['CurrentMSRP'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['CurrentMSRP'] = sprintf("%.2f",$row['CurrentMSRP']);
	}
	if ($row['HistoricLow'] == 0){
		$row['HistoricLow'] = sprintf("%.2f",min($row['CurrentMSRP'],$row['MSRP']));
	} else {
		$row['HistoricLow'] = sprintf("%.2f",$row['HistoricLow']);
	}
	$row['SteamAchievements']=(int)$row['SteamAchievements'];
	if ($row['SteamAchievements'] == 0){
		$row['SteamAchievements'] = null;
	} 
	$row['SteamCards']=(int)$row['SteamCards'];
	if ($row['SteamCards'] == 0){
		$row['SteamCards'] = null;
	} 
	
	$row['Metascore']=(int)$row['Metascore'];
	if ($row['Metascore'] == 0){
		if($row['MetascoreID']=="") {
			$row['MetascoreID'] = 
			$row['Metascore'] = null;
		} else {
			$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/game/".$row['Metascore']."' target='_blank'>N/A</a>";
		}
	} else {
		$row['MetascoreLinkCritic']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['Metascore']."</a>";
	}
	
	$row['UserMetascore']=(int)$row['UserMetascore'];
	if ($row['UserMetascore'] == 0){
		if($row['MetascoreID']=="") {
			$row['MetascoreID'] =
			$row['UserMetascore'] = null;
		} else {
			$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>N/A</a>";
		}
	} else {
		$row['MetascoreLinkUser']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['UserMetascore']."</a>";
	}
	$row['SteamRating']=(int)$row['SteamRating'];
	if ($row['SteamRating'] == 0){
		$row['SteamRating'] = null;
	} 
	$row['SteamID']=(int)$row['SteamID'];
	if($row['SteamID']<>0) {
		$row['SteamLinks'] = "<a href='http://store.steampowered.com/app/".$row['SteamID']."' target='_blank'>Store</a>";
	} else {
		$row['SteamID']=null;
		$row['SteamLinks'] = "<a class='Search' href='http://store.steampowered.com/search/?term=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	if($row['GOGID']<>"") {
		$row['GOGLink']= "<a href='http://www.gog.com/game/". $row['GOGID'] ."' target='_blank'>Store</a>";
	} else {
		$row['GOGLink']= "<a class='Search' href='http://www.gog.com/games##search=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	if($row['DesuraID']<>"") {
		$row['DesuraLink']= "<a href='http://www.desura.com/games/".$row['DesuraID']."' target='_blank'>Store</a>";
	} else {
		$row['DesuraLink']= "<a class='Search' href='http://www.desura.com/search?q=". urlencode($row['Title']) ."&sa.x=0&sa.y=0&sa=Search' target='_blank'>Search</a>";
	}			
	if($row['isthereanydealID']<>"") {
		//$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/#/page:game/info?plain=".$row['isthereanydealID']."' target='_blank'>Info</a>";
		$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/game/".$row['isthereanydealID']."/history' target='_blank'>Info</a>";
	} else {
		$row['isthereanydealLink'] = "<a class='Search' href='http://isthereanydeal.com/search?q=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	$row['TimeToBeatID']=(int)$row['TimeToBeatID'];
	if($row['TimeToBeatID']<>0) {
		$row['TimeToBeatLink']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>Link</a>";
		if($row['TimeToBeat']==0){
			$row['TimeToBeatLink2']= "<a class='Search' href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>N/A</a>";
		} else {
			$row['TimeToBeatLink2']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>".timeduration($row['TimeToBeat'],"hours")."</a>";
		}
	} else {
		$row['TimeToBeatLink']= "";
		$row['TimeToBeatLink2']= "";
	}
	if($row['MetascoreID'] <> "") {
		$row['MetascoreLink']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>Link</a>";
	} else {
		$row['MetascoreLink']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
	}
	
	$row['Want']=(int)$row['Want'];
	$row['ParentGameID']=(int)$row['ParentGameID'];
	$row['Playable']=(bool)$row['Playable'];
	
	return $row;
}
/* */

/* ----------------------------------------------------------------------------------- */

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getGames.inc") {
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="Games Inc Test";
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
		$games=reIndexArray(getGames(""),"Game_ID");
		echo arrayTable($games[$_GET['id']]);
	}
	echo Get_Footer();
}
?>
