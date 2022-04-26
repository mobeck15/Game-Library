<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";

class datacheckPage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="Database Checks";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();

$sql1c = "select * from `gl_history` order by `HistoryID` Asc";
$History=findgaps($sql1c,$conn,"HistoryID");

$sql2c = "select * from `gl_transactions` order by `TransID` Asc";
$Transaction=findgaps($sql2c,$conn,"TransID");

$sql3c = "select * from `gl_items` order by `ItemID` Asc";
$Item=findgaps($sql3c,$conn,"ItemID");

$sql4c = "select * from `gl_products` order by `Game_ID` Asc";
$Product=findgaps($sql4c,$conn,"Game_ID");

$output .= "<style>
#grid {
	display: grid;
  grid-gap: 10px;
  grid-template: repeat(1, 1fr) / repeat(2, 1fr);
  grid-auto-flow: row;  /* or 'row','column', 'row dense', 'column dense' */
}
</style>";

	$output .= '<div id="grid">
	<div>';
$sql="select `Store`, sum(`Paid`) as Credit
FROM (SELECT * FROM `gl_transactions` 
where `Paid` < 0 ) as base 
GROUP BY `Store`";

$sql="select `Store`, sum(`Credit Used`) as Used
FROM (SELECT * FROM `gl_transactions` 
where `Credit Used` <> 0 ) as base 
GROUP BY `Store`";

$sql="SELECT * FROM `gl_transactions` 
where `Credit Used` <> 0";

if($result = $conn->query($sql)) {
		while($row = $result->fetch_assoc()) {
			$row['sortdate']=strtotime(combinedate($row['PurchaseDate'],$row['PurchaseTime'],$row['Sequence']));
			$rowdata[]=$row;
		}
	}
	
	foreach ($rowdata as $key => $row) {
		$Sortby1[$key]  = $row['sortdate'];
	}
	array_multisort($Sortby1, SORT_ASC, $rowdata);

	//unset($rowdata);
	//unset($key);
	//unset($row);
	
	/* */
	foreach ($rowdata as $key => &$row) {
		$row['Credit Used']=$row['Credit Used']+0;
		if(!isset($totalCredit[$row['Store']])){
			$totalCredit[$row['Store']]=array('Earned'=>0,'Spent'=>0,'Total'=>0);
		}
		if($row['Credit Used']<0){
			$totalCredit[$row['Store']]['Earned']+=$row['Credit Used'];
		} else {
			$totalCredit[$row['Store']]['Spent']+=$row['Credit Used'];
		}
		$totalCredit[$row['Store']]['Total'] += $row['Credit Used'];
		$row['runningTotal']=$totalCredit[$row['Store']]['Total'];
		$row['runningEarned']=$totalCredit[$row['Store']]['Earned'];
		$row['runningSpent']=$totalCredit[$row['Store']]['Spent'];
		//$row['debug']=$totalCredit['GMG']['Total'];
	}
	unset($row);

	$output .= "<input id='deatilbutton' title='Click to show/hide content' type='button' value='Show Details'
	onclick=\"if(document.getElementById('detailSpoiler').style.display=='none') {
		document.getElementById('detailSpoiler').style.display='';
		document.getElementById('deatilbutton').value='Hide Details';
	} else {
		document.getElementById('detailSpoiler').style.display='none'
		document.getElementById('deatilbutton').value='Show Details';
	}\">
	
	<div id='detailSpoiler' style='display:none'>
	<table>
	<thead>
	<tr><th>TransID</th>
	<th>Transaction</th>
	<th>Store</th>
	<th>Credit Used</th>
	<th>Sort Date</th>
	<th class='hidden'>Purchase Date</th>
	<th class='hidden'>Purchase Time</th>
	<th class='hidden'>Sequence</th>
	<th>Earned</th>
	<th>Spent</th>
	<th>Total Credit</th>
	<th class='hidden'>GMG Credit</th>
	</tr>
	</thead>
	<tbody>";
	foreach ($rowdata as $row) { 
	//TODO: Add a column to count items in transaction (should be 1 for cards)
	//TODO: Add a column to list what product the item is linked to.
		$output .= '<tr>
		<td class="numeric"><a href="http://games.stuffiknowabout.com/gl6/viewbundle.php?id='. $row['TransID'].'">'. $row['TransID'].'</a></td>
		<td class="text">'. nl2br($row['Title']).'</td>
		<td class="text">'.$row['Store'].'</td>
		<td class="numeric">$'.sprintf("%.2f",round($row['Credit Used'],2)).'</td>
		<td class="numeric">'. date("n/j/Y H:i:s",$row['sortdate']).'</td>
		<td class="hidden numeric">'. $row['PurchaseDate'].'</td>
		<td class="hidden numeric">'. $row['PurchaseTime'].'</td>
		<td class="hidden numeric">'. $row['Sequence'].'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['runningEarned'],2)).'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['runningSpent'],2)).'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['runningTotal'],2)).'</td>';
		//$output .= '<td class="hidden numeric">$'. $row['debug'].'</td>';
		$output .= '</tr>';
	}
	$output .= '</tbody>
	</table>
	</div>
	<table>
	<thead>
	<tr><th>Credit Type</th>
	<th>Earned</th>
	<th>Spent</th>
	<th>Total</th>
	</thead>
	<tbody>';
	foreach ($totalCredit as $key => $row) {
		$output .= '<tr>
		<td class="text">'.$key.'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['Earned'],2)).'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['Spent'],2)).'</td>
		<td class="numeric">$'. sprintf("%.2f",round($row['Total'],2)).'</td>
		</tr>';
	}
	$output .= '</tbody>
	</table>
	</div>
	
	<div>
	<table>
	<thead>
	<tr><th>Field</th><th>Max</th><th>Count</th><th>Gaps</th></tr>
	</thead>
	<tr><td>History</td><td>'. $History['max'].'</td>
	<td>'. $History['count'].'</td>
	<td>Missing: '. count($History['gaps']).' <br>'. $History['gapsText'].'</td></tr>
	
	<tr><td>Transaction</td><td>'. $Transaction['max'].'</td>
	<td>'. $Transaction['count'].'</td>
	<td>Missing: '. count($Transaction['gaps']).' <br>'. $Transaction['gapsText'].'</td></tr>

	<tr><td>Items</td><td>'. $Item['max'].'</td>
	<td>'. $Item['count'].'</td>
	<td>Missing: '. count($Item['gaps']).' <br>'. $Item['gapsText'].'</td></tr>

	<tr><td>Games</td><td>'. $Product['max'].'</td>
	<td>'. $Product['count'].'</td>
	<td>Missing: '. count($Product['gaps']).' <br>'. $Product['gapsText'].'</td></tr>
	</table>
	</div>
	
	</div>

	<p>
	Last Card: 
	'. $Transaction['lastcard']['Title'].'
	<br>Date: ';
	$output .= combinedate($Transaction['lastcard']['PurchaseDate'],$Transaction['lastcard']['PurchaseTime'],$Transaction['lastcard']['Sequence']);
		
		return $output;
	}
}	