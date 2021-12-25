<?php
//https://steamcommunity.com/dev/apiterms
//You are limited to one hundred thousand (100,000) calls to the Steam Web API per day. 

if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

if(!isset($GLOBALS['rootpath'])) {$GLOBALS['rootpath']="..";}
require_once $GLOBALS['rootpath']."/inc/CurlRequest.class.php";

function GetOwnedGames($ch=null) {
	$ch = $ch ?? new CurlRequest("");
	require $GLOBALS['rootpath']."/inc/authapi.inc.php";
	//GetOwnedGames
	$url="http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$SteamAPIwebkey."&steamid=".$SteamProfileID."&format=json";

	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();

	$resultarray3=json_decode($result, true);
	
	return $resultarray3;
}

function GetRecentlyPlayedGames($ch=null) {
	$ch = $ch ?? new CurlRequest("");
	require $GLOBALS['rootpath']."/inc/authapi.inc.php";

	//GetRecentlyPlayedGames
	$url="http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=".$SteamAPIwebkey."&steamid=".$SteamProfileID."&format=json";

	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();

	$resultarray=json_decode($result, true);
	unset($result);
	
	return $resultarray;
}

function GetPlayerAchievements($steamgameid,$ch=null) {
	$ch = $ch ?? new CurlRequest("");
	require $GLOBALS['rootpath']."/inc/authapi.inc.php";
	
	//GetUserStatsForGame
	$url="http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=".$steamgameid."&key=".$SteamAPIwebkey."&steamid=".$SteamProfileID;
	
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();
	
	$userstatsarray=json_decode($result, true);	
	
	return $userstatsarray;
}

function GetUserStatsForGame($steamgameid,$ch=null) {
	$ch = $ch ?? new CurlRequest("");
	require $GLOBALS['rootpath']."/inc/authapi.inc.php";
	
	//GetUserStatsForGame
	$url="http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=".$steamgameid."&key=".$SteamAPIwebkey."&steamid=".$SteamProfileID;
	
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();
	
	$userstatsarray=json_decode($result, true);	
	
	return $userstatsarray;
}

function GetGameNews($steamgameid,$newscount=5,$length=500,$ch=null) {
	$ch = $ch ?? new CurlRequest("");
	
	//Get Game News
	$url="http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=".$steamgameid."&count=".$newscount."&maxlength=".$length."&format=json";
	
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();
	
	$newsarray=json_decode($result, true);
	
	return $newsarray;
}

function GetSchemaForGame($steamgameid, $ch=null) {
	$ch = $ch ?? new CurlRequest("");

	require $GLOBALS['rootpath']."/inc/authapi.inc.php";
	//GetSchemaForGame
	$url="http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?key=".$SteamAPIwebkey."&appid=".$steamgameid;

	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();

	$resultarray=json_decode($result, true);

	return $resultarray;
}

function GetAppDetails($steamgameid,$ch=null) {
	$ch = $ch ?? new CurlRequest("");

	//unoficial app id information
	$url="http://store.steampowered.com/api/appdetails/?appids=".$steamgameid;

	//Follow HTML redirects
	$ch->setOption(CURLOPT_FOLLOWLOCATION, true); 
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();

	$appdetails=json_decode($result, true);

	return $appdetails;
}

function GetSteamPICS($steamgameid,$ch=null) {
	$ch = $ch ?? new CurlRequest("");

	//https://github.com/DoctorMcKay/steam-pics-api
	//https://steampics-mckay.rhcloud.com/info?apps=252490&prettyprint=1
	$url="https://steampics-mckay.rhcloud.com/info?apps=".$steamgameid;
	
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();

	$steampics=json_decode($result, true);

	return $steampics;
}

function scrapeSteamStore($steamgameid,$ch=null) {
	$ch = $ch ?? new CurlRequest("");

	//Simulate steam down
	//return false;
	
	//Steam Store Page
	$url="http://store.steampowered.com/app/".$steamgameid;
	//Solution to age verification found on sourceforge: 
	//http://stackoverflow.com/questions/22140197/how-to-pass-age-verification-with-dom

	//Follow HTML redirects
	$ch->setOption(CURLOPT_FOLLOWLOCATION, true); 
	//time out if too long
	$ch->setOption(CURLOPT_TIMEOUT, 5); 
	// set cookie for age verification
	$ch->setOption(CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com"); 
	// Disable SSL verification
	$ch->setOption(CURLOPT_SSL_VERIFYPEER, false); 
	// Will return the response, if false it print the response
	$ch->setOption(CURLOPT_RETURNTRANSFER, true); 
	$ch->setOption(CURLOPT_URL, $url); 
	$result = $ch->execute();
	$ch->close();
	
	/* Return the Steam page for troubleshooting * /
	echo $url . "<p>";
	var_dump($result); 
	/* */
	
	//Returns false if there is no store page (defaults to steam homepage)
	//But this also makes the code skip the stamAPI calls to view achievements which could still be valid.
	if(getPageTitle($result)=="Welcome to Steam"){
		return false;
	}
	
	return $result;
}

function getPageTitle($source){
	$pattern='/<title>(.*)<\/title>/';
	$description= preg_match($pattern,$source,$matches);
	if(isset($matches[1])){
		$description=$matches[1];
	} else {
		$description="";
	}
	return $description;
}

function parse_game_description($source){

	//class="game_description_snippet"
	$pattern='/game_description_snippet">\s*(.*?)\s*<\/div>/';
	$description= preg_match($pattern,$source,$matches);
	if(isset($matches[1])){
		$description=$matches[1];
	} else {
		$description="";
	}
	unset($matches);
	
	return $description;
}

function parse_tags($source){
	//Searching for:
	//class="glance_tags popular_tags"
	$start=strpos($source,'class="glance_tags popular_tags"');
	$stop=strpos($source,'class="app_tag add_button"');
	$rawtaglist=substr($source,$start,$stop-$start);
	$pattern="/\t+([^\t].*?)\t+</";
	$taglistmatches= preg_match_all ($pattern,$rawtaglist,$matches);
	
	$allkeywordarray=array();
	$steamkeywordarray=$matches[1];
	$steamkeywordlist="";
	foreach($matches[1] as $steamkeyword){
		if($steamkeywordlist<>"") {$steamkeywordlist.=", ";}
		$steamkeywordlist.=$steamkeyword;
		$allkeywordarray[strtolower($steamkeyword)]=$steamkeyword;
	}
	unset($matches);
	
	return array(
		"list" =>$steamkeywordlist,
		"all"=>$allkeywordarray
	);
}

function parse_details($source){
	//Searching for:
	//class="game_area_details_specs"
	$pattern="/game_area_details_specs.*?name.*?>(.+?)</";
	$pattern="/\"label\">(.+?)</";
	$featurematches= preg_match_all ($pattern,$source,$matches);
	
	$steamfeaturearray=$matches[1];
	$steamfeaturelist="";
	$steamfeature=array();
	$allkeywordarray=array();
	foreach($matches[1] as $steamfeature){
		if($steamfeaturelist<>"") {$steamfeaturelist.=", ";}
		$steamfeaturelist.=$steamfeature;
		$allkeywordarray[strtolower($steamfeature)]=$steamfeature;
	}
	unset($matches);

	return array(
		"list" =>$steamfeaturelist,
		"all"=>$allkeywordarray
	);
}

function parse_reviews($source){
	//Regular expression to find user reviews on Steam Store page.
	//$pattern="/([0-9]+)% of the [0-9,]+ user reviews for/"; //Old pattern stopped working
	$pattern="/user_reviews_summary_row.+\"([0-9]*)\%/";
	
	$ratingmatches= preg_match ($pattern,$source,$matches);
	//var_dump($matches);		
	
	$newsteamrating="";
	if(isset($matches[1])) {
		$newsteamrating=$matches[1];
	} 
	unset($matches);	
	
	return $newsteamrating;
}

function parse_developer($source){
	//TODO: Update to read multiple developer entries.
	//class="details_block"
	$pattern="/Developer:<\/b>\s*<.*?>(.*?)<\/a>/";
	$pattern="/\/developer\/(?:.*?)>(.*?)<\/a>/";
	$Devmatch= preg_match ($pattern,$source,$matches);
	if(isset($matches[1])) {
		$Developer=$matches[1];
	} else {
		$Developer="";
		trigger_error("No data found for : Developer");
	}
	unset($matches);	
	
	return $Developer;
}

function parse_publisher($source){
	$pattern="/Publisher:<\/b>\s*<.*?>(.*?)<\/a>/";
	$pattern="/\/publisher\/(?:.*?)>(.*?)<\/a>/";
	$Pubmatch= preg_match ($pattern,$source,$matches);
	if(isset($matches[1])){
		$Publisher=$matches[1];
	} else {
		$Publisher="";
		trigger_error("No data found for : Publisher");
	}
	unset($matches);
	
	return $Publisher;
}

function parse_releasedate($source){
	$pattern="/<b>Release Date:<\/b>\s*(.*?)<br>/";
	$Datematch= preg_match ($pattern,$source,$matches);
	$PubDate=$matches[1];
	unset($matches);
	
	return $PubDate;
}

function parse_genre($source){
	$pattern1="/Genre:(?:.*?)<a(?:.*?)\">(.*?)<\/a>/";
	$genrematch= preg_match ($pattern1,$source,$matches);
	$pattern2="/\">(.*?)<\/a>/";
	if(isset($matches[1])){
		//$genrematch= preg_match_all ($pattern2,$matches1[1],$matches);
		$GenreArray=$matches[1];
		$steamgenrelist="";
		$allkeywordarray=array();
		/*
		foreach($matches[1] as $steamgenre){
			if($steamgenrelist<>"") {$steamgenrelist.=", ";}
			$steamgenrelist.=$steamgenre;
			$allkeywordarray[strtolower($steamgenre)]=$steamgenre;
		}
		*/
		$steamgenrelist.=$matches[1];
		$allkeywordarray[strtolower($matches[1])]=$matches[1];
	} else {
		trigger_error("No data found for : Steam Genre");
	}
	unset($matches);
	
	return array(
		"list" =>$steamgenrelist,
		"all"=>$allkeywordarray
	);
}

function formatAppDetails($appdetails,$verbose=true){
	//var_dump($appdetails);
	
	$output  = "Type: " . $appdetails['data']['type'] . "<br>";
	$output .= "Name: " . $appdetails['data']['name'] . "<br>";
	$output .= "Required Age: " . $appdetails['data']['required_age'] . "<br>";
	$output .= "Free Game: " . boolText($appdetails['data']['is_free']) . "<br>";
	
	if(isset($appdetails['data']['controller_support'])){
		$output .= "Controller Support: " . $appdetails['data']['controller_support'] . "<br>";
	} 
	
	if(isset($appdetails['data']['dlc'])){
		$output .= "<ul>DLC: ";
		foreach($appdetails['data']['dlc'] as $dlc){
			$output .= "<li>$dlc</li>";
		}
		$output .= "</ul>";
	}
	
	if($verbose==true) {
		$output .= "Description: " . $appdetails['data']['detailed_description'] . "<br>";
		if($appdetails['data']['detailed_description']<>$appdetails['data']['about_the_game']){
			$output .= "Description: " . $appdetails['data']['about_the_game'] . "<br>";
		} 
	}
	$output .= "Supported Languages: " . $appdetails['data']['supported_languages'] . "<br>";
	if(isset($appdetails['data']['reviews'])) { $output .= "Reviews: " . $appdetails['data']['reviews'] . "<br>"; }
	if($verbose==true) {
		$output .= "Header Image: <img src='" . $appdetails['data']['header_image'] . "'><br>";
	}
	$output .= "Web Site: <a href='" . $appdetails['data']['website'] . "'>" . $appdetails['data']['website'] . "</a><br>";
	foreach($appdetails['data']['pc_requirements'] as $level => $req){
		$output .= "PC Requirements ($level): $req<br>";
	} 
	unset($req);
	unset($level);
	
	foreach($appdetails['data']['mac_requirements'] as $level => $req){
		$output .= "Mac Requirements ($level): $req<br>";
	}
	unset($req);
	unset($level); 
	
	foreach($appdetails['data']['linux_requirements'] as $level => $req){ 
		//TODO: add CSS for all elements to make DIV containters for each and spacing.
		$output .= "<div style='padding-bottom: 20px;'>Linux Requirements ($level): $req</div>";
	}
	unset($req);
	unset($level);
	
	$output .= "Legal Notice: " . $appdetails['data']['legal_notice'] . "<br>";
	
	$output .= "<ul>Publishers: ";
	foreach($appdetails['data']['publishers'] as $pub){
		$output .= "<li>$pub</li>";
	}
	$output .= "</ul>";
	
	if(isset($appdetails['data']['demos'])){
		$output .= "<ul>Demos: ";
		foreach($appdetails['data']['demos'] as $demo){ 
			$output .= "<li>" . $demo['appid'].": ".$demo['description'] . "</li>";
		}
		$output .= "</ul>";
	}
	
	if(isset($appdetails['data']['price_overview'])) {
		$output .= "Price Overview: ";
		$output .= $appdetails['data']['price_overview']['currency']; 
		$output .= "$" . $appdetails['data']['price_overview']['initial']/100; 
		if($appdetails['data']['price_overview']['discount_percent']>0){
			 $output .= "-" . $appdetails['data']['price_overview']['discount_percent'] . "%";
			 $output .= "=$" . $appdetails['data']['price_overview']['final']/100; 
		}
		$output .= "<br>";
	}
	
	if(isset($appdetails['data']['packages'])){
		$output .= "<ul>Packages: ";
		foreach($appdetails['data']['packages'] as $package){
			$output .= "<li>".$package."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($appdetails['data']['package_groups'])){
		$output .= "Package Groups: ";
		foreach($appdetails['data']['package_groups'] as $group){
			$output .= "<br>Name: ".$group['name']."<br>";
			$output .= "Title: ".$group['title']."<br>";
			$output .= "description: ".$group['description']."<br>";
			$output .= "selection_text: ".$group['selection_text']."<br>";
			$output .= "save_text: ".$group['save_text']."<br>";
			$output .= "display_type: ".$group['display_type']."<br>";
			$output .= "is_recurring_subscription: ".$group['is_recurring_subscription']."<br>";
			if(isset($group['subs'])){
				foreach($group['subs'] as $subgroup){
					$output .= "<br>ID: ".$subgroup['packageid'] . "<br>";
					$output .= "Percent Savings: ".$subgroup['percent_savings_text'] . ", (".$subgroup['percent_savings'].")<br>";
					$output .= "Option Text: ".$subgroup['option_text'] . "<br>";
					$output .= "Option Description: ".$subgroup['option_description'] . "<br>";
					$output .= "can get free license: ".$subgroup['can_get_free_license'] . "<br>";
					$output .= "is free license: ".booltext($subgroup['is_free_license']) . "<br>";
					$output .= "price with discount: $".$subgroup['price_in_cents_with_discount']/100 . "<br>";
				}
			}
		}
		$output .= "<br>";
	}
	if(isset($appdetails['data']['platforms'])){
		$output .= "<ul>Platforms: ";
		foreach($appdetails['data']['platforms'] as $platform => $supported){
			$output .= "<li>".$platform.": ".booltext($supported)."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($appdetails['data']['metacritic'])){
		$output .= "metacritic: " . $appdetails['data']['metacritic']['score'] . "<a href='".$appdetails['data']['metacritic']['url']."' target='_blank'>link</a><br>";
	}
	if(isset($appdetails['data']['categories'])){
		$output .= "<ul>Categories: ";
		foreach($appdetails['data']['categories'] as $category){
			$output .= "<li>".$category['id'].": ".$category['description']."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($appdetails['data']['genres'])){
		$output .= "<ul>Genres: ";
		foreach($appdetails['data']['genres'] as $genre){
			$output .= "<li>".$genre['id'].": ".$genre['description']."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($appdetails['data']['screenshots']) && $verbose==true){
		$output .= "Screenshots: <br>";
		foreach($appdetails['data']['screenshots'] as $screenshot){
			$output .= "<a href='".$screenshot['path_full']."'><img src='".$screenshot['path_thumbnail']."></a>";
		}
		$output .= "<br><br>";
	}
	if(isset($appdetails['data']['movies']) && $verbose==true){
		$output .= "Movies: <br>";
		foreach($appdetails['data']['movies'] as $movie){
			//var_dump($movie);
			$output .= "<a href='".$movie['webm']['480']."'><img src='".$movie['thumbnail']." title='".$movie['name']."'></a>";
		}
		$output .= "<br><br>";
	}
	if(isset($appdetails['data']['recommendations'])){
		$output .= "<ul>recommendations: ";
		foreach($appdetails['data']['recommendations'] as $recommendation){
			//$output .= "<li>".$recommendation['total']."</li>";
			$output .= "<li>".$recommendation."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($appdetails['data']['achievements'])){
		$output .= "achievements: " . $appdetails['data']['achievements']['total'] . "<br>";
	}
	if(isset($appdetails['data']['release_date'])){
		$output .= "release_date: " . $appdetails['data']['release_date']['date'] . "<br>";
	}
	if(isset($appdetails['data']['support_info'])){
		$output .= "support_info: " . $appdetails['data']['support_info']['url'] . ", " . $appdetails['data']['support_info']['email'] . "<br>";
	}
	if(isset($appdetails['data']['background'])){
		$output .= "background: <a href='" . $appdetails['data']['background'] . "'>Link</a><br>";
	}
	
	return($output);
}

function formatSteamPics($steampics){
	// @codeCoverageIgnoreStart
	//function is unused and has been since 2020
	$output  = "ID: ". $steampics['apps'][$game['SteamID']]['appid']."<br>";
	$output .= "Change#: ". $steampics['apps'][$game['SteamID']]['change_number']."<br>";
	$output .= "name: ". $steampics['apps'][$game['SteamID']]['common']['name']."<br>";
	$output .= "type: ". $steampics['apps'][$game['SteamID']]['common']['type']."<br>";
	if(isset($steampics['apps'][$game['SteamID']]['common']['releasestate'])){
		$output .= "releasestate: ". $steampics['apps'][$game['SteamID']]['common']['releasestate']."<br>";
	}
	$output .= "logo: <img src='https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/".$game['SteamID']."/". $steampics['apps'][$game['SteamID']]['common']['logo'].".jpg'><br>";
	$output .= "logo_small: <img src='https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/".$game['SteamID']."/". $steampics['apps'][$game['SteamID']]['common']['logo_small'].".jpg'><br>";
	$output .= "clienticon: <img src='https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/".$game['SteamID']."/". $steampics['apps'][$game['SteamID']]['common']['clienticon'].".ico'><br>";
	//$output .= "clienttga: <img src='https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/".$game['SteamID']."/". $steampics['apps'][$game['SteamID']]['common']['clienttga'].".tga'><br>";
	$output .= "icon: <img src='https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/".$game['SteamID']."/". $steampics['apps'][$game['SteamID']]['common']['icon'].".jpg'><br>";
	if(isset($steampics['apps'][$game['SteamID']]['common']['oslist'])){
		$output .= "oslist: ". $steampics['apps'][$game['SteamID']]['common']['oslist']."<br>";
	}
	$output .= "metacritic_name: ". $steampics['apps'][$game['SteamID']]['common']['metacritic_name']."<br>";
	$output .= "community_visible_stats: ". booltext($steampics['apps'][$game['SteamID']]['common']['community_visible_stats'])."<br>";
	$output .= "community_hub_visible: ". booltext($steampics['apps'][$game['SteamID']]['common']['community_hub_visible'])."<br>";
	$output .= "gameid: ". $steampics['apps'][$game['SteamID']]['common']['gameid']."<br>";
	if(isset($steampics['apps'][$game['SteamID']]['common']['controller_support'])){
		$output .= "controller_support: ". $steampics['apps'][$game['SteamID']]['common']['controller_support']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['common']['metacritic_score'])){
		$output .= "metacritic_score: ". $steampics['apps'][$game['SteamID']]['common']['metacritic_score']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['common']['metacritic_fullurl'])){
		$output .= "metacritic_fullurl: ". $steampics['apps'][$game['SteamID']]['common']['metacritic_fullurl']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['common']['languages'])){
		$output .= "<ul>languages: ";
		foreach($steampics['apps'][$game['SteamID']]['common']['languages'] as $language => $supported){
			$output .= "<li>".$language . ": ".boolText($supported)."</li>";
		}
		$output .= "</ul>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['common']['eulas'])){
		$output .= "<ul>eulas: ";
		foreach($steampics['apps'][$game['SteamID']]['common']['eulas'] as $eula){
			$output .= "<li><a href='".$eula['url']."'>".$eula['name']."</a></li>";
		}
		$output .= "</ul>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['developer'])){
		$output .= "developer: ". $steampics['apps'][$game['SteamID']]['extended']['developer']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['gamedir'])){
		$output .= "gamedir: ". $steampics['apps'][$game['SteamID']]['extended']['gamedir']."<br>";
	}
	$output .= "gamemanualurl: ". $steampics['apps'][$game['SteamID']]['extended']['gamemanualurl']."<br>";
	$output .= "homepage: ". $steampics['apps'][$game['SteamID']]['extended']['homepage']."<br>";
	if(isset($steampics['apps'][$game['SteamID']]['extended']['icon'])){
		$output .= "icon: ". $steampics['apps'][$game['SteamID']]['extended']['icon']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['installscript'])){
		$output .= "installscript: ". $steampics['apps'][$game['SteamID']]['extended']['installscript']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['languages'])){
		$output .= "languages: ". $steampics['apps'][$game['SteamID']]['extended']['languages']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['launcheula'])){
		$output .= "launcheula: ". $steampics['apps'][$game['SteamID']]['extended']['launcheula']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['noservers'])){
		$output .= "noservers: ". $steampics['apps'][$game['SteamID']]['extended']['noservers']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['order'])){
		$output .= "order: ". $steampics['apps'][$game['SteamID']]['extended']['order']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['primarycache'])){
		$output .= "primarycache: ". $steampics['apps'][$game['SteamID']]['extended']['primarycache']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['serverbrowsername'])){
		$output .= "serverbrowsername: ". $steampics['apps'][$game['SteamID']]['extended']['serverbrowsername']."<br>";
	}
	if(isset($steampics['apps'][$game['SteamID']]['extended']['state'])){
		$output .= "state: ". $steampics['apps'][$game['SteamID']]['extended']['state']."<br>";
	}
	$output .= "publisher: ". $steampics['apps'][$game['SteamID']]['extended']['publisher']."<br>";
	if(isset($steampics['apps'][$game['SteamID']]['extended']['listofdlc'])){
		$output .= "listofdlc: ". $steampics['apps'][$game['SteamID']]['extended']['listofdlc']."<br>";
	}
	
	//var_dump($steampics);	
	return $output;
	// @codeCoverageIgnoreEnd
}

function formatSteamAPI($resultarray,$userstatsarray){
	//echo "000-DEBUG-000";
	//var_dump($resultarray); //GetSchemaForGame
	//var_dump($userstatsarray); //GetUserStatsForGame
	
	//$output .= "<img src='http://cdn.akamai.steamstatic.com/steam/apps/".$game['SteamID']."/header.jpg'>";
	//$output .= "<br>".$description;
	$output  = "<table><tr>";
	if(isset($resultarray['game']['gameName']) && $resultarray['game']['gameName']<>""){
		$output .= "<td>".$resultarray['game']['gameName'] . "</td>";
	}
	
	if(isset($resultarray['game']['gameVersion']) && $resultarray['game']['gameVersion']<>""){
		$output .= "<td>Version ".$resultarray['game']['gameVersion'] . "</td>";
	}
	$output .= "</tr><tr>";
	if(isset($resultarray['game']['availableGameStats']['stats'])){
		$output .= "<td ";
		if(!isset($resultarray['game']['availableGameStats']['achievements'])){ $output .= "colspan=2 "; }
		$output .= "valign=top><table><thead><tr><th>Stat</th><th>Value</th></tr></thead>";
		//var_dump($userstatsarray['playerstats']);
		if(isset($userstatsarray['playerstats']['stats'])){
			$statarray=regroupArray($userstatsarray['playerstats']['stats'],"name");
			//var_dump($statarray);
		}
		foreach($resultarray['game']['availableGameStats']['stats'] as $key => $stat ) {
			$output .= "<tr><td>";
			if($stat['displayName']<>""){
				$output .= "<a href='' title='".htmlspecialchars($stat['name'])."'>".str_replace(" ","&nbsp;",$stat['displayName'])."</a>";
			} else {
				$output .= str_replace(" ","&nbsp;",$stat['name']);
			}
			$output .= "</td>";
			//var_dump($userstatsarray['playerstats']['stats'][$stat['name']]);
			if(isset($statarray[$stat['name']])){
				$output .= "<td>" . $statarray[$stat['name']][0]['value'] . "</td></tr>";
			} else {
				$output .= "<td>" . $stat['defaultvalue'] . "</td></tr>";
			}
		}
		$output .= "</table></td>";
	}
	
	if(isset($resultarray['game']['availableGameStats']['achievements'])){
		$output .= "<td ";
		if(!isset($resultarray['game']['availableGameStats']['stats'])){ $output .= "colspan=2 "; }
		$output .= "valign=top>";
		if(isset($userstatsarray['playerstats']['achievements'])){
			$acharray=regroupArray($userstatsarray['playerstats']['achievements'],"name");
			//var_dump($acharray);
		}
		$achivementcounter=0;
		$counter=1;
		foreach($resultarray['game']['availableGameStats']['achievements'] as $key => $acachievement ) {
			if(isset($acharray[$acachievement['name']]) && $acharray[$acachievement['name']][0]['achieved']==1){
				$output .= "<img src='" . $acachievement['icon']. "' height=64 width=64 "; 
			} else {
				$output .= "<img src='" . $acachievement['icongray']. "' height=64 width=64 ";
				$output .= "onMouseOver=\"this.src='".$acachievement['icon']."'\" "; 
				$output .= "onMouseOut=\"this.src='".$acachievement['icongray']."'\" "; 
			}
			
			//TODO: Fix the replace code to print "'" properly.
			$output .= " title='".htmlspecialchars(str_replace("'","",$acachievement['displayName']));
			if (isset($acachievement['description'])){
				$output .= " | " . htmlspecialchars(str_replace("'","",$acachievement['description']));
			}
			$output .= "'> ";
			if($counter==6){
				$counter=0;
				//$output .= "<br>";
			}
			$counter++;
			$achivementcounter++;
		}
		$output .= "</td>";
	}
	$output .= "</tr>";
	$output .= "</table>";
	
	return($output);
}

function formatSteamLinks($gameid,$profileid){
	$output  = "<ul>";
	if($gameid<>"") {
		$output .= "<li><a href='http://astats.astats.nl/astats/Steam_Game_Info.php?AppID=".$gameid."'>Stats</a></li>";
		$output .= "<li><a href='https://steamdb.info/app/".$gameid."'>Steam DB</a></li>";
		$output .= "<li><a href='http://pcgamingwiki.com/api/appid.php?appid=".$gameid."'>PC Gaming Wiki</a></li>";
		$output .= "<li><a href='http://store.steampowered.com/news/?appids=".$gameid."'>Update History / News</a></li>";
		$output .= "<br>";
		$output .= "<li><a href='http://store.steampowered.com/app/".$gameid."'>Store Page</a></li>";
		$output .= "<li><a href='steam://url/StoreAppPage/".$gameid."'>View in Steam Client</a></li>";
		$output .= "<li><a href='http://steamcommunity.com/app/".$gameid."'>Steam Community</a></li>";
		$output .= "<ul>";
		$output .= "<li><a href='http://steamcommunity.com/app/".$gameid."/discussions/'>Discussions</a></li>";
		$output .= "<li>Screenshots: <a href='http://steamcommunity.com/app/".$gameid."/screenshots/'>All</a> ";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/screenshots/'>Mine</a>";
		$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/screenshots/?appid=".$gameid."'>This Game</a>)</li>";
		
		$output .= "<li>Artwork: <a href='http://steamcommunity.com/app/".$gameid."/images/'>All</a> ";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/images/'>Mine</a>";
		$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/images/?appid=".$gameid."'>This Game</a>)</li>";

		$output .= "<li><a href='http://steamcommunity.com/app/".$gameid."/broadcasts/'>Broadcasts</a></li>";
		$output .= "<li>Videos: <a href='http://steamcommunity.com/app/".$gameid."/videos/'>All</a> ";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/videos/'>Mine</a>";
		$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/videos/?appid=".$gameid."'>This Game</a>)</li>";
		
		$output .= "<li><a href='http://steamcommunity.com/app/".$gameid."/allnews/'>Community News</a></li>";
		$output .= "<li>Guides: <a href='http://steamcommunity.com/app/".$gameid."/guides/'>All</a>";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=guides'>Mine</a> ";
		$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=guides&appid=".$gameid."'>This Game</a>)</li>";

		$output .= "<li>Reviews: <a href='http://steamcommunity.com/app/".$gameid."/reviews/'>All</a> ";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/reviews/'>Mine</a> "; //http://steamcommunity.com/id/Mobeck/recommended also works
		$output .= " <a href='http://store.steampowered.com/app/".$gameid."/#app_reviews_hash'>Store Page</a></li>";
		$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/gamecards/".$gameid."/'>Game Cards</a></li>";
		
		$output .= "<li>Achievements: <a href='http://steamcommunity.com/stats/".$gameid."'/achievements>All</a> ";
		$output .= " <a href='http://steamcommunity.com/id/".$profileid."/stats/".$gameid."/achievements'>Mine</a></li>";
		$output .= "</ul>";
	}
	
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."'>My Profile (".$profileid.")</a> <a href='http://steamcommunity.com/id/".$profileid."/edit'>edit</a></li>";
	$output .= "<ul>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/badges/'>Badges</a></li>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/inventory/#753'>Inventory</a></li>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/myactivity'>My Activity</a></li>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/home/'>Friend Activity</a></li>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/friends/'>My Friends</a></li>";  //All Friends
	$output .= "<ul>";
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/blocked/'>Blocked</a></li>";  //Blocked Users
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/coplay/'>Recently Played With</a></li>";  //Recently Played With
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/friends/following/'>Following</a></li>"; //Following
	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/groups/'>Groups</a></li>";
	$output .= "</ul>";
	
	
	//$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/'>WorkShop Files</a></li>";
	//$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=merchandise'>Merchandise</a>";
	//$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=merchandise&appid=".$gameid."'>This Game</a>)</li>";
	//$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=collections'>Collections</a>";
	//$output .= " (<a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=collections&appid=".$gameid."'>This Game</a>)</li>";
	//$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/myworkshopfiles/?section=greenlight'>Greenlight</a></li>";

	$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/games/'>My Games (Recent)</a></li>";
		$output .= "<ul>";
			$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/games/?tab=all'>All My Games</a></li>";
			$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/followedgames/'>My Followed Games</a></li>";
			$output .= "<li><a href='http://steamcommunity.com/id/".$profileid."/wishlist/'>My WishList</a></li>";
		$output .= "</ul>";
		$output .= "</ul>";
	$output .= "</ul>";	
	
	return $output;
}

function formatnews($newsarray){
	$output  = "<b>News:</b>";
	foreach($newsarray['appnews']['newsitems'] as $news){
		$output .= "<p>";
		$output .= "<a href='". $news['url'] ."' target='_blank'>". $news['title'] ."</a>";
		if($news['author']<>""){
			$output .= " by " . $news['author'];
		}
		$output .= " on " . date("m/d/Y",$news['date']);
		$output .= "<br>";
		
		//TODO: Need to do a preg_replace that does not break existing valid HTML links AND if the URL is an image, imbed the image instead.
		//http://stackoverflow.com/questions/1188129/replace-urls-in-text-with-html-links/
		$output .= $news['contents'];
		$output .= "</p>";
	}
	
	return $output;
}
