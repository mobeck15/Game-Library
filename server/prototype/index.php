<?php
$GLOBALS['rootpath']="..";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

$title="Feature Checklist";
echo Get_Header($title);

$sheeticon='<img src="http://ssl.gstatic.com/docs/spreadsheets/favicon_jfk2.png"/>';
$sheet='https://docs.google.com/spreadsheets/d/';

$sheetURLs['1']['id']['key']   = "1ihYtBWy9vKO4Q2rxH_17ZcfUWDADWJpVeo7u29PF2mg";
$sheetURLs['1.0']['id']['key'] = "1SF-8003ukPtKhObQEYeiLmfNK1ZBMWCV4Ey2hYYrla8";
$sheetURLs['1']['id']['dashboard']    = "10";
$sheetURLs['1']['id']['calculations'] = "8";
$sheetURLs['1']['id']['library']      = "0";
$sheetURLs['1']['id']['wishlist']     = "6";
$sheetURLs["1"]['dashboard']      ="$sheet".$sheetURLs['1']['id']['key']."/edit#gid="  .$sheetURLs['1']['id']['dashboard'];
$sheetURLs["1.0"]['dashboard']    ="$sheet".$sheetURLs['1.0']['id']['key']."/edit#gid=".$sheetURLs['1']['id']['dashboard'];
$sheetURLs["1"]['calculations']   ="$sheet".$sheetURLs['1']['id']['key']."/edit#gid="  .$sheetURLs['1']['id']['calculations'];
$sheetURLs["1.0"]['calculations'] ="$sheet".$sheetURLs['1.0']['id']['key']."/edit#gid=".$sheetURLs['1']['id']['calculations'];
$sheetURLs["1"]['library']        ="$sheet".$sheetURLs['1']['id']['key']."/edit#gid="  .$sheetURLs['1']['id']['library'];
$sheetURLs["1.0"]['library']      ="$sheet".$sheetURLs['1.0']['id']['key']."/edit#gid=".$sheetURLs['1']['id']['library'];
$sheetURLs["1"]['wishlist']       ="$sheet".$sheetURLs['1']['id']['key']."/edit#gid="  .$sheetURLs['1']['id']['wishlist'];
$sheetURLs["1.0"]['wishlist']     ="$sheet".$sheetURLs['1.0']['id']['key']."/edit#gid=".$sheetURLs['1']['id']['wishlist'];

$sheetURLs['2']['id']['key']   = "1GMQcgMZwCb67h2LERCT1z13nuA2W1jTZdLVV3JZtYkM";
$sheetURLs['2.0']['id']['key'] = "1GMQcgMZwCb67h2LERCT1z13nuA2W1jTZdLVV3JZtYkM";
$sheetURLs['2']['id']['dashboard']    = "4";
$sheetURLs['2']['id']['statistics']   = "3";
$sheetURLs['2']['id']['calculations'] = "2";
$sheetURLs['2']['id']['purchases']    = "0";
$sheetURLs['2']['id']['settings']     = "5";
$sheetURLs['2']['id']['activity']     = "1";
$sheetURLs["2"]['dashboard']      ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['dashboard'];
$sheetURLs["2.0"]['dashboard']    ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['dashboard'];
$sheetURLs["2"]['statistics']     ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['statistics'];
$sheetURLs["2.0"]['statistics']   ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['statistics'];
$sheetURLs["2"]['calculations']   ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['calculations'];
$sheetURLs["2.0"]['calculations'] ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['calculations'];
$sheetURLs["2"]['purchases']      ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['purchases'];
$sheetURLs["2.0"]['purchases']    ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['purchases'];
$sheetURLs["2"]['settings']       ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['settings'];
$sheetURLs["2.0"]['settings']     ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['settings'];
$sheetURLs["2"]['activity']       ="$sheet".$sheetURLs['2']['id']['key']."/edit#gid="  .$sheetURLs['2']['id']['activity'];
$sheetURLs["2.0"]['activity']     ="$sheet".$sheetURLs['2.0']['id']['key']."/edit#gid=".$sheetURLs['2']['id']['activity'];

$sheetURLs['3']['id']['key']   = "1GsUqgtxKzcHu_st_Ng4ToBEIBqtP8sKYfaQ7ngGk0dM";
$sheetURLs['3.0']['id']['key'] = "1EWfj2rCECNS6YbeocSj1vN_Nu3VPHw-63kKqCqOz0jo";
$sheetURLs['3']['id']['dashboard']    = "1642113405";
$sheetURLs['3']['id']['statistics']   = "754265216";
$sheetURLs['3']['id']['calculations'] = "1223595866";
$sheetURLs['3']['id']['purchases']    = "150947784";
$sheetURLs['3']['id']['settings']     = "1351713249";
$sheetURLs['3']['id']['games']        = "48829890";
$sheetURLs['3']['id']['tracker']      = "295073703";
$sheetURLs['3']['id']['activity']     = "177914213";
$sheetURLs['3']['id']['trackerstats'] = "1097183918";
$sheetURLs['3']['id']['notes']        = "0";
$sheetURLs['3']['id']['sortedlists']  = "350710896";
$sheetURLs['3']['id']['waste']        = "888821928";
$sheetURLs['3']['id']['toplevel']     = "1725828564";
$sheetURLs['3']['id']['series']       = "656840306";
$sheetURLs['3']['id']['history']      = "653820089";
$sheetURLs['3']['id']['genres']       = "879578578";
$sheetURLs['3']['id']['hardware']     = "788068040";
$sheetURLs['3']['id']['cpi']          = "1766798461";
$sheetURLs['3']['id']['goty']         = "2088545640";
$sheetURLs["3"]['dashboard']      ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['dashboard'];
$sheetURLs["3.0"]['dashboard']    ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['dashboard'];
$sheetURLs["3"]['statistics']     ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['statistics'];
$sheetURLs["3.0"]['statistics']   ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['statistics'];
$sheetURLs["3"]['calculations']   ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['calculations'];
$sheetURLs["3.0"]['calculations'] ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['calculations'];
$sheetURLs["3"]['purchases']      ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['purchases'];
$sheetURLs["3.0"]['purchases']    ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['purchases'];
$sheetURLs["3"]['settings']       ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['settings'];
$sheetURLs["3.0"]['settings']     ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['settings'];
$sheetURLs["3"]['games']          ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['games'];
$sheetURLs["3.0"]['games']        ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['games'];
$sheetURLs["3"]['tracker']        ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['tracker'];
$sheetURLs["3.0"]['tracker']      ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['tracker'];
$sheetURLs["3"]['activity']       ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['activity'];
$sheetURLs["3.0"]['activity']     ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['activity'];
$sheetURLs["3"]['trackerstats']   ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['trackerstats'];
$sheetURLs["3.0"]['trackerstats'] ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['trackerstats'];
$sheetURLs["3"]['notes']          ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['notes'];
$sheetURLs["3.0"]['notes']        ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['notes'];
$sheetURLs["3"]['sortedlists']    ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['sortedlists'];
$sheetURLs["3.0"]['sortedlists']  ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['sortedlists'];
$sheetURLs["3"]['waste']          ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['waste'];
$sheetURLs["3.0"]['waste']        ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['waste'];
$sheetURLs["3"]['toplevel']       ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['toplevel'];
$sheetURLs["3.0"]['toplevel']     ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['toplevel'];
$sheetURLs["3"]['series']         ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['series'];
$sheetURLs["3.0"]['series']       ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['series'];
$sheetURLs["3"]['history']        ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['history'];
$sheetURLs["3.0"]['history']      ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['history'];
$sheetURLs["3"]['genres']         ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['genres'];
$sheetURLs["3.0"]['genres']       ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['genres'];
$sheetURLs["3"]['hardware']       ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['hardware'];
$sheetURLs["3.0"]['hardware']     ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['hardware'];
$sheetURLs["3"]['cpi']            ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['cpi'];
$sheetURLs["3.0"]['cpi']          ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['cpi'];
$sheetURLs["3"]['goty']           ="$sheet".$sheetURLs['3']['id']['key']."/edit#gid="  .$sheetURLs['3']['id']['goty'];
$sheetURLs["3.0"]['goty']         ="$sheet".$sheetURLs['3.0']['id']['key']."/edit#gid=".$sheetURLs['3']['id']['goty'];

$sheetURLs['4a']['id']['key']     = "1uYF-63iyvvmmUecnjTFNMHIrZpG6ZoBRSbkhR3LSgf4";
$sheetURLs['4b']['id']['key']     = "12tIPrBSCRoktEEYI3p5ac4PMuwuTfVrawgqPalTxULQ";
$sheetURLs['4c']['id']['key']     = "1GV1c51ZGTbhNv_Sr9t7cXhRYFKYTxJp9LLnMqrigQ2o";
$sheetURLs["4a"]['activity']      ="$sheet".$sheetURLs['4a']['id']['key']."/edit#gid="  ."1680994573";
$sheetURLs["4a"]['activitystats'] ="$sheet".$sheetURLs['4a']['id']['key']."/edit#gid="  ."0";
$sheetURLs["4a"]['historydata']   ="$sheet".$sheetURLs['4a']['id']['key']."/edit#gid="  ."1157015125";
$sheetURLs["4a"]['goty']          ="$sheet".$sheetURLs['4a']['id']['key']."/edit#gid="  ."985821080";
$sheetURLs["4b"]['purchases']     ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."1091512546";
$sheetURLs["4b"]['settings']      ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."1151251091";
$sheetURLs["4b"]['games']         ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."0";
$sheetURLs["4b"]['notes']         ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."1360354046";
$sheetURLs["4b"]['chartdata']     ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."1876665488";
$sheetURLs["4b"]['dashboard']     ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."2089204547";
$sheetURLs["4b"]['upgrade']       ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."348543386";
//$sheetURLs["4c"]['chartdata']     ="$sheet".$sheetURLs['4b']['id']['key']."/edit#gid="  ."2088545640"; //Deleted Sheet
$sheetURLs["4c"]['chartdata']     ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1271980432";
$sheetURLs["4c"]['statistics']    ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."658646980";
$sheetURLs["4c"]['calculations']  ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1934108002";
$sheetURLs["4c"]['dashboard']     ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."743273972";
$sheetURLs["4c"]['toplists']      ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."62677507";
$sheetURLs["4c"]['sortedlists']   ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1988893217";
$sheetURLs["4c"]['waste']         ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1085016590";
$sheetURLs["4c"]['toplevel']      ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1123333780";
$sheetURLs["4c"]['history']       ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."2050200343";
$sheetURLs["4c"]['cpi']           ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."791406332";
$sheetURLs["4c"]['hardware']      ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."918891093";
$sheetURLs["4c"]['export']        ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."1802149337";
$sheetURLs["4c"]['playnext']      ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."488301569";
$sheetURLs["4c"]['rename']        ="$sheet".$sheetURLs['4c']['id']['key']."/edit#gid="  ."668192643";

//var_dump($sheetURLs["3.0"]['purchases']);
?>
<style>
.gl1 {
	display: none;
	/* 
	width:8%; 
	*/
}

.gl2 {
	display: none;
}

.prototype {
	display: none;
}



</style>

<table style="table-layout: fixed;">
<thead><tr>
	<th >Feature</th>
	<th >Type</th>
	<th style="width:20%">Description</th>
	<th >Pre-Req</th>
	<th class="gl1">GL1</th>
	<th class="gl2" >GL2</th>
	<th class="gl3" >GL3</th>
	<th class="gl4" >GL4</th>
	<th class="gl5" >GL5</th>
	<th class="prototype" >GL6&nbsp;prototype</th>
	<th class="gl6" >GL6&nbsp;Functional</th>
</tr></thead>

<tr>
	<td>Add/Edit History</td>
	<td>Input Page</td>
	<td>Add historical data to the database.</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/addhistory.php">addhistory.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/addhistory.php">addhistory.php</a></td>
</tr>

<tr>
	<td>Recently Played</td>
	<td>Input Page</td>
	<td>Add historical data to the database using the Steam API to detect recently played games.</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/steamapi_recentlyplayed.php">steamapi_recentlyplayed.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/addhistory.php?mode=steam">addhistory.php</a> Mode: Steam</td>
</tr>

<tr>
	<td>Insert Purchases</td>
	<td>Input Page</td>
	<td>Add transactions to the transactions table.</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><?php echo $sheeticon; ?><a href="https://docs.google.com/spreadsheets/d/1zrvf-VQMadIduA0HJ64aW2sZAyZu45qkb_8nfmJVQx4/edit#gid=2097839486" target='_blank'>GL5 Inserter</a>
		<br/><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/insertersettings.php">insertersettings.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/addtransaction.php">addtransaction.php</a></td>
</tr>

<tr>
	<td>Insert Games</td>
	<td>Input Page</td>
	<td>Add products to the product table.</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><?php echo $sheeticon; ?><a href="https://docs.google.com/spreadsheets/d/1zrvf-VQMadIduA0HJ64aW2sZAyZu45qkb_8nfmJVQx4/edit#gid=2097839486" target='_blank'>GL5 Inserter</a>
		<br/><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/insertersettings.php">insertersettings.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/addproduct.php">addproduct.php</a></td>
</tr>

<tr>
	<td>Insert Item</td>
	<td>Input Page</td>
	<td>Add items to the items table.</td>
	<td>Insert&nbsp;Games, Insert&nbsp;Purchases</td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><?php echo $sheeticon; ?><a href="https://docs.google.com/spreadsheets/d/1zrvf-VQMadIduA0HJ64aW2sZAyZu45qkb_8nfmJVQx4/edit#gid=2097839486" target='_blank'>GL5 Inserter</a>
		<br/><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/insertersettings.php">insertersettings.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/additem.php">additem.php</a></td>
</tr>

<tr>
	<td>View/Edit Games</td>
	<td>Input Page</td>
	<td>Display game data and detaild stats.</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/viewgame.php">viewgame.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/viewgame.php">viewgame.php</a></td>
</tr>

<tr>
	<td>View/Edit Item</td>
	<td>Input Page</td>
	<td>Display Item records</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/viewitem.php">viewitem.php</a> INCOMPLETE</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl5/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/viewitem.php">viewitem.php</a></td>
</tr>

<tr>
	<td>View/Edit Transaction</td>
	<td>Input Page</td>
	<td>Display Transaction records</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl5/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/viewbundle.php">viewbundle.php</a></td>
</tr>

<tr>
	<td>Calendar</td>
	<td>Table</td>
	<td>Spending by month or year with some math and totals.</td>
	<td>Purchases</td>
	<td class="gl1"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["1"]['dashboard']; ?>" target='_blank'>GL1 Dashboard</a>
		(<a href="<?php echo $sheetURLs["1.0"]['dashboard']; ?>" target='_blank'>1.0</a>) G:K</td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['dashboard']; ?>" target='_blank'>GL2 Dashboard</a>
		(<a href="<?php echo $sheetURLs["2.0"]['dashboard']; ?>" target='_blank'>2.0</a>) A:E</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) A1:J27</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['chartdata']; ?>" target='_blank'>GL4b (Purchases) Chart Data</a>
		(<a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>4c</a>) A1:L32</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/chartdata.php">chartdata.php</a><br/>
	<img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/chartdata-year.php">chartdata-year.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl5/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/chartdata.php">chartdata.php</a></td>
</tr>

<tr>
	<td>Statistics</td>
	<td>Table</td>
	<td>Deep analysis of various lists showing which games, when sorted by various values fall in the top, bottom, average, etc.</td>
	<td></td>
	<td class="gl1"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["1"]['calculations']; ?>" target='_blank'>GL1 Calculations</a>
		(<a href="<?php echo $sheetURLs["1.0"]['calculations']; ?>" target='_blank'>1.0</a>) A:P</td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['statistics']; ?>" target='_blank'>GL2 Statistics</a>
		(<a href="<?php echo $sheetURLs["2.0"]['statistics']; ?>" target='_blank'>2.0</a>) blank</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['statistics']; ?>" target='_blank'>GL3 Statistics</a>
		(<a href="<?php echo $sheetURLs["3.0"]['statistics']; ?>" target='_blank'>3.0</a>) A:U</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['statistics']; ?>" target='_blank'>GL4c (Calculations) Statistics</a>
		A:AB</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/statistics.php">statistics.php</a> BROKEN</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/statistics.php">statistics.php</a> WIP</td>
	
</tr>

<tr>
	<td>Calculations</td>
	<td>Table</td>
	<td>Calculations based on Purchase data & Activity data</td>
	<td>Purchases, History</td>
	<td class="gl1"></td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['calculations']; ?>" target='_blank'>GL2 Calculations</a>
		(<a href="<?php echo $sheetURLs["2.0"]['calculations']; ?>" target='_blank'>2.0</a>) A:R</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['calculations']; ?>" target='_blank'>GL3 Calculations</a>
		(<a href="<?php echo $sheetURLs["3.0"]['calculations']; ?>" target='_blank'>3.0</a>) A:U</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['calculations']; ?>" target='_blank'>GL4c (Calculations) Calculations</a>
		A:CB</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php">calculations.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php">calculations.php</a></td>
</tr>

<tr>
	<td>Ranked Play List</td>
	<td>Table</td>
	<td>List of games based on Statistics "Points" <br/> Statistics Points are determined by counting the title occurance in the stats (N83 - P107) which covers Least & Second least of the following vlaues: 
		<ul><li>Hrs to next position when sorted by alt sale $/hr
		</li><li>Hrs to MSRP Target/hr (Arbitrarily set to $3)
		</li><li>Hrs to MSRP Avg/hr
		</li><li>Hrs to MSRP Mean/hr
		</li><li>Hrs to MSRP Median/hr
		</li><li>Hrs to MSRP [Game] (Most played game current $/hr vlaue)
		</li><li>Hrs to Sale Target/hr
		</li><li>Hrs to Sale Avg/hr
		</li><li>Hrs to Sale Mean/hr
		</li><li>Hrs to Sale Median/hr
		</li><li>Hrs to Sale [Game]</li></ul>
		Each value is calculated for all played games and entire library. (This duplication may not be relevant when majority of library is played, results would be the same) Alternately Status:Active could be used.
	<td>Statistics</td>
	<td class="gl1"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["1"]['dashboard']; ?>" target='_blank'>GL1 Dashboard</a>
		(<a href="<?php echo $sheetURLs["1.0"]['dashboard']; ?>" target='_blank'>1.0</a>) A:E</td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) L:R & A29:F52</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/dashboard.php">dashboard.php</a> INCOMPLETE</td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Purchases</td>
	<td>Table</td>
	<td>Library of all purchased games and some calculations related to the price.</td>
	<td></td>
	<td class="gl1"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["1"]['library']; ?>" target='_blank'>GL1 Library</a>
		(<a href="<?php echo $sheetURLs["1.0"]['library']; ?>" target='_blank'>1.0</a>) A:M<br/>
		<img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/export-gl1.php">export-gl1.php</a> (GL5)</td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['purchases']; ?>" target='_blank'>GL2 Purchases</a>
		(<a href="<?php echo $sheetURLs["2.0"]['purchases']; ?>" target='_blank'>2.0</a>) A:AC</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['purchases']; ?>" target='_blank'>GL3 Purchases</a>
		(<a href="<?php echo $sheetURLs["3.0"]['purchases']; ?>" target='_blank'>3.0</a>) A:AQ<br/>
		<img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/export-gl3-purchases.php">export-gl3-purchases.php</a> (GL5)</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['purchases']; ?>" target='_blank'>GL4b (Purchases) Purchases</a>
		A:AI</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php?Group=Bundle">toplevel.php</a> Group: Bundle</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php?Group=Bundle">toplevel.php</a> Group: Bundle</td>
</tr>

<tr>
	<td>Wish List</td>
	<td>List</td>
	<td>A list of games not yet in the library and why I would want to play them. Compiled from various "Best of" lists on the web with references to the original articles.</td>
	<td></td>
	<td class="gl1"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["1"]['wishlist']; ?>" target='_blank'>GL1 Wish List</a>
		(<a href="<?php echo $sheetURLs["1.0"]['wishlist']; ?>" target='_blank'>1.0</a>) A:T</td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Settings</td>
	<td>Page</td>
	<td>Configurable options for how calculations are executed</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['settings']; ?>" target='_blank'>GL2 Settings</a>
		(<a href="<?php echo $sheetURLs["2.0"]['settings']; ?>" target='_blank'>2.0</a>) C:K</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['settings']; ?>" target='_blank'>GL3 Settings</a>
		(<a href="<?php echo $sheetURLs["3.0"]['settings']; ?>" target='_blank'>3.0</a>) D:M</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['settings']; ?>" target='_blank'>GL4b (Purchases) Settings</a>
		A:M</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/settings.php">settings.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/settings.php">settings.php</a></td>
</tr>

<tr>
	<td>Game List</td>
	<td>List</td>
	<td>List of all games and calculated values for each</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['settings']; ?>" target='_blank'>GL2 Settings</a>
		(<a href="<?php echo $sheetURLs["2.0"]['settings']; ?>" target='_blank'>2.0</a>) A:A</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['games']; ?>" target='_blank'>GL3 Games</a>
		(<a href="<?php echo $sheetURLs["3.0"]['games']; ?>" target='_blank'>3.0</a>) A:AE</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['games']; ?>" target='_blank'>GL4b (Purchases) Games</a>
		A:AG</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Title&dir=3&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Title&dir=3&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>History</td>
	<td>Table</td>
	<td>Historical log of game activity by title</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["2"]['activity']; ?>" target='_blank'>GL2 Activity</a>
		(<a href="<?php echo $sheetURLs["2.0"]['activity']; ?>" target='_blank'>2.0</a>) A:L</td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['tracker']; ?>" target='_blank'>GL3 Tracker</a>
		(<a href="<?php echo $sheetURLs["3.0"]['tracker']; ?>" target='_blank'>3.0</a>) A:AN<br/>
		<img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/export-gl3-tracker.php">export-gl3-tracker.php</a> (GL5)</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['activity']; ?>" target='_blank'>GL4a (History) Activity</a>
		A:AQ</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/history.php">history.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/viewallhistory.php">viewallhistory.php</a></td>
</tr>

<tr>
	<td>Activity</td>
	<td>Table</td>
	<td>Calculated activity totals based on Historical data</td>
	<td>History</td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['activity']; ?>" target='_blank'>GL3 Activity</a>
		(<a href="<?php echo $sheetURLs["3.0"]['activity']; ?>" target='_blank'>3.0</a>) A:Z</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['activitystats']; ?>" target='_blank'>GL4a (History) Activity Stats</a>
		A:S</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/activity.php">activity.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/activity.php">activity.php</a></td>
</tr>

<tr>
	<td>History Summary</td>
	<td>Chart Data</td>
	<td>Presents Historical Data in totals by day, week, month, year</td>
	<td>History</td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['trackerstats']; ?>" target='_blank'>GL3 Tracker Stats</a>
		(<a href="<?php echo $sheetURLs["3.0"]['trackerstats']; ?>" target='_blank'>3.0</a>) A:AH</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['historydata']; ?>" target='_blank'>GL4a (History) History Data</a>
		A:I</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Notes</td>
	<td>Page</td>
	<td>Links to resources</td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['notes']; ?>" target='_blank'>GL3 Notes</a>
		(<a href="<?php echo $sheetURLs["3.0"]['notes']; ?>" target='_blank'>3.0</a>) A:C</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['notes']; ?>" target='_blank'>GL4b (Purchases) Notes</a>
		A:B</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gl5.php">gl5.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gl6.php">gl6.php</a></td>
</tr>

<tr>
	<td>Next Play List (Based on Statistics)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['notes']; ?>" target='_blank'>GL3 Notes</a>
		(<a href="<?php echo $sheetURLs["3.0"]['notes']; ?>" target='_blank'>3.0</a>) G:R</td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Chart: Metascores</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['notes']; ?>" target='_blank'>GL3 Notes</a>
		(<a href="<?php echo $sheetURLs["3.0"]['notes']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['dashboard']; ?>" target='_blank'>GL4b (Purchases) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/ratings.php">ratings.php</a> Chart</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/ratings.php">ratings.php</a> Chart</td>
</tr>

<tr>
	<td>Chart Data: Metascores</td>
	<td>Chart Data</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['notes']; ?>" target='_blank'>GL3 Notes</a>
		(<a href="<?php echo $sheetURLs["3.0"]['notes']; ?>" target='_blank'>3.0</a>) T:V</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['chartdata']; ?>" target='_blank'>GL4b (Purchases) Chart Data</a>
		W:Y (<a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>4c</a> AB:AD)</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/ratings.php">ratings.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/ratings.php">ratings.php</a></td>
</tr>

<tr>
	<td>Chart: Purchase History</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['dashboard']; ?>" target='_blank'>GL4b (Purchases) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/chartdata.php">chartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl5/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/chartdata.php">chartdata.php</a></td>
</tr>

<tr>
	<td>Card Value Stats</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) A54:F88</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['chartdata']; ?>" target='_blank'>GL4b (Purchases) Chart Data</a>
		N7:O16 (<a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>4c</a> S7:T15)</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/cardtotals.php">cardtotals.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl5/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/datacheck.php">datacheck.php</a></td>
</tr>

<tr>
	<td>Ranked Play List (Summary)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) V:AB</td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Chart: Played vs. Unplayed</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/totals.php">totals.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/totals.php">totals.php</a></td>
</tr>

<tr>
	<td>Chart: Top played of all time (25?)</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplists.php">toplists.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplists.php">toplists.php</a></td>
</tr>

<tr>
	<td>Chart: Played vs. Unplayed (Detail)</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/totals.php">totals.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/totals.php">totals.php</a></td>
</tr>

<tr>
	<td>Total Stats</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) AE3:AJ13</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['chartdata']; ?>" target='_blank'>GL4b (Purchases) Chart Data</a>
		P1:U3(<a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>4c</a> U1:Z3)</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/totals.php">totals.php</a></td>
	<td class="prototype"></td>
	<td><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/totals.php">totals.php</a></td>
</tr>

<tr>
	<td>Top 25 Played & Next 10</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['dashboard']; ?>" target='_blank'>GL3 Dashboard</a>
		(<a href="<?php echo $sheetURLs["3.0"]['dashboard']; ?>" target='_blank'>3.0</a>) AD18:AJ57</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['toplists']; ?>" target='_blank'>GL4c (Calculations) Top Lists</a>
		B:J</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=MostPlayed#tablestart">calculations.php</a> View: MostPlayed</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=MostPlayed#tablestart">calculations.php</a> View: MostPlayed</td>
</tr>

<tr>
	<td>All Games Sorted by: Last Played Game</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) A:B</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		C:D</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Status,eq,Done,GrandTotal,eq,0&sort=lastplaySort&dir=3&col=Title,All%20Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Status,eq,Done,GrandTotal,eq,0&sort=lastplaySort&dir=3&col=Title,All%20Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Last Played Active Game</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) C:D</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		E:F</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,ne,Active,GrandTotal,eq,0&sort=lastplaySort&dir=4&col=Title,All%20Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,ne,Active,GrandTotal,eq,0&sort=lastplaySort&dir=4&col=Title,All%20Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Last Played / Purchased of All</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) E:F</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		F:G</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=LastPlayORPurchase&dir=4&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,lastplay,LastPlayORPurchase,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=LastPlayORPurchase&dir=4&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,lastplay,LastPlayORPurchase,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Unplayd Achivements</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) G:H</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		I:J</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>All Games Sorted by: Last Played Series</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) I:J</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		K:L</strike></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Active Games Sorted by: Paid $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) K:L</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		M:N</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Paidperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Paidperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Active Games Sorted by: Launch Price $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) M:N</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		AA:AB</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Launchperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Launchperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Active Games Sorted by: MSRP $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) O:P</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		Q:R</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=MSRPperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=MSRPperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Active Games Sorted by: Historic Low $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) Q:R</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		S:T</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Historicperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Historicperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Active Games Sorted by: Sale Price $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) S:T</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		U:V</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Saleperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Saleperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Active Games Sorted by: Alt Sale $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) U:V</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		W:X</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Altperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Altperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Paid $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) W:X</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		Y:Z</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Paidperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Paidperhr&dir=3&hide=Playable,eq,0,Status,ne,Active&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Launch Price $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) Y:Z</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Launchperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>All Games Sorted by: MSRP $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) AA:AB</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		AC:AD</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=MSRPperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=MSRPperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Historic Low $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) AC:AD</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		AE:AF</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Historicperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Historicperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Sale Price $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) AE:AF</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		AG:AH</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Saleperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Saleperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>All Games Sorted by: Alt Sale $/hr</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['sortedlists']; ?>" target='_blank'>GL3 Sorted Lists</a>
		(<a href="<?php echo $sheetURLs["3.0"]['sortedlists']; ?>" target='_blank'>3.0</a>) AG:AH</td>
	<td class="gl4"><?php echo $sheeticon; ?><strike><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		AJ:AK</strike></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&sort=Altperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&sort=Altperhr&dir=3&hide=Playable,eq,0&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2">
	calculations.php</a> Dynamic List</td>
</tr>

<tr>
	<td>Chart: 2 Weeks Activity</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['trackerstats']; ?>" target='_blank'>GL3 Tracker Stats</a>
		(<a href="<?php echo $sheetURLs["3.0"]['trackerstats']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Chart: Historical Activity by week</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['trackerstats']; ?>" target='_blank'>GL3 Tracker Stats</a>
		(<a href="<?php echo $sheetURLs["3.0"]['trackerstats']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Chart: Historical Activity by month</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['trackerstats']; ?>" target='_blank'>GL3 Tracker Stats</a>
		(<a href="<?php echo $sheetURLs["3.0"]['trackerstats']; ?>" target='_blank'>3.0</a>) Chart</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Waste Summary Stats</td>
	<td>Page</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['waste']; ?>" target='_blank'>GL3 Waste</a>
		(<a href="<?php echo $sheetURLs["3.0"]['waste']; ?>" target='_blank'>3.0</a>) A2:I5</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['waste']; ?>" target='_blank'>GL4c (Calculations) Waste</a>
		A1:H4</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/waste.php">waste.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/waste.php">waste.php</a></td>
</tr>

<tr>
	<td>Waste: Biggest Overpaid</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['waste']; ?>" target='_blank'>GL3 Waste</a>
		(<a href="<?php echo $sheetURLs["3.0"]['waste']; ?>" target='_blank'>3.0</a>) A8:B21</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['waste']; ?>" target='_blank'>GL4c (Calculations) Waste</a>
		A7:B16</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/waste.php">waste.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/waste.php">waste.php</a></td>
</tr>

<tr>
	<td>Waste: Biggest Unplayed</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['waste']; ?>" target='_blank'>GL3 Waste</a>
		(<a href="<?php echo $sheetURLs["3.0"]['waste']; ?>" target='_blank'>3.0</a>) E8:F21</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['waste']; ?>" target='_blank'>GL4c (Calculations) Waste</a>
		D7:E16</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/waste.php">waste.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/waste.php">waste.php</a></td>
</tr>

<tr>
	<td>Waste: Oldest Unplayed</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['waste']; ?>" target='_blank'>GL3 Waste</a>
		(<a href="<?php echo $sheetURLs["3.0"]['waste']; ?>" target='_blank'>3.0</a>) H8:I21</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['waste']; ?>" target='_blank'>GL4c (Calculations) Waste</a>
		G7:H16</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/waste.php">waste.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/waste.php">waste.php</a></td>
</tr>

<tr>
	<td>Top Level</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['toplevel']; ?>" target='_blank'>GL3 Top Level</a>
		(<a href="<?php echo $sheetURLs["3.0"]['toplevel']; ?>" target='_blank'>3.0</a>) A:AC</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['toplevel']; ?>" target='_blank'>GL4c (Calculations) Top Level</a>
		A:AE</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php">toplevel.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php">toplevel.php</a></td>
</tr>

<tr>
	<td>Top Level: Average % Played of bundles</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['toplevel']; ?>" target='_blank'>GL3 Top Level</a>
		(<a href="<?php echo $sheetURLs["3.0"]['toplevel']; ?>" target='_blank'>3.0</a>) AE:AE</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php">toplevel.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php">toplevel.php</a></td>
</tr>

<tr>
	<td>Top Level: 1 More Game (of Bundles)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['toplevel']; ?>" target='_blank'>GL3 Top Level</a>
		(<a href="<?php echo $sheetURLs["3.0"]['toplevel']; ?>" target='_blank'>3.0</a>) AF:AF</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php">toplevel.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php">toplevel.php</a></td>
</tr>

<tr>
	<td>Top Level: Average % Played of all games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['toplevel']; ?>" target='_blank'>GL3 Top Level</a>
		(<a href="<?php echo $sheetURLs["3.0"]['toplevel']; ?>" target='_blank'>3.0</a>) AG:AG</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php">toplevel.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php">toplevel.php</a></td>
</tr>

<tr>
	<td>Top Level: 1 More Game (of Games)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['toplevel']; ?>" target='_blank'>GL3 Top Level</a>
		(<a href="<?php echo $sheetURLs["3.0"]['toplevel']; ?>" target='_blank'>3.0</a>) AH:AH</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php">toplevel.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php">toplevel.php</a></td>
</tr>

<tr>
	<td>Series</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['series']; ?>" target='_blank'>GL3 Series</a>
		(<a href="<?php echo $sheetURLs["3.0"]['series']; ?>" target='_blank'>3.0</a>) A:X</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php?Group=Series">toplevel.php</a> Group:Series</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php?Group=Series">toplevel.php</a> Group:Series</td>
</tr>

<tr>
	<td>Daily Calendar Data</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['history']; ?>" target='_blank'>GL3 History</a>
		(<a href="<?php echo $sheetURLs["3.0"]['history']; ?>" target='_blank'>3.0</a>) A:I</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['history']; ?>" target='_blank'>GL4c (Calculations) History</a>
		A:M</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Active Game List</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['history']; ?>" target='_blank'>GL3 History</a>
		(<a href="<?php echo $sheetURLs["3.0"]['history']; ?>" target='_blank'>3.0</a>) L:O</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Active">calculations.php</a> View: Active</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Active">calculations.php</a> View: Active</td>
</tr>

<tr>
	<td>To Do List</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['settings']; ?>" target='_blank'>GL3 Settings</a>
		(<a href="<?php echo $sheetURLs["3.0"]['settings']; ?>" target='_blank'>3.0</a>) D21:D33</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['settings']; ?>" target='_blank'>GL4b (Purchases) Settings</a>
		A21:A37</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gl5.php">gl5.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gl6.php">gl6.php</a></td>
</tr>

<tr>
	<td>CPI Summary by Year</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['settings']; ?>" target='_blank'>GL3 Settings</a>
		(<a href="<?php echo $sheetURLs["3.0"]['settings']; ?>" target='_blank'>3.0</a>) A:B</td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Uncounted Games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['settings']; ?>" target='_blank'>GL3 Settings</a>
		(<a href="<?php echo $sheetURLs["3.0"]['settings']; ?>" target='_blank'>3.0</a>) N:N</td>
	<td class="gl4"></td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Genres</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['genres']; ?>" target='_blank'>GL3 Genres</a>
		(<a href="<?php echo $sheetURLs["3.0"]['genres']; ?>" target='_blank'>3.0</a>) A:F</td>
	<td class="gl4"></td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/toplevel.php?Group=Keyword">toplevel.php</a> Group:Keyword</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/toplevel.php?Group=Keyword">toplevel.php</a> Group:Keyword</td>
</tr>

<tr>
	<td>Hardware</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['hardware']; ?>" target='_blank'>GL3 Hardware</a>
		(<a href="<?php echo $sheetURLs["3.0"]['hardware']; ?>" target='_blank'>3.0</a>) A:F</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['hardware']; ?>" target='_blank'>GL4b (Purchases) Hardware</a>
		A:E</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>CPI Data</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['cpi']; ?>" target='_blank'>GL3 CPI</a>
		(<a href="<?php echo $sheetURLs["3.0"]['cpi']; ?>" target='_blank'>3.0</a>) A:F</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['cpi']; ?>" target='_blank'>GL4b (Purchases) CPI</a>
		A:P</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/cpi.php">cpi.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/cpi.php">cpi.php</a></td>
</tr>

<tr>
	<td>GOTY - Most Played</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['goty']; ?>" target='_blank'>GL3 GOTY</a>
		(<a href="<?php echo $sheetURLs["3.0"]['goty']; ?>" target='_blank'>3.0</a>) G:H</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['goty']; ?>" target='_blank'>GL4a (History) GOTY</a>
		H:I</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"><a href="<?php echo $GLOBALS['rootpath'];?>/chartdata.php?group=year">chartdata.php</a> Group: Year</td>
</tr>

<tr>
	<td>GOTY - Most Frequent</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['goty']; ?>" target='_blank'>GL3 GOTY</a>
		(<a href="<?php echo $sheetURLs["3.0"]['goty']; ?>" target='_blank'>3.0</a>) J:K</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['goty']; ?>" target='_blank'>GL4a (History) GOTY</a>
		K:L</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>GOTY - Most Played (Purchased / First Play in year)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['goty']; ?>" target='_blank'>GL3 GOTY</a>
		(<a href="<?php echo $sheetURLs["3.0"]['goty']; ?>" target='_blank'>3.0</a>) M:N</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['goty']; ?>" target='_blank'>GL4a (History) GOTY</a>
		N:O</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"><a href="<?php echo $GLOBALS['rootpath'];?>/chartdata.php?group=year">chartdata.php</a> Group: Year</td>
</tr>

<tr>
	<td>GOTY - Most Frequent (Purchased / First Play in year)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"><td>
	<td class="gl3"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["3"]['goty']; ?>" target='_blank'>GL3 GOTY</a>
		(<a href="<?php echo $sheetURLs["3.0"]['goty']; ?>" target='_blank'>3.0</a>) P:Q</td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['goty']; ?>" target='_blank'>GL4a (History) GOTY</a>
		Q:R</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Top Played Last 7 Days</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gamestatuschart.php?preset=7days">gamestatuschart.php</a> View: 7days</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gamestatuschart.php?preset=7days">gamestatuschart.php</a> View: 7days</td>
</tr>

<tr>
	<td>Chart: Historical Activity by year</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/historicchartdata.php">historicchartdata.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/historicchartdata.php">historicchartdata.php</a></td>
</tr>

<tr>
	<td>Top Played Last 30 Days</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gamestatuschart.php?preset=30days">gamestatuschart.php</a> View: 30days</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gamestatuschart.php?preset=30days">gamestatuschart.php</a> View: 30days</td>
</tr>

<tr>
	<td>Top Played Last 365 Days</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gamestatuschart.php?preset=1year">gamestatuschart.php</a> View: 1year</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gamestatuschart.php?preset=1year">gamestatuschart.php</a> View: 1year</td>
</tr>

<tr>
	<td>Chart: Games by Status (include unplayed)</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gamestatuschart.php">gamestatuschart.php</a> Chart</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gamestatuschart.php">gamestatuschart.php</a> Chart</td>
</tr>

<tr>
	<td>Chart: Games by Status (Exclude Unplayed)</td>
	<td>Chart</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4a"]['dashboard']; ?>" target='_blank'>GL4a (History) Dashboard</a>
		Chart</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/gamestatuschart.php">gamestatuschart.php</a> Chart</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/gamestatuschart.php">gamestatuschart.php</a> Chart</td>
</tr>

<tr>
	<td>Upgrade</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['upgrade']; ?>" target='_blank'>GL4b (Purchases) Upgrade</a>
		A:H</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,OtherLibrary,eq,0&sort=Title&dir=3&col=Title,MainLibrary,All%20Bundles,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review#tablestart">
	calculations.php</a> INCOMPLETE</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,OtherLibrary,eq,0&sort=Title&dir=3&col=Title,MainLibrary,All%20Bundles,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review#tablestart">
	calculations.php</a> INCOMPLETE</td>
</tr>

<tr>
	<td>Sale Card Distribution Calculator</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['notes']; ?>" target='_blank'>GL4b (Purchases) Notes</a>
		E:H</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Export (Keys for Trade)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['export']; ?>" target='_blank'>GL4b (Purchases) Export</a>
		A:A</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Inactive,eq,0&sort=Title&dir=3&col=Title,Key,All%20Bundles,PurchaseDate,GrandTotal,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">
	calculations.php</a> Dynamic View</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Inactive,eq,0&sort=Title&dir=3&col=Title,Key,All%20Bundles,PurchaseDate,GrandTotal,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">
	calculations.php</a> Dynamic View</td>
</tr>

<tr>
	<td>Export (Insallers for Share)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4b"]['export']; ?>" target='_blank'>GL4b (Purchases) Export</a>
		B:E</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,DrmFree,eq,0&sort=Title&dir=3&col=Title,DrmFreeLibrary,DrmFreeSize,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">
	calculations.php</a> Dynamic View</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,DrmFree,eq,0&sort=Title&dir=3&col=Title,DrmFreeLibrary,DrmFreeSize,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">
	calculations.php</a> Dynamic View</td>
</tr>

<tr>
	<td>Game Closest to end</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['sortedlists']; ?>" target='_blank'>GL4c (Calculations) Sorted Lists</a>
		A:B</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,TimeLeftToBeat,eq,0&sort=TimeLeftToBeat&dir=4&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">
	calculations.php</a> Dynamic View</td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,TimeLeftToBeat,eq,0&sort=TimeLeftToBeat&dir=4&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">
	calculations.php</a> Dynamic View</td>
</tr>

<tr>
	<td>Waste: Games from Overpaid Bundles (Sort by low)</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['waste']; ?>" target='_blank'>GL4c (Calculations) Waste</a>
		P:Q</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/waste.php">waste.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/waste.php">waste.php</a></td>
</tr>

<tr>
	<td>Play Next List (based on unplayed )</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['playnext']; ?>" target='_blank'>GL4c (Calculations) Play Next</a>
		F:F</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/playnext.php">playnext.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/playnext.php">playnext.php</a></td>
</tr>

<tr>
	<td>Play Next List (based on unplayed ) - Sort by Critic</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['playnext']; ?>" target='_blank'>GL4c (Calculations) Play Next</a>
		O:O</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/playnext.php">playnext.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/playnext.php">playnext.php</a></td>
</tr>

<tr>
	<td>Play Next List (based on unplayed ) - Sort by User</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['playnext']; ?>" target='_blank'>GL4c (Calculations) Play Next</a>
		P:P</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/playnext.php">playnext.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/playnext.php">playnext.php</a></td>
</tr>

<tr>
	<td>Play Next List (based on unplayed ) - Sort by Total Review</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['playnext']; ?>" target='_blank'>GL4c (Calculations) Play Next</a>
		Q:Q</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/playnext.php">playnext.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/playnext.php">playnext.php</a></td>
</tr>

<tr>
	<td>Play Next List (based on unplayed ) - Sort by Points</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['playnext']; ?>" target='_blank'>GL4c (Calculations) Play Next</a>
		R:S</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/playnext.php">playnext.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/playnext.php">playnext.php</a></td>
</tr>

<tr>
	<td>Buy vs Play Calculator</td>
	<td>Table</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		V7:W16</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Missing Games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		J:J</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Extra Games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		K:K</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Steam List</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		L:L</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Extra Steam</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		M:M</td>
	<td class="gl5"><img src="/gl5/img/favicon.ico" height=15 /><a href="http://games.stuffiknowabout.com/gl5/steamapi_ownedgames.php">steamapi_ownedgames.php</a></td>
	<td class="prototype"></td>
	<td class="gl6"><img src="/gl6/img/favicon.ico" height=15 /><a href="<?php echo $GLOBALS['rootpath'];?>/steamapi_ownedgames.php">steamapi_ownedgames.php</a></td>
</tr>

<tr>
	<td>Rename: GOG List</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		N:N</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Extra GOG</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		O:O</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Purchases Missing from Games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		Q:Q</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Purchases (DLC) Missing from Games</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		R:R</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Games Missing from Purchases</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		S:S</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Rename: Activity not Purchased</td>
	<td>List</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"><?php echo $sheeticon; ?><a href="<?php echo $sheetURLs["4c"]['chartdata']; ?>" target='_blank'>GL4c (Calculations) Chart Data</a>
		T:T</td>
	<td class="gl5"></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Database diagram</td>
	<td>Documentation</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="https://ssl.gstatic.com/docs/drawings/images/favicon5.ico">
	<a href="https://docs.google.com/drawings/d/1fR3an7xfkCjNwYq4ie0aQWiFXl32xrodD7w9POQuXvA/edit" target='_blank'>
	GL5 Database Diagram</a></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

<tr>
	<td>Data structures</td>
	<td>Documentation</td>
	<td></td>
	<td></td>
	<td class="gl1"></td>
	<td class="gl2"></td>
	<td class="gl3"></td>
	<td class="gl4"></td>
	<td class="gl5"><img src="https://ssl.gstatic.com/docs/drawings/images/favicon5.ico">
	<a href="https://docs.google.com/drawings/d/1-E-fAQHenwWilWrar21BGG-IRiXF_BXTefTDos-7-d8/edit" target='_blank'>
	GL5 Data Structures</a></td>
	<td class="prototype"></td>
	<td class="gl6"></td>
</tr>

</table>

<?php echo Get_Footer(); ?>
