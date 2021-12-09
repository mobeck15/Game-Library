<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Database Checks";
echo Get_Header($title);

$conn=get_db_connection();

$sql1c = "select * from `gl_history` order by `HistoryID` Asc";
$History=findgaps($sql1c,$conn,"HistoryID");

$sql2c = "select * from `gl_transactions` order by `TransID` Asc";
$Transaction=findgaps($sql2c,$conn,"TransID");

$sql3c = "select * from `gl_items` order by `ItemID` Asc";
$Item=findgaps($sql3c,$conn,"ItemID");

$sql4c = "select * from `gl_products` order by `Game_ID` Asc";
$Product=findgaps($sql4c,$conn,"Game_ID");
?>
<style>
#grid {
	display: grid;
  grid-gap: 10px;
  grid-template: repeat(1, 1fr) / repeat(2, 1fr);
  grid-auto-flow: row;  /* or 'row','column', 'row dense', 'column dense' */
}
</style>

	<div id="grid">
	<div>
	<?php 
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
	?>
	<input id='deatilbutton' title='Click to show/hide content' type='button' value='Show Details'
	onclick="if(document.getElementById('detailSpoiler').style.display=='none') {
		document.getElementById('detailSpoiler').style.display='';
		document.getElementById('deatilbutton').value='Hide Details';
	} else {
		document.getElementById('detailSpoiler').style.display='none'
		document.getElementById('deatilbutton').value='Show Details';
	}">
	
	<div id='detailSpoiler' style='display:none'>
	<table>
	<thead>
	<tr><th>TransID</th>
	<th>Transaction</th>
	<th>Store</th>
	<th>Credit Used</th>
	<th>Sort Date</th>
	<th class="hidden">Purchase Date</th>
	<th class="hidden">Purchase Time</th>
	<th class="hidden">Sequence</th>
	<th>Earned</th>
	<th>Spent</th>
	<th>Total Credit</th>
	<th class="hidden">GMG Credit</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($rowdata as $row) { 
	//TODO: Add a column to count items in transaction (should be 1 for cards)
	//TODO: Add a column to list what product the item is linked to.
	?>
		<tr>
		<td class="numeric"><a href="http://games.stuffiknowabout.com/gl6/viewbundle.php?id=<?php echo $row['TransID']; ?>"><?php echo $row['TransID']; ?></a></td>
		<td class="text"><?php echo nl2br($row['Title']); ?></td>
		<td class="text"><?php echo $row['Store']; ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['Credit Used'],2)); ?></td>
		<td class="numeric"><?php echo date("n/j/Y H:i:s",$row['sortdate']); ?></td>
		<td class="hidden numeric"><?php echo $row['PurchaseDate']; ?></td>
		<td class="hidden numeric"><?php echo $row['PurchaseTime']; ?></td>
		<td class="hidden numeric"><?php echo $row['Sequence']; ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['runningEarned'],2)); ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['runningSpent'],2)); ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['runningTotal'],2)); ?></td>
		<td class="hidden numeric">$<?php echo $row['debug']; ?></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
	</div>
	<table>
	<thead>
	<tr><th>Credit Type</th>
	<th>Earned</th>
	<th>Spent</th>
	<th>Total</th>
	</thead>
	<tbody>
	<?php foreach ($totalCredit as $key => $row) { ?>
		<tr>
		<td class="text"><?php echo $key; ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['Earned'],2)); ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['Spent'],2)); ?></td>
		<td class="numeric">$<?php echo sprintf("%.2f",round($row['Total'],2)); ?></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
	</div>
	
	<div>
	<table>
	<thead>
	<tr><th>Field</th><th>Max</th><th>Count</th><th>Gaps</th></tr>
	</thead>
	<tr><td>History</td><td><?php echo $History['max']; ?></td>
	<td><?php echo $History['count']; ?></td>
	<td>Missing: <?php echo count($History['gaps']); ?> <br><?php echo $History['gapsText']; ?></td></tr>
	
	<tr><td>Transaction</td><td><?php echo $Transaction['max']; ?></td>
	<td><?php echo $Transaction['count']; ?></td>
	<td>Missing: <?php echo count($Transaction['gaps']); ?> <br><?php echo $Transaction['gapsText']; ?></td></tr>

	<tr><td>Items</td><td><?php echo $Item['max']; ?></td>
	<td><?php echo $Item['count']; ?></td>
	<td>Missing: <?php echo count($Item['gaps']); ?> <br><?php echo $Item['gapsText']; ?></td></tr>

	<tr><td>Games</td><td><?php echo $Product['max']; ?></td>
	<td><?php echo $Product['count']; ?></td>
	<td>Missing: <?php echo count($Product['gaps']); ?> <br><?php echo $Product['gapsText']; ?></td></tr>
	</table>
	</div>
	
	</div>

	<p>
	Last Card: 
	<?php echo $Transaction['lastcard']['Title']; ?>
	<br>Date: 
	<?php echo combinedate($Transaction['lastcard']['PurchaseDate'],$Transaction['lastcard']['PurchaseTime'],$Transaction['lastcard']['Sequence']); ?>
	
<?php echo Get_Footer();

function findgaps($sql,$conn,$idname) {
	//TODO: Reports values that are not gaps.
	$stats=array();
	if($result = $conn->query($sql)){
		$stats['max']=0;
		$stats['count']=0;
		$stats['gaps']=array();
		$stats['gapsText']="";
		$index=0;
		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$stats['count']++;
				$stats['max']=$row[$idname];
				if($row[$idname]<>$index){
					while($row[$idname]<>$index){
						$stats['gaps'][]=$index;
						$stats['gapsText'] .= $index . ", ";
						$index++;
					}
					$stats['gaps'][]=$index;
					$stats['gapsText'] .= $index . ", ";
				}
				$index++;
				$stats['lastrow']=$row;
				if($idname=="TransID" AND (0+$row['Credit Used'])<0){
					$stats['lastcard']=$row;
				}
				if($idname=="ItemID" AND $row['ProductID']==null){
					$stats['lastcard']=$row;
				}
			}
		}
	}
	return $stats;
}
 ?>
