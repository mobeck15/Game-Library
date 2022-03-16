<?php
$GLOBALS['rootpath']= $GLOBALS['rootpath'] ?? "..";
require_once $GLOBALS['rootpath']."/inc/utility.inc.php";
require_once $GLOBALS['rootpath']."/inc/getGames.inc.php";
require_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";
require_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/dataAccess.class.php";

class Purchases
{
	private $gameIndex;
	private $activity;
	private $settings;
	private $ParentBundleIndex;
	private $max_loop=5;
	private $items;
	private $games;
	private $purchases;

	public function __construct($transID="",$connection=false,$items=false,$games=false) {
		if($connection==false){
			$conn = get_db_connection();
		} else {
			$conn = $connection;
		}
		if($games==false){
			$this->games=getGames("",$conn);
		} else {
			$this->games=$games;
		}
		if ($items==false){
			$this->items=getAllItems("",$conn);
		} else {
			$this->items=$items;
		}

		$this->gameIndex=makeIndex($this->games,"Game_ID");
		$this->activity=getActivityCalculations("","",$conn);
		$this->settings=getsettings($conn);
		
		if($connection==false){
			$conn->close();	
		}
		
		$dataobject= new dataAccess();
		//TODO: Filtering by one transaction ID breaks later calculations.
		$transID=null; //Remove this line once todo above is fixed.
		$statement=$dataobject->getPurchases($transID);
		while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$this->purchases[]=$this->purchaseRowFirstPass($row);		
		}

		$this->ParentBundleIndex=makeIndex($this->purchases,"TransID");
		
	}
	
	//TODO: groupItemsByBundle Belongs in the item class
	private function groupItemsByBundle($items){
		$itemsbyBundle=array();
		foreach ($items as $value) {
			//DONE: change Paid calculation to use only the first bundle ****PAID****
			$count=true;
			if($this->settings["CountDupes"]=="First" && $value["FirstItem"]==false){
				$count=false;
			}
			
			if($count) {
				$itemsbyBundle[$this->getTopBundle($value['TransID'])][]=$value;
			}
		}
		return $itemsbyBundle;
	}
	
	private function purchaseRowFirstPass($row){
		$row['Tier']=$this->setvalue($row['Tier']==0,"",$row['Tier']);
		
		$date=strtotime($row['PurchaseDate']);
		$row['PurchaseDate']=$this->setvalue(strtotime($row['PurchaseDate']) == 0,"",date("n/j/Y",$date));
		
		$time = strtotime($row['PurchaseTime']);
		//TODO: 00:00:00 OR 01:00:00 might have something to do with setting the system time zone.
		$row['PurchaseTime']=$this->setvalue((date("H:i:s",$time) == "00:00:00" OR date("H:i:s",$time) == "01:00:00"),"",date("H:i:s",$time));
		
		$row['PrintPurchaseTimeStamp']=combinedate($row['PurchaseDate'], $row['PurchaseTime'], $row['Sequence']);
		
		$row['PurchaseDateTime'] = new DateTime($row['PurchaseDate'] . " " . $row['PurchaseTime']);
		
		$row['Sequence']=$this->setvalue($row['Sequence']==0,"",$row['Sequence']);
		
		$row['Price'] = sprintf("%.2f",$row['Price']);

		$row['Fees']=$this->setvalue($row['Fees']<>0,sprintf("%.2f",$row['Fees']),"");
		
		$row['Paid'] = sprintf("%.2f",$row['Paid']);

		$row['Credit Used']=$this->setvalue($row['Credit Used']<>0,sprintf("%.2f",$row['Credit Used']),"");
		
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
		
		return $row;
	}
	
	private function itemRowBundles($item, $row) {
		$row['ProductsinBunde'][$item['ProductID']]=$item['ProductID'];
		if(isset($this->gameIndex[$item['ProductID']])){
			$row['GamesinBundle'][$item['ProductID']]['GameID']=$item['ProductID'];
			$row['GamesinBundle'][$item['ProductID']]['Type']=$this->games[$this->gameIndex[$item['ProductID']]]['Type'];
			$row['GamesinBundle'][$item['ProductID']]['Playable']=$this->games[$this->gameIndex[$item['ProductID']]]['Playable'];
			$row['GamesinBundle'][$item['ProductID']]['MSRP']=$this->games[$this->gameIndex[$item['ProductID']]]['MSRP'];
			$row['GamesinBundle'][$item['ProductID']]['Want']=$this->games[$this->gameIndex[$item['ProductID']]]['Want'];
			$row['GamesinBundle'][$item['ProductID']]['HistoricLow']=$this->games[$this->gameIndex[$item['ProductID']]]['HistoricLow'];
			
			if(isset($this->activity[$item['ProductID']]) && $this->settings['status'][$this->activity[$item['ProductID']]['Status']]['Count']==1){
				$row['TotalMSRP']+=$this->games[$this->gameIndex[$item['ProductID']]]['MSRP'];
				$row['TotalMSRPFormula'] .= " + " . $this->games[$this->gameIndex[$item['ProductID']]]['MSRP'];
				$row['TotalWant']+=$this->games[$this->gameIndex[$item['ProductID']]]['Want'];
				
				//May need to use $this->activity[$item['ProductID']]['GrandTotal'] intead of 'totalHrs'
				$row['TotalHrs']+=$this->activity[$item['ProductID']]['totalHrs'];
			} elseif (!isset($this->activity[$item['ProductID']])){
				$row['TotalMSRP']+=$this->games[$this->gameIndex[$item['ProductID']]]['MSRP'];
				$row['TotalMSRPFormula'] .= " + " . $this->games[$this->gameIndex[$item['ProductID']]]['MSRP'];
				$row['TotalWant']+=$this->games[$this->gameIndex[$item['ProductID']]]['Want'];
			} //else {  //Use this to discover scenarios for testing.
				//var_dump($item['ProductID']); //1162
				//var_dump($this->activity[$item['ProductID']]); 
		}
		return $row;
	}
	
	private function calculateSalePrice($item, $row) {
		$row['GamesinBundle'][$item['ProductID']]['SalePrice']=($this->divzero($row['GamesinBundle'][$item['ProductID']]['MSRP'],$row['TotalMSRP'])*$row['Paid']);
		$row['GamesinBundle'][$item['ProductID']]['SalePriceFormula']=$this->setvalue(($row['TotalMSRP'] <> 0),"(" . $row['GamesinBundle'][$item['ProductID']]['MSRP'] . " / " . $row['TotalMSRP'] . ") * " . $row['Paid']);

		return $row;
	}
	
	private function calculateAltSalePrice($item, $row){
		$totalWeight=$this->settings['WeightWant']+$this->settings['WeightPlay']+$this->settings['WeightMSRP'];
		if($totalWeight==0) {
			$totalWeight = 1;
		}
		$useWeightMSRP=$this->settings['WeightMSRP']/$totalWeight;
		$useWeightPlay=$this->settings['WeightPlay']/$totalWeight;
		$useWeightWant=$this->settings['WeightWant']/$totalWeight;
		
		$payRatio=$this->divzero($row['GamesinBundle'][$item['ProductID']]['MSRP'],$row['TotalMSRP']);
		$wantRatio=$this->divzero($row['GamesinBundle'][$item['ProductID']]['Want'],$row['TotalWant']);
		$hoursRatio=$this->divzero(($this->activity[$item['ProductID']]['totalHrs'] ?? 0),$row['TotalHrs']);
		//echo $item['ProductID']. " ";
		
		$row['GamesinBundle'][$item['ProductID']]['Altwant']=($wantRatio*$useWeightWant*$row['Paid']);
		$row['GamesinBundle'][$item['ProductID']]['Althrs']=($hoursRatio*$useWeightPlay*$row['Paid']);
		$row['GamesinBundle'][$item['ProductID']]['AltPrice']=($payRatio*$useWeightMSRP*$row['Paid']);
		
		$row['GamesinBundle'][$item['ProductID']]['AltSalePrice']=
			$row['GamesinBundle'][$item['ProductID']]['Altwant']
			+$row['GamesinBundle'][$item['ProductID']]['Althrs']
			+$row['GamesinBundle'][$item['ProductID']]['AltPrice'];
			
		//([Game Price]/[Bundle Total]*[Price Weight]*[Bundle Price])
		//+([Game Want]/[Bundle Want]*[Want Weight]*[Bundle Price])
		//+([Game Hours]/[Bundle Hours]*[Play Weight]*[Bundle Price])
		/*
		$row['GamesinBundle'][$item['ProductID']]['Debug'] = 
				"\nAltSale: " . $row['GamesinBundle'][$item['ProductID']]['AltSalePrice'] . " = ".
				"\nWantPart (" . ($wantRatio*$useWeightWant*$row['Paid']) .") + ".
				"HoursPart (" . ($hoursRatio*$useWeightPlay*$row['Paid']) . ") + ".
				"MSRPpart (" . ($payRatio*$useWeightMSRP*$row['Paid']) . ")" ."\n";
				*/
		return $row;
	}
	
	private function divzero($numerator, $denominator, $ifzero=0){
		if($denominator <> 0) {
			return $numerator/$denominator;
		} else {
			return $ifzero;
		}
	}

	private function setvalue($test, $value, $altvalue=0) {
		if($test) {
			return $value;
		} else {
			return $altvalue;
		}
	}
	
	private function getTopBundle($TopBundleID){
		$n=0;
		while($this->purchases[$this->ParentBundleIndex[$TopBundleID]]['BundleID']<>$TopBundleID && $n<=$this->max_loop){ 
			$TopBundleID=$this->purchases[$this->ParentBundleIndex[$TopBundleID]]['BundleID'];
			$n++;
		}
		if($n>$this->max_loop) {
			$TopBundleID=null;
			//echo "\nTop bundle search stopped at $n loops\n";
			trigger_error("Exceeded maximum parent bundles (" . $this->max_loop . ")");
		}
		return $TopBundleID;
	}
	
	public function getPurchases(){
		$itemsbyBundle=$this->groupItemsByBundle($this->items);
		
		foreach ($this->purchases as &$row) {
			$row['Bundle']=$this->purchases[$this->ParentBundleIndex[$row['BundleID']]]['Title'];
			
			$row['TopBundleID']=$this->getTopBundle($row['BundleID']);
			
			$row['TopBundle']=$this->purchases[$this->ParentBundleIndex[$row['TopBundleID']]]['Title'] ?? "Not Found";
			
			$row['BundlePrice'] = sprintf("%.2f",$this->purchases[$this->ParentBundleIndex[$row['TopBundleID']]]['Paid']);

			if (isset($itemsbyBundle[$row['TransID']])){

				//This first loop counts the totals for each bundle such as totalMSRP, totlaWant, and totalHours
				$gamesList=array();
				foreach($itemsbyBundle[$row['TransID']] as $item){
					$row['itemsinBundle'][$item['ItemID']]=$item['ItemID'];
					if(!in_array($item['ProductID'],$gamesList) && $item['ProductID']<>null){
						$gamesList[]=$item['ProductID'];
						$row=$this->itemRowBundles($item,$row);
					}
				}
				
				//The second loop uses totalMSRP, totlaWant, and totalHours to calculate saleprice and altsaleprice.
				$gamesList=array();
				foreach($itemsbyBundle[$row['TransID']] as $item){
					if(isset($this->gameIndex[$item['ProductID']])){
						if(!in_array($item['ProductID'],$gamesList)){
							$gamesList[]=$item['ProductID'];
							$row=$this->calculateAltSalePrice($item,$row);
							$row=$this->calculateSalePrice($item,$row);
						}
					}
				}
			}
		}
		
		return $this->purchases;
	}
}
/* ----------------------------------------------------------------------------------- */

// @codeCoverageIgnoreStart
if (basename($_SERVER["SCRIPT_NAME"], '.php') == "getPurchases.class") {
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
			<br><input type="submit">
		</form>

		<?php
		echo $lookupbundle["lookupBox"];
	} else {
		//$purchases=reIndexArray(getAllPurchases(),"TransID");
		$purchaseobj=new Purchases();
		$purchases=$purchaseobj->getPurchases();
		$purchases=reIndexArray($purchases, "TransID");

		echo arrayTable($purchases[$_GET['id']]);
	}
	echo Get_Footer();
}
// @codeCoverageIgnoreEnd

?>
