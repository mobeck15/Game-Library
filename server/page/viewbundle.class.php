<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getGames.inc.php";

class viewbundlePage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="View Bundle";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();

if(isset($_POST['TransID'])){
	//if($GLOBALS['Debug_Enabled']) {trigger_error("Post data listed below", E_USER_NOTICE);}
	//$output .= '<pre>'. print_r($_POST,true) . "</pre>";
	
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
	$output .= "<hr>";
}

$settings=getsettings($conn);

$gameID="";
$games=getGames($gameID,$conn);
$items=getAllItems($gameID,$conn);
//$purchases=getPurchases("",$conn,$items,$games);
$purchaseobj=new Purchases("",$conn,$items,$games);
$purchases=$purchaseobj->getPurchases();

$purchaseIndex=makeIndex($purchases,"TransID");
$gameIndex=makeIndex($games,"Game_ID");
$itemIndex=makeIndex($items,"ItemID");

//<div style="background:yellow;color:black">WORK IN PROGRESS</div>

$lookupbundle=lookupTextBox("BundleTitle", "BundleID", "id", "Trans");
$output .= $lookupbundle["header"];

$edit_mode=false;
if (isset($_GET['edit']) && $_GET['edit'] = 1) {
	$edit_mode=true;	
}

if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		$output .= 'Please specify a bundle by ID.
		<form method="Get">
			'. $lookupbundle["textBox"].'
			<input type="submit">
		</form>';
		$output .= $lookupbundle["lookupBox"];
} else { 
	$output .= '<a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']-1).'">&lt;--Prev</a> | <a href="'. $_SERVER['PHP_SELF'] . "?id=" . ($_GET['id']+1).'">Next--&gt;</a>
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
	
	//var_dump($_POST); $output .= "<p>";
	
	/*
	$sql="select * from `gl_transactions` where `TransID`=". $conn->real_escape_string($_GET['id']);
	if($result = $conn->query($sql)){
		$bundle = $result->fetch_assoc();
	}
	var_dump($bundle);
	*/
	
	//$output .= "<p>"; var_dump($purchases[$purchaseIndex[$_GET['id']]]);

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
	$output .= '<tr>
		<th>Transaction ID</th>
		<td>
		<a href="'. $_SERVER['PHP_SELF'] . "?id=" . $purchases[$purchaseIndex[$_GET['id']]]['TransID'].'">'. $purchases[$purchaseIndex[$_GET['id']]]['TransID'].'</a> ';
		if ($edit_mode === true) {
		$output .= '<input type="hidden" name="TransID" value="'. $purchases[$purchaseIndex[$_GET['id']]]['TransID'].'">';
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Title</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<textarea align=top rows=2 cols=40 name="Title">'. $purchases[$purchaseIndex[$_GET['id']]]['Title'].'</textarea>';
		} else {  
			$output .= nl2br($purchases[$purchaseIndex[$_GET['id']]]['Title']); 
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Store</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="text" name="Store" id="Store" size="12" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Store'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Store']; } 
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
		<td><a href="'. $_SERVER['PHP_SELF'] . "?id=" . $purchases[$purchaseIndex[$_GET['id']]]['BundleID'].'">'. $purchases[$purchaseIndex[$_GET['id']]]['BundleID'].'</a>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="BundleID" id="BundleID" min="0" max="9999" value="'. $purchases[$purchaseIndex[$_GET['id']]]['BundleID'].'">
		(?)<input id="BundleTitle" size=30 value="'. $purchases[$purchaseIndex[$purchases[$purchaseIndex[$_GET['id']]]['BundleID']]]['Title'].'">
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
			$output .= $purchases[$purchaseIndex[$purchases[$purchaseIndex[$_GET['id']]]['BundleID']]]['Title'];
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Tier</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="Tier" min="0" max="99" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Tier'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Tier']; } 
		$output .= '</td>
	</tr>
	
	<tr class="Hidden">
		<th>Purchase Date</th>
		<td>'. $purchases[$purchaseIndex[$_GET['id']]]['PurchaseDate'].'</td>
	</tr>
	<tr class="Hidden">
		<th>Purchase Time</th>
		<td>'. $purchases[$purchaseIndex[$_GET['id']]]['PurchaseTime'].'</td>
	</tr>
	<tr class="Hidden">
		<th>Sequence</th>
		<td>'. $purchases[$purchaseIndex[$_GET['id']]]['Sequence'].'</td>
	</tr>
	<tr>
		<th>Purchase Date/Time</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="datetime-local" name="purchasetime" value="'. date("Y-m-d\TH:i:s",$purchases[$purchaseIndex[$_GET['id']]]['PurchaseDateTime']->gettimestamp()).'">
		<br>Sequence: <input type="number" name="Sequence" min="1" max="999" step="1" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Sequence'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['PrintPurchaseTimeStamp']; } 
		$output .= '</td>
	</tr>
	
	<tr>
		<th>Price</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="Price" min="-9999" max="9999" step=".01" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Price'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Price']; } 
		$output .= '</td>
	</tr>
	<tr>
		<th>Fees</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="Fees" min="-9999" max="9999" step=".01" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Fees'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Fees']; } 
		$output .= '</td>
	</tr>
	<tr>
		<th>Paid</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="Paid" min="-9999" max="9999" step=".01" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Paid'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Paid']; } 
		$output .= '</td>
	</tr>
	<tr>
		<th>Credit Used</th>
		<td>';
		if ($edit_mode === true) {
		$output .= '<input type="number" name="Credit" min="-9999" max="9999" step=".01" value="'. $purchases[$purchaseIndex[$_GET['id']]]['Credit Used'].'">';
		} else { 
			$output .= $purchases[$purchaseIndex[$_GET['id']]]['Credit Used']; } 
		$output .= '</td>
	</tr>
	<tr>
		<th>Bundle Link</th>
		<td>';
		$output .= $purchases[$purchaseIndex[$_GET['id']]]['Bundle Link'];
		if ($edit_mode === true) {
		$output .= '<input type="text" name="Link" size="40" value="'. $purchases[$purchaseIndex[$_GET['id']]]['BundleURL'].'">';
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Games in Bundle</th>
		<td>';
		if(isset($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle']) && count($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle'])>0) { 
		$output .= '<details>
		<summary>';
		$output .= count($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle']);
		$output .= '</summary>
			<table>
			<thead><tr>
			<th class="hidden">ID</th><th>Title</th><th>Type</th><th>Playable</th><th>MSRP</th><th>Want</th><th>HistoricLow</th><th>AltWant</th><th>AltHrs</th><th>Sale Price</th><th>Alt Sale Price</th>
			</tr></thead>';
			foreach ($purchases[$purchaseIndex[$_GET['id']]]['GamesinBundle'] as $value) {
				$output .= '<tr>
				<td class="hidden"><a href="viewgame.php?id='. $value['GameID'].'">'. $value['GameID'].'</a></td>
				<td><a href="viewgame.php?id='. $value['GameID'].'">'. $games[$gameIndex[$value['GameID']]]['Title'].'</a></td>
				<td>'. $value['Type'].'</td>
				<td>'. booltext($value['Playable']).'</td>
				<td>'. "$" . sprintf('%.2f', $value['MSRP']).'</td>
				<td>'. $value['Want'].'</td>
				<td>'. "$" . sprintf('%.2f', $value['HistoricLow']).'</td>
				<td>'. round($value['Altwant'],3).'</td>
				<td>'. round($value['Althrs'],3).'</td>
				<td>'. "$" . sprintf('%.2f', $value['SalePrice']).'</td>
				<td>'. "$" . sprintf('%.2f', $value['AltSalePrice']).'</td>';
				//$output .= '<td class="hidden">'; var_dump($value); $output .= '</td>';
				//$output .= '<td class="hidden">'; var_dump($games[$gameIndex[$value['GameID']]]); $output .= '</td>';
				$output .= '</tr>';
			}
			
			$output .= '</table>
		</details>';
		} else { 
		$output .= 0;
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Items in Bundle</th>
		<td>';
		if(isset($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle']) && count($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle'])>0) { 
		$output .= '<details>
		<summary>';
		$output .= count($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle']); 
		$output .= '</summary>
		
			<table>
			<thead><tr>
			<th>Item ID</th><th>Product ID</th><th>Transaction ID</th><th>Parent Product ID</th><th>Tier</th><th>Notes</th><th>SizeMB</th><th>DRM</th><th>OS</th><th>Activation Key</th><th>Date Added</th><th>Time Added</th><th>Sequence</th><th>Library</th><th>Added Time Stamp</th>
			</tr></thead>';
			foreach ($purchases[$purchaseIndex[$_GET['id']]]['itemsinBundle'] as $value) {
			$output .= '<tr>
			<td><a href="viewitem.php?id='. $items[$itemIndex[$value]]['ItemID'].'">'. $items[$itemIndex[$value]]['ItemID'].'</a></td>

			<td>';
			if (isset($items[$itemIndex[$value]]['ProductID']) && $items[$itemIndex[$value]]['ProductID']<>"") {
			$output .= "<a href=\"viewgame.php?id=" . $items[$itemIndex[$value]]['ProductID'] . "\">" . $games[$gameIndex[$items[$itemIndex[$value]]['ProductID']]]['Title'] . "</a>"; 
			}
			$output .= '</td>
			<td><a href="viewbundle.php?id='. $items[$itemIndex[$value]]['TransID'].'">'. nl2br($purchases[$purchaseIndex[$items[$itemIndex[$value]]['TransID']]]['Title']).'</a></td>';
			if($items[$itemIndex[$value]]['ParentProductID']<>$items[$itemIndex[$value]]['ProductID']) {
			$output .= '<td><a href="viewgame.php?id='. $items[$itemIndex[$value]]['ParentProductID'].'">'. $games[$gameIndex[$items[$itemIndex[$value]]['ParentProductID']]]['Title'].'</a></td>';
			} else {
			$output .= '<td></td>';
			}

			$output .= '<td>'. $items[$itemIndex[$value]]['Tier'].'</td>
			<td>'. nl2br($items[$itemIndex[$value]]['Notes']).'</td>
			<td>'. $items[$itemIndex[$value]]['SizeMB'].'</td>
			<td>'. $items[$itemIndex[$value]]['DRM'].'</td>
			<td>'. $items[$itemIndex[$value]]['OS'].'</td>
			<td>'. $items[$itemIndex[$value]]['ActivationKey'].'</td>
			<td>'. $items[$itemIndex[$value]]['DateAdded'].'</td>
			<td>'. $items[$itemIndex[$value]]['Time Added'].'</td>
			<td>'. $items[$itemIndex[$value]]['Sequence'].'</td>
			<td>'. $items[$itemIndex[$value]]['Library'].'</td>
			<td>'. $items[$itemIndex[$value]]['PrintAddedTimeStamp'].'</td>';
			//$output .= '<td class="Hidden">'; var_dump($value); $output .= '</td>';
			//$output .= '<td class="Hidden">'; var_dump($items[$itemIndex[$value]]); $output .= '</td>';
			$output .= '</tr>';
			}
			
			$output .= '</table>
		</details>';
		} else {
			$output .= 0;
		}
		$output .= '</td>
	</tr>
	<tr>
		<th>Products in Bundle</th>
		<td>';
		if(isset($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde']) && count($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde'])>0) {
		$output .= '<details>
		<summary>';
		$output .= count($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde']);
		$output .= '</summary>
			<table>
			<thead><tr>
			<th>Product</th><th>Parent Game</th><th>Series</th><th>Want</th><th>Playable</th><th>Type</th><th>Launch Date</th><th>Launch Price</th><th>MSRP</th><th>Current MSRP</th><th>Historic Low</th><th>Historic Low Date</th><th>Steam Achievements</th><th>Steam Cards</th><th>Time To Beat</th><th>Metascore</th><th>Metascore User</th><th>Steam Rating</th><th>Date Updated</th>
			<th class="hidden">Steam Store</th><th class="hidden">GOG</th><th class="hidden">isthereanydeal</th><th class="hidden">Developer</th><th class="hidden">Publisher</th>
			</tr></thead>';
			foreach ($purchases[$purchaseIndex[$_GET['id']]]['ProductsinBunde'] as $value) {
			$output .= '<tr>
			<td><a href="viewgame.php?id='. $games[$gameIndex[$value]]['Game_ID'].'">'. $games[$gameIndex[$value]]['Title'].'</a></td>';
			
			if($games[$gameIndex[$value]]['ParentGameID']<>$games[$gameIndex[$value]]['Game_ID']) {
			$output .= '<td><a href="viewgame.php?id='. $games[$gameIndex[$value]]['ParentGameID'].'">'. $games[$gameIndex[$games[$gameIndex[$value]]['ParentGameID']]]['Title'].'</a></td>';
			} else {
			$output .= '<td></td>';
			}
			
			$output .= '<td>'. $games[$gameIndex[$value]]['Series'].'</td>
			<td>'. $games[$gameIndex[$value]]['Want'].'</td>
			<td>'. booltext($games[$gameIndex[$value]]['Playable']).'</td>
			<td>'. $games[$gameIndex[$value]]['Type'].'</td>
			<td>'. $games[$gameIndex[$value]]['LaunchDate']->format("n/d/Y").'</td>
			<td>'. "$" . sprintf('%.2f', $games[$gameIndex[$value]]['LaunchPrice']).'</td>
			<td>'. "$" . sprintf('%.2f', $games[$gameIndex[$value]]['MSRP']).'</td>
			<td>'. "$" . sprintf('%.2f', $games[$gameIndex[$value]]['CurrentMSRP']).'</td>
			<td>'. "$" . sprintf('%.2f', $games[$gameIndex[$value]]['HistoricLow']).'</td>
			<td>'. $games[$gameIndex[$value]]['LowDate'].'</td>
			<td>'. $games[$gameIndex[$value]]['SteamAchievements'].'</td>
			<td>'. $games[$gameIndex[$value]]['SteamCards'].'</td>
			<td>'. $games[$gameIndex[$value]]['TimeToBeatLink2'].'</td>
			<td>'. $games[$gameIndex[$value]]['MetascoreLinkCritic'].'</td>
			<td>'. $games[$gameIndex[$value]]['MetascoreLinkUser'].'</td>
			<td>'. $games[$gameIndex[$value]]['SteamRating'].'</td>
			<td>'. $games[$gameIndex[$value]]['DateUpdated'].'</td>';
			$output .= '<td class="hidden">'. $games[$gameIndex[$value]]['SteamLinks'].'</td>';
			$output .= '<td class="hidden">'. $games[$gameIndex[$value]]['GOGLink'].'</td>';
			$output .= '<td class="hidden">'. $games[$gameIndex[$value]]['isthereanydealLink'].'</td>';
			$output .= '<td class="hidden">'. $games[$gameIndex[$value]]['Developer'].'</td>';
			$output .= '<td class="hidden">'. $games[$gameIndex[$value]]['Publisher'].'</td>';
			//$output .= '<td class="hidden">'; var_dump($value); $output .= '</td>';
			//$output .= '<td class="hidden">'; var_dump($games[$gameIndex[$value]]); $output .= '</td>';
			$output .= '</tr>';
			} 
			//TODO: Clean up hidden data.
			
			$output .= '</table>
		</details>';
		} else {
			$output .= 0;
		}
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

//<div style="background:yellow;color:black">WORK IN PROGRESS</div>
$conn -> close();
unset($conn);
unset($settings);
		return $output;
	}
}	