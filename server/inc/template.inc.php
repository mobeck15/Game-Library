<?php
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";

/*
 * Creates an HTML & CSS Header for each page to ensure uniform look & Feel 
 * Some code is still required to set up a new page, see example below.
 * 
 * include 'inc/functions.inc.php';
 * $title="Settings";
 * echo Get_Header($title);
 */
 
function Get_Header($title="",$WIP=""){
	//header('Content-Type: text/html; charset=utf-8');
	 
	$default_title="Game Library v6";
	
	if($title=="") {
		$title=$default_title;
	} else {
		$title = $title . " - " . $default_title;
	}
	
	$Template_Header="<HTML>
	<HEAD>
	<title>$title</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
	<link rel=\"shortcut icon\" href=\"".$GLOBALS['rootpath']."/img/favicon.ico\"/>";
	$Template_Header .= "\n	<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/3.11.0/build/cssnormalize/cssnormalize-min.css\">";
	$Template_Header .= "\n	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$GLOBALS['rootpath']."/css/style.css\">";
	
	//Needed to support the dynamic navigation menu. 
	//There is a conflict with something in style.css that makes it act weird if style.css is loaded after this one.
	$Template_Header .= "\n	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$GLOBALS['rootpath']."/css/menu_style2.css\">";

	//Needed to support dynamic lookups
	$Template_Header .= "\n	<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css\" type=\"text/css\" /> ";
	
	$Template_Header .= "
	<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\"></script>";
	$Template_Header .= "
	</HEAD>
	<BODY>";
	
	/* START NAVIGATION */
	/* Multi Level CSS https://www.cssscript.com/create-a-multi-level-drop-down-menu-with-pure-css/ */
	$Template_Header .= get_navmenu(true);
	$Template_Header .= "<div class='main'>";
	
	/* END NAVIGATION */
	
	if($WIP=="WIP"){
		$Template_Header .= "\r\n<div style='background:yellow;color:black'>WORK IN PROGRESS</div>\r\n";
	}
	
	return $Template_Header;
}


/*
 * Creates an HTML & CSS Footer for each page to ensure uniform look & Feel 
 */
function Get_Footer($WIP=""){
	if($WIP=="WIP"){
		$WIP="\r\n<div style='background:yellow;color:black'>WORK IN PROGRESS</div>\r\n";
	}
	$time = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],3);
	$Template_Footer=$WIP."<div class='foot'><span class='execTime'>Loaded in ".$time." Seconds</class><br/>
	Memory Used: ".read_memory_usage();
	$Template_Footer .="</div>";
	
	$Template_Footer .= '<a href="https://github.com/SamKirkland/FTP-Deploy-Action"><img alt="Website Deployed for Free with FTP Deploy Action" src="https://img.shields.io/badge/Website deployed for free with-FTP DEPLOY ACTION-%3CCOLOR%3E?style=for-the-badge&color=2b9348"></a>';
	
	$Template_Footer .="</div>
	</BODY>
	</HTML>";

	
	return($Template_Footer);
}

function get_navmenu($dropbar=true){
	if($dropbar) {
		$navmenu  = "\r\n\r\n<div id='main_nav' class='top-nav'>\r\n";
	} else {
		$navmenu  = "<div>\r\n";
	}
		$navmenu .= "\t<ul class='main-navigation'>\r\n";
//CONTROL
		$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/gl6.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Control</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href='games.stuffiknowabout.com/gl5/gl5.php' target='_blank'>GL5 Index<img src='".$GLOBALS['rootpath']."/img/new_window-512.png' height=15 /></a></li>\r\n";
			//$navmenu .= "\t\t<li><a href=\"http://www.uniformserver.com/\" target=\"_blank\">Uniform Server<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"/phpinfo.php\" target='_blank'>PHP Info<img src='".$GLOBALS['rootpath']."/img/new_window-512.png' height=15 /></a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/prototype\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Prototypes</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/settings.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Settings</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/prototype/test.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Tests <img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
			//$navmenu .= "\t\t<li><a >Tests <img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
			$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getGames.class.php\">Get Games</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getCalculations.inc.php\">Get Calculations</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getPurchases.class.php\">Get Purchases</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getActivityCalculations.inc.php\">Get Activity Calculations</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getHistoryCalculations.inc.php\">Get History Calculations</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getTopList.class.php\">Get Top List</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/inc/getSettings.inc.php\">Get Settings</a></li>\r\n";
			$navmenu .= "\t</ul>\r\n";
			$navmenu .= "</li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
//MANAGE DATABASE
		$navmenu .= "\t<li><a>Manage Database</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			//if($_SERVER['SERVER_NAME']=="localhost"){
			if(($_SERVER['SERVER_NAME'] ?? "localhost") == "localhost") {
				//$navmenu .= "\t\t<li><a href=\"/us_opt1/\" target=\"_blank\">uniserver phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
				$navmenu .= "\t\t<li><a href=\"/phpmyadmin/\" target=\"_blank\">local phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			} else {
				//https://west1-phpmyadmin.dreamhost.com/index.php
				$navmenu .= "\t\t<li><a href=\"http://data.stuffiknowabout.com\" target=\"_blank\">dreamhost phpMyAdmin<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			}
			$navmenu .= "\t\t<li><a href=\"https://www.dropbox.com/home/web/uniserverz/www/gl6\" target=\"_blank\">Dropbox<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /><img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
				$navmenu .= "\t\t\t<ul><li><a href=\"https://www.dropbox.com/home/web/uniserverz/etc/phpmyadmin\" target=\"_blank\">Database Backups<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li></ul>\r\n";
			$navmenu .= "\t\t</li>\r\n";
			$navmenu .= "\t\t<li><a >Add<img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
			$navmenu .= "\t\t<ul>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/addtransaction.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Add New Transaction (Bundle)</a></li>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/addproduct.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Add New Product (Game)</a></li>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/additem.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Add New Item</a></li>\r\n";
			$navmenu .= "\t\t</ul></li>\r\n";
			$navmenu .= "\t\t<li><a >View/Edit<img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
			$navmenu .= "\t\t<ul>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/viewbundle.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />View/Edit Bundle</a></li>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/viewgame.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />View/Edit Game</a></li>\r\n";
				$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/viewitem.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />View/Edit Item</a></li>\r\n";
			$navmenu .= "\t\t</ul></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/datacheck.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Data Check</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/steamapi_ownedgames.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />All Steam Games</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/cpi.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />CPI<img src=\"".$GLOBALS['rootpath']."/img/caret-right.png\" height=15 /></a>\r\n";
			$navmenu .= "\t\t<ul>\r\n";
				$navmenu .= "\t\t\t<li><a href='http://www.usinflationcalculator.com/inflation/consumer-price-index-and-annual-percent-changes-from-1913-to-2008/' target='_blank'>CPI Table<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			$navmenu .= "\t\t</ul></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
//HISTORY
		$navmenu .= "\t<li><a>History</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/addhistory.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Add History (Manual)</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/addhistory.php?mode=steam\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Add History (Steam API)</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/viewallhistory.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />View All History</a></li>\r\n";
			$navmenu .= "\t\t\t<li><a href=\"".$GLOBALS['rootpath']."/activity.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />All Activity</a></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
//REPORTS
		$navmenu .= "\t<li><a>Reports</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/statistics.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Statistics</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/calculations.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Calculations</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/toplists.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Top Lists</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/toplevel.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Group by ___</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/chartdata.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Chart Data (Calendar)</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/totals.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Total Stats</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/historicchartdata.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Historic Charts</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/ratings.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Ratings</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/waste.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Waste</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/playnext.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Play Next</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/playnext2.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Play Next 2</a></li>\r\n";
			$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/gamestatuschart.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />Status Charts</a></li>\r\n";
			//$navmenu .= "\t\t<li><a href=\"".$GLOBALS['rootpath']."/goty.php\"><img src=\"".$GLOBALS['rootpath']."/img/favicon.ico\" height=15 />GOTY</a></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
//REFERENCE
		$navmenu .= "\t<li><a>Reference</a>\r\n";
		$navmenu .= "\t<ul>\r\n";
			$navmenu .= "\t\t<li><a href='https://partner.steamgames.com/doc/api' target='_blank'>Steam Web API<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			$navmenu .= "\t\t<li><a href='https://steamcommunity.com/dev' target='_blank'>Steam Web API<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			$navmenu .= "\t\t<li><a href='https://wiki.teamfortress.com/wiki/User:RJackson/StorefrontAPI' target='_blank'>Steam Storefront API<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
			$navmenu .= "\t\t<li><a href='https://github.com/SteamRE/SteamKit' target='_blank'>SteamKit (GitHub)<img src=\"".$GLOBALS['rootpath']."/img/new_window-512.png\" height=15 /></a></li>\r\n";
		$navmenu .= "\t</ul></li>\r\n";
		$navmenu .= "\t</ul>\r\n";
	$navmenu .= "</div>";
	
	return $navmenu;
}

?>
