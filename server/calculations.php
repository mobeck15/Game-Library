<?php
include "inc/php.ini.inc.php";
include "inc/functions.inc.php";
$title="Calculations";
echo Get_Header($title);
//DONE: Floating divs don't float in gl6, fix the header stuff.
//TODO: Make a form menu for custom view

$conn=get_db_connection();

$settings=getsettings($conn);

?>
<style> 
.flex-container {
    display: -webkit-flex;
    display: flex;
	flex-wrap:wrap ;
}

.flex-item {
	margin: 10px;
}

.greencell a{
	color: black;
}
</style>



<div class="flex-container">
<div class="flex-item" style="order: 1">
<details>
<summary>
<b>Favorites</b>
</summary>
<ul>
	<li><a href="calculations.php#tablestart">Base</a> / 
	<a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Title&dir=4&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Custom</a>
	/ <a href="calculations.php?fav=Default#tablestart">Default</a>
	</li>
	<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Title&dir=3&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">All Games</a></li>
		<ul>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Status,eq,Done,GrandTotal,eq,0&sort=GrandTotal&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Most Played</a></li>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Status,eq,Done,GrandTotal,eq,0&sort=lastplaySort&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Last Played</a></li>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Saleperhr&dir=3&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">All Games Sorted by Alt Saleperhr</a></li>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Altperhr&dir=3&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">All Games Sorted by Alt AltSaleperhr</a></li>
		</ul>
	<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,ne,Active&sort=Title&dir=4&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Active Games</a></li>
		<ul>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,ne,Active,GrandTotal,eq,0&sort=lastplaySort&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Last Played Active</a></li>
		<li class="hidden"><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,ne,Active&sort=Saleperhr&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Active Games Sorted by Saleperhr</a></li>
		</ul>
	<li><a href="calculations.php?fav=Custom&hide=None&sort=DateUpdatedSort&dir=4&col=TitleEdit,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated#tablestart">Oldest-Updated</a></li>
		<ul>
		<li><a href="calculations.php?fav=Custom&hide=DateUpdated,gt,0&sort=PurchaseDate&dir=3&col=TitleEdit,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated#tablestart">Un-Updated</a></li>
		<li><a href="calculations.php?fav=Custom&hide=DateUpdated,gt,0,Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=PurchaseDate&dir=3&col=TitleEdit,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated#tablestart">Un-Updated (Counted)</a></li>
		<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,MSRP,ne,0,Paid,eq,0&sort=PurchaseDate&dir=3&col=TitleEdit,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,Status,lastplay,DateUpdated#tablestart">No Price</a></li>
		<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,TimeToBeat,eq,0&sort=PurchaseDate&dir=3&col=TitleEdit,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,Status,lastplay,DateUpdated#tablestart">No Time</a></li>
		</ul>
	<li class="hidden"><a href="calculations.php?fav=Custom&hide=lastplay,gt,0,Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=Saleperhr&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2#tablestart">Un-Played</a></li>
	<li><a href="calculations.php?fav=Custom&sort=LaunchDateValue&dir=4&hide=Playable,eq,0,LaunchDateValue,gt,946713600&col=Title,Type,ParentGame,Platforms,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,SalePrice,AltSalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,Altperhr,PaidLess1,SaleLess1,AltLess1,PaidLess2,SaleLess2,AltLess2">DOS?</a></li>
	<li><a href="calculations.php?fav=Custom&hide=SteamAchievements,eq,0,lastplay,gt,0,Playable,eq,0,Status,eq,Never,Status,eq,Broken&sort=SteamAchievements&dir=3&col=Title,All Bundles,PurchaseDate,LaunchPrice,MSRP,HistoricLow,Paid,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,Status,lastplay,DateUpdated,Paidperhr,Saleperhr,PaidLess1,SaleLess1,PaidLess2,SaleLess2,Achievements#tablestart">Un-Played with Achievements</a></li>
	<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,OtherLibrary,eq,0&sort=Title&dir=3&col=Title,MainLibrary,All%20Bundles,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review#tablestart">Upgrade List</a></li>
	<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,DrmFree,eq,0&sort=Title&dir=3&col=Title,DrmFreeLibrary,DrmFreeSize,PurchaseDate,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">DRM-Free</a></li>
	<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Inactive,eq,0&sort=Title&dir=3&col=Title,Key,All%20Bundles,PurchaseDate,GrandTotal,TimeToBeat,Metascore,MetaUser,Review,Paid#tablestart">Inactive</a></li>
	<li><a href="calculations.php?fav=Custom&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,TimeLeftToBeat,eq,0&sort=TimeLeftToBeat&dir=4&col=Title,PurchaseDate,GrandTotal,TimeToBeat,TimeLeftToBeat,AltSalePrice,Altperhr,AltLess1,AltLess2,AltHrsNext1,AltHrsNext2,Metascore,MetaUser,lastplay#tablestart">Least Time left to Beat</a></li>
	<li><a href="calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Review,lt,4,Status,eq,Done&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,SalePrice,AltSalePrice,TimeToBeat,Metascore,MetaUser,Review,GrandTotal,lastplay">4 Star Games not finished</a></li>
	<ul><li><a href="calculations.php?fav=Custom&sort=TimeLeftToBeat&dir=4&hide=Playable,eq,0,Review,lt,4,Status,eq,Done&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,SalePrice,AltSalePrice,TimeToBeat,TimeLeftToBeat,Metascore,MetaUser,Review,GrandTotal,lastplay">4 star by least time left</a></li>
	<li><a href="calculations.php?fav=Custom&sort=AltSalePrice&dir=4&hide=Playable,eq,0,Review,lt,4,Status,eq,Done&col=Title,Type,ParentGame,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,SalePrice,AltSalePrice,TimeToBeat,TimeLeftToBeat,Metascore,MetaUser,Review,GrandTotal,lastplay">4 star by Alt Price</a></li></ul>
	<li>Verify Inserts</li>
	<ul><li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=None&col=Title,Type,ParentGame,All%20Bundles,MainLibrary,LaunchDate,PurchaseDate,Want,TimeToBeat,Metascore,MetaUser">All by Purchase Date</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=LaunchDateValue&dir=3&hide=None&col=Title,Type,ParentGame,All%20Bundles,MainLibrary,OtherLibrary,LaunchDate,PurchaseDate,Want,TimeToBeat,Metascore,MetaUser">All by Launch Date</a></li></ul>
	
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed All</a></li>
	<ul><li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,Steam&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed Steam</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,GOG&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed GOG</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,Twitch&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed Twitch</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,Epic Games&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed Epic</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,Uplay&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed Uplay</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,ne,IndieGala&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed IndieGala</a></li>
	<li><a href="http://games.stuffiknowabout.com/gl6/calculations.php?fav=Custom&sort=PurchaseDate&dir=3&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0,MainLibrary,eq,IndieGala,MainLibrary,eq,Uplay,MainLibrary,eq,Epic Games,MainLibrary,eq,Twitch,MainLibrary,eq,GOG,MainLibrary,eq,Steam&col=Title,Type,ParentGame,MainLibrary,All%20Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay">Unplayed Other</a></li>
	</ul>
	
</ul>
</details>
</div>
<div class="flex-item" style="order: 2">
<details>
<summary>
Filters
</summary>
<?php 
$columns="&col=Title,Type,ParentGame,All Bundles,LaunchDate,PurchaseDate,LaunchPrice,Paid,SalePrice,AltSalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,Saleperhr,Altperhr,PaidLess1,SaleLess1,AltLess1,PaidLess2,SaleLess2,AltLess2";
$paidcolumns="&col=Title,Type,LaunchDate,PurchaseDate,Paid,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Paidperhr,PaidLess1,PaidLess2";
$launchcolumns="&col=Title,Type,LaunchDate,PurchaseDate,LaunchPrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Launchperhr,LaunchLess1,LaunchLess2";
$msrpcolumns="&col=Title,Type,LaunchDate,PurchaseDate,MSRP,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,MSRPperhr,MSRPLess1,MSRPLess2";
$histcolumns="&col=Title,Type,LaunchDate,PurchaseDate,HistoricLow,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Historicperhr,HistoricLess1,HistoricLess2";
$salecolumns="&col=Title,Type,LaunchDate,PurchaseDate,SalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Saleperhr,SaleLess1,SaleLess2";
$altcolumns="&col=Title,Type,LaunchDate,PurchaseDate,AltSalePrice,TimeToBeat,Metascore,MetaUser,GrandTotal,lastplay,Altperhr,AltLess1,AltLess2";
$nextcols="&col=Title,Type,All%20Bundles,Review,AltSalePrice,TimeToBeat,GrandTotal,Altperhr,AltLess1,AltLess2";
$lastplaycols="&col=Title,Type,All%20Bundles,Review,AltSalePrice,TimeToBeat,GrandTotal,Altperhr,AltLess1,AltLess2,LastPlayORPurchase";

/*http://games.stuffiknowabout.com/gl6/calculations.php?
fav=Custom&
sort=Altperhr&dir=3&
hide=Review,eq,1,Review,eq,2,Playable,eq,0,Status,eq,Broken,Status,eq,Never&
col=Title,Type,All%20Bundles,Review,AltSalePrice,TimeToBeat,GrandTotal,Altperhr,AltLess1,AltLess2
*/

$urlbase         = "calculations.php?fav=Custom";
$filterItems     = "&hide=None";
$filterGames     = "&hide=Playable,eq,0";
$filterGames2    = "&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken";
$filterActive    = "&hide=Playable,eq,0,Status,ne,Active";
$filterUnplayed  = "&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,GrandTotal,ne,0";
$extrafilternext = ",Review,eq,1,Review,eq,2";
$filterunbeat    = "&hide=Playable,eq,0,Status,eq,Never,Status,eq,Broken,Status,eq,Done";

$sortPaid = "&sort=Paidperhr&dir=3";
$sortLaunchp = "&sort=Launchperhr&dir=3";
$sortMSRP = "&sort=MSRPperhr&dir=3";
$sortHist = "&sort=Historicperhr&dir=3";
$sortSale = "&sort=Saleperhr&dir=3";
$sortAlt = "&sort=Altperhr&dir=3";
$sortLaunchd = "&sort=LaunchDateValue&dir=4"; 
$sortPurch = "&sort=PurchaseDate&dir=3"; 
$sortHrs = "&sort=GrandTotal&dir=3"; 
$sortPlay = "&sort=lastplaySort&dir=3"; 
$sortPlayP = "&sort=LastPlayORPurchase&dir=4"; 
$sortnext = "&sort=Altperhr&dir=3";

//DONE: fix colors in preset grid.
?>

<table>
	<tr><th>Sort Order</th><th>All Items</th><th>All Games</th><th>Games</th><th>Active Games</th><th>Un-Played Games</th><th>Un-Finished Games</th></tr>
	<tr><td>Paid/hr</td>
		<td><a href="<?php echo $urlbase.$sortPaid.$filterItems.$paidcolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortPaid.$filterGames.$paidcolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPaid.$filterGames2.$paidcolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPaid.$filterActive.$paidcolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPaid.$filterUnplayed.$paidcolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPaid.$filterunbeat.$paidcolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Launch Price/hr</td>
		<td><a href="<?php echo $urlbase.$sortLaunchp.$filterItems.$launchcolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortLaunchp.$filterGames.$launchcolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortLaunchp.$filterGames2.$launchcolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortLaunchp.$filterActive.$launchcolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortLaunchp.$filterUnplayed.$launchcolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortLaunchp.$filterunbeat.$launchcolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>MSRP/hr</td>
		<td><a href="<?php echo $urlbase.$sortMSRP.$filterItems.$msrpcolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortMSRP.$filterGames.$msrpcolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortMSRP.$filterGames2.$msrpcolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortMSRP.$filterActive.$msrpcolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortMSRP.$filterUnplayed.$msrpcolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortMSRP.$filterunbeat.$msrpcolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Historc/hr</td>
		<td><a href="<?php echo $urlbase.$sortHist.$filterItems.$histcolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortHist.$filterGames.$histcolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortHist.$filterGames2.$histcolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortHist.$filterActive.$histcolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHist.$filterUnplayed.$histcolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHist.$filterunbeat.$histcolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Sale/hr</td>
		<td><a href="<?php echo $urlbase.$sortSale.$filterItems.$salecolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortSale.$filterGames.$salecolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortSale.$filterGames2.$salecolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortSale.$filterActive.$salecolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortSale.$filterUnplayed.$salecolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortSale.$filterunbeat.$salecolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Alt Sale/hr</td>
		<td><a href="<?php echo $urlbase.$sortAlt.$filterItems.$altcolumns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortAlt.$filterGames.$altcolumns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortAlt.$filterGames2.$altcolumns;?>">Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortAlt.$filterActive.$altcolumns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortAlt.$filterUnplayed.$altcolumns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortAlt.$filterunbeat.$altcolumns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Launch Date</td>
		<td><a href="<?php echo $urlbase.$sortLaunchd.$filterItems.$columns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortLaunchd.$filterGames.$columns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortLaunchd.$filterGames2.$columns;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortLaunchd.$filterActive.$columns;?>">Active Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortLaunchd.$filterUnplayed.$columns;?>">Un-Played Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortLaunchd.$filterunbeat.$columns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Purchase Date</td>
		<td><a href="<?php echo $urlbase.$sortPurch.$filterItems.$columns;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortPurch.$filterGames.$columns;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPurch.$filterGames2.$columns;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPurch.$filterActive.$columns;?>">Active Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPurch.$filterUnplayed.$columns;?>">Un-Played Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPurch.$filterunbeat.$columns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Total Hours</td>
		<td><a href="<?php echo $urlbase.$sortHrs.$filterItems.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">All Items</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortHrs.$filterGames.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">All Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHrs.$filterGames2.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHrs.$filterActive.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHrs.$filterUnplayed.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortHrs.$filterunbeat.",Status,eq,Done,GrandTotal,eq,0".$columns;?>">Un-Finished Games</a></td></tr>
	<tr><td>Last Played</td>
		<td><a href="<?php echo $urlbase.$sortPlay.$filterItems.$lastplaycols;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortPlay.$filterGames.$lastplaycols;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPlay.$filterGames2.$lastplaycols;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlay.$filterActive.$lastplaycols;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlay.$filterUnplayed.$lastplaycols;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlay.$filterunbeat.$lastplaycols;?>">Un-Finished Games</a></td></tr>
	<tr><td>Last Played OR Purchased</td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterItems.$lastplaycols;?>,LastPlayORPurchase">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterGames.$lastplaycols;?>,LastPlayORPurchase">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPlayP.$filterGames2.$lastplaycols;?>,LastPlayORPurchase">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterActive.$lastplaycols;?>,LastPlayORPurchase">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterUnplayed.$lastplaycols;?>,LastPlayORPurchase">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterunbeat.$lastplaycols;?>,LastPlayORPurchase">Un-Finished Games</a></td></tr>
	<tr><td>Play Next (Alt Sale)</td>
		<td><a href="<?php echo $urlbase.$sortnext.$filterItems.$extrafilternext.$nextcols;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortnext.$filterGames.$extrafilternext.$nextcols;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortnext.$filterGames2.$extrafilternext.$nextcols;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortnext.$filterActive.$extrafilternext.$nextcols;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortnext.$filterUnplayed.$extrafilternext.$nextcols;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortnext.$filterunbeat.$extrafilternext.$nextcols;?>">Un-Finished Games</a></td></tr>
	<tr><td>Play Next (Last Play)</td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterItems.$extrafilternext.$lastplaycols;?>">All Items</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterGames.$extrafilternext.$lastplaycols;?>">All Games</a></td>
		<td class='greencell'><a href="<?php echo $urlbase.$sortPlayP.$filterGames2.$extrafilternext.$lastplaycols;?>">Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterActive.$extrafilternext.$lastplaycols;?>">Active Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterUnplayed.$extrafilternext.$lastplaycols;?>">Un-Played Games</a></td>
		<td><a href="<?php echo $urlbase.$sortPlayP.$filterunbeat.$extrafilternext.$lastplaycols;?>">Un-Finished Games</a></td></tr>
</table>
</details>
</div>
<a name="tablestart"></a>
<?php
$time_start = microtime(true);

$filter=array(
	'Columns' => array(
		"Title",
		"All Bundles",
		"PurchaseDate",
		"LaunchPrice",
		"MSRP",
		"HistoricLow",
		"Paid",
		"SalePrice",
		//"AltSale",
		"TimeToBeat",
		"Metascore",
		"MetaUser",
		"GrandTotal",
		"Status",
		"lastplay",
		"DateUpdated",
		"Paidperhr",
		"Saleperhr",
		"PaidLess1",
		"SaleLess1",
		"PaidLess2",
		"SaleLess2"
	),
	'Sortby' => "Title",
	'SortDir' => SORT_ASC,
	'HideRows' => array(
		array(
			'field' => "Playable",
			'operator' => "eq",
			'value' => false
		),
		array(
			'field' => "Status",
			'operator' => "eq",
			'value' => "Never"
		),
		array(
			'field' => "Status",
			'operator' => "eq",
			'value' => "Broken"
		)
	)
);

if(isset($_GET['fav'])) {
	$favorite=$_GET['fav'];
	switch($favorite) {
		case "Custom":
			if(isset($_GET['col'])){
				$filter['Columns'] = explode (",",$_GET['col']);
				//var_dump($filter['Columns'] );
			}
			if(isset($_GET['sort'])){
				$filter['Sortby']=$_GET['sort'];
				$filter['SortDir']=SORT_DESC;
			}
			if(isset($_GET['dir']) && ($_GET['dir']==3 || $_GET['dir']==1)){
				$filter['SortDir']=SORT_DESC;
				//SORT_DESC = 3 (also accept 1)
			}elseif (isset($_GET['dir']) && ($_GET['dir']==4 || $_GET['dir']==0)){
				$filter['SortDir']=SORT_ASC;
				//SORT_ASC = 4 (also accept 0)
			}
			if(isset($_GET['hide'])){
				$filter['HideRows']=array();
				$hideData = explode (",",$_GET['hide']);
				$index2=1;
				foreach($hideData as $data){
					if($index2==1){
						$tempdata=array();
						$tempdata['field']=$data;
						$index2++;
					}elseif($index2==2){
						
						$tempdata['operator']=$data;
						$index2++;
					}elseif($index2==3){
						
						$tempdata['value']=$data;
						$index2=1;
						$filter['HideRows'][]=$tempdata;
					}
					
				}
			}
			break;

		case "Default":
		default:
			$filter=array(
				'Columns' => array(
					"Title",
					"Type",
					"Playable",
					"ParentGame",
					"All Bundles",
					"Platforms",
					"Series",
					"Keywords",
					"LaunchDate",
					"PurchaseDate",
					"BundlePrice",
					"LaunchPrice",
					"MSRP",
					"CurrentMSRP",
					"HistoricLow",
					"Paid",
					"SalePrice",
					"AltSalePrice",
					"Want",
					"Achievements",
					"Cards",
					"TimeToBeat",
					"TimeLeftToBeat",
					"Metascore",
					"MetaUser",
					"Hours",
					"GrandTotal",
					"AchievementsEarned",
					"AchievementsLeft",
					"Status",
					"CountGame",
					"Active",
					"lastplay",
					"LastPlayORPurchase",
					"firstplay",
					"LastBeat",
					"DateUpdated",
					"Launchperhr",
					"MSRPperhr",
					"Currentperhr",
					"Historicperhr",
					"Paidperhr",
					"Saleperhr",
					"Altperhr",
					"LaunchLess1",
					"MSRPLess1",
					"CurrentLess1",
					"HistoricLess1",
					"PaidLess1",
					"SaleLess1",
					"AltLess1",
					"LaunchLess2",
					"MSRPLess2",
					"CurrentLess2",
					"HistoricLess2",
					"PaidLess2",
					"SaleLess2",
					"AltLess2",
					"LaunchHrsNext1",
					"MSRPHrsNext1",
					"CurrentHrsNext1",
					"HistoricHrsNext1",
					"PaidHrsNext1",
					"SaleHrsNext1",
					"AltHrsNext1",
					"LaunchHrsNext2",
					"MSRPHrsNext2",
					"CurrentHrsNext2",
					"HistoricHrsNext2",
					"PaidHrsNext2",
					"SaleHrsNext2",
					"AltHrsNext2"
				),
				'Sortby' => "lastplaySort",
				'SortDir' => SORT_DESC,
				'HideRows' => array()
			);

	}
}

//var_dump($filter);

?>
<div class="flex-item" style="order: 4">
<details>
<summary>Active Filter</summary>
<br>Sort by <?php echo $filter['Sortby'] . ($filter['Sortby']==SORT_DESC ? " Desc " : " ASC "); 
if(isset($filter['HideRows'])){ ?>
	<br>
	<?php foreach($filter['HideRows'] as $hide){ ?>
		Hide <?php echo $hide['field']; ?> <?php echo $hide['operator']; ?> <?php echo $hide['value']; ?><br>
	<?php }
} ?>
</details>
</div>
<?php
$header_Index=array(
	"ParentGame" => "Parent Game",
	"LaunchDate" => "Launch Date",
	"PurchaseDate" => "Purchase Date",
	"BundlePrice" => "Bundle Price",
	"LaunchPrice" => "Launch Price",
	"CurrentMSRP" => "Current MSRP",
	"HistoricLow" => "Historic Low",
	"SalePrice" => "Sale Price",
	"AltSalePrice" => "Alt Sale",
	"TimeToBeat" => "Time To Beat",
	"MetaUser" => "User Metascore",
	"GrandTotal" => "Total Hours",
	"AchievementsEarned" => "Achievements Earned",
	"AchievementsLeft" => "Achievements Left",
	"CountGame" => "Count Game",
	"lastplay" => "Last Played",
	"firstplay" => "First Played",
	"LastBeat" => "Last Beat",
	"DateUpdated" => "Last Updated",
	
	"Launchperhr" => "Launch Price $/hr",
	"MSRPperhr" => "MSRP $/hr",
	"Currentperhr" => "Current MSRP $/hr",
	"Historicperhr" => "Historic Low $/hr",
	"Paidperhr" => "Paid $/hr",
	"Saleperhr" => "Sale Price $/hr",
	"Altperhr" => "Alt Sale $/hr",

	"LaunchLess1" => "1 Hour reduces Launch by",
	"MSRPLess1" => "1 Hour reduces MSRP by",
	"CurrentLess1" => "1 Hour reduces Current by",
	"HistoricLess1" => "1 Hour reduces Historic by",
	"PaidLess1" => "1 Hour reduces Paid by",
	"SaleLess1" => "1 Hour reduces Sale by",
	"AltLess1" => "1 Hour reduces Alt by",

	"LaunchLess2" => "Hours to $0.01 less of Launch",
	"MSRPLess2" => "Hours to $0.01 less of MSRP",
	"CurrentLess2" => "Hours to $0.01 less of Current",
	"HistoricLess2" => "Hours to $0.01 less of Historic",
	"PaidLess2" => "Hours to $0.01 less of Paid",
	"SaleLess2" => "Hours to $0.01 less of Sale",
	"AltLess2" => "Hours to $0.01 less of Alt",

	"LaunchHrsNext1" => "Hrs to next position Launch $/hr",
	"MSRPHrsNext1" => "Hrs to next position MSRP $/hr",
	"CurrentHrsNext1" => "Hrs to next position Current $/hr",
	"HistoricHrsNext1" => "Hrs to next position Historic $/hr",
	"PaidHrsNext1" => "Hrs to next position Paid $/hr",
	"SaleHrsNext1" => "Hrs to next position Sale $/hr",
	"AltHrsNext1" => "Hrs to next position Alt $/hr",

	"LaunchHrsNext2" => "Hrs to next active position Launch $/hr",
	"MSRPHrsNext2" => "Hrs to next active position MSRP $/hr",
	"CurrentHrsNext2" => "Hrs to next active position Current $/hr",
	"HistoricHrsNext2" => "Hrs to next active position Historic $/hr",
	"PaidHrsNext2" => "Hrs to next Active position Paid $/hr",
	"SaleHrsNext2" => "Hrs to next active position Sale $/hr",
	"AltHrsNext2" => "Hrs to next active position Alt $/hr",
	
	"LastPlayORPurchase" => "Last Play/Purchase",
	"TimeLeftToBeat" => "Time Left to Beat",
	
	"DrmFree" => "Drm Free",
	"DrmFreeSize" => "Drm Free File Size",
	"DrmFreeLibrary" => "Drm Free Library",
	"Key" => "Activation Key / Link",
	
	"MainLibrary" => "Main Library",
	"OtherLibrary" => "Other Library"
	
);
	
?>
<div class="flex-item" style="order: 5">
<div>
<table class="fancyTable" id="myTable01" cellpadding="0" cellspacing="0">
<thead>
<tr>
<?php
//TODO: Add clickable sort order to column headers.
foreach ($filter['Columns'] as $key => $row) {
	if(isset($header_Index[$row])) { ?>
		<th><?php echo $header_Index[$row]; ?></th>
	<?php } else { ?>
		<th><?php echo $row; ?></th>
	<?php }
} ?>

<th class="hidden">Debug</th>

</tr>
</thead>
<tbody>
<?php
//$calculations=getCalculationsv2("",$conn);
$calculations=getCalculations("",$conn);

$counters['timetobeat']=0;
$counters['metascore']=0;
$counters['usermetascore']=0;
$counters['metascorepage']=0;
$counters['countall']=0;
$counters['launch']=0;
$counters['msrp']=0;
$counters['hlow']=0;
$counters['paid']=0;
$counters['sale']=0;
$counters['alt']=0;
$counters['hours']=0;

//$counters['Summaryfield']=$filter['Sortby'];
$counters['Total']=0;
$counters['Avg']=0;
$counters['Mean']=0;
$counters['Median']=0;
$counters['Mode']=0;
$counters['max1']=
$counters['max2']=0;
$counters['min1']=
$counters['min2']=time();	
$counters['data']=array();

//DONE: Calculations Sort field sorts dates as text

switch($filter['Sortby']){
	case "LaunchDate":
	case "PurchaseDate":
	case "lastplay":
	case "firstplay":
	case "LastBeat":
	case "DateUpdated":
	case "LastPlayORPurchase":
		foreach ($calculations as $key => $row) {
			$Sortby1[$key]  = strtotime($row[$filter['Sortby']]);
		}
		break;
	default:
		foreach ($calculations as $key => $row) {
			$Sortby1[$key]  = $row[$filter['Sortby']];
		}
		break;
}
array_multisort($Sortby1, $filter['SortDir'], $calculations);


foreach ($calculations as $game) {
	$showgame=true;
	
	foreach ($filter['HideRows'] as $hide) {
		switch($hide['operator']){
			case "gt": //Greater Than
				if($game[$hide['field']]>$hide['value']){$showgame=false; }
				break;
			case "gte": //Greater Than or Equal
				if($game[$hide['field']]>=$hide['value']){$showgame=false; }
				break;
			case "eq": // Equal
				if($game[$hide['field']]==$hide['value']){$showgame=false; }
				break;
			case "lte": //Less Than or Equal
				if($game[$hide['field']]<=$hide['value']){$showgame=false; }
				break;
			case "lt": //Less Than
				if($game[$hide['field']]<$hide['value']){$showgame=false; }
				break;
			case "ne": //Not Equal
				if($game[$hide['field']]<>$hide['value']){$showgame=false; }
				break;
		}
	}
	
	if($showgame==true){
	//if(true==true){ ?>
		<tr class="<?php echo $game['Status']; ?>">
		<?php foreach ($filter['Columns'] as $key => $row) {
			switch($row){
				case "Game_ID":
					?>
					<td><?php echo $game['Game_ID']; ?></td><?php
					break;
				case "Title":
					?>
					<td class="text"><a href='viewgame.php?id=<?php echo $game['Game_ID']; ?>' target='_blank'><?php echo $game[$row]; ?></a></td><?php
					break;
				case "TitleEdit":
					?>
					<td class="text"><a href='viewgame.php?id=<?php echo $game['Game_ID']; ?>&edit=1' target='_blank'><?php echo $game['Title']; ?></a></td><?php
					break;
				case "Type":
				case "Series":
				default:
					?>
					<td class="text"><?php echo $game[$row]; ?></td><?php
					break;
				case "Playable":
				case "CountGame":
				case "Active":
				case "Inactive":
				case "DrmFree":
				case "OtherLibrary":
					?>
					<td class="text"><?php echo boolText($game[$row]); ?></td><?php
					break;
				case "ParentGame":
					?>
					<td class="text"><a href='viewgame.php?id=<?php echo $game['ParentGameID']; ?>' target='_blank'><?php echo $game[$row]; ?></a></td><?php
					break;
				case "All Bundles":
					//echo "<td class=\"text\">" . $game['PrintBundles'] . "</td>"; // Bundles  //print_r($game['TopBundleIDs'],true) .
					?>
					<td class="text"><?php echo str_replace("|&nbsp;", "</br>", str_replace(" ", "&nbsp;", $game['PrintBundles'])); ?></td><?php // Bundles
					//print_r($game['TopBundleIDs'],true) .
					break;
				case "Platforms":
					?>
					<td class="text"><?php echo nl2br($game[$row]); ?></td><?php // Platforms
					break;
				case "Keywords":
					?>
					<td class="text"><?php echo nl2br($game['allKeywords']); ?></td><?php // Keywords
					break;
				case "LaunchDate":
				case "Want":
				case "AchievementsLeft":
				case "DateUpdated":
				case "DrmFreeSize":
				case "Review":
					?>
					<td class="numeric"><?php echo $game[$row]; ?></td><?php
					break;
				case "BundlePrice":
				case "LaunchPrice":
				case "MSRP":
				case "CurrentMSRP":
				case "HistoricLow":
				case "Paid":
				case "SalePrice":
				case "AltSalePrice":

				case "Launchperhr":
				case "MSRPperhr":
				case "Currentperhr":
				case "Historicperhr":
				case "Paidperhr":
				case "Saleperhr":
				case "Altperhr":
				
				case "LaunchLess1":
				case "MSRPLess1":
				case "CurrentLess1":
				case "HistoricLess1":
				case "PaidLess1":
				case "SaleLess1":
				case "AltLess1":
					?>
					<td class="numeric">$<?php echo sprintf("%.2f",$game[$row]); ?></td><?php
					break;
				case "PurchaseDate":
					?>
					<td class="numeric"><?php echo str_replace(" ", "&nbsp;", $game['PrintPurchaseDate']);?></td><?php // Purchase Dates
					break;
				case "Achievements":
					?>
					<td class="numeric"><?php echo $game['SteamAchievements']; ?></td><?php
					break;
				case "Cards":
					?>
					<td class="numeric"><?php echo $game['SteamCards']; ?></td><?php
					break;
				case "TimeToBeat":
					?>
					<td class="numeric"><?php echo $game['TimeToBeatLink2']; ?></td><?php
					break;
				case "Metascore":
					?>
					<td class="numeric"><?php echo $game['MetascoreLinkCritic']; ?></td><?php
					break;
				case "MetaUser":
					?>
					<td class="numeric"><?php echo $game['MetascoreLinkUser']; ?></td><?php
					break;
				case "Hours":
					?>
					<td class="numeric"><?php echo timeduration($game['totalHrs'],"seconds"); ?></td><?php // Hours
					break;
				case "GrandTotal":
					?>
					<td class="numeric"><?php echo timeduration($game[$row],"seconds"); ?></td><?php // Total Hours
					break;
				case "AchievementsEarned":
					?>
					<td class="numeric"><?php echo $game['Achievements']; ?></td><?php // Achievements Earned
					break;
				case "Status":
					?>
					<td class="text"><?php echo str_replace(" ", "&nbsp;", $game[$row])?></td><?php
					break;
				case "lastplay":
				case "firstplay":
				case "LastBeat":
				case "LastPlayORPurchase":
					?>
					<td class="numeric"><?php echo str_replace(" ", "&nbsp;", $game[$row]); ?></td><?php
					break;
				case "LaunchLess2":
				case "MSRPLess2":
				case "CurrentLess2":
				case "HistoricLess2":
				case "PaidLess2":
				case "SaleLess2":
				case "AltLess2":

				case "LaunchHrsNext1":
				case "MSRPHrsNext1":
				case "CurrentHrsNext1":
				case "HistoricHrsNext1":
				case "PaidHrsNext1":
				case "SaleHrsNext1":
				case "AltHrsNext1":

				case "LaunchHrsNext2":
				case "MSRPHrsNext2":
				case "CurrentHrsNext2":
				case "HistoricHrsNext2":
				case "PaidHrsNext2":
				case "SaleHrsNext2":
				case "AltHrsNext2":
				
				case "TimeLeftToBeat":
					?>
					<td class="numeric"><?php echo timeduration($game[$row],"hours"); ?></td><?php
					break;
			}
		}

		$counters['countall']++;
		if($game['TimeToBeat']==0) {$counters['timetobeat']++;}
		if($game['Metascore']==0) {$counters['metascore']++;}
		if($game['UserMetascore']==0) {$counters['usermetascore']++;}
		if($game['MetascoreID']=="") {$counters['metascorepage']++;}
		$counters['msrp']+=$game['MSRP'];
		$counters['hlow']+=$game['HistoricLow'];
		$counters['paid']+=$game['Paid'];
		$counters['sale']+=$game['SalePrice'];
		//$counters['alt']+=$game['MSRP'];
		$counters['hours']+=$game['totalHrs'];
		
		//$counters['Total']+=$game[$filter['Sortby']];
		//$counters['Mean']=0;
		//$counters['Median']=0;
		//$counters['Mode']=0;
		//$counters['max1']=
		//$counters['max2']=0;
		//$counters['min1']=
		//$counters['min2']=time();	
		$counters['data'][]=$game[$filter['Sortby']];
		
		//$fullstatdata[$game['Game_ID']]['Game_ID']=$row['Game_ID'];
		//$fullstatdata[$game['Game_ID']]['Title']=$row['Title'];
		
		?>
		<td class="hidden text"><?php echo $game['Debug']; ?></td>
		</tr>
		<tr class="hidden"><td colspan=100><?php echo $game['Debug']; ?></td></tr>
		<?php

	}
}
		//needs to be sorted to get Median, Direction is irrelevant.
		rsort($counters['data']);
		//array_multisort($counters['data'], $fullstatdata,SORT_ASC);
		
		//TODO: errors out if there are no results in the filtered list
		if(is_numeric($counters['data'][0])) {
			$counters['Total']=array_sum($counters['data']);
			
			if($counters['countall']==0){
				$counters['Avg']=0;
			} else {
				$counters['Avg']=$counters['Total']/$counters['countall'];
			}
			
			$sum=0;
			for ($i = 0; $i < $counters['countall']; $i++) {
				
				//var_dump($sum); echo " += 1 / ("; var_dump($counters['data'][$i]); echo " +.00001)</br>";
				//if($counters['data'][$i]=="") {$counters['data'][$i]=0;}
				$sum += 1 / ($counters['data'][$i]+.00001);
			}
			if($sum==0){
				$counters['Mean']=0;
			}else {
				$counters['Mean'] = $counters['countall'] / $sum;
			}
			
			$usearray=$counters['data'];
			foreach($usearray as $key => &$value){
				$type = gettype($value);
				$value =$value * 100;
				if($type=="double" OR $type=="float"){
					$value = (int)($value );
					//$value = " ".$value;
				} elseif ($type=="boolean") { 
					//$value=0;
					unset($usearray[$key]);
				} elseif ($type<>"string" AND $type<>"integer") {
					echo $type." ";
				}
				//echo $value . " ";
				//var_dump($value);
			}
			//print_r($usearray)." ";
			//$counters['Mode']=mmmr($usearray,'mode');
			$counters['Mode']=$counters['Mode']/100;
			
			$usearray=array_unique($usearray);
			rsort($usearray);
			
			//var_dump($counters);
			
			$counters['max1']=$usearray[0]/100;
			if(isset($usearray[1])) {
				$counters['max2']=$usearray[1]/100;
			}
			$counters['min1']=min($usearray)/100;
			if(isset($usearray[count($usearray)-2])) {
				$counters['min2']=$usearray[count($usearray)-2]/100;	
			}

		} else {
			$counters['Total']="";
			$counters['Avg']  ="";
			$counters['Mean'] ="";
			$counters['max1'] ="";
			$counters['max2'] ="";
			$counters['min1'] ="";
			$counters['min2'] ="";
		}
		
		//TODO: errors out if there are no results in the filtered list
		$counters['Median']=$counters['data'][round($counters['countall'] / 2)-1];

		//print_r($usearray);

echo "</tbody>";

//DONE: Fix this so it puts things in the right dynamic column and does not break headers.
echo "<tfoot>";

echo "<tr>";
foreach ($filter['Columns'] as $key => $row) {
	switch($row){
		case "Title":
		case "TitleEdit":
			?>
			<th class="text">Counts & Totals (<?php echo $counters['countall']; ?>)</th><?php
			break;
		case "MSRP":
			?>
			<td class="numeric">$<?php echo number_format($counters['msrp'],2); ?></td><?php
			break;
		case "HistoricLow":
			?>
			<td class="numeric">$<?php echo number_format($counters['hlow'],2); ?></td><?php
			break;
		case "Paid":
			//TODO: Paid total should be actual paid totoal
			?>
			<td class="numeric">$<?php echo number_format($counters['paid'],2); ?></td><?php
			break;
		case "SalePrice":
			?>
			<td class="numeric">$<?php echo number_format($counters['sale'],2); ?></td><?php
			break;
		case "TimeToBeat":
		//TODO: Timetobeat counter does not make any sense.
			?>
			<td class="numeric"><?php echo $counters['timetobeat']; ?></td><?php
			break;
		case "Metascore":
			?>
			<td class="numeric"><?php echo $counters['metascore']; ?>/<?php echo $counters['metascorepage']; ?></td><?php
			break;
		case "MetaUser":
			?>
			<td class="numeric"><?php echo $counters['usermetascore']; ?>/<?php echo $counters['metascorepage']; ?></td><?php
			break;
		case "GrandTotal":
			?>
			<td class="numeric"><?php echo timeduration($counters['hours'],"seconds"); ?></td><?php
			break;
		default:
			?>
			<td></td><?php
			break;
		
	}
} ?>
</tr>
</tfoot>
</table>
</div>
</div>

<div class="flex-item" style="order: 3">
<details>
<summary>Stats</summary>
<table border=0>
<tr><th>Count</th><td><?php echo $counters['countall']; ?></td></tr>
<tr><th>Total</th><td><?php echo $counters['Total']; ?></td></tr>
<tr><th>Math</th><td></td></tr>
<tr><th>Avg (mean)</th><td><?php echo $counters['Avg']; ?></td></tr>
<tr><th>Harmonic Mean</th><td><?php echo $counters['Mean']; ?></td></tr>
<tr><th>Median</th><td><?php echo $counters['Median']; ?></td></tr>
<tr><th>Mode</th><td><?php echo $counters['Mode']; ?></td></tr>
<tr><th>Most</th><td><?php echo $counters['max1']; ?></td></tr>
<tr><th>Second Most</th><td><?php echo $counters['max2']; ?></td></tr>
<tr><th>Least</th><td><?php echo $counters['min1']; ?></td></tr>
<tr><th>Second Least</th><td><?php echo $counters['min2']; ?></td></tr>
</table>
</details>
</div>
</div class="flex-container">

<?php echo Get_Footer(); ?>