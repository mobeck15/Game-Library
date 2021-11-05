<?php
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/php.ini.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/gl6/inc/functions.inc.php";

$title="Waste";
echo Get_Header($title);

include "inc/functions.inc.php";

$conn=get_db_connection();
	$settings=getsettings($conn);
	$calculations=getCalculations("",$conn);
	$topList=getTopList('Bundle',$conn,$calculations);
	$items=getAllItems("",$conn);
	$conn->close();	
	
	$calculations=reIndexArray($calculations,"Game_ID");
	
	//var_dump($topList);
	$BundleCount=
	$GameCount=
	$PriceDiff=
	$NeverHours=
	$FreeHours=
	$DupeCount=
	$upBundleCount=
	$upGames=
	$upSpent=0;
	
	foreach($topList as $key => $toprow){
		if($toprow['TotalHistoricPlayed']<$toprow['ModPaid']){
			$BundleCount++;
			$GameCount+=$toprow['UnplayedCount'];
			$PriceDiff+=$toprow['ModPaid']-$toprow['TotalHistoricPlayed'];
			$OverPaidList[]['BundleKey']=$key;
			//var_dump($toprow);
			//break;
		}
		
		if($toprow['GameCount']<=$toprow['UnplayedCount']){
			$UnPlayedList[]['BundleKey']=$key;
			if($toprow['UnplayedCount']>0){
				$upBundleCount++;
				$upSpent+=$toprow['ModPaid'];
				$upGames+=$toprow['UnplayedCount'];	
			}
		}
		//var_dump($toprow);
		//break;
	}

	foreach($calculations as $calcrow){
		if($calcrow['Status']=="Never"){
			$NeverHours+=$calcrow['GrandTotal'];
		}
		
		if($calcrow['Paid']==0){
			$FreeHours+=$calcrow['GrandTotal'];
		}
	}
	
	foreach($items as $itemrow){
		if($itemrow['Library']=="Inactive"){
			$DupeCount++;
			//var_dump($itemrow);
			//break;
		}
	}
	
	//$top['Title']
	//$top['key']
	
	?>
	<table>
	<thead>
	<tr><th colspan=2>Unplayed Bundles</th></tr>
	</thead>
	<tbody>
	<tr>
	<th>Bundles</th><td><?php echo $upBundleCount; ?></td>
	</tr>
	<tr>
	<th>Games</th><td><?php echo $upGames; ?></td>
	</tr>
	<tr>
	<th>Spent</th><td>$<?php echo sprintf("%.2f",$upSpent); ?></td>
	</tr>
	<tr>

	<table>
	<thead>
	<tr>
	<th>Biggest Unplayed</th><th>Paid</th><th>Unplayed</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	foreach($UnPlayedList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']){
			?>
			<tr>
			<td><?php echo $topList[$key['BundleKey']]['Title']; ?></td>
			<td>$<?php echo sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']); ?></td>
			<td><?php echo $topList[$key['BundleKey']]['UnplayedCount']; ?></td>
			</tr><?php
		}
	} ?>
	</tbody>
	</table>

	<table>
	<thead>
	<tr>
	<th>Oldest Unplayed</th><th>Purchased</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach($UnPlayedList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']){
			?>
			<tr>
			<td><?php echo $topList[$key['BundleKey']]['Title']; ?></td>
			<td><?php echo combinedate($topList[$key['BundleKey']]['PurchaseDate'],$topList[$key['BundleKey']]['PurchaseTime'],$topList[$key['BundleKey']]['PurchaseSequence']); ?></td>
			<td><?php echo $topList[$key['BundleKey']]['UnplayedCount']; ?></td>
			</tr>
			<?php
		}
	} ?>
	</tbody>
	</table>
	
	<table>
	<thead>
	<?php 
	//TODO: add detail list of overpaid bundles
	//TODO: add detail list of overpaid games in bundles
	?>
	<tr><th colspan=2>Overpaid Bundles</th></tr>
	</thead>
	<tbody>
	<tr>
	<th>Bundles</th><td><?php echo $BundleCount; ?></td>
	</tr>
	<tr>
	<th>Unplayed games in Bundles</th><td><?php echo $GameCount; ?></td>
	</tr>
	<tr>
	<th>Price Diff</th><td>$<?php echo sprintf("%.2f",$PriceDiff); ?></td>
	</tr>
	<tr>
	<th>Hours on NEVER</th><td><?php echo timeduration($NeverHours,"seconds"); ?></td>
	</tr>
	<tr>
	<th>Hours on Free</th><td><?php echo timeduration($FreeHours,"seconds"); ?></td>
	</tr>
	<tr>
	<th>Untraded Dupes</th><td><?php echo $DupeCount; ?></td>
	</tr>
	</tbody>
	</table>
	
	<table>
	<thead>
	<tr>
	<th>Biggest Overpaid</th><th>Cost Diff</th>
	<th>Mod Paid</th>
	<th>Total Historic Price of Played</th>
	<th>Unplayed Games</th>
	</tr>
	</thead>
	<tbody>
	<?php
	
	foreach ($OverPaidList as $key => $row) {
		$topList[$row['BundleKey']]['diff']= 
		$Sortby1[$key]  = ($topList[$row['BundleKey']]['ModPaid']-$topList[$row['BundleKey']]['TotalHistoricPlayed']);
	}
	array_multisort($Sortby1, SORT_DESC, $OverPaidList);
	
	foreach($OverPaidList as $key){
		if($topList[$key['BundleKey']]['UnplayedCount']>0){
			foreach($topList[$key['BundleKey']]['RawData']['GamesinBundle'] as $BundleGame){
				if($calculations[$BundleGame['GameID']]['GrandTotal']==0){
					$GamesfromOverpaid[$BundleGame['GameID']]['GameID']=$BundleGame['GameID'];
					$GamesfromOverpaid[$BundleGame['GameID']]['LowPrice']=$BundleGame['HistoricLow'];
				}
			}
			
			//var_dump($key);
			?>
			<tr>
			<td><?php echo $topList[$key['BundleKey']]['Title']; ?></td>
			<td>$<?php echo sprintf("%.2f",$topList[$key['BundleKey']]['diff']); ?></td>
			<td>$<?php echo sprintf("%.2f",$topList[$key['BundleKey']]['ModPaid']); ?></td>
			<td>$<?php echo sprintf("%.2f",$topList[$key['BundleKey']]['TotalHistoricPlayed']); ?></td>
			<td><?php echo $topList[$key['BundleKey']]['UnplayedCount']; ?></td>
			</tr> <?php
		}
	} ?>
	</tbody>
	</table>

	<table>
	<thead>
	<tr>
	<th>Games from Overpaid Bundles</th><th>Low price</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	
	//TODO: add detail to overpaid games table including play link and item type.

	unset ($Sortby1);
	foreach ($GamesfromOverpaid as $key => $row) {
		$Sortby1[$key]  = ($calculations[$row['GameID']]['HistoricLow']);
	}
	array_multisort($Sortby1, SORT_DESC, $GamesfromOverpaid);

	foreach($GamesfromOverpaid as $row){
		?>
			<tr>
			<td><a href='viewgame.php?id=<?php echo $row['GameID']; ?>' target='_blank'><?php echo $calculations[$row['GameID']]['Title']; ?></a></td>
			<td><?php echo $calculations[$row['GameID']]['HistoricLow']; ?></td>
			</tr> <?php
	}	?>
	</tbody>
	</table>

<?php echo Get_Footer(); ?>