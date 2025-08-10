<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getTopList.inc.php";
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
		$history = null,
		$items = null
	) {
		$this->calculations = $calculations;
		$this->topBundles = $topBundles;
		$this->purchases = $purchases;
		$this->settings = $settings;
		$this->history = $history;
		$this->items = $items;
	}
	
	public static function merge(self $first, self $second): self {
		$merged = new self();
		$refClass = new \ReflectionClass(self::class);

		foreach ($refClass->getProperties() as $prop) {
			$prop->setAccessible(true);

			$value = $prop->getValue($first); // take from first
			$secondValue = $prop->getValue($second);

			// Override if $second has a non-null value
			if ($secondValue !== null) {
				$value = $secondValue;
			}

			$prop->setValue($merged, $value);
		}

		return $merged;
	}
	
	public function getCalculations(){
		if(!isset($this->calculations)){
			$this->calculations = reIndexArray(getCalculations(),"Game_ID");
		}
		return $this->calculations;
	}

	public function getPurchases(){
		if(!isset($this->purchases)){
			$data = new Purchases();
			$this->purchases = $data->getPurchases();
		}
		return $this->purchases;
	}
	
	public function getHistory(){
		if(!isset($this->history)){
			$this->history = getHistoryCalculations();
		}
		return $this->history;
	}

	public function getTopBundles(){
		if(!isset($this->topBundles)){
			$this->topBundles = getTopList('Bundle',null,$this->getCalculations());
		}
		return $this->topBundles;
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