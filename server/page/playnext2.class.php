<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/topx.class.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";

class playnext2Page extends Page
{
	public function __construct() {
		$this->title="Play Next";
	}
	
	public function buildHtmlBody(){
		$output="";
		
		$topxobj = new topx(reIndexArray(getCalculations(),"Game_ID"));

		if(isset($_GET["mode"]) && $_GET["mode"] == "Active"){
			$topxobj->setfilter("Playable,eq,0,Status,ne,Active");
		}

		$totalranks=$topxobj->getTotalRanks();

		$output .= "<a href='?mode=Active'>Active</a> | <a href='?'>All</a>";
		$output .= "<table><tr><td valign=top>";
		$output .= $topxobj->makeDetailTable($totalranks);
		$output .= "</td><td valign=top><details><summary><b>Details</b></summary>";
		$output .= $topxobj->makeSourceCloud("main");
		$output .= "</summary></td></tr></table>";

		return $output;
	}
}	