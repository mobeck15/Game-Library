<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";

class viewallhistoryPage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="View All History";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();

if(!isset($_GET['num'])) {
	//TODO: add function to allow date range selection
	//TODO: add pageination
	$output .= '<form method="Get" >
	How Many: <input type="number" name="num" value=30> <br>
	Sort By: <select name="Sort">
		<option value="Played">Played</option>
		<option value="Purchased">Purchased</option>
		<option value="Days">Days</option>
	</select></br>
	<input type="checkbox" name="Free" value="Free"> Count Free Games</br>
	<input type="checkbox" name="Never" value="Never"> Count Never</br>
	<input type="checkbox" name="Beat" value="Beat"> Count Beat</br>
	<input type="submit" value="Submit">
	</form>';
} else {
	//var_dump($_GET);
	
	$settings=getsettings($conn);
	$historytable=getHistoryCalculations("",$conn);
	$output .= '<table>
	<thead>
	<tr>
	<th class="hidden">ID</th>
	<th>Timestamp</th>
	<th>Game</th>
	<th>System</th>
	<th>Data</th>
	<th>Time</th>
	<th>Notes</th>
	<th class="hidden">Achievements</th>
	<th class="hidden">Achievement Type</th>
	<th class="hidden">Levels</th>
	<th class="hidden">Level Type</th>
	<th class="hidden">Status</th>
	<th class="hidden">Review</th>
	<th>Keywords</th>
	<th class="hidden">Row Type</th>
	<th class="hidden">Previous Start</th>
	<th>Elapsed</th>
	<th class="hidden">Prev Total (System)</th>
	<th>Total (System)</th>
	<th class="hidden">Prev Total</th>
	<th>Total</th>
	<th>Final Status</th>
	<th>Final Rating</th>
	<th class="hidden">Count Game</th>
	<th class="hidden">Base Game</th>
	<th class="hidden">Launch Date</th>
	<th class="hidden">Final Count All</th>
	<th>Final Count Hours</th>
	<th>Use Game</th>
	</tr>
	</thead>
	<tbody>';
	
	if($_GET['Sort']=="Played") {
		$sortby="Timestamp";

		foreach ($historytable as $key => $row) {
			$sortarray[$key]  = strtotime($row["Timestamp"]);
		}
		
		array_multisort($sortarray, SORT_DESC, $historytable);
	}
	
	
	$index=1;
	foreach ($historytable as $row) {
		if($index>$_GET['num']) {break;}
		if ($row['Data']<>"Start/Stop") {$index++;}
		$output .= '<tr  class="'. $row['FinalStatus'].'">
		<td class="hidden numeric"><a href="addhistory.php?HistID='. $row['HistoryID'].'" target=_blank>'. $row['HistoryID'].'</a></td>
		<td class="numeric"><a href="addhistory.php?HistID='. $row['HistoryID'].'" target=_blank>'. str_replace(" ", "&nbsp;", $row['Timestamp']).'</a></td>
		<td class="text"><a href="viewgame.php?id='. $row['GameID'].'">'. $row['Game'].'</a></td>
		<td class="text">'. str_replace(" ", "&nbsp;", $row['System']).'</td>
		<td class="text">'. str_replace(" ", "&nbsp;", $row['Data']).'</td>
		<td class="numeric">'. timeduration($row['Time'],"hours").'</td>
		<td class="text">'. nl2br($row['Notes']).'</td>
		<td class="hidden numeric">'. $row['Achievements'].'</td>
		<td class="hidden text">'. $row['AchievementType'].'</td>
		<td class="hidden numeric">'. $row['Levels'].'</td>
		<td class="hidden text">'. $row['LevelType'].'</td>
		<td class="hidden text">'. $row['Status'].'</td>
		<td class="hidden numeric">'. $row['Review'].'</td>
		<td class="text">'. $row['KeyWords'].'</td>
		<td class="hidden text">'. $row['RowType'].'</td>
		<td class="hidden numeric">';
		if( isset($row['prevstart'])) {
			$output .= date("n/j/Y H:i:s",$row['prevstart']);
		}
		$output .= '</td>
		<td class="numeric">'. timeduration($row['Elapsed'],"seconds").'</td>
		<td class="hidden numeric">'. timeduration($row['prevTotSys'],"seconds").'</td>
		<td class="numeric">'. timeduration($row['totalSys'],"seconds").'</td>
		<td class="hidden numeric">'. timeduration($row['prevTotal'],"seconds").'</td>
		<td class="numeric">'. timeduration($row['Total'],"seconds").'</td>
		<td class="text">'. str_replace(" ", "&nbsp;", $row['FinalStatus']).'</td>
		<td class="numeric">'. $row['finalRating'].'</td>
		<td class="hidden text">'. $row['Count'].'</td>
		<td class="hidden numeric"><a href="viewgame.php?id='. $row['ParentGameID'].'">'. $row['ParentGameID'].'</a></td>
		<td class="hidden numeric">'. $row['LaunchDate'].'</td>
		<td class="hidden text">'. $row['FinalCountAll'].'</td>
		<td class="text">'. boolText($row['FinalCountHours']).'</td>
		<td class="numeric"><a href="viewgame.php?id='. $row['UseGame'].'">'. $row['UseGame'].'</a></td>
		</tr>';
		//var_dump($row);
	}
	$output .= '</tbody>
	</table>';
 }
		return $output;
	}
}	