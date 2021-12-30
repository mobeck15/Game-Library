<?php
//if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";

class SteamAPI
{
	private $SteamAPIwebkey=null;
	private $SteamProfileID=null;
	private $steamGameID=null;
	
	private $GetOwnedGamesURL=null;
	private $GetRecentlyPlayedGamesURL=null;
	private $GetPlayerAchievementsURL=null;
	private $GetUserStatsForGameURL=null;
	private $GetGameNewsURL=null;
	private $GetSchemaForGameURL=null;
	private $GetAppDetailsURL=null;
	private $GetSteamPICSURL=null;

    public function __construct($steamGameID=null) {
		$this->steamGameID=$steamGameID;
		
		$this->setAuth();
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

	public function GetSteamAPI($APIname,$curlHandle=null){
		switch ($APIname) {
			case "GetOwnedGames":
				return $this->CallAPI($this->GetOwnedGamesURL,$curlHandle);
			case "GetRecentlyPlayedGames":
				return $this->CallAPI($this->GetRecentlyPlayedGamesURL,$curlHandle);
			case "GetPlayerAchievements":
				return $this->CallAPI($this->GetPlayerAchievementsURL,$curlHandle);
			case "GetUserStatsForGame":
				return $this->CallAPI($this->GetUserStatsForGameURL,$curlHandle);
			case "GetGameNews":
				return $this->CallAPI($this->GetGameNewsURL,$curlHandle);
			case "GetSchemaForGame":
				return $this->CallAPI($this->GetSchemaForGameURL,$curlHandle);
			case "GetAppDetails":
				return $this->CallAPI($this->GetAppDetailsURL,$curlHandle);
			case "GetSteamPICS":
				return $this->CallAPI($this->GetSteamPICSURL,$curlHandle);
			default:
				return null;
		}
	}
	
	private function CallAPI($url,$curlHandle=null){
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

		$resultarray=json_decode($result, true);
		unset($result);
		
		return $resultarray;
	}
}