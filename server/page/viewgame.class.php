<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/SteamScrape.class.php";
include_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";
include_once $GLOBALS['rootpath']."/inc/SteamFormat.class.php";

class viewgamePage extends Page
{
	public function __construct() {
		$this->title="View Game";
	}
	
	public function buildHtmlBody(){
		$output="";
		
//$ShowSteamThings=false; // Steam scraping is broken for now.
$ShowSteamThings=true;

$lookupgame=lookupTextBox("Product", "ProductID", "id");
$output .= $lookupgame["header"];

$edit_mode=false;
if (isset($_GET['edit']) && $_GET['edit'] = 1) {
	$edit_mode=true;	
}

$conn=get_db_connection();

if (isset($_POST['ID'])) {
	$_GET = array ('id' => $_POST['ID']);
	
	if($_POST['Genre']<>"" and $_POST['Genre']<>"null"){
		$kwlist1=explode(",",$_POST['Genre']);
		foreach($kwlist1 as $kw){
			$kwlist2[]=array("kwType" => "Genre","Keyword" => $kw);
		}
	}
	if($_POST['GameType']<>"" and $_POST['GameType']<>"null"){
		$kwlist1=explode(",",$_POST['GameType']);
		foreach($kwlist1 as $kw){
			$kwlist2[]=array("kwType" => "Game Type","Keyword" => $kw);
		}
	}
	if($_POST['StoryMode']<>"" and $_POST['StoryMode']<>"null"){
		$kwlist1=explode(",",$_POST['StoryMode']);
		foreach($kwlist1 as $kw){
			$kwlist2[]=array("kwType" => "Story Mode","Keyword" => $kw);
		}
	}
	if($_POST['GameFeature']<>"" and $_POST['GameFeature']<>"null"){
		$kwlist1=explode(",",$_POST['GameFeature']);
		foreach($kwlist1 as $kw){
			$kwlist2[]=array("kwType" => "Game Feature","Keyword" => $kw);
		}
	}
	if($_POST['GameMode']<>"" and $_POST['GameMode']<>"null"){
		$kwlist1=explode(",",$_POST['GameMode']);
		foreach($kwlist1 as $kw){
			$kwlist2[]=array("kwType" => "Game Mode","Keyword" => $kw);
		}
	}

	foreach($_POST as $key => &$postitem){
		/*
		switch ($i) {
		case 'Title':
		case 'Type':
			$output .= "i equals 2";
			break;
		}
		*/
		
		if(mysqli_real_escape_string($conn, $postitem)==""){
			$postitem="null";
		} else {
			$postitem="'".mysqli_real_escape_string($conn, $postitem)."'";
		}
	}
	
	//TODO: Test this insert query some more. It was doing wierd stuff with null values.
	
	$update_SQL  = "UPDATE `gl_products` SET ";
	
	$update_SQL .= "`Title`             = ". $_POST['Title']             . ", ";
	$update_SQL .= "`Type`              = ". $_POST['Type']              . ", ";
	$update_SQL .= "`Playable`          = ". $_POST['Playable']          . ", ";
	$update_SQL .= "`Want`              = ". $_POST['Want']              . ", ";
	$update_SQL .= "`Series`            = ". $_POST['Series']            . ", ";
	$update_SQL .= "`LaunchDate`        = ". $_POST['LaunchDate']        . ", ";
	$update_SQL .= "`LaunchPrice`       = ". $_POST['LaunchPrice']       . ", ";
	$update_SQL .= "`MSRP`              = ". $_POST['MSRP']              . ", ";
	$update_SQL .= "`CurrentMSRP`       = ". $_POST['CurrentMSRP']       . ", ";
	$update_SQL .= "`HistoricLow`       = ". $_POST['HistoricLow']       . ", ";
	$update_SQL .= "`LowDate`           = ". $_POST['LowDate']           . ", ";
	$update_SQL .= "`SteamAchievements` = ". $_POST['SteamAchievements'] . ", ";
	$update_SQL .= "`SteamCards`        = ". $_POST['SteamCards']        . ", ";
	$update_SQL .= "`TimeToBeat`        = ". $_POST['TimetoBeat']        . ", ";
	$update_SQL .= "`Metascore`         = ". $_POST['Metascore']         . ", ";
	$update_SQL .= "`UserMetascore`     = ". $_POST['UserMetascore']     . ", ";
	$update_SQL .= "`SteamRating`       = ". $_POST['SteamRating']       . ", ";
	$update_SQL .= "`SteamID`           = ". $_POST['SteamID']           . ", ";
	$update_SQL .= "`GOGID`             = ". $_POST['GOGID']             . ", ";
	$update_SQL .= "`isthereanydealID`  = ". $_POST['isthereanydealID']  . ", ";
	$update_SQL .= "`TimeToBeatID`      = ". $_POST['TimeTobeatID']      . ", ";
	$update_SQL .= "`MetascoreID`       = ". $_POST['MetascoreID']       . ", ";
	$update_SQL .= "`DateUpdated`       = ". $_POST['DateUpdated']       . ", ";
	$update_SQL .= "`ParentGameID`      = ". $_POST['ParentGameID']		 . ", ";
	$update_SQL .= "`ParentGame`        = ". $_POST['ParentGame']		 . ", ";
	$update_SQL .= "`Developer`         = ". $_POST['Developer']		 . ", ";
	$update_SQL .= "`Publisher`         = ". $_POST['Publisher']		 . "  ";
	$update_SQL .= "WHERE `gl_products`.`Game_ID` = " . $_POST['ID'];
	
	if ($conn->query($update_SQL) === TRUE) {
		$output .= "Game record updated successfully";
		$output .= "<br>";

		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $update_SQL.";\r\n", FILE_APPEND | LOCK_EX);
		
	} else {
		$output .= "Error updating game record: " . $conn->error;
		$output .= "<br>";
		$output .= $update_SQL;
	}
	
	///?

	$index=0;
	$sql="SELECT * FROM `gl_keywords` WHERE `ProductID` = " . $_POST['ID'];
	if($result = $conn->query($sql)) {
		while ($row2 = $result->fetch_assoc()) {
			if (isset($kwlist2[$index])){
				$kwsql1  ="UPDATE `gl_keywords` SET `KwType` = '". mysqli_real_escape_string($conn, trim($kwlist2[$index]['kwType']));
				$kwsql1 .="', `Keyword` = '". mysqli_real_escape_string($conn, trim($kwlist2[$index]['Keyword']));
				$kwsql1 .="' WHERE `gl_keywords`.`KWid` = ". $row2['KWid'].";";
				
				$index++;
			} else {
				$kwsql1 = "DELETE FROM `gl_keywords` ";
				$kwsql1 .=" WHERE `gl_keywords`.`KWid` = ". $row2['KWid'].";";
			}
			
			if ($conn->query($kwsql1) === TRUE) {
			} else {
				$output .= "Error updating keyword record using sql " . $kwsql1 . ": " . $conn->error;
				$output .= "<br>";
			}

		}
		
		$kwsql1="INSERT INTO `gl_keywords` (`ProductID`, `KwType`, `Keyword`) VALUES ";
		$kwsql2="";
		while(isset($kwlist2[$index])){
			if($kwsql2<>""){
				$kwsql2 .=", ";
			}

			$kwsql2 .="(".$_POST['ID'].", '". trim($kwlist2[$index]['kwType'])."', '". mysqli_real_escape_string($conn, trim($kwlist2[$index]['Keyword']))."')";

			$index++;
		}
		if($kwsql2<>""){
			if ($conn->query($kwsql1.$kwsql2) === TRUE) {

				$file = 'insertlog'.date("Y").'.txt';
				// Write the contents to the file, 
				// using the FILE_APPEND flag to append the content to the end of the file
				// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
				file_put_contents($file, $kwsql1.$kwsql2.";\r\n", FILE_APPEND | LOCK_EX);
			} else {
				$output .= "Error updating keyword record using sql " . $kwsql1.$kwsql2 . ":<br> " . $conn->error;
				$output .= "<br>";
			}
		}
	}	
}

if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		$output .= 'Please specify a game by ID.
		<form method="Get">
			'. $lookupgame["textBox"].'
			<input type="submit">
		</form>';
		$output .= $lookupgame["lookupBox"];
	
} else {
	//DONE: cleanse get[ID] to make sure it is only a numeric value - Rejects id if not numeric
	$game=getGameDetail($_GET['id'],$conn);
	$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
	$settings=getsettings($conn);

	//$output .= '<form action="'. $_SERVER['PHP_SELF'].'" method="post">';

	$output .= '<form method="post">
	<table>
	<tr>
		<th height=10>Game</th><td>
		<table>
		<thead><tr>
		<th height=10>Game ID</th>
		<th height=10>Title</th>
		<th height=10>Parent Game</th>
		<th height=10>Series</th>
		</tr></thead>
		<tr>
		<td>';
			$output .= '<input type="hidden" name="ID" value="'. $game['Game_ID'].'">
			<a href="'. $_SERVER['PHP_SELF'].'?id='. $game['Game_ID'].'" target="_blank">'. $game['Game_ID'].'</a>
		</td>

		<td>';
			if ($edit_mode === true) {
			$output .= '<input type="text" name="Title" size="60" value="'. $game['Title'].'">';
			} else { $output .= $game['Title']; }
		$output .= '</td>

		<td>';
			//DONE: Need to add lookup of parentgameID
			
			if ($edit_mode === true) { 
			$output .= 'ID: <input type="number" id="ParentGameID" name="ParentGameID" max="99999" min="0" value="'. $game['ParentGameID'].'">
			Parent Game: <input type="text" id="ParentGame" name="ParentGame" size="60" value="'. ($game['ParentGame']=="" ? $calculations[$game['ParentGameID']]['Title'] : $game['ParentGame']).'">
			
			<script>
			  $(function() {
					$("#ParentGame").autocomplete({ 
						source: "./ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ParentGameID").val(ui.item.id);
						} }
					);
				} );
			</script>';
			
			} else {
			$output .= "<a href='viewgame.php?id=". $game['ParentGameID']."' target='_blank'>". $game['ParentGameID']."</a> ";
			$output .= $game['ParentGame']; 
			
				//If the ID for parent game do not match show the alternate value in parenthese
				if($calculations[$game['ParentGameID']]['Title']<>$game['ParentGame']){
					$output .= " (".$calculations[$game['ParentGameID']]['Title'].")";
				}
				
			} 
		$output .= '</td>

		<td>';
			if ($edit_mode === true) {
			$output .= '<input type="text" name="Series" size="35" value="'. $game['Series'].'">';
			} else { 
			$output .= $game['Series']; 
			}
		$output .= '</td>
		</tr>
		</table>
		</td>';
		
	if($game['SteamID']>0 and $ShowSteamThings){
		/*
		 * Steam scraping code goes here
		 */
		$SteamPage = new SteamScrape($game['SteamID']);
		$steamAPI= new SteamAPI($game['SteamID']);
		$steamformat = new SteamFormat();
		 
		//$userstatsarray=GetUserStatsForGame($game['SteamID']);
		$userstatsarray=$steamAPI->GetSteamAPI("GetUserStatsForGame");
		
		//$resultarray=GetSchemaForGame($game['SteamID']);
		$resultarray=$steamAPI->GetSteamAPI("GetSchemaForGame");

		$showAppDetails=true;
		//$showAppDetails=false;
		if($showAppDetails){
			//$appdetails=GetAppDetails($game['SteamID']);
			$appdetails=$steamAPI->GetSteamAPI("GetAppDetails");
		}

		//$showSteamPics=false;
		/* //Currently offline (1/23/2020) * /
		if($showSteamPics){
			$steampics=GetSteamPICS($game['SteamID']);
		}
		/* */
		
		//$result=scrapeSteamStore($game['SteamID']);

		//if($result!=false) {
		if($SteamPage->pageExists) {
			//$description=parse_game_description($result);
			//TODO: fatal error when vieing GameMaker Studio Pro (id=1343) Call to a member function find() on bool
			$description=$SteamPage->getDescription();

			$allkeywordarray=array();
			//$matches=parse_tags($result);
			//$steamkeywordlist=$matches['list'];
			//$allkeywordarray=$matches['all'];
			//unset($matches);
			$steamkeywordlist=$SteamPage->getTagList();
			$allkeywordarray=array_merge($allkeywordarray,$SteamPage->getTags());
			
			//$matches=parse_details($result);
			//$steamfeaturelist=$matches['list'];
			//$allkeywordarray=array_merge($allkeywordarray,$matches['all']);
			//unset($matches);
			$steamfeaturelist=$SteamPage->getDetailList();
			$allkeywordarray=array_merge($allkeywordarray,$SteamPage->getDetails());
			
			//$newsteamrating=parse_reviews($result);
			$newsteamrating=$SteamPage->getReview();
			if($game['SteamRating']==0 && isset($newsteamrating) && $newsteamrating>0){
				$game['SteamRating']=$newsteamrating;
			}
			
			//$Developer=parse_developer($result);
			$Developer=$SteamPage->getDeveloper();
			//$Publisher=parse_publisher($result);
			$Publisher=$SteamPage->getPublisher();
			//$PubDate=parse_releasedate($result);
			$PubDate=$SteamPage->getReleaseDate();
			if(($game['LaunchDate']==null OR $game['LaunchDate']->getTimestamp()==0) && isset($PubDate)){
				$game['LaunchDate']=new DateTime($PubDate);
			}

			//$matches=parse_genre($result);
			//$steamgenrelist=$matches['list'];
			//$allkeywordarray=array_merge($allkeywordarray,$matches['all']);
			//unset($matches);
			$steamgenrelist=$SteamPage->getGenreList();
			$allkeywordarray=array_merge($allkeywordarray,$SteamPage->getGenre());
		}
		$output .= '<td rowspan=12 width=800 valign=top>';
		/* Return the Steam page for troubleshooting * /
		//$output .= $url . "<p>";
		var_dump($result); 
		/* */
		
		if($showAppDetails){
		$output .= '<details>
		<summary>AppDetails</summary>';
		$output .= $steamformat->formatAppDetails($appdetails[$game['SteamID']],false);
		//$output .= arrayTable($appdetails[$game['SteamID']]);

		$output .= '</details>';
		} 
		
		/*
		//@codeCoverageIgnoreStart
		if($showSteamPics){
			$output .= '<details>
			<summary>steampics</summary>';
			$output .= $steamformat->formatSteamPics($steampics['apps'][$game['SteamID']]);
			//var_dump($steampics);
			$output .= '</details>';
		}
		//@codeCoverageIgnoreEnd
		*/
		
		//TODO: Check for API data even if there is no store page. Currently skipps if steam redirects to home page.
		//if($result!=false) { 
		if($SteamPage->pageExists) { 
			$output .= "<details>
			<summary>Steam API</summary>
			<img src='http://cdn.akamai.steamstatic.com/steam/apps/". $game['SteamID']."/header.jpg'>
			<br>";
			$output .= $description; 
			$output .= $steamformat->formatSteamAPI($resultarray,$userstatsarray);
			$output .= '</details> ';
		}
		
		$output .= $steamformat->formatSteamLinks($game['SteamID'],$settings['LinkSteam']);
		$output .= "</td>";
		}
	$output .= "</tr>
	
	<tr><th height=10>Attributes</th><td>
		<table><thead><tr><th>Type</th><th>Playable</th><th>Status</th><th>Active</th><th>Count Game</th><th>Achievements</th><th>Cards</th></tr></thead>
		<tr><td>";
		if ($edit_mode === true) {
		$output .= '<input type="text" name="Type" value="'. $game['Type'].'">';
		} else { $output .= $game['Type']; }
		$output .= '</td>
		
		<td>';
		if ($edit_mode === true) {
		$output .= '<label><input type="radio" name="Playable" value="1"';
			if ($game['Playable']==1) {$output .= "checked=\"checked\"";}
		$output .= '> Playable</label><br>
		
		<label><input type="radio" name="Playable" value="0"';
		if ($game['Playable']==0) {$output .= "checked=\"checked\"";}
		$output .= '> Not Playable</label>';
		} else { $output .= boolText($game['Playable']); }
		$output .= '</td>
		
		<td>'. $calculations[$game['Game_ID']]['Status'].'</td>
		<td>'. boolText($calculations[$game['Game_ID']]['Active']).'</td>
		<td>'. boolText($calculations[$game['Game_ID']]['CountGame']).'</td>';

		if($game['SteamAchievements']==0){
			$game['SteamAchievements']=0;
		}

		if(isset($resultarray['game']['availableGameStats']['achievements'])) {
			$achivementcounter=count($resultarray['game']['availableGameStats']['achievements']);
		} 
		
		if(isset($achivementcounter) && $achivementcounter<>$game['SteamAchievements']){
			$game['SteamAchievements']=$achivementcounter;
		}
		$output .= '<td>'. (0+$calculations[$game['Game_ID']]['Achievements']).' of ';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="SteamAchievements" max="9999" min="0" value="'. $game['SteamAchievements'] .'">';
		} else { $output .= (int) $game['SteamAchievements']; }

		$output .= '('. sprintf("%.2f",$calculations[$game['Game_ID']]['AchievementsPct']) . '% | 
		 '. $calculations[$game['Game_ID']]['AchievementsLeft'].' Left)
		</td>
		
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="SteamCards" max="100" min="0" value="'. $game['SteamCards'].'">';
		} else { $output .= (int)$game['SteamCards']; }
		$output .= '</td>
		
		</tr></table>
	</td></tr>

	<tr><th height=10>Ratings</th><td>
		<table><thead><tr><th>Want</th><th>Metacritic</th><th>Metacritic User</th><th>Steam Rating</th><th>Review</th></tr><tr></thead>
		<td>';
		if ($edit_mode === true) {
		$output .= '<select name="Want" >
		<option value="1" '; if ($game['Want']==1) { $output .= 'selected="selected"';} $output .= ' >1</option>
		<option value="2" '; if ($game['Want']==2) { $output .= 'selected="selected"';} $output .= ' >2</option>
		<option value="3" '; if ($game['Want']==3) { $output .= 'selected="selected"';} $output .= ' >3</option>
		<option value="4" '; if ($game['Want']==4) { $output .= 'selected="selected"';} $output .= ' >4</option>
		<option value="5" '; if ($game['Want']==5) { $output .= 'selected="selected"';} $output .= ' >5</option>
		</select>';
		} else { $output .= $game['Want']; }
		$output .= '</td>
		
		<td>';
		if ($edit_mode === true) { 
		$output .= '<input type="number" name="Metascore" max="100" min="0" value="'. $game['Metascore'].'">';
		} else { $output .= $game['MetascoreLinkCritic']; }
		$output .= '</td>
		
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="UserMetascore" max="100" min="0" value="'. $game['UserMetascore'].'">';
		} else { $output .= $game['MetascoreLinkUser']; }
		$output .= '</td>
		
		<td>';

		if ($edit_mode === true) {
		$output .= '<input type="number" name="SteamRating" max="100" min="0" value="'. $game['SteamRating'].'">';
		} else { $output .= $game['SteamRating']; } 
		
		if (isset($newsteamrating) && $game['SteamRating']<>$newsteamrating){
			$output .= " (". $newsteamrating . ")";
		}
		$output .= '</td>

		<td>'. $calculations[$game['Game_ID']]['Review'].'</td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Dates</th>';
		//DONE: Move this up to where these values are set.
		
		$output .= '<td><table><thead><tr><th>Launch</th><th>Updated</th><th>First Play</th><th>Last Play</th><th>Last Beat</th><th>Purchase</th><th>Last Play / Purchase</th></tr></thead>
		<tr><td>';
		if ($edit_mode === true) {
		$output .= '<input type="date" name="LaunchDate" value="';
			//$output .= date("Y-m-d",strtotime($game['LaunchDate'])); 
			$output .= $game['LaunchDate']->format("Y-m-d");
			$output .= '">';
		} else { $output .= $game['LaunchDate']->format("n/j/Y"); }
		
		if(isset($PubDate)){
			$output .= "<br>".trim($PubDate);
		}
		$output .= '</td>
		
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="date" name="DateUpdated" value="'. date("Y-m-d").'"> 
		<br>'. $game['DateUpdated']; 
		} else { $output .= $game['DateUpdated']; }
		$output .= '</td>
		
		<td>'. $calculations[$game['Game_ID']]['firstplay'].'</td>
		<td>'. $calculations[$game['Game_ID']]['lastplay']; 
			if($calculations[$game['Game_ID']]['lastplay']<>""){
			$output .= '<br>('. $calculations[$game['Game_ID']]['DaysSinceLastPlay'].' Days)';
			}
		$output .= '</td>
		
		<td>'. $calculations[$game['Game_ID']]['LastBeat'].'</td>
		<td>';
		if(isset($calculations[$game['Game_ID']]['PurchaseDateTime'])) {
			$output .= $calculations[$game['Game_ID']]['PurchaseDateTime']->format("n/j/Y g:i:s A"); 
		}
		$output .= '<br>('. $calculations[$game['Game_ID']]['DaysSincePurchaseDate'].' Days)</td>
		<td>'. $calculations[$game['Game_ID']]['LastPlayORPurchase'].'<br>('. $calculations[$game['Game_ID']]['DaysSinceLastPlayORPurchase'].' Days)</td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Price</th>
	<td><table><thead><tr><th></th><th>Launch</th><th>MSRP</th><th>Current</th><th>Historic</th><th>CPI Launch</th><th>Paid</th><th>Bundle</th><th>Sale</th><th>Alt Sale</th></tr></thead>
	<tr><th>Price</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="text" name="LaunchPrice" size="5" value="'. $game['LaunchPrice'].'">';
		} else { $output .= "$".$game['LaunchPrice']; }
		$output .= '</td>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="text" name="MSRP" size="5" value="'. $game['MSRP'].'">';
		} else { $output .= "$".$game['MSRP']; }
		$output .= '</td>
		
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="text" name="CurrentMSRP" size="5" value="'. $game['CurrentMSRP'].'">';
		} else { $output .= "$".$game['CurrentMSRP']; }
		$output .= '</td>
		
		<td>';
		//if($game['HistoricLow'] == false)
		//{
		//	$game['HistoricLow'] = 0;
		//}
		
		if ($edit_mode === true) { 
			if ($game['LowDate']==0) {
				$useLowDate=$game['LaunchDate']->format("n/j/Y");
			} else {
				$useLowDate = $game['LowDate'];
			}
			
			if($useLowDate == false)
			{
				$useLowDate = "";
			}
			
			$lowtime = strtotime($useLowDate);
			
			if($lowtime == false)
			{
				$lowtime = 0;
			}
			
		$output .= '<input type="text" name="HistoricLow" size="5" value="'. $game['HistoricLow'].'">
		<br><input type="date" name="LowDate" value="'. date("Y-m-d",$lowtime).'">';
		} else { $output .= "$".$game['HistoricLow']."<br>".$game['LowDate']; }
		$output .= '</td>';
		/* */
		
		
		$output .= '<td>';
		//TODO: Move CPI to a row and a row for dates to calulate eache price type.
		//$output .= $game['CPILaunch']; 
		$output .= '</td>
		<td>$'. $calculations[$game['Game_ID']]['Paid'].'</td>
		<td>$'. ($calculations[$game['Game_ID']]['BundlePrice'] ?? 0).'</td>
		<td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['SalePrice']).'</td>
		<td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['AltSalePrice']).'</td>
	</tr><tr><th>Discount</th>	';
		/* */
		$output .= '<!-- Launch -->        <td></td>
		<!-- MSRP -->          <td>0%</td> 
		<!-- Current Price --> <td></td> 
		<!-- Historic Low -->  <td></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>'. sprintf("%.2f",$calculations[$game['Game_ID']]['PaidVariancePct']).'%</td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>'. sprintf("%.2f",$calculations[$game['Game_ID']]['SaleVariancePct']).'%</td> 
		<!-- Alt Sale -->      <td>'. sprintf("%.2f",$calculations[$game['Game_ID']]['AltSaleVariancePct']).'%</td> 
	</tr><tr><th>Per Hour (Played)</th>
		<!-- Launch -->        <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhr']).'</td>
		<!-- MSRP -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhr']).'</td> 
		<!-- Current Price --> <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Currentperhr']).'</td> 
		<!-- Historic Low -->  <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Historicperhr']).'</td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Paidperhr']).'</td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhr']).'</td> 
		<!-- Alt Sale -->      <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhr']).'</td> 
	</tr><tr><th>Per Hour (Time To Beat)</th>
		<!-- Launch -->        <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhrbeat']).'</td>
		<!-- MSRP -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhrbeat']).'</td> 
		<!-- Current Price --> <td></td> 
		<!-- Historic Low -->  <td></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td></td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhrbeat']).'</td> 
		<!-- Alt Sale -->      <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhrbeat']).'</td> 
	</tr><tr><th>1 hour less</th>
		<!-- Launch -->        <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['LaunchLess1']).'</td>
		<!-- MSRP -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPLess1']).'</td> 
		<!-- Current Price --> <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['CurrentLess1']).'</td> 
		<!-- Historic Low -->  <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['HistoricLess1']).'</td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['PaidLess1']).'</td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['SaleLess1']).'</td> 
		<!-- Alt Sale -->      <td>$'. sprintf("%.2f",$calculations[$game['Game_ID']]['AltLess1']).'</td> 
	</tr><tr><th>Time to $0.01 less</th>
		<!-- Launch -->        <td>'. $calculations[$game['Game_ID']]['LaunchPriceObj']->getHoursTo01LessPerHour(true).'</td>
		<!-- MSRP -->          <td>'. timeduration($calculations[$game['Game_ID']]['MSRPLess2'],"hours").'</td>
		<!-- Current Price --> <td>'. timeduration($calculations[$game['Game_ID']]['CurrentLess2'],"hours").'</td>
		<!-- Historic Low -->  <td>'. timeduration($calculations[$game['Game_ID']]['HistoricLess2'],"hours").'</td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>'. timeduration($calculations[$game['Game_ID']]['PaidLess2'],"hours").'</td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>'. timeduration($calculations[$game['Game_ID']]['SaleLess2'],"hours").'</td>
		<!-- Alt Sale -->      <td>'. timeduration($calculations[$game['Game_ID']]['AltLess2'],"hours").'</td>
	</tr><tr><th>Time to next position</th>
		<!-- Launch -->        <td>'. timeduration($calculations[$game['Game_ID']]['LaunchHrsNext1'],"hours").'</td>
		<!-- MSRP -->          <td>'. timeduration($calculations[$game['Game_ID']]['MSRPHrsNext1'],"hours").'</td>
		<!-- Current Price --> <td>'. timeduration($calculations[$game['Game_ID']]['CurrentHrsNext1'],"hours").'</td>
		<!-- Historic Low -->  <td>'. timeduration($calculations[$game['Game_ID']]['HistoricHrsNext1'],"hours").'</td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>'. timeduration($calculations[$game['Game_ID']]['PaidHrsNext1'],"hours").'</td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>'. timeduration($calculations[$game['Game_ID']]['SaleHrsNext1'],"hours").'</td>
		<!-- Alt Sale -->      <td>'. timeduration($calculations[$game['Game_ID']]['AltHrsNext1'],"hours").'</td>
	</tr><tr><th>Time to next active position</th>
		<!-- Launch -->        <td>'. timeduration($calculations[$game['Game_ID']]['LaunchHrsNext2'],"hours").'</td>
		<!-- MSRP -->          <td>'. timeduration($calculations[$game['Game_ID']]['MSRPHrsNext2'],"hours").'</td>
		<!-- Current Price --> <td>'. timeduration($calculations[$game['Game_ID']]['CurrentHrsNext2'],"hours").'</td>
		<!-- Historic Low -->  <td>'. timeduration($calculations[$game['Game_ID']]['HistoricHrsNext2'],"hours").'</td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>'. timeduration($calculations[$game['Game_ID']]['PaidHrsNext2'],"hours").'</td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>'. timeduration($calculations[$game['Game_ID']]['SaleHrsNext2'],"hours").'</td>
		<!-- Alt Sale -->      <td>'. timeduration($calculations[$game['Game_ID']]['AltHrsNext2'],"hours").'</td>
	</tr>
	</table>
	</td></tr>
	
	<tr><th height=10>Times</th>
		<td><table><thead><tr><th>Time To Beat</th><th>Total Hours</th><th>Grand Total</th><th>TimeLeftToBeat</th></tr><tr></thead>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="text" name="TimetoBeat" size="5" value="'. $game['TimeToBeat'].'"> (hours)';
		} else { $output .= $game['TimeToBeatLink2']; }
		$output .= '</td>
		<td>'. timeduration($calculations[$game['Game_ID']]['totalHrs'],"seconds").'</td>
		<td>'. timeduration($calculations[$game['Game_ID']]['GrandTotal'],"seconds").'</td>
		<td>'. timeduration($calculations[$game['Game_ID']]['TimeLeftToBeat'],"hours").'</td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Links</th><td>
		<table><thead><tr><th>GOG</th><th class="hidden">Desura</th><th>Is There Any Deal</th><th>Steam</th><th>Metacritic</th><th>How Long to Beat</th></tr></thead>
		<td>'. $game['GOGLink'].'</td>
		<td class="hidden">'. $game['DesuraLink'].'</td>
		<td>'. $game['isthereanydealLink'].'</td>
		<td>'. $game['SteamLinks'].'</td>
		<td>'. $game['MetascoreLink'].'</td>
		<td>'. $game['TimeToBeatLink'].'</td>
		</tr>';
		if ($edit_mode === true) {
		$output .= '<tr>
		<td><input type="text" name="GOGID" size="10" value="'. $game['GOGID'].'"></td>
		<td class="hidden"><input type="text" name="DesuraID" size="10" value="'. $game['DesuraID'].'"></td>
		<td><input type="text" name="isthereanydealID" size="10" value="'. $game['isthereanydealID'].'"></td>
		<td><input type="text" name="SteamID" size="5" value="'. $game['SteamID'].'"></td>
		<td><input type="text" name="MetascoreID" size="20" value="'. $game['MetascoreID'].'"></td>
		<td><input type="text" name="TimeTobeatID" size="10" value="'. $game['TimeToBeatID'].'"></td>
		</tr>';
		}
		$output .= '</table>
	</td></tr>

	<tr><th>Keywords</th><td valign=top>
	<table>
	<thead>
	<tr><th>Type</th><th>Keyword</th></tr></thead>
	<tbody>';
	
	$sql="SELECT * FROM `gl_keywords` WHERE `ProductID` = " . $game['Game_ID'];
	
	if($game['Developer']=="" && isset($Developer)){
		$game['Developer']=$Developer;
	}
	if($game['Publisher']=="" && isset($Publisher)){
		$game['Publisher']=$Publisher;
	}

	if($result = $conn->query($sql)) {
		$displaykwtable="";
		while($row2 = $result->fetch_assoc()) {
			if(!isset($keywords[$row2['KwType']])) {
				$keywords[$row2['KwType']]=$row2['Keyword'];
			} else {
				$keywords[$row2['KwType']] .= ", ".$row2['Keyword'];
			}
			
			$displaykwtable .= "<tr>";
			$displaykwtable .= "<td>".$row2['KwType'] ."</td>";
			$displaykwtable .= "<td>".$row2['Keyword'] ."</td>";
			$displaykwtable .= "</tr>\r\n";
		} 

		if ($edit_mode === true) {
			if(!isset($keywords['Genre'])) {$keywords['Genre']="";}
			if(!isset($keywords['Game Type'])) {$keywords['Game Type']="";}
			if(!isset($keywords['Story Mode'])) {$keywords['Story Mode']="";}
			if(!isset($keywords['Game Feature'])) {$keywords['Game Feature']="";}
			if(!isset($keywords['Game Mode'])) {$keywords['Game Mode']="";}
			
			$output .= '<tr><td>Developer</td><td>
			<input type="text" name="Developer" size="35" value="'. $game['Developer'].'">
			</td></tr>
			<tr><td>Publisher</td><td>
			<input type="text" name="Publisher" size="35" value="'. $game['Publisher'].'">
			</td></tr>
			
			<tr><td>Genre</td><td>';
			
			if($keywords['Genre']=="" && isset($steamgenrelist)){
				$keywords['Genre']=$steamgenrelist;
			}
			$output .= '<input type="text" name="Genre" size="35" value="'. $keywords['Genre'].'">';
			if(isset($steamgenrelist) && $keywords['Genre']<>$steamgenrelist){$output .= " ($steamgenrelist)";}
			$output .= '</td></tr>
			
			<tr><td>Game Type</td><td>';
			if($keywords['Game Type']=="" && isset($steamkeywordlist)){
				$keywords['Game Type']=$steamkeywordlist;
			}
			$output .= '<input type="text" name="GameType" size="35" value="'. $keywords['Game Type'].'">';
			if(isset($steamkeywordlist) && $keywords['Game Type']<>$steamkeywordlist){$output .= " ($steamkeywordlist)";}
			$output .= '</td></tr>
			
			<tr><td>Story Mode</td><td>
			<input type="text" name="StoryMode" size="35" value="'. $keywords['Story Mode'].'">
			</td></tr>
			
			<tr><td>Game Feature</td><td>';
			if($keywords['Game Feature']=="" && isset($steamfeaturelist)){
				$keywords['Game Feature']=$steamfeaturelist;
			}
			$output .= '<input type="text" name="GameFeature" size="35" value="'. $keywords['Game Feature'].'">';
			if(isset($steamfeaturelist) && $keywords['Game Feature']<>$steamfeaturelist){$output .= " ($steamfeaturelist)";} 
			$output .= '</td></tr>
			
			<tr><td>Game Mode</td><td>
			<input type="text" name="GameMode" size="35" value="'. $keywords['Game Mode'].'">
			</td></tr>';
			} else {
				if(isset($game['Developer']) && $game['Developer']<>""){
				$output .= '<tr>
				<td>Developer</td><td>';
				$output .= $game['Developer']; 				
				if(isset($Developer) && $Developer<>"" && $Developer<>$game['Developer']){
					$output .= '('. trim($Developer).')';
				}
				$output .= '</td></tr>';
			} 
			
			if(isset($game['Publisher']) && $game['Publisher']<>""){
				$output .= '<tr>
				<td>Publisher</td><td>';
				$output .= $game['Publisher']; 				
				if(isset($Publisher) && $Publisher<>"" && $Publisher<>$game['Publisher']){
					$output .= '('. trim($Publisher).')';
				}
				$output .= '</td></tr>';
			}
			
			$output .= $displaykwtable;
		}
		unset($displaykwtable);

		//DONE: Something is wrong here, when running on uniserver the following else block needs to be commented or unexpected else? - some ?tags did not have PHP (works on dreamhost)
		//DONE: Also, it seems to be putting the keywords cell in the the same space as Links.  - some ?tags did not have PHP (works on dreamhost)
	} else {
		$output .= '<tr><td colspan=2> ';
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
		$output .= '</tr></td>';
	}
	$output .= '</tbody></table>';
	if(isset($steamgenrelist) && $steamgenrelist<>"" && (!isset($keywords['Genre']) OR $keywords['Genre']<>$steamgenrelist)){
		$output .= '<br>Steam Genres (Genre): '. $steamgenrelist; 
	}
	
	if(isset($steamkeywordlist) && $steamkeywordlist<>"" && (!isset($keywords['Game Type']) OR $keywords['Game Type']<>$steamkeywordlist)){ 
		$output .= '<br>Steam Keywords (Game Type): '. $steamkeywordlist; 
	}
	
	if(isset($steamfeaturelist) && $steamfeaturelist<>"" && (!isset($keywords['Game Feature']) OR $keywords['Game Feature']<>$steamfeaturelist)){ 
		$output .= '<br>Steam Features (Game Feature): '. $steamfeaturelist;
	} 
	$output .= '</td></tr>';

	/* Raw data from calculations array * /
	$output .= "<tr>";
	$output .= "<th>Raw Data</th>";
	$output .= "<td>";
	//$output .= "First Play: " . $calculations[$game['Game_ID']]['firstplay'];
	//$output .= "<br>Last Play: " . $calculations[$game['Game_ID']]['lastplay'];
	//$output .= "<br>Achievements: " . (0+$calculations[$game['Game_ID']]['Achievements']);
	//$output .= "<br>Status: " . $calculations[$game['Game_ID']]['Status'];
	//$output .= "<br>Review: " . $calculations[$game['Game_ID']]['Review'];
	//$output .= "<br>Last Beat: " . $calculations[$game['Game_ID']]['LastBeat'];
	//$output .= "<br>Total Hours: " . timeduration($calculations[$game['Game_ID']]['totalHrs'],"seconds");
	//$output .= "<br>Grand Total: " . timeduration($calculations[$game['Game_ID']]['GrandTotal'],"seconds");
	//$output .= "<br>AchievementsLeft: " . $calculations[$game['Game_ID']]['AchievementsLeft'];
	//$output .= "<br>Active: " . $calculations[$game['Game_ID']]['Active'];
	//$output .= "<br>CountGame: " . $calculations[$game['Game_ID']]['CountGame'];
	$output .= "<br>LaunchDateValue: " . $calculations[$game['Game_ID']]['LaunchDateValue'];
	$output .= "<br>PrintBundles: " . $calculations[$game['Game_ID']]['PrintBundles'];
	$output .= "<br>Platforms: " . $calculations[$game['Game_ID']]['Platforms'];
	//$output .= "<br>PurchaseDate: " . $calculations[$game['Game_ID']]['PrintPurchaseDate'];
	//$output .= "<br>Paid: $" . $calculations[$game['Game_ID']]['Paid'];
	//$output .= "<br>BundlePrice: $" . $calculations[$game['Game_ID']]['BundlePrice'];
	$output .= "<br>TopBundleIDs: "; var_dump($calculations[$game['Game_ID']]['TopBundleIDs']);
	$output .= "<br>FirstBundle: ". $calculations[$game['Game_ID']]['FirstBundle'];
	$output .= "<br>Bundles: "; var_dump($calculations[$game['Game_ID']]['Bundles']);
	$output .= "<br>OS: "; var_dump($calculations[$game['Game_ID']]['OS']);
	$output .= "<br>Library: "; var_dump($calculations[$game['Game_ID']]['Library']);
	$output .= "<br>DRM: "; var_dump($calculations[$game['Game_ID']]['DRM']);
	$output .= "<br>itemsinbundle: " . $calculations[$game['Game_ID']]['itemsinbundle'];
	//$output .= "<br>SalePrice: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['SalePrice']);
	//$output .= "<br>AltSalePrice: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['AltSalePrice']);
	//$output .= "<br>TimeLeftToBeat: " . $calculations[$game['Game_ID']]['TimeLeftToBeat'];
	//$output .= "<br>LastPlayORPurchase: " . $calculations[$game['Game_ID']]['LastPlayORPurchase']; //Not used in ViewData
	/* $output .= "<hr align=left width=30%>"; * /
	//$output .= "<br>Launchperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhr']);
	//$output .= "<br>MSRPperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhr']);
	//$output .= "<br>Currentperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Currentperhr']);
	//$output .= "<br>Historicperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Historicperhr']);
	//$output .= "<br>Paidperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Paidperhr']);
	//$output .= "<br>Saleperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhr']);
	//$output .= "<br>Altperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhr']);
	/* $output .= "<hr align=left width=30%>"; * /
	//$output .= "<br>LaunchLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['LaunchLess1']);
	//$output .= "<br>MSRPLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPLess1']);
	//$output .= "<br>CurrentLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['CurrentLess1']);
	//$output .= "<br>HistoricLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['HistoricLess1']);
	//$output .= "<br>PaidLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['PaidLess1']);
	//$output .= "<br>SaleLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['SaleLess1']);
	//$output .= "<br>AltLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['AltLess1']);
	$output .= "<hr align=left width=30%>";
	$output .= "<br>LaunchLess2: " . timeduration($calculations[$game['Game_ID']]['LaunchLess2'],"hours");
	$output .= "<br>MSRPLess2: " . timeduration($calculations[$game['Game_ID']]['MSRPLess2'],"hours");
	$output .= "<br>CurrentLess2: " . timeduration($calculations[$game['Game_ID']]['CurrentLess2'],"hours");
	$output .= "<br>HistoricLess2: " . timeduration($calculations[$game['Game_ID']]['HistoricLess2'],"hours");
	$output .= "<br>PaidLess2: " . timeduration($calculations[$game['Game_ID']]['PaidLess2'],"hours");
	$output .= "<br>SaleLess2: " . timeduration($calculations[$game['Game_ID']]['SaleLess2'],"hours");
	$output .= "<br>AltLess2: " . timeduration($calculations[$game['Game_ID']]['AltLess2'],"hours");
	$output .= "<hr align=left width=30%>";
	$output .= "<br>LaunchHrsNext1: " . timeduration($calculations[$game['Game_ID']]['LaunchHrsNext1'],"hours");
	$output .= "<br>MSRPHrsNext1: " . timeduration($calculations[$game['Game_ID']]['MSRPHrsNext1'],"hours");
	$output .= "<br>CurrentHrsNext1: " . timeduration($calculations[$game['Game_ID']]['CurrentHrsNext1'],"hours");
	$output .= "<br>HistoricHrsNext1: " . timeduration($calculations[$game['Game_ID']]['HistoricHrsNext1'],"hours");
	$output .= "<br>PaidHrsNext1: " . timeduration($calculations[$game['Game_ID']]['PaidHrsNext1'],"hours");
	$output .= "<br>SaleHrsNext1: " . timeduration($calculations[$game['Game_ID']]['SaleHrsNext1'],"hours");
	$output .= "<br>AltHrsNext1: " . timeduration($calculations[$game['Game_ID']]['AltHrsNext1'],"hours");
	$output .= "<hr align=left width=30%>";
	$output .= "<br>LaunchHrsNext2: " . timeduration($calculations[$game['Game_ID']]['LaunchHrsNext2'],"hours");
	$output .= "<br>MSRPHrsNext2: " . timeduration($calculations[$game['Game_ID']]['MSRPHrsNext2'],"hours");
	$output .= "<br>CurrentHrsNext2: " . timeduration($calculations[$game['Game_ID']]['CurrentHrsNext2'],"hours");
	$output .= "<br>HistoricHrsNext2: " . timeduration($calculations[$game['Game_ID']]['HistoricHrsNext2'],"hours");
	$output .= "<br>PaidHrsNext2: " . timeduration($calculations[$game['Game_ID']]['PaidHrsNext2'],"hours");
	$output .= "<br>SaleHrsNext2: " . timeduration($calculations[$game['Game_ID']]['SaleHrsNext2'],"hours");
	$output .= "<br>AltHrsNext2: " . timeduration($calculations[$game['Game_ID']]['AltHrsNext2'],"hours");
	
	//$output .= "<br>Achievements Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['AchievementsPct'])."%";
	//$output .= "<br>Sale Price Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['SaleDiscount'])."%";
	//$output .= "<br>Alt Sale Price Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['AltSaleDiscount'])."%";
	//$output .= "<br>Paid Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['PaidDiscount'])."%";
	//$output .= "<br>Launch Per Hours to beat: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhrbeat']);
	
	
	//$output .= "<hr>";
	
	//$output .= "<hr>";
	//var_dump($calculations[$game['Game_ID']]);
	$output .= "</td>";
	$output .= "</tr>";
	/* */

	$output .= '<tr><th>Bundles</th><td>';
	//$purchases=getPurchases("",$conn);
	$purchaseobj=new Purchases("",$conn);
	$purchases=$purchaseobj->getPurchases();
	$purchaseIndex=makeIndex($purchases,"TransID");

	if(!isset($calculations[$game['Game_ID']]['TopBundleIDs'])) {
		$output .= "<b>No Bundle Found!</b>";
	} else {
		$output .= '<table>
		<thead><tr><th>Bundle ID</th><th>Title</th><th>Store</th><th>Date</th><th>Paid</th><th>Detail</th><th class="hidden">Debug</th></tr></thead>';
		foreach($calculations[$game['Game_ID']]['TopBundleIDs'] as $key => $bundleID){ 
			$output .= '<tr>
			<td>'. $bundleID.'</td>
			<td>'. $purchases[$purchaseIndex[$bundleID]]['Title'].'</td>
			<td>'. $purchases[$purchaseIndex[$bundleID]]['Store'].'</td>
			<td>';
			//$output .= Date("n/j/Y H:i:s",$purchases[$purchaseIndex[$bundleID]]['PurchaseTimeStamp']); 
			$output .= $purchases[$purchaseIndex[$bundleID]]['PrintPurchaseTimeStamp'];
			$output .= '</td>
			<td>'. $purchases[$purchaseIndex[$bundleID]]['Paid'].'</td>
			
			<td>
			<details>
			<summary>'. $purchases[$purchaseIndex[$bundleID]]['Title'].'</summary>
			
			<table>
			<thead><tr><th class="hidden">Game ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th>
			<th>Altwant</th><th>Althrs</th><th>SalePrice</th><th>AltSalePrice</th><th class="hidden">Debug</th></tr></thead>';
			
			//TODO: Fix this loop so it's not an embedded table. (make the previous cells multirow) 
			foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $gameinbundle ){ 
					$output .= '<tr>
					<td class="hidden">'. $gameinbundle['GameID'].'</td>
					<td><a href="viewgame.php?id='. $gameinbundle['GameID'].'">'. $calculations[$gameinbundle['GameID']]['Title'].'</a></td>
					<td>'. $gameinbundle['Type'].'</td>
					<td>'. booltext($gameinbundle['Playable']).'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['MSRP']).'</td>
					<td class="numeric">'. $gameinbundle['Want'].'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['HistoricLow']).'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['Altwant']).'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['Althrs']).'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['SalePrice']).'</td>
					<td class="numeric">$'. sprintf("%.2f",$gameinbundle['AltSalePrice']).'</td>';
					//$output .= '<td class="hidden">'. $gameinbundle['Debug'].'</td>';
					$output .= '</tr>';
			}
			$output .= '</table>
			</details>
			</td>
			
			<td class="hidden">'. print_r($purchases[$purchaseIndex[$bundleID]],true).'</td>
			</tr>';
		}
		$output .= '</table>';
	}	
	
	$output .= '</td></tr>';

	//TODO: use the javascript based spoiler code from the totals by week/month/year page
	/* this version puts all the data in one table but the collapse code does not work as is. 
	 * need to use the javascript based spoiler code from the totals by week/month/year page.
	 * /
	$output .= '<tr><th>Bundles</th><td>';
	$purchases=getPurchases("",$conn);
	$purchaseIndex=makeIndex($purchases,"TransID");
	
	$output .= '<table>
	<thead><tr>
	<th>Bundle ID</th><th>Title</th><th>Store</th><th>Date</th><th>Paid</th>
	<th class="hidden">Game ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th>
	<th>Altwant</th><th>Althrs</th><th>SalePrice</th><th>AltSalePrice</th><th class="hidden">Debug</th>
	<th class="hidden">Debug</th>
	</tr></thead>';
	foreach($calculations[$game['Game_ID']]['TopBundleIDs'] as $key => $bundleID){ 
		$bundleitemcount=count($purchases[$purchaseIndex[$bundleID]]['GamesinBundle']);
		$output .= '<tr>
		<td rowspan='. $bundleitemcount+1 .'>'. $bundleID.'</td>
		<td rowspan='. $bundleitemcount+1 .'>'. $purchases[$purchaseIndex[$bundleID]]['Title'].'</td>
		<td rowspan='. $bundleitemcount+1 .'>'. $purchases[$purchaseIndex[$bundleID]]['Store'].'</td>
		<td rowspan='. $bundleitemcount+1 .'>'. Date("n/j/Y H:i:s",$purchases[$purchaseIndex[$bundleID]]['PurchaseTimeStamp']).'</td>
		<td rowspan='. $bundleitemcount+1 .'>'. $purchases[$purchaseIndex[$bundleID]]['Paid'].'</td>
		
		<details>
		<summary><td>'. $purchases[$purchaseIndex[$bundleID]]['Title'].'</td></summary>';
		
		//TODO: Fix this loop so it's not an embedded table. (make the previous cells multirow) 
		foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $gameinbundle ){ 
				$output .= '<tr>
				<td class="hidden">'. $gameinbundle['GameID'].'</td>
				<td><a href="viewgame.php?id='. $gameinbundle['GameID'].'">'. $calculations[$gameinbundle['GameID']]['Title'].'</a></td>
				<td>'. $gameinbundle['Type'].'</td>
				<td>'. booltext($gameinbundle['Playable']).'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['MSRP']).'</td>
				<td class="numeric">'. $gameinbundle['Want'].'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['HistoricLow']).'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['Altwant']).'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['Althrs']).'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['SalePrice']).'</td>
				<td class="numeric">$'. sprintf("%.2f",$gameinbundle['AltSalePrice']).'</td>
				<td class="hidden">'. $gameinbundle['Debug'].'</td>
				</tr>';
		} 
		$output .= '</details>
		
		<td rowspan='. $bundleitemcount+1 .' class="hidden">'. print_r($purchases[$purchaseIndex[$bundleID]],true).'</td>
		</tr>';
	} 
	$output .= '</table>
	
	</td></tr>	';
	*/

	$output .= '<tr><th>Play History</th><td>';
	if (is_array($game['History']) && is_array($game['Activity'])) {
		$output .= '<details>
		<summary>
		<table><thead><tr><th class="hidden">ID</th><th>Games</th><th>First Play</th><th>Last Play</th><th class="hidden">Last time</th><th class="hidden">Total Hrs</th>
		<th class="hidden">Achievements</th><th>Status</th><th>Last Rating</th><th>Last Beat</th><th class="hidden">Base Game</th><th class="hidden">Launch Date</th>
		<th>Sub Total</th><th>Grand Total</th><th class="hidden">Week Play</th><th class="hidden">Month Play</th><th class="hidden">Year Play</th><th class="hidden">Week Achievements</th>
		<th class="hidden">Month Achievements</th><th class="hidden">Year Achievements</th>
		</tr></thead>';
		//TODO: Grand total for launchers and games with multiple DLC adds time played to earlier games even when they are not associated.
		//TODO: Add a column to pull achievements earned from SteamAPI aligned with historical play record.
		foreach ($game['Activity'] as $totals) {
			$output .= '<tr class="'. $totals['Status'].'">
			<td class="hidden numeric"><a href="viewgame.php?id='. $totals['ID'].'" target="_blank">'. $totals['ID'].'</a></td>
			<td class="text"><a href="viewgame.php?id='. $totals['ID'].'" target="_blank">'. $totals['Games'].'</a></td>
			<td class="numeric">'. $totals['firstplay'].'</td>
			<td class="numeric">'. $totals['lastplay'].'</td>
			<td class="hidden numeric">'. timeduration($totals['elapsed'],"seconds").'</td>
			<td class="hidden numeric">'. timeduration($totals['totalHrs'],"seconds").'</td>
			<td class="hidden numeric">'. $totals['Achievements'].'</td>
			<td class="numeric">'. $totals['Status'].'</td>
			<td class="numeric">'. $totals['Review'].'</td>
			<td class="numeric">'. $totals['LastBeat'].'</td>
			<td class="hidden numeric"><a href="viewgame.php?id='. $totals['Basegame'].'" target="_blank">'. $totals['Basegame'].'</a></td>
			<td class="hidden numeric">'. $totals['LaunchDate'].'</td>
			<td class="numeric">'. timeduration($totals['totalHrs'],"seconds").'</td>
			<td class="numeric">'. timeduration($totals['GrandTotal'],"seconds").'</td>'; /* Grand Total */
			$output .= '<td class="hidden numeric">'. timeduration($totals['weekPlay'],"seconds").'</td>
			<td class="hidden numeric">'. timeduration($totals['monthPlay'],"seconds").'</td>
			<td class="hidden numeric">'. timeduration($totals['yearPlay'],"seconds").'</td>
			<td class="hidden numeric">'. $totals['WeekAchievements'].'</td>
			<td class="hidden numeric">'. $totals['MonthAchievements'].'</td>
			<td class="hidden numeric">'. $totals['YearAchievements'].'</td>
			</tr>';
			
			unset($totals);
		}
		$output .= '</table>
		</summary>

		<table>
		<thead>
		<tr>
		<th></th>
		<th>Timestamp</th>';
		if(count($game['Activity'])>1){
			$output .= '<th>Title</th>';
		}
		$output .= '<th>System</th>
		<th>Data</th>
		<th>Time</th>
		<th>Notes</th>
		<th>Achievements</th>
		<th class="hidden">Achievement Type</th>
		<th>Status</th>
		<th>Review</th>
		<th>Keywords</th>
		<th class="hidden">Previous Start</th>
		<th>Elapsed</th>
		<th class="hidden">Prev Total (System)</th>
		<th>Total (System)</th>
		<th class="hidden">Prev Total</th>
		<th>Total</th>
		<th>Count Game</th>
		<th>Data Source</th>
		</tr>
		</thead>
		<tbody>';
		
		foreach ($game['History'] as $history) {
			$output .= '<tr>
			<td class="numeric"><a href="addhistory.php?HistID='. $history['HistoryID'].'" target=_blank>edit</a></td>
			<td class="numeric">'. str_replace(" ", "&nbsp;", $history['Timestamp']).'</td>';
			if(count($game['Activity'])>1){
				$output .= '<td class="text">'. $history['Game'].'</td>';
			}
			$output .= '<td class="text">'. $history['System'] .'</td>
			<td class="text">'. str_replace(" ", "&nbsp;", $history['Data']).'</td>
			<td class="numeric">'. timeduration((float)$history['Time'],"hours").'</td>
			<td class="text">'. nl2br(($history['Notes'] ?? "")).'</td>
			<td class="numeric">'. $history['Achievements'].'</td>
			<td class="hidden text">'. $history['AchievementType'].'</td>
			<td class="text">'. $history['Status'].'</td>
			<td class="numeric">'. $history['Review'].'</td>
			<td class="text">'. $history['KeyWords'].'</td>
			<td class="hidden numeric">'; if( isset($history['prevstart'])) {$output .= date("n/j/Y H:i:s",$history['prevstart']);} $output .= '</td>
			<td class="numeric">'. timeduration((float)$history['Elapsed'],"seconds").'</td>
			<td class="hidden numeric">'. timeduration((float)$history['prevTotSys'],"seconds").'</td>
			<td class="numeric">'. timeduration((float)$history['totalSys'],"seconds").'</td>
			<td class="hidden numeric">'. timeduration((float)$history['prevTotal'],"seconds").'</td>
			<td class="numeric">'. timeduration((float)$history['Total'],"seconds").'</td>
			<td class="text">'. boolText($history['FinalCountHours']).'</td>
			<td class="text">'. $history['RowType'].'</td>
			</tr>';
		}
		
		$output .= '</tbody>
		</table>
		</details>';
	}
	
	$output .= '<a href="addhistory.php?GameID='. $game['Game_ID'].'">Add History Record</a>
	</td></tr>

	<tr><th>Games in series</th><td>';
	
	$sql="select 
		`gl_products`.`Game_ID`,
		`gl_products`.`Title`,
		`gl_products`.`Type`,
		`gl_products`.`Playable`,
		`gl_products`.`LaunchDate`,
		`products_1`.`Title` 'ParentGame'
		FROM `gl_products`
		JOIN `gl_products` products_1
		ON `products_1`.`Game_ID` = `gl_products`.`ParentGameID`
		where `gl_products`.`Series`='". mysqli_real_escape_string($conn, $game['Series'])."' 
		order by `LaunchDate` ASC";
		/* */
		
		if($result = $conn->query($sql)){
			$output .= '<table>
			<thead><tr><th class="hidden">ID</th><th>Game</th><th>Parent Game</th><th>Type</th><th>Playable</th><th>Launch Date</th><th>Status</th><th>Review</th><th>Last Play</th></tr></thead>
			<tbody>';
			while($row2 = $result->fetch_assoc()) {
				$output .= '<tr>
				<td class="hidden"><a href="viewgame.php?id='. $row2['Game_ID'].'" target="_blank">'. $row2['Game_ID'].'</a></td>
				<td><a href="viewgame.php?id='. $row2['Game_ID'].'" target="_blank">'. $row2['Title'].'</a></td>
				<td>'. $row2['ParentGame'].'</td>
				<td>'. $row2['Type'].'</td>
				<td>'. booltext($row2['Playable']).'</td>
				<td>'. $row2['LaunchDate'].'</td>
				<td>'. $calculations[$row2['Game_ID']]['Status'].'</td>
				<td>'. $calculations[$row2['Game_ID']]['Review'].'</td>
				<td>'. $calculations[$row2['Game_ID']]['lastplay'].'</td>
				</tr>';
			}
			$output .= '</tbody>
			</table>';
		}
		
	$output .= '</td></tr>
	<tr><th>All Game components</th><td>';
	
	//TODO: Move the All Game Components query into a sub-function of the datagetters such as getCalculations
	$sql="select 
		`gl_products`.`Game_ID`,
		`gl_products`.`Title`,
		`gl_products`.`Type`,
		`gl_products`.`Playable`,
		`gl_products`.`LaunchDate`
		FROM `gl_products`
		where `gl_products`.`ParentGameID`='". $game['ParentGameID'] . "' 
		order by `LaunchDate` ASC";
	$result = $conn->query($sql);
	
	$output .= '<table>
	<thead><tr><th class="hidden">ID</th><th>Game</th><th>Type</th><th>Playable</th><th>Launch Date</th></tr></thead>
	<tbody>';
	
	$row2 = $result->fetch_assoc();
		do {
			$output .= '<tr>
			<td class="hidden"><a href="viewgame.php?id='. $row2['Game_ID'].'" target="_blank">'. $row2['Game_ID'].'</a></td>
			<td><a href="viewgame.php?id='. $row2['Game_ID'].'" target="_blank">'. $row2['Title'].'</a></td>
			<td>'. $row2['Type'].'</td>
			<td>'. booltext($row2['Playable']).'</td>
			<td>'. $row2['LaunchDate'].'</td>
			</tr>';			
		} while($row2 = $result->fetch_assoc());
	$output .= '</tbody>
	</table>
	</td></tr>
	
	<tr><th>Copies</th><td colspan=2>';
	//TODO: only lists copies that have the same parent. Add items that have the same base also.
	$sql = "SELECT `ItemID`, `ParentProductID`, `Notes`, `SizeMB`, `DRM`, `OS`, `ActivationKey`, `DateAdded`, `Time Added`, `gl_items`.`Sequence` as 'items_Sequence', 
	`Library`, `Title`, `Store`, `BundleID`, `gl_transactions`.`Tier`, `PurchaseDate`, `PurchaseTime`, `gl_transactions`.`Sequence` as 'trans_Sequence', `Paid`, `Credit Used`, `Bundle Link`
	from `gl_items` 
	JOIN `gl_transactions`  ON `gl_items`.`TransID` = `gl_transactions`.`TransID`
	WHERE `gl_items`.`ParentProductID` = " . $game['ParentGameID']
	. " OR `gl_items`.`ParentProductID` = " . $game['Game_ID']
	. " OR `gl_items`.`ProductID` = " . $game['Game_ID']
	. " OR `gl_items`.`ProductID` = " . $game['ParentGameID'];
	
	if($result = $conn->query($sql)) {
		$output .= '<table>
		<thead><tr><th>Item ID</th><th>Parent Product ID</th><th>Notes</th><th>Size (MB)</th><th>DRM</th><th>OS</th><th>Activation Key</th><th>Date Added</th><th>Library</th><th>Bundle</th><th>Store</th>
		<th>Bundle ID</th><th class="hidden">Parent Bundle</th><th>Tier</th><th>Purchase Date</th><th>Paid</th><th>Credit Used</th></tr></thead>
		<tbody>';
			// output data of each row
			while($row2 = $result->fetch_assoc()) {
				$output .= '<tr>
				<td><a href="viewitem.php?id='. $row2['ItemID'].'" target="_blank">'. $row2['ItemID'].'</a></td>
				<td><a href="viewgame.php?id='. $row2['ParentProductID'].'" target="_blank">'. $row2['ParentProductID'].'</a></td>
				<td>'. nl2br($row2['Notes'] ?? "").'</td>
				<td>'. $row2['SizeMB'].'</td>
				<td>'. $row2['DRM'].'</td>
				<td>'. $row2['OS'].'</td>
				<td>'. $row2['ActivationKey'].'</td>
				
				<td>'. date("n/j/Y H:i:s",strtotime($row2['DateAdded'] . " " . $row2['Time Added'])+$row2['items_Sequence']).'</td>
				
				<td>'. $row2['Library'].'</td>
				<td>'. $row2['Title'].'</td>
				<td>';
				if($row2['Bundle Link']<>"") {
					$output .= "<a href=\"";
					if(substr($row2['Bundle Link'],0,4)<>"http"){
						$output .= "http://";
					}
					$output .= $row2['Bundle Link']."\" target='_blank'>".$row2['Store'] ."</a>" ;
				} else {
					$output .= $row2['Store'] ;
				}
				$output .= '</td>
				<td><a href="viewbundle.php?id='. $row2['BundleID'].'" target="_blank">'. $row2['BundleID'].'</a></td>
				<td class="hidden">'. $row2['BundleID'].'</td>
				<td>'. $row2['Tier'].'</td>
				<td>'. date("n/j/Y H:i:s",strtotime($row2['PurchaseDate'] . " " . $row2['PurchaseTime'])+$row2['trans_Sequence']).'</td>
				<td>'. $row2['Paid'].'</td>
				<td>'. $row2['Credit Used'].'</td>
				</tr>';
			}
		$output .= '</tbody>
		</table>';
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	$output .= '</td></tr>
	
	</table>';
	
	if($edit_mode) {
		$output .= "<div><input type='submit' value='Save'></div>";
	} else {
		$output .= "<div><a href='viewgame.php?id=". $_GET['id']."&edit=1'>Edit</a></div>";
	}
	$output .= '</form>';

	if($game['SteamID']>0 and $ShowSteamThings){
		//$newscount=5;
		//$length=500;
		//$newsarray=GetGameNews($game['SteamID'],$newscount,$length);
		
		$newsarray=$steamAPI->GetSteamAPI("GetGameNews"); 
		if(isset($newsarray['appnews']['newsitems'])){
			$output .= $steamformat->formatnews($newsarray);
		}
	}
}

		return $output;
	}
}	