<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";

//TODO: BUG: the url for the bundle is being printed on the page from somewhere.
class viewbundlePage extends Page
{
	private $games;
	private $items;
	private $purchaseobj;
	private $purchases;
	private $purchaseIndex;
	private $gameIndex;
	private $itemIndex;
	
	public function __construct() {
		$this->title="View Bundle";
	}
	
	private function getGames(){
		if(!isset($this->games)){
			$this->games = getGames();
		}
		return $this->games;
	}
	
	private function getItems(){
		if(!isset($this->items)){
			$this->items = getAllItems();
		}
		return $this->items;
	}
	
	private function getPurchaseObj(){
		if(!isset($this->purchaseobj)){
			$this->purchaseobj = new Purchases("",false,$this->getItems(),$this->getGames());
		}
		return $this->purchaseobj;
	}
	
	private function getPurchases(){
		if(!isset($this->purchases)){
			$this->purchases = $this->getPurchaseObj()->getPurchases();
		}
		return $this->purchases;
	}
	
	private function getPurchaseIndex($key="TransID"){
		if(!isset($this->purchaseIndex)){
			$this->purchaseIndex = makeIndex($this->getPurchases(),$key);
		}
		return $this->purchaseIndex;
	}

	private function getGameIndex($key="Game_ID"){
		if(!isset($this->gameIndex)){
			$this->gameIndex = makeIndex($this->getGames(),$key);
		}
		return $this->gameIndex;
	}

	private function getItemIndex($key="ItemID"){
		if(!isset($this->itemIndex)){
			$this->itemIndex = makeIndex($this->getItems(),$key);
		}
		return $this->itemIndex;
	}

	private function makePrompt(){
		$lookupbundle=lookupTextBox("BundleTitle", "BundleID", "id", "Trans");
		$output = $lookupbundle["header"];
		
		$output .= 'Please specify a bundle by ID.
		<form method="Get">
			'. $lookupbundle["textBox"].'
			<input type="submit">
		</form>';
		$output .= $lookupbundle["lookupBox"];
		
		return $output;
	}

	private function makeBundleTable($id,$edit_mode=false){
		//TODO: Add a check for the last record and omit the +1 link
		$output = '<a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']-1).'">&lt;--Prev</a> | <a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']+1).'">Next--&gt;</a>
		<form method="post">
		<table  class="ui-widget">
			<thead>
			<tr>
				<th colspan=4>Bundle</th>
			</tr>

			<tr>
				<th>Field</th>
				<th>Value</th>
				<th class="hidden">Details</th>
			</tr>
			</thead>';
		
		$output .= '<tr>
			<th>Transaction ID</th>
			<td>
			<a href="'. $_SERVER['PHP_SELF'] . "?id=" . $this->purchaseAttribute($id,'TransID').'">'. $this->purchaseAttribute($id,'TransID').'</a> ';
			if ($edit_mode === true) {
			$output .= '<input type="hidden" name="TransID" value="'. $this->purchaseAttribute($id,'TransID').'">';
			}
			$output .= '</td>
		</tr>
		<tr>
			<th>Title</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<textarea align=top rows=2 cols=40 name="Title">'. $this->purchaseAttribute($id,'Title').'</textarea>';
			} else {  
				$output .= nl2br($this->purchaseAttribute($id,'Title')); 
			}
			$output .= '</td>
		</tr>
		<tr>
			<th>Store</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="text" name="Store" id="Store" size="12" value="'. $this->purchaseAttribute($id,'Store').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Store'); } 
			$output .= '</td>
			<script>
			  $(function() {
				$( "#Store" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Store" }, 
							response
						);
					}
				});
			  });
			</script>
		</tr>
		<tr>
			<th>Bundle ID</th>
			<td><a href="'. $_SERVER['PHP_SELF'] . "?id=" . $this->purchaseAttribute($id,'BundleID').'">'. $this->purchaseAttribute($id,'BundleID').'</a> ';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="BundleID" id="BundleID" min="0" max="9999" value="'. $this->purchaseAttribute($id,'BundleID').'">
			(?)<input id="BundleTitle" size=30 value="'. $this->purchaseAttribute($this->purchaseAttribute($id,'BundleID'),'Title').'">
			<script>
			  $(function() {
				$( "#BundleTitle" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Trans" }, 
							response
						);
					},
					select: function(event, ui){
						$("#BundleID").val(ui.item.id);
					}
				});
			  });
			</script>';
			} else {
				$output .= $this->purchaseAttribute($this->purchaseAttribute($id,'BundleID'),'Title');
			}
			$output .= '</td>
		</tr>
		<tr>
			<th>Tier</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="Tier" min="0" max="99" value="'. $this->purchaseAttribute($id,'Tier').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Tier'); } 
			$output .= '</td>
		</tr>
		
		<tr class="Hidden">
			<th>Purchase Date</th>
			<td>'. $this->purchaseAttribute($id,'PurchaseDate').'</td>
		</tr>
		<tr class="Hidden">
			<th>Purchase Time</th>
			<td>'. $this->purchaseAttribute($id,'PurchaseTime').'</td>
		</tr>
		<tr class="Hidden">
			<th>Sequence</th>
			<td>'. $this->purchaseAttribute($id,'Sequence').'</td>
		</tr>
		<tr>
			<th>Purchase Date/Time</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="datetime-local" name="purchasetime" value="'. date("Y-m-d\TH:i:s",$this->purchaseAttribute($id,'PurchaseDateTime')->gettimestamp()).'">
			<br>Sequence: <input type="number" name="Sequence" min="1" max="999" step="1" value="'. $this->purchaseAttribute($id,'Sequence').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'PrintPurchaseTimeStamp'); } 
			$output .= '</td>
		</tr>
		
		<tr>
			<th>Price</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="Price" min="-9999" max="9999" step=".01" value="'. $this->purchaseAttribute($id,'Price').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Price'); } 
			$output .= '</td>
		</tr>
		<tr>
			<th>Fees</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="Fees" min="-9999" max="9999" step=".01" value="'. $this->purchaseAttribute($id,'Fees').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Fees'); } 
			$output .= '</td>
		</tr>
		<tr>
			<th>Paid</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="Paid" min="-9999" max="9999" step=".01" value="'. $this->purchaseAttribute($id,'Paid').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Paid'); } 
			$output .= '</td>
		</tr>
		<tr>
			<th>Credit Used</th>
			<td>';
			if ($edit_mode === true) {
			$output .= '<input type="number" name="Credit" min="-9999" max="9999" step=".01" value="'. $this->purchaseAttribute($id,'Credit Used').'">';
			} else { 
				$output .= $this->purchaseAttribute($id,'Credit Used'); } 
			$output .= '</td>
		</tr>
		<tr>
			<th>Bundle Link</th>
			<td>';
			$output .= $this->purchaseAttribute($id,'Bundle Link');
			$output .= " ";
			$output .= $this->editableField($this->purchaseAttribute($id,'BundleURL'),$edit_mode,"Link");
			$output .= '</td>
		</tr>
		<tr>
			<th>Games in Bundle</th>
			<td>';
			$output .= $this->makeGamesTable();
			$output .= '</td>
		</tr>
		<tr>
			<th>Items in Bundle</th>
			<td>';
			$output .= $this->makeItemsTable();
			$output .= '</td>
		</tr>
		<tr>
			<th>Products in Bundle</th>
			<td>';
			$output .= $this->makeProductsTable();
			$output .= '</td>
		</tr>
		
		</table>';
		if($edit_mode) {
			$output .= "<div><input type='submit' value='Save'></div>";
		} else {
			$output .= "<div><a href='". $_SERVER['PHP_SELF']."?id=". $_GET['id']."&edit=1'>Edit</a></div>";
		}
		$output .= '</form>';
		return $output;
	}
	
	private function editableField($data,$edit_mode=false,$name=""){
		$output = "";
		if ($edit_mode === true) {
			$output .= ' <input type="text" name="'.$name.'" size="40" value="'. $data.'">';
		} else {
			$output .= $data;
		}
		return $output;
	}
	
	private function purchaseAttribute($id,$attribute){
		return $this->getPurchases()[$this->getPurchaseIndex()[$id]][$attribute];
	}
	
	private function makeGamesTable(){
		$output="";
		//TODO: Don't reference _Get['id'] directly
		if(isset($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['GamesinBundle']) && count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['GamesinBundle'])>0) { 
			$output .= '<details>
			<summary>';
			$output .= count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['GamesinBundle']);
			$output .= '</summary>
				<table>
				<thead><tr>
				<th class="hidden">ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th><th>AltWant</th><th>AltHrs</th><th>Sale Price</th><th>Alt Sale Price</th>
				</tr></thead>';
				foreach ($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['GamesinBundle'] as $value) {
					$output .= '<tr>
					<td class="hidden"><a href="viewgame.php?id='. $value['GameID'].'">'. $value['GameID'].'</a></td>
					<td><a href="viewgame.php?id='. $value['GameID'].'">'. $this->getGames()[$this->getGameIndex()[$value['GameID']]]['Title'].'</a></td>
					<td>'. $value['Type'].'</td>
					<td>'. booltext($value['Playable']).'</td>
					<td>'. "$" . sprintf('%.2f', $value['MSRP']).'</td>
					<td>'. $value['Want'].'</td>
					<td>'. "$" . sprintf('%.2f', $value['HistoricLow']).'</td>
					<td>'. round($value['Altwant'],3).'</td>
					<td>'. round($value['Althrs'],3).'</td>
					<td>'. "$" . sprintf('%.2f', $value['SalePrice']).'</td>
					<td>'. "$" . sprintf('%.2f', $value['AltSalePrice']).'</td>';
					$output .= '</tr>';
				}
				
				$output .= '</table>
			</details>';
			} else { 
			$output .= 0;
			}
			return $output;
	}

	private function makeItemsTable(){
		$output="";
		if(isset($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['itemsinBundle']) && count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['itemsinBundle'])>0) {
			$output .= '<details>
			<summary>';
			$output .= count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['itemsinBundle']); 
			$output .= '</summary>
			<table>
			<thead><tr>
			<th>Item ID</th><th>Product ID</th><th>Transaction ID</th><th>Parent Product ID</th><th>Tier</th><th>Notes</th><th>SizeMB</th><th>DRM</th><th>OS</th><th>Activation Key</th><th>Date Added</th><th>Time Added</th><th>Sequence</th><th>Library</th><th>Added Time Stamp</th>
			</tr></thead>';
			foreach ($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['itemsinBundle'] as $value) {
				$output .= '<tr>
				<td><a href="viewitem.php?id='. $this->getItems()[$this->getItemIndex()[$value]]['ItemID'].'">'. $this->getItems()[$this->getItemIndex()[$value]]['ItemID'].'</a></td>

				<td>';
				if (isset($this->getItems()[$this->getItemIndex()[$value]]['ProductID']) && $this->getItems()[$this->getItemIndex()[$value]]['ProductID']<>"") {
					$output .= "<a href=\"viewgame.php?id=" . $this->getItems()[$this->getItemIndex()[$value]]['ProductID'] . "\">" . $this->getGames()[$this->getGameIndex()[$this->getItems()[$this->getItemIndex()[$value]]['ProductID']]]['Title'] . "</a>"; 
				}
				$output .= '</td>
				<td><a href="viewbundle.php?id='. $this->getItems()[$this->getItemIndex()[$value]]['TransID'].'">'. nl2br($this->getPurchases()[$this->getPurchaseIndex()[$this->getItems()[$this->getItemIndex()[$value]]['TransID']]]['Title']).'</a></td>';
				if($this->getItems()[$this->getItemIndex()[$value]]['ParentProductID']<>$this->getItems()[$this->getItemIndex()[$value]]['ProductID']) {
					$output .= '<td><a href="viewgame.php?id='. $this->getItems()[$this->getItemIndex()[$value]]['ParentProductID'].'">'. $this->getGames()[$this->getGameIndex()[$this->getItems()[$this->getItemIndex()[$value]]['ParentProductID']]]['Title'].'</a></td>';
				} else {
					$output .= '<td></td>';
				}

				$output .= '<td>'. $this->getItems()[$this->getItemIndex()[$value]]['Tier'].'</td>
				<td>'. nl2br($this->getItems()[$this->getItemIndex()[$value]]['Notes'] ?? "").'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['SizeMB'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['DRM'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['OS'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['ActivationKey'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['DateAdded'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['Time Added'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['Sequence'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['Library'].'</td>
				<td>'. $this->getItems()[$this->getItemIndex()[$value]]['PrintAddedTimeStamp'].'</td>';
				//$output .= '<td class="Hidden">'; var_dump($value); $output .= '</td>';
				//$output .= '<td class="Hidden">'; var_dump($this->getItems()[$this->getItemIndex()[$value]]); $output .= '</td>';
				$output .= '</tr>';
			}
			
			$output .= '</table>
			</details>';
		} else {
			$output .= 0;
		}
		return $output;
	}

	private function makeProductsTable(){
		$output="";
		if(isset($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['ProductsinBunde']) && count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['ProductsinBunde'])>0) {
			$output .= '<details>
			<summary>';
			$output .= count($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['ProductsinBunde']);
			$output .= '</summary>
				<table>
				<thead><tr>
				<th>Product</th><th>Parent Game</th><th>Series</th><th>Want</th><th>Playable</th><th>Type</th><th>Launch Date</th><th>Launch Price</th><th>MSRP</th><th>Current MSRP</th><th>Historic Low</th><th>Historic Low Date</th><th>Steam Achievements</th><th>Steam Cards</th><th>Time To Beat</th><th>Metascore</th><th>Metascore User</th><th>Steam Rating</th><th>Date Updated</th>
				<th class="hidden">Steam Store</th><th class="hidden">GOG</th><th class="hidden">isthereanydeal</th><th class="hidden">Developer</th><th class="hidden">Publisher</th>
				</tr></thead>';
				foreach ($this->getPurchases()[$this->getPurchaseIndex()[$_GET['id']]]['ProductsinBunde'] as $value) {
					$output .= '<tr>
					<td><a href="viewgame.php?id='. $this->getGames()[$this->getGameIndex()[$value]]['Game_ID'].'">'. $this->getGames()[$this->getGameIndex()[$value]]['Title'].'</a></td>';
					
					if($this->getGames()[$this->getGameIndex()[$value]]['ParentGameID']<>$this->getGames()[$this->getGameIndex()[$value]]['Game_ID']) {
						$output .= '<td><a href="viewgame.php?id='. $this->getGames()[$this->getGameIndex()[$value]]['ParentGameID'].'">'. $this->getGames()[$this->getGameIndex()[$this->getGames()[$this->getGameIndex()[$value]]['ParentGameID']]]['Title'].'</a></td>';
					} else {
						$output .= '<td></td>';
					}
					
					$output .= '<td>'. $this->getGames()[$this->getGameIndex()[$value]]['Series'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['Want'].'</td>
					<td>'. booltext($this->getGames()[$this->getGameIndex()[$value]]['Playable']).'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['Type'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['LaunchDate']->format("n/d/Y").'</td>
					<td>'. "$" . sprintf('%.2f', $this->getGames()[$this->getGameIndex()[$value]]['LaunchPrice']).'</td>
					<td>'. "$" . sprintf('%.2f', $this->getGames()[$this->getGameIndex()[$value]]['MSRP']).'</td>
					<td>'. "$" . sprintf('%.2f', $this->getGames()[$this->getGameIndex()[$value]]['CurrentMSRP']).'</td>
					<td>'. "$" . sprintf('%.2f', $this->getGames()[$this->getGameIndex()[$value]]['HistoricLow']).'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['LowDate'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['SteamAchievements'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['SteamCards'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['TimeToBeatLink2'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['MetascoreLinkCritic'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['MetascoreLinkUser'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['SteamRating'].'</td>
					<td>'. $this->getGames()[$this->getGameIndex()[$value]]['DateUpdated'].'</td>';
					$output .= '<td class="hidden">'. $this->getGames()[$this->getGameIndex()[$value]]['SteamLinks'].'</td>';
					$output .= '<td class="hidden">'. $this->getGames()[$this->getGameIndex()[$value]]['GOGLink'].'</td>';
					$output .= '<td class="hidden">'. $this->getGames()[$this->getGameIndex()[$value]]['isthereanydealLink'].'</td>';
					$output .= '<td class="hidden">'. $this->getGames()[$this->getGameIndex()[$value]]['Developer'].'</td>';
					$output .= '<td class="hidden">'. $this->getGames()[$this->getGameIndex()[$value]]['Publisher'].'</td>';
					//$output .= '<td class="hidden">'; var_dump($value); $output .= '</td>';
					//$output .= '<td class="hidden">'; var_dump($this->getGames()[$this->getGameIndex()[$value]]); $output .= '</td>';
					$output .= '</tr>';
				} 
				//TODO: Clean up hidden data.
				
				$output .= '</table>
			</details>';
		} else {
			$output .= 0;
		}
		return $output;
	}

	public function buildHtmlBody(){
		$output="";
		
		if (isset($_POST['TransID'])){
			//TODO: cleanse post data
			$this->getDataAccessObject()->updateBundle($_POST);
		}

		//<div style="background:yellow;color:black">WORK IN PROGRESS</div>

		$edit_mode=false;
		if (isset($_GET['edit']) && $_GET['edit'] = 1) {
			$edit_mode=true;	
		}

		if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
			$output .= $this->makePrompt();
		} else {
			$output .= $this->makeBundleTable($_GET['id'],$edit_mode);
		}

		//<div style="background:yellow;color:black">WORK IN PROGRESS</div>
		return $output;
	}
	
}	