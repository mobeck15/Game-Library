<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/template.inc.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php";

class activityPage extends Page
{
	public function __construct() {
		$this->title="Activity";
	}
	
	public function buildHtmlBody(){
		$conn=get_db_connection();

		$settings=getsettings($conn);
		$History=getHistoryCalculations("",$conn);
		$activity=getActivityCalculations("",$History,$conn);
		$conn->close();	

		$this->body='<table>
<thead>
<tr>
<th>Games</th>
<th>First Play</th>
<th>Last Play</th>
<th class="hidden">Last time</th>
<th class="hidden">Total Hrs</th>
<th>Achievements</th>
<th>Status</th>
<th>Last Rating</th>
<th>Last Beat</th>
<th class="hidden">Base Game</th>
<th class="hidden">Launch Date</th>
<th>Grand Total</th>
<th class="hidden">Week Play</th>
<th class="hidden">Month Play</th>
<th class="hidden">Year Play</th>
<th class="hidden">Week Achievements</th>
<th class="hidden">Month Achievements</th>
<th class="hidden">Year Achievements</th>
<th class="hidden">Add</th>
</tr>
</thead>
<tbody>'."\n\n";

		foreach ($activity as $totals) { 
			$this->body .= "<tr class='" . $totals['Status'] . "'>\n";
			$this->body .= "<td class='text'><a href='viewgame.php?id=" . $totals['ID'] . "' target='_blank'>" . $totals['Games'] . "</a></td>\n";
			$this->body .= "<td class='numeric'>" . $totals['firstplay'] . "</td>\n";
			$this->body .= "<td class='numeric'>" . $totals['lastplay'] . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . timeduration($totals['elapsed'],"seconds") . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . timeduration($totals['totalHrs'],"seconds") . "</td>\n";
			$this->body .= "<td class='numeric'>" . $totals['Achievements'] . "</td>\n";
			$this->body .= "<td class='text'>" . $totals['Status'] . "</td>\n";
			$this->body .= "<td class='numeric'>" . $totals['Review'] . "</td>\n";
			$this->body .= "<td class='numeric'>" . $totals['LastBeat'] . "</td>\n";
			$this->body .= "<td class='hidden numeric'><a href='viewgame.php?id=" . $totals['Basegame'] . "'>" . $totals['Basegame'] . "</a></td>\n";
			$this->body .= "<td class='hidden numeric'>" . $totals['LaunchDate'] . "</td>\n";
			$this->body .= "<td class='numeric'>" . timeduration($totals['GrandTotal'],"seconds") . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . timeduration($totals['weekPlay'],"seconds") . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . timeduration($totals['monthPlay'],"seconds") . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . timeduration($totals['yearPlay'],"seconds") . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . $totals['WeekAchievements'] . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . $totals['MonthAchievements'] . "</td>\n";
			$this->body .= "<td class='hidden numeric'>" . $totals['YearAchievements'] . "</td>\n";
			$this->body .= "<td class='hidden numeric'><a href='addhistory.php?GameID=" . $totals['ID'] . "' target='_blank'>Add</a></td>\n";
			$this->body .= "</tr>\n";
			
			//var_dump($totals);
			//echo "<br><br>";
			unset($totals);
		}
		
		$this->body.="</tbody>
</table>\n";
		return $this->body;
	}
}
