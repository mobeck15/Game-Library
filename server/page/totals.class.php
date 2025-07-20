<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getPurchases.class.php";

class totalsPage extends Page
{
	public function __construct() {
		$this->title="Totals";
	}
	
	public function buildHtmlBody(){
		$output="";
		
	$conn=get_db_connection();
	$purchaseobj=new Purchases("",$conn);
	$purchases=$purchaseobj->getPurchases();
	$conn->close();	

	$settings=$this->data()->getSettings();
	$calculations=$this->data()->getCalculations();

	$Totals['Played']=
	$Totals['UnPlayed']=
	$Totals['hours']=
	$Totals['value']=
	$Totals['100ach']=
	$Totals['Achievements']=
	$Totals['mostplaytime']=
	$Totals['record']=
	$Totals['Played2']['Done']=
	$Totals['Played2']['Broken']=
	$Totals['Played2']['Never']=
	$Totals['Played2']['free']=
	$Totals['Played2']['Started']=
	$Totals['UnPlayed2']['free']=
	$Totals['UnPlayed2']['ach']=
	$Totals['UnPlayed2']['Steam']=
	$Totals['UnPlayed2']['Other']=
	$Totals['paid']=
	0;
	
	//This total is different from the one in Calendar
	foreach($purchases as $item){
		//var_dump($item); break;
		$Totals['paid']+=$item['Paid'];
	}
	
	foreach($calculations as $key => $row){
		if($row['Playable']==1){
			//var_dump($row); break;
			//$allcount++;
			
			if($row['CountGame']==1  ){
				if($row['GrandTotal']>0){
					$Totals['hours']+=$row['totalHrs'];
					$Totals['Played']++;
					
					if($row['SteamAchievements']>0 && $row['Achievements']==$row['SteamAchievements']){
						$Totals['100ach']++;
					}
					
					if($row['GrandTotal']>$Totals['mostplaytime']){
						$Totals['mostplaytime']=$row['GrandTotal'];
						$Totals['record']=$key;
					}
				} else {
					$Totals['UnPlayed']++;
				}
				$Totals['value']+=$row['MSRP'];
				$Totals['Achievements']+=$row['Achievements'];
			}
			
			if($row['Status']=="Done" && $row['GrandTotal']>0){
				$Totals['Played2']['Done']++;
			}elseif($row['Status']=="Broken"){
				$Totals['Played2']['Broken']++;
			}elseif($row['Status']=="Never"){
				$Totals['Played2']['Never']++;
			}elseif($row['Paid']<=0 && $row['GrandTotal']>0){
				$Totals['Played2']['free']++;
			}elseif($row['GrandTotal']>0){
				$Totals['Played2']['Started']++;
			}elseif($row['Paid']<=0 && $row['GrandTotal']==0){
				$Totals['UnPlayed2']['free']++;
			}elseif($row['SteamAchievements']>0){
				$Totals['UnPlayed2']['ach']++;
			}elseif(in_array ("Steam",$row['DRM'])){
				$Totals['UnPlayed2']['Steam']++;
			}elseif ($row['CountGame']==1) {
				$Totals['UnPlayed2']['Other']++;
			}
		}
	}
	
	//$output .= "All count: " . $allcount;
	
	$Totals['Played2']['Done']-=$Totals['100ach'];
/*	$Totals['Played2']['Started']=$Totals['Played']
		-$Totals['Played2']['Done']
		-$Totals['Played2']['Broken']
		-$Totals['Played2']['Never']
		-$Totals['Played2']['free']
		-$Totals['100ach'];
*/
	
	$Totals['Count']=$Totals['Played'] + $Totals['UnPlayed'];
	$Totals['percentPlayed'] = ($Totals['Count']==0 ? 0 : $Totals['Played']/$Totals['Count']);
	$Totals['percentUnPlayed'] = ($Totals['Count']==0 ? 0 : $Totals['UnPlayed']/$Totals['Count']);
	$output .= '<table><tr><td>
	<table>
	<thead>
	</thead>
	<tbody>
	<tr>
	<th>Total Games</th>
	<td class="numeric">'. $Totals['Count'].'</td>
	</tr>
	<tr>
	<th>Total Hours</th>
	<td class="numeric">'. timeduration($Totals['hours'],"seconds").'</td>
	</tr>
	<tr>
	<th>Paid</th>
	<td class="numeric">$'. number_format($Totals['paid'],2).'></td>
	</tr>
	<tr>
	<th>Value</th>
	<td class="numeric">$'. number_format($Totals['value'],2).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<td class="numeric">'. $Totals['Played'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", $Totals['percentPlayed'] * 100).'</td>
	<td class="numeric">'. ceil($Totals['percentPlayed'] * 100).'%</td>
	<td class="numeric">'. (ceil($Totals['Count']*(ceil($Totals['percentPlayed'] * 100)/100))-$Totals['Played']).'</td>
	</tr>
	<tr>
	<th>UnPlayed</th>
	<td class="numeric">'. $Totals['UnPlayed'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", $Totals['percentUnPlayed'] * 100).'</td>
	<td class="numeric">1%=</td>
	<td class="numeric">'. ceil($Totals['Count'] * .01).'</td>
	</tr>
	<tr>
	<th>Games 100% Achievements</th>
	<td class="numeric">'. $Totals['100ach'].'</td>
	</tr>
	<tr>
	<th>Total Achievements</th>
	<td class="numeric">'. $Totals['Achievements'].'</td>
	</tr>
	<tr>
	<th>Most Played Game</th>
	<td class="text"><a href="viewgame.php?id='. $calculations[$Totals['record']]['Game_ID'].'" target="_blank">'. $calculations[$Totals['record']]['Title'].'</a></td>
	</tr>
	<tr>
	<th>Hours for Most Played Game</th>
	<td class="numeric">'. timeduration($Totals['mostplaytime'],"seconds").'</td>
	</tr>
	<tr>
	<th>% of total play time for most played game</th>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['hours']==0 ? 0 : $Totals['mostplaytime']/$Totals['hours']) * 100).'</td>
	</tr>
	</tbody>
	</table>
	
	</td><td>
	
	<table>
	<thead>
	</thead>
	<tbody>
	<tr>
	<th>Played</th>
	<th>100% ach</th>
	<td class="numeric">'. $Totals['100ach'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['100ach']==0 ? 0 : $Totals['100ach']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<th>DONE</th>
	<td class="numeric">'. $Totals['Played2']['Done'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['Played2']['Done']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<th>Started</th>
	<td class="numeric">'. $Totals['Played2']['Started'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['Played2']['Started']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<th>BROKEN</th>
	<td class="numeric">'. $Totals['Played2']['Broken'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['Played2']['Broken']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<th>NEVER</th>
	<td class="numeric">'. $Totals['Played2']['Never'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['Played2']['Never']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>Played</th>
	<th>Free</th>
	<td class="numeric">'. $Totals['Played2']['free'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['Played2']['free']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>UnPlayed</th>
	<th>Free</th>
	<td class="numeric">'. $Totals['UnPlayed2']['free'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['UnPlayed2']['free']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>UnPlayed</th>
	<th>Has Ach</th>
	<td class="numeric">'. $Totals['UnPlayed2']['ach'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['UnPlayed2']['ach']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>UnPlayed</th>
	<th>Steam</th>
	<td class="numeric">'. $Totals['UnPlayed2']['Steam'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['UnPlayed2']['Steam']/$Totals['Count']) * 100).'</td>
	</tr>
	<tr>
	<th>UnPlayed</th>
	<th>Other</th>
	<td class="numeric">'. $Totals['UnPlayed2']['Other'].'</td>
	<td class="numeric">'. sprintf("%.2f%%", ($Totals['Count']==0 ? 0 : $Totals['UnPlayed2']['Other']/$Totals['Count']) * 100).'</td>
	</tr>
	</tbody>
	</table>
	
	</td></tr>';
		/***** Dynamic Chart *****/
	//$output .= $GoogleChartDataString;
		//$GoogleChartDataString="";
	/* */
	      $GoogleChartDataString="
          ['Category', 'Count of Games'],
          ['Played',	".$Totals['Played']."],
          ['UnPlayed',	".$Totals['UnPlayed']."]
		  ";
	/* */
	      $GoogleChartDataString2="
          ['Category', 'Count of Games'],
          ['100% Achievements',	".$Totals['100ach']."],
          ['Done',	".$Totals['Played2']['Done']."],
          ['Started',	".$Totals['Played2']['Started']."],
          ['Broken',	".$Totals['Played2']['Broken']."],
          ['Never',	".$Totals['Played2']['Never']."],
          ['Played Free',	".$Totals['Played2']['free']."],
          ['UnPlayed Free',	".$Totals['UnPlayed2']['free']."],
          ['UnPlayed Has Achievements',	".$Totals['UnPlayed2']['ach']."],
          ['UnPlayed Steam',	".$Totals['UnPlayed2']['Steam']."],
          ['UnPlayed Other',	".$Totals['UnPlayed2']['Other']."]
		  ";
	/* */
	//Cancelled: Update charts to use CSS background colors. (or transparent) - Solved but does not look good.
$output .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">';
      $output .= "google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
		". $GoogleChartDataString."
        ]);

        var data2 = google.visualization.arrayToDataTable([
		". $GoogleChartDataString2."
        ]);
		
        var options = {
          title: 'Played Games',
		  //backgroundColor: '#924136'
		  //backgroundColor:  { fill:'transparent' }
		 //,legend.textStyle: { color: '#FFFFFF' }
		 //,titleTextStyle:   { color: '#FFFFFF' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
        chart2.draw(data2, options);
      }
    </script>";
	
	$output .= '<tr><td>
	<div id="piechart" style="width: 900px; height: 500px;"></div>
	</td><td>
	<div id="piechart2" style="width: 900px; height: 500px;"></div>
	</td></tr></table>';
		return $output;
	}
}	