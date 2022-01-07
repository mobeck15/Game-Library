<?php
//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";

class SteamScrape
{
	private $pageExists=true;
	private $steamGameID;
	private $curlHandle;
	private $rawPageText;
	private $pageTitle;
	private $description;
	private $keywords=array();
	private $details=array();
	private $review;
	private $developer;
	private $publisher;
	private $releaseDate;
	private $genre=array();
	
    public function __construct($steamGameID=null) {
		//$this->curlHandle = $curlHandle ?? new CurlRequest("");
		$this->steamGameID=$steamGameID;
	}

	public function getStorePage() {
		if($this->steamGameID===null) {
			return null;
		}
		if($this->rawPageText <> null) {
			return $this->rawPageText;
		}
		
		//Steam Store Page
		$url="http://store.steampowered.com/app/".$this->steamGameID;
		
		$result = $this->getPage($url);
		
		$this->rawPageText=$result;
		
		if($this->getPageTitle()=="Welcome to Steam"){
			$this->pageExists=false;
			$this->rawPageText=false;
			return false;
		}
		
		return $result;
	}
	
	private function getPage($url){
		if($url===null) {
			return null;
		}
		$this->curlHandle = $this->curlHandle ?? new CurlRequest("");

		//Follow HTML redirects
		$this->curlHandle->setOption(CURLOPT_FOLLOWLOCATION, true); 
		// set cookie for age verification
		$this->curlHandle->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
		// Disable SSL verification
		$this->curlHandle->setOption(CURLOPT_SSL_VERIFYPEER, false); 
		// Will return the response, if false it print the response
		$this->curlHandle->setOption(CURLOPT_RETURNTRANSFER, true); 
		$this->curlHandle->setOption(CURLOPT_URL, $url); 
		$result = $this->curlHandle->execute();
		$this->curlHandle->close();

		return $result;
	}
	
	public function getPageTitle(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->pageTitle <> null) {
			return $this->pageTitle;
		}
		
		$pattern='/<title>(.*)<\/title>/';
		$pageTitle= preg_match($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$pageTitle=$matches[1];
		} else {
			$pageTitle="";
		}
		$this->pageTitle=$pageTitle;
		return $pageTitle;
	}
	
	public function getDescription(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->description <> null) {
			return $this->description;
		}
		
		$pattern='/game_description_snippet">\s*(.*?)\s*<\/div>/';
		$description= preg_match($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$description=$matches[1];
		} else {
			$description="";
		}
		$this->description=$description;
		return $description;
	}
	
	public function getTags(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if(count($this->keywords) > 0) {
			return $this->keywords;
		}
		
		$start=strpos($this->rawPageText,'class="glance_tags popular_tags"');
		$stop=strpos($this->rawPageText,'class="app_tag add_button"');
		$rawtaglist=substr($this->rawPageText,$start,$stop-$start);
		$pattern="/\t+([^\t].*?)\t+</";
		$taglistmatches= preg_match_all ($pattern,$rawtaglist,$matches);
		
		$allkeywordarray=array();
		foreach($matches[1] as $steamkeyword){
			$allkeywordarray[strtolower($steamkeyword)]=$steamkeyword;
		}
		$this->keywords=$allkeywordarray;
		return $allkeywordarray;
	}
	
	public function getTagList() {
		return implode(",", $this->getTags());
	}
	
	function getDetails(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if(count($this->details) > 0) {
			return $this->details;
		}
		
		$pattern="/\"label\">(.+?)</";
		$featurematches= preg_match_all ($pattern,$this->rawPageText,$matches);
		
		$allkeywordarray=array();
		foreach($matches[1] as $steamfeature){
			$allkeywordarray[strtolower($steamfeature)]=$steamfeature;
		}
		$this->details=$allkeywordarray;
		return $allkeywordarray;
	}

	public function getDetailList() {
		return implode(",", $this->getDetails());
	}
	
	function getReview(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->review <> null) {
			return $this->review;
		}
		
		$pattern="/user_reviews_summary_row.+\"([0-9]*)\%/";
		$ratingmatches= preg_match ($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])) {
			$newsteamrating=$matches[1];
		} else {
			$newsteamrating="";
		}
		$this->review=$newsteamrating;
		return $newsteamrating;
	}

	function getDeveloper(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->developer <> null) {
			return $this->developer;
		}
		
		//TODO: Update to read multiple developer entries.
		$pattern="/\/developer\/(?:.*?)>(.*?)<\/a>/";
		$Devmatch= preg_match ($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])) {
			$Developer=$matches[1];
		} else {
			$Developer="";
			trigger_error("No data found for : Developer",E_USER_NOTICE );
		}
		$this->developer=$Developer;
		return $Developer;
	}

	function getPublisher(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->publisher <> null) {
			return $this->publisher;
		}
		
		//TODO: Update to read multiple publisher entries.
		$pattern="/\/publisher\/(?:.*?)>(.*?)<\/a>/";
		$Pubmatch= preg_match ($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$Publisher=$matches[1];
		} else {
			$Publisher="";
			trigger_error("No data found for : Publisher",E_USER_NOTICE );
		}
		$this->publisher=$Publisher;
		return $Publisher;
	}

	function getReleaseDate(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->releaseDate <> null) {
			return $this->releaseDate;
		}
		
		$pattern="/<b>Release Date:<\/b>\s*(.*?)<br>/";
		$Datematch= preg_match ($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$PubDate=$matches[1];
		} else {
			$PubDate="";
		}
		$this->releaseDate=$PubDate;
		return $PubDate;
	}

	function getGenre(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if(count($this->genre) > 0) {
			return $this->genre;
		}
		
		$pattern='/408">(.+?)<\/a/';
		$genrematches= preg_match_all ($pattern,$this->rawPageText,$matches);
		
		$allkeywordarray=array();
		foreach($matches[1] as $steamgenre){
			$allkeywordarray[strtolower($steamgenre)]=$steamgenre;
		}
		$this->details=$allkeywordarray;
		return $allkeywordarray;
	}
	
	public function getGenreList() {
		return implode(",", $this->getGenre());
	}

}