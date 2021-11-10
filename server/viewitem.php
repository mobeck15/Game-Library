<?php
$GLOBALS['rootpath']=".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="View Item";
echo Get_Header($title);

$conn=get_db_connection();

if(isset($_POST['ItemID'])){
	//if($GLOBALS['Debug_Enabled']) {trigger_error("Post data listed below", E_USER_NOTICE);}
	//echo '<pre>'. print_r($_POST,true) . "</pre>";
	
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


	if($GLOBALS['Debug_Enabled']) {trigger_error("Running SQL Query to update transaction: ". $update_SQL, E_USER_NOTICE);}
	
	if ($conn->query($update_SQL) === TRUE) {
		if($GLOBALS['Debug_Enabled']) { trigger_error("Item record inserted successfully", E_USER_NOTICE);}

		$file = 'insertlog'.date("Y").'.txt';
		// Write the contents to the file, 
		// using the FILE_APPEND flag to append the content to the end of the file
		// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
		file_put_contents($file, $update_SQL.";\r\n", FILE_APPEND | LOCK_EX);
		
	} else {
		trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
	}
	echo "<hr>";
}

$settings=getsettings($conn);

$gameID="";
$games=getGames($gameID,$conn);
$items=getAllItems($gameID,$conn);
$purchases=getPurchases("",$conn,$items,$games);

$gameIndex=makeIndex($games,"Game_ID");
$itemIndex=makeIndex($items,"ItemID");
$purchaseIndex=makeIndex($purchases,"TransID");

?>
<p>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
$edit_mode=false;
if (isset($_GET['edit']) && $_GET['edit'] = 1) {
	$edit_mode=true;	
}


if (!isset($_GET['id'])) {
	//TODO: Add ajax lookup by name
	//Need a new lookup function to search the notes in the items.
	?>
	Please specify a item by ID.
	<form method="Get">
		<input type="numeric" name="id">
		<input type="submit">
	</form>
	<?php
	
} else {
	
	//echo "<p>"; var_dump($_POST);
	//echo "<p>"; var_dump($items[$itemIndex[$_GET['id']]]);
	
	?>
	<div><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']-1); ?>">&lt;--Prev</a> | <a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']+1); ?>">Next--&gt;</a></div>
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
				<td>
				<?php 
				echo $items[$itemIndex[$_GET['id']]]['ItemID'];
				if ($edit_mode === true) { ?>
				<input type="hidden" name="ItemID" value="<?php echo $items[$itemIndex[$_GET['id']]]['ItemID']; ?>">
				<?php } ?>
				</td>
			</tr>

			<tr>
				<th>Bundle ID</th>
				<td>
				<a href="viewbundle.php?id=<?php echo $items[$itemIndex[$_GET['id']]]['TransID']; ?>"><?php echo $items[$itemIndex[$_GET['id']]]['TransID']; ?></a>
				<?php if ($edit_mode === true) { ?>
				<input type="number" name="TransID" min="0" max="99999" id="TransactionID" value="<?php echo $items[$itemIndex[$_GET['id']]]['TransID']; ?>">
				(?)<input id="BundleTitle" size=30 value="<?php echo $purchases[$purchaseIndex[$items[$itemIndex[$_GET['id']]]['TransID']]]['Title']; ?>">
				<script>
				//TODO: Auto-fill script not changing bundle ID value on edit.
				  $(function() {
					$( "#BundleTitle" ).autocomplete({
						source: function(request, response) {
							$.getJSON(
								"ajax/search.ajax.php",
								{ term:request.term, querytype:'Trans' }, 
								response
							);
						},
						select: function(event, ui){
							$("#TransactionID").val(ui.item.id);
						}
					});
				  });
				</script>
				<?php } else { echo nl2br($purchases[$purchaseIndex[$items[$itemIndex[$_GET['id']]]['TransID']]]['Title']); }?>
				</td>
			</tr>

			<tr>
				<th>Game ID</th>
				<td>
				<a href="viewgame.php?id=<?php echo $items[$itemIndex[$_GET['id']]]['ProductID']; ?>"><?php echo $items[$itemIndex[$_GET['id']]]['ProductID']; ?></a>
				<?php if ($edit_mode === true) { ?>
				<input type="number" name="ProductID" min="0" max="99999" class='auto' id="ProductID" value="<?php echo $items[$itemIndex[$_GET['id']]]['ProductID']; ?>">
				(?)<input id="GameTitle" size=30 value="<?php 
					if(isset($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']])) {
						echo $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]['Title'];
					} 
				?>">
				<?php } else { 
					if(isset($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']])) {
					
						//echo "<p>Get ID: "; var_dump($_GET['id']); 
						//echo "<p>ItemIndex: "; var_dump($itemIndex[$_GET['id']]); 
						//echo "<p>Item: "; var_dump($items[$itemIndex[$_GET['id']]]); 
						//echo "<p>Product ID: "; var_dump($items[$itemIndex[$_GET['id']]]['ProductID']); 
						//echo "<p>gameindex: "; var_dump($gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]); 
						//echo "<p>game: "; var_dump($games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]); 
						//echo "<p>game Title: ";
						
						echo $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ProductID']]]['Title']; 
					}
				} ?>
				</td>
			</tr>
			<script>
			  $(function() {
					$('#GameTitle').autocomplete({ 
						source: "ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ProductID").val(ui.item.id);
						} }
					);
				} );
			</script>

			<tr>
				<th>Parent Game ID</th>
				<td>
				<a href="viewgame.php?id=<?php echo $items[$itemIndex[$_GET['id']]]['ParentProductID']; ?>"><?php echo $items[$itemIndex[$_GET['id']]]['ParentProductID']; ?></a>
				<?php if ($edit_mode === true) { ?>
				<input type="number" name="ParentProductID" min="0" max="99999" id="ParentProductID" value="<?php echo $items[$itemIndex[$_GET['id']]]['ParentProductID']; ?>">
				(?)<input id="ParentTitle" size=30 value="<?php echo $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ParentProductID']]]['Title']; ?>">
				<?php } else { echo $games[$gameIndex[$items[$itemIndex[$_GET['id']]]['ParentProductID']]]['Title']; } ?>
				</td>
			</tr>
			<script>
			  $(function() {
					$('#ParentTitle').autocomplete({ 
						source: "ajax/search.ajax.php",
						select: function (event, ui) { 
							$("#ParentProductID").val(ui.item.id);
						} }
					);
			  } );
			</script>			

			<tr>
				<th>Notes</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<textarea name="Notes" id="Notes" rows="6" cols="33"><?php echo $items[$itemIndex[$_GET['id']]]['Notes']; ?></textarea>
				<?php } else { echo nl2br($items[$itemIndex[$_GET['id']]]['Notes']); } ?>
				</td>
			</tr>

			<tr>
				<th>Tier</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="number" name="Tier" min="1" value="<?php echo $items[$itemIndex[$_GET['id']]]['Tier']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['Tier']; } ?>
				</td>
			</tr>

			<tr>
				<th>Activation Key</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="text" name="ActivationKey" value="<?php echo $items[$itemIndex[$_GET['id']]]['ActivationKey']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['ActivationKey']; } ?>
				</td>
			</tr>

			<tr>
				<th>Size (in MB)</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="number" name="SizeMB" min="0" value="<?php echo $items[$itemIndex[$_GET['id']]]['SizeMB']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['SizeMB']; } ?>
				</td>
			</tr>

			<tr>
				<th>Library</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="text" name="Library" id="Library" onchange='$("#DRM").val(this.value);' value="<?php echo $items[$itemIndex[$_GET['id']]]['Library']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['Library']; } ?>
				</td>
			</tr>
			<script>
			  $(function() {
				$( "#Library" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'Library' }, 
							response
						);
					}
			   });
			  });
			</script>

			<tr>
				<th>DRM</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="text" name="DRM" id="DRM" value="<?php echo $items[$itemIndex[$_GET['id']]]['DRM']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['DRM']; } ?>
				</td>
			</tr>
			<script>
			  $(function() {
				$( "#DRM" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'DRM' }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>OS</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="text" name="OS" id="OS" value="<?php echo $items[$itemIndex[$_GET['id']]]['OS']; ?>">
				<?php } else { echo $items[$itemIndex[$_GET['id']]]['OS']; } ?>
				</td>
			</tr>
			<script>
			  $(function() {
				$( "#OS" ).autocomplete({
					source: function(request, response) {
						$.getJSON(
							"ajax/search.ajax.php",
							{ term:request.term, querytype:'OS' }, 
							response
						);
					}
				});
			  });
			</script>

			<tr>
				<th>Date/Time Added</th>
				<td>
				<?php if ($edit_mode === true) { ?>
				<input type="datetime-local" name="purchasetime" value="<?php echo date("Y-m-d\TH:i:s",$items[$itemIndex[$_GET['id']]]['AddedTimeStamp']); ?>">
				<br>Sequence: <input type="number" name="Sequence" min="1" max="999" step="1" value="<?php echo $items[$itemIndex[$_GET['id']]]['Sequence']; ?>">
				<?php } else { 
					echo date("n/j/Y g:i:s a",$items[$itemIndex[$_GET['id']]]['AddedTimeStamp']); } 
				?>
				</td>
			</tr>
			
		</table>
		<?php if($edit_mode) { ?>
			<div><input type='submit' value='Save'></div>
		<?php } else { ?>
			<div><a href='<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&edit=1'>Edit</a></div>
		<?php } ?>
		</form>
	<?php
}


?>

<?php 
$conn -> close();
unset($conn);
unset($settings);

echo Get_Footer(); ?>