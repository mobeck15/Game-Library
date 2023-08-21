<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/gettoplist.inc.php";

class dataSet {
	private $calculations;
	private $topBundles;
	private $settings;
	private $history;
	private $items;
	
	public function getCalculations(){
		if(!isset($this->calculations)){
			$this->calculations = reIndexArray(getCalculations(),"Game_ID");
		}
		return $this->calculations;
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

}