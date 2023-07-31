<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/getsettings.inc.php";
include_once $GLOBALS['rootpath']."/inc/getCalculations.inc.php";

class ratingsPage extends Page
{
	public function __construct() {
		$this->title="Ratings";
	}
	
	public function buildHtmlBody(){
		$output="";
		
$conn=get_db_connection();
$settings=getsettings($conn);

$calculations=getCalculations("",$conn);

$scale=10;
$data=RatingsChartData($scale,$calculations);

$conn->close();	

$GoogleChartDataString="['Rating', 'Metascore',	'UserMetascore',	'SteamRating',	'Review',	'Want']";
//TODO: [Major overhaul] re-do database to handle unlimited reviews per item in a seperate table.

$output .= '<table>
<thead>
<tr>
<th>Scale</th>
<th>Metascore</th>
<th>UserMetascore</th>
<th>SteamRating</th>
<th>Review</th>
<th>Want</th>
</tr>
</thead>
<tbody>';

for ($x = 1; $x <= $scale; $x++) { 
	$output .= "<tr>
	<td>$x</td> ";
	//$chartarray[$x]['scale']=$x;
	$GoogleChartDataString.=", ['".$x."', ";
	
	//TODO: Add links to group lists for each rating filter.
	$field="Metascore";
	$output .= "<td>";
	if(isset($data[$field][$x])){ 
		$output .= $data[$field][$x];
		//$chartarray[$x][$field]=$data[$field][$x];
		$GoogleChartDataString.=$data[$field][$x].", ";
	} else { 
		//$chartarray[$x][$field]=0;
		$GoogleChartDataString.="0, ";
	}
	$output .= "</td>";
	
	$field="UserMetascore";
	$output .= "<td>";
	if(isset($data[$field][$x])){ 
		$output .= $data[$field][$x];
		//$chartarray[$x][$field]=$data[$field][$x];
		$GoogleChartDataString.=$data[$field][$x].", ";
	} else { 
		//$chartarray[$x][$field]=0;
		$GoogleChartDataString.="0, ";
	}
	$output .= "</td>";

	$field="SteamRating";
	$output .= "<td>";
	if(isset($data[$field][$x])){ 
		$output .= $data[$field][$x];
		//$chartarray[$x][$field]=$data[$field][$x];
		$GoogleChartDataString.=$data[$field][$x].", ";
	} else { 
		//$chartarray[$x][$field]=0;
		$GoogleChartDataString.="0, ";
	}
	$output .= "</td>";

	$field="Review";
	$output .= "<td>";
	if(isset($data[$field][$x])){ 
		$output .= $data[$field][$x];
		//$chartarray[$x][$field]=$data[$field][$x];
		$GoogleChartDataString.=$data[$field][$x].", ";
	} else { 
		//$chartarray[$x][$field]=0;
		$GoogleChartDataString.="0, ";
	}
	$output .= "</td>";

	$field="Want";
	$output .= "<td>";
	if(isset($data[$field][$x])){ 
		$output .= $data[$field][$x];
		//$chartarray[$x][$field]=$data[$field][$x];
		$GoogleChartDataString.=$data[$field][$x].", ";
	} else { 
		//$chartarray[$x][$field]=0;
		$GoogleChartDataString.="0, ";
	}
	$output .= "</td>";
	$output .= "</tr>";
$GoogleChartDataString.="]";
}
$output .= "</tbody>
</table>";

//$JsonData=json_encode($chartarray);

//$output .= $GoogleChartDataString;

/* * /
	  $GoogleChartDataString="['Rating', 'Metascore',	'UserMetascore',	'SteamRating',	'Review',	'Want'],
	  ['1', 1, 	1, 	1,	0,	0],
	  ['2', 1,	3,	2,	0,	356],
	  ['3', 1,	5,	2,	236,	0],
	  ['4', 6,	12,	4,	0,	198],
	  ['5', 13,	29,	3,	210,	0],
	  ['6', 55,	47,	18,	0,	229],
	  ['7', 125,	157,	19, 0, 0],
	  ['8', 208,	243,	28,	192,	199],
	  ['9', 168,	240,	58, 0, 0],
	  ['10', 30,	33,	53,	104,	215]";

	  $output .= "<br>";
	$output .= $GoogleChartDataString;
/* */

///https://developers.google.com/chart/interactive/docs/gallery/columnchart#data-format

$output .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">';
      $output .= "google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
		". $GoogleChartDataString."
        ]);

        var options = {
          chart: {
            title: 'Count of Ratings',
            subtitle: '',
          },
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          colors: ['#1b9e77', '#d95f02', '#7570b3']
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }
    </script>	
	<br>
       <div id='chart_div'></div>";

		return $output;
	}
}	