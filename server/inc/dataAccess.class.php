<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";

class dataAccess {
	//TODO: Split into dataAccess.class and dataProcess.class
	private $dbConnection;

	function __construct($conn=null) {
		
	}
	
	public function getConnection(){
		if(isset($this->dbConnection)) {
			return $this->dbConnection;
		}
		
		require $GLOBALS['rootpath']."/inc/auth.inc.php";
		
		try{
			$this->dbConnection=new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",$username, $password);
		// @codeCoverageIgnoreStart
		} catch (exception $e){
			trigger_error("Database connection failed: ". $e->getMessage() . "\n"); // @codeCoverageIgnore
		}
		// @codeCoverageIgnoreEnd
		
		return $this->dbConnection;
	}
	
	public function closeConnection(){
		$this->dbConnection=null;
	}
	
	public function getPurchases($transactionid=null, $purchased = false){
		$filter="";
		if($transactionid <> null){
			$filter .= "where TransID = ?";
			$filter .= " OR BundleID = ?";			
		}
		
		if($purchased) {
			if($filter == "") {
				$filter .= " where ";
			} else {
				$filter .= " AND ";
			}
			$filter .= " `PurchaseDate` is not null ";
		}
		
		$query = $this->getConnection()->prepare("SELECT * FROM `gl_transactions` $filter order by `PurchaseDate` ASC, `PurchaseTime` ASC, `Sequence` ASC");
		
		if(isset($transactionid)){
			$query->bindvalue(1,$transactionid);
			$query->bindvalue(2,$transactionid);
		}
		
		$query->execute();
		
		return $query;
	}
	
	public function getGames($gameID=null) {
		$filter="";
		if($gameID <> null){
			$filter .= "where Game_ID in (?)";
			$filter .= " OR ParentGameID in (?)";			
		}
		
		$query = $this->getConnection()->prepare("SELECT * from `gl_products` $filter order by `Series` ASC, `LaunchDate` ASC");
		
		if(is_array($gameID)) {
			$gameID = implode(",",$gameID);
		}
		
		if($gameID <> null){
			$query->bindvalue(1,$gameID);
			$query->bindvalue(2,$gameID);
		}
		
		$query->execute();
		
		return $query;
	}
	
	public function getItems($itemID=null) {
		$filter="";
		if($itemID <> null){
			$filter .= "where ItemID in (?)";
		}
		
		$query = $this->getConnection()->prepare("SELECT * from `gl_items` $filter order by `DateAdded` ASC");
		
		if(is_array($itemID)) {
			$itemID = implode(",",$itemID);
		}
		
		if($itemID <> null){
			$query->bindvalue(1,$itemID);
		}
		
		$query->execute();
		
		return $query;
	}
	
	public function getItemYears() {
		$query = $this->getConnection()->prepare("SELECT DISTINCT Year(`DateAdded`) as Year FROM `gl_items` where `DateAdded` is not null ORDER by `DateAdded` ASC");
		
		$query->execute();
		
		return $query;
	}
	
	public function getItemMonths() {
		$query = $this->getConnection()->prepare("SELECT DISTINCT Year(`DateAdded`) as Year, MONTH(`DateAdded`) as Month FROM `gl_items` where `DateAdded` is not null ORDER by `DateAdded` ASC");
		
		$query->execute();
		
		return $query;
	}
	
	public function countCPI($year,$month) {
		$query = $this->getConnection()->prepare("SELECT count(*) as count FROM `gl_cpi` where `Year`=:year and `Month`=:month;");
		$query->bindvalue(":year",$year);
		$query->bindvalue(":month",$month);
		$query->execute();
		
		return $query;
	}
	
	public function addCPI($year,$month,$cpi) {
		$test = $this->getAllRows($this->countCPI($year,$month));
		if($test[0]['count']>0){
			$insert_SQL = "UPDATE gl_cpi SET CPI = :cpi WHERE `Year`=:year and `Month`=:month;";
		} else {
			$insert_SQL  = "INSERT INTO `gl_cpi` (`Year`, `Month`, `CPI`) VALUES (:year, :month, :cpi);";
		}
		
		$query = $this->getConnection()->prepare($insert_SQL);
		$query->bindvalue(":year",$year);
		$query->bindvalue(":month",$month);
		$query->bindvalue(":cpi",$cpi);
		
		if ($query->execute() === TRUE) {
			$this->insertlog("Add cpi: " . $year."-".$month." ".$cpi);
		}
	}
	
	public function getAllCPI() {
		$cpi=array();

		$sql = "select
		`gl_cpi`.`year`,
		`gl_cpi`.`month`,
		`gl_cpi`.`cpi`
		from `gl_cpi`
		where `gl_cpi`.`cpi`<>0
		order by `gl_cpi`.`Year` ASC, `gl_cpi`.`Month` ASC";
		
		$query = $this->getConnection()->prepare($sql);
		$query->execute();
		
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$cpi[$row['year']][$row['month']]=$row['cpi'];
			$cpi['Current']=$row['cpi'];			
		}		
		
		return $cpi;
	}
	
	public function getMaxTableId($table) {
		$table = strtolower($table);
		switch ($table) {
			case "purchases":
			case "transactions":
			case "gl_transactions":
				$table = "gl_transactions";
				$ID = "TransID";
				break;
			case "history":
			case "gl_history":
				$table = "gl_history";
				$ID = "HistoryID";
				break;
			case "items":
			case "gl_items":
				$table = "gl_items";
				$ID = "ItemID";
				break;
			case "games":
			case "products":
			case "gl_products":
				$table = "gl_products";
				$ID = "Game_ID";
				break;
			default:
				return false;
		}
		
		$maxID=1;
		$query=$this->getConnection()->prepare("SELECT * FROM `$table` order by `$ID` DESC Limit 1");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_ASSOC)) {
			$maxID=$result[$ID]+1;
		}
		
		return $maxID;
	}
	
	public function getMaxHistoryId() {
		return $this->getMaxTableId("gl_history");
	}
	
	public function getMaxItemId() {
		return $this->getMaxTableId("gl_items");
	}
	
	public function getMaxProductId() {
		return $this->getMaxTableId("gl_products");
	}
	
	public function getAllRows($query,$key=null) {
		$allrows=array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if(isset($key)){
				$allrows[$row[$key]] = $row;
			} else {
				$allrows[] = $row;
			}
		}
		return $allrows;
	}

	public function insertHistory($datarow,$timestamp,$maxID){
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
		
		$sql2 = "";
		//loop through all data rows
		$loopcount=0;
		foreach($datarow as $insertrow){
			//Check if the indicator is on for update that row.
			if(isset($insertrow['update']) && $insertrow['update']=="on"){
				$loopcount++;
				if($sql2<>"") {
					$sql2.=",";
				}
				
				$sql2.="(:insertid$loopcount, "; // HistoryID
				$sql2.=":date$loopcount, "; //Time
				
				$sql2.=":title$loopcount, "; //Game (Name)
				$sql2.=":system$loopcount, "; //System
				$sql2.=":data$loopcount, "; //Data
				$sql2.=":hours$loopcount, "; //Time
				$sql2.=":notes$loopcount, "; //Notes
				$sql2.=":source$loopcount, "; //Source / RowType
				$sql2.=":achievements$loopcount, "; //Achivements
				$sql2.=":status$loopcount, "; //Status
				$sql2.=":review$loopcount, "; //Review
				$sql2.=":basegame$loopcount, "; //BaseGame
				$sql2.=":minutes$loopcount, "; //Keyword Minutes
				$sql2.=":idle$loopcount, "; //Keyword Idle
				$sql2.=":cardfarming$loopcount, "; //Keyword Card Farming
				$sql2.=":cheating$loopcount, "; //Keyword Cheating
				$sql2.=":beatgame$loopcount, "; //Keyword Beat Game
				$sql2.=":share$loopcount, "; //Keyword Share
				$sql2.=":gameid$loopcount)"; //GameID			
			}
		}
		
		$query = $this->getConnection()->prepare($sql.$sql2);
		
		$loopcount=0;
		foreach($datarow as $insertrow){
			if(isset($insertrow['update']) && $insertrow['update']=="on"){
				$inserted[]=$insertrow;
				$loopcount++;
				if(isset($insertrow['id'])) {
					$query->bindvalue(":insertid$loopcount",$insertrow['id']);
				} else {
					$query->bindvalue(":insertid$loopcount",$maxID);
					$maxID++;
				}
				if( isset($_POST['currenttime']) && $_POST['currenttime'] == "on") {
					$query->bindvalue(":date$loopcount",date("Y-m-d H:i:s"));
				} else {
					$query->bindvalue(":date$loopcount",date("Y-m-d H:i:s",strtotime($timestamp)));
				}
				$query->bindvalue(":title$loopcount",$insertrow['Title']);
				$query->bindvalue(":system$loopcount",$insertrow['System']);
				$query->bindvalue(":data$loopcount",$insertrow['Data']);
				$query->bindvalue(":hours$loopcount",$insertrow['hours']);
				$query->bindvalue(":notes$loopcount",$insertrow['notes']);
				$query->bindvalue(":source$loopcount",$insertrow['source']);
				$query->bindvalue(":achievements$loopcount",$insertrow['achievements']);
				$query->bindvalue(":status$loopcount",$insertrow['status']);
				$query->bindvalue(":review$loopcount",$insertrow['review']);
				$query->bindvalue(":basegame$loopcount",isset($insertrow['basegame']) && $insertrow['basegame'] == "on" ? 1 : 0);
				$query->bindvalue(":minutes$loopcount",isset($insertrow['minutes']) && $insertrow['minutes'] == "on" ? 1 : 0);
				$query->bindvalue(":idle$loopcount",isset($insertrow['idle']) && $insertrow['idle'] == "on" ? 1 : 0);
				$query->bindvalue(":cardfarming$loopcount",isset($insertrow['cardfarming']) && $insertrow['cardfarming'] == "on" ? 1 : 0);
				$query->bindvalue(":cheating$loopcount",isset($insertrow['cheating']) && $insertrow['cheating'] == "on" ? 1 : 0);
				$query->bindvalue(":beatgame$loopcount",isset($insertrow['beatgame']) && $insertrow['beatgame'] == "on" ? 1 : 0);
				$query->bindvalue(":share$loopcount",isset($insertrow['share']) && $insertrow['share'] == "on" ? 1 : 0);
				$query->bindvalue(":gameid$loopcount",$insertrow['ProductID']);
			}
		}
		
		if ($query->execute() === TRUE) {
			//return "Record updated successfully<br>";
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update: " . date("Y-m-d H:i:s",strtotime($timestamp)) . " (" . $timestamp . ") " . print_r($inserted,true));
		}
	}
	
	public function logFileName(){
		return $GLOBALS['rootpath'].'\insertlog'.date("Y").'.txt';
	}
	
	public function insertlog($content,$file=null) {
		$file = $file ?? $this->logFileName();
		//var_dump($file);
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $content."\r\n\r\n", FILE_APPEND | LOCK_EX);
	}
	
	public function updateAllSettings($postdata) {
		//Set values to off for unchecked checkboxes. (Unchecked boxes are not submitted with forms.)
		if(!isset($postdata['CountFarm']))          {$postdata['CountFarm']="Off";}
		if(!isset($postdata['CountShare']))         {$postdata['CountShare']="Off";}
		if(!isset($postdata['CountIdle']))          {$postdata['CountIdle']="Off";}
		if(!isset($postdata['CountCheat']))         {$postdata['CountCheat']="Off";}
		if(!isset($postdata['AdjustforInflation'])) {$postdata['AdjustforInflation']="Off";}
		if(!isset($postdata['CountFree']))          {$postdata['CountFree']="Off";}
		if(!isset($postdata['CountNever']))         {$postdata['CountNever']="Off";}
		if(!isset($postdata['CountWantX']))         {$postdata['CountWantX']="Off";}
		
		if(!isset($postdata['Active-Active']))   {$postdata['Active-Active']="Off";}
		if(!isset($postdata['Broken-Active']))   {$postdata['Broken-Active']="Off";}
		if(!isset($postdata['Done-Active']))     {$postdata['Done-Active']="Off";}
		if(!isset($postdata['Inactive-Active'])) {$postdata['Inactive-Active']="Off";}
		if(!isset($postdata['Never-Active']))    {$postdata['Never-Active']="Off";}
		if(!isset($postdata['On_Hold-Active']))  {$postdata['On_Hold-Active']="Off";}
		if(!isset($postdata['Unplayed-Active'])) {$postdata['Unplayed-Active']="Off";}

		if(!isset($postdata['Active-Count']))   {$postdata['Active-Count']="Off";}
		if(!isset($postdata['Broken-Count']))   {$postdata['Broken-Count']="Off";}
		if(!isset($postdata['Done-Count']))     {$postdata['Done-Count']="Off";}
		if(!isset($postdata['Inactive-Count'])) {$postdata['Inactive-Count']="Off";}
		if(!isset($postdata['Never-Count']))    {$postdata['Never-Count']="Off";}
		if(!isset($postdata['On_Hold-Count']))  {$postdata['On_Hold-Count']="Off";}
		if(!isset($postdata['Unplayed-Count'])) {$postdata['Unplayed-Count']="Off";}
		
		foreach ($postdata as $setting_label => $setting_value) {
			$realLabel = $setting_label;
			$valueType = "";
			$table = "";
			switch ($setting_label) {
				//Numeric
				case "Tax":
				case "TrackHours":
				case "LessStat":
				case "XhourGet" :
				case "MinPlay" :
				case "MinTotal" :
				case "WantX" :
					$update_SQL = "UPDATE `gl_settings` SET `SettingNum` = :value WHERE `gl_settings`.`Setting` = :label;";
					break;
				//True-False
				case "CountFarm":
				case "CountShare":
				case "CountIdle":
				case "CountCheat":
				case "AdjustforInflation":
				case "CountFree":
				case "CountNever":
				case "CountWantX":
					if($setting_value=="on") {$setting_value=1;} else {$setting_value=0;}
					$update_SQL = "UPDATE `gl_settings` SET `SettingNum` = :value WHERE `gl_settings`.`Setting` = :label;";
					break;
				//Date 
				case "StartStats":
					$update_SQL = "UPDATE `gl_settings` SET `SettingDate` = :value WHERE `gl_settings`.`Setting` = :label;";
					break;
				//Status
				case "Active-Active":
				case "Broken-Active":
				case "Done-Active":
				case "Inactive-Active":
				case "Never-Active":
				case "On_Hold-Active":
				case "Unplayed-Active":
				
				case "Active-Count":
				case "Broken-Count":
				case "Done-Count":
				case "Inactive-Count":
				case "Never-Count":
				case "On_Hold-Count":
				case "Unplayed-Count":
					if($setting_value=="on") {$setting_value=1;} else {$setting_value=0;}

					list($realLabel, $valueType) = explode("-", $setting_label);

					$update_SQL = "UPDATE `gl_status` SET `".$valueType."` = :value WHERE `gl_status`.`Status` = :label;";
					break;
			}
			
				$query = $this->getConnection()->prepare($update_SQL);
				$query->bindvalue(':value',         $setting_value);
				$query->bindvalue(':label',         $realLabel);
				
				if ($query->execute() === TRUE) {
					//TODO: The print_r makes the insertlog look weird.
					$this->insertlog("Update Setting: " . $realLabel . " - " . $setting_value);
			}
		}
	}
	
	public function updateItem($postdata) {
		$update_SQL  = "UPDATE `gl_items` SET ";
		if($postdata['ProductID']=="") {
			$update_SQL .= "`ProductID`   = NULL, ";
		} else {
			$update_SQL .= "`ProductID`   = :ProductID, ";
		}
		$update_SQL .= "`TransID`         = :TransID, ";
		$update_SQL .= "`ParentProductID` = :ParentProductID, ";
		$update_SQL .= "`Notes`           = :Notes, ";
		$update_SQL .= "`Tier`            = :Tier, ";
		$update_SQL .= "`ActivationKey`   = :ActivationKey, ";
		if($postdata['SizeMB']=="") {
			$update_SQL .= "`SizeMB`      = NULL, ";
		} else {
			$update_SQL .= "`SizeMB`      = :SizeMB, ";
		}
		$update_SQL .= "`Library`         = :Library, ";
		$update_SQL .= "`DRM`             = :DRM, ";
		$update_SQL .= "`OS`              = :OS, ";
		$update_SQL .= "`DateAdded`       = :DateAdded, ";
		$update_SQL .= "`Time Added`      = :TimeAdded, ";
		$update_SQL .= "`Sequence`        = :Sequence ";
		$update_SQL .= "WHERE `ItemID`    = :ItemID";

		$query = $this->getConnection()->prepare($update_SQL);

		if($postdata['ProductID']<>"") {
			$query->bindvalue(':ProductID',   $postdata['ProductID']);
		}
		$query->bindvalue(':TransID',         $postdata['TransID']);
		$query->bindvalue(':ParentProductID', $postdata['ParentProductID']);
		$query->bindvalue(':Notes',           $postdata['Notes']);
		$query->bindvalue(':Tier',            $postdata['Tier']);
		$query->bindvalue(':ActivationKey',   $postdata['ActivationKey']);
		if($postdata['SizeMB']<>"") {
			$query->bindvalue(':SizeMB',      $postdata['SizeMB']);
		}
		$query->bindvalue(':Library',         $postdata['Library']);
		$query->bindvalue(':DRM',             $postdata['DRM']);
		$query->bindvalue(':OS',              $postdata['OS']);
		$query->bindvalue(':DateAdded',       date("Y-m-d",strtotime($postdata['purchasetime'])));
		$query->bindvalue(':TimeAdded',       date("H:i:00",strtotime($postdata['purchasetime'])));
		$query->bindvalue(':Sequence',        $postdata['Sequence']);
		$query->bindvalue(':ItemID',          $postdata['ItemID']);
		
		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update Item: " . $postdata['ItemID'] . " " . print_r($postdata,true));
		}
	}
	
	public function updateGame($postdata) {
		$update_SQL  = "UPDATE `gl_products` SET ";
		$update_SQL .= "`Title`             = :Title, ";
		$update_SQL .= "`Type`              = :Type, ";
		$update_SQL .= "`Playable`          = :Playable, ";
		$update_SQL .= "`Want`              = :Want, ";
		$update_SQL .= "`Series`            = :Series, ";
		$update_SQL .= "`LaunchDate`        = :LaunchDate, ";
		$update_SQL .= "`LaunchPrice`       = :LaunchPrice, ";
		$update_SQL .= "`MSRP`              = :MSRP, ";
		$update_SQL .= "`CurrentMSRP`       = :CurrentMSRP, ";
		$update_SQL .= "`HistoricLow`       = :HistoricLow, ";
		$update_SQL .= "`LowDate`           = :LowDate, ";
		$update_SQL .= "`SteamAchievements` = :SteamAchievements, ";
		$update_SQL .= "`SteamCards`        = :SteamCards, ";
		$update_SQL .= "`TimeToBeat`        = :TimeToBeat, ";
		$update_SQL .= "`Metascore`         = :Metascore, ";
		$update_SQL .= "`UserMetascore`     = :UserMetascore, ";
		$update_SQL .= "`SteamRating`       = :SteamRating, ";
		$update_SQL .= "`SteamID`           = :SteamID, ";
		$update_SQL .= "`GOGID`             = :GOGID, ";
		$update_SQL .= "`isthereanydealID`  = :isthereanydealID, ";
		$update_SQL .= "`TimeToBeatID`      = :TimeToBeatID, ";
		$update_SQL .= "`MetascoreID`       = :MetascoreID, ";
		$update_SQL .= "`DateUpdated`       = :DateUpdated, ";
		$update_SQL .= "`ParentGameID`      = :ParentGameID, ";
		$update_SQL .= "`ParentGame`        = :ParentGame, ";
		$update_SQL .= "`Developer`         = :Developer, ";
		$update_SQL .= "`Publisher`         = :Publisher ";
		$update_SQL .= "WHERE `gl_products`.`Game_ID` = :Game_ID";

		$query = $this->getConnection()->prepare($update_SQL);

		$query->bindvalue(':Title',             $postdata['Title']);
		$query->bindvalue(':Type',              $postdata['Type']);
		$query->bindvalue(':Playable',          $postdata['Playable']);
		$query->bindvalue(':Want',              $postdata['Want']);
		$query->bindvalue(':Series',            $postdata['Series']);
		$query->bindvalue(':LaunchDate',        date("Y-m-d H:i:00",strtotime($postdata['LaunchDate'])));
		$query->bindvalue(':LaunchPrice',       $postdata['LaunchPrice']);
		$query->bindvalue(':MSRP',              $postdata['MSRP']);
		$query->bindvalue(':CurrentMSRP',       $postdata['CurrentMSRP']);
		$query->bindvalue(':HistoricLow',       $postdata['HistoricLow']);
		$query->bindvalue(':LowDate',           date("Y-m-d H:i:00",strtotime($postdata['LowDate'])));
		$query->bindvalue(':SteamAchievements', $postdata['SteamAchievements']);
		$query->bindvalue(':SteamCards',        $postdata['SteamCards']);
		$query->bindvalue(':TimeToBeat',        $postdata['TimetoBeat']);
		$query->bindvalue(':Metascore',         $postdata['Metascore']);
		$query->bindvalue(':UserMetascore',     $postdata['UserMetascore']);
		$query->bindvalue(':SteamRating',       $postdata['SteamRating']);
		$query->bindvalue(':SteamID',           $postdata['SteamID']);
		$query->bindvalue(':GOGID',             $postdata['GOGID']);
		$query->bindvalue(':isthereanydealID',  $postdata['isthereanydealID']);
		$query->bindvalue(':TimeToBeatID',      $postdata['TimeTobeatID']);
		$query->bindvalue(':MetascoreID',       $postdata['MetascoreID']);
		$query->bindvalue(':DateUpdated',       $postdata['DateUpdated']);
		$query->bindvalue(':ParentGameID',      $postdata['ParentGameID']);
		$query->bindvalue(':ParentGame',        $postdata['ParentGame']);
		$query->bindvalue(':Developer',         $postdata['Developer']);
		$query->bindvalue(':Publisher',         $postdata['Publisher']);
		$query->bindvalue(':Game_ID',           $postdata['ID']);
		
		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update Game: " . $postdata['ID'] . " " . print_r($postdata,true));
		}
	}
	
	private function splitKeywords($group,$kwList) {
		$kwlist2=array();
		if($kwList<>"" and $kwList<>"null"){
			$kwlist1=explode(",",$kwList);
			foreach($kwlist1 as $kw){
				$kwlist2[]=array("kwType" => $group,"Keyword" => trim($kw));
			}
		}
		return $kwlist2;
	}
	
	public function getKeywords($gameID) {
		$sql="SELECT * FROM `gl_keywords` WHERE `ProductID` = :gameID";
		$query = $this->getConnection()->prepare($sql);
		$query->bindvalue(':gameID', $gameID);
		$query->execute();
		return $query;
	}
	
	public function updateKeywords($postdata) {
		$Genrelist = $this->splitKeywords('Genre',$postdata['Genre']);
		$Typelist = $this->splitKeywords('GameType',$postdata['GameType']);
		$sModelist = $this->splitKeywords('StoryMode',$postdata['StoryMode']);
		$Feturelist = $this->splitKeywords('GameFeature',$postdata['GameFeature']);
		$gModelist = $this->splitKeywords('GameMode',$postdata['GameMode']);
		
		$kwlist2 = array_merge($Genrelist,$Typelist,$sModelist,$Feturelist,$gModelist);

		$delete_SQL = "DELETE FROM gl_keywords WHERE `gl_keywords`.`ProductID` = :gameid";
		$query = $this->getConnection()->prepare($delete_SQL);
		$query->bindvalue(':gameid', $postdata['ID']);
		$query->execute();
		
		$insert_SQL="";
		$index=100;
		foreach ($kwlist2 as $kw) {
			if($insert_SQL<>""){
				$insert_SQL .=", ";
			}
			
			$insert_SQL .= "(:gameid, :kwtype$index, :keyword$index) ";
			$index++;
		}
		$insert_SQL = "INSERT INTO `gl_keywords` (`ProductID`, `KwType`, `Keyword`) VALUES " . $insert_SQL;

		$query2 = $this->getConnection()->prepare($insert_SQL);
		
		$query2->bindvalue(':gameid', $postdata['ID']);
		$index=100;
		foreach ($kwlist2 as $kw) {
			$query2->bindvalue(":kwtype$index", $kw['kwType']);
			$query2->bindvalue(":keyword$index", $kw['Keyword']);
			$index++;
		}
		
		if ($query2->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update Keywords: " . $postdata['ID'] . " " . print_r($postdata,true));
		}
	}
	
	public function updateBundle($postdata)	{
		$update_SQL  = "UPDATE `gl_transactions` SET ";
		$update_SQL .= "`Title`        = :title, ";
		$update_SQL .= "`Store`        = :store, ";
		$update_SQL .= "`BundleID`     = :bundleid, ";
		$update_SQL .= "`Tier`         = :tier, ";
		$update_SQL .= "`PurchaseDate` = :purchasedate, ";
		$update_SQL .= "`PurchaseTime` = :purchasetime, ";
		$update_SQL .= "`Sequence`     = :sequence, ";
		$update_SQL .= "`Price`        = :price, ";
		$update_SQL .= "`Fees`         = :fees, ";
		$update_SQL .= "`Paid`         = :paid, ";
		$update_SQL .= "`Credit Used`  = :credit, ";
		$update_SQL .= "`Bundle Link`  = :link ";
		$update_SQL .= "WHERE `TransID` = :transid";

		$query = $this->getConnection()->prepare($update_SQL);

		$query->bindvalue(':title',       $postdata['Title']);
		$query->bindvalue(':store',       $postdata['Store']);
		$query->bindvalue(':bundleid',    $postdata['BundleID']);
		$query->bindvalue(':tier',        $postdata['Tier']);
		$query->bindvalue(':purchasedate',date("Y-m-d",strtotime($postdata['purchasetime'])));
		$query->bindvalue(':purchasetime',date("H:i:00",strtotime($postdata['purchasetime'])));
		$query->bindvalue(':sequence',    $postdata['Sequence']);
		$query->bindvalue(':price',       $postdata['Price']);
		$query->bindvalue(':fees',        $postdata['Fees']);
		$query->bindvalue(':paid',        $postdata['Paid']);
		$query->bindvalue(':credit',      $postdata['Credit']);
		$query->bindvalue(':link',        $postdata['Link']);
		$query->bindvalue(':transid',     $postdata['TransID']);

		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update Bundle: " . $postdata['BundleID'] . " " . print_r($postdata,true));
		}
	}
	
	public function insertItem($postdata)	{
		$insert_SQL  = "INSERT INTO `gl_items` (`ItemID`, `ProductID`, `TransID`, `ParentProductID`, `Tier`, `Notes`, `SizeMB`, `DRM`, `OS`, `ActivationKey`, `DateAdded`, `Time Added`, `Sequence`, `Library`) ";
		$insert_SQL .= "VALUES (:ItemID, :ProductID, :TransID, :ParentProductID, :Tier, :Notes, :SizeMB, :DRM, :OS, :ActivationKey, :DateAdded, :Time_Added, :Sequence, :Library )";
		
		$query = $this->getConnection()->prepare($insert_SQL);

		$query->bindvalue(':ItemID',         $postdata['ItemID']);
		$query->bindvalue(':ProductID',      $postdata['ProductID']);
		$query->bindvalue(':TransID',        $postdata['TransID']);
		$query->bindvalue(':ParentProductID',$postdata['ParentProductID']);
		$query->bindvalue(':Tier',           $postdata['Tier']);
		$query->bindvalue(':Notes',          $postdata['Notes']);
		$query->bindvalue(':SizeMB',         $postdata['SizeMB']);
		$query->bindvalue(':DRM',            $postdata['DRM']);
		$query->bindvalue(':OS',             $postdata['OS']);
		$query->bindvalue(':ActivationKey',  $postdata['ActivationKey']);
		$query->bindvalue(':DateAdded',      date("Y-m-d",strtotime($postdata['DateAdded'])));
		$query->bindvalue(':Time_Added',     date("H:i:00",strtotime($postdata['Time_Added'])));
		$query->bindvalue(':Sequence',       $postdata['Sequence']);
		$query->bindvalue(':Library',        $postdata['Library']);

		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Insert Item: " . $postdata['ItemID'] . " " . print_r($postdata,true));
		}
	}
	
	public function insertGame($postdata)	{
		$insert_SQL  = "INSERT INTO `gl_products` (`Game_ID`, `Title`, `Series`, `LaunchDate`, `LaunchPrice`, `MSRP`, `CurrentMSRP`, `HistoricLow`, `LowDate`, `SteamAchievements`, `SteamCards`, `TimeToBeat`, `Metascore`, `UserMetascore`, `SteamRating`, `SteamID`, `GOGID`, `isthereanydealID`, `TimeToBeatID`, `MetascoreID`, `DateUpdated`, `Want`, `Playable`, `Type`, `ParentGameID`, `ParentGame`, `Developer`, `Publisher`) ";
		$insert_SQL .= "VALUES (:Game_ID, :Title, :Series, :LaunchDate, :LaunchPrice, :MSRP, :CurrentMSRP, :HistoricLow, :LowDate, :SteamAchievements, :SteamCards, :TimeToBeat, :Metascore, :UserMetascore, :SteamRating, :SteamID, :GOGID, :isthereanydealID, :TimeToBeatID, :MetascoreID, :DateUpdated, :Want, :Playable, :Type, :ParentGameID, :ParentGame, :Developer, :Publisher)";
		
		$query = $this->getConnection()->prepare($insert_SQL);

		$query->bindvalue(':Game_ID',           $postdata['Game_ID']);
		$query->bindvalue(':Title',             $postdata['Title']);
		$query->bindvalue(':Series',            $postdata['Series']);
		$query->bindvalue(':LaunchDate',        $postdata['LaunchDate']);
		$query->bindvalue(':LaunchPrice',       $postdata['LaunchPrice']);
		$query->bindvalue(':MSRP',              $postdata['MSRP']);
		$query->bindvalue(':CurrentMSRP',       $postdata['CurrentMSRP']);
		$query->bindvalue(':HistoricLow',       $postdata['HistoricLow']);
		$query->bindvalue(':LowDate',           $postdata['LowDate']);
		$query->bindvalue(':SteamAchievements', $postdata['SteamAchievements']);
		$query->bindvalue(':SteamCards',        $postdata['SteamCards']);
		$query->bindvalue(':TimeToBeat',        $postdata['TimeToBeat']);
		$query->bindvalue(':Metascore',         $postdata['Metascore']);
		$query->bindvalue(':UserMetascore',     $postdata['UserMetascore']);
		$query->bindvalue(':SteamRating',       $postdata['SteamRating']);
		$query->bindvalue(':SteamID',           $postdata['SteamID']);
		$query->bindvalue(':GOGID',             $postdata['GOGID']);
		$query->bindvalue(':isthereanydealID',  $postdata['isthereanydealID']);
		$query->bindvalue(':TimeToBeatID',      $postdata['TimeToBeatID']);
		$query->bindvalue(':MetascoreID',       $postdata['MetascoreID']);
		$query->bindvalue(':DateUpdated',       $postdata['DateUpdated']);
		$query->bindvalue(':Want',              $postdata['Want']);
		$query->bindvalue(':Playable',          $postdata['Playable']);
		$query->bindvalue(':Type',              $postdata['Type']);
		$query->bindvalue(':ParentGameID',      $postdata['ParentGameID']);
		$query->bindvalue(':ParentGame',        $postdata['ParentGame']);
		$query->bindvalue(':Developer',         $postdata['Developer']);
		$query->bindvalue(':Publisher',         $postdata['Publisher']);

		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Insert Game: " . $postdata['Game_ID'] . " " . print_r($postdata,true));
		}
	}

	public function insertGame2($postdata)	{
		$insert_SQL  = "INSERT INTO `gl_products` (`Game_ID`, `Title`, `Series`, `LaunchDate`, `SteamID`, `Want`, `Playable`, `Type`, `ParentGameID`, `ParentGame`) ";
		$insert_SQL .= "VALUES (:Game_ID, :Title, :Series, :LaunchDate, :SteamID, :Want, :Playable, :Type, :ParentGameID, :ParentGame )";
		
		$query = $this->getConnection()->prepare($insert_SQL);

		$query->bindvalue(':Game_ID',     $postdata['Game_ID']);
		$query->bindvalue(':Title',       $postdata['Title']);
		$query->bindvalue(':Series',      $postdata['Series']);
		$query->bindvalue(':LaunchDate',  $postdata['LaunchDate']);
		$query->bindvalue(':SteamID',     $postdata['SteamID']);
		$query->bindvalue(':Want',        $postdata['Want']);
		$query->bindvalue(':Playable',    $postdata['Playable']);
		$query->bindvalue(':Type',        $postdata['Type']);
		$query->bindvalue(':ParentGameID',$postdata['ParentGameID']);
		$query->bindvalue(':ParentGame',  $postdata['ParentGame']);

		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Insert Game: " . $postdata['Game_ID'] . " " . print_r($postdata,true));
		}
	}
	
	public function insertTransaction($postdata)	{
		$insert_SQL  = "INSERT INTO `gl_transactions` (`TransID`, `Title`, `Store`, `BundleID`, `Tier`, `PurchaseDate`, `PurchaseTime`, `Sequence`, `Price`, `Fees`, `Paid`, `Credit Used`, `Bundle Link`) ";
		$insert_SQL .= "VALUES (:TransID, :Title, :Store, :BundleID, :Tier, :PurchaseDate, :PurchaseTime, :Sequence, :Price, :Fees, :Paid, :CreditUsed, :BundleLink )";
		
		$query = $this->getConnection()->prepare($insert_SQL);

		$query->bindvalue(':TransID',      $postdata['TransID']);
		$query->bindvalue(':Title',        $postdata['Title']);
		$query->bindvalue(':Store',        $postdata['Store']);
		$query->bindvalue(':BundleID',     $postdata['BundleID']);
		$query->bindvalue(':Tier',         $postdata['Tier']);
		$query->bindvalue(':PurchaseDate', $postdata['PurchaseDate']);
		$query->bindvalue(':PurchaseTime', $postdata['PurchaseTime']);
		$query->bindvalue(':Sequence',     $postdata['Sequence']);
		$query->bindvalue(':Price',        $postdata['Price']);
		$query->bindvalue(':Fees',         $postdata['Fees']);
		$query->bindvalue(':Paid',         $postdata['Paid']);
		$query->bindvalue(':CreditUsed',   $postdata['CreditUsed']);
		$query->bindvalue(':BundleLink',   $postdata['BundleLink']);

		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Insert Pruchase: " . $postdata['TransID'] . " " . print_r($postdata,true));
		}
	}
		
	public function updateHistory($insertrow,$timestamp){
		$sql ="UPDATE `gl_history` ";
		$sql.="SET `Timestamp`  = :date, "; //Timestamp  2015/09/18 22:08:55
		$sql.=" `Game`          = :title, "; //Game (Name)
		$sql.=" `System`        = :system, "; //System
		$sql.=" `Data`          = :data, "; //Data
		$sql.=" `Time`          = :hours, "; //Time
		$sql.=" `Notes`         = :notes, "; //Notes
		$sql.=" `RowType`       = :source, "; //Source / RowType
		$sql.=" `Achievements`  = :achievements, "; //Achivements
		$sql.=" `Status`        = :status, "; //Status
		$sql.=" `Review`        = :review, "; //Review
		$sql.=" `BaseGame`      = :basegame, "; //BaseGame
		$sql.=" `kwMinutes`     = :minutes, "; //Keyword Minutes
		$sql.=" `kwIdle`        = :idle, "; //Keyword Idle
		$sql.=" `kwCardFarming` = :cardfarming, "; //Keyword Card Farming
		$sql.=" `kwCheating`    = :cheating, "; //Keyword Cheating
		$sql.=" `kwBeatGame`    = :beatgame, "; //Keyword Beat Game
		$sql.=" `kwShare`       = :share, "; //Keyword Share
		$sql.=" `GameID`        = :gameid"; //GameID
		$sql.=" WHERE `gl_history`.`HistoryID` = :insertid";
		
		$query = $this->getConnection()->prepare($sql);
		
		$query->bindvalue(':date',date("Y-m-d H:i:s",strtotime($timestamp)));
		$query->bindvalue(':title',$insertrow['Title']);
		$query->bindvalue(':system',$insertrow['System']);
		$query->bindvalue(':data',$insertrow['Data']);
		$query->bindvalue(':hours',$insertrow['hours']);
		$query->bindvalue(':notes',$insertrow['notes']);
		$query->bindvalue(':source',$insertrow['source']);
		$query->bindvalue(':achievements',$insertrow['achievements']);
		$query->bindvalue(':status',$insertrow['status']);
		$query->bindvalue(':review',$insertrow['review']);
		$query->bindvalue(':basegame',isset($insertrow['basegame']) && $insertrow['basegame'] == "on" ? 1 : 0);
		$query->bindvalue(':minutes',isset($insertrow['minutes']) && $insertrow['minutes'] == "on" ? 1 : 0);
		$query->bindvalue(':idle',isset($insertrow['idle']) && $insertrow['idle'] == "on" ? 1 : 0);
		$query->bindvalue(':cardfarming',isset($insertrow['cardfarming']) && $insertrow['cardfarming'] == "on" ? 1 : 0);
		$query->bindvalue(':cheating',isset($insertrow['cheating']) && $insertrow['cheating'] == "on" ? 1 : 0);
		$query->bindvalue(':beatgame',isset($insertrow['beatgame']) && $insertrow['beatgame'] == "on" ? 1 : 0);
		$query->bindvalue(':share',isset($insertrow['share']) && $insertrow['share'] == "on" ? 1 : 0);
		$query->bindvalue(':gameid',$insertrow['ProductID']);
		$query->bindvalue(':insertid',$insertrow['id']);
		
		if ($query->execute() === TRUE) {
			//TODO: The print_r makes the insertlog look weird.
			$this->insertlog("Update: " . $timestamp . " " . print_r($insertrow,true));
		}
	}

	public function getLatestHistory(){
		$query=$this->getConnection()->prepare("SELECT * FROM `gl_history` order by `Timestamp` DESC, `HistoryID` DESC Limit 1");
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getHistoryRecrod($HistoryID){
		$query=$this->getConnection()->prepare("SELECT * FROM `gl_history` join `gl_products` on `gl_history`.`GameID` = `gl_products`.`Game_ID` WHERE `HistoryID`=?");
		$query->bindvalue(1,$HistoryID);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function countGameStartStop($gameid){
		$query = $this->getConnection()->prepare("SELECT count(*) c FROM `gl_history` where `GameID` = ? and `Data` = 'Start/Stop';");
		$query->bindvalue(1,$gameid);
		$query->execute();
		$result= $query->fetch(PDO::FETCH_ASSOC);
		return $result["c"];
	}
	
	public function isEven($number){
		return 1-$number&1;
	}
	
	public function isOdd($number){
		return $number&1;
	}
	
	public function isGameStarted($historyarray) {
		if($historyarray["Data"]=='Start/Stop'){
			if($this->isEven($this->countGameStartStop($historyarray['GameID']))){
				return false;
			} else {
				return $historyarray['GameID'];
			}
		} else {
			return false;
		}
	}
	
	public function getStartedGame(){
		$historyarray=$this->getLatestHistory();
		return $this->isGameStarted($historyarray);
	}
	
	public function getHistoryRecord($gameid){
		$query=$this->getConnection()->prepare("SELECT `Title`, `gl_history`.*, `SteamID` 
				FROM `gl_products` 
				left join `gl_history` on `gl_history`.`GameID` = `gl_products`.`Game_ID` 
				WHERE `Game_ID`=? 
				ORDER by `Timestamp` desc;");
		$query->bindvalue(1,$gameid);
		$query->execute();
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if(!isset($lastgamerecord)){
				$lastgamerecord=$row;
			}
			
			$lastgamerecord['Status']=$this->fillIfBlank($lastgamerecord['Status'],$row['Status']);
			$lastgamerecord['Review']=$this->fillIfBlank($lastgamerecord['Review'],$row['Review']);
			
			if($lastgamerecord['Status']<>"" && $lastgamerecord['Review']<>""){
				break;
			}
		}
		return $lastgamerecord ?? array();
	}
	
	public function fillIfBlank($target,$value){
		if($target=="" && $value<>""){
			return $value;
		}
		return $target;
	}
	
	public function getStatusList(){
		$query=$this->getConnection()->prepare("SELECT `Status` FROM `gl_status` order by `Active` DESC, `Count` DESC");
		$query->execute();
		return $this->getAllRows($query);
		
		/*
		array(7) {
		  [0]=> array(1) { ["Status"]=> string(6) "Active"	}
		  [1]=> array(1) { ["Status"]=> string(4) "Done"	}
		  [2]=> array(1) { ["Status"]=> string(8) "Inactive"}
		  [3]=> array(1) { ["Status"]=> string(7) "On Hold"	}
		  [4]=> array(1) { ["Status"]=> string(8) "Unplayed"}
		  [5]=> array(1) { ["Status"]=> string(6) "Broken"	}
		  [6]=> array(1) { ["Status"]=> string(5) "Never"	}
		}
		*/
	}
	
	public function getHistoryDataTypes(){
		$query=$this->getConnection()->prepare("SELECT DISTINCT `Data` FROM `gl_history` where `system` is not null	order by `system`");
		$query->execute();
		return $this->getAllRows($query);
	}

	public function getSystemList(){
		$query=$this->getConnection()->prepare("SELECT DISTINCT `system` FROM `gl_history` where `system` is not null OR `system` <> '' order by `system`");
		$query->execute();
		return $this->getAllRows($query);
	}
	
	public function getProductTitle($gameid){
		$query=$this->getConnection()->prepare("SELECT `Title` FROM `gl_products` WHERE `Game_ID`=? limit 1");
		$query->bindvalue(1,$gameid);
		$query->execute();
		return $this->getAllRows($query)[0]['Title'] ?? "";
		//return $this->getAllRows($query);
	}
}