<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require_once $GLOBALS['rootpath']."/inc/topx.class.php";

$title="Play Next";
echo Get_Header($title);

$calculations=getCalculations();

$calculations=reIndexArray($calculations,"Game_ID");

$topxobj= new topx($calculations);

$totalranks=array();
$output2="";
$topxobj->setfilter("Playable,eq,0,Status,ne,Active,Review,eq,1,Review,eq,2");
foreach ($topxobj->statlist("main") as $stat) {
	$list = $topxobj->gettopx($stat);
	foreach ($list as $key => $item){
		$totalranks[$item]["ranks"] = ($totalranks[$item]["ranks"] ?? 0) + (count($list)-$key)/count($list);
		$sortranks[$item]=$totalranks[$item]["ranks"];
		$totalranks[$item]["id"]=$item;
		if(!isset($totalranks[$item]["metastatname"])){
			$metastatcurrentvalue=0;
		} else {
			$metastatcurrentvalue=$calculations[$totalranks[$item]["id"]][$totalranks[$item]["metastatname"]];
		}
		$metastat=$topxobj->getMetaStat($stat,"active");
		if(count($metastat) > 0){
			$usemetastat=$metastat[0];
		} else {
			$usemetastat=$stat;
		}
		
		$metastatnewvalue=$calculations[$totalranks[$item]["id"]][$usemetastat];
		
		if($metastatnewvalue > $metastatcurrentvalue) {
			$totalranks[$item]["metastatname"]=$usemetastat;
		}
	}
	$output2 .= $topxobj->displaytop($list,$stat);
}

array_multisort($sortranks, SORT_DESC, $totalranks);		

$output  ="";
$output .="<table>";
$output .="<thead><tr><th>Ranks</th><th>Title</th><th>Top Stat</th><th>Value</th></tr></thead>";
$output .="<tbody>";
foreach($totalranks as $item){
	$output .="<tr>";
	$output .="<tr class='".$calculations[$item["id"]]['Status']."'>";
	$output .="<td>".round($item["ranks"],1)."</td>";
	$output .="<td><a href='viewgame.php?id=".$item["id"]."'>".$calculations[$item["id"]]["Title"]."</a></td>";
	$output .="<td>";
	if(isset($item["metastatname"])){
		$output .=$topxobj->getHeaderText($item["metastatname"]);
	}
	$output .="</td>";
	$output .="<td>";
	if(isset($item["metastatname"])){
		$output .=$topxobj->statformat($calculations[$item["id"]][$item["metastatname"]],$item["metastatname"]);
	}
	$output .="</td>";
	$output .="</tr>";
}
$output .="</tbody>";
$output .="</table>";

echo "<table><tr><td valign=top>";
echo $output;
echo "</td><td valign=top><details><summary><b>Details</b></summary>";
echo $output2;
echo "</summary></td></tr></table>";

echo Get_Footer(); 


?>