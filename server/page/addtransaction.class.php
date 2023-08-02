<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";


class addtransactionPage extends Page
{
	public function __construct() {
		$this->title="Add Product";
	}
	
	public function buildHtmlBody(){
		$output = "";
		
	if(isset($_POST['TransID'])){
		$this->getDataAccessObject()->insertTransaction($_POST);
	}

  $output .= '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
			</thead>';

	$nextTrans_ID=$this->getDataAccessObject()->getMaxTableID("transactions");

$blank="";

/* 
 * TODO: At some point update this to make use of INFORMATION_SCHEMA.COLUMNS to retrieve
 * a description for each field. At this time I can't figure out how to read that table
 * select * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'transactions'
 * select `COLUMN_NAME`, `COLUMN_COMMENT` FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'transactions'
 * ALTER TABLE `gl_transactions` CHANGE `Game_ID` `Game_ID` INT(11) NOT NULL COMMENT 'Game ID';
 */

			$output .= '<tr>
				<th>Transaction ID *</th>
				<td><input type="number" name="TransID" min="0" id="TransactionID" onchange=\'$("#BundleID").val(this.value);\' value="'.$nextTrans_ID.'"></td>
				<td>The purchase transaction which included this item (Bundle)</td>
				<td></td>
			</tr>

			<tr>
				<th>Title *</th>
				<td><input type="text" name="Title" value="'.$blank.'"></td>
				<td>The name of the purchased bundle or package</td>
				<td></td>
			</tr>

			<tr>
				<th>Store (?)</th>
				<td><input type="text" name="Store" id="Store" value="'.$blank.'"></td>
				<td>The store purchased from</td>
				<td></td>
			</tr>
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
			
			<tr>
				<th>Bundle ID *</th>
				<td><input type="number" name="BundleID" min="0"  id="BundleID" value="'.$nextTrans_ID.'"></td>
				<td>The parent transaction if this is part of another bundle.</td>
				<td>(?)<input id="BundleTitle" size=30></td>
			</tr>
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
			</script>

			<tr>
				<th>Tier</th>
				<td><input type="number" name="Tier" min="1" value="1"></td>
				<td>The bundle Tier this item was purchased in.</td>
				<td></td>
			</tr>

			<tr>
				<th>Purchase Date *</th>';
//$useDate="2019-10-31";
$useDate=date("Y-m-d");
				$output .= '<td><input type="date" name="PurchaseDate" value="'.$useDate.'"></td>
				<td>Date this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Purchase Time *</th>';
$useTime="10:00:00";
				$output .= '<td><input type="time" name="PurchaseTime" min="0" value="'.$useTime.'"></td>
				<td>Time this item was acquired</td>
				<td></td>
			</tr>

			<tr>
				<th>Sequence</th>
				<td><input type="number" name="Sequence" min="0" value="1"></td>
				<td>Sequence for this item in the bundle</td>
				<td></td>
			</tr>
			
			<tr><th>Price *</th>
				<td><input type="number" name="Price" id="Price" onchange=\'$("#Paid").val(Number(this.value)+Number(document.getElementById("Fees").value));\' step="0.01" value="'.$blank.'"></td>
				<td>The price paid (Before tax or fees)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>
			
			<tr><th>Fees *</th>
				<td><input type="number" name="Fees" id="Fees" onchange=\'$("#Paid").val(Number(this.value)+Number(document.getElementById("Price").value));\' step="0.01" value="'.$blank.'"></td>
				<td>The fees paid (such as tax)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>
			
			<tr><th>Paid *</th>
				<td><input type="number" name="Paid" id="Paid" step="0.01" value="'.$blank.'"></td>
				<td>Total paid (Price + Fees)<br>Negative value is allowed for sold items.</td>
			<td></td></tr>

			<tr><th>Credit Used *</th>
				<td><input type="number" name="CreditUsed" step="0.01" value="'.$blank.'"></td>
				<td>Any store credit used in this transaction. <br>Negative value represents a credit gain.</td>
			<td></td></tr>
			
			<tr>
				<th>Bundle Link</th>
				<td><input type="text" name="BundleLink" value="'.$blank.'"></td>
				<td>The URL to the bundle page</td>
				<td></td>
			</tr>
			

			<tr><th colspan=4><input type="submit" value="Save"></th></tr>
			<tr><th colspan=4>* = Required Field<br>(?) = Lookup Prompt available</th></tr>
		</table>
		</form>';
		
		return $output;
	}
}
