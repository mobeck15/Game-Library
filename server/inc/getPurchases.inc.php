<?php
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";

function getPurchases($transID="",$connection=false,$items=false,$games=false){
	if($connection==false){
		require $GLOBALS['rootpath']."/inc/auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	if($games==false){
		$games=getGames("",$conn);
	}
	if ($items==false){
		$items=getAllItems("",$conn);
	}

	$gameIndex=makeIndex($games,"Game_ID");
	$activity=getActivityCalculations("","",$conn);
	$settings=getsettings($conn);
	//TODO: sometimes Totalweight is zero? Investigate.
	$totalWeight=$settings['WeightMSRP']+$settings['WeightPlay']+$settings['WeightWant'];
	if($totalWeight==0) {
		// @codeCoverageIgnoreStart
		$useWeightMSRP=0;
		$useWeightPlay=0;
		$useWeightWant=0;
		// @codeCoverageIgnoreEnd
	} else {
		$useWeightMSRP=$settings['WeightMSRP']/$totalWeight;
		$useWeightPlay=$settings['WeightPlay']/$totalWeight;
		$useWeightWant=$settings['WeightWant']/$totalWeight;
	}
	
	$sql="SELECT * FROM `gl_transactions`";
	/*
	//Filtering by one transaction ID breaks later calculations.
	if ($transID <> "" ) {
		$sql .= " where TransID = " . $transID ;
		$sql .= " OR BundleID = " . $transID ;
	}
	*/
	$sql .= " order by `PurchaseDate` ASC, `PurchaseTime` ASC, `Sequence` ASC" ;
	//$sql .= " Limit 50" ;
	if($result = $conn->query($sql)){
		while($row = $result->fetch_assoc()) {
			if($row['Tier']==0){$row['Tier']="";}
			
			$date=strtotime($row['PurchaseDate']);
			if(strtotime($row['PurchaseDate']) == 0) {
				$row['PurchaseDate'] = "";
			} else {
				$row['PurchaseDate'] = date("n/j/Y",$date);
			}
			
			$time = strtotime($row['PurchaseTime']);
			if(date("H:i:s",$time) == "00:00:00") {
				$row['PurchaseTime']= "";
			} else {
				$row['PurchaseTime']= date("H:i:s",$time) ;
			}
			
			$row['PrintPurchaseTimeStamp']=combinedate($row['PurchaseDate'], $row['PurchaseTime'], $row['Sequence']);
			
			$row['PurchaseDateTime'] = new DateTime($row['PurchaseDate'] . " " . $row['PurchaseTime']);
			
			if($row['Sequence']==0){$row['Sequence']="";}
			
			$row['Price'] = sprintf("%.2f",$row['Price']);
			if($row['Fees']<>0) {
				$row['Fees'] = sprintf("%.2f",$row['Fees']);
			} else {
				$row['Fees']="";
			}
			$row['Paid'] = sprintf("%.2f",$row['Paid']);
			if($row['Credit Used']<>0) {
				$row['Credit Used'] = sprintf("%.2f",$row['Credit Used']);
			} else {
				$row['Credit Used']="";
			}
			
			$row['BundleURL']=$row['Bundle Link'];
			if($row['Bundle Link']<>""){
				if(substr($row['Bundle Link'],0,4)<>"http"){
					$row['Bundle Link']="http://". $row['Bundle Link'];
				}
				$row['BundleURL']=$row['Bundle Link'];
				$row['Bundle Link']="<a href=\"".$row['Bundle Link']."\">Bundle</a>";
			}
			
			$row['GamesinBundle']=array();
			$row['TotalMSRP']=0;
			$row['TotalWant']=0;
			$row['TotalHrs']=0;
			
			$row['TotalMSRPFormula']="0";
			
			$purchases[]=$row;
		}
	} else {
		// @codeCoverageIgnoreStart
		$purchases = false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
		// @codeCoverageIgnoreEnd
	}
	
	if($connection==false){
		$conn->close();	
	}	

	$ParentBundleIndex=makeIndex($purchases,"TransID");
	
	$max_loop=5;
	foreach ($items as $key => $value) {
		//DONE: change Paid calculation to use only the first bundle ****PAID****
		$count=true;
		if($settings["CountDupes"]=="First" && $value["FirstItem"]==false){
			$count=false;
		}
		
		if($count) {
			$bundleID=$value['TransID'];
			$n=0;
			$parentbundle=$ParentBundleIndex[$bundleID];
			
			if(!isset($ParentBundleIndex[$bundleID])){
		// @codeCoverageIgnoreStart
				trigger_error("No parent bundle found");
				var_dump($value);
		// @codeCoverageIgnoreEnd
			}
			
			while($purchases[$parentbundle]['BundleID']<>$bundleID){
				$bundleID=$purchases[$ParentBundleIndex[$bundleID]]['BundleID'];
				if($n>=$max_loop) {
		// @codeCoverageIgnoreStart
					trigger_error("Exceeded maximum parent bundles (" . $n . ")");
					break;
		// @codeCoverageIgnoreEnd
				}
				$n++;
			}		
			$itemsbyBundle[$bundleID][]=$value;
		}
	}
	
	foreach ($purchases as &$row) {
		$n=0;
		$row['TopBundleID']=$row['BundleID'];
		$row['Bundle']=$purchases[$ParentBundleIndex[$row['BundleID']]]['Title'];
		while($purchases[$ParentBundleIndex[$row['TopBundleID']]]['BundleID']<>$row['TopBundleID']){
			$row['TopBundleID']=$purchases[$ParentBundleIndex[$row['TopBundleID']]]['BundleID'];
			if($n>=$max_loop) {
		// @codeCoverageIgnoreStart
				trigger_error("Exceeded maximum parent bundles (" . $n . ")");
				break;
		// @codeCoverageIgnoreEnd
			}
			$n++;
		}
		if(isset($purchases[$ParentBundleIndex[$row['TopBundleID']]]['Title'])){
			$row['TopBundleID']  = $row['TopBundleID'];
			$row['TopBundle'] = $purchases[$ParentBundleIndex[$row['TopBundleID']]]['Title'];
		} else {
		// @codeCoverageIgnoreStart
			$row['TopBundle']="Not Found";
			$row['TopBundleID']=null;
		// @codeCoverageIgnoreEnd
		}
		
		$row['BundlePrice'] = sprintf("%.2f",$purchases[$ParentBundleIndex[$row['TopBundleID']]]['Paid']);

		if (isset($itemsbyBundle[$row['TransID']])){

			$gamesList=array();
			foreach($itemsbyBundle[$row['TransID']] as $item){
				$row['itemsinBundle'][$item['ItemID']]=$item['ItemID'];
				if(!in_array($item['ProductID'],$gamesList) && $item['ProductID']<>null){
					$gamesList[]=$item['ProductID'];
					
					$row['ProductsinBunde'][$item['ProductID']]=$item['ProductID'];
				
					/*
					if($row['TransID']==57 AND $item['ProductID']==0){
						echo "<b>Item</b>";
						echo "<pre>".print_r($item,true)."</pre>";
					}
					
VVVVVV:
57  ProductID:381 :: MSRP(4.99)/TotalMSRP(29.94)*Paid(5.00)=0.83333333333333
300 ProductID:381 :: MSRP(4.99)/TotalMSRP(79.9)*Paid(1.00)=0.062453066332916
773 ProductID:381 :: MSRP(4.99)/TotalMSRP(331.68)*Paid(4.74)=0.071311505065123
SELECT `Game_ID`,`Title`,`MSRP` FROM `gl_products` join `gl_items` on `gl_products`.`Game_ID`=`gl_items`.`ProductID` where `gl_items`.`TransID`=300
					*/
					
					//var_dump($gameIndex[$item['ProductID']]);
					if(isset($gameIndex[$item['ProductID']])){
						$row['GamesinBundle'][$item['ProductID']]['GameID']=$item['ProductID'];
						$row['GamesinBundle'][$item['ProductID']]['Type']=$games[$gameIndex[$item['ProductID']]]['Type'];
						$row['GamesinBundle'][$item['ProductID']]['Playable']=$games[$gameIndex[$item['ProductID']]]['Playable'];
						$row['GamesinBundle'][$item['ProductID']]['MSRP']=$games[$gameIndex[$item['ProductID']]]['MSRP'];
						$row['GamesinBundle'][$item['ProductID']]['Want']=$games[$gameIndex[$item['ProductID']]]['Want'];
						$row['GamesinBundle'][$item['ProductID']]['HistoricLow']=$games[$gameIndex[$item['ProductID']]]['HistoricLow'];
						
						if(isset($activity[$item['ProductID']]) && $settings['status'][$activity[$item['ProductID']]['Status']]['Count']==1){
							//var_dump($settings['status'][$activity[$item['ProductID']]['Status']]['Count']);
							//var_dump($activity[$item['ProductID']]);
							$row['TotalMSRP']+=$games[$gameIndex[$item['ProductID']]]['MSRP'];
							$row['TotalMSRPFormula'] .= " + " . $games[$gameIndex[$item['ProductID']]]['MSRP'];
							$row['TotalWant']+=$games[$gameIndex[$item['ProductID']]]['Want'];
							
							//May need to use $activity[$item['ProductID']]['GrandTotal'] intead of 'totalHrs'
							$row['TotalHrs']+=$activity[$item['ProductID']]['totalHrs'];
							//var_dump($activity[$item['ProductID']]);	
						} elseif (!isset($activity[$item['ProductID']])){
							$row['TotalMSRP']+=$games[$gameIndex[$item['ProductID']]]['MSRP'];
							$row['TotalMSRPFormula'] .= " + " . $games[$gameIndex[$item['ProductID']]]['MSRP'];
							$row['TotalWant']+=$games[$gameIndex[$item['ProductID']]]['Want'];
						}
					}
				}
			}
			
			$gamesList=array();
			foreach($itemsbyBundle[$row['TransID']] as $item){
				if(isset($gameIndex[$item['ProductID']])){
					//if(!isset($row['GamesinBundle'][$item['ProductID']]['Debug'])) {
					//	$row['GamesinBundle'][$item['ProductID']]['Debug']="";
					//}
					//This check might be extranious, It does not seem to have an impact on the SalePrice Calculation. Need to investigate the TotalMSRP value now.
					//Corrected, it was the TotalMSRP in the loop above. this if is not needed for the accuracy of the calculation, leaving it in for acuacy of debug values.
					if(!in_array($item['ProductID'],$gamesList)){
						$gamesList[]=$item['ProductID'];
						
						if($row['TotalWant'] <> 0) {
							//var_dump($row['GamesinBundle'][$item['ProductID']]);
							$row['GamesinBundle'][$item['ProductID']]['Altwant']=$row['GamesinBundle'][$item['ProductID']]['Want']/$row['TotalWant']*$row['Paid'];
						} else {
							$row['GamesinBundle'][$item['ProductID']]['Altwant']=0;
						}
						
						if($row['TotalHrs'] <> 0) {
							if(isset($activity[$item['ProductID']])){
								//var_dump($activity[$item['ProductID']]);
								$row['GamesinBundle'][$item['ProductID']]['Althrs']=$activity[$item['ProductID']]['totalHrs']/$row['TotalHrs']*$row['Paid'];
							} else {
								$row['GamesinBundle'][$item['ProductID']]['Althrs']=0;
							}
						} else {
							$row['GamesinBundle'][$item['ProductID']]['Althrs']=0;
						}
						
						if($row['TotalMSRP'] <> 0) {
							//$debug1=
							//	"SalePrice: " .
							//	"MSRP(".$row['GamesinBundle'][$item['ProductID']]['MSRP'] . 
							//	")/TotalMSRP(" . $row['TotalMSRP'] . 
							//	")*Paid(" . $row['Paid'] . 
							//	")=" . $row['GamesinBundle'][$item['ProductID']]['MSRP']/$row['TotalMSRP']*$row['Paid'] . "<br>";
							//$row['GamesinBundle'][$item['ProductID']]['Debug'] .= $debug1;
						
							$row['GamesinBundle'][$item['ProductID']]['SalePrice']=$row['GamesinBundle'][$item['ProductID']]['MSRP']/$row['TotalMSRP']*$row['Paid'];
							$row['GamesinBundle'][$item['ProductID']]['SalePriceFormula']="(" . $row['GamesinBundle'][$item['ProductID']]['MSRP'] . " / " . $row['TotalMSRP'] . ") * " . $row['Paid'];
						} else {
							//$row['GamesinBundle'][$item['ProductID']]['Debug'].=$item['ProductID'].": totalMSRP=0<br>";
							
							$row['GamesinBundle'][$item['ProductID']]['SalePrice']=0;
							$row['GamesinBundle'][$item['ProductID']]['SalePriceFormula']="0";
						}
						
						//Good except need to add weight
						
						//var_dump($settings['WeightMSRP']);
						//var_dump($settings['WeightPlay']);
						//var_dump($settings['WeightWant']);
						//var_dump($useWeightMSRP);
						//var_dump($useWeightPlay);
						//var_dump($useWeightWant);
						
						/* */
						$totalWeight=0;
						if($row['GamesinBundle'][$item['ProductID']]['Altwant']>0) {
							$totalWeight+=$settings['WeightWant'];
						}
						if($row['GamesinBundle'][$item['ProductID']]['Althrs']>0) {
							$totalWeight+=$settings['WeightPlay'];
						}
						if($row['GamesinBundle'][$item['ProductID']]['SalePrice']<>0){
							$totalWeight+=$settings['WeightMSRP'];
						}
						if($totalWeight==0){
							$totalWeight=1;
						}
						//$totalWeight=$settings['WeightMSRP']+$settings['WeightPlay']+$settings['WeightWant'];
						$useWeightMSRP=$settings['WeightMSRP']/$totalWeight;
						$useWeightPlay=$settings['WeightPlay']/$totalWeight;
						$useWeightWant=$settings['WeightWant']/$totalWeight;
						/* */
						
						//TODO: Alt paid needs some corrections in the calculation for when games have zero hours.
						//TODO: Alt paid needs some corrections in the calculation for when a bundle has zero total hours.

						$row['GamesinBundle'][$item['ProductID']]['AltSalePrice']=
							$row['GamesinBundle'][$item['ProductID']]['Altwant']  *$useWeightWant +
							$row['GamesinBundle'][$item['ProductID']]['Althrs']   *$useWeightPlay +
							$row['GamesinBundle'][$item['ProductID']]['SalePrice']*$useWeightMSRP ;

						/*
						$row['GamesinBundle'][$item['ProductID']]['Debug'].=
							"TotalWeight = " . $totalWeight . " " .
							"AltSalePrice: AltWant (".
							(0+$row['GamesinBundle'][$item['ProductID']]['Altwant']) .
							" * (".$settings['WeightWant']."/".$totalWeight."=".(0+round($useWeightWant,2)).")=".($row['GamesinBundle'][$item['ProductID']]['Altwant']  *$useWeightWant).") + Althrs (".
							(0+$row['GamesinBundle'][$item['ProductID']]['Althrs']) .
							" * (".$settings['WeightPlay']."/".$totalWeight."=".(0+round($useWeightPlay,2)).")=".($row['GamesinBundle'][$item['ProductID']]['Althrs']   *$useWeightPlay).") + SalePrice (".
							(0+$row['GamesinBundle'][$item['ProductID']]['SalePrice']).
							" * (".$settings['WeightMSRP']."/".$totalWeight."=".(0+round($useWeightMSRP,2)).")=".($row['GamesinBundle'][$item['ProductID']]['SalePrice']*$useWeightMSRP).") = ".
							(0+$row['GamesinBundle'][$item['ProductID']]['AltSalePrice'])."<br>";
						*/
						/*
						HurtWorld:
						SalePrice: MSRP(24.99)/TotalMSRP(24.99)*Paid(12.00)=12
						TotalWeight = 100 
						AltSalePrice: 
							AltWant   (12 * (30/100=0.3)=3.6) + 
							Althrs    (0  * (20/100=0.2)=0) + 
							SalePrice (12 * (50/100=0.5)=6) = 9.6

						HurtWorld:
						SalePrice: MSRP(24.99)/TotalMSRP(24.99)*Paid(12.00)=12
						TotalWeight = 80 
						AltSalePrice: 
							AltWant   (12 * (30/80=0.38)=4.5) + 
							Althrs    (0  * (20/80=0.25)=0) + 
							SalePrice (12 * (50/80=0.63)=7.5) = 12
						
						HurtWorld:
						SalePrice: MSRP(24.99)/TotalMSRP(24.99)*Paid(12.00)=12
						TotalWeight = 60 
						AltSalePrice: 
							AltWant   (12 * (30/60=0.5 )=6) + 
							Althrs    (0  * (20/60=0.33)=0) + 
							SalePrice (12 * (50/60=0.83)=10) = 16

						*/
						
					}
				}
			}
		}
	}
	
	return $purchases;
}


// @codeCoverageIgnoreStart
function getAllPurchases($transID=""){
	trigger_error("FUNCTION getAllPurchases() IS DEPRICATED IN ".__FILE__.". ");
	
	require $GLOBALS['rootpath']."/inc/auth.inc.php";
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql="SELECT * FROM `gl_transactions`";
	if ($transID <> "" ) {
		$sql .= " where TransID = " . $transID ;
		$sql .= " OR BundleID = " . $transID ;
	}
	$sql .= "order by `PurchaseDate` ASC, `PurchaseTime` ASC, `Sequence` ASC" ;
	//$sql .= " Limit 50" ;
	if($result = $conn->query($sql)){
		//$cpi=getAllCpi();
		$items=regroupArray(getAllItems(),"TransID");

		
		while($row = $result->fetch_assoc()) {
			if($row['Tier']==0){$row['Tier']="";}
			
			$date=strtotime($row['PurchaseDate']);
			if(strtotime($row['PurchaseDate']) == 0) {
				$row['PurchaseDate'] = "";
			} else {
				$row['PurchaseDate'] = date("n/j/Y",$date);
			}
			
			$time = strtotime($row['PurchaseTime']);
			if(date("H:i:s",$time) == "00:00:00") {
				$row['PurchaseTime']= "";
			} else {
				$row['PurchaseTime']= date("H:i:s",$time) ;
			}
			
			$row['PurchaseTimeStamp']=strtotime($row['PurchaseDate'] . " " . $row['PurchaseTime'])+$row['Sequence'];
			if(date("H:i:s",$row['PurchaseTimeStamp']) == "00:00:00") {
				$row['PrintPurchaseTimeStamp']= date("n/j/Y",$row['PurchaseTimeStamp']);
			} else {
				$row['PrintPurchaseTimeStamp']= date("n/j/Y H:i:s",$row['PurchaseTimeStamp']) ;
			}

			if($row['Sequence']==0){$row['Sequence']="";}
			
			$row['Price'] = sprintf("%.2f",$row['Price']);
			if($row['Fees']<>0) {
				$row['Fees'] = sprintf("%.2f",$row['Fees']);
			} else {
				$row['Fees']="";
			}
			$row['Paid'] = sprintf("%.2f",$row['Paid']);
			if($row['Credit Used']<>0) {
				$row['Credit Used'] = sprintf("%.2f",$row['Credit Used']);
			} else {
				$row['Credit Used']="";
			}
			
			if($row['Bundle Link']<>""){
				if(substr($row['Bundle Link'],0,4)<>"http"){
					$row['Bundle Link']="http://". $row['Bundle Link'];
				}
				$row['Bundle Link']="<a href=\"".$row['Bundle Link']."\">Bundle</a>";
			}
			
			if(isset($items[$row['TransID']])) {
				$row['itemcount']=count($items[$row['TransID']]);
				$row['items']=$items[$row['TransID']];
			} else {
				$row['itemcount']=0;
				$row['items']=false;
			}
			
			$purchases[]=$row;
		}
	} else {
		$games = false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	$conn->close();	

	$ParentBundleIndex=makeIndex($purchases,"TransID");
	//echo nl2br(print_r($ParentBundleIndex,true));
	foreach ($purchases as &$row) {
		$max_loop=5;
		$n=0;
		$row['TopBundleID']=$row['BundleID'];
		$row['Bundle']=$purchases[$ParentBundleIndex[$row['BundleID']]]['Title'];
		while($purchases[$ParentBundleIndex[$row['TopBundleID']]]['BundleID']<>$row['TopBundleID']){
			$row['TopBundleID']=$purchases[$ParentBundleIndex[$row['TopBundleID']]]['BundleID'];
			if($n>=$max_loop) {
				break;
			}
			$n++;
		}
		if(isset($purchases[$ParentBundleIndex[$row['TopBundleID']]]['Title'])){
			$row['TopBundleID']  = $row['TopBundleID'];
			$row['TopBundle'] = $purchases[$ParentBundleIndex[$row['TopBundleID']]]['Title'];
		} else {
			$row['TopBundle']="Not Found";
			$row['TopBundleID']=null;
		}
		
		$row['BundlePrice'] = sprintf("%.2f",$purchases[$ParentBundleIndex[$row['TopBundleID']]]['Paid']);
		
		if($row['items']<>false) {
			$row['salePrice']=round($row['BundlePrice']/$row['itemcount'],2);
			foreach ($row['items'] as &$Item) {
				$Item['salePrice']=$row['salePrice'];
			}
		} else {
			$row['salePrice']=$row['BundlePrice'];
		}

	}
			
	return $purchases;
}

if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getPurchases.inc") {
	require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
	require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
	
	$title="get Purchases Inc Test";
	echo Get_Header($title);
	
	$lookupbundle=lookupTextBox("BundleTitle", "BundleID", "id", "Trans", $GLOBALS['rootpath']."/ajax/search.ajax.php");
	echo $lookupbundle["header"];
	if (!(isset($_GET['id']) && is_numeric($_GET['id']))) {
		?>
		Please specify a bundle by ID.
		<form method="Get">
			<?php echo $lookupbundle["textBox"]; ?>
			<br><label><input type="radio" name="function" value="getPurchases" CHECKED>getPurchases</label>
			<br><label><input type="radio" name="function" value="getAllPurchases">getAllPurchases</label>
			<br><input type="submit">
		</form>

		<?php
		echo $lookupbundle["lookupBox"];
	} else {
		if(!isset($_GET['function'])) {
			$_GET['function'] = "getPurchases";
		}
		
		switch ($_GET['function']) {
			case "getPurchases":
			default:
				$purchases=reIndexArray(getPurchases(),"TransID");
				break;
			case "getAllPurchases":
				$purchases=reIndexArray(getAllPurchases(),"TransID");
				break;
		}
		
		echo arrayTable($purchases[$_GET['id']]);
	}
	echo Get_Footer();
}
// @codeCoverageIgnoreEnd

?>
