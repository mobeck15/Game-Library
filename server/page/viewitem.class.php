<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";

class viewitemPage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="CPI";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();

if(isset($_POST['ItemID'])){
	//if($GLOBALS['Debug_Enabled']) {trigger_error("Post data listed below", E_USER_NOTICE);}
	//$output .= '<pre>'. print_r($_POST,true) . "</pre>";
	
	$update_SQL  = "UPDATE `gl_items` SET ";
	if(mysqli_real_escape_string($conn, $_POST['ProductID'])=="") {
		$update_SQL .= "`ProductID`       = NULL, ";
	} else {
		$update_SQL .= "`ProductID`       = '". mysqli_real_escape_string($conn, $_POST['ProductID'])       . "', ";
	}
	$update_SQL .= "`TransID`         = '". mysqli_real_escape_string($conn, $_POST['TransID'])         . "', ";
	$update_SQL .= "`ParentProductID` = '". mysqli_real_escape_string($conn, $_POST['ParentProductID']) . "', ";
	$update_SQL .= "`Notes`           = '". mysqli_real_escape_string($conn, $_POST['Notes'])           . "', ";
	$update_SQL .= "`Tier`            = '". mysqli_real_escape_string($conn, $_POST['Tier'])            . "', ";
	$update_SQL .= "`ActivationKey`   = '". mysqli_real_escape_string($conn, $_POST['ActivationKey'])   . "', ";
	if(mysqli_real_escape_string($conn, $_POST['SizeMB'])=="") {
		$update_SQL .= "`SizeMB`          = NULL, ";
	} else {
		$update_SQL .= "`SizeMB`          = '". mysqli_real_escape_string($conn, $_POST['SizeMB'])          . "', ";
	}
	$update_SQL .= "`Library`         = '". mysqli_real_escape_string($conn, $_POST['Library'])         . "', ";
	$update_SQL .= "`DRM`             = '". mysqli_real_escape_string($conn, $_POST['DRM'])             . "', ";
	$update_SQL .= "`OS`              = '". mysqli_real_escape_string($conn, $_POST['OS'])              . "', ";
	$update_SQL .= "`DateAdded`       = '". mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['purchasetime'])))  . "', ";
	$update_SQL .= "`Time Added`      = '". mysqli_real_escape_string($conn, date("H:i:00",strtotime($_POST['purchasetime']))) . "', ";
	//$update_SQL .= "`Sequence`        = '". mysqli_real_escape_string($conn, date("s",strtotime($_POST['purchasetime'])))    . "', ";
	$update_SQL .= "`Sequence`        = '". mysqli_real_escape_string($conn, $_POST['Sequence'])        . "' ";
	$update_SQL .= "WHERE `ItemID` = " . mysqli_real_escape_string($conn, $_POST['ItemID']);


	if($GLOBALS['Debug_Enabled'] ?? false) {trigger_error("Running SQL Query to update transaction: ". $update_SQL, E_USER_NOTICE);}
	
	if ($conn->query($update_SQL) === TRUE) {
		$output .= "Item record inserted successfully";
		
		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $update_SQL.";\r\n", FILE_APPEND | LOCK_EX);
		
	} else {
		trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
	}
	$output .= "<hr>";
}

$settings=getsettings($conn);

$gameID="";
$games=getGames($gameID,$conn);
$items=getAllItems($gameID,$conn);
//$purchases=getPurchases("",$conn,$items,$games);
$purchaseobj=new Purchases("",$conn,$items,$games);
$purchases=$purchaseobj->getPurchases();

$gameIndex=makeIndex($games,"Game_ID");
$itemIndex=makeIndex($items,"ItemID");
$purchaseIndex=makeIndex($purchases,"TransID");

$output .= '<p>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
$edit_mode=false;
if (isset($_GET['edit']) && $_GET['edit'] = 1) {
	$edit_mode=true;	
}


if (!isset($_GET['id'])) {
	//TODO: Add ajax lookup by name
	//Need a new lookup function to search the notes in the items.
	$output .= 'Please specify a item by ID.
	<form method="Get">
		<input type="numeric" name="id">
		<input type="submit">
	</form>';
	
} else {
	
	//$output .= "<p>"; var_dump($_POST);
	//$output .= "<p>"; var_dump($items[$itemIndex[$_GET['id']]]);
	
	$output .= '<div><a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']-1).'">&lt;--Prev</a> | <a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']+1).'">Next--&gt;</a></div>
	<form method="post">
	<table class="ui-widget">
		<thead>
		<tr>
			<th colspan=4>Edit Item</th>
		</tr>

		<tr>
			<th>Field</th>
			<th>Value</th>
		</tr>
		</thead>

			<tr>
				<th>Item ID</th>
				<td>';
				$output .= $items[$itemIndex[$_GET['id']]]['ItemID'];
				if ($edit_mode === true) { 
				$output .= '<input type="hidden" name="ItemID" value="'. $items[$itemIndex[$_GET['id']]]['ItemID'].'">';
				}
				$output .= '</td>
			</tr>

			<tr>
				<th>Bundle ID</th>
				<td>
				<a href="viewbundle.php?id='. $items[$itemIndex[$_GET['id']]]['TransID'].'">'. $items[$itemIndex[$_GET['id']]]['TransID'].'</a> ';
				if ($edit_mode === true) { 
				$output .= '<input type="number" name="TransID" min="0" max="99999" id="TransactionID" value="'. $items[$itemIndex[$_GET['id']]]['TransID'].'">
				(?)<input id="BundleTitle" size=30 value="'. $purchases[$purchaseIndex[$items[$itemIndex[$_GET['id']]]['TransID']]]['Title'].'">
				<script>
				//TODO: Auto-fill script not changing bundle ID value on edit.
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
							$("#TransactionID").val(ui.item.id);
						}
					});
				  });
				</script>';
				} else { 
					$output .= nl2br($purchases[$purchaseIndex[$items[$itemIndex[$_GET['id']]]['TransID']]]['Title']); 
				}
				$output .= '</td>
			</tr>

			<tr>
				<th>Game ID</th>
				<td>
				<a href="viewgame.php?id='. $items[$itemIndex[$_GET['id']]]['ProductID'].'">'. $items[$itemIndex[$_GET['id']]]['ProductID'].'</a> ';
				if ($edit_mode === true) {
				$output .= '<input type="number" name="ProductID" min="0" max="99999" class="auto" id="ProductID" value="'. $items[$itemIndex[$_GET['id']]]['ProductID'].'">
				(?)<input id="GameTitle" size=30 value="';
					if(isset($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']])) {
						$output .= $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]['Title'];
					} 
				$output .= '">';
				} else { 
					if(isset($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']])) {
					
						//$output .= "<p>Get ID: "; var_dump($_GET['id']); 
						//$output .= "<p>ItemIndex: "; var_dump($itemIndex[$_GET['id']]); 
						//$output .= "<p>Item: "; var_dump($items[$itemIndex[$_GET['id']]]); 
						//$output .= "<p>Product ID: "; var_dump($items[$itemIndex[$_GET['id']]]['ProductID']); 
						//$output .= "<p>gameindex: "; var_dump($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]); 
						//$output .= "<p>game: "; var_dump($games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]); 
						//$output .= "<p>game Title: ";
						
						$output .= $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]['Title']; 
					}
				}
				$output .= "</td>
			</tr>
			<script>
			  $(function() {
					$('#GameTitle').autocomplete({ 
						source: 'ajax/search.ajax.php',
						select: function (event, ui) { 
							$('#ProductID').val(ui.item.id);
						} }
					);
				} );
			</script>

			<tr>
				<th>Parent Game ID</th>
				<td>";
				$output .= '<a href="viewgame.php?id='. $items[$itemIndex[$_GET['id']]]['ParentProductID'].'">'. $items[$itemIndex[$_GET['id']]]['ParentProductID'].'</a> ';
				if ($edit_mode === true) {
				$output .= '<input type="number" name="ParentProductID" min="0" max="99999" id="ParentProductID" value="'. $items[$itemIndex[$_GET['id']]]['ParentProductID'].'">
				(?)<input id="ParentTitle" size=30 value="'. $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ParentProductID']]]['Title'].'">';
				} else { 
					$output .= $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ParentProductID']]]['Title']; 
				}
				$output .= "</td>
			</tr>
			<script>';
			  $(function() {
					$('#ParentTitle').autocomplete({ 
						source: 'ajax/search.ajax.php',
						select: function (event, ui) { 
							$('#ParentProductID').val(ui.item.id);
						} }
					);
			  } );
			</script>

			<tr>
				<th>Notes</th>
				<td>";
				if ($edit_mode === true) { 
				$output .= '<textarea name="Notes" id="Notes" rows="6" cols="33">'. $items[$itemIndex[$_GET['id']]]['Notes'].'</textarea>';
				} else { $output .= nl2br($items[$itemIndex[$_GET['id']]]['Notes']); }
				$output .= '</td>
			</tr>

			<tr>
				<th>Tier</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="number" name="Tier" min="1" value="'. $items[$itemIndex[$_GET['id']]]['Tier'].'">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['Tier']; }
				$output .= '</td>
			</tr>

			<tr>
				<th>Activation Key</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="text" name="ActivationKey" value="'. $items[$itemIndex[$_GET['id']]]['ActivationKey'].'">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['ActivationKey']; }
				$output .= '</td>
			</tr>

			<tr>
				<th>Size (in MB)</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="number" name="SizeMB" min="0" value="'. $items[$itemIndex[$_GET['id']]]['SizeMB'].'>">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['SizeMB']; }
				$output .= '</td>
			</tr>

			<tr>
				<th>Library</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="text" name="Library" id="Library" onchange=\'$("#DRM").val(this.value);\' value="'. $items[$itemIndex[$_GET['id']]]['Library'].'">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['Library']; }
				$output .= '</td>
			</tr>
			<script>
			  $(function() {
				$( "#Library" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"Library" }, 
							response
						);
					}
			   });
			  });
			</script>

			<tr>
				<th>DRM</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="text" name="DRM" id="DRM" value="'. $items[$itemIndex[$_GET['id']]]['DRM'].'">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['DRM']; }
				$output .= '</td>
			</tr>
			<script>
			  $(function() {
				$( "#DRM" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"DRM" }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>OS</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="text" name="OS" id="OS" value="'. $items[$itemIndex[$_GET['id']]]['OS'].'">';
				} else { $output .= $items[$itemIndex[$_GET['id']]]['OS']; }
				$output .= '</td>
			</tr>
			<script>
			  $(function() {
				$( "#OS" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:"OS" }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>Date/Time Added</th>
				<td>';
				if ($edit_mode === true) {
				$output .= '<input type="datetime-local" name="purchasetime" value="'. date("Y-m-d\TH:i:s",$items[$itemIndex[$_GET['id']]]['AddedTimeStamp']).'">
				<br>Sequence: <input type="number" name="Sequence" min="1" max="999" step="1" value="'. $items[$itemIndex[$_GET['id']]]['Sequence'].'">';
				} else { 
					$output .= date("n/j/Y g:i:s a",$items[$itemIndex[$_GET['id']]]['AddedTimeStamp']); } 
				$output .= '</td>
			</tr>
			
		</table>';
		if($edit_mode) {
			$output .= "<div><input type='submit' value='Save'></div>";
		} else {
			$output .= "<div><a href='". $_SERVER['PHP_SELF']."?id=". $_GET['id']."&edit=1'>Edit</a></div>";
		}
		$output .= '</form>';
}

$conn -> close();
unset($conn);
unset($settings);
		return $output;
	}
}	