<?php
//https://steamcommunity.com/dev/apiterms
//You are limited to one hundred thousand (100,000) calls to the Steam Web API per day. 

//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";

class SteamAPI
{
	private $SteamAPIwebkey=null;
	private $SteamProfileID=null;
	private $steamGameID=null;
	private $curlHandle=null;
	
	private $GetOwnedGamesURL=null;
	private $GetRecentlyPlayedGamesURL=null;
	private $GetPlayerAchievementsURL=null;
	private $GetUserStatsForGameURL=null;
	private $GetGameNewsURL=null;
	private $GetSchemaForGameURL=null;
	private $GetAppDetailsURL=null;
	private $GetSteamPICSURL=null;

    public function __construct($steamGameID=null,$curlHandle=null) {
		$this->steamGameID=$steamGameID;
		$this->curlHandle = $curlHandle ?? new CurlRequest("");
		
		$this->setAuth();
		$this->setApiUrls();
    }
	
	public function setSteamGameID($steamGameID){
		$this->steamGameID=$steamGameID;
		$this->setApiUrls();
	}
	
	private function setAuth() {
		require $GLOBALS['rootpath']."/inc/authapi.inc.php";
        $this->SteamAPIwebkey = $SteamAPIwebkey;
        $this->SteamProfileID = $SteamProfileID;
	}

    private function setApiUrls() {
		$newscount=5;
		$newslength=500;
		$this->GetOwnedGamesURL="http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$this->SteamAPIwebkey."&steamid=".$this->SteamProfileID."&format=json";
		$this->GetRecentlyPlayedGamesURL="http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=".$this->SteamAPIwebkey."&steamid=".$this->SteamProfileID."&format=json";
		
		if($this->steamGameID <> null) {
			$this->GetPlayerAchievementsURL="http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=".$this->steamGameID."&key=".$this->SteamAPIwebkey."&steamid=".$this->SteamProfileID;
			$this->GetUserStatsForGameURL="http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=".$this->steamGameID."&key=".$this->SteamAPIwebkey."&steamid=".$this->SteamProfileID;
			$this->GetGameNewsURL="http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=".$this->steamGameID."&count=".$newscount."&maxlength=".$newslength."&format=json";
			$this->GetSchemaForGameURL="http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?key=".$this->SteamAPIwebkey."&appid=".$this->steamGameID;
			$this->GetAppDetailsURL="http://store.steampowered.com/api/appdetails/?appids=".$this->steamGameID;
			$this->GetSteamPICSURL="https://steampics-mckay.rhcloud.com/info?apps=".$this->steamGameID;
		}
	}

	public function GetSteamAPI($APIname){
		switch ($APIname) {
			case "GetOwnedGames":
				return $this->CallAPI($this->GetOwnedGamesURL);
			case "GetRecentlyPlayedGames":
				return $this->CallAPI($this->GetRecentlyPlayedGamesURL);
			case "GetPlayerAchievements":
				return $this->CallAPI($this->GetPlayerAchievementsURL);
			case "GetUserStatsForGame":
				return $this->CallAPI($this->GetUserStatsForGameURL);
			case "GetGameNews":
				return $this->CallAPI($this->GetGameNewsURL);
			case "GetSchemaForGame":
				return $this->CallAPI($this->GetSchemaForGameURL);
			case "GetAppDetails":
				return $this->CallAPI($this->GetAppDetailsURL);
			case "GetSteamPICS":
				return $this->CallAPI($this->GetSteamPICSURL);
			default:
				return null;
		}
	}
	
	private function CallAPI($url){
		if($url===null) {
			return null;
		}
		//$curlHandle = $curlHandle ?? new CurlRequest("");

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
		//$curlHandle->close();

		$resultarray=json_decode($result, true);
		unset($result);
		
		return $resultarray;
	}
	
	function __destruct() {
		$this->curlHandle->close();
	}
}