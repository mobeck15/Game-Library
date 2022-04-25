<?php
declare(strict_types=1);

//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";
require_once $GLOBALS['rootpath']."/ext/simple_html_dom.php";

class SteamScrape
{
	public $pageExists=true;
	private $steamGameID;
	private $curlHandle;
	private $rawPageText;
	private $htmldom;
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

	public function getdom() {
		if($this->htmldom === null) {
			$this->htmldom = str_get_html($this->getStorePage());
		}
		return $this->htmldom;
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
		$this->htmldom=str_get_html($this->rawPageText);
		
		if($this->getPageTitle()=="Welcome to Steam"){
			$this->pageExists=false;
			$this->rawPageText=" ";
			$this->htmldom=str_get_html(" ");
			return $this->htmldom;
		}
		
		return $result;
	}
	
	private function getPage($url){
		if($url===null) {
			return null;
		}
		
		/* */
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
		/* */
		//$result = file_get_html($url, false)->outertext;
		return $result;
	}
	
	public function getPageTitle(){
		if($this->pageTitle <> null) {
			return $this->pageTitle;
		}
		
		$this->pageTitle="";
		$search_results = $this->getdom()->find("title");
		if (isset($search_results[0])) {
			$this->pageTitle = $search_results[0]->innertext;
		}
		
		return $this->pageTitle;
	}
	
	public function getDescription(){
		if($this->description <> null) {
			return $this->description;
		}
		
		$this->description = "";
		//TODO: fatal error when viewing GameMaker Studio Pro (id=1343) Call to a member function find() on bool
		$dom=$this->getdom();
		$search_results = $dom->find(".game_description_snippet");
		if(isset($search_results[0])){
			$this->description = trim($search_results[0]->innertext);
		}

		return $this->description;
	}
	
	public function getTags(){
		if(count($this->keywords) > 0) {
			return $this->keywords;
		}

		$tags=array();
		$search_results = $this->getdom()->find(".glance_tags a");
		foreach ($search_results as $result) {
			$tags[strtolower(trim($result->innertext))] = trim($result->innertext);
		}
		$this->keywords=$tags;
		return $this->keywords;
	}
	
	public function getTagList() {
		return implode(",", $this->getTags());
	}
	
	function getDetails(){
		if(count($this->details) > 0) {
			return $this->details;
		}
		
		$details=array();
		$search_results = $this->getdom()->find(".game_area_details_specs_ctn .label");
		foreach ($search_results as $result) {
			$details[strtolower(trim($result->innertext))] = trim($result->innertext);
		}
		$this->details=$details;

		return $this->details;
	}

	public function getDetailList() {
		return implode(",", $this->getDetails());
	}
	
	function getReview(){
		if($this->review <> null) {
			return $this->review;
		}
		
		$search_results = $this->getdom()->find(".responsive_reviewdesc");
		if(isset($search_results[1])) {
			$review = $search_results[1]->innertext;
		} elseif (isset($search_results[0])) {
			$review = $search_results[0]->innertext;
		} else {
			$this->review="";
			return $this->review;
		}
		$review=trim($review);
		$pctpos=strpos($review,"%");
		$review=substr($review,1,$pctpos-1);
		
		$this->review = trim($review);
		return $this->review;
	}

	function getDeveloper(){
		if($this->developer <> null) {
			return $this->developer;
		}
		//TODO: Update to read multiple developer entries and return an array.
		
		$developers=array();
		$this->developer="";
		$search_results = $this->getdom()->find("#developers_list a");
		foreach ($search_results as $result) {
			$developers[] = trim($result->innertext);
		}
		$this->developer=implode(", ",$developers);
		if (count($developers)==0) {
			trigger_error("No data found for : Developer",E_USER_NOTICE );
		}
		return $this->developer;
	}

	function getPublisher(){
		if($this->publisher <> null) {
			return $this->publisher;
		}
		//TODO: This getPublisher loop take way too long (2 seconds) and may be making viewgame.php take up to 30 seconds to load.
		$eles = $this->getdom()->find('*');
		$i=0;
		$publishers=array();
		$this->publisher="";
		foreach($eles as $e) {
			if($e->innertext == 'Publisher:') {
				$search_results = $e->parent->find("a");
				foreach ($search_results as $result) {
					$publishers[] = trim($result->innertext);
				}
				break;
			}
		}
		$this->publisher=implode(", ",$publishers);
		if (count($publishers)==0) {
			trigger_error("No data found for : Publisher",E_USER_NOTICE );
		}
		return $this->publisher;
	}

	function getReleaseDate(){
		if($this->releaseDate <> null) {
			return $this->releaseDate;
		}
		
		$search_results = $this->getdom()->find(".date");
		$this->releaseDate = "";
		if (isset($search_results[0])) {
			$this->releaseDate = $search_results[0]->innertext;
		}
		
		return $this->releaseDate;
	}

	function getGenre(){
		if(count($this->genre) > 0) {
			return $this->genre;
		}
		//TODO: This getGenre loop take way too long (2 seconds) and may be making viewgame.php take up to 30 seconds to load.
		$eles = $this->getdom()->find('*');
		$i=0;
		$genres=array();
		foreach($eles as $e) {
			if($e->innertext == 'Genre:') {
				$search_results = $e->parent->find(" span a");
				foreach ($search_results as $result) {
					$genres[strtolower(trim($result->innertext))] = trim($result->innertext);
				}
				break;
				echo "<br>";
			}
		}

		$this->genre=$genres; 
		return $this->genre;
	}
	
	public function getGenreList() {
		return implode(",", $this->getGenre());
	}

}