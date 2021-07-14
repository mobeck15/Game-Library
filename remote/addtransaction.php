<?php
$time_start = microtime(true);
$path = $_SERVER['DOCUMENT_ROOT'];
include "inc/php.ini.inc.php";

include 'inc/functions.inc.php';
$title="Add Transaction";
echo Get_Header($title);

//DONE: AUTH.INC.PHP is called multiple times (it's called again in get_db_connection
//include "inc/auth.inc.php";
//$conn = new mysqli($servername, $username, $password, $dbname);
$conn=get_db_connection();

$settings=getsettings($conn);

if(isset($_POST['TransID'])){
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
			
	$insert_SQL  = "INSERT INTO `gl_transactions` (`TransID`, `Title`, `Store`, `BundleID`, `Tier`, `PurchaseDate`, `PurchaseTime`, `Sequence`, `Price`, `Fees`, `Paid`, `Credit Used`, `Bundle Link`)";
	$insert_SQL .= "VALUES (";
	$insert_SQL .= $_POST['TransID'].", ";
	$insert_SQL .= $_POST['Title'].", ";
	$insert_SQL .= $_POST['Store'].", ";
	$insert_SQL .= $_POST['BundleID'].", ";
	$insert_SQL .= $_POST['Tier'].", ";
	$insert_SQL .= $_POST['PurchaseDate'].", ";
	$insert_SQL .= $_POST['PurchaseTime'].", ";
	$insert_SQL .= $_POST['Sequence'].", ";
	$insert_SQL .= $_POST['Price'].", ";
	$insert_SQL .= $_POST['Fees'].", ";
	$insert_SQL .= $_POST['Paid'].", ";
	$insert_SQL .= $_POST['CreditUsed'].", ";
	$insert_SQL .= $_POST['BundleLink'].");";

		if($GLOBALS['Debug_Enabled']) {trigger_error("Running SQL Query to add new Item: ". $insert_SQL, E_USER_NOTICE);}
		
		if ($conn->query($insert_SQL) === TRUE) {
			if($GLOBALS['Debug_Enabled']) { trigger_error("Item record inserted successfully", E_USER_NOTICE);}
		} else {
			trigger_error( "Error inserting record: " . $conn->error ,E_USER_ERROR );
		}
	echo "<hr>";
}

?>
	
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<form action="addtransaction.php" method="post">
		<table  class="ui-widget">
			<thead>
			<tr>
				<th colspan=4>New Transaction / Bundle</th>
			</tr>

			<tr>
				<th>Field</th>
				<th>Value</th>
				<th>Description</th>
				<th>Lookup Prompt</th>
			</tr>
			</thead>

<?php
$sql="select max(`TransID`) maxid from `gl_transactions`";
if($result = $conn->query($sql)){
	while($row = $result->fetch_assoc()) {
		$nextTrans_ID=$row['maxid']+1;
	}
}


$conn->close();	

$blank="";

/* 
 * TODO: At some point update this to make use of INFORMATION_SCHEMA.COLUMNS to retrieve
 * a description for each field. At this time I can't figure out how to read that table
 * select * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'transactions'
 * select `COLUMN_NAME`, `COLUMN_COMMENT` FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'transactions'
 * ALTER TABLE `gl_transactions` CHANGE `Game_ID` `Game_ID` INT(11) NOT NULL COMMENT 'Game ID';
 */

?>

			<tr>
				<th>Transaction ID *</th>
				<td><input type="number" name="TransID" min="0" id="TransactionID" onchange='$("#BundleID").val(this.value);' value="<?php echo $nextTrans_ID; ?>"></td>
				<td>The purchase transaction which included this item (Bundle)</td>
				<td></td>
			</tr>

			<tr>
				<th>Title *</th>
				<td><input type="text" name="Title" value="<?php echo $blank; ?>"></td>
				<td>The name of the purchased bundle or package</td>
				<td></td>
			</tr>

			<tr>
				<th>Store (?)</th>
				<td><input type="text" name="Store" id="Store" value="<?php echo $blank; ?>"></td>
				<td>The store purchased from</td>
				<td></td>
			</tr>
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
			
			<tr>
				<th>Bundle ID *</th>
				<td><input type="number" name="BundleID" min="0"  id="BundleID" value="<?php echo $nextTrans_ID; ?>"></td>
				<td>The parent transaction if this is part of another bundle.</td>
				<td>(?)<input id="BundleTitle" size=30></td>
			</tr>
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

			<tr>
				<th>Tier</th>
				<td><input type="number" name="Tier" min="1" value="<?php echo 1; ?>"></td>
				<td>The bundle Tier this item was purchased in.</td>
				<td></td>
			</tr>

			<tr>
				<th>Purchase Date *</th>
<?php 
//$useDate="2019-10-31";
$useDate=date("Y-m-d");
?>
				<td><input type="date" name="PurchaseDate" value="<?php echo $useDate; ?>"></td>
				<td>Date this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Purchase Time *</th>
<?php 
$useTime="10:00:00";
?>
				<td><input type="time" name="PurchaseTime" min="0" value="<?php echo $useTime; ?>"></td>
				<td>Time this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Sequence</th>
				<td><input type="number" name="Sequence" min="0" value="<?php echo 1; ?>"></td>
				<td>Sequence for this item in the bundle</td>
				<td></td>
			</tr>
			
			<tr><th>Price *</th>
				<td><input type="number" name="Price" id="Price" onchange='$("#Paid").val(Number(this.value)+Number(document.getElementById("Fees").value));' step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The price paid (Before tax or fees)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>
			
			<tr><th>Fees *</th>
				<td><input type="number" name="Fees" id="Fees" onchange='$("#Paid").val(Number(this.value)+Number(document.getElementById("Price").value));' step="0.01" value="<?php echo $blank; ?>"></td>
				<td>The fees paid (such as tax)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>
			
			<tr><th>Paid *</th>
				<td><input type="number" name="Paid" id="Paid" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>Total paid (Price + Fees)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>

			<tr><th>Credit Used *</th>
				<td><input type="number" name="CreditUsed" step="0.01" value="<?php echo $blank; ?>"></td>
				<td>Any store credit used in this transaction. <br>Negative value represents a credit gain.</td>
			<td></td></tr>
			
			<tr>
				<th>Bundle Link</th>
				<td><input type="text" name="BundleLink" value="<?php echo $blank; ?>"></td>
				<td>The URL to the bundle page</td>
				<td></td>
			</tr>
			

			<tr><th colspan=4><input type="submit" value="Save"></th></tr>
			<tr><th colspan=4>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
		</table>
		</form>

<?php echo Get_Footer(); ?>