<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";

class addproductPage extends Page
{
	public function __construct() {
		$this->title="Add Product";
	}
	
	public function buildHtmlBody(){
		$output = "";

	if(isset($_POST['Game_ID'])){
		$this->getDataAccessObject()->insertGame($_POST);
	}

	$nextGame_ID = $this->getDataAccessObject()->getMaxTableId("games");

  $output .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
				<th>Description</th>';
				//<th>Lookup Prompt</th>
			$output .= '</tr>
			</thead>';

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

			$output .= '<tr><th>Game_ID *</th>
				<td><input type="number" name="Game_ID" onchange=\'$("#ParentGameID").val(this.value);\' min="0" value="'. $nextGame_ID.'"></td>
				<td>A Unique ID for each product/game. (Used as join key)</td>';
				
				//TODO:Add function to copy value to Series if Series is blank
				//TODO:Add function to search for if the title already exists in the library and give warning
			$output .= '</tr>

			<tr><th>Title *</th>
				<td><input type="text" name="Title" onchange=\'$("#Series").val(this.value); $("#ParentGame").val(this.value);\' value="'.$blank.'"></td>
				<td>The product title (Display Name)</td>
			</tr>

			<tr><th>Series * (?)</th>
				<td><input type="text" name="Series" min="0" id="Series" value="'.$blank.'"></td>
				<td>The franchise this game is a part of. (Use Title if stand-alone)</td>
				
				<script>
				  $(function() {
					$( "#Series" ).autocomplete({
						source: function(request, response) {
							$.getJSON(
								"ajax/search.ajax.php",
								{ term:request.term, querytype:"Series" }, 
								response
							);
						}
				   });
				  });
				  </script>
			</tr>

			<tr><th>Launch Date *</th>
				<td><input type="date" name="LaunchDate" value="'. date("Y-m-d").'"></td>
				<td>Date this product was first available for purchase</td>
			</tr>

			<tr><th>Launch Price *</th>
				<td><input type="number" name="LaunchPrice" onchange=\'$("#MSRP").val(this.value); $("#CurrentMSRP").val(this.value); $("#HistoricLow").val(this.value);\' min="0.00" step="0.01" value="'. $blank.'"></td>
				<td>The price this product was first sold at on the Launch Date</td>
			</tr>

			<tr><th>MSRP *</th>
				<td><input type="number" name="MSRP" id="MSRP" onchange=\'$("#CurrentMSRP").val(this.value); $("#HistoricLow").val(this.value);\' min="0.00" step="0.01" value="'. $blank.'"></td>
				<td>The Manufactures Suggested Retail Price at the time<br> this product was added to the library. (Not sale price)</td>
			</tr>

			<tr><th>Current MSRP *</th>
				<td><input type="number" name="CurrentMSRP" id="CurrentMSRP" onchange=\'$("#HistoricLow").val(this.value);\' min="0.00" step="0.01" value="'. $blank.'"></td>
				<td>The Manufactures Suggested Retail Price at the time<br> this product was last updated. (Not sale price)</td>
			</tr>

			<tr><th>Historic Low Price</th>
				<td><input type="number" name="HistoricLow" id="HistoricLow" min="0.00" step="0.01" value="'.$blank.'"></td>
				<td>The lowest price this produce has been sold for.<br> Use history from <a href="https://isthereanydeal.com/">isthereanydeal.com</a></td>
			</tr>

			<tr><th>Historic Low Date</th>
				<td><input type="date" name="LowDate" value="'.date("Y-m-d").'"></td>
				<td>The Date on which the historic low price was offered.</td>
			</tr>

			<tr><th>Steam Achievements</th>
				<td><input type="number" name="SteamAchievements" min="1" step="1" value="'. $blank.'"></td>
				<td>Number of Steam Achievements for this product.</td>
			</tr>

			<tr><th>Steam Cards</th>
				<td><input type="number" name="SteamCards" min="1" step="1" value="'.$blank.'"></td>
				<td>Total number of Steam Cards for this product. (Not card drops)</td>
			</tr>

			<tr><th>Time To Beat</th>
				<td><input type="number" name="TimeToBeat" min="0" step="0.1" value="'. $blank.'"></td>
				<td>Number of hours to complete based of data from <a href="https://howlongtobeat.com/">howlongtobeat.com</a></td>
			</tr>

			<tr><th>Critic Metascore</th>
				<td><input type="number" name="Metascore" min="0" max="100" step="1" value="'. $blank.'"></td>
				<td>Critic score from <a href="https://www.metacritic.com/">metacritic.com</a></td>
			</tr>

			<tr><th>User Metascore</th>
				<td><input type="number" name="UserMetascore" min="0" max="100" step="1" value="'. $blank.'"></td>
				<td>User score from <a href="https://www.metacritic.com/">metacritic.com</a> (*10)</td>
			</tr>

			<tr><th>Steam Rating</th>
				<td><input type="number" name="SteamRating" min="0" max="100" step="1" value="'. $blank.'"></td>
				<td>All time user review percentage from <a href="https://store.steampowered.com/">Steam</a></td>
			</tr>

			<tr><th>Steam ID</th>
				<td><input type="number" name="SteamID" min="0" step="1" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://store.steampowered.com/">Steam</a></td>
			</tr>

			<tr><th>GOG ID</th>
				<td><input type="text" name="GOGID" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://www.gog.com/">GOG</a></td>
			</tr>

			<tr><th>isthereanydeal ID</th>
				<td><input type="text" name="isthereanydealID" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://isthereanydeal.com/">isthereanydeal.com</a></td>
			</tr>

			<tr><th>TimeToBeat ID</th>
				<td><input type="number" name="TimeToBeatID" min="0"  step="1" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://howlongtobeat.com/">howlongtobeat.com</a></td>
			</tr>

			<tr><th>Metascore ID</th>
				<td><input type="text" name="MetascoreID" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://www.metacritic.com/">metacritic.com</a></td>
			</tr>

			<tr><th>Date Updated</th>
				<td><input type="date" name="DateUpdated" value="'. date("Y-m-d").'"></td>
				<td>Date last updated (today)</td>
			</tr>
			
			<tr><th>Want</th>
				<td><input type="number" name="Want" min="1" max="5" step="1" value="3"></td>
				<td>How much I wanted this on 1-5 scale</td>
			</tr>

			<tr><th>Playable</th>
				<td><input type="hidden" name="Playable" id="Playable" min="0" max="1" step="1" value="1">
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
				<td><input type="text" name="Type" id="Type" value="Game'. $blank.'"></td>
				<td>Game, DLC, App, or Launcher</td>
			</tr>
			<script>
			  $(function() {
				$( "#Type" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Type" }, 
							response
						);
					}
			   });
			  });
		  </script>
		
			<tr><th>Parent Game ID</th> 
				<td><input type="number" name="ParentGameID" id="ParentGameID" min="0" step="1" value="'. $nextGame_ID.'"></td>
				<td>The ID of the Parent Game (Product)</td>
			</tr>
			
			<tr class="hidden"><th>Desura ID</th> 
				<td><input type="text" name="DesuraID" value="'. $blank.'"></td>
				<td>The product ID found in the URL on <a href="https://www.desura.com/">Desura.com</a> (No longer active)</td>
			</tr>
			
			<tr><th>Parent Game (?)</th> 
				<td><input type="text" name="ParentGame" id="ParentGame" value="'. $blank.'"></td>
				<td>The name of the Parent Game for this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#ParentGame" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Game" }, 
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
				<td><input type="text" name="Developer" id="Developer" onchange=\'$("#Publisher").val(this.value);\'  value="'. $blank.'"</td>
				<td>The name of the developer of this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#Developer" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Developer" }, 
							response
						);
					}
			   });
			  });
		  </script>
					
			<tr><th>Publisher (?)</th> 
				<td><input type="text" name="Publisher" id="Publisher" value="'. $blank.'"</td>
				<td>The name of the publisher of this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#Publisher" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Publisher" }, 
							response
						);
					}
			   });
			  });
		  </script>
		
			<tr><th colspan=3><input type="submit" value="Save"></th></tr>
			<tr><th colspan=3>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
		</table>
		</form>';
		
		return $output;
	}
}
