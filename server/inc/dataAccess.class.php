<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";

class dataAccess {
	//TODO: Split into dataAccess.class and dataProcess.class
	private $dbConnection;
	// @codeCoverageIgnoreStart
	function __construct($conn=null) {
		
	}
	// @codeCoverageIgnoreEnd
	
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
	
	public function getPurchases($transactionid=null){
		$filter="";
		if($transactionid <> null){
			$filter .= "where TransID = ?";
			$filter .= " OR BundleID = ?";			
		}
		
		$query = $this->getConnection()->prepare("SELECT * FROM `gl_transactions` $filter order by `PurchaseDate` ASC, `PurchaseTime` ASC, `Sequence` ASC");
		
		if(isset($transactionid)){
			$query->bindvalue(1,$transactionid);
			$query->bindvalue(2,$transactionid);
		}
		
		$query->execute();
		
		/*
		$row = $query->fetch(PDO::FETCH_ASSOC);
		var_dump($row);
		$row = $query->fetch(PDO::FETCH_ASSOC);
		var_dump($row);
		*/
		
		return $query;
	}
	
	public function getGames($gameID=null) {
		$filter="";
		if($gameID <> null){
			$filter .= "where Game_ID in (?)";
			$filter .= " OR ParentGameID in (?)";			
		}
		
		$query = $this->getConnection()->prepare("SELECT * from `gl_products` $filter order by `Series` ASC, `LaunchDate` ASC");
		
		//var_dump($query);
		
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
	
	public function getMaxHistoryId() {
		$maxID=1;
		$query=$this->getConnection()->prepare("SELECT * FROM `gl_history` order by `HistoryID` DESC Limit 1");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_ASSOC)) {
			//var_dump($result);
			$maxID=$result['HistoryID']+1;
		}
		
		return $maxID;
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
				//print_r($insertrow);
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
		//var_dump($sql2);

		//var_dump($sql.$sql2);
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
					$query->bindvalue(":date$loopcount",date("Y-m-d H:i:s",$timestamp));
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
			$this->insertlog("Update: " . $timestamp . " " . print_r($inserted,true));
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
	
	public function updateHistory($insertrow,$timestamp){
		$sql ="UPDATE `gl_history` ";
		$sql.="SET `Timestamp` = :date, "; //Timestamp  2015/09/18 22:08:55
		$sql.=" `Game` = :title, "; //Game (Name)
		$sql.=" `System` = :system, "; //System
		$sql.=" `Data` = :data, "; //Data
		$sql.=" `Time` = :hours, "; //Time
		$sql.=" `Notes` = :notes, "; //Notes
		$sql.=" `RowType` = :source, "; //Source / RowType
		$sql.=" `Achievements` = :achievements, "; //Achivements
		$sql.=" `Status` = :status, "; //Status
		$sql.=" `Review` = :review, "; //Review
		$sql.=" `BaseGame` = :basegame, "; //BaseGame
		$sql.=" `kwMinutes` = :minutes, "; //Keyword Minutes
		$sql.=" `kwIdle` = :idle, "; //Keyword Idle
		$sql.=" `kwCardFarming` = :cardfarming, "; //Keyword Card Farming
		$sql.=" `kwCheating` = :cheating, "; //Keyword Cheating
		$sql.=" `kwBeatGame` = :beatgame, "; //Keyword Beat Game
		$sql.=" `kwShare` = :share, "; //Keyword Share
		$sql.=" `GameID` = :gameid"; //GameID
		$sql.=" WHERE `gl_history`.`HistoryID` = :insertid";
		
		$query = $this->getConnection()->prepare($sql);
		
		$query->bindvalue(':date',date("Y-m-d H:i:s",$timestamp ));
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
			//return "Record updated successfully<br>";
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