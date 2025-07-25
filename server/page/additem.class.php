<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";

//TODO: Split into three files, Single form, SteamAPI, and Form Post (where both forms will go and show stats about recently played)

class additemPage extends Page
{
	public function __construct() {
		$this->title="Add Item";
	}
	
	public function buildHtmlBody(){
		$output = "";
		if(isset($_POST['TransID'])){
			$this->getDataAccessObject()->insertItem($_POST);
		}

		if(isset($_POST['Product_ckbx'])){
			$this->getDataAccessObject()->insertGame2($_POST);
		}

		//TODO: Enforce required fields
		//TODO: Test null values
		//TODO: Add links to 'New' buttons for transaction and product
		//TODO: move description column to pop up text
		//TODO: Add infobox for selected product and transaction
		$output .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>';
		$output .= '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';

		$output .= '<form action="additem.php" method="post">
		<table class="ui-widget">
			<thead>
			<tr>
				<th colspan=4>New Item</th>
			</tr>

			<tr>
				<th>Field</th>
				<th>Value</th>
				<th>Description</th>
				<th>Lookup Prompt</th>
			</tr>
			</thead>';
			
			$nextItemID = $this->getDataAccessObject()->getMaxTableId("items");
			$nextGame_ID = $this->getDataAccessObject()->getMaxTableId("games");

			$blank="";

			/* 
			 * //TODO: make use of INFORMATION_SCHEMA.COLUMNS to retrieve a description for each field. 
			 * At this time I can't figure out how to read that table.
			 * select * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'products'
			 * select `COLUMN_NAME`, `COLUMN_COMMENT` FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'products'
			 * ALTER TABLE `gl_products` CHANGE `Game_ID` `Game_ID` INT(11) NOT NULL COMMENT 'Game ID';
			 */

			$output .= '<tr>
				<th>Item ID *</th>
				<td><input type="number" name="ItemID" min="0" value="' . $nextItemID . '"></td>
				<td>A Unique ID for each item. (Used as join key)</td>
				<td></td>
			</tr>

			<tr>
				<th>Transaction ID *</th>
				<td><input type="number" name="TransID" min="0" id="TransactionID" value="'.$blank.'"></td>
				<td>The purchase transaction which included this item (Bundle)</td>
				<td>(?)<input id="Transaction" onchange="setNotes()" size=30>
				<input class="hidden" type="button" value="New"></td>
			</tr>
			<script>
			  $(function() {
				$( "#Transaction" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'Trans\' }, 
							response
						);
					},
					select: function(event, ui){
						$("#TransactionID").val(ui.item.id);
						//setNotes();
						$("#Notes").val("Game: "+document.getElementById("Product").value+"\nBundle: "+ui.item.value+"\nParent Game: "+document.getElementById("ParentProduct").value);
					}
				});
			  });
			</script>
			
			<tr>
				<th>Product ID</th>
				<td><input type="number" name="ProductID" min="0" class="auto" id="ProductID" value="'.$blank.'"></td>
				<td>The product this item is linked to. (GameID) <br>Cards do not have an associated Product, use ParentProductID.</td>
				<td>(?)<input id="Product" onchange="setNotes()" size=30>
				<input class="hidden" type="button" value="New"></td>
			</tr>
			<script>
			  $(function() {
					$("#Product").autocomplete({ 
						source: "ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ProductID").val(ui.item.id);
							$("#ParentProduct").val(ui.item.value);
							$("#ParentProductID").val(ui.item.id);
							//setNotes();
							$("#Notes").val("Game: "+ui.item.value+"\nBundle: "+document.getElementById("Transaction").value+"\nParent Game: "+document.getElementById("ParentProduct").value);
						} }
					);
				} );
			</script>';

			$output .= '<tr>
				<th>Parent Product ID *</th>
				<td><input type="number" name="ParentProductID" min="0" id="ParentProductID" value="'.$blank.'"></td>
				<td>The parent product ID controls how total cost is calculated. <br>Should be the same as product ID most of the time except for cards.</td>
				<td>(?)<input id="ParentProduct" onchange="setNotes()" size=30></td>
			</tr>
			<script>
			  $(function() {
					$("#ParentProduct").autocomplete({ 
						source: "ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ParentProductID").val(ui.item.id);
							//setNotes(); 
							$("#Notes").val("Game: "+document.getElementById("Product").value+"\nBundle: "+document.getElementById("Transaction").value+"\nParent Game: "+ui.item.value);
						} }
					);
			  } );
			</script>			

			<tr>
				<th>Notes</th>
				<td><textarea name="Notes" id="Notes" rows="6" cols="33"></textarea></td>
				<td>Any notes about the purchase</td>
				<td></td>
			</tr>
			<script>
			function setNotes() {
				$("#Notes").val("Game: "+document.getElementById("Product").value+"\nBundle: "+document.getElementById("Transaction").value+"\nParent Game: "+document.getElementById("ParentProduct").value);
			}
			</script>

			<tr>
				<th>Tier</th>
				<td><input type="number" name="Tier" min="1" value="1"></td>
				<td>The bundle Tier this item was purchased in.</td>
				<td></td>
			</tr>

			<tr>
				<th>Activation Key</th>
				<td><input type="text" name="ActivationKey" value="'.$blank.'"></td>
				<td>The activation key (if provided)</td>
				<td></td>
			</tr>

			<tr>
				<th>Size (in MB)</th>
				<td><input type="number" name="SizeMB" min="0" value="'.$blank.'"></td>
				<td>Size of the download</td>
				<td></td>
			</tr>

			<tr>
				<th>Library * (?)</th>
				<td><input type="text" name="Library" id="Library" onchange=\'$("#DRM").val(this.value);\' value="'.$blank.'"></td>
				<td>What library this item is found in.</td>
				<td></td>
			</tr>
			<script>
			  $(function() {
				$( "#Library" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'Library\' }, 
							response
						);
					},
					 select: function(event, ui){
						$("#DRM").val(ui.item.value);
					}
			   });
			  });
			</script>

			<tr>
				<th>DRM * (?)</th>
				<td><input type="text" name="DRM" id="DRM" value="'.$blank.'"></td>
				<td>What DRM is used</td>
				<td></td>
			</tr>
			<script>
			  $(function() {
				$( "#DRM" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'DRM\' }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>OS (?)</th>
				<td><input type="text" name="OS" id="OS" value="'.$blank.'"></td>
				<td>What Operating systems are supported</td>
				<td></td>
			</tr>
			<script>
			  $(function() {
				$( "#OS" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'OS\' }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>Date Added *</th>';
		//$useDate="2019-10-31";
		$useDate=date("Y-m-d");
				$output .= '<td><input type="date" name="DateAdded" value="'.$useDate.'"></td>
				<td>Date this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Time Added *</th>';
		$useTime="10:00:00";
		$output .= '<td><input type="time" name="Time Added" min="0" value="'.$useTime.'"></td>
				<td>Time this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Sequence</th>
				<td><input type="number" name="Sequence" min="0" value="'.$blank.'"></td>
				<td>Sequence for this item in the bundle</td>
				<td></td>
			</tr>
			<script>
			//TODO: Add function to query the max value of the selected bundle and +1
			</script>

			<tr><th colspan=4><input type="submit" value="Save"></th></tr>
			<tr><th colspan=4>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
			
			<script>
			//------------------------------------------------------------------------
			</script>
			<tr><th colspan=4>Quick Add New Product
			<label class="switch"><input type="checkbox" name="Product_ckbx" id="Product_ckbx" onchange="doalert(this)">
				<span class="slider round"></span></label>
				<script>
				function doalert(checkboxElem) {
					//TODO: BUG: Code to copy product ID on checkbox action does not work.
				  if (checkboxElem.checked) {
					$("#ProductID").val(document.getElementById("Game_ID").value);
					$("#ParentProductID").val(document.getElementById("Game_ID").value);
				  } else {
					//TODO: Add function to make quick form hidden unless the checkbox is activated.
				  }
				}
				</script>
			</th></tr>
			
			<tr><th>Game_ID *</th>
				<td><input type="number" name="Game_ID" id="Game_ID" onchange=\'$("#ParentGameID").val(this.value); $("#ParentProductID").val(this.value); $("#ProductID").val(this.value);\' min="0" value="'.$nextGame_ID.'"></td>
				<td>A Unique ID for each product/game. (Used as join key)</td>
			</tr>

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
								{ term:request.term, querytype:\'Series\' }, 
								response
							);
						}
				   });
				  });
				  </script>
			</tr>

			<tr><th>Launch Date *</th>
				<td><input type="date" name="LaunchDate" value="'.date("Y-m-d").'"></td>
				<td>Date this product was first available for purchase</td>
			</tr>
			
			<tr><th>Steam ID</th>
				<td><input type="number" name="SteamID" min="0" step="1" value="'.$blank.'"></td>
				<td>The product ID found in the URL on <a href="https://store.steampowered.com/">Steam</a></td>
			</tr>
			
			<tr><th>Want</th>
				<td><input type="number" name="Want" min="1" max="5" step="1" value="3"></td>
				<td>How much I wanted this on 1-5 scale</td>
			</tr>

			<tr><th>Playable</th>
				<td><input type="hidden" name="Playable" id="Playable" min="0" max="1" step="1" value="1">
				<label class="switch"><input type="checkbox" name="Playable_ckbx" id="Playable_ckbx" CHECKED onchange="doalert(this)">
				<span class="slider round"></span>
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
				<td><input type="text" name="Type" id="Type" value="Game'.$blank.'"></td>
				<td>Game, DLC, App, or Launcher</td>
			</tr>
			<script>
			  $(function() {
				$( "#Type" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'Type\' }, 
							response
						);
					}
			   });
			  });
			</script>
		
			<tr><th>Parent Game ID</th> 
				<td><input type="number" name="ParentGameID" id="ParentGameID" min="0" step="1" value="'.$nextGame_ID.'"></td>
				<td>The ID of the Parent Game (Product)</td>
			</tr>
			
			<tr><th>Parent Game (?)</th> 
				<td><input type="text" name="ParentGame" id="ParentGame" value="'.$blank.'"></td>
				<td>The name of the Parent Game for this product</td>
			</tr>
			<script>
			  $(function() {
				$( "#ParentGame" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:\'Game\' }, 
							response
						);
					},
					select: function(event, ui){
						$("#ParentGameID").val(ui.item.id);
					}
			   });
			  });
			</script>
			<tr><th colspan=4><input type="submit" value="Save"></th></tr>
			
			
		</table>
		</form>';
		
		return $output;
	}
}
