<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";

class viewallhistoryPage extends Page
{
	
	private $settings;
	private $historytable;
	private $dataAccessObject;
	
	public function __construct() {
		$this->title="View All History";
	}
	
	private function getHistory(){
		if(!isset($this->historytable)){
			$this->historytable = getHistoryCalculations();
		}
		return $this->historytable;
	}
	
	private function getSettings(){
		if(!isset($this->settings)){
			$this->settings = getsettings();
		}
		return $this->settings;
	}
	
	private function prompt(){
		//TODO: add function to allow date range selection
		//TODO: add pageination
		$output = '<form method="Get" >
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
		
		return $output;
	}
	
	private function tableHeader(){
		$header = '<thead><tr>';
		$header .= '<th class="hidden">ID</th>';
		$header .= '<th>Timestamp</th>';
		$header .= '<th>Game</th>';
		$header .= '<th>System</th>';
		$header .= '<th>Data</th>';
		$header .= '<th>Time</th>';
		$header .= '<th>Notes</th>';
		//$header .= '<th class="hidden">Achievements</th>';
		//$header .= '<th class="hidden">Achievement Type</th>';
		//$header .= '<th class="hidden">Levels</th>';
		//$header .= '<th class="hidden">Level Type</th>';
		//$header .= '<th class="hidden">Status</th>';
		//$header .= '<th class="hidden">Review</th>';
		$header .= '<th>Keywords</th>';
		//$header .= '<th class="hidden">Row Type</th>';
		//$header .= '<th class="hidden">Previous Start</th>';
		$header .= '<th>Elapsed</th>';
		//$header .= '<th class="hidden">Prev Total (System)</th>';
		$header .= '<th>Total (System)</th>';
		//$header .= '<th class="hidden">Prev Total</th>';
		$header .= '<th>Total</th>';
		$header .= '<th>Final Status</th>';
		$header .= '<th>Final Rating</th>';
		//$header .= '<th class="hidden">Count Game</th>';
		//$header .= '<th class="hidden">Base Game</th>';
		//$header .= '<th class="hidden">Launch Date</th>';
		//$header .= '<th class="hidden">Final Count All</th>';
		$header .= '<th>Final Count Hours</th>';
		$header .= '<th>Use Game</th>';
		$header .= '</tr></thead>';
		
		return $header;
	}
	
	private function sortHistory($sortykey){
		$sortby="Timestamp"; //Default
		/* 
		if($sortykey == "Played"){
			$sortby="Timestamp";
		}
		if($sortykey == "Purchased"){
			$sortby="Timestamp";
		}
		if($sortykey == "Days"){
			$sortby="Timestamp";
		} */
		
		$arrayToSort = $this->getHistory();
		
		foreach ($arrayToSort as $key => $row) {
			$sortarray[$key]  = strtotime($row["Timestamp"]);
		}
		
		array_multisort($sortarray, SORT_DESC, $arrayToSort);
		
		$this->historytable = $arrayToSort;
	}
	
	private function buildHistoryTable($maxRow){
		$output = '<table>';
		$output .= $this->tableHeader();
		$output .= '<tbody>';
		
		$index=1;
		foreach ($this->getHistory() as $row) {
			if($index>$maxRow) {break;}
			if ($row['Data']<>"Start/Stop") {$index++;}
			$output .= $this->makeDataRow($row);
		}
		$output .= '</tbody>
		</table>';
		
		return $output;
	}
	
	private function makeDataRow($row){
		$output = '<tr  class="'. $row['FinalStatus'].'">';
		//$output .= $this->makeDataCell("numeric",'<a href="addhistory.php?HistID='. $row['HistoryID'].'" target=_blank>'. $row['HistoryID'].'</a>');
		$output .= $this->makeDataCell("numeric",'<a href="addhistory.php?HistID='. $row['HistoryID'].'" target=_blank>'. str_replace(" ", "&nbsp;", $row['Timestamp']).'</a>');
		$output .= $this->makeDataCell("text",'<a href="viewgame.php?id='. $row['GameID'].'">'. $row['Game'].'</a>');
		$output .= $this->makeDataCell("text",str_replace(" ", "&nbsp;", $row['System']));
		$output .= $this->makeDataCell("text",str_replace(" ", "&nbsp;", $row['Data']));
		$output .= $this->makeDataCell("numeric",timeduration($row['Time'],"hours"));
		$output .= $this->makeDataCell("text",nl2br($row['Notes']??""));
		//$output .= $this->makeDataCell("numeric",$row['Achievements']);
		//$output .= $this->makeDataCell("text",$row['AchievementType']);
		//$output .= $this->makeDataCell("numeric",$row['Levels']);
		//$output .= $this->makeDataCell("text",$row['LevelType']);
		//$output .= $this->makeDataCell("text",$row['Status']);
		//$output .= $this->makeDataCell("numeric",$row['Review']);
		$output .= $this->makeDataCell("text",$row['KeyWords']);
		//$output .= $this->makeDataCell("text",$row['RowType']);
		//$output .= $this->makeDataCell("numeric",(($row['prevstart']??null)==null ? "" : date("n/j/Y H:i:s",$row['prevstart'])));
		$output .= $this->makeDataCell("numeric",timeduration($row['Elapsed'],"seconds"));
		//$output .= $this->makeDataCell("numeric",timeduration($row['prevTotSys'],"seconds"));
		$output .= $this->makeDataCell("numeric",timeduration($row['totalSys'],"seconds"));
		//$output .= $this->makeDataCell("numeric",timeduration($row['prevTotal'],"seconds"));
		$output .= $this->makeDataCell("numeric",timeduration($row['Total'],"seconds"));
		$output .= $this->makeDataCell("text",str_replace(" ", "&nbsp;", $row['FinalStatus']));
		$output .= $this->makeDataCell("numeric",$row['finalRating']);
		//$output .= $this->makeDataCell("text",$row['Count']);
		//$output .= $this->makeDataCell("numeric",'<a href="viewgame.php?id='. $row['ParentGameID'].'">'. $row['ParentGameID'].'</a>');
		//$output .= $this->makeDataCell("numeric",$row['LaunchDate']);
		//$output .= $this->makeDataCell("text",$row['FinalCountAll']);
		$output .= $this->makeDataCell("text",boolText($row['FinalCountHours']));
		$output .= $this->makeDataCell("numeric",'<a href="viewgame.php?id='. $row['UseGame'].'">'. $row['UseGame'].'</a>');
		$output .= '</tr>';
		
		return $output;
	}
	
	private function makeDataCell($datatype,$value){
		return "<td class='$datatype'>$value</td>";
	}
	
	public function buildHtmlBody(){
		$output="";
		
		if(!isset($_GET['num'])) {
			$output .= $this->prompt();
		} else {
			$this->sortHistory($_GET["Sort"]);
			$output .= $this->buildHistoryTable($_GET['num']);
		}
		return $output;
	}
}	