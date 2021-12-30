<?php
//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";

class SteamScrape
{
	private $steamGameID=null;
	private $rawPageText=null;
	private $pageExists=true;
	private $PageTitle=null;
	
    public function __construct($steamGameID=null) {
		$this->steamGameID=$steamGameID;
	}

	public function getStorePage($curlHandle=null) {
		if($this->steamgameid===null) {
			return null;
		}
		if($this->rawPageText <> null) {
			return $this->rawPageText;
		}
		
		//Steam Store Page
		$url="http://store.steampowered.com/app/".$this->steamgameid;
		
		$result = getPage($url,$curlHandle);
		
		$this->rawPageText=$result;
		
		if(getPageTitle()=="Welcome to Steam"){
			$this->pageExists=false;
			return false;
		}
		
		return $result;
	}
	
	private function getPage($url,$curlHandle=null){
		if($url===null) {
			return null;
		}
		$curlHandle = $curlHandle ?? new CurlRequest("");

		//Follow HTML redirects
		$curlHandle->setOption(CURLOPT_FOLLOWLOCATION, true); 
		// set cookie for age verification
		$curlHandle->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
		// Disable SSL verification
		$curlHandle->setOption(CURLOPT_SSL_VERIFYPEER, false); 
		// Will return the response, if false it print the response
		$curlHandle->setOption(CURLOPT_RETURNTRANSFER, true); 
		$curlHandle->setOption(CURLOPT_URL, $url); 
		$result = $curlHandle->execute();
		$curlHandle->close();

		return $result;
	}
	
	public function getPageTitle(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		if($this->PageTitle <> null) {
			return $this->PageTitle;
		}
		
		$pattern='/<title>(.*)<\/title>/';
		$PageTitle= preg_match($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$PageTitle=$matches[1];
		} else {
			$PageTitle="";
		}
		$this->PageTitle=$PageTitle;
		return $PageTitle;
	}
	
	public function getDescription(){
		if($this->rawPageText === null) {
			$this->getStorePage();
		}
		$pattern='/game_description_snippet">\s*(.*?)\s*<\/div>/';
		$description= preg_match($pattern,$this->rawPageText,$matches);
		if(isset($matches[1])){
			$description=$matches[1];
		} else {
			$description="";
		}
		unset($matches);
		
		return $description;
	}
}