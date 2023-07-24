<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";
include_once $GLOBALS['rootpath']."/inc/SteamAPI.class.php";

class steamapi_ownedgamesPage extends Page
{
	private $dataAccessObject;
	public function __construct() {
		$this->title="Steam API All Games";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();

$hitory=getHistoryCalculations("",$conn);
$games=getCalculations("",$conn);
$steamindex=makeIndex($games,"SteamID");
$conn->close();

foreach($hitory as $historyrow){
	if($historyrow['System']=="Steam"){
		$lastrecord[$historyrow['GameID']]=$historyrow;
		if ($historyrow['BaseGame']==1){
			$lastrecord[$historyrow['ParentGameID']]=$historyrow;
		}
	}
}
//print_r($historyrow);

//$resultarray=GetOwnedGames();
$steamAPI= new SteamAPI();
$resultarray=$steamAPI->GetSteamAPI("GetOwnedGames");
	$output .= "<table>
	<thead>
	<tr>
	<th rowspan=2>Title</th>
	<th colspan=2>Playtime Forever</th>
	<th colspan=2>Playtime two weeks</th>
	<th rowspan=2>Total Time</th>
	<th colspan=4>Last Time Entry</th>
	</tr>
	<tr>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Minutes</th>
	<th style='top:77px;'>Hours</th>
	<th style='top:77px;'>Time</th>
	<th style='top:77px;'>Keywords</th>
	</tr>
	</thead>
	<tbody>";

	$missing_count=0;
	$missing_ids=array();
	
	
	foreach ($resultarray['response']['games'] as $key => $row) {
		$Sortby1[$key]  = $row['playtime_forever'];
	}
	array_multisort($Sortby1, SORT_DESC, $resultarray['response']['games']);

	foreach($resultarray['response']['games'] as $row){
		$rowclass="unknown";
		if(isset($steamindex[$row['appid']])){
			$thisgamedata=$games[$steamindex[$row['appid']]];
		}
		if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']]['Time'])) {
			if(round($lastrecord[$thisgamedata['Game_ID']]['Time']*60)==round($row['playtime_forever'])) {
				$rowclass="greenRow";
				//$rowclass="hidden";
			} else {
				$rowclass="redRow";
			}
		} 
		
		if ($row['playtime_forever']==0){
			$rowclass="hidden";
		}
		$output .= "<tr class='$rowclass'>";
		if(isset($steamindex[$row['appid']])){
			//$thisgamedata=$games[$steamindex[$row['appid']]];
			$output .= "<td>
			<a href='addhistory.php?GameID=" . $thisgamedata['Game_ID']."' target='_blank'>+</a>";
			if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']]['Time'])) { 
				$output .= "<a href='addhistory.php?HistID=". $lastrecord[$thisgamedata['Game_ID']]['HistoryID']."' target='_blank'>edit last</a> ";
			}
			
			$output .= "<a href='http://store.steampowered.com/app/". $row['appid']."'>". $thisgamedata['Title']."</a>
			</td>";
		} else {
			$output .= "<td>&nbsp;&nbsp;&nbsp;<a href='http://store.steampowered.com/app/". $row['appid']."'>MISSING</a></td>";
			$missing_count++;
			$missing_ids[]=$row['appid'];
			unset($thisgamedata);
		}
		$output .= '<td>'. $row['playtime_forever'].'</td>
		<td>'. round($row['playtime_forever']/60,1).'</td>';
		if(isset($row['playtime_2weeks'])){
			$output .= '<td>'. $row['playtime_2weeks'].'</td>
			<td>'. round($row['playtime_2weeks']/60,1).'</td>';
		} else {
			$output .= '<td></td>
			<td></td>';
		}
		if (isset($thisgamedata)){
			$output .= '<td>'. timeduration($thisgamedata['GrandTotal'],"seconds").'</td>';
		} else {
			$output .= '<td></td>';
		} 

		if (isset($thisgamedata) && isset($lastrecord[$thisgamedata['Game_ID']])) { 
			$output .= '<td>'. ($lastrecord[$thisgamedata['Game_ID']]['Time']*60).'</td>
			<td>'. round(($lastrecord[$thisgamedata['Game_ID']]['Time']+0),1).'</td>
			<td>'. timeduration((float)$lastrecord[$thisgamedata['Game_ID']]['Time'],"hours").'</td>
			<td>'. $lastrecord[$thisgamedata['Game_ID']]['KeyWords'].'</td>';
		} else {
			$output .= '<td></td>
			<td></td>
			<td></td>
			<td></td>';
		}
		
		//$output .= '<td class="hidden">'. print_r(($thisgamedata ?? null),true).' </td></tr>';
	}
	
	$output .= "</tbody>
	</table>

	<br>
	
	Missing $missing_count Games from library
	<table border=0>
	<thead>
	<tr>
	<th>Store</th>
	<th>DB</th>
	<th>ID</th>
	<th>Title</th>
	</tr>
	</thead>
	<tbody>";
	
	foreach($missing_ids as $id) {
		
		//$schemaresultarray=GetSchemaForGame($id);
		$steamAPI= new SteamAPI($id);
		$schemaresultarray=$steamAPI->GetSteamAPI("GetSchemaForGame");
		
		//$appdetails=GetAppDetails($id);
		//$stats=GetUserStatsForGame($id);
		//$playerach=GetPlayerAchievements($id);
		$output .= "<tr>
		
		<td><a href='http://store.steampowered.com/app/". $id."' target='_blank'>Steam Store</a></td>
		<td><a href='https://steamdb.info/app/". $id."' target='_blank'>Steam DB</a></td>
		<td>". $id."</td>";
		if (isset($schemaresultarray['game']['gameName'])) {
			$output .= "<td>". $schemaresultarray['game']['gameName']."</td>";
		} else { 
		//GetAppDetails($game['SteamID'])
		//$appdetails['data']['name']
		//CANCELLED: get the real name of the game somehow. All API options seem ineffective. 
			$output .= "<td>Removed from Steam</td>";
		}
		//$output .= '<td class="hidden">'. print_r($schemaresultarray,true).' </td>';
		//$output .= '<td class="hidden">'. print_r($appdetails,true).' </td>';
		//$output .= '<td class="hidden">'. print_r($stats,true).' </td>';
		//$output .= '<td class="hidden">'. print_r($playerach,true).' </td>';
		$output .= '</tr>';
	 }
	$output .= "</tbody>
	</table>";
		return $output;
	}
}	