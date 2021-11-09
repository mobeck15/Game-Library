<?php
$GLOBALS['rootpath']="..";
require $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Test Functions";
echo Get_Header($title);

$conn=get_db_connection();
$settings=getsettings($conn);
$calculations=reIndexArray(getCalculations("",$conn),"Game_ID");
$purchases=reIndexArray(getPurchases("",$conn), "TransID");
//$items=getAllItems("",$conn);


?>
<b>Sale Price</b><br>
<details>
<summary>Scenario: Multiple Bundles:</summary>
<table>
<tr><td>Count Duplicates Setting: </td><td><?php echo $settings["CountDupes"]; ?></td>
<?php 
$useproductid=30; //Wizardry 7
echo "<td>Purchase Date: </td><td>". $calculations[$useproductid]["PurchaseDateTime"]->format("Y-m-d h:i:s") . "</td></tr>";
echo "<tr><td>ID: </td><td><a href='../viewgame.php?id=" . $calculations[$useproductid]["Game_ID"] . "'>" . $calculations[$useproductid]["Game_ID"] . "</a></td>";
echo "<td>Game: </td><td>" . $calculations[$useproductid]["Title"] . "</td></tr>";
echo "<tr><td>MSRP: </td><td>$" . sprintf("%.2f",$calculations[$useproductid]["MSRP"]) . " (" . $calculations[$useproductid]["MSRP"] . ")</td>";
echo "<td>Sale Price: </td><td>$" . sprintf("%.2f",$calculations[$useproductid]["SalePrice"]) . " (" . $calculations[$useproductid]["SalePrice"] . ")</td>";
echo "<td>Alt Sale Price: </td><td>$" . sprintf("%.2f",$calculations[$useproductid]["AltSalePrice"]) . " (" . $calculations[$useproductid]["AltSalePrice"] . ")</td></tr>";
echo "<tr><td>Top Bundles: </td><td colspan='5'>";
echo "<table><tr><th>ID</th><th>Title</th><th>Purchase Date</th><th>Paid</th>";
//echo "<th>total MSRP</th>";
echo "<th>Games</th>";
//echo "<th>Items</th>";
echo "</tr>";
foreach ($calculations[$useproductid]["TopBundleIDs"] as $bundle) {
	echo "<tr><td><a href='../viewbundle.php?id=" . $bundle . "'>" . $bundle . "</a></td>";
	echo "<td>" . $purchases[$bundle]["Title"] . "</td>";
	echo "<td>" . $purchases[$bundle]["PurchaseDate"] . " " . $purchases[$bundle]["PurchaseTime"] . "</td>";
	echo "<td>Paid: " . $purchases[$bundle]["Paid"] . "<br>Total MSRP: " . $purchases[$bundle]["TotalMSRP"] . "<br>Totl MSRP Formula: " . $purchases[$bundle]["TotalMSRPFormula"] . "</td>";
	//echo "<td>";
		$totalMSRP=
		$totalSale=
		$totalAlt=
		$totalWant=
		$totalSeconds=0;
		foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
			if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
				$totalMSRP+=$calculations[$gamein["GameID"]]["MSRP"];
				$totalSale+=$calculations[$gamein["GameID"]]["SalePrice"];
				$totalAlt+=$calculations[$gamein["GameID"]]["AltSalePrice"];
				$totalWant+=$calculations[$gamein["GameID"]]["Want"];
				$totalSeconds+=$calculations[$gamein["GameID"]]["GrandTotal"];
			}
		}
		//echo $totalMSRP;
	//echo "</td>";
	echo "<td>";
		echo "<table><tr><th>ID</th><th>Title</th><th>Play Time</th><th>Want</th><th>Status</th><th>MSRP</th><th>Sale Price</th><th>Alt Sale</th></tr>";
		$totalExpectedSale=0;
		foreach ($purchases[$bundle]["GamesinBundle"] as $gamein){
			echo "<tr><td><a href='../viewgame.php?id=".$gamein["GameID"]."'>".$gamein["GameID"]."</a></td>";
			echo "<td>".$calculations[$gamein["GameID"]]["Title"]."</td>";
			echo "<td>".timeduration($calculations[$gamein["GameID"]]["GrandTotal"],"seconds")."</td>";
			echo "<td>".$calculations[$gamein["GameID"]]["Want"]."</td>";
			echo "<td>".$calculations[$gamein["GameID"]]["Status"]." (" . $settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] . ")</td>";
			echo "<td>".$calculations[$gamein["GameID"]]["MSRP"]."</td>";
			$expectedSale=($calculations[$gamein["GameID"]]["MSRP"]/$totalMSRP)*$purchases[$bundle]["Paid"];
			if($settings["status"][$calculations[$gamein["GameID"]]["Status"]]["Count"] == 1) {
				$totalExpectedSale += $expectedSale;
			}
			echo "<td>Formula:&nbsp;(MSRP/TotalMSRP)*BundlePrice<br>";
			echo "Expected: (" . $calculations[$gamein["GameID"]]["MSRP"] . " / " . $totalMSRP . ") * " . $purchases[$bundle]["Paid"] . " = " . round($expectedSale,4)."<br>";
			echo "Acual Formula: " . $calculations[$gamein["GameID"]]["SalePriceFormula"]."<br>";
			echo "Actual: " . round($calculations[$gamein["GameID"]]["SalePrice"],4);
			if ($expectedSale == $calculations[$gamein["GameID"]]["SalePrice"]) {
				 echo " PASS";
			} else {
				echo " FAIL";
			}
			echo "</td>";
			echo "<td>".$calculations[$gamein["GameID"]]["AltSalePrice"]."</td>";
			echo "</tr>";
		}
		echo "<tr><td></td><td>Total</td><td>" . timeduration($totalSeconds,"seconds") . "</td><td>$totalWant</td><td></td><td>$totalMSRP</td><td>$totalExpectedSale = $totalSale</td><td>$totalAlt</td></tr>";
		echo "</table>";
	echo "</td>";
	//echo "<td>";
	//echo "<table>";
	//echo "<tr><th>ID</th><th>Notes</th></tr>";
	
	//echo "</table>";
	//echo "</td>";
	
	echo "</tr>";
}
echo "</table>";
echo "</td></tr>";
echo "</table>";

?>
</details>
<?php
//var_dump($settings);
//var_dump($purchases[$bundle]);
//var_dump($calculations[$useproductid]);

echo Get_Footer(); ?>