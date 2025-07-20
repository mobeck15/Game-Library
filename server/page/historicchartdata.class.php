<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php";

class historicchartdataPage extends Page
{
	public function __construct() {
		$this->title="Historic Charts & Data";
	}
	
	public function buildHtmlBody(){
		$output="";

$conn=get_db_connection();

$settings=getsettings($conn);
$History=getHistoryCalculations("",$conn);
$conn->close();	
//var_dump($History);
//var_dump($settings);
//var_dump(date("m/d/Y",$settings['StartStats']));

//TODO: $firstweek does nothing?
//$firstweek="8/22/13";
//$firstweek=date("m/d/Y",$settings['StartStats']);
$lastdate=0;

foreach($History as $row){
	$timestamp=strtotime($row['Timestamp']);
	$datestamp=strtotime(date("Y-m-d",$timestamp));
	//var_dump(date("M/d/Y",$timestamp));
	
	//TODO: Add in calculations so this can also exclude counts of games that later are broken or Never
	if($row['Elapsed']>0 && $row['FinalCountHours']==True){
		//var_dump($row);break;
		$dateData[$datestamp]['Sort']=$datestamp;
		$dateData[$datestamp]['Date']=strtotime(date("Y-m-d",$datestamp));
		if(!isset($dateData[$datestamp]['Time'])){
			$dateData[$datestamp]['Time']=0;
		}
		$dateData[$datestamp]['Time']+=$row['Elapsed'];
		
		//TODO: Weekstamp may still be wrong, need to do some debug runs to make sure it is only catching data from the following week.
		//$weekstamp=strtotime(date("Y-m-d",$datestamp-(date("w",$datestamp)*60*60*24)));
		$day = date("w",$datestamp);
		//$dateData[$datestamp]['weekstamp']=
		$weekstamp = strtotime('-'.$day.' days',$datestamp);
		/*
		if (date("w",$weekstamp)<>0){
			$error="Week Stamp out of sync: ";
			$error .= date("U l(w) Y-m-d h:i:s",$weekstamp);
			$error .= " TimeStamp: ". date("U l(w) Y-m-d h:i:s",$datestamp);
			$error .= "<br>{Math: " . date("w",$datestamp) . " * 60 = ". date("w",$datestamp)*60;
			$error .= " * 60 = " . date("w",$datestamp)*60*60;
			$error .= " * 24 = " . date("w",$datestamp)*60*60*24;
			$error .= "}";
			trigger_error($error);
		}
		*/
		$dateData[$weekstamp]['Sort']=$weekstamp;
		if(!isset($dateData[$weekstamp]['WeekTime'])){
			$dateData[$weekstamp]['WeekTime']=0;
			//$dateData[$weekstamp]['WeekDayCount']=0;
		} else {
			$lastweek=$weekstamp;
		}
		$dateData[$weekstamp]['WeekTime']+=$row['Elapsed'];
		$dateData[$weekstamp]['Date']=strtotime(date("Y-m-d",$weekstamp));
		if(!isset($dateData[$weekstamp]['Time'])){
			$dateData[$weekstamp]['Time']=0;
		}
		
		//$monthstamp=$datestamp-(date("d",$datestamp)-1)*60*60*24;
		$monthstamp=strtotime((date("Y",$datestamp))."-".(date("m",$datestamp))."-1");
		$dateData[$monthstamp]['Sort']=$monthstamp;
		if(!isset($dateData[$monthstamp]['MonthTime'])){
			$dateData[$monthstamp]['MonthTime']=0;
			//$dateData[$monthstamp]['MonthDayCount']=0;
			$currentmonthtime=0;
		} else {
			$lastmonth=$monthstamp;
		}
		$dateData[$monthstamp]['MonthTime']+=$row['Elapsed'];
		$currentmonthtime+=$row['Elapsed'];
		$dateData[$monthstamp]['Date']=strtotime(date("Y-m-d",$monthstamp));
		if(!isset($dateData[$monthstamp]['Time'])){
			$dateData[$monthstamp]['Time']=0;
		}
		
		//$yearstamp=$datestamp-(date("z",$datestamp)-1)*60*60*24;
		//$dateData[$datestamp]['yearstamp']=
		$yearstamp=strtotime((date("Y",$datestamp))."-1-1");
		$dateData[$yearstamp]['Sort']=$yearstamp;
		if(!isset($dateData[$yearstamp]['YearTime'])){
			$dateData[$yearstamp]['YearTime']=0;
			//$dateData[$yearstamp]['YearDayCount']=0;
		} else {
			$lastyear=$yearstamp;
		}
		$dateData[$yearstamp]['YearTime']+=$row['Elapsed'];
		$dateData[$yearstamp]['Date']=strtotime(date("Y-m-d",$yearstamp));
		if(!isset($dateData[$yearstamp]['Time'])){
			$dateData[$yearstamp]['Time']=0;
		}
		
		if(!isset($dateData[$datestamp]['weekstamp'])){
			$dateData[$datestamp]['weekstamp']=$weekstamp;
		}
		if(!isset($dateData[$weekstamp]['weekstamp'])){
			$dateData[$weekstamp]['weekstamp']=$weekstamp;
		}
		if(!isset($dateData[$monthstamp]['weekstamp'])){
			$dateData[$monthstamp]['weekstamp']=$weekstamp;
		}
		if(!isset($dateData[$yearstamp]['weekstamp'])){
			$dateData[$yearstamp]['weekstamp']=$weekstamp;
		}
		if(!isset($dateData[$datestamp]['monthstamp'])){
			$dateData[$datestamp]['monthstamp']=$monthstamp;
		}
		if(!isset($dateData[$weekstamp]['monthstamp'])){
			$dateData[$weekstamp]['monthstamp']=$monthstamp;
		}
		if(!isset($dateData[$monthstamp]['monthstamp'])){
			$dateData[$monthstamp]['monthstamp']=$monthstamp;
		}
		if(!isset($dateData[$yearstamp]['monthstamp'])){
			$dateData[$yearstamp]['monthstamp']=$monthstamp;
		}
		if(!isset($dateData[$datestamp]['yearstamp'])){
			$dateData[$datestamp]['yearstamp']=$yearstamp;
		}
		if(!isset($dateData[$weekstamp]['yearstamp'])){
			$dateData[$weekstamp]['yearstamp']=$yearstamp;
		}
		if(!isset($dateData[$monthstamp]['yearstamp'])){
			$dateData[$monthstamp]['yearstamp']=$yearstamp;
		}
		if(!isset($dateData[$yearstamp]['yearstamp'])){
			$dateData[$yearstamp]['yearstamp']=$yearstamp;
		}

		/*
		$dateData[$datestamp]['weekstamp']=
		$dateData[$weekstamp]['weekstamp']=
		$dateData[$monthstamp]['weekstamp']=
		$dateData[$yearstamp]['weekstamp']=
		$weekstamp;

		$dateData[$datestamp]['monthstamp']=
		$dateData[$weekstamp]['monthstamp']=
		$dateData[$monthstamp]['monthstamp']=
		$dateData[$yearstamp]['monthstamp']=
		$monthstamp;

		$dateData[$datestamp]['yearstamp']=
		$dateData[$weekstamp]['yearstamp']=
		$dateData[$monthstamp]['yearstamp']=
		$dateData[$yearstamp]['yearstamp']=
		$yearstamp;
		*/
	}
	
	//$lastdate=$datestamp;
	
	//$key=date("w",$timestamp);
	//$weekday[$key]['day']=date("l",$timestamp);
	
	//if(!isset($weekday[$key]['Count'])){
	//	$weekday[$key]['Count']=0;
	//}
	//$weekday[$key]['Count']++;
}
$output .= "<ul class='side-nav'>
<li><a class='dayclass' href='#'>Days (All)</a></li>
<li><a class='weekclass' href='#'>Weeks</a></li>
<li><a class='monthclass' href='#'>Months</a></li>
<li><a class='yearclass' href='#'>Years</a></li>
</ul>

<table>
<thead>
<tr>
	<th class='hidden'>Sort</th>
	<th>Date</th>
	<th>Time</th>
	<th>Day</th>
	<th>Weekday Avg</th>
	<th>7 days<br>average</th>
	<th>30 days<br>average</th>
	<th>Week Time</th>
	<th>Week Time<br>Projected</th>
	<th>Week Time<br>Projected Avg</th>
	<th class='hidden'>Week Average</th>
	<th class='hidden'>Month</th>
	<th>Month Time</th>
	<th>Month Time<br>Projected</th>
	<th>Month Time<br>Projected Avg</th>
	<th class='hidden'>Average day this Month</th>
	<th class='hidden'>Year</th>
	<th>Year Time</th>
	<th>Year Time<br>Projected</th>
	<th>Year Time<br>Projected Avg</th>
	<th class='hidden'>Average day this Year</th>
	<th class='hidden'>Month Year Average Day</th>
	<th class='hidden'>Month Average Day</th>
	<th></th>
</tr>
</thead>
<tbody>";

//TODO: The table header does not lign up with the table on the right side. Look for extra hidden cells.

$weekavg['Time']=0;
$weekavg['Count']=0;

$yearavg['Time']=0;
$yearavg['Count']=0;

$dayavg['Time']=0;
$dayavg['Count']=0;
foreach ($dateData as $key => &$row) {
	if(!isset($weekday[date("l",$key)]['Time'])){
		$weekday[date("l",$key)]['Time']=0;
		$weekday[date("l",$key)]['Count']=0;
	} else {
		$lastdate=$key;
	}
	
	$weekday[date("l",$key)]['Time']+=$row['Time'];
	$weekday[date("l",$key)]['Count']++;
	
	if(isset($row['WeekTime']) && $row['Date']>=strtotime("2013-8-11")){
		$weekavg['Time']+=$row['WeekTime'];
		$weekavg['Count']++;
	}

	if(isset($row['YearTime']) && $row['Date']>=strtotime("2013-1-1")){
		$yearavg['Time']+=$row['YearTime'];
		$yearavg['Count']++;
	}

	$dayavg['Time']+=$row['Time'];
	$dayavg['Count']++;
	
	$row['7avg']['Time']=0;
	//$row['7avg']['Debug']="";
	for ($x = 0; $x <= 7; $x++) {
		$pastkey=strtotime("-".$x." days",$row['Date']);
		if(isset($dateData[$pastkey]['Time'])){
			$row['7avg']['Time']+=$dateData[$pastkey]['Time'];
			//$row['7avg']['Debug'].="+".timeduration($dateData[$pastkey]['Time'],"seconds");
		}
	}
	$row['7avg']['Avg']=$row['7avg']['Time']/7;

	
	$row['30avg']['Time']=0;
	for ($x = 0; $x <= 30; $x++) {
		$pastkey=strtotime("-".$x." days",$row['Date']);
		if(isset($dateData[$pastkey]['Time'])){
			$row['30avg']['Time']+=$dateData[$pastkey]['Time'];
		}
	}
	$row['30avg']['Avg']=$row['30avg']['Time']/30;
	$row['AvgDayinYear']=$dateData[$row['yearstamp']]['YearTime']/(date("z", mktime(0,0,0,12,31,(int)date("Y",$row['Date']))) + 1);
	
	//$Sortby1[$key]  = $key;
	$Sortby1[$key]  = $row['Sort'];
	
	if(!isset($dateData[$row['weekstamp']]['WeekDayCount'])){
		$dateData[$row['weekstamp']]['WeekDayCount']=0;
	}
	if(!isset($dateData[$row['monthstamp']]['MonthDayCount'])){
		$dateData[$row['monthstamp']]['MonthDayCount']=0;
	}
	if(!isset($dateData[$row['yearstamp']]['YearDayCount'])){
		$dateData[$row['yearstamp']]['YearDayCount']=0;
	}
	if($lastdate<>$datestamp) {
		$dateData[$row['weekstamp']]['WeekDayCount']++;
		$dateData[$row['monthstamp']]['MonthDayCount']++;
		$dateData[$row['yearstamp']]['YearDayCount']++;
	}
		
	
}

//foreach ($dateData as $key => &$row) {
//	$row['AvgDayinYear']=$dateData[$row['yearstamp']]['YearTime']/$dateData[$row['yearstamp']]['YearDayCount'];
//}

$weekavg['Avg']=$weekavg['Time']/$weekavg['Count'];
$yearavg['Avg']=$yearavg['Time']/$yearavg['Count'];

$dayavg['Avg']=$dayavg['Time']/$dayavg['Count'];

array_multisort($Sortby1, SORT_ASC, $dateData);

$dateData=reIndexArray($dateData,"Sort");
//var_dump($dateData);


foreach($weekday as &$row){
	$row['Avg']=$row['Time']/$row['Count'];
}

//var_dump($weekday);

unset($row);

//var_dump($weekday);

$GoogleChartDataString['TwoWeeks']="";
$GoogleChartDataString['AllWeeks']="";
$GoogleChartDataString['AllMonths']="";
$GoogleChartDataString['Years']="";
foreach($dateData as $row){
	$output .= "<tr class='dayclass ";
	if(isset($row['WeekTime'])){$output .= "weekclass ";}
	if(isset($row['MonthTime'])){$output .= "monthclass ";}
	if(isset($row['YearTime'])){$output .= "yearclass ";}
	$output .= "'>
	<td class='hidden numeric'>". $row['Sort']."</td>
	<td class='numeric'>". date("M/d/Y",$row['Date'])."</td>
	<td class='numeric'>". timeduration($row['Time'],"seconds")."</td>
	<td class='text'>". date("l",$row['Date'])."</td>
	<td class='numeric'>". timeduration($weekday[date("l",$row['Date'])]['Avg'],"seconds")."</td>
	<td class='numeric'>". timeduration($row['7avg']['Avg'],"seconds")."</td>
	<td class='numeric'>". timeduration($row['30avg']['Avg'],"seconds")."</td>";

	//TODO: Year ago would be wierd as the days would be offset. Maybe -52 weeks would work?
	//if($row['Date']>=strtotime("-1 year 14 days") && $row['Date']<=strtotime("-1 year")){}
	
	if($row['Date']>=strtotime("-14 days")){
		$GoogleChartDataString['TwoWeeks'].="
		[new Date(".date("Y",$row['Date']).", ". (date("m",$row['Date'])-1) .", ".date("d",$row['Date'])."),
		".($row['Time']/60/60) 
		.", ".($weekday[date("l",$row['Date'])]['Avg']/60/60) 
		.", ".$dayavg['Avg']/60/60 
		//.", ".($weekavg['Avg']/60/60/7) 
		.", ".($row['7avg']['Avg']/60/60) 
		.", ".($row['30avg']['Avg']/60/60)

		//TODO: This timeduration useage is broken if the /60/60*7 is outside the function and breaks the charts if it is inside.
		//TODO: Double check that it is making charts as expected.
		//.", ".timeduration($dateData[$row['monthstamp']]['MonthTime']/date("t",$row['Date']))/60/60
		.", ".$dateData[$row['monthstamp']]['MonthTime']/date("t",$row['Date'])/60/60
		.", ".($row['AvgDayinYear']/60/60)
		.", ".($dateData[$row['yearstamp']]['YearTime']/$dateData[$row['yearstamp']]['YearDayCount']/60/60)
		."], ";
	}
	//$row['YearTime']/$row['YearDayCount']
	
	//$output .= "<td>".date("w",$row['Date'])."</td>";
	if(isset($row['WeekTime'])){
		$output .= "<td class='numeric'>". timeduration($row['WeekTime'],"seconds")."</td>
		<td class='hidden numeric'>". timeduration($weekavg['Avg'],"seconds")."</td>";
		if($row['weekstamp']==$lastweek){
			$dayofweek=(date("w",$lastdate));
			$output .= "<td class='numeric'>". timeduration(($row['WeekTime']/($dayofweek+1))*(7),"seconds")."</td>
			<td class='numeric'>". timeduration($row['WeekTime']/($dayofweek+1),"seconds")."</td>";
		} else {
			$output .= "<td class='numeric'>". timeduration(($row['WeekTime']/$row['WeekDayCount'])*7,"seconds")."</td>
			<td class='numeric'>". timeduration($row['WeekTime']/$row['WeekDayCount'],"seconds")."</td>";
		}

		//$output .="<br>timeduration("; var_dump($dateData[$row['monthstamp']]['MonthTime']); $output .= " / date(\"t\","; var_dump($row['Date']); $output .= " ))<br>";
		//$output .="timeduration("; var_dump($dateData[$row['monthstamp']]['MonthTime']); $output .= " / "; var_dump(date("t",$row['Date'])); $output .= " )<br>";
		//$output .="timeduration("; var_dump($dateData[$row['monthstamp']]['MonthTime'] / date("t",$row['Date'])); $output .= " )<br>";
		//var_dump(timeduration($dateData[$row['monthstamp']]['MonthTime'] / date("t",$row['Date']))); $output .= "/60/60*7<br>";
		//var_dump(strtotime(timeduration($dateData[$row['monthstamp']]['MonthTime'] / date("t",$row['Date'])))/60/60*7); $output .= "<br>";
		//var_dump(timeduration($dateData[$row['monthstamp']]['MonthTime'] / date("t",$row['Date'])/60/60*7)); $output .= "<br>";
		
		if($row['Date']>strtotime("2013-7-1")){
			$GoogleChartDataString['AllWeeks'].="
			[new Date(".date("Y",$row['Date']).", ". (date("m",$row['Date'])-1) .", ".date("d",$row['Date'])."),
			".($row['WeekTime']/60/60) 
			.", ".($weekavg['Avg']/60/60) 
			.", ".$dayavg['Avg']/60/60*7 
			
			//TODO: This timeduration useage is broken if the /60/60*7 is outside the function and breaks the charts if it is inside.
			//TODO: Double check that it is making charts as expected.
			//.", ".timeduration($dateData[$row['monthstamp']]['MonthTime']/$row['Date'])/60/60*7 
			.", ".$dateData[$row['monthstamp']]['MonthTime']/date("t",$row['Date'])/60/60*7 
			
			.", ".($row['AvgDayinYear']/60/60*7)
			."], ";
		}
	} else {
		$output .= '<td></td>
		<td class="hidden"></td>
		<td></td>
		<td></td>';
	}
		//DEBUG ROWS
		//$output .= "<td class='text'>".date("F",$row['monthstamp'])."</td>";
		//$monthstamp=strtotime((date("Y",$row['Date']))."-".(date("m",$row['Date']))."-1");
		//$output .= "<td class='text'>".date("Y-m-d",$monthstamp)."</td>"; //Show date of monthstamp
		//$output .= "<td class='text'>".date("Y-m-d",$row['monthstamp'])."</td>"; //Show date of monthstamp
	if(isset($row['MonthTime'])){
		$output .= '<td class="hidden text">'. date("F",$row['Date']).'</td>
		<td class="numeric">'. timeduration($row['MonthTime'],"seconds").'</td>
		<td class="hidden numeric">'. timeduration($row['MonthTime']/date("t",$row['Date']),"seconds").'</td>';

		if($row['monthstamp']==$lastmonth){
			$dayofmonth=(date("j",$lastdate));
			$daysinmonth=(date("t",$lastdate));
			$output .= "<td class='numeric'>". timeduration(($row['MonthTime']/($dayofmonth))*($daysinmonth-$dayofmonth),"seconds")."</td>
			<td class='numeric'>". timeduration($row['MonthTime']/($dayofmonth),"seconds")."</td>";
		} else {
			//DEBUG ROW
			/*
			$output .= "<td class='numeric'>";
			//$output .= timeduration($row['MonthTime'],"seconds");
			$output .= "/";
			$output .= $row['MonthDayCount'];
			$output .= "*";
			$output .= date("t",$row['monthstamp']);
			$output .= "=";
			$output .= timeduration(($row['MonthTime']/$row['MonthDayCount'])*date("t",$row['monthstamp']),"seconds");
			$output .= "</td>";
			*/
			$output .= "<td class='numeric'>". timeduration(($row['MonthTime']/$row['MonthDayCount'])*date("t",$row['monthstamp']),"seconds")."</td>
			<td class='numeric'>". timeduration($row['MonthTime']/$row['MonthDayCount'],"seconds")."</td>";
		}
		
		if($row['Date']>strtotime("2013-6-1")){
			$GoogleChartDataString['AllMonths'].="
			[new Date(".date("Y",$row['Date']).", ". (date("m",$row['Date'])-1) .", ".date("d",$row['Date'])."),
			".($row['MonthTime']/60/60)
			.", ".$dayavg['Avg']/60/60 *date("t",$row['Date'])
			.", ".($row['AvgDayinYear']/60/60*date("t",$row['Date']))
			."], ";
		}
	} else {
		$output .= '<td class="hidden"></td>
		<td></td>
		<td></td>
		<td></td>';
	}
	$output .= "<td class='hidden numeric'>". timeduration($dateData[$row['monthstamp']]['MonthTime']/date("t",$row['Date']),"seconds")."</td>";
	if(isset($row['YearTime'])){
		$output .= "<td class='hidden numeric'>". date("Y",$row['Date'])."</td>
		<td class='numeric'>". timeduration($row['YearTime'],"seconds")."</td>";
		if($row['yearstamp']==$lastyear){
			$dayofyear=(date("z",$lastdate)+1);
			$daysinyear=365+(date("L",$lastdate));
			$output .= "<td class='numeric'>". timeduration(($row['YearTime']/($dayofyear))*($daysinyear-$dayofyear),"seconds")."</td>
			<td class='numeric'>". timeduration($row['YearTime']/($dayofyear),"seconds")."</td>";
		} else {
			$output .= "<td class='numeric'>". timeduration($row['YearTime']/$row['YearDayCount']*(365+(date("L",$row['yearstamp']))),"seconds")."</td>
			<td class='numeric'>". timeduration($row['YearTime']/$row['YearDayCount'],"sec
			onds")."</td>
			<td class='hidden numeric'>". timeduration((($row['YearTime']/(365+(date("L",$row['yearstamp']))))),"seconds")."</td>";
		}

		if($row['Date']>strtotime("2012-1-1")){
			$GoogleChartDataString['Years'].="
			[new Date(".date("Y",$row['Date']).", ". (date("m",$row['Date'])-1) .", ".date("d",$row['Date'])."),
			".($row['YearTime']/60/60)
			.", ".($yearavg['Avg']/60/60) 
			."], ";
		}
	} else { 
		$output .= '<td class="hidden"></td>
		<td></td>
		<td></td>
		<td></td>';
	}
	//$output .= "<td class='numeric'>".timeduration($row['AvgDayinYear'],"seconds")."</td>";

	//$output .= "<td>".$row['7avg']['Debug']."</td>";
	//$output .= "<td>".date("M/d/Y h:i:s A",$row['Date'])."</td>";
	//$output .= "<td>";	var_dump($row);	$output .= "</td>";
	//$output .= "<td>" .timeduration($currentmonthtime,"seconds" )."</td>";
	
	/*
	$output .= "<td>".date("Y-m-d",$row['monthstamp'])." ";
	if(isset($row['monthstamp'])){
		$output .= timeduration($dateData[$row['monthstamp']]['MonthTime'],"seconds" );
	} else {
		$output .= "No monthstamp";
	}
	$output .= "</td>";
	*/
	$output .= '</tr>
	<tr class="hidden"><td colspan=10>'. print_r($row,true).'</td></tr>';
 } 
$output .= "</tbody>
</table>";

//var_dump($lastweek);

/***** Dynamic Chart *****/
//$output .= $GoogleChartDataString;
	//$GoogleChartDataString="";
/* * /
	  $GoogleChartDataString="
	[new Date(2010, 5), 0, 1],    
	[new Date(2013, 5), 0.1, 6.3],   ";
	/* * /
	  $GoogleChartDataString.="
	[new Date(2013, 7), 121.48, 32, 121.48, 39, 9],
	[new Date(2013, 8), 0, 0, 60.74, 20, 1],
	[new Date(2013, 9), 15.27, 22, 45.58, 20, 10],
	[new Date(2013, 10), 34.91, 58, 42.92, 30, 8],
	[new Date(2013, 11), 73.63, 55, 49.06, 35, 10],
	[new Date(2013, 12), 78.65, 87, 53.99, 44, 11],
	[new Date(2014, 1), 36.53, 36, 51.50, 42, 32],
	[new Date(2014, 2), 14.53, 59, 46.88, 45, 10],
	[new Date(2014, 3), 11.04, 30, 42.89, 43, 5],
	[new Date(2014, 4), 10.76, 38, 39.68, 42, 30],
	[new Date(2014, 5), 14.00, 39, 37.35, 42, 14],
	[new Date(2014, 6), 45.24, 52, 38.00, 43, 20],
	[new Date(2014, 7), 9.91, 25, 35.84, 42, 36],
	[new Date(2014, 8), -50.96, 35, 29.64, 41, 41],
	[new Date(2014, 9), 21.06, 45, 29.07, 41, 22],
	[new Date(2014, 10), 4.02, 25, 27.50, 40, 18],
	[new Date(2014, 11), 2.73, 11, 26.05, 39, 13],
	[new Date(2014, 12), 14.49, 24, 25.41, 38, 19],
	[new Date(2015, 1), 20.51, 39, 25.15, 38, 40],
	[new Date(2015, 2), 11.32, 30, 24.46, 37, 21],
	[new Date(2015, 3), 9.91, 31, 23.76, 37, 17],
	[new Date(2015, 4), 3.74, 17, 22.85, 36, 18],
	[new Date(2015, 5), 12.87, 33, 22.42, 36, 32],
	[new Date(2015, 6), 64.90, 37, 24.19, 36, 32],
	[new Date(2015, 7), 11.83, 28, 23.69, 36, 29],
	[new Date(2015, 8), 3.65, 16, 22.92, 35, 22],
	[new Date(2015, 9), 19.12, 25, 22.78, 35, 28],
	[new Date(2015, 10), 1.01, 13, 22.01, 34, 21],
	[new Date(2015, 11), 26.79, 12, 22.17, 33, 32],
	[new Date(2015, 12), 19.49, 49, 22.08, 34, 45],
	[new Date(2016, 1), 5.31, 15, 21.54, 33, 29],
	[new Date(2016, 2), 25.86, 9, 21.68, 32, 3],
	";
	
/* */
	//$output .= "<br>";$output .= $GoogleChartDataString['AllMonths'];

///https://developers.google.com/chart/interactive/docs/gallery/linechart
$GoogleChartDataString['TwoWeeks']=substr(trim($GoogleChartDataString['TwoWeeks']), 0, -1);
$output .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">';
$output .= "google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawCurveTypes);

function drawCurveTypes() {
  var data = new google.visualization.DataTable();
  data.addColumn('date', 'Date');
  data.addColumn('number', 'Hours');
  data.addColumn('number', 'Weekday Average');
  data.addColumn('number', 'Daily Average');
  //data.addColumn('number', 'Weekly Average / 7');
  data.addColumn('number', '7 Days Average');
  data.addColumn('number', '30 Days Average');
  data.addColumn('number', 'Average day of month');
  data.addColumn('number', 'Average day of year');
  data.addColumn('number', 'Average day of year (projected)');

  data.addRows([
	". $GoogleChartDataString['TwoWeeks']."
  ]);

  var options = {
	title: 'Two Weeks Daily History',
	hAxis: {
	  title: 'Date'
	},
	vAxis: {
	  title: 'Amount',
	  viewWindow: {
		  //max: 12.1,
		  max: 6,
		  min: 0
	  }
	},
	series: {
	  1: {curveType: 'none'}
	}
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(data, options);
  
  
  var data2 = new google.visualization.DataTable();
  data2.addColumn('date', 'Date');
  data2.addColumn('number', 'Hours');
  data2.addColumn('number', 'Average of Weeks');
  data2.addColumn('number', 'Daily Average * 7');
  data2.addColumn('number', 'Average day of month * 7');
  data2.addColumn('number', 'Average day of year * 7');
  
  data2.addRows([
	". $GoogleChartDataString['AllWeeks']."
  ]);

  var options2 = {
   title: 'Weekly History',
	hAxis: {
	  title: 'Date'
	},
	vAxis: {
	  title: 'Amount',
	  viewWindow: {
		  //max: 65,
		  max: 35,
		  min: 2.9
	  }
	},
	series: {
	  1: {curveType: 'none'}
	}
  };
	
  var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
  chart2.draw(data2, options2);
  
  
  var data3 = new google.visualization.DataTable();
  data3.addColumn('date', 'Date');
  data3.addColumn('number', 'Hours');
  data3.addColumn('number', 'Average day');
  data3.addColumn('number', 'Average day of year');
 //data3.addColumn('number', 'Average Month');
  //data3.addColumn('number', 'Hours');
  
  data3.addRows([
	". $GoogleChartDataString['AllMonths']."
  ]);

  var options3 = {
   title: 'Monthly History',
	hAxis: {
	  title: 'Date'
	},
	vAxis: {
	  title: 'Amount',
	  viewWindow: {
		  //max: 225,
		  max: 120,
		  min: 20
	  }
	},
	series: {
	  1: {curveType: 'none'}
	}
  };
	
  var chart3 = new google.visualization.LineChart(document.getElementById('chart_div3'));
  chart3.draw(data3, options3);
  
  
  var data4 = new google.visualization.DataTable();
  data4.addColumn('date', 'Date');
  data4.addColumn('number', 'Hours');
  data4.addColumn('number', 'Average Year');
  
  data4.addRows([
	". $GoogleChartDataString['Years']."
  ]);

  var options4 = {
   title: 'Annual History',
	hAxis: {
	  title: 'Date'
	},
	vAxis: {
	  title: 'Amount',
	  viewWindow: {
		  //max: 1095,
		  max: 970,
		  min: 520
	  }
	},
	series: {
	  1: {curveType: 'none'}
	}
  };
	
  var chart4 = new google.visualization.LineChart(document.getElementById('chart_div4'));
  chart4.draw(data4, options4);
  
}


$(function()
{
	$('ul.side-nav a').click(function()
	{
	   $('tr.dayclass').hide();
	   $('tr.' + $(this).attr('class')).show();       
	});
});		
</script>";
$output .= '<p>
   <div id="chart_div" style="height:500px;"></div>
   <div id="chart_div2" style="height:500px;"></div>
   <div id="chart_div3" style="height:500px;"></div>
   <div id="chart_div4" style="height:500px;"></div>';
		return $output;
	}
}	