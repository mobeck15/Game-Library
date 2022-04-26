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
		$output  = "<table><tr>";
		$output.=$this->formatDetailStat("",$resultarray['game']['gameName'],"<td>","","</td>");
		$output.=$this->formatDetailStat("Version",$resultarray['game']['gameVersion'],"<td>"," ","</td>");
		$output .= "</tr><tr>";
		if(isset($resultarray['game']['availableGameStats']['stats'])){
			$output .= "<td ";
			if(!isset($resultarray['game']['availableGameStats']['achievements'])){ $output .= "colspan=2 "; }
			$output .= "valign=top>";
			$output .= $this->statsTable($resultarray['game']['availableGameStats']['stats'],($userstatsarray['playerstats']['stats'] ?? []));
			$output .= "</td>";
		}
		
		if(isset($resultarray['game']['availableGameStats']['achievements'])){
			$output .= "<td ";
			if(!isset($resultarray['game']['availableGameStats']['stats'])){ $output .= "colspan=2 "; }
			$output .= "valign=top>";
			$output .= $this->achievementTable($resultarray['game']['availableGameStats']['achievements'],($userstatsarray['playerstats']['achievements'] ?? []));
			$output .= "</td>";
		}
		$output .= "</tr>";
		$output .= "</table>";
		
		return($output);		
	}
	
	private function achievementTable($achievements,$userach){
		$acharray=regroupArray($userach,"name");
		$output="";
		foreach($achievements as $key => $acachievement ) {
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
		}
		return $output;
	}

	private function statsTable($gamestats,$userstats){
		$output = "<table><thead><tr><th>Stat</th><th>Value</th></tr></thead>";
		$statarray=regroupArray($userstats,"name");
		foreach($gamestats as $key => $stat ) {
			$output .= "<tr><td>";
			if($stat['displayName']<>""){
				$output .= "<a href='' title='".htmlspecialchars($stat['name'])."'>".str_replace(" ","&nbsp;",$stat['displayName'])."</a>";
			} else {
				$output .= str_replace(" ","&nbsp;",$stat['name']);
			}
			$output .= "</td>";
			if(isset($statarray[$stat['name']])){
				$output .= "<td>" . $statarray[$stat['name']][0]['value'] . "</td></tr>";
			} else {
				$output .= "<td>" . $stat['defaultvalue'] . "</td></tr>";
			}
		}
		$output .= "</table>";
		return $output;
	}
	
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
		if(isset($appdetails['success']) && $appdetails['success']==false){
			return "";
		} else {
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
			
			if(isset($appdetails['data']['legal_notice'])){
				$output .= $this->formatStat("Legal Notice",$appdetails['data']['legal_notice']);
			}
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
	
}