<?php
/*
 *  GL5 Version - Need to re-work for GL6
 */

//DONE: add control function to prevent loading multiple times.
if(isset($GLOBALS[__FILE__])){
	trigger_error("File already included once ".__FILE__.". ");
}
$GLOBALS[__FILE__]=1;

function getGames($gameID="",$connection=false){
	if($connection==false){
		include "auth.inc.php";
		$conn = new mysqli($servername, $username, $password, $dbname);
	} else {
		$conn = $connection;
	}
	$sql  = "select * from `gl_products`";
	$sql2 ="";
	if ($gameID <> "" ) {
		if(is_array($gameID)){
			foreach ($gameID as $value){
				if($sql2==""){
					$sql2 .= " where Game_ID = " . $value ;
					$sql2 .= " OR ParentGameID = " . $value ;
				} else {
					$sql2 .= " OR Game_ID = " . $value ;
					$sql2 .= " OR ParentGameID = " . $value ;
				}
				$sql .= $sql2;
			}
		} else {
			$sql .= " where Game_ID = " . $gameID ;
			$sql .= " OR ParentGameID = " . $gameID ;
		}
	}
	$sql .= " order by `Series` ASC, `LaunchDate` ASC" ;
	if($result = $conn->query($sql)){
		$cpi=getAllCpi($conn);

		while($row = $result->fetch_assoc()) {
			$row=CalculateGameRow($row);
			
			$launchDate=strtotime($row['LaunchDate']);
			$row['LaunchDateValue']=$launchDate;
			if (isset($cpi[date("Y",$launchDate)][date("n",$launchDate)])) {
				$cpi_launch=$cpi[date("Y",$launchDate)][date("n",$launchDate)];
			} else {
				$cpi_launch=$cpi['Current'];
			}
			$row['CPILaunch'] = round($row['LaunchPrice'] * ($cpi['Current'] / $cpi_launch) ,2);
			$row['CPILaunch'] = sprintf("$%.2f",$row['CPILaunch']);

			
			
			$games[]=$row;
		}
	} else {
		$games = false;
		trigger_error("SQL Query Failed: " . mysqli_error($conn) . "</br>Query: ". $sql);
	}
	
	if($connection==false){
		$conn->close();	
	}	
	return $games;
}

function CalculateGameRow($row){
	$row['LaunchDateSort'] = $row['LaunchDate'];
	$row['LaunchDate'] = date("n/j/Y",strtotime($row['LaunchDate']));
	if(strtotime($row['LowDate']) == 0) {
		$row['LowDate'] = "";
	} else {
		$row['LowDate'] = date("n/j/Y",strtotime($row['LowDate']));
	}
	if(strtotime($row['DateUpdated']) == 0) {
		$row['DateUpdated'] = "";
		$row['DateUpdatedSort'] = "";
	} else {
		$row['DateUpdatedSort'] = $row['DateUpdated'];
		$row['DateUpdated'] = date("n/j/Y",strtotime($row['DateUpdated']));
	}
	
	if ($row['LaunchPrice'] == 0){
		$row['LaunchPrice'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
	}
	$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
	
	if ($row['LaunchPrice'] == 0){
		$row['MSRP'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['LaunchPrice'] = sprintf("%.2f",$row['LaunchPrice']);
	}
	if ($row['CurrentMSRP'] == 0){
		$row['CurrentMSRP'] = sprintf("%.2f",$row['MSRP']);
	} else {
		$row['CurrentMSRP'] = sprintf("%.2f",$row['CurrentMSRP']);
	}
	if ($row['HistoricLow'] == 0){
		$row['HistoricLow'] = sprintf("%.2f",min($row['CurrentMSRP'],$row['MSRP']));
	} else {
		$row['HistoricLow'] = sprintf("%.2f",$row['HistoricLow']);
	}
	if ($row['SteamAchievements'] == 0){
		$row['SteamAchievements'] = "";
	} 
	if ($row['SteamCards'] == 0){
		$row['SteamCards'] = "";
	} 
	if ($row['Metascore'] == 0){
		if($row['MetascoreID']=="") {
			$row['Metascore'] = "";
		} else {
			$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/game/".$row['Metascore']."' target='_blank'>N/A</a>";
		}
	} else {
		$row['MetascoreLinkCritic']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['Metascore']."</a>";
	}
	if ($row['UserMetascore'] == 0){
		if($row['MetascoreID']=="") {
			$row['UserMetascore'] = "";
		} else {
			$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>N/A</a>";
		}
	} else {
		$row['MetascoreLinkUser']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>".$row['UserMetascore']."</a>";
	}
	if ($row['SteamRating'] == 0){
		$row['SteamRating'] = "";
	} 
	if($row['SteamID']<>0) {
		$row['SteamLinks'] = "<a href='http://store.steampowered.com/app/".$row['SteamID']."' target='_blank'>Store</a>";
	} else {
		$row['SteamID']="";
		$row['SteamLinks'] = "<a class='Search' href='http://store.steampowered.com/search/?term=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	if($row['GOGID']<>"") {
		$row['GOGLink']= "<a href='http://www.gog.com/game/". $row['GOGID'] ."' target='_blank'>Store</a>";
	} else {
		$row['GOGLink']= "<a class='Search' href='http://www.gog.com/games##search=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	if($row['DesuraID']<>"") {
		$row['DesuraLink']= "<a href='http://www.desura.com/games/".$row['DesuraID']."' target='_blank'>Store</a>";
	} else {
		$row['DesuraLink']= "<a class='Search' href='http://www.desura.com/search?q=". urlencode($row['Title']) ."&sa.x=0&sa.y=0&sa=Search' target='_blank'>Search</a>";
	}			
	if($row['isthereanydealID']<>"") {
		//$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/#/page:game/info?plain=".$row['isthereanydealID']."' target='_blank'>Info</a>";
		$row['isthereanydealLink'] = "<a href='http://isthereanydeal.com/game/".$row['isthereanydealID']."/history' target='_blank'>Info</a>";
	} else {
		$row['isthereanydealLink'] = "<a class='Search' href='http://isthereanydeal.com/search?q=". urlencode($row['Title']) ."' target='_blank'>Search</a>";
	}
	if($row['TimeToBeatID']<>0) {
		$row['TimeToBeatLink']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>Link</a>";
		if($row['TimeToBeat']==0){
			$row['TimeToBeatLink2']= "<a class='Search' href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>N/A</a>";
		} else {
			$row['TimeToBeatLink2']= "<a href='http://howlongtobeat.com/game.php?id=".$row['TimeToBeatID']."' target='_blank'>".timeduration($row['TimeToBeat'],"hours")."</a>";
		}
	} else {
		$row['TimeToBeatLink']= "";
		$row['TimeToBeatLink2']= "";
	}
	if($row['MetascoreID'] <> "") {
		$row['MetascoreLink']= "<a href='http://www.metacritic.com/game/".$row['MetascoreID']."' target='_blank'>Link</a>";
	} else {
		$row['MetascoreLink']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		$row['MetascoreLinkCritic']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		$row['MetascoreLinkUser']= "<a class='Search' href='http://www.metacritic.com/search/game/". urlencode($row['Title']) ."/results' target='_blank'>Search</a>";
		
	}	
	return $row;
}
?>