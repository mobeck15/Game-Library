<?php
declare(strict_types=1);

require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

class topx
{
	private $calculations;
	private $xvalue=3;
	private $filter="Playable,eq,0,Status,eq,Never,Status,eq,Done,Status,eq,Broken,Review,eq,1,Review,eq,2";
	private $sortDir=SORT_DESC;
	
	public function __construct($calculations=null,$xvalue=3, $sortDir=SORT_DESC){
		$this->calculations = $calculations;
		$this->xvalue=$xvalue;
		$this->sortDir=$sortDir;
	}
	
	public function displaytop($gameidList,$stat){
		$output ="<div style='float:right; margin: 5px;'>";
		$output .="<table>";
		$output .="<thead><tr><th>Rank</th><th>Title</th><th>".$this->getHeaderText($stat)."</th></tr></thead>";
		$output .="<tbody>";
		foreach($gameidList as $key=> $gameid) {
			$rank=count($gameidList) -$key;
			$output .="<tr class='".$this->calculations[$gameid]['Status']."'>";
			$output .="<td>".$rank."</td>";
			$output .="<td><a href='viewgame.php?id=".$gameid."'>".$this->calculations[$gameid]["Title"]."</a></td>";
			$output .="<td>".$this->calculations[$gameid][$stat]."</td>";
			
			$output .="</tr>";
		}
		$output .="</tbody>";
		$output .="</table>";
		$output .="</div>";
		return $output;		
	}
	
	private function parseFilter($filterstring){
		$filter=array();
		$hideData = explode (",",$filterstring);
		$index2=1;
		foreach($hideData as $data){
			if($index2==1){
				$tempdata=array();
				$tempdata['field']=$data;
				$index2++;
			}elseif($index2==2){
				
				$tempdata['operator']=$data;
				$index2++;
			}elseif($index2==3){
				
				$tempdata['value']=$data;
				$index2=1;
				$filter[]=$tempdata;
			}
			
		}
		return $filter;
	}
	
	private function hideLoop($game,$filter){
		$showgame=true;
	
		foreach ($filter as $hide) {
			switch($hide['operator']){
				case "gt": //Greater Than
					if($game[$hide['field']]>$hide['value']){$showgame=false; }
					break;
				case "gte": //Greater Than or Equal
					if($game[$hide['field']]>=$hide['value']){$showgame=false; }
					break;
				case "eq": // Equal
					if($game[$hide['field']]==$hide['value']){$showgame=false; }
					break;
				case "lte": //Less Than or Equal
					if($game[$hide['field']]<=$hide['value']){$showgame=false; }
					break;
				case "lt": //Less Than
					if($game[$hide['field']]<$hide['value']){$showgame=false; }
					break;
				case "ne": //Not Equal
					if($game[$hide['field']]<>$hide['value']){$showgame=false; }
					break;
			}
		}
		return $showgame;
	}
	
	private function sortbystat($stat){
		//$sortDir=$sortDir ?? $this->sortDir;
		$sortDir=$this->defaultSortDir($stat);

		$calculations=$this->calculations;

		switch($stat){
			case "PurchaseDate": 
				foreach ($calculations as $key => $row) {
					$Sortby1[$key] = $row['AddedDateTime']->getTimestamp();
				}
				break;
			case "LaunchDate":
				foreach ($calculations as $key => $row) {
					$Sortby1[$key] = $row[$stat]->getTimestamp();
				}
				break;
			case "lastplay":
			case "firstplay":
			case "LastBeat":
			case "DateUpdated":
			case "LastPlayORPurchase":
				foreach ($calculations as $key => $row) {
					$Sortby1[$key]  = strtotime($row[$stat]);
				}
				break;
			default:
				foreach ($calculations as $key => $row) {
					$Sortby1[$key]  = $row[$stat];
				}
				break;
		}
		array_multisort($Sortby1, $sortDir, $calculations);
		return $calculations;
	}
	
	public function gettopx($stat,$filter=null){
		$calculations=$this->sortbystat($stat);
		
		$filter=$filter ?? $this->defaultFilterString($stat);
		$filter=$this->parseFilter($filter);		
		
		//$list = array(3372,4252,3143);
		$list=array();
		
		$rowcount=0;
		foreach($calculations as $game){
			if($this->hideLoop($game,$filter)){
				$list[]=$game["Game_ID"];
				$rowcount++;
			}
			if($rowcount>=$this->xvalue){
				break;
			}
		}
		
		return $list;
	}
	private function defaultFilterString($stat){
		switch($stat){
			case "GrandTotal":
				return $this->filter;
				break;
			default:
				return $this->filter.",$stat,eq,0";
				break;
		}
	}
	
	private function defaultSortDir($stat){
		switch($stat){
			case "LastPlayORPurchase":
			case "LaunchLess2":
			case "MSRPLess2":
			case "CurrentLess2":
			case "HistoricLess2":
			case "PaidLess2":
			case "SaleLess2":
			case "AltLess2":
			case "LaunchHrsNext1":
			case "MSRPHrsNext1":
			case "CurrentHrsNext1":
			case "HistoricHrsNext1":
			case "PaidHrsNext1":
			case "SaleHrsNext1":
			case "AltHrsNext1":

			case "LaunchHrsNext2":
			case "MSRPHrsNext2":
			case "CurrentHrsNext2":
			case "HistoricHrsNext2":
			case "PaidHrsNext2":
			case "SaleHrsNext2":
			case "AltHrsNext2":
			case "TimeLeftToBeat":
			case "GrandTotal":
				return SORT_ASC;
				break;
			default:
				return SORT_DESC;
				break;
		}
	}
	
	public function statlist(){
		$list = array(
			"LastPlayORPurchase",
			"SaleLess1",
			"Saleperhr",
			"AltLess1",
			"Altperhr",
			"Launchperhr",
			"MSRPperhr",
			"Historicperhr",
			"Paidperhr",
			"LaunchLess1",
			"MSRPLess1",
			"CurrentLess1",
			"HistoricLess1",
			"PaidLess1",
			"LaunchLess2",
			"MSRPLess2",
			"CurrentLess2",
			"HistoricLess2",
			"PaidLess2",
			"SaleLess2",
			"AltLess2",

			"LaunchHrsNext1",
			"MSRPHrsNext1",
			"CurrentHrsNext1",
			"HistoricHrsNext1",
			"PaidHrsNext1",
			"SaleHrsNext1",
			"AltHrsNext1",

			"LaunchHrsNext2",
			"MSRPHrsNext2",
			"CurrentHrsNext2",
			"HistoricHrsNext2",
			"PaidHrsNext2",
			"SaleHrsNext2",
			"AltHrsNext2",
			"TimeLeftToBeat",
			"GrandTotal",
		);
		
		return $list;
	}
	
	private function getHeaderText($stat){
		$header_Index=array(
			"ParentGame" => "Parent Game",
			"LaunchDate" => "Launch Date",
			"PurchaseDate" => "Purchase Date",
			"BundlePrice" => "Bundle Price",
			"LaunchPrice" => "Launch Price",
			"CurrentMSRP" => "Current MSRP",
			"HistoricLow" => "Historic Low",
			"SalePrice" => "Sale Price",
			"AltSalePrice" => "Alt Sale",
			"TimeToBeat" => "Time To Beat",
			"MetaUser" => "User Metascore",
			"GrandTotal" => "Total Hours",
			"AchievementsEarned" => "Achievements Earned",
			"AchievementsLeft" => "Achievements Left",
			"CountGame" => "Count Game",
			"lastplay" => "Last Played",
			"firstplay" => "First Played",
			"LastBeat" => "Last Beat",
			"DateUpdated" => "Last Updated",
			
			"Launchperhr" => "Launch Price $/hr",
			"MSRPperhr" => "MSRP $/hr",
			"Currentperhr" => "Current MSRP $/hr",
			"Historicperhr" => "Historic Low $/hr",
			"Paidperhr" => "Paid $/hr",
			"Saleperhr" => "Sale Price $/hr",
			"Altperhr" => "Alt Sale $/hr",

			"LaunchLess1" => "1 Hour reduces Launch by",
			"MSRPLess1" => "1 Hour reduces MSRP by",
			"CurrentLess1" => "1 Hour reduces Current by",
			"HistoricLess1" => "1 Hour reduces Historic by",
			"PaidLess1" => "1 Hour reduces Paid by",
			"SaleLess1" => "1 Hour reduces Sale by",
			"AltLess1" => "1 Hour reduces Alt by",

			"LaunchLess2" => "Hours to $0.01 less of Launch",
			"MSRPLess2" => "Hours to $0.01 less of MSRP",
			"CurrentLess2" => "Hours to $0.01 less of Current",
			"HistoricLess2" => "Hours to $0.01 less of Historic",
			"PaidLess2" => "Hours to $0.01 less of Paid",
			"SaleLess2" => "Hours to $0.01 less of Sale",
			"AltLess2" => "Hours to $0.01 less of Alt",

			"LaunchHrsNext1" => "Hrs to next position Launch $/hr",
			"MSRPHrsNext1" => "Hrs to next position MSRP $/hr",
			"CurrentHrsNext1" => "Hrs to next position Current $/hr",
			"HistoricHrsNext1" => "Hrs to next position Historic $/hr",
			"PaidHrsNext1" => "Hrs to next position Paid $/hr",
			"SaleHrsNext1" => "Hrs to next position Sale $/hr",
			"AltHrsNext1" => "Hrs to next position Alt $/hr",

			"LaunchHrsNext2" => "Hrs to next active position Launch $/hr",
			"MSRPHrsNext2" => "Hrs to next active position MSRP $/hr",
			"CurrentHrsNext2" => "Hrs to next active position Current $/hr",
			"HistoricHrsNext2" => "Hrs to next active position Historic $/hr",
			"PaidHrsNext2" => "Hrs to next Active position Paid $/hr",
			"SaleHrsNext2" => "Hrs to next active position Sale $/hr",
			"AltHrsNext2" => "Hrs to next active position Alt $/hr",
			
			"LastPlayORPurchase" => "Last Play/Purchase",
			"TimeLeftToBeat" => "Time Left to Beat",
			
			"DrmFree" => "Drm Free",
			"DrmFreeSize" => "Drm Free File Size",
			"DrmFreeLibrary" => "Drm Free Library",
			"Key" => "Activation Key / Link",
			
			"MainLibrary" => "Main Library",
			"OtherLibrary" => "Other Library"
		);		
		return $header_Index[$stat];
	}
	
}