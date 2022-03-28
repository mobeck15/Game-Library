<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";

class dataAccess {
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
			if ($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$maxID=$row['HistoryID']+1;
			}
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

	public function insertHistory($datarow,$timestamp){
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
		foreach($datarow as $insertrow){
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
					$sql2.="'" . date("Y-m-d H:i:s",strtotime($conn->real_escape_string($timestamp))) ."', "; //Timestamp  2015/09/18 22:08:55
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
		
		$query = $this->getConnection()->prepare($sql.$sql2);
		
		//$query->bindvalue(1,$transactionid);
		//$query->bindvalue(2,$transactionid);
		
		if ($query->execute() === TRUE) {
			echo "Record updated successfully<br>";
			$this->insertlog($sql.$sql2);
		} else {
			trigger_error( "Error Running Query: " . $sql.$sql2 ,E_USER_NOTICE   );
		}
	}
	
	public function insertlog($query) {
		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $query.";\r\n", FILE_APPEND | LOCK_EX);
	}
	
	public function updateHistory($insertrow,$timestamp){
		$sql ="UPDATE `gl_history` ";
		$sql.="SET `Timestamp` = '" . date("Y-m-d H:i:s",strtotime($conn->real_escape_string($timestamp))) ."', "; //Timestamp  2015/09/18 22:08:55
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
		
		$query = $this->getConnection()->prepare($sql);
		
		//$query->bindvalue(1,$transactionid);
		//$query->bindvalue(2,$transactionid);
		
		if ($query->execute() === TRUE) {
			echo "Record updated successfully<br>";
			$this->insertlog($sql);
		} else {
			trigger_error( "Error Running Query: " . $sql ,E_USER_NOTICE   );
		}
	}

	public function isGameStarted() {
		$maxID=1;
		$query=$this->getConnection()->prepare("SELECT * FROM `gl_history` order by `Timestamp` DESC Limit 1");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$query2=$this->getConnection()->prepare("SELECT count(*) as count FROM `gl_history` where `GameID`= ? AND `Data`='Start/Stop' order by `Timestamp` DESC");
				$query2->bindvalue(1,$row['GameID']);
				$query2->execute();
				$row2 = $result->fetch_assoc();
				if($row2['count'] % 2 == 0) {
					//$GameStarted=false;
					return false;
				} else {
					//$_GET['GameID']=$row['GameID'];
					//$GameStarted=true;
					return $row['GameID'];
				}
			}
		}
	}
}