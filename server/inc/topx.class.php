<?php
declare(strict_types=1);

require_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

class topx
{
	private $calculations;
	private $xvalue=3;
	private $filter="Playable,eq,0,Status,eq,Never,Status,eq,Done,Status,eq,Broken,Review,eq,1,Review,eq,2";
	private $sortDir=SORT_DESC;
	
	public function setfilter($filterstring){
		$this->filter=$filterstring;
	}
	
	public function setxvalue($xvalue){
		$this->xvalue=$xvalue;
	}
	
	public function __construct($calculations=null,$xvalue=3, $sortDir=SORT_DESC){
		$this->calculations = $calculations;
		$this->xvalue=$xvalue;
		$this->sortDir=$sortDir;
	}
	
	public function displaytop($gameidList,$stat,$mode="all"){
		$metastat = $stat["alt"];
		$output ="<div style='float:right; margin: 5px;'>";
		$output .="<table>";
		$output .="<thead><tr><th>Rank</th><th>Title</th>";
		
		$link  = "calculations.php?fav=Custom";
		$link .= "&sort=".$stat["stat"]."&dir=" .$stat["sortdir"];
		if($mode =="Active"){
			$link .= "&hide=Playable,eq,0,Status,ne,Active,Status,eq,Done" . $stat["filter"];
		} else {
			$link .= "&hide=Playable,eq,0,Rating,eq,1,Rating,eq,2,Status,eq,Never,Status,eq,Broken,Status,eq,Done" . $stat["filter"];
		}
		$link .= "&col=Title,MainLibrary," . $stat["stat"] . ",Review,AltSalePrice,TimeToBeat,GrandTotal,Altperhr,AltLess1,AltLess2,LastPlayORPurchase";
		
		$output .="<th><a style='color:black' href='$link' target=_blank>".$stat["header"]."</a></th>";
		foreach($metastat as $meta){
			$output .= "<th>".$this->statlist2()[$meta]["header"]."</th>";
		}
		$output .="</tr></thead>";
		$output .="<tbody>";
		foreach($gameidList as $key=> $gameid) {
			$rank=count($gameidList) -$key;
			$output .="<tr class='".$this->calculations[$gameid]['Status']."'>";
			$output .="<td>".$rank."</td>";
			$output .="<td><a href='viewgame.php?id=".$gameid."'>".$this->calculations[$gameid]["Title"]."</a></td>";
			$output .="<td>".$this->statformat($this->calculations[$gameid][$stat["stat"]],$stat["stat"])."</td>";
			foreach($metastat as $meta){
				$output .="<td>". $this->statformat($this->calculations[$gameid][$meta],$meta) ."</td>";
			}
			
			$output .="</tr>";
		}
		$output .="</tbody>";
		$output .="</table>";
		$output .="</div>";
		return $output;		
	}
	
	private function statformat($value,$statname){
		$currency=["Launchperhr","MSRPperhr", "Currentperhr", "Historicperhr", "Paidperhr", "Saleperhr", "Altperhr", "LaunchLess1", "MSRPLess1", "CurrentLess1", "HistoricLess1", "PaidLess1", "SaleLess1", "AltLess1"];
		
		$duration_hours=["TimeLeftToBeat", "LaunchLess2", "HistoricLess2", "MSRPLess2", "AltLess2", "SaleLess2", "PaidLess2", "MSRPHrsNext1", "LaunchHrsNext1", "PaidHrsNext1", "HistoricHrsNext1", "AltHrsNext1", "SaleHrsNext1", "MSRPHrsNext2", "LaunchHrsNext2", "PaidHrsNext2", "HistoricHrsNext2", "AltHrsNext2", "SaleHrsNext2"];
		
		$duration_seconds=["GrandTotal"];
		
		$percentage=["AchievementsPct"];
		
		$output=$value;
		
		if (in_array($statname, $currency)) {
			$output=sprintf("$%.2f",$value);
		}
		
		if (in_array($statname, $duration_hours)) {
			$output=timeduration($value,"hours");
		}

		if (in_array($statname, $duration_seconds)) {
			$output=timeduration($value,"seconds");
		}

		if (in_array($statname, $percentage)) {
			$output=sprintf("%.2f%%",$value);
		}

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
	
	private function sortbystat($stat, $sortDir=null){
		$sortDir=$sortDir ?? $stat["sortdir"];

		$calculations=$this->calculations;

		switch($stat["stat"]){
			case "PurchaseDate": 
				foreach ($calculations as $key => $row) {
					$Sortby1[$key] = $row['AddedDateTime']->getTimestamp();
				}
				break;
			case "LaunchDate":
				foreach ($calculations as $key => $row) {
					$Sortby1[$key] = $row[$stat["stat"]]->getTimestamp();
				}
				break;
			case "lastplay":
			case "firstplay":
			case "LastBeat":
			case "DateUpdated":
			case "LastPlayORPurchase":
				foreach ($calculations as $key => $row) {
					$Sortby1[$key]  = strtotime($row[$stat["stat"]]);
				}
				break;
			default:
				foreach ($calculations as $key => $row) {
					$Sortby1[$key]  = $row[$stat["stat"]];
				}
				break;
		}
		array_multisort($Sortby1, $sortDir, $calculations);
		return $calculations;
	}
	
	public function gettopx($stat,$filter=null){
		$calculations=$this->sortbystat($stat);
		
		$filter=$filter ?? $this->filter . $stat["filter"];
		$filter=$this->parseFilter($filter);
		
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
	
	public function statlist2($filter="all"){
		$list["LastPlayORPurchase"] = array(
			"stat"=>"LastPlayORPurchase",
			"alt"=> array("DaysSinceLastPlayORPurchase"),
			"sortdir"=>SORT_ASC,
			"filter"=>",LastPlayORPurchase,eq,0",
			"header"=>"Last Play/Purchase Date",
		);
		$list[] = array(
			"stat"=>"LastPlayORPurchase",
			"alt"=> array("DaysSinceLastPlayORPurchase"),
			"sortdir"=>SORT_DESC,
			"filter"=>",LastPlayORPurchase,eq,0",
			"header"=>"Recent Play/Purchase Date",
		);
		$list["Launchperhr"] = array(
			"stat"=>"Launchperhr",
			"alt"=> array("LaunchHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Launchperhr,eq,0",
			"header"=>"Launch Price $/hr",
		);
		$list["MSRPperhr"] = array(
			"stat"=>"MSRPperhr",
			"alt"=> array("MSRPHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",MSRPperhr,eq,0",
			"header"=>"MSRP $/hr",
		);
		$list["Historicperhr"] = array(
			"stat"=>"Historicperhr",
			"alt"=> array("HistoricHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Historicperhr,eq,0",
			"header"=>"Historic $/hr",
		);
		$list["Paidperhr"] = array(
			"stat"=>"Paidperhr",
			"alt"=> array("PaidHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Paidperhr,eq,0",
			"header"=>"Paid $/hr",
		);
		$list["Saleperhr"] = array(
			"stat"=>"Saleperhr",
			"alt"=> array("SaleHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Saleperhr,eq,0",
			"header"=>"Sale Price $/hr",
		);		
		$list["Altperhr"] = array(
			"stat"=>"Altperhr",
			"alt"=> array("AltHrsNext1"),
			"sortdir"=>SORT_DESC,
			"filter"=>",Altperhr,eq,0",
			"header"=>"Alt Sale $/hr",
		);
		
		$list["LaunchLess1"] = array(
			"stat"=>"LaunchLess1",
			"alt"=> array("LaunchLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",LaunchLess1,eq,0",
			"header"=>"1 Hour reduces Launch by",
		);
		$list["MSRPLess1"] = array(
			"stat"=>"MSRPLess1",
			"alt"=> array("MSRPLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",MSRPLess1,eq,0",
			"header"=>"1 Hour reduces MSRP by",
		);
		$list["HistoricLess1"] = array(
			"stat"=>"HistoricLess1",
			"alt"=> array("HistoricLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",HistoricLess1,eq,0",
			"header"=>"1 Hour reduces Historic by",
		);
		$list["PaidLess1"] = array(
			"stat"=>"PaidLess1",
			"alt"=> array("PaidLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",PaidLess1,eq,0",
			"header"=>"1 Hour reduces Paid by",
		);
		$list["SaleLess1"] = array(
			"stat"=>"SaleLess1",
			"alt"=> array("SaleLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",SaleLess1,eq,0",
			"header"=>"1 Hour reduces Sale by",
		);
		$list["AltLess1"] = array(
			"stat"=>"AltLess1",
			"alt"=> array("AltLess2"),
			"sortdir"=>SORT_DESC,
			"filter"=>",AltLess1,eq,0",
			"header"=>"1 Hour reduces Alt by",
		);
		
		$list["LaunchLess2"] = array(
			"stat"=>"LaunchLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",LaunchLess2,eq,0",
			"header"=>"Hours to $0.01 less of Launch",
		);
		$list["MSRPLess2"] = array(
			"stat"=>"MSRPLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",MSRPLess2,eq,0",
			"header"=>"Hours to $0.01 less of MSRP",
		);
		$list["HistoricLess2"] = array(
			"stat"=>"HistoricLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",HistoricLess2,eq,0",
			"header"=>"Hours to $0.01 less of Historic",
		);
		$list["PaidLess2"] = array(
			"stat"=>"PaidLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",PaidLess2,eq,0",
			"header"=>"Hours to $0.01 less of Paid",
		);
		$list["SaleLess2"] = array(
			"stat"=>"SaleLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",SaleLess2,eq,0",
			"header"=>"Hours to $0.01 less of Sale",
		);
		$list["AltLess2"] = array(
			"stat"=>"AltLess2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",AltLess2,eq,0",
			"header"=>"Hours to $0.01 less of Alt",
		);
		
		$list["LaunchHrsNext1"] = array(
			"stat"=>"LaunchHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",LaunchHrsNext1,eq,0",
			"header"=>"Hrs to next position Launch $/hr",
		);
		$list["MSRPHrsNext1"] = array(
			"stat"=>"MSRPHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",MSRPHrsNext1,eq,0",
			"header"=>"Hrs to next position MSRP $/hr",
		);
		$list["HistoricHrsNext1"] = array(
			"stat"=>"HistoricHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",HistoricHrsNext1,eq,0",
			"header"=>"Hrs to next position Historic $/hr",
		);
		$list["PaidHrsNext1"] = array(
			"stat"=>"PaidHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",PaidHrsNext1,eq,0",
			"header"=>"Hrs to next position Paid $/hr",
		);
		$list["SaleHrsNext1"] = array(
			"stat"=>"SaleHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",SaleHrsNext1,eq,0",
			"header"=>"Hrs to next position Sale $/hr",
		);
		$list["AltHrsNext1"] = array(
			"stat"=>"AltHrsNext1",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",AltHrsNext1,eq,0",
			"header"=>"Hrs to next position Alt $/hr",
		);
		
		$list["LaunchHrsNext2"] = array(
			"stat"=>"LaunchHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",LaunchHrsNext2,eq,0",
			"header"=>"Hrs to next active position Launch $/hr",
		);
		$list["MSRPHrsNext2"] = array(
			"stat"=>"MSRPHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",MSRPHrsNext2,eq,0",
			"header"=>"Hrs to next active position MSRP $/hr",
		);
		$list["HistoricHrsNext2"] = array(
			"stat"=>"HistoricHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",HistoricHrsNext2,eq,0",
			"header"=>"Hrs to next active position Historic $/hr",
		);
		$list["PaidHrsNext2"] = array(
			"stat"=>"PaidHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",PaidHrsNext2,eq,0",
			"header"=>"Hrs to next Active position Paid $/hr",
		);
		$list["SaleHrsNext2"] = array(
			"stat"=>"SaleHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",SaleHrsNext2,eq,0",
			"header"=>"Hrs to next active position Sale $/hr",
		);
		$list["AltHrsNext2"] = array(
			"stat"=>"AltHrsNext2",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",AltHrsNext2,eq,0",
			"header"=>"Hrs to next active position Alt $/hr",
		);
		
		$list["TimeLeftToBeat"] = array(
			"stat"=>"TimeLeftToBeat",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",TimeLeftToBeat,eq,0",
			"header"=>"Time Left to Beat",
		);
		$list["GrandTotal"] = array(
			"stat"=>"GrandTotal",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>"",
			"header"=>"Total Hours (min)",
		);
		$list[] = array(
			"stat"=>"GrandTotal",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>"",
			"header"=>"Total Hours (max)",
		);
		$list["AchievementsLeft"] = array(
			"stat"=>"AchievementsLeft",
			"alt"=> array(),
			"sortdir"=>SORT_ASC,
			"filter"=>",AchievementsLeft,lte,0",
			"header"=>"Achievements Left",
		);
		$list["AchievementsPct"] = array(
			"stat"=>"AchievementsPct",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",AchievementsPct,lte,0,AchievementsPct,gte,100",
			"header"=>"Achievement %",
		);
		$list["Metascore"] = array(
			"stat"=>"Metascore",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",Metascore,eq,0",
			"header"=>"Metascore",
		);
		$list["UserMetascore"] = array(
			"stat"=>"UserMetascore",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",UserMetascore,eq,0",
			"header"=>"User Metascore",
		);
		$list["SteamRating"] = array(
			"stat"=>"SteamRating",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",SteamRating,eq,0",
			"header"=>"Steam Rating",
		);
		$list["Review"] = array(
			"stat"=>"Review",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",Review,eq,0",
			"header"=>"My Review",
		);
		$list["Want"] = array(
			"stat"=>"Want",
			"alt"=> array(),
			"sortdir"=>SORT_DESC,
			"filter"=>",Want,eq,0",
			"header"=>"My Wishlist Rank",
		);
		$list["DaysSinceLastPlayORPurchase"] = array(
			"header"=>"Days Since Last Play/Purchase",
		);
		
		return $list;
	}
	
	public function getTotalRanks($filter="main"){
		foreach ($this->statlist2($filter) as $stat) {
			if(isset($stat["stat"])){
				$list = $this->gettopx($stat);
				foreach ($list as $key => $item){
					$totalranks[$item]["ranks"] = ($totalranks[$item]["ranks"] ?? 0) + (count($list)-$key)/count($list);
					$sortranks[$item]=$totalranks[$item]["ranks"];
					$totalranks[$item]["id"]=$item;
					if(!isset($totalranks[$item]["metastatname"])){
						$totalranks[$item]["metastatname"]=$stat["stat"];
						$metastatcurrentvalue=0;
					} else {
						$metastatcurrentvalue=$this->calculations[$totalranks[$item]["id"]][$totalranks[$item]["metastatname"]];
					}
					$metastat=$stat["alt"];
					if(count($metastat) > 0){
						$usemetastat=$metastat[0];
					} else {
						$usemetastat=$stat["stat"];
					}
					$metastatnewvalue=$this->calculations[$totalranks[$item]["id"]][$usemetastat];
					
					if($metastatnewvalue > $metastatcurrentvalue) {
						$totalranks[$item]["metastatname"]=$usemetastat;
					}
				}
			}
		}
		
		array_multisort($sortranks, SORT_DESC, $totalranks);

		//var_dump($totalranks);

		return $totalranks;
	}
	
	public function makeDetailTable($totalranks){
		$output  ="";
		$output .="<table>";
		$output .="<thead><tr><th>Ranks</th><th>Title</th><th>Library</th><th>Top Stat</th><th>Value</th>";
		//$output .="<th>Meta Stat</th><th>Value</th>";
		$output .="</tr></thead>";
		$output .="<tbody>";
		//var_dump($totalranks);
		foreach($totalranks as $item){
			$output .="<tr>";
			$output .="<tr class='".$this->calculations[$item["id"]]['Status']."'>";
			$output .="<td>".round($item["ranks"],1)."</td>";
			$output .="<td><a href='viewgame.php?id=".$item["id"]."'>".$this->calculations[$item["id"]]["Title"]."</a></td>";
			$output .="<td>".$this->calculations[$item["id"]]["MainLibrary"]."</td>";
			//$output .="<td>";
			//$output .= $this->statlist2()[$item["metastatname"]]["header"];
			//$output .="</td>";
			//$output .="<td>";
			//$output .=$this->statformat($this->calculations[$item["id"]][$item["metastatname"]],$item["metastatname"]);
			//$output .="</td>";
			$output .="<td>";
			if(isset($item["metastatname"])){
				$output .= $this->statlist2()[$item["metastatname"]]["header"];
			}
			$output .="</td>";
			$output .="<td>";
			if(isset($item["metastatname"])){
				$output .=$this->statformat($this->calculations[$item["id"]][$item["metastatname"]],$item["metastatname"]);
			}
			$output .="</td>";
			$output .="</tr>";
		}
		$output .="</tbody>";
		$output .="</table>";
		
		return $output;
	}
	
	public function makeSourceCloud($filter="all"){
		$output2="";
		foreach ($this->statlist2($filter) as $stat) {
			if(isset($stat["stat"])){
				$list = $this->gettopx($stat);
				$output2 .= $this->displaytop($list,$stat,$filter);
			}
		}
		return $output2;
	}
	
}