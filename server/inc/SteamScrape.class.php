<?php
//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";
require_once $GLOBALS['rootpath']."/ext/simple_html_dom.php";

class SteamScrape
{
	private $pageExists=true;
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
		$this->htmldom=file_get_html($url, false);
		
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
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->pageTitle <> null) {
			return $this->pageTitle;
		}
		/* */
		$pattern='/<title>(.*)<\/title>/';
		$pageTitle= preg_match($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$pageTitle=$matches[1];
		} else {
			$pageTitle="";
		}
		$this->pageTitle=$pageTitle;
		/* */
		//$search_results = $this->htmldom->find("title");
		//$this->pageTitle = $search_results[0]->innertext;
		
		return $this->pageTitle;
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
		//if($this->rawPageText === null) {
		//	$this->getStorePage();
		//}
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
		
		/*
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
		*/
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
		if($this->review <> null) {
			return $this->review;
		}
		
		$search_results = $this->getdom()->find(".responsive_reviewdesc");
		if(isset($search_results[1])) {
			$review = $search_results[1]->innertext;
		} elseif (isset($search_results[0])) {
			$review = $search_results[0]->innertext;
		} else {
			$review = "";
		}
		
		$this->review = trim(substr(trim($review),1,strpos(trim($review),"%")-1));
		return $this->review;
	}

	function getDeveloper(){
		if($this->developer <> null) {
			return $this->developer;
		}
		//TODO: Update to read multiple developer entries.
		
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
		if(count($this->genre) > 0) {
			return $this->genre;
		}
		
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