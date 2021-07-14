<?php
$time_start = microtime(true);
$path = $_SERVER['DOCUMENT_ROOT'];
include $path."/gl6/inc/php.ini.inc.php";

include 'inc/functions.inc.php';
$title="Add Product";
echo Get_Header($title);

include "inc/auth.inc.php";
//$conn = new mysqli($servername, $username, $password, $dbname);
$conn=get_db_connection();

$settings=getsettings($conn);

if(isset($_POST['Game_ID'])){
	//if($GLOBALS['Debug_Enabled']) {trigger_error("Post data listed below", E_USER_NOTICE);}
	//echo '<pre>'. print_r($_POST,true) . "</pre>";
	
	foreach ($_POST as $key => &$value) {
		if($value=="") {
			$value="null";
		} else {
			
			$value="'".$conn->real_escape_string($value)."'";
		}
	}
	unset($value);
			
	$insert_SQL  = "INSERT INTO `gl_products` (`Game_ID`, `Title`, `Series`, `LaunchDate`, `LaunchPrice`, `MSRP`, `CurrentMSRP`, `HistoricLow`, `LowDate`, `SteamAchievements`, `SteamCards`, `TimeToBeat`, `Metascore`, 
	`UserMetascore`, `SteamRating`, `SteamID`, `GOGID`, `isthereanydealID`, `TimeToBeatID`, `MetascoreID`, `DateUpdated`, `Want`, `Playable`, `Type`, `ParentGameID`, `ParentGame`, `Developer`, `Publisher`)";
	//`DesuraID`,
	$insert_SQL .= "VALUES (";
	$insert_SQL .= $_POST['Game_ID'].", ";
	$insert_SQL .= $_POST['Title'].", ";
	$insert_SQL .= $_POST['Series'].", ";
	$insert_SQL .= $_POST['LaunchDate'].", ";
	$insert_SQL .= $_POST['LaunchPrice'].", ";
	$insert_SQL .= $_POST['MSRP'].", ";
	$insert_SQL .= $_POST['CurrentMSRP'].", ";
	$insert_SQL .= $_POST['HistoricLow'].", ";
	$insert_SQL .= $_POST['LowDate'].", ";
	$insert_SQL .= $_POST['SteamAchievements'].", ";
	$insert_SQL .= $_POST['SteamCards'].", ";
	$insert_SQL .= $_POST['TimeToBeat'].", ";
	$insert_SQL .= $_POST['Metascore'].", ";
	$insert_SQL .= $_POST['UserMetascore'].", ";
	$insert_SQL .= $_POST['SteamRating'].", ";
	$insert_SQL .= $_POST['SteamID'].", ";
	$insert_SQL .= $_POST['GOGID'].", ";
	$insert_SQL .= $_POST['isthereanydealID'].", ";
	$insert_SQL .= $_POST['TimeToBeatID'].", ";
	$insert_SQL .= $_POST['MetascoreID'].", ";
	$insert_SQL .= $_POST['DateUpdated'].", ";
	$insert_SQL .= $_POST['Want'].", ";
	$insert_SQL .= $_POST['Playable'].", ";
	$insert_SQL .= $_POST['Type'].", ";
	$insert_SQL .= $_POST['ParentGameID'].", ";
	//$insert_SQL .= $_POST['DesuraID'].", ";
	$insert_SQL .= $_POST['ParentGame'].", ";
	$insert_SQL .= $_POST['Developer'].", ";
	$insert_SQL .= $_POST['Publisher'].");";

		if($GLOBALS['Debug_Enabled']) {trigger_error("Running SQL Query to add new Item: ". $insert_SQL, E_USER_NOTICE);}
		
		if ($conn->query($insert_SQL) === TRUE) {
			if($GLOBALS['Debug_Enabled']) { trigger_error("Item record inserted successfully", E_USER_NOTICE);}
		} else {
			trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
		}
	echo "<hr>";
}

/*
INSERT INTO `gl_products` (`Game_ID`, `Title`, `Series`, `LaunchDate`, `LaunchPrice`, `MSRP`, `CurrentMSRP`, `HistoricLow`, `LowDate`, `SteamAchievements`, `SteamCards`, `TimeToBeat`, `Metascore`, `UserMetascore`, `SteamRating`, `SteamID`, `GOGID`, `isthereanydealID`, `TimeToBeatID`, `MetascoreID`, `DateUpdated`, `Want`, `Playable`, `Type`, `ParentGameID`, `DesuraID`, `ParentGame`, `Developer`, `Publisher`) 
VALUES('3345', 'THE GREAT GEOMETRIC MULTIVERSE TOUR ', 'THE GREAT GEOMETRIC MULTIVERSE TOUR ', '2018/08/27', '4.99', '4.99', '4.99', '4.99', null, null, null, null, null, null, null, '887400', null, null, '70453', 'pc/the-great-geometric-multiverse-tour', null, '1', '1', 'Game', '3345', null, null, null, null),
('3346', 'Conarium', 'Conarium', '2017/06/06', '19.99', '19.99', '19.99', '19.99', null, null, null, '5.5', '71', '69', '66', '313780', null, null, '46123', 'pc/conarium', null, '2', '1', 'Game', '3346', null, null, null, null),
('3347', 'Blackguards', 'Blackguards', '2014/01/22', '9.99', '9.99', '9.99', '9.99', null, null, null, '49', '68', '71', '63', '249650', null, null, '14481', 'pc/blackguards', null, '4', '1', 'Game', '3347', null, null, null, null),
('3348', 'Broken Sword 4 - The Angel of Death', 'Broken Sword 4 - The Angel of Death', '2006/09/15', '5.99', '5.99', '5.99', '5.99', null, null, null, '12', null, null, '43', '316160', null, null, '1336', null, null, '2', '1', 'Game', '3348', null, null, null, null),
('3349', 'Merry Glade', 'Merry Glade', '2018/05/11', '3.99', '3.99', '3.99', '3.99', null, null, null, '0.9', null, null, '97', '838390', null, null, '65363', 'pc/merry-glade', null, '3', '1', 'Game', '3349', null, null, null, null),
('3350', 'Journey of a Roach', 'Journey of a Roach', '2013/11/04', '6.99', '6.99', '6.99', '6.99', null, null, null, '4.5', '65', '69', '88', '255300', null, null, '14435', 'pc/journey-of-a-roach', null, '2', '1', 'Game', '3350', null, null, null, null),
('3351', 'LEGO® Batman™ 2: DC Super Heroes', 'LEGO® Batman™ 2: DC Super Heroes', '2012/06/22', '19.99', '19.99', '19.99', '19.99', null, null, null, '25', '81', '76', '88', '213330', null, null, '5244', 'pc/lego-batman-2-dc-super-heroes', null, '4', '1', 'Game', '3351', null, null, null, null),
('3352', 'LEGO® Batman™ 3: Beyond Gotham', 'LEGO® Batman™ 3: Beyond Gotham', '2014/11/11', '19.99', '19.99', '19.99', '19.99', null, null, null, '36', null, '66', '85', '313690', null, null, '21254', 'pc/lego-batman-3-beyond-gotham', null, '4', '1', 'Game', '3352', null, null, null, null);
*/

?>
	
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
		<form action="addproduct.php" method="post">
		<table class="ui-widget">
			<thead>
			<tr>
				<th colspan=3>New Product / Game</th>
			</tr>

			<tr>
				<th>Field</th>
				<th>Value</th>
				<th>Description</th>
				<?php //<th>Lookup Prompt</th> ?>
			</tr>
			</thead>

<?php
$sql="select max(`Game_ID`) maxid from `gl_products`";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$nextGame_ID=$row['maxid']+1;
	}
}


$conn->close();	

$blank="";

/* 
 * //TODO: make use of INFORMATION_SCHEMA.COLUMNS to retrieve a description for each field. 
 * At this time I can't figure out how to read that table.
 * select * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'products'
 * select `COLUMN_NAME`, `COLUMN_COMMENT` FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'products'
 * ALTER TABLE `gl_products` CHANGE `Game_ID` `Game_ID` INT(11) NOT NULL COMMENT 'Game ID';
 * 
 * This works on dreamhost
 * SELECT * FROM `COLUMNS` WHERE `Table_Name` = "gl_history"
 */

?>

			<tr><th>Game_ID *</th>
				<td><input type="number" name="Game_ID" onchange='$("#ParentGameID").val(this.value);' min="0" value="<?php echo $nextGame_ID; ?>"></td>
				<td>A Unique ID for each product/game. (Used as join key)</td>
				
				<script>
				//TODO:Add function to copy value to Series if Series is blank
				//TODO:Add function to search for if the title already exists in the library and give warning
				</script>
			</tr>

			<tr><th>Title *</th>
				<td><input type="text" name="Title" onchange='$("#Series").val(this.value); $("#ParentGame").val(this.value);' value="<?php echo $blank; ?>"></td>
				<td>The product title (Display Name)</td>
			</tr>

			<tr><th>Series * (?)</th>
				<td><input type="text" name="Series" min="0" id="Series" value="<?php echo $blank; ?>"></td>
				<td>The franchise this game is a part of. (Use Title if stand-alone)</td>
				
				<script>
				  $(function() {
					$( "#Series" ).autocomplete({
						source: function(request, response) {
							$.getJSON(
								"ajax/search.ajax.php",
								{ term:request.term, querytype:'Series' }, 
								response
							);
						}
				   });
				  });
				  </script>
			</tr>

			<tr><th>Launch Date *</th>
				<td><input type="date" name="LaunchDate" value="<?php echo date("Y-m-d"); ?>"></td>
				<td>Date this product was first available for purchase</td>
			</tr>

			<tr><th>Launch Price *</th>
				<td><input type="number" name="LaunchPrice" onchange='$("#MSRP").val(this.value); $("#CurrentMSRP").val(this.value); $("#HistoricLow").val(this.value);' min="0.00" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The price this product was first sold at on the Launch Date</td>
			</tr>

			<tr><th>MSRP *</th>
				<td><input type="number" name="MSRP" id="MSRP" onchange='$("#CurrentMSRP").val(this.value); $("#HistoricLow").val(this.value);' min="0.00" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The Manufactures Suggested Retail Price at the time<br> this product was added to the library. (Not sale price)</td>
			</tr>

			<tr><th>Current MSRP *</th>
				<td><input type="number" name="CurrentMSRP" id="CurrentMSRP" onchange='$("#HistoricLow").val(this.value);' min="0.00" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The Manufactures Suggested Retail Price at the time<br> this product was last updated. (Not sale price)</td>
			</tr>

			<tr><th>Historic Low Price</th>
				<td><input type="number" name="HistoricLow" id="HistoricLow" min="0.00" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The lowest price this produce has been sold for.<br> Use history from <a href="https://isthereanydeal.com/">isthereanydeal.com</a></td>
			</tr>

			<tr><th>Historic Low Date</th>
				<td><input type="date" name="LowDate" value="<?php echo date("Y-m-d"); ?>"></td>
				<td>The Date on which the historic low price was offered.</td>
			</tr>

			<tr><th>Steam Achievements</th>
				<td><input type="number" name="SteamAchievements" min="1" step="1" value="<?php echo $blank; ?>"></td>
				<td>Number of Steam Achievements for this product.</td>
			</tr>

			<tr><th>Steam Cards</th>
				<td><input type="number" name="SteamCards" min="1" step="1" value="<?php echo $blank; ?>"></td>
				<td>Total number of Steam Cards for this product. (Not card drops)</td>
			</tr>

			<tr><th>Time To Beat</th>
				<td><input type="number" name="TimeToBeat" min="0" step="0.1" value="<?php echo $blank; ?>"></td>
				<td>Number of hours to complete based of data from <a href="https://howlongtobeat.com/">howlongtobeat.com</a></td>
			</tr>

			<tr><th>Critic Metascore</th>
				<td><input type="number" name="Metascore" min="0" max="100" step="1" value="<?php echo $blank; ?>"></td>
				<td>Critic score from <a href="https://www.metacritic.com/">metacritic.com</a></td>
			</tr>

			<tr><th>User Metascore</th>
				<td><input type="number" name="UserMetascore" min="0" max="100" step="1" value="<?php echo $blank; ?>"></td>
				<td>User score from <a href="https://www.metacritic.com/">metacritic.com</a> (*10)</td>
			</tr>

			<tr><th>Steam Rating</th>
				<td><input type="number" name="SteamRating" min="0" max="100" step="1" value="<?php echo $blank; ?>"></td>
				<td>All time user review percentage from <a href="https://store.steampowered.com/">Steam</a></td>
			</tr>

			<tr><th>Steam ID</th>
				<td><input type="number" name="SteamID" min="0" step="1" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://store.steampowered.com/">Steam</a></td>
			</tr>

			<tr><th>GOG ID</th>
				<td><input type="text" name="GOGID" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://www.gog.com/">GOG</a></td>
			</tr>

			<tr><th>isthereanydeal ID</th>
				<td><input type="text" name="isthereanydealID" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://isthereanydeal.com/">isthereanydeal.com</a></td>
			</tr>

			<tr><th>TimeToBeat ID</th>
				<td><input type="number" name="TimeToBeatID" min="0"  step="1" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://howlongtobeat.com/">howlongtobeat.com</a></td>
			</tr>

			<tr><th>Metascore ID</th>
				<td><input type="text" name="MetascoreID" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://www.metacritic.com/">metacritic.com</a></td>
			</tr>

			<tr><th>Date Updated</th>
				<td><input type="date" name="DateUpdated" value="<?php echo date("Y-m-d"); ?>"></td>
				<td>Date last updated (today)</td>
			</tr>
			
			<tr><th>Want</th>
				<td><input type="number" name="Want" min="1" max="5" step="1" value="<?php echo 3; ?>"></td>
				<td>How much I wanted this on 1-5 scale</td>
			</tr>

			<tr><th>Playable</th>
				<td><input type="hidden" name="Playable" id="Playable" min="0" max="1" step="1" value="<?php echo 1; ?>">
				<label class="switch"><input type="checkbox" name="Playable_ckbx" id="Playable_ckbx" CHECKED onchange="doalert(this)">
				<span class="slider round"></span>"
				</label></td>
				<td>True or False: This product is playable. <br> DLC that add componenets are not playable <br>unless there is a seperate menu option to play that mode.</td>
				
				<script>
				function doalert(checkboxElem) {
				  if (checkboxElem.checked) {
					$("#Playable").val(1);
				  } else {
					$("#Playable").val(0);
				  }
				}
				</script>
			</tr>

			<tr><th>Type (?)</th> 
				<td><input type="text" name="Type" id="Type" value="Game<?php echo $blank; ?>"></td>
				<td>Game, DLC, App, or Launcher</td>
			</tr>
			<script>
			  $(function() {
				$( "#Type" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'Type' }, 
							response
						);
					}
			   });
			  });
		  </script>
		
			<tr><th>Parent Game ID</th> 
				<td><input type="number" name="ParentGameID" id="ParentGameID" min="0" step="1" value="<?php echo $nextGame_ID; ?>"></td>
				<td>The ID of the Parent Game (Product)</td>
			</tr>
			
			<tr class="hidden"><th>Desura ID</th> 
				<td><input type="text" name="DesuraID" value="<?php echo $blank; ?>"></td>
				<td>The product ID found in the URL on <a href="https://www.desura.com/">Desura.com</a> (No longer active)</td>
			</tr>
			
			<tr><th>Parent Game (?)</th> 
				<td><input type="text" name="ParentGame" id="ParentGame" value="<?php echo $blank; ?>"></td>
				<td>The name of the Parent Game for this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#ParentGame" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'Game' }, 
							response
						);
					},
					select: function(event, ui){
						$("#ParentGameID").val(ui.item.id);
					}
			   });
			  });
		  </script>
			
			<tr><th>Developer (?)</th> 
				<td><input type="text" name="Developer" id="Developer" onchange='$("#Publisher").val(this.value);'  value="<?php echo $blank; ?>"</td>
				<td>The name of the developer of this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#Developer" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'Developer' }, 
							response
						);
					}
			   });
			  });
		  </script>
					
			<tr><th>Publisher (?)</th> 
				<td><input type="text" name="Publisher" id="Publisher" value="<?php echo $blank; ?>"</td>
				<td>The name of the publisher of this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#Publisher" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'Publisher' }, 
							response
						);
					}
			   });
			  });
		  </script>
		
			<tr><th colspan=3><input type="submit" value="Save"></th></tr>
			<tr><th colspan=3>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
		</table>
		</form>

<?php echo Get_Footer(); ?>