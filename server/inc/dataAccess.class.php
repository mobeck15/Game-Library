<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";

class dataAccess {
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
		} catch (exception $e){
			trigger_error("Database connection failed: ". $e->getMessage() . "\n"); // @codeCoverageIgnore
		}
		
		return $this->dbConnection;
	}
	
	public function closeConnection(){
		$this->dbConnection=null;
	}
	
	public function getPurchases($transactionid=null){
		$filter="";
		if(isset($transactionid)){
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
}