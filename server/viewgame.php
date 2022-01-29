<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

require_once $GLOBALS['rootpath']."/inc/SteamFormat.class.php";
require_once $GLOBALS['rootpath']."/inc/SteamScrape.class.php";
require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

//$ShowSteamThings=false; // Steam scraping is broken for now.
$ShowSteamThings=true;

$title="View Game";
echo Get_Header($title);

$lookupgame=lookupTextBox("Product", "ProductID", "id");
echo $lookupgame["header"];

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
			echo "i equals 2";
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
		echo "Game record updated successfully";
		echo "<br>";

		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $update_SQL.";\r\n", FILE_APPEND | LOCK_EX);
		
	} else {
		echo "Error updating game record: " . $conn->error;
		echo "<br>";
		echo $update_SQL;
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
				echo "Error updating keyword record using sql " . $kwsql1 . ": " . $conn->error;
				echo "<br>";
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
				echo "Error updating keyword record using sql " . $kwsql1.$kwsql2 . ":<br> " . $conn->error;
				echo "<br>";
			}
		}
	}	
}

if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
	?>
		Please specify a game by ID.
		<form method="Get">
			<?php echo $lookupgame["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupgame["lookupBox"];
	
} else {
	//DONE: cleanse get[ID] to make sure it is only a numeric value - Rejects id if not numeric
	$game=getGameDetail($_GET['id'],$conn);
	$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
	$settings=getsettings($conn);

	//<form action="<?php echo $_SERVER['PHP_SELF'];>" method="post">

	?>
	<form method="post">
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
		<td>
			<input type="hidden" name="ID" value="<?php echo $game['Game_ID']; ?>">
			<a href='<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $game['Game_ID']; ?>' target='_blank'><?php echo $game['Game_ID']; ?></a>
		</td>

		<td>
			<?php if ($edit_mode === true) { ?>
			<input type="text" name="Title" size="60" value="<?php echo $game['Title']; ?>">
			<?php } else { echo $game['Title']; } ?>
		</td>

		<td>
			<?php 
			//DONE: Need to add lookup of parentgameID
			
			if ($edit_mode === true) { ?>
			ID: <input type="number" id="ParentGameID" name="ParentGameID" max="99999" min="0" value="<?php echo $game['ParentGameID']; ?>">
			Parent Game: <input type="text" id="ParentGame" name="ParentGame" size="60" value="<?php echo ($game['ParentGame']=="" ? $calculations[$game['ParentGameID']]['Title'] : $game['ParentGame']); ?>">
			
			<script>
			  $(function() {
					$('#ParentGame').autocomplete({ 
						source: "./ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ParentGameID").val(ui.item.id);
						} }
					);
				} );
			</script>
			
			
			<?php } else { ?>
			<a href='viewgame.php?id=<?php echo $game['ParentGameID']; ?>' target='_blank'><?php echo $game['ParentGameID']; ?></a> 
			<?php echo $game['ParentGame']; 
			
				//If the ID for parent game do not match show the alternate value in parenthese
				if($calculations[$game['ParentGameID']]['Title']<>$game['ParentGame']){
					echo " (".$calculations[$game['ParentGameID']]['Title'].")";
				}
				
			} ?>
		</td>

		<td>
			<?php if ($edit_mode === true) { ?>
			<input type="text" name="Series" size="35" value="<?php echo $game['Series']; ?>">
			<?php } else { 
			echo $game['Series']; 
			} ?>
		</td>
		</tr>
		</table>
		</td>
		
	<?php
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
		?>
		<td rowspan=12 width=800 valign=top>
		<?php 
		/* Return the Steam page for troubleshooting * /
		//echo $url . "<p>";
		var_dump($result); 
		/* */
		
		if($showAppDetails){ ?>
		<details>
		<summary>AppDetails</summary>
		<?php echo $steamformat->formatAppDetails($appdetails[$game['SteamID']],false);?>
		</details>
		<?php } 
		
		/*
		//@codeCoverageIgnoreStart
		if($showSteamPics){ ?>
			<details>
			<summary>steampics</summary>
			<?php echo $steamformat->formatSteamPics($steampics['apps'][$game['SteamID']]);
			//var_dump($steampics); ?>
			</details>
		<?php }
		//@codeCoverageIgnoreEnd
		*/
		
		//TODO: Check for API data even if there is no store page. Currently skipps if steam redirects to home page.
		//if($result!=false) { 
		if($SteamPage->pageExists) { 
		?>
			<details>
			<summary>Steam API</summary>
			<img src='http://cdn.akamai.steamstatic.com/steam/apps/<?php echo $game['SteamID']; ?>/header.jpg'>
			<br>
			<?php echo $description; 
			echo $steamformat->formatSteamAPI($resultarray,$userstatsarray); ?>
			</details>
		<?php } ?>
		
		<?php echo $steamformat->formatSteamLinks($game['SteamID'],$settings['LinkSteam']); ?>
		</td>
		<?php } ?>
	</tr>
	
	<tr><th height=10>Attributes</th><td>
		<table><thead><tr><th>Type</th><th>Playable</th><th>Status</th><th>Active</th><th>Count Game</th><th>Achievements</th><th>Cards</th></tr></thead>
		<tr><td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="Type" value="<?php echo $game['Type']; ?>">
		<?php } else { echo $game['Type']; } ?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<label><input type="radio" name="Playable" value="1"
			<?php if ($game['Playable']==1) {echo "checked=\"checked\"";} ?>
		> Playable</label><br>
		
		<label><input type="radio" name="Playable" value="0"
		<?php if ($game['Playable']==0) {echo "checked=\"checked\"";} ?>
		> Not Playable</label>
		<?php } else { echo boolText($game['Playable']); } ?>
		</td>
		
		<td><?php echo $calculations[$game['Game_ID']]['Status']; ?></td>
		<td><?php echo boolText($calculations[$game['Game_ID']]['Active']); ?></td>
		<td><?php echo boolText($calculations[$game['Game_ID']]['CountGame']); ?></td>

		<?php
		if($game['SteamAchievements']==0){
			$game['SteamAchievements']=0;
		}

		if(isset($resultarray['game']['availableGameStats']['achievements'])) {
			$achivementcounter=count($resultarray['game']['availableGameStats']['achievements']);
		} 
		
		if(isset($achivementcounter) && $achivementcounter<>$game['SteamAchievements']){
			$game['SteamAchievements']=$achivementcounter;
		}
		?>
		<td><?php echo (0+$calculations[$game['Game_ID']]['Achievements']); ?> of 
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="SteamAchievements" max="9999" min="0" value="<?php echo $game['SteamAchievements'] ?>">
		<?php } else { echo (int) $game['SteamAchievements']; } ?>

		(<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['AchievementsPct']); ?>% | 
		 <?php echo $calculations[$game['Game_ID']]['AchievementsLeft']; ?> Left)
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="SteamCards" max="100" min="0" value="<?php echo $game['SteamCards']; ?>">
		<?php } else { echo (int)$game['SteamCards']; } ?>
		</td>
		
		</tr></table>
	</td></tr>

	<tr><th height=10>Ratings</th><td>
		<table><thead><tr><th>Want</th><th>Metacritic</th><th>Metacritic User</th><th>Steam Rating</th><th>Review</th></tr><tr></thead>
		<td>
		<?php if ($edit_mode === true) { ?>
		<select name="Want" >
		<option value='1' <?php if ($game['Want']==1) { echo 'selected="selected"';} ?> >1</option>
		<option value='2' <?php if ($game['Want']==2) { echo 'selected="selected"';} ?> >2</option>
		<option value='3' <?php if ($game['Want']==3) { echo 'selected="selected"';} ?> >3</option>
		<option value='4' <?php if ($game['Want']==4) { echo 'selected="selected"';} ?> >4</option>
		<option value='5' <?php if ($game['Want']==5) { echo 'selected="selected"';} ?> >5</option>
		</select>
		<?php } else { echo $game['Want']; }?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Metascore" max="100" min="0" value="<?php echo $game['Metascore']; ?>">
		<?php } else { echo $game['MetascoreLinkCritic']; } ?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="UserMetascore" max="100" min="0" value="<?php echo $game['UserMetascore']; ?>">
		<?php } else { echo $game['MetascoreLinkUser']; } ?>
		</td>
		
		<td>
		<?php 
		//DONE: Move this up to where these values are set.

		if ($edit_mode === true) { ?>
		<input type="number" name="SteamRating" max="100" min="0" value="<?php echo $game['SteamRating']; ?>">
		<?php } else { echo $game['SteamRating']; } 
		
		if (isset($newsteamrating) && $game['SteamRating']<>$newsteamrating){
			echo " (". $newsteamrating . ")";
		} ?>
		</td>

		<td><?php echo $calculations[$game['Game_ID']]['Review']; ?></td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Dates</th>
		<?php
		//DONE: Move this up to where these values are set.
		?>
		
		<td><table><thead><tr><th>Launch</th><th>Updated</th><th>First Play</th><th>Last Play</th><th>Last Beat</th><th>Purchase</th><th>Last Play / Purchase</th></tr></thead>
		<tr><td>
		<?php if ($edit_mode === true) { ?>
		<input type="date" name="LaunchDate" value="<?php 
			//echo date("Y-m-d",strtotime($game['LaunchDate'])); 
			echo $game['LaunchDate']->format("Y-m-d");
			?>">
		<?php } else { echo $game['LaunchDate']->format("n/j/Y"); }
		
		if(isset($PubDate)){
			echo "<br>".trim($PubDate);
		} ?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="date" name="DateUpdated" value="<?php echo date("Y-m-d"); ?>"> 
		<br><?php echo $game['DateUpdated']; 
		} else { echo $game['DateUpdated']; } ?>
		</td>
		
		<td><?php echo $calculations[$game['Game_ID']]['firstplay']; ?></td>
		<td><?php echo $calculations[$game['Game_ID']]['lastplay']; 
			if($calculations[$game['Game_ID']]['lastplay']<>""){ ?>
			<br>(<?php echo $calculations[$game['Game_ID']]['DaysSinceLastPlay']; ?> Days)
			<?php } ?>
		</td>
		
		<td><?php echo $calculations[$game['Game_ID']]['LastBeat']; ?></td>
		<td><?php 
		if(isset($calculations[$game['Game_ID']]['PurchaseDateTime'])) {
			echo $calculations[$game['Game_ID']]['PurchaseDateTime']->format("n/j/Y g:i:s A"); 
		}
		?><br>(<?php echo $calculations[$game['Game_ID']]['DaysSincePurchaseDate']; ?> Days)</td>
		<td><?php echo $calculations[$game['Game_ID']]['LastPlayORPurchase']; ?><br>(<?php echo $calculations[$game['Game_ID']]['DaysSinceLastPlayORPurchase']; ?> Days)</td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Price</th>
	<td><table><thead><tr><th></th><th>Launch</th><th>MSRP</th><th>Current</th><th>Historic</th><th>CPI Launch</th><th>Paid</th><th>Bundle</th><th>Sale</th><th>Alt Sale</th></tr></thead>
	<tr><th>Price</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="LaunchPrice" size="5" value="<?php echo $game['LaunchPrice']; ?>">
		<?php } else { echo "$".$game['LaunchPrice']; } ?>
		</td>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="MSRP" size="5" value="<?php echo $game['MSRP']; ?>">
		<?php } else { echo "$".$game['MSRP']; } ?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="CurrentMSRP" size="5" value="<?php echo $game['CurrentMSRP']; ?>">
		<?php } else { echo "$".$game['CurrentMSRP']; } ?>
		</td>
		
		<td>
		<?php if ($edit_mode === true) { 
			if ($game['LowDate']==0) {
				$useLowDate=$game['LaunchDate']->format("n/j/Y");
			} else {
				$useLowDate=$game['LowDate'];
			} ?>
		<input type="text" name="HistoricLow" size="5" value="<?php echo $game['HistoricLow']; ?>">
		<br><input type="date" name="LowDate" value="<?php echo date("Y-m-d",strtotime($useLowDate)); ?>">
		<?php } else { echo "$".$game['HistoricLow']."<br>".$game['LowDate']; } ?>
		</td>
		<?php /* */ ?>
		
		
		<td><?php 
		//TODO: Move CPI to a row and a row for dates to calulate eache price type.
		//echo $game['CPILaunch']; 
		?></td>
		<td>$<?php echo $calculations[$game['Game_ID']]['Paid']; ?></td>
		<td>$<?php echo ($calculations[$game['Game_ID']]['BundlePrice'] ?? 0); ?></td>
		<td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['SalePrice']); ?></td>
		<td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['AltSalePrice']); ?></td>
	</tr><tr><th>Discount</th>	
		<?php /* */ ?>
		<!-- Launch -->        <td></td>
		<!-- MSRP -->          <td>0%</td> 
		<!-- Current Price --> <td></td> 
		<!-- Historic Low -->  <td></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td><?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['PaidVariancePct']); ?>%</td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td><?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['SaleVariancePct']); ?>%</td> 
		<!-- Alt Sale -->      <td><?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['AltSaleVariancePct']); ?>%</td> 
	</tr><tr><th>Per Hour (Played)</th>
		<!-- Launch -->        <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhr']); ?></td>
		<!-- MSRP -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhr']); ?></td> 
		<!-- Current Price --> <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Currentperhr']); ?></td> 
		<!-- Historic Low -->  <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Historicperhr']); ?></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Paidperhr']); ?></td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhr']); ?></td> 
		<!-- Alt Sale -->      <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhr']); ?></td> 
	</tr><tr><th>Per Hour (Time To Beat)</th>
		<!-- Launch -->        <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhrbeat']); ?></td>
		<!-- MSRP -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhrbeat']); ?></td> 
		<!-- Current Price --> <td></td> 
		<!-- Historic Low -->  <td></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td></td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhrbeat']); ?></td> 
		<!-- Alt Sale -->      <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhrbeat']); ?></td> 
	</tr><tr><th>1 hour less</th>
		<!-- Launch -->        <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['LaunchLess1']); ?></td>
		<!-- MSRP -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPLess1']); ?></td> 
		<!-- Current Price --> <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['CurrentLess1']); ?></td> 
		<!-- Historic Low -->  <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['HistoricLess1']); ?></td> 
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['PaidLess1']); ?></td> 
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['SaleLess1']); ?></td> 
		<!-- Alt Sale -->      <td>$<?php echo sprintf("%.2f",$calculations[$game['Game_ID']]['AltLess1']); ?></td> 
	</tr><tr><th>Time to $0.01 less</th>
		<!-- Launch -->        <td><?php echo $calculations[$game['Game_ID']]['LaunchPriceObj']->getHoursTo01LessPerHour(true); ?></td>
		<!-- MSRP -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['MSRPLess2'],"hours"); ?></td>
		<!-- Current Price --> <td><?php echo timeduration($calculations[$game['Game_ID']]['CurrentLess2'],"hours"); ?></td>
		<!-- Historic Low -->  <td><?php echo timeduration($calculations[$game['Game_ID']]['HistoricLess2'],"hours"); ?></td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['PaidLess2'],"hours"); ?></td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['SaleLess2'],"hours"); ?></td>
		<!-- Alt Sale -->      <td><?php echo timeduration($calculations[$game['Game_ID']]['AltLess2'],"hours"); ?></td>
	</tr><tr><th>Time to next position</th>
		<!-- Launch -->        <td><?php echo timeduration($calculations[$game['Game_ID']]['LaunchHrsNext1'],"hours"); ?></td>
		<!-- MSRP -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['MSRPHrsNext1'],"hours"); ?></td>
		<!-- Current Price --> <td><?php echo timeduration($calculations[$game['Game_ID']]['CurrentHrsNext1'],"hours"); ?></td>
		<!-- Historic Low -->  <td><?php echo timeduration($calculations[$game['Game_ID']]['HistoricHrsNext1'],"hours"); ?></td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['PaidHrsNext1'],"hours"); ?></td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['SaleHrsNext1'],"hours"); ?></td>
		<!-- Alt Sale -->      <td><?php echo timeduration($calculations[$game['Game_ID']]['AltHrsNext1'],"hours"); ?></td>
	</tr><tr><th>Time to next active position</th>
		<!-- Launch -->        <td><?php echo timeduration($calculations[$game['Game_ID']]['LaunchHrsNext2'],"hours"); ?></td>
		<!-- MSRP -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['MSRPHrsNext2'],"hours"); ?></td>
		<!-- Current Price --> <td><?php echo timeduration($calculations[$game['Game_ID']]['CurrentHrsNext2'],"hours"); ?></td>
		<!-- Historic Low -->  <td><?php echo timeduration($calculations[$game['Game_ID']]['HistoricHrsNext2'],"hours"); ?></td>
		<!-- CPI Launch -->    <td></td> 
		<!-- Paid -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['PaidHrsNext2'],"hours"); ?></td>
		<!-- Bundle -->        <td></td> 
		<!-- Sale -->          <td><?php echo timeduration($calculations[$game['Game_ID']]['SaleHrsNext2'],"hours"); ?></td>
		<!-- Alt Sale -->      <td><?php echo timeduration($calculations[$game['Game_ID']]['AltHrsNext2'],"hours"); ?></td>
	</tr>
	</table>
	</td></tr>
	
	<tr><th height=10>Times</th>
		<td><table><thead><tr><th>Time To Beat</th><th>Total Hours</th><th>Grand Total</th><th>TimeLeftToBeat</th></tr><tr></thead>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="TimetoBeat" size="5" value="<?php echo $game['TimeToBeat']; ?>"> (hours)
		<?php } else { echo $game['TimeToBeatLink2']; } ?>
		</td>
		<td><?php echo timeduration($calculations[$game['Game_ID']]['totalHrs'],"seconds"); ?></td>
		<td><?php echo timeduration($calculations[$game['Game_ID']]['GrandTotal'],"seconds"); ?></td>
		<td><?php echo timeduration($calculations[$game['Game_ID']]['TimeLeftToBeat'],"hours"); ?></td>
		</tr></table>
	</td></tr>

	<tr><th height=10>Links</th><td>
		<table><thead><tr><th>GOG</th><th class="hidden">Desura</th><th>Is There Any Deal</th><th>Steam</th><th>Metacritic</th><th>How Long to Beat</th></tr></thead>
		<td><?php echo $game['GOGLink']; ?></td>
		<td class="hidden"><?php echo $game['DesuraLink']; ?></td>
		<td><?php echo $game['isthereanydealLink']; ?></td>
		<td><?php echo $game['SteamLinks']; ?></td>
		<td><?php echo $game['MetascoreLink']; ?></td>
		<td><?php echo $game['TimeToBeatLink']; ?></td>
		</tr>
		<?php if ($edit_mode === true) { ?>
		<tr>
		<td><input type="text" name="GOGID" size="10" value="<?php echo $game['GOGID']; ?>"></td>
		<td class="hidden"><input type="text" name="DesuraID" size="10" value="<?php echo $game['DesuraID']; ?>"></td>
		<td><input type="text" name="isthereanydealID" size="10" value="<?php echo $game['isthereanydealID']; ?>"></td>
		<td><input type="text" name="SteamID" size="5" value="<?php echo $game['SteamID']; ?>"></td>
		<td><input type="text" name="MetascoreID" size="20" value="<?php echo $game['MetascoreID']; ?>"></td>
		<td><input type="text" name="TimeTobeatID" size="10" value="<?php echo $game['TimeToBeatID']; ?>"></td>
		</tr>
		<?php } ?>
		</table>
	</td></tr>

	<tr><th>Keywords</th><td valign=top>
	<table>
	<thead>
	<tr><th>Type</th><th>Keyword</th></tr></thead>
	<tbody>
	<?php 
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
			?>
			
			<tr><td>Developer</td><td>
			<input type="text" name="Developer" size="35" value="<?php echo $game['Developer'];?>">
			</td></tr>
			<tr><td>Publisher</td><td>
			<input type="text" name="Publisher" size="35" value="<?php echo $game['Publisher'];?>">
			</td></tr>
			
			<tr><td>Genre</td><td>
			<?php
			if($keywords['Genre']=="" && isset($steamgenrelist)){
				$keywords['Genre']=$steamgenrelist;
			}
			?>
			<input type="text" name="Genre" size="35" value="<?php echo $keywords['Genre'];?>">
			<?php if(isset($steamgenrelist) && $keywords['Genre']<>$steamgenrelist){echo " ($steamgenrelist)";} ?>
			</td></tr>
			
			<tr><td>Game Type</td><td>
			<?php 
			if($keywords['Game Type']=="" && isset($steamkeywordlist)){
				$keywords['Game Type']=$steamkeywordlist;
			}
			?>
			<input type="text" name="GameType" size="35" value="<?php echo $keywords['Game Type'];?>">
			<?php if(isset($steamkeywordlist) && $keywords['Game Type']<>$steamkeywordlist){echo " ($steamkeywordlist)";} ?>
			</td></tr>
			
			<tr><td>Story Mode</td><td>
			<input type="text" name="StoryMode" size="35" value="<?php echo $keywords['Story Mode'];?>">
			</td></tr>
			
			<tr><td>Game Feature</td><td>
			<?php
			if($keywords['Game Feature']=="" && isset($steamfeaturelist)){
				$keywords['Game Feature']=$steamfeaturelist;
			}
			?>
			<input type="text" name="GameFeature" size="35" value="<?php echo $keywords['Game Feature'];?>">
			<?php if(isset($steamfeaturelist) && $keywords['Game Feature']<>$steamfeaturelist){echo " ($steamfeaturelist)";} ?>
			</td></tr>
			
			<tr><td>Game Mode</td><td>
			<input type="text" name="GameMode" size="35" value="<?php echo $keywords['Game Mode'];?>">
			</td></tr>
			
			
		<?php } else { ?>
			<?php if(isset($game['Developer']) && $game['Developer']<>""){ ?>
				<tr>
				<td>Developer</td><td>
				<?php echo $game['Developer']; 				
				if(isset($Developer) && $Developer<>"" && $Developer<>$game['Developer']){ ?>
					(<?php echo trim($Developer);?>)
				<?php } ?>
				</td></tr>
			<?php } 
			
			if(isset($game['Publisher']) && $game['Publisher']<>""){ ?>
				<tr>
				<td>Publisher</td><td>
				<?php echo $game['Publisher']; 				
				if(isset($Publisher) && $Publisher<>"" && $Publisher<>$game['Publisher']){ ?>
					(<?php echo trim($Publisher);?>)
				<?php } ?>
				</td></tr>
			<?php } ?>
			
			<?php
			echo $displaykwtable;
		}
		unset($displaykwtable);

		//DONE: Something is wrong here, when running on uniserver the following else block needs to be commented or unexpected else? - some ?tags did not have PHP (works on dreamhost)
		//DONE: Also, it seems to be putting the keywords cell in the the same space as Links.  - some ?tags did not have PHP (works on dreamhost)
	} else { ?>
		<tr><td colspan=2> 
		<?php trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql); ?>
		</tr></td>
	<?php } ?>
	</tbody></table>
	<?php if(isset($steamgenrelist) && $steamgenrelist<>"" && (!isset($keywords['Genre']) OR $keywords['Genre']<>$steamgenrelist)){ ?>
		<br>Steam Genres (Genre): <?php echo $steamgenrelist; 
	}
	
	if(isset($steamkeywordlist) && $steamkeywordlist<>"" && (!isset($keywords['Game Type']) OR $keywords['Game Type']<>$steamkeywordlist)){ ?>
		<br>Steam Keywords (Game Type): <?php echo $steamkeywordlist; 
	}
	
	if(isset($steamfeaturelist) && $steamfeaturelist<>"" && (!isset($keywords['Game Feature']) OR $keywords['Game Feature']<>$steamfeaturelist)){ ?>
		<br>Steam Features (Game Feature): <?php $steamfeaturelist;
	} ?>
	</td></tr>

	<?php
	/* Raw data from calculations array * /
	echo "<tr>";
	echo "<th>Raw Data</th>";
	echo "<td>";
	//echo "First Play: " . $calculations[$game['Game_ID']]['firstplay'];
	//echo "<br>Last Play: " . $calculations[$game['Game_ID']]['lastplay'];
	//echo "<br>Achievements: " . (0+$calculations[$game['Game_ID']]['Achievements']);
	//echo "<br>Status: " . $calculations[$game['Game_ID']]['Status'];
	//echo "<br>Review: " . $calculations[$game['Game_ID']]['Review'];
	//echo "<br>Last Beat: " . $calculations[$game['Game_ID']]['LastBeat'];
	//echo "<br>Total Hours: " . timeduration($calculations[$game['Game_ID']]['totalHrs'],"seconds");
	//echo "<br>Grand Total: " . timeduration($calculations[$game['Game_ID']]['GrandTotal'],"seconds");
	//echo "<br>AchievementsLeft: " . $calculations[$game['Game_ID']]['AchievementsLeft'];
	//echo "<br>Active: " . $calculations[$game['Game_ID']]['Active'];
	//echo "<br>CountGame: " . $calculations[$game['Game_ID']]['CountGame'];
	echo "<br>LaunchDateValue: " . $calculations[$game['Game_ID']]['LaunchDateValue'];
	echo "<br>PrintBundles: " . $calculations[$game['Game_ID']]['PrintBundles'];
	echo "<br>Platforms: " . $calculations[$game['Game_ID']]['Platforms'];
	//echo "<br>PurchaseDate: " . $calculations[$game['Game_ID']]['PrintPurchaseDate'];
	//echo "<br>Paid: $" . $calculations[$game['Game_ID']]['Paid'];
	//echo "<br>BundlePrice: $" . $calculations[$game['Game_ID']]['BundlePrice'];
	echo "<br>TopBundleIDs: "; var_dump($calculations[$game['Game_ID']]['TopBundleIDs']);
	echo "<br>FirstBundle: ". $calculations[$game['Game_ID']]['FirstBundle'];
	echo "<br>Bundles: "; var_dump($calculations[$game['Game_ID']]['Bundles']);
	echo "<br>OS: "; var_dump($calculations[$game['Game_ID']]['OS']);
	echo "<br>Library: "; var_dump($calculations[$game['Game_ID']]['Library']);
	echo "<br>DRM: "; var_dump($calculations[$game['Game_ID']]['DRM']);
	echo "<br>itemsinbundle: " . $calculations[$game['Game_ID']]['itemsinbundle'];
	//echo "<br>SalePrice: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['SalePrice']);
	//echo "<br>AltSalePrice: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['AltSalePrice']);
	//echo "<br>TimeLeftToBeat: " . $calculations[$game['Game_ID']]['TimeLeftToBeat'];
	//echo "<br>LastPlayORPurchase: " . $calculations[$game['Game_ID']]['LastPlayORPurchase']; //Not used in ViewData
	/* echo "<hr align=left width=30%>"; * /
	//echo "<br>Launchperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhr']);
	//echo "<br>MSRPperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPperhr']);
	//echo "<br>Currentperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Currentperhr']);
	//echo "<br>Historicperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Historicperhr']);
	//echo "<br>Paidperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Paidperhr']);
	//echo "<br>Saleperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Saleperhr']);
	//echo "<br>Altperhr: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Altperhr']);
	/* echo "<hr align=left width=30%>"; * /
	//echo "<br>LaunchLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['LaunchLess1']);
	//echo "<br>MSRPLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['MSRPLess1']);
	//echo "<br>CurrentLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['CurrentLess1']);
	//echo "<br>HistoricLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['HistoricLess1']);
	//echo "<br>PaidLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['PaidLess1']);
	//echo "<br>SaleLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['SaleLess1']);
	//echo "<br>AltLess1: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['AltLess1']);
	echo "<hr align=left width=30%>";
	echo "<br>LaunchLess2: " . timeduration($calculations[$game['Game_ID']]['LaunchLess2'],"hours");
	echo "<br>MSRPLess2: " . timeduration($calculations[$game['Game_ID']]['MSRPLess2'],"hours");
	echo "<br>CurrentLess2: " . timeduration($calculations[$game['Game_ID']]['CurrentLess2'],"hours");
	echo "<br>HistoricLess2: " . timeduration($calculations[$game['Game_ID']]['HistoricLess2'],"hours");
	echo "<br>PaidLess2: " . timeduration($calculations[$game['Game_ID']]['PaidLess2'],"hours");
	echo "<br>SaleLess2: " . timeduration($calculations[$game['Game_ID']]['SaleLess2'],"hours");
	echo "<br>AltLess2: " . timeduration($calculations[$game['Game_ID']]['AltLess2'],"hours");
	echo "<hr align=left width=30%>";
	echo "<br>LaunchHrsNext1: " . timeduration($calculations[$game['Game_ID']]['LaunchHrsNext1'],"hours");
	echo "<br>MSRPHrsNext1: " . timeduration($calculations[$game['Game_ID']]['MSRPHrsNext1'],"hours");
	echo "<br>CurrentHrsNext1: " . timeduration($calculations[$game['Game_ID']]['CurrentHrsNext1'],"hours");
	echo "<br>HistoricHrsNext1: " . timeduration($calculations[$game['Game_ID']]['HistoricHrsNext1'],"hours");
	echo "<br>PaidHrsNext1: " . timeduration($calculations[$game['Game_ID']]['PaidHrsNext1'],"hours");
	echo "<br>SaleHrsNext1: " . timeduration($calculations[$game['Game_ID']]['SaleHrsNext1'],"hours");
	echo "<br>AltHrsNext1: " . timeduration($calculations[$game['Game_ID']]['AltHrsNext1'],"hours");
	echo "<hr align=left width=30%>";
	echo "<br>LaunchHrsNext2: " . timeduration($calculations[$game['Game_ID']]['LaunchHrsNext2'],"hours");
	echo "<br>MSRPHrsNext2: " . timeduration($calculations[$game['Game_ID']]['MSRPHrsNext2'],"hours");
	echo "<br>CurrentHrsNext2: " . timeduration($calculations[$game['Game_ID']]['CurrentHrsNext2'],"hours");
	echo "<br>HistoricHrsNext2: " . timeduration($calculations[$game['Game_ID']]['HistoricHrsNext2'],"hours");
	echo "<br>PaidHrsNext2: " . timeduration($calculations[$game['Game_ID']]['PaidHrsNext2'],"hours");
	echo "<br>SaleHrsNext2: " . timeduration($calculations[$game['Game_ID']]['SaleHrsNext2'],"hours");
	echo "<br>AltHrsNext2: " . timeduration($calculations[$game['Game_ID']]['AltHrsNext2'],"hours");
	
	//echo "<br>Achievements Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['AchievementsPct'])."%";
	//echo "<br>Sale Price Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['SaleDiscount'])."%";
	//echo "<br>Alt Sale Price Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['AltSaleDiscount'])."%";
	//echo "<br>Paid Discount from MSRP Percent: " . sprintf("%.2f",$calculations[$game['Game_ID']]['PaidDiscount'])."%";
	//echo "<br>Launch Per Hours to beat: $" . sprintf("%.2f",$calculations[$game['Game_ID']]['Launchperhrbeat']);
	
	
	//echo "<hr>";
	
	//echo "<hr>";
	//var_dump($calculations[$game['Game_ID']]);
	echo "</td>";
	echo "</tr>";
	/* */
	?>	


	<tr><th>Bundles</th><td>
	<?php
	$purchases=getPurchases("",$conn);
	$purchaseIndex=makeIndex($purchases,"TransID");

	if(!isset($calculations[$game['Game_ID']]['TopBundleIDs'])) {
		echo "<b>No Bundle Found!</b>";
	} else {
	?>
		<table>
		<thead><tr><th>Bundle ID</th><th>Title</th><th>Store</th><th>Date</th><th>Paid</th><th>Detail</th><th class="hidden">Debug</th></tr></thead>
		<?php 
		foreach($calculations[$game['Game_ID']]['TopBundleIDs'] as $key => $bundleID){ ?>
			<tr>
			<td><?php echo $bundleID; ?></td>
			<td><?php echo $purchases[$purchaseIndex[$bundleID]]['Title']; ?></td>
			<td><?php echo $purchases[$purchaseIndex[$bundleID]]['Store']; ?></td>
			<td><?php 
			//echo Date("n/j/Y H:i:s",$purchases[$purchaseIndex[$bundleID]]['PurchaseTimeStamp']); 
			echo $purchases[$purchaseIndex[$bundleID]]['PrintPurchaseTimeStamp'];
			?></td>
			<td><?php echo $purchases[$purchaseIndex[$bundleID]]['Paid']; ?></td>
			
			<td>
			<details>
			<summary><?php echo $purchases[$purchaseIndex[$bundleID]]['Title']; ?></summary>
			
			<table>
			<thead><tr><th class="hidden">Game ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th>
			<th>Altwant</th><th>Althrs</th><th>SalePrice</th><th>AltSalePrice</th><th class="hidden">Debug</th></tr></thead>
			
			<?php //ToDO: Fix this loop so it's not an embedded table. (make the previous cells multirow) 
			foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $gameinbundle ){ ?>
					<tr>
					<td class="hidden"><?php echo $gameinbundle['GameID']; ?></td>
					<td><a href='viewgame.php?id=<?php echo $gameinbundle['GameID']; ?>'><?php echo $calculations[$gameinbundle['GameID']]['Title']; ?></a></td>
					<td><?php echo $gameinbundle['Type']; ?></td>
					<td><?php echo booltext($gameinbundle['Playable']); ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['MSRP']); ?></td>
					<td class="numeric"><?php echo $gameinbundle['Want']; ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['HistoricLow']); ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['Altwant']); ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['Althrs']); ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['SalePrice']); ?></td>
					<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['AltSalePrice']); ?></td>
					<td class="hidden"><?php //echo $gameinbundle['Debug']; ?></td>
					</tr>
			<?php } ?>
			</table>
			</details>
			</td>
			
			<td class="hidden"><?php print_r($purchases[$purchaseIndex[$bundleID]]); ?></td>
			</tr>
		<?php }	?>
		</table>
	<?php }	?>
	
	</td></tr>

	<?php /* this version puts all the data in one table but the collapse code does not work as is. 
		   * need to the javascript based spoiler code from the totals by week/month/year page.
	<tr><th>Bundles</th><td>
	<?php
	$purchases=getPurchases("",$conn);
	$purchaseIndex=makeIndex($purchases,"TransID");
	?>
	
	<table>
	<thead><tr>
	<th>Bundle ID</th><th>Title</th><th>Store</th><th>Date</th><th>Paid</th>
	<th class="hidden">Game ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th>
	<th>Altwant</th><th>Althrs</th><th>SalePrice</th><th>AltSalePrice</th><th class="hidden">Debug</th>
	<th class="hidden">Debug</th>
	</tr></thead>
	<?php foreach($calculations[$game['Game_ID']]['TopBundleIDs'] as $key => $bundleID){ 
		$bundleitemcount=count($purchases[$purchaseIndex[$bundleID]]['GamesinBundle']);
		?>
		<tr>
		<td rowspan=<?php echo $bundleitemcount+1; ?>><?php echo $bundleID; ?></td>
		<td rowspan=<?php echo $bundleitemcount+1; ?>><?php echo $purchases[$purchaseIndex[$bundleID]]['Title']; ?></td>
		<td rowspan=<?php echo $bundleitemcount+1; ?>><?php echo $purchases[$purchaseIndex[$bundleID]]['Store']; ?></td>
		<td rowspan=<?php echo $bundleitemcount+1; ?>><?php echo Date("n/j/Y H:i:s",$purchases[$purchaseIndex[$bundleID]]['PurchaseTimeStamp']); ?></td>
		<td rowspan=<?php echo $bundleitemcount+1; ?>><?php echo $purchases[$purchaseIndex[$bundleID]]['Paid']; ?></td>
		
		<details>
		<summary><td><?php echo $purchases[$purchaseIndex[$bundleID]]['Title']; ?></td></summary>
		
		
		<?php //ToDO: Fix this loop so it's not an embedded table. (make the previous cells multirow) 
		foreach($purchases[$purchaseIndex[$bundleID]]['GamesinBundle'] as $gameinbundle ){ ?>
				<tr>
				<td class="hidden"><?php echo $gameinbundle['GameID']; ?></td>
				<td><a href='viewgame.php?id=<?php echo $gameinbundle['GameID']; ?>'><?php echo $calculations[$gameinbundle['GameID']]['Title']; ?></a></td>
				<td><?php echo $gameinbundle['Type']; ?></td>
				<td><?php echo booltext($gameinbundle['Playable']); ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['MSRP']); ?></td>
				<td class="numeric"><?php echo $gameinbundle['Want']; ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['HistoricLow']); ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['Altwant']); ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['Althrs']); ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['SalePrice']); ?></td>
				<td class="numeric">$<?php echo sprintf("%.2f",$gameinbundle['AltSalePrice']); ?></td>
				<td class="hidden"><?php echo $gameinbundle['Debug']; ?></td>
				</tr>
		<?php } ?>
		</details>
		
		<td rowspan=<?php echo $bundleitemcount+1; ?> class="hidden"><?php print_r($purchases[$purchaseIndex[$bundleID]]); ?></td>
		</tr>
	<?php } ?>
	</table>
	
	</td></tr>	
	*/?>

	<tr><th>Play History</th><td>
	<?php if (is_array($game['History'])) { ?>
		<details>
		<summary>
		<table><thead><tr><th class="hidden">ID</th><th>Games</th><th>First Play</th><th>Last Play</th><th class="hidden">Last time</th><th class="hidden">Total Hrs</th>
		<th class="hidden">Achievements</th><th>Status</th><th>Last Rating</th><th>Last Beat</th><th class="hidden">Base Game</th><th class="hidden">Launch Date</th>
		<th>Sub Total</th><th>Grand Total</th><th class="hidden">Week Play</th><th class="hidden">Month Play</th><th class="hidden">Year Play</th><th class="hidden">Week Achievements</th>
		<th class="hidden">Month Achievements</th><th class="hidden">Year Achievements</th>
		</tr></thead>
		<?php
		//TODO: Grand total for launchers and games with multiple DLC adds time played to earlier games even when they are not associated.
		//TODO: Add a column to pull achievements earned from SteamAPI aligned with historical play record.
		foreach ($game['Activity'] as $totals) { ?>
			<tr class="<?php echo $totals['Status']; ?>">
			<td class="hidden numeric"><a href='viewgame.php?id=<?php echo $totals['ID']; ?>' target='_blank'><?php echo $totals['ID']; ?></a></td>
			<td class="text"><a href='viewgame.php?id=<?php echo $totals['ID']; ?>' target='_blank'><?php echo $totals['Games']; ?></a></td>
			<td class="numeric"><?php echo $totals['firstplay']; ?></td>
			<td class="numeric"><?php echo $totals['lastplay']; ?></td>
			<td class="hidden numeric"><?php echo timeduration($totals['elapsed'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo timeduration($totals['totalHrs'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo $totals['Achievements']; ?></td>
			<td class="numeric"><?php echo $totals['Status']; ?></td>
			<td class="numeric"><?php echo $totals['Review']; ?></td>
			<td class="numeric"><?php echo $totals['LastBeat']; ?></td>
			<td class="hidden numeric"><a href='viewgame.php?id=<?php echo $totals['Basegame']; ?>' target='_blank'><?php echo $totals['Basegame']; ?></a></td>
			<td class="hidden numeric"><?php echo $totals['LaunchDate']; ?></td>
			<td class="numeric"><?php echo timeduration($totals['totalHrs'],"seconds"); ?></td>
			<td class="numeric"><?php echo timeduration($totals['GrandTotal'],"seconds"); /* Grand Total */ ?></td> 
			<td class="hidden numeric"><?php echo timeduration($totals['weekPlay'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo timeduration($totals['monthPlay'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo timeduration($totals['yearPlay'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo $totals['WeekAchievements']; ?></td>
			<td class="hidden numeric"><?php echo $totals['MonthAchievements']; ?></td>
			<td class="hidden numeric"><?php echo $totals['YearAchievements']; ?></td>
			</tr>
			
			<?php unset($totals);
		} ?>
		</table>
		</summary>

		<table>
		<thead>
		<tr>
		<th></th>
		<th>Timestamp</th>
		<?php if(count($game['Activity'])>1){ ?>
			<th>Title</th>
		<?php } ?>
		<th>System</th>
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
		<tbody>
		
		<?php foreach ($game['History'] as $history) { ?>
			<tr>
			<td class="numeric"><a href='addhistory.php?HistID=<?php echo $history['HistoryID']; ?>' target=_blank>edit</a></td>
			<td class="numeric"><?php echo str_replace(" ", "&nbsp;", $history['Timestamp']); ?></td>
			<?php if(count($game['Activity'])>1){ ?>
				<td class="text"><?php echo $history['Game']; ?></td>
			<?php } ?>
			<td class="text"><?php echo $history['System'] ?></td>
			<td class="text"><?php echo str_replace(" ", "&nbsp;", $history['Data']); ?></td>
			<td class="numeric"><?php echo timeduration($history['Time'],"hours"); ?></td>
			<td class="text"><?php echo nl2br($history['Notes']); ?></td>
			<td class="numeric"><?php echo $history['Achievements']; ?></td>
			<td class="hidden text"><?php echo $history['AchievementType']; ?></td>
			<td class="text"><?php echo $history['Status']; ?></td>
			<td class="numeric"><?php echo $history['Review']; ?></td>
			<td class="text"><?php echo $history['KeyWords']; ?></td>
			<td class="hidden numeric"><?php if( isset($history['prevstart'])) {echo date("n/j/Y H:i:s",$history['prevstart']);} ?></td>
			<td class="numeric"><?php echo timeduration($history['Elapsed'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo timeduration($history['prevTotSys'],"seconds"); ?></td>
			<td class="numeric"><?php echo timeduration($history['totalSys'],"seconds"); ?></td>
			<td class="hidden numeric"><?php echo timeduration($history['prevTotal'],"seconds"); ?></td>
			<td class="numeric"><?php echo timeduration($history['Total'],"seconds"); ?></td>
			<td class="text"><?php echo boolText($history['FinalCountHours']); ?></td>
			<td class="text"><?php echo $history['RowType']; ?></td>
			</tr>
		<?php } ?>
		
		</tbody>
		</table>
		</details>
	<?php } ?>
	
	<a href='addhistory.php?GameID=<?php echo $game['Game_ID']; ?>'>Add History Record</a>
	</td></tr>

	<tr><th>Games in series</th><td>
	
	<?php
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
		
		if($result = $conn->query($sql)){ ?>
			<table>
			<thead><tr><th class="hidden">ID</th><th>Game</th><th>Parent Game</th><th>Type</th><th>Playable</th><th>Launch Date</th><th>Status</th><th>Review</th><th>Last Play</th></tr></thead>
			<tbody>
			<?php while($row2 = $result->fetch_assoc()) { ?>
				<tr>
				<td class="hidden"><a href='viewgame.php?id=<?php echo $row2['Game_ID'];?>' target='_blank'><?php echo $row2['Game_ID']; ?></a></td>
				<td><a href='viewgame.php?id=<?php echo $row2['Game_ID']; ?>' target='_blank'><?php echo $row2['Title']; ?></a></td>
				<td><?php echo $row2['ParentGame']; ?></td>
				<td><?php echo $row2['Type']; ?></td>
				<td><?php echo booltext($row2['Playable']); ?></td>
				<td><?php echo $row2['LaunchDate']; ?></td>
				<td><?php echo $calculations[$row2['Game_ID']]['Status']; ?></td>
				<td><?php echo $calculations[$row2['Game_ID']]['Review']; ?></td>
				<td><?php echo $calculations[$row2['Game_ID']]['lastplay']; ?></td>
				</tr>
			<?php } ?>
			</tbody>
			</table>
		<?php } ?>
		
	</td></tr>
	<tr><th>All Game components</th><td>
	
	<?php 
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
	?>
	
	<table>
	<thead><tr><th class="hidden">ID</th><th>Game</th><th>Type</th><th>Playable</th><th>Launch Date</th></tr></thead>
	<tbody>
	
	<?php
	$row2 = $result->fetch_assoc();
		do {
			?>
			<tr>
			<td class="hidden"><a href='viewgame.php?id=<?php echo $row2['Game_ID']; ?>' target='_blank'><?php echo $row2['Game_ID']; ?></a></td>
			<td><a href='viewgame.php?id=<?php echo $row2['Game_ID']; ?>' target='_blank'><?php echo $row2['Title']; ?></a></td>
			<td><?php echo $row2['Type']; ?></td>
			<td><?php echo booltext($row2['Playable']); ?></td>
			<td><?php echo $row2['LaunchDate']; ?></td>
			</tr>			
		<?php } while($row2 = $result->fetch_assoc()); ?>
	</tbody>
	</table>
	</td></tr>
	
	<tr><th>Copies</th><td colspan=2>
	<?php
	//TODO: only lists copies that have the same parent. Add items that have the same base also.
	$sql = "SELECT `ItemID`, `ParentProductID`, `Notes`, `SizeMB`, `DRM`, `OS`, `ActivationKey`, `DateAdded`, `Time Added`, `gl_items`.`Sequence` as 'items_Sequence', 
	`Library`, `Title`, `Store`, `BundleID`, `gl_transactions`.`Tier`, `PurchaseDate`, `PurchaseTime`, `gl_transactions`.`Sequence` as 'trans_Sequence', `Paid`, `Credit Used`, `Bundle Link`
	from `gl_items` 
	JOIN `gl_transactions`  ON `gl_items`.`TransID` = `gl_transactions`.`TransID`
	WHERE `gl_items`.`ParentProductID` = " . $game['ParentGameID']
	. " OR `gl_items`.`ParentProductID` = " . $game['Game_ID']
	. " OR `gl_items`.`ProductID` = " . $game['Game_ID']
	. " OR `gl_items`.`ProductID` = " . $game['ParentGameID'];
	
	if($result = $conn->query($sql)) { ?>
		<table>
		<thead><tr><th>Item ID</th><th>Parent Product ID</th><th>Notes</th><th>Size (MB)</th><th>DRM</th><th>OS</th><th>Activation Key</th><th>Date Added</th><th>Library</th><th>Bundle</th><th>Store</th>
		<th>Bundle ID</th><th class="hidden">Parent Bundle</th><th>Tier</th><th>Purchase Date</th><th>Paid</th><th>Credit Used</th></tr></thead>
		<tbody>
			<?php
			// output data of each row
			while($row2 = $result->fetch_assoc()) {
				?>
				<tr>
				<td><a href="viewitem.php?id=<?php echo $row2['ItemID']; ?>" target='_blank'><?php echo $row2['ItemID']; ?></a></td>
				<td><a href='viewgame.php?id=<?php echo $row2['ParentProductID']; ?>' target='_blank'><?php echo $row2['ParentProductID']; ?></a></td>
				<td><?php echo nl2br($row2['Notes']); ?></td>
				<td><?php echo $row2['SizeMB']; ?></td>
				<td><?php echo $row2['DRM']; ?></td>
				<td><?php echo $row2['OS']; ?></td>
				<td><?php echo $row2['ActivationKey']; ?></td>
				
				<td><?php echo date("n/j/Y H:i:s",strtotime($row2['DateAdded'] . " " . $row2['Time Added'])+$row2['items_Sequence']); ?></td>
				
				<td><?php echo $row2['Library']; ?></td>
				<td><?php echo $row2['Title']; ?></td>
				<td><?php if($row2['Bundle Link']<>"") {
					echo "<a href=\"";
					if(substr($row2['Bundle Link'],0,4)<>"http"){
						echo "http://";
					}
					echo $row2['Bundle Link']."\" target='_blank'>".$row2['Store'] ."</a>" ;
				} else {
					echo $row2['Store'] ;
				}
				?></td>
				<td><a href="viewbundle.php?id=<?php echo $row2['BundleID']; ?>" target='_blank'><?php echo $row2['BundleID']; ?></a></td>
				<td class="hidden"><?php echo $row2['BundleID']; ?></td>
				<td><?php echo $row2['Tier']; ?></td>
				<td><?php echo date("n/j/Y H:i:s",strtotime($row2['PurchaseDate'] . " " . $row2['PurchaseTime'])+$row2['trans_Sequence']); ?></td>
				<td><?php echo $row2['Paid']; ?></td>
				<td><?php echo $row2['Credit Used']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
		</table>
	<?php
	} else {
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	?>
	</td></tr>
	
	</table>
	
	<?php if($edit_mode) { ?>
		<div><input type='submit' value='Save'></div>
	<?php } else { ?>
		<div><a href='viewgame.php?id=<?php echo $_GET['id']; ?>&edit=1'>Edit</a></div>
	<?php } ?>
	</form>

<?php
	if($game['SteamID']>0 and $ShowSteamThings){
		//$newscount=5;
		//$length=500;
		//$newsarray=GetGameNews($game['SteamID'],$newscount,$length);
		
		$newsarray=$steamAPI->GetSteamAPI("GetGameNews"); 
		if(isset($newsarray['appnews']['newsitems'])){
			echo $steamformat->formatnews($newsarray);
		}
	}
}

echo Get_Footer(); ?>
