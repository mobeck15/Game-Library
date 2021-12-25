<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="View Bundle";
echo Get_Header($title);

$conn=get_db_connection();

if(isset($_POST['TransID'])){
	//if($GLOBALS['Debug_Enabled']) {trigger_error("Post data listed below", E_USER_NOTICE);}
	//echo '<pre>'. print_r($_POST,true) . "</pre>";
	
	$update_SQL  = "UPDATE `gl_transactions` SET ";
	$update_SQL .= "`Title`        = '". mysqli_real_escape_string($conn, $_POST['Title'])             . "', ";
	$update_SQL .= "`Store`        = '". mysqli_real_escape_string($conn, $_POST['Store'])             . "', ";
	$update_SQL .= "`BundleID`     = '". mysqli_real_escape_string($conn, $_POST['BundleID'])             . "', ";
	$update_SQL .= "`Tier`         = '". mysqli_real_escape_string($conn, $_POST['Tier'])             . "', ";
	$update_SQL .= "`PurchaseDate` = '". mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['purchasetime'])))             . "', ";
	$update_SQL .= "`PurchaseTime` = '". mysqli_real_escape_string($conn, date("H:i:00",strtotime($_POST['purchasetime'])))             . "', ";
	//$update_SQL .= "`Sequence`     = '". mysqli_real_escape_string($conn, date("s",strtotime($_POST['purchasetime'])))              . "', ";
	$update_SQL .= "`Sequence`     = '". mysqli_real_escape_string($conn, $_POST['Sequence'])              . "', ";
	$update_SQL .= "`Price`        = '". mysqli_real_escape_string($conn, $_POST['Price'])             . "', ";
	$update_SQL .= "`Fees`         = '". mysqli_real_escape_string($conn, $_POST['Fees'])             . "', ";
	$update_SQL .= "`Paid`         = '". mysqli_real_escape_string($conn, $_POST['Paid'])             . "', ";
	$update_SQL .= "`Credit Used`   = '". mysqli_real_escape_string($conn, $_POST['Credit'])             . "', ";
	$update_SQL .= "`Bundle Link`   = '". mysqli_real_escape_string($conn, $_POST['Link'])             . "' ";
	$update_SQL .= "WHERE `TransID` = " . mysqli_real_escape_string($conn, $_POST['TransID']);


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

$purchaseIndex=makeIndex($purchases,"TransID");
$gameIndex=makeIndex($games,"Game_ID");
$itemIndex=makeIndex($items,"ItemID");

//<div style="background:yellow;color:black">WORK IN PROGRESS</div>

$lookupbundle=lookupTextBox("BundleTitle", "BundleID", "id", "Trans");
echo $lookupbundle["header"];

$edit_mode=false;
if (isset($_GET['edit']) && $_GET['edit'] = 1) {
	$edit_mode=true;	
}

if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
	?>
		Please specify a bundle by ID.
		<form method="Get">
			<?php echo $lookupbundle["textBox"]; ?>
			<input type="submit">
		</form>

		<?php
		echo $lookupbundle["lookupBox"];
} else { ?>
	<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']-1); ?>">&lt;--Prev</a> | <a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']+1); ?>">Next--&gt;</a>
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
		</thead>
	
	<?php
	
	//var_dump($_POST); echo "<p>";
	
	/*
	$sql="select * from `gl_transactions` where `TransID`=". $conn->real_escape_string($_GET['id']);
	if($result = $conn->query($sql)){
		$bundle = $result->fetch_assoc();
	}
	var_dump($bundle);
	*/
	
	//echo "<p>"; var_dump($purchases[$purchaseIndex[$_GET['id']]]);

	/*
array(25) {
  ["TransID"]=>  string(4) "1028"
  ["Title"]=>  string(24) "Prototype Franchise Pack"
  ["Store"]=>  string(5) "Steam"
  ["BundleID"]=>  string(4) "1028"
  ["Tier"]=>  string(1) "1"
  ["PurchaseDate"]=>  string(9) "6/14/2015"
  ["PurchaseTime"]=>  string(8) "18:26:00"
  ["Sequence"]=>  string(1) "1"
  ["Price"]=>  string(5) "14.39"
  ["Fees"]=>  string(4) "1.24"
  ["Paid"]=>  string(5) "15.63"
  ["Credit Used"]=>  string(5) "15.63"
  ["Bundle Link"]=>  NULL
  ["PurchaseTimeStamp"]=>  int(1434331561)
  ["PrintPurchaseTimeStamp"]=>  string(18) "6/14/2015 18:26:01"
  ["GamesinBundle"]=>  array(3) {
    [1167]=>    array(10) {
      ["GameID"]=>      string(4) "1167"
      ["Type"]=>      string(4) "Game"
      ["Playable"]=>      string(1) "1"
      ["MSRP"]=>      string(5) "19.99"
      ["Want"]=>      string(1) "4"
      ["HistoricLow"]=>      string(4) "4.79"
      ["Altwant"]=>      float(6.252)
      ["Althrs"]=>      float(3.0725641025641)
      ["SalePrice"]=>      float(4.4653951693583)
      ["AltSalePrice"]=>      float(4.722810405192)
    }
    [1168]=>    array(10) {
      ["GameID"]=>      string(4) "1168"
      ["Type"]=>      string(4) "Game"
      ["Playable"]=>      string(1) "1"
      ["MSRP"]=>      string(5) "39.99"
      ["Want"]=>      string(1) "4"
      ["HistoricLow"]=>      string(4) "7.41"
      ["Altwant"]=>      float(6.252)
      ["Althrs"]=>      float(12.557435897436)
      ["SalePrice"]=>      float(8.9330241532085)
      ["AltSalePrice"]=>      float(8.8535992560914)
    }
    [1169]=>    array(10) {
      ["GameID"]=>      string(4) "1169"
      ["Type"]=>      string(3) "DLC"
      ["Playable"]=>      string(1) "0"
      ["MSRP"]=>      string(4) "9.99"
      ["Want"]=>      string(1) "2"
      ["HistoricLow"]=>      string(4) "2.39"
      ["Altwant"]=>      float(3.126)
      ["Althrs"]=>      int(0)
      ["SalePrice"]=>      float(2.2315806774332)
      ["AltSalePrice"]=>      float(2.5669879233957)
    }
  }
  ["TotalMSRP"]=>  float(69.97)
  ["TotalWant"]=>  int(10)
  ["TotalHrs"]=>  float(70200)
  ["TopBundleID"]=>  string(4) "1028"
  ["Bundle"]=>  string(24) "Prototype Franchise Pack"
  ["TopBundle"]=>  string(24) "Prototype Franchise Pack"
  ["BundlePrice"]=>  string(5) "15.63"
  ["itemsinBundle"]=>  array(3) {
    [2497]=>    string(4) "2497"
    [2498]=>    string(4) "2498"
    [2499]=>    string(4) "2499"
  }
  ["ProductsinBunde"]=>  array(3) {
    [1167]=>    string(4) "1167"
    [1168]=>    string(4) "1168"
    [1169]=>    string(4) "1169"
  }
}
	*/
	?>
	<tr>
		<th>Transaction ID</th>
		<td>
		<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $purchases[$purchaseIndex[$_GET['id']]]['TransID']; ?>"><?php echo $purchases[$purchaseIndex[$_GET['id']]]['TransID']; ?></a>
		<?php if ($edit_mode === true) { ?>
		<input type="hidden" name="TransID" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['TransID']; ?>">
		<?php } ?>
		</td>
	</tr>
	<tr>
		<th>Title</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<textarea align=top rows=2 cols=40 name="Title"><?php echo $purchases[$purchaseIndex[$_GET['id']]]['Title']; ?></textarea>
		<?php } else {  
			echo nl2br($purchases[$purchaseIndex[$_GET['id']]]['Title']); 
		} ?>
		</td>
	</tr>
	<tr>
		<th>Store</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="Store" id="Store" size="12" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Store']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Store']; } 
		?>
		</td>
		<script>
		  $(function() {
			$( "#Store" ).autocomplete({
				source: function(request, response) {
					$.getJSON(
						"ajax/search.ajax.php",
						{ term:request.term, querytype:'Store' }, 
						response
					);
				}
			});
		  });
		</script>
	</tr>
	<tr>
		<th>Bundle ID</th>
		<td><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $purchases[$purchaseIndex[$_GET['id']]]['BundleID']; ?>"><?php echo $purchases[$purchaseIndex[$_GET['id']]]['BundleID']; ?></a>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="BundleID" id="BundleID" min="0" max="9999" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['BundleID']; ?>">
		(?)<input id="BundleTitle" size=30 value="<?php echo $purchases[$purchaseIndex[$purchases[$purchaseIndex[$_GET['id']]]['BundleID']]]['Title']; ?>">
		<script>
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
					$("#BundleID").val(ui.item.id);
				}
			});
		  });
		</script>
		<?php } else {
			echo $purchases[$purchaseIndex[$purchases[$purchaseIndex[$_GET['id']]]['BundleID']]]['Title'];
		} ?>
		</td>
	</tr>
	<tr>
		<th>Tier</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Tier" min="0" max="99" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Tier']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Tier']; } 
		?>
		</td>
	</tr>
	
	<tr class="Hidden">
		<th>Purchase Date</th>
		<td><?php echo $purchases[$purchaseIndex[$_GET['id']]]['PurchaseDate']; ?></td>
	</tr>
	<tr class="Hidden">
		<th>Purchase Time</th>
		<td><?php echo $purchases[$purchaseIndex[$_GET['id']]]['PurchaseTime']; ?></td>
	</tr>
	<tr class="Hidden">
		<th>Sequence</th>
		<td><?php echo $purchases[$purchaseIndex[$_GET['id']]]['Sequence']; ?></td>
	</tr>
	<tr>
		<th>Purchase Date/Time</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="datetime-local" name="purchasetime" value="<?php echo date("Y-m-d\TH:i:s",$purchases[$purchaseIndex[$_GET['id']]]['PurchaseDateTime']->gettimestamp()); ?>">
		<br>Sequence: <input type="number" name="Sequence" min="1" max="999" step="1" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Sequence']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['PrintPurchaseTimeStamp']; } 
		?>
		</td>
	</tr>
	
	<tr>
		<th>Price</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Price" min="-9999" max="9999" step=".01" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Price']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Price']; } 
		?>
		</td>
	</tr>
	<tr>
		<th>Fees</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Fees" min="-9999" max="9999" step=".01" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Fees']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Fees']; } 
		?>
		</td>
	</tr>
	<tr>
		<th>Paid</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Paid" min="-9999" max="9999" step=".01" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Paid']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Paid']; } 
		?>
		</td>
	</tr>
	<tr>
		<th>Credit Used</th>
		<td>
		<?php if ($edit_mode === true) { ?>
		<input type="number" name="Credit" min="-9999" max="9999" step=".01" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Credit Used']; ?>">
		<?php } else { 
			echo $purchases[$purchaseIndex[$_GET['id']]]['Credit Used']; } 
		?>
		</td>
	</tr>
	<tr>
		<th>Bundle Link</th>
		<td>
		<?php echo $purchases[$purchaseIndex[$_GET['id']]]['Bundle Link']; ?>
		<?php if ($edit_mode === true) { ?>
		<input type="text" name="Link" size="40" value="<?php echo $purchases[$purchaseIndex[$_GET['id']]]['BundleURL']; ?>">
		<?php } ?>
		</td>
	</tr>
	<tr>
		<th>Games in Bundle</th>
		<td>
		<?php if(isset($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle']) && count($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle'])>0) { ?> 
		<details>
		<summary>
		<?php echo count($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle']); ?>
		</summary>
			<table>
			<thead><tr>
			<th class="hidden">ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th><th>AltWant</th><th>AltHrs</th><th>Sale Price</th><th>Alt Sale Price</th>
			</tr></thead>
			<?php foreach ($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle'] as $value) { ?>
			<tr>
			<td class="hidden"><a href="viewgame.php?id=<?php echo $value['GameID']; ?>"><?php echo $value['GameID']; ?></a></td>
			<td><a href="viewgame.php?id=<?php echo $value['GameID']; ?>"><?php echo $games[$gameIndex[$value['GameID']]]['Title']; ?></a></td>
			<td><?php echo $value['Type']; ?></td>
			<td><?php echo booltext($value['Playable']); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $value['MSRP']); ?></td>
			<td><?php echo $value['Want']; ?></td>
			<td><?php echo "$" . sprintf('%.2f', $value['HistoricLow']); ?></td>
			<td><?php echo round($value['Altwant'],3); ?></td>
			<td><?php echo round($value['Althrs'],3); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $value['SalePrice']); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $value['AltSalePrice']); ?></td>
			<td class="hidden"><?php var_dump($value); ?></td>
			<td class="hidden"><?php var_dump($games[$gameIndex[$value['GameID']]]); ?></td>
			</tr>
			<?php } ?>
			
			</table>
		</details>
		<?php } else { ?>
		0
		<?php } ?>
		</td>
	</tr>
	<tr>
		<th>Items in Bundle</th>
		<td>
		<?php if(isset($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle']) && count($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle'])>0) { ?>
		<details>
		<summary>
		<?php echo count($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle']);  ?>
		</summary>
		
			<table>
			<thead><tr>
			<th>Item ID</th><th>Product ID</th><th>Transaction ID</th><th>Parent Product ID</th><th>Tier</th><th>Notes</th><th>SizeMB</th><th>DRM</th><th>OS</th><th>Activation Key</th><th>Date Added</th><th>Time Added</th><th>Sequence</th><th>Library</th><th>Added Time Stamp</th>
			</tr></thead>
			<?php foreach ($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle'] as $value) { ?>
			<tr>
			<td><a href="viewitem.php?id=<?php echo $items[$itemIndex[$value]]['ItemID']; ?>"><?php echo $items[$itemIndex[$value]]['ItemID']; ?></a></td>

			<td><?php 
			if (isset($items[$itemIndex[$value]]['ProductID']) && $items[$itemIndex[$value]]['ProductID']<>"") {
			echo "<a href=\"viewgame.php?id=" . $items[$itemIndex[$value]]['ProductID'] . "\">" . $games[$gameIndex[$items[$itemIndex[$value]]['ProductID']]]['Title'] . "</a>"; 
			}
			?></td>
			<td><a href="viewbundle.php?id=<?php echo $items[$itemIndex[$value]]['TransID']; ?>"><?php echo nl2br($purchases[$purchaseIndex[$items[$itemIndex[$value]]['TransID']]]['Title']); ?></a></td>
			<?php if($items[$itemIndex[$value]]['ParentProductID']<>$items[$itemIndex[$value]]['ProductID']) { ?>
			<td><a href="viewgame.php?id=<?php echo $items[$itemIndex[$value]]['ParentProductID']; ?>"><?php echo $games[$gameIndex[$items[$itemIndex[$value]]['ParentProductID']]]['Title']; ?></a></td>
			<?php } else { ?>
			<td></td>
			<?php } ?>

			<td><?php echo $items[$itemIndex[$value]]['Tier']; ?></td>
			<td><?php echo nl2br($items[$itemIndex[$value]]['Notes']); ?></td>
			<td><?php echo $items[$itemIndex[$value]]['SizeMB']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['DRM']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['OS']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['ActivationKey']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['DateAdded']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['Time Added']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['Sequence']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['Library']; ?></td>
			<td><?php echo $items[$itemIndex[$value]]['PrintAddedTimeStamp']; ?></td>
			<td class="Hidden"><?php var_dump($value); ?></td>
			<td class="Hidden"><?php var_dump($items[$itemIndex[$value]]); ?></td>
			</tr>
			<?php } ?>
			
			</table>
		</details>
		<?php } else { ?> 
			0
		<?php } ?>
		</td>
	</tr>
	<tr>
		<th>Products in Bundle</th>
		<td>
		<?php if(isset($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde']) && count($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde'])>0) { ?>
		<details>
		<summary>
		<?php echo count($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde']); ?>
		</summary>
			<table>
			<thead><tr>
			<th>Product</th><th>Parent Game</th><th>Series</th><th>Want</th><th>Playable</th><th>Type</th><th>Launch Date</th><th>Launch Price</th><th>MSRP</th><th>Current MSRP</th><th>Historic Low</th><th>Historic Low Date</th><th>Steam Achievements</th><th>Steam Cards</th><th>Time To Beat</th><th>Metascore</th><th>Metascore User</th><th>Steam Rating</th><th>Date Updated</th>
			<th class="hidden">Steam Store</th><th class="hidden">GOG</th><th class="hidden">isthereanydeal</th><th class="hidden">Developer</th><th class="hidden">Publisher</th>
			</tr></thead>
			<?php foreach ($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde'] as $value) { ?>
			<tr>
			<td><a href="viewgame.php?id=<?php echo $games[$gameIndex[$value]]['Game_ID']; ?>"><?php echo $games[$gameIndex[$value]]['Title']; ?></a></td>
			
			<?php if($games[$gameIndex[$value]]['ParentGameID']<>$games[$gameIndex[$value]]['Game_ID']) { ?>
			<td><a href="viewgame.php?id=<?php echo $games[$gameIndex[$value]]['ParentGameID']; ?>"><?php echo $games[$gameIndex[$games[$gameIndex[$value]]['ParentGameID']]]['Title']; ?></a></td>
			<?php } else { ?>
			<td></td>
			<?php } ?>
			
			<td><?php echo $games[$gameIndex[$value]]['Series']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['Want']; ?></td>
			<td><?php echo booltext($games[$gameIndex[$value]]['Playable']); ?></td>
			<td><?php echo $games[$gameIndex[$value]]['Type']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['LaunchDate']->format("n/d/Y"); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $games[$gameIndex[$value]]['LaunchPrice']); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $games[$gameIndex[$value]]['MSRP']); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $games[$gameIndex[$value]]['CurrentMSRP']); ?></td>
			<td><?php echo "$" . sprintf('%.2f', $games[$gameIndex[$value]]['HistoricLow']); ?></td>
			<td><?php echo $games[$gameIndex[$value]]['LowDate']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['SteamAchievements']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['SteamCards']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['TimeToBeatLink2']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['MetascoreLinkCritic']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['MetascoreLinkUser']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['SteamRating']; ?></td>
			<td><?php echo $games[$gameIndex[$value]]['DateUpdated']; ?></td>
			<td class="hidden"><?php echo $games[$gameIndex[$value]]['SteamLinks']; ?></td>
			<td class="hidden"><?php echo $games[$gameIndex[$value]]['GOGLink']; ?></td>
			<td class="hidden"><?php echo $games[$gameIndex[$value]]['isthereanydealLink']; ?></td>
			<td class="hidden"><?php echo $games[$gameIndex[$value]]['Developer']; ?></td>
			<td class="hidden"><?php echo $games[$gameIndex[$value]]['Publisher']; ?></td>
			<td class="hidden"><?php var_dump($value); ?></td>
			<td class="hidden"><?php var_dump($games[$gameIndex[$value]]); ?></td>
			</tr>
			<?php } 
			//Clean up hidden data.
			?>
			
			</table>
		</details>
		<?php } else { ?> 
			0
		<?php } ?>
		</td>
	</tr>
	
	</table>
	<?php if($edit_mode) { ?>
		<div><input type='submit' value='Save'></div>
	<?php } else { ?>
		<div><a href='<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>&edit=1'>Edit</a></div>
	<?php } ?>
	</form>
<?php } ?>

<?php 
//<div style="background:yellow;color:black">WORK IN PROGRESS</div>
$conn -> close();
unset($conn);
unset($settings);

echo Get_Footer(); ?>