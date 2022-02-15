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

//$filter = $topxobj->filterlist();

$totalranks=array();
$output2="";
foreach ($topxobj->statlist() as $stat) {
	$list = $topxobj->gettopx($stat);
	foreach ($list as $key => $item){
		$totalranks[$item]["ranks"] = ($totalranks[$item]["ranks"] ?? 0) + count($list)-$key;
		$sortranks[$item]=$totalranks[$item]["ranks"];
		$totalranks[$item]["id"]=$item;
	}
	$output2 .= $topxobj->displaytop($list,$stat);
}

array_multisort($sortranks, SORT_DESC, $totalranks);		

$output  ="";
$output .="<table>";
$output .="<thead><tr><th>Ranks</th><th>Title</th></tr></thead>";
$output .="<tbody>";
foreach($totalranks as $item){
	$output .="<tr>";
	$output .="<tr class='".$calculations[$item["id"]]['Status']."'>";
	$output .="<td>".$item["ranks"]."</td>";
	$output .="<td><a href='viewgame.php?id=".$item["id"]."'>".$calculations[$item["id"]]["Title"]."</a></td>";
	$output .="</tr>";
}
$output .="</tbody>";
$output .="</table>";

echo "<table><tr><td width=300 valign=top>";
echo $output;
echo "</td><td valign=top>";
echo $output2;
echo "</td></tr></table>";

echo Get_Footer(); 


?>