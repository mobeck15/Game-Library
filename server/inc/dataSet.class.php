<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getTopList.class.php";
include_once $GLOBALS['rootpath']."/inc/keywords.class.php";

class dataSet {
	private $calculations;
	private $topBundles;
	private $purchases;
	private $settings;
	private $keywords;
	private $history;
	private $items;
	
	public function __construct(
		$calculations = null,
		$topBundles = null,
		$purchases = null,
		$settings = null,
		$keywords = null,
		$history = null,
		$items = null
	) {
		$this->calculations = $calculations;
		$this->topBundles = $topBundles;
		$this->purchases = $purchases;
		$this->settings = $settings;
		$this->keywords = $keywords;
		$this->history = $history;
		$this->items = $items;
	}
	
	public function getCalculations(){
		if(!isset($this->calculations)){
			$this->calculations = reIndexArray(getCalculations(),"Game_ID");
		}
		return $this->calculations;
	}

	public function getPurchases(){
		if(!isset($this->purchases)){
			$data = $this->createPurchasesInstance();
			$this->purchases = $data->getPurchases();
		}
		return $this->purchases;
	}
	
	protected function createPurchasesInstance()
	{
		return new Purchases();
	}
	
	public function getHistory(){
		if(!isset($this->history)){
			$this->history = getHistoryCalculations();
		}
		return $this->history;
	}

	public function getTopBundles(){
		if(!isset($this->topBundles)){
			$data = $this->createTopBundlesInstance();
			$this->topBundles = $data->buildTopListArray('Bundle');
		}
		return $this->topBundles;
	}
	
	protected function createTopBundlesInstance()
	{
		return new TopList($this);
	}

	public function getSettings(){
		if(!isset($this->settings)){
			$this->settings = getsettings();
		}
		return $this->settings;
	}
	
	public function getAllItems(){
		if(!isset($this->items)){
			$this->items = getAllItems();
		}
		return $this->items;
	}

	public function getKeywords(){
		if(!isset($this->keywords)){
			$this->keywords = new Keywords();
		}
		return $this->keywords;
	}
}