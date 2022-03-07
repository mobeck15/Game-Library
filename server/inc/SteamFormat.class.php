<?php
declare(strict_types=1);

require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

class SteamFormat
{
	public function formatnews($newsarray){
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
	
	public function formatSteamLinks($gameid,$profileid){
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
	
	public function formatSteamAPI($resultarray,$userstatsarray){
		//echo "000-DEBUG-000";
		//var_dump($resultarray); //GetSchemaForGame
		//var_dump($userstatsarray); //GetUserStatsForGame
		
		//$output .= "<img src='http://cdn.akamai.steamstatic.com/steam/apps/".$game['SteamID']."/header.jpg'>";
		//$output .= "<br>".$description;
		$output  = "<table><tr>";
		$output.=$this->formatDetailStat("",$resultarray['game']['gameName'],"<td>","","</td>");
		$output.=$this->formatDetailStat("Version",$resultarray['game']['gameVersion'],"<td>"," ","</td>");
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
	
	/*
	public function formatSteamPics($steampics){
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
	*/
	
	private function formatDetailStat($label,$value,$prefix="",$separator=": ",$suffix="<br>"){
		if($this->isempty($value)) {
			return "";
		}
		
		$output = $prefix . $label . $separator . $value . $suffix;
		
		return $output;
	}
	
	private function formatListStat($label,$array){
		if(!is_array($array) or $this->isempty($array)) {
			return "";
		}
		
		$output = "<ul>$label: ";
		foreach($array as $value){
			$output .= "<li>$value</li>";
		}
		$output .= "</ul>";
		
		return $output;
	}
	
	private function formatStat($label,$value){
		if($this->isempty($value)) {
			return "";
		}
		
		if(is_array($value)){
			return $this->formatListStat($label,$value);
		}
		
		return $this->formatDetailStat($label,$value);
	}
	
	private function formatDemos($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = "<ul>$label: ";
		foreach($array as $value){ 
			$output .= "<li>" . $value['appid'].": ".$value['description'] . "</li>";
		}
		$output .= "</ul>";
		
		return $output;
	}
	
	private function formatoverview($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = $label. ": ";
		$output .= $array['currency']; 
		$output .= "$" . $array['initial']/100; 
		if($array['discount_percent']>0){
			 $output .= "-" . $array['discount_percent'] . "%";
			 $output .= "=$" . $array['final']/100; 
		}
		$output .= "<br>";
		
		return $output;
	}
	
	private function formatpackage($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = $label. ": ";
		foreach($array as $value){
			$output .= "<br>Name: ".$value['name']."<br>";
			$output .= "Title: ".$value['title']."<br>";
			$output .= "description: ".$value['description']."<br>";
			$output .= "selection_text: ".$value['selection_text']."<br>";
			$output .= "save_text: ".$value['save_text']."<br>";
			$output .= "display_type: ".$value['display_type']."<br>";
			$output .= "is_recurring_subscription: ".$value['is_recurring_subscription']."<br>";
			if(isset($value['subs'])){
				foreach($value['subs'] as $subvalue){
					$output .= "<br>ID: ".$subvalue['packageid'] . "<br>";
					$output .= "Percent Savings: ".$subvalue['percent_savings_text'] . ", (".$subvalue['percent_savings'].")<br>";
					$output .= "Option Text: ".$subvalue['option_text'] . "<br>";
					$output .= "Option Description: ".$subvalue['option_description'] . "<br>";
					$output .= "can get free license: ".$subvalue['can_get_free_license'] . "<br>";
					$output .= "is free license: ".booltext($subvalue['is_free_license']) . "<br>";
					$output .= "price with discount: $".$subvalue['price_in_cents_with_discount']/100 . "<br>";
				}
			}
		}
		$output .= "<br>";
		
		return $output;
	}
	
	private function formatplatform($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = "<ul>$label: ";
		foreach($array as $label => $value){
			$output .= "<li>".$label.": ".booltext($value)."</li>";
		}
		$output .= "</ul>";
		
		return $output;
	}
	
	private function formatcategory($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = "<ul>$label: ";
		foreach($array as $value){
			$output .= "<li>".$value['id'].": ".$value['description']."</li>";
		}
		$output .= "</ul>";
		return $output;
	}
		
	private function formatscreenshot($label, $array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = $label. ": <br>";
		foreach($array as $value){
			$output .= "<a href='".$value['path_full']."'><img src='".$value['path_thumbnail']."></a>";
		}
		$output .= "<br><br>";
		
		return $output;
	}
	
	private function formatmovies($label, $array) {
		if($this->isempty($array)) {
			return "";
		}

		$output = $label . ": <br>";
		foreach($array as $value){
			//var_dump($value);
			$output .= "<a href='".$value['webm']['480']."'><img src='".$value['thumbnail']." title='".$value['name']."'></a>";
		}
		$output .= "<br><br>";
		
		return $output;
	}
	
	private function formatRecommendations($label, $array){
		if($this->isempty($array)) {
			return "";
		}
		$output = "<ul>$label: ";
		foreach($array as $value){
			if(is_array($value)){
				$output .= "<li>".$value['total']."</li>";
			} else {
				$output .= "<li>".$value."</li>";
			}
		}
		$output .= "</ul>";
		
		return $output;
	}
	
	private function formatsupport($label,$array) {
		if($this->isempty($array)) {
			return "";
		}
		
		$output = $label . ": " . $array['url'] . ", " . $array['email'] . "<br>";
		return $output;
	}
	
	private function makehyperlink($ref,$text){
		if($this->isempty($ref) || $this->isempty($text)){
			return "";
		}
		
		return "<a href='$ref'>$text</a>";
	}

	private function isempty($value) {
		if($value == "" || $value == null || (is_array($value) && count($value)==0)){
			return true;
		}
		
		return false;
	}
	
	public function formatAppDetails($appdetails,$verbose=true){
		$output  = $this->formatStat("Type",$appdetails['data']['type']);
		$output .= $this->formatStat("Name",$appdetails['data']['required_age']);
		$output .= $this->formatStat("Required Age",$appdetails['data']['required_age']);
		$output .= $this->formatStat("Free Game",boolText($appdetails['data']['is_free']));
		$output .= $this->formatStat("Controller Support",($appdetails['data']['controller_support'] ?? null));
		$output .= $this->formatStat("DLC",($appdetails['data']['dlc'] ?? null));
		
		if($verbose==true) {
			$output .= "Description: " . $appdetails['data']['detailed_description'] . "<br>";
			if($appdetails['data']['detailed_description']<>$appdetails['data']['about_the_game']){
				$output .= "Description: " . $appdetails['data']['about_the_game'] . "<br>";
			} 
		}
		
		$output .= $this->formatStat("Supported Languages",$appdetails['data']['supported_languages']);
		$output .= $this->formatStat("Reviews",($appdetails['data']['reviews'] ?? null));

		if($verbose==true) {
			$output .= $this->formatStat("Header Image","<img src='" . $appdetails['data']['header_image'] . "'>");
		}
		
		$sitelink=$this->makehyperlink($appdetails['data']['website'],$appdetails['data']['website']);
		$output .= $this->formatStat("Web Site",$sitelink);

		//TODO: add CSS for all elements to make DIV containters for each and spacing.
		$output .= $this->formatStat("PC Requirements",$appdetails['data']['pc_requirements']);
		$output .= $this->formatStat("Mac Requirements",$appdetails['data']['mac_requirements']);
		$output .= $this->formatStat("Linux Requirements",$appdetails['data']['linux_requirements']);
		
		$output .= $this->formatStat("Legal Notice",$appdetails['data']['legal_notice']);
		$output .= $this->formatStat("Publishers",$appdetails['data']['publishers']);
		$output .= $this->formatDemos("Demos",($appdetails['data']['demos'] ?? null));
		$output .= $this->formatoverview("Price Overview",($appdetails['data']['price_overview'] ?? null));
		$output .= $this->formatStat("Packages",$appdetails['data']['packages']);
		$output .= $this->formatpackage("Package Groups", $appdetails['data']['package_groups']);
		$output .= $this->formatplatform("Platforms",($appdetails['data']['platforms'] ?? null));
		$metacriticlink = $this->makehyperlink(($appdetails['data']['metacritic']['url'] ?? null),
			($appdetails['data']['metacritic']['score'] ?? null));
		$output .= $this->formatStat("metacritic",$metacriticlink);
		$output .= $this->formatcategory("Categories",($appdetails['data']['categories'] ?? null));
		$output .= $this->formatcategory("Genres",($appdetails['data']['genres'] ?? null));
		$output .= $this->formatscreenshot("Screenshots",($appdetails['data']['screenshots'] ?? null));
		$output .= $this->formatmovies("Movies",($appdetails['data']['movies'] ?? null));
		$output .= $this->formatRecommendations("recommendations",($appdetails['data']['recommendations'] ?? null));
		$output .= $this->formatStat("achievements",($appdetails['data']['achievements']['total'] ?? null));
		$output .= $this->formatStat("release_date",($appdetails['data']['release_date']['date'] ?? null));
		$output .= $this->formatsupport("Support Info",($appdetails['data']['support_info'] ?? null));
		$output .= $this->formatStat("background",$this->makehyperlink(($appdetails['data']['background'] ?? null),"Link"));
		
		return($output);		
	}
	
}