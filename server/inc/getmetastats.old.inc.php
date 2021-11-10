<?php
	/*
	$metatarget['launchhrsavg']=$stats['LaunchPrice']['Average'];
	$metatarget['launchhrsmean']=$stats['LaunchPrice']['HarMean'];
	$metatarget['launchhrsmedian']=$stats['LaunchPrice']['Median'];

	$metatarget['msrphrsavg']=$stats['MSRP']['Average'];
	$metatarget['msrphrsmean']=$stats['MSRP']['HarMean'];
	$metatarget['msrphrsmedian']=$stats['MSRP']['Median'];

	$metatarget['histhrsavg']=$stats['HistoricLow']['Average'];
	$metatarget['histhrsmean']=$stats['HistoricLow']['HarMean'];
	$metatarget['histhrsmedian']=$stats['HistoricLow']['Median'];
	
	$metatarget['paidhrsavg']=$stats['Paid']['Average'];
	$metatarget['paidhrsmean']=$stats['Paid']['HarMean'];
	$metatarget['paidhrsmedian']=$stats['Paid']['Median'];

	$metatarget['salehrsavg']=$stats['SalePrice']['Average'];
	$metatarget['salehrsmean']=$stats['SalePrice']['HarMean'];
	$metatarget['salehrsmedian']=$stats['SalePrice']['Median'];

	$metatarget['althrsavg']=$stats['AltSalePrice']['Average'];
	$metatarget['althrsmean']=$stats['AltSalePrice']['HarMean'];
	$metatarget['althrsmedian']=$stats['AltSalePrice']['Median'];
	*/
	
function getStatRow($calculations,$field,$datatype,$sourcekey,$criteria){
	$Statistics['datatype']=$datatype;
	$Statistics['GameCount']=0;
	
	$Statistics['max1']=
	$Statistics['max2']=0;
	
	$Statistics['min1']=
	$Statistics['min2']=time();	
	
	$statoutput=array();
	foreach ($calculations as $key => $row) {
		$countrow=false;
		if($row['Playable']==true) {
			$countrow=true;
		}
		
		if($_GET['filter']=="Played" && $row['lastplay']==""){
			$countrow=false;
		} elseif ($_GET['filter']=="Unplayed" && $row['lastplay']<>"") {
			$countrow=false;
		}
		
		if(isset($_GET['Free']) && $_GET['Free']=="Hide" && $row['Paid']<=0){
			$countrow=false;
		}
		
		if(isset($_GET['Never']) && $_GET['Never']=="Hide" && $row['Status']=="Never"){
			$countrow=false;
		}

		if(isset($_GET['Beat']) && $_GET['Beat']=="Hide" && $row['Paid']=="Done"){
			$countrow=false;
		}

		if($countrow) {
			//var_dump($row);
			$Statistics['GameCount']++;
			
			if(is_array($criteria)){
				
				$criteriamet=false;
				switch ($criteria['Operator']) {
					case "!eq":
						if($row[$criteria['Field']] <> $criteria['Value']) {$criteriamet=true;}
						break;
					case "gt":
						if($row[$criteria['Field']] > $criteria['Value']) {$criteriamet=true;}
						break;
					case "gte":
						if($row[$criteria['Field']] >= $criteria['Value']) {$criteriamet=true;}
						break;
					default:
					case "NA":
						$criteriamet=true;
						break;
				}
				
				if($datatype=="Date"){
					if(is_string($row[$sourcekey])){
						//$usevalue=strtotime($row[$sourcekey]);
						$usevalue=$row[$sourcekey]->getTimestamp();
					} else {
						$usevalue=$row[$sourcekey];
					}
					if($usevalue == -57600){
						//$row[$criteria['Field']]="";
						//$usevalue=0;
						$criteriamet=false;
					}
				} else {
					$usevalue=$row[$sourcekey];
				}
				
				if($criteriamet){
					$statoutput[]=$usevalue;
					
					/* DEBUG Data */
					$fullstatdata[$key]['usevalue']=$usevalue;
					$fullstatdata[$key]['original']=$row[$sourcekey];
					$fullstatdata[$key]['Game_ID']=$row['Game_ID'];
					$fullstatdata[$key]['Title']=$row['Title'];
					/* DEBUG Data */

					if($usevalue > $Statistics['max1']) {
						$Statistics['max2'] = $Statistics['max1'];
						$Statistics['max1'] = $usevalue;
					} elseif($usevalue > $Statistics['max2'] && $usevalue!=$Statistics['max1']) {
						$Statistics['max2'] = $usevalue;
					}
					
					if($usevalue < $Statistics['min1']) {
						$Statistics['min2'] = $Statistics['min1'];
						$Statistics['min1'] = $usevalue;
					} elseif($usevalue < $Statistics['min2'] && $usevalue!=$Statistics['min1']) {
						$Statistics['min2'] = $usevalue;
					}
				}
			}
		}
	}
	
	if(is_array($criteria)){
		$Statistics['Count']= count($statoutput);
		$Statistics['Total']= array_sum($statoutput);	
		if($Statistics['Count']==0){
			$Statistics['Average']=0;
		} else {
			$Statistics['Average'] = array_sum($statoutput) / $Statistics['Count'];
		}
		$sum=0;
		for ($i = 0; $i < $Statistics['Count']; $i++) {
			$sum += 1 / ($statoutput[$i]+.00001);
		}
		if($sum==0){
			$Statistics['HarMean']=0;
		}else {
			$Statistics['HarMean'] = $Statistics['Count'] / $sum;
		}
		
		//needs to be sorted to get Median, Direction is irrelevant.
		//rsort($statoutput);
		array_multisort($statoutput, $fullstatdata,SORT_ASC);
		
		$Statistics['Median']=$statoutput[round($Statistics['Count'] / 2)-1];
		
		$usearray=$statoutput;
		foreach($usearray as $key => &$value){
			$type = gettype($value);
			if($type=="double" OR $type=="float"){
				$value = (int)($value * 10^$criteria['round']);
			} elseif ($type=="boolean") { 
				//$value=0;
				unset($usearray[$key]);
			} elseif ($type<>"string" AND $type<>"integer") {
				//echo $type." ";
			}
		}
		$Statistics['Mode']=mmmr($usearray,'mode');
		//Add game titles to the numeric results
		
		foreach ($fullstatdata as $row) {
			//echo "<br>";var_dump($row);break;
			if(isset($row['usevalue'])){
				$Statistics=getGameLinks2($datatype,$sourcekey,$Statistics,$row,$calculations);
			}
		}
		
		$Statistics['AverageGameTitle']=$calculations[$Statistics['AverageGame']]['Title'];
		$Statistics['HarMeanGameTitle']=$calculations[$Statistics['HarMeanGame']]['Title'];
		$Statistics['MedianGameTitle']=$calculations[$Statistics['MedianGame']]['Title'];
		$Statistics['ModeGameTitle']=$calculations[$Statistics['ModeGame']]['Title'];
		$Statistics['max1GameTitle']=$calculations[$Statistics['max1Game']]['Title'];
		$Statistics['max2GameTitle']=$calculations[$Statistics['max2Game']]['Title'];
		$Statistics['min1GameTitle']=$calculations[$Statistics['min1Game']]['Title'];
		$Statistics['min2GameTitle']=$calculations[$Statistics['min2Game']]['Title'];
		
		
	}
	
	if(isset($fullstatdata)){
		//$Statistics['Debug']=$fullstatdata;
	}
	
	return $Statistics;
}

function printStatRow($datatype,$datatype2,$prefix,$suffix,$data,$totaltype="sum",$MathValue=""){
	$printrow  = "";
	
	if(isset($data['datatype'])){
		$datatype=$data['datatype'];
	}
	
	//Total
	if($datatype=="Numeric" && $suffix<>"%"){
		$printrow .= "<td class='numeric'>".formatData($data,$datatype,$datatype2,'Total',$prefix,$suffix)."</td>"; 
	} else {
		$printrow .= "<td></td>"; 
	}
	//Math / Notes
	$printrow .= "<td>$MathValue</td>";
	//Average (Mean)
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'Average',$prefix,$suffix) . "</td>"; 
	//Average (Mean) - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['AverageGame']."' target='_blank'>".$data['AverageGameTitle']."</a></td>";
	//Harmonic Mean
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'HarMean',$prefix,$suffix) . "</td>"; 
	//Harmonic Mean - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['HarMeanGame']."' target='_blank'>".$data['HarMeanGameTitle']."</a></td>";
	//Median
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'Median',$prefix,$suffix) . "</td>"; 
	//Median - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['MedianGame']."' target='_blank'>".$data['MedianGameTitle']."</a></td>";
	//Mode
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'Mode',$prefix,$suffix) . "</td>"; 
	//Mode - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['ModeGame']."' target='_blank'>".$data['ModeGameTitle']."</a></td>";
	//Max1
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'max1',$prefix,$suffix) . "</td>"; 
	//Max1 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['max1Game']."' target='_blank'>".$data['max1GameTitle']."</a></td>";
	//Max2
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'max2',$prefix,$suffix) . "</td>"; 
	//Max2 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['max2Game']."' target='_blank'>".$data['max2GameTitle']."</a></td>";
	//Min1
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'min1',$prefix,$suffix) . "</td>"; 
	//Min1 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['min1Game']."' target='_blank'>".$data['min1GameTitle']."</a></td>";
	//Min2
	$printrow .= "<td class='numeric' >" . formatData($data,$datatype,$datatype2,'min2',$prefix,$suffix) . "</td>"; 
	//Min2 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['min2Game']."' target='_blank'>".$data['min2GameTitle']."</a></td>";
	
	return $printrow ;
}

function printStatRow2($data,$MathValue=""){
	$printrow  = "";
	
	if(!isset($data['datatype2'])){
		$data['datatype2']=0;
	}
	
	if(!isset($data['prefix'])){
		$data['prefix']="";
	}
	
	if(!isset($data['suffix'])){
		$data['suffix']="";
	}

	if(!isset($data['totaltype'])){
		$data['totaltype']="";
	}
	
	/* 
	Total Types
	None
	Sum
	Average
	Calculation
	*/
	//if($data['totaltype']<>"None" && ($data['datatype']=="Duration" OR ($data['datatype']=="Numeric" && $data['suffix']<>"%"))){
	if($data['totaltype']=="Sum"){
		$printrow .= "<td class='numeric'>".formatData($data,$data['datatype'],$data['datatype2'],'Total',$data['prefix'],$data['suffix'])."</td>"; 
		//Math / Notes
		$printrow .= "<td>$MathValue</td>";
	} elseif ($data['totaltype']=="Math"){
		$printrow .= "<td class='numeric'>$MathValue</td>"; 
		//Math / Notes
		$printrow .= "<td></td>"; 
	} else {
		$printrow .= "<td></td>";
		//Math / Notes
		$printrow .= "<td>$MathValue</td>";
	}
	//Average (Mean)
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'Average',$data['prefix'],$data['suffix']) . "</td>"; 
	//Average (Mean) - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['AverageGame']."' target='_blank'>".$data['AverageGameTitle']."</a></td>";
	//Harmonic Mean
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'HarMean',$data['prefix'],$data['suffix']) . "</td>"; 
	//Harmonic Mean - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['HarMeanGame']."' target='_blank'>".$data['HarMeanGameTitle']."</a></td>";
	//Median
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'Median',$data['prefix'],$data['suffix']) . "</td>"; 
	//Median - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['MedianGame']."' target='_blank'>".$data['MedianGameTitle']."</a></td>";
	//Mode
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'Mode',$data['prefix'],$data['suffix']) . "</td>"; 
	//Mode - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['ModeGame']."' target='_blank'>".$data['ModeGameTitle']."</a></td>";
	//Max1
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'max1',$data['prefix'],$data['suffix']) . "</td>"; 
	//Max1 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['max1Game']."' target='_blank'>".$data['max1GameTitle']."</a></td>";
	//Max2
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'max2',$data['prefix'],$data['suffix']) . "</td>"; 
	//Max2 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['max2Game']."' target='_blank'>".$data['max2GameTitle']."</a></td>";
	//Min1
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'min1',$data['prefix'],$data['suffix']) . "</td>"; 
	//Min1 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['min1Game']."' target='_blank'>".$data['min1GameTitle']."</a></td>";
	//Min2
	$printrow .= "<td class='numeric' >" . formatData($data,$data['datatype'],$data['datatype2'],'min2',$data['prefix'],$data['suffix']) . "</td>"; 
	//Min2 - Game
	$printrow .= "<td><a href='viewgame.php?id=".$data['min2Game']."' target='_blank'>".$data['min2GameTitle']."</a></td>";
	/*/Debug * /
	$printrow .= "<td>";
	$printrow .= $data['datatype2'];
	$printrow .= "</td>";
	/* */
	
	return $printrow ;
}

function formatData($data,$datatype,$datatype2,$key,$prefix,$suffix){
	if(isset($data[$key])){
		switch ($datatype){
			case "Date":
				//echo gettype($data[$key])." ";
				//if(gettype($data[$key])=="double"){echo debugpath();}
				$output=$prefix . date("n/j/Y", $data[$key]). $suffix;
				return $output;
				break;
			case "Duration":
				return $prefix . timeduration($data[$key],$datatype2). $suffix;
				break;
			default:
				if(is_int($datatype2)){
					//return $prefix . round($data[$key],$datatype2). $suffix;
					//echo gettype($data[$key])." ";
					if (gettype($data[$key])=="string"){
						return $prefix . sprintf("%.".$datatype2."f",$data[$key]). $suffix;
					} else {
						return $prefix . number_format($data[$key],$datatype2). $suffix;
					}
				} else {
					return $prefix . $data[$key] . $suffix;
				}
				break;
		}
	} else {
		return "";
	}
}

function getGameLinks2($datatype,$sourcekey,$BaseData,$row,$calculations){
	//echo "<br>";var_dump($row);return;
	//echo "<br>";var_dump($BaseData);return $BaseData;
	
	//Initialize all the variables with the Game_ID from the first item in the loop
	if(!isset($BaseData['AverageGame'])){
		$BaseData['AverageGame']=$row['Game_ID'];
	}
	if(!isset($BaseData['HarMeanGame'])){
		$BaseData['HarMeanGame']=$row['Game_ID'];
	}
	if(!isset($BaseData['MedianGame'])){
		$BaseData['MedianGame']=$row['Game_ID'];
	}
	if(!isset($BaseData['ModeGame'])){
		$BaseData['ModeGame']=$row['Game_ID'];
	}
	if(!isset($BaseData['max1Game'])){
		$BaseData['max1Game']=$row['Game_ID'];
	}
	if(!isset($BaseData['max2Game'])){
		$BaseData['max2Game']=$row['Game_ID'];
	}
	if(!isset($BaseData['min1Game'])){
		$BaseData['min1Game']=$row['Game_ID'];
	}
	if(!isset($BaseData['min2Game'])){
		$BaseData['min2Game']=$row['Game_ID'];
	}
	//echo $row['Game_ID'] . " ";
	
	if($datatype=="Date" && is_string($row['usevalue']) ){
		//echo "Datatype is DATE and usevalue is STRING<br>";
		
		//Usevalue2 is always the same for some reason? 
		//When the start value is higher than the target it never changes!
		$usevalue=strtotime($row['usevalue']);
		$usevalue2['HarMean']=strtotime($calculations[$BaseData['HarMeanGame']][$sourcekey]);
	} else {
		//echo "Datatype is not DATE (" . var_export($datatype,true). ") or usevalue is not STRING (" . var_export($row['usevalue'],true). ")<br>";
		$usevalue=$row['usevalue'];
		$usevalue2['HarMean']=$calculations[$BaseData['HarMeanGame']][$sourcekey];
	}
	
	if($datatype=="Date" && is_string($calculations[$BaseData['AverageGame']][$sourcekey]) ){
		$usevalue2['Average']=strtotime($calculations[$BaseData['AverageGame']][$sourcekey]);
	} else {
		$usevalue2['Average']=$calculations[$BaseData['AverageGame']][$sourcekey];
	}

	if($datatype=="Date" && is_string($calculations[$BaseData['MedianGame']][$sourcekey]) ){
		$usevalue2['Median']=strtotime($calculations[$BaseData['MedianGame']][$sourcekey]);
	} else {
		$usevalue2['Median']=$calculations[$BaseData['MedianGame']][$sourcekey];
	}

	if($datatype=="Date" && is_string($calculations[$BaseData['ModeGame']][$sourcekey]) ){
		$usevalue2['Mode']=strtotime($calculations[$BaseData['ModeGame']][$sourcekey]);
	} else {
		$usevalue2['Mode']=$calculations[$BaseData['ModeGame']][$sourcekey];
	}
	//
	if($datatype=="Date" && is_string($calculations[$BaseData['max1Game']][$sourcekey]) ){
		$usevalue2['max1']=strtotime($calculations[$BaseData['max1Game']][$sourcekey]);
	} else {
		$usevalue2['max1']=$calculations[$BaseData['max1Game']][$sourcekey];
	}
	
	if($datatype=="Date" && is_string($calculations[$BaseData['max2Game']][$sourcekey]) ){
		$usevalue2['max2']=strtotime($calculations[$BaseData['max2Game']][$sourcekey]);
	} else {
		$usevalue2['max2']=$calculations[$BaseData['max2Game']][$sourcekey];
	}
	
	if($datatype=="Date" && is_string($calculations[$BaseData['min1Game']][$sourcekey]) ){
		$usevalue2['min1']=strtotime($calculations[$BaseData['min1Game']][$sourcekey]);
	} else {
		$usevalue2['min1']=$calculations[$BaseData['min1Game']][$sourcekey];
	}
	
	if($datatype=="Date" && is_string($calculations[$BaseData['min2Game']][$sourcekey]) ){
		$usevalue2['min2']=strtotime($calculations[$BaseData['min2Game']][$sourcekey]);
	} else {
		$usevalue2['min2']=$calculations[$BaseData['min2Game']][$sourcekey];
	}
	
	//$usevalue2['Average'] is a date string. 
	//echo "if(". var_export($BaseData['Average'],true) . " >= " . var_export($usevalue,true) . " AND abs(".var_export($usevalue,true)." - " . var_export($BaseData['Average'],true). " ) <= abs(".var_export($usevalue2['Average'],true)." - " . var_export($BaseData['Average'],true)." )){";
	if($BaseData['Average']>=$usevalue AND abs($usevalue-$BaseData['Average']) <= abs($usevalue2['Average']-$BaseData['Average'])){
		$BaseData['AverageGame']=$row['Game_ID'];
	}
	//Still something wrong with the harmean release date calculation. 
	//echo "if(BaseData['HarMean']:"; var_dump($BaseData['HarMean']); echo ">=usevalue:"; var_dump($usevalue); echo " AND abs(usevalue:"; var_dump($usevalue); echo "-BaseData['HarMean']:"; var_dump($BaseData['HarMean']); echo ") <= abs(usevalue2['HarMean']:"; var_dump($usevalue2['HarMean']); echo"-BaseData['HarMean']:"; var_dump($BaseData['HarMean']); echo ")){<p>";

	//$usevalue2['HarMean'] is coming as a string formatted date for some reason.
	
	
	if($BaseData['HarMean']>=$usevalue AND abs($usevalue-$BaseData['HarMean']) <= abs($usevalue2['HarMean']-$BaseData['HarMean'])){
		$BaseData['HarMeanGame']=$row['Game_ID'];
	}
	if($BaseData['Median']>=$usevalue AND abs($usevalue-$BaseData['Median']) <= abs($usevalue2['Median']-$BaseData['Median'])){
		$BaseData['MedianGame']=$row['Game_ID'];
	}
	if(isset($BaseData['Mode'])){
		if($BaseData['Mode']>=$usevalue AND abs($usevalue-$BaseData['Mode']) <= abs($usevalue2['Mode']-$BaseData['Mode'])){
			$BaseData['ModeGame']=$row['Game_ID'];
		}
	}
	if($BaseData['max1']>=$usevalue AND abs($usevalue-$BaseData['max1']) <= abs($usevalue2['max1']-$BaseData['max1'])){
		$BaseData['max1Game']=$row['Game_ID'];
	}
	if($BaseData['max2']>=$usevalue AND abs($usevalue-$BaseData['max2']) <= abs($usevalue2['max2']-$BaseData['max2'])){
		$BaseData['max2Game']=$row['Game_ID'];
	}
	if($BaseData['min1']>=$usevalue AND abs($usevalue-$BaseData['min1']) <= abs($usevalue2['min1']-$BaseData['min1'])){
		$BaseData['min1Game']=$row['Game_ID'];
	}
	if($BaseData['min2']>=$usevalue AND abs($usevalue-$BaseData['min2']) <= abs($usevalue2['min2']-$BaseData['min2'])){
		$BaseData['min2Game']=$row['Game_ID'];
	}
	
	if(!isset($BaseData['Mode'])){
		//trigger_error("Missing MODE");
	}
	
	return $BaseData;
}

function addHoursToTarget($calculations,$sourceVariable,$VariableName,$target){
	foreach ($calculations as &$game) {
		$game[$VariableName]  =getHrsToTarget($game[$sourceVariable],  $game['GrandTotal']  ,$target);
	}
	return $calculations;
}

function getallstats($calculations){
	/* Launch Date */
	$statname=$sourcekey=$criteria['Field']="LaunchDate";
	$datatype="Date";
	$criteria['Operator']="!eq";
	$criteria['Value']="";		
	$stats['LaunchDate']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchDate']['totaltype']="None";
	/* PurchaseDate */
	$statname=$sourcekey=$criteria['Field']="PurchaseDate";
	$stats['PurchaseDate']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PurchaseDate']['totaltype']="None";
	/* Last Play */
	$statname=$sourcekey=$criteria['Field']="lastplaySort";
	$stats['lastplaySort']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['lastplaySort']['totaltype']="None";
	/* First Play */
	$statname=$sourcekey=$criteria['Field']="firstplaysort";
	$stats['firstplaysort']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['firstplaysort']['totaltype']="None";
	/* Achievements */
	$statname=$sourcekey=$criteria['Field']="SteamAchievements";
	$criteria['Operator']="gt";
	$criteria['Value']=0;		
	$criteria['round']=0;		
	$datatype="Numeric";
	$stats['SteamAchievements']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SteamAchievements']['totaltype']="Sum";
	/* Earned Achievements */
	$statname=$sourcekey="Achievements";
	$stats['Achievements']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Achievements']['totaltype']="Sum";
	/* Achievements Percent */
	$statname=$sourcekey="AchievementsPct";
	$stats['AchievementsPct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AchievementsPct']['datatype2']=2;
	$stats['AchievementsPct']['suffix']="%";
	$stats['AchievementsPct']['totaltype']="Math";
	/* Achievements Left */
	$statname=$sourcekey="AchievementsLeft";
	$stats['AchievementsLeft']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AchievementsLeft']['totaltype']="Sum";
	/* Play Time */
	$statname=$sourcekey=$criteria['Field']="GrandTotal";
	$datatype="Duration";
	$stats['totalHrs']=getStatRow($calculations,'totalHrs',$datatype,'totalHrs',$criteria);
	$stats['GrandTotal']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['GrandTotal']['datatype2']="seconds";
	$stats['GrandTotal']['totaltype']="Math";
	/* TimeToBeat */
	$statname=$sourcekey=$criteria['Field']="TimeToBeat";
	$stats['TimeToBeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['TimeToBeat']['datatype2']="hours";
	$stats['TimeToBeat']['totaltype']="Sum";
	/* TimeLeftToBeat */
	$statname=$sourcekey="TimeLeftToBeat";
	$stats['TimeLeftToBeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['TimeLeftToBeat']['totaltype']="Sum";
	/* Metascore */
	$statname=$sourcekey=$criteria['Field']="Metascore";
	$datatype="Numeric";
	$stats['Metascore']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Metascore']['totaltype']="None";
	/* UserMetascore */
	$statname=$sourcekey=$criteria['Field']="UserMetascore";
	$stats['UserMetascore']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['UserMetascore']['totaltype']="None";
	/* SteamRating */
	$statname=$sourcekey=$criteria['Field']="SteamRating";
	$stats['SteamRating']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SteamRating']['totaltype']="None";
	/* Review */
	$statname=$sourcekey=$criteria['Field']="Review";
	$stats['Review']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Review']['totaltype']="None";
	
	/* LaunchPrice */
	$statname=$sourcekey=$criteria['Field']="LaunchPrice";
	$stats['LaunchPrice']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchPrice']['datatype2']=2;
	$stats['LaunchPrice']['prefix']="$";
	$stats['LaunchPrice']['totaltype']="Sum";
	/* LaunchVariance */
	$statname=$sourcekey=$criteria['Field']="LaunchVariance";
	$stats['LaunchVariance']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchVariance']['datatype2']=2;
	$stats['LaunchVariance']['prefix']="$";
	$stats['LaunchVariance']['totaltype']="Math";
	/* LaunchVariancePct */
	$statname=$sourcekey="LaunchVariancePct";
	$criteria['Field']="LaunchPrice";
	$stats['LaunchVariancePct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchVariancePct']['datatype2']=2;
	$stats['LaunchVariancePct']['suffix']="%";
	$stats['LaunchVariancePct']['totaltype']="Math";
	/* Launchperhr */
	$statname=$sourcekey=$criteria['Field']="Launchperhr";
	$stats['Launchperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Launchperhr']['datatype2']=2;
	$stats['Launchperhr']['prefix']="$";
	$stats['Launchperhr']['totaltype']="Math";
	/* LaunchLess2 */
	$statname=$sourcekey=$criteria['Field']="LaunchLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['LaunchLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchLess2']['datatype2']='hours';
	$stats['LaunchLess2']['totaltype']="Math";
	/* LaunchLess1 */
	$statname=$sourcekey=$criteria['Field']="LaunchLess1";
	$criteria['Value']=0;
	$datatype="Numeric";
	$stats['LaunchLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchLess1']['datatype2']=2;
	$stats['LaunchLess1']['prefix']="$";
	$stats['LaunchLess1']['totaltype']="Math";
	/* Launchperhrbeat */
	$statname=$sourcekey=$criteria['Field']="Launchperhrbeat";
	$stats['Launchperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Launchperhrbeat']['datatype2']=2;
	$stats['Launchperhrbeat']['prefix']="$";
	$stats['Launchperhrbeat']['totaltype']="Math";
	
	
	/* MSRP */
	$statname=$sourcekey=$criteria['Field']="MSRP";
	$stats['MSRP']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRP']['datatype2']=2;
	$stats['MSRP']['prefix']="$";
	$stats['MSRP']['totaltype']="Sum";
	/* MSRPperhr */
	$statname=$sourcekey=$criteria['Field']="MSRPperhr";
	$stats['MSRPperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPperhr']['datatype2']=2;
	$stats['MSRPperhr']['prefix']="$";
	$stats['MSRPperhr']['totaltype']="Math";
	/* MSRPLess2 */
	$statname=$sourcekey=$criteria['Field']="MSRPLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['MSRPLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPLess2']['datatype2']='hours';
	$stats['MSRPLess2']['totaltype']="Math";
	/* MSRPLess1 */
	$statname=$sourcekey=$criteria['Field']="MSRPLess1";
	$criteria['Value']=0.005;
	$datatype="Numeric";
	$stats['MSRPLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPLess1']['datatype2']=2;
	$stats['MSRPLess1']['prefix']="$";
	$stats['MSRPLess1']['totaltype']="Math";
	/* MSRPperhrbeat */
	$statname=$sourcekey=$criteria['Field']="MSRPperhrbeat";
	$stats['MSRPperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPperhrbeat']['datatype2']=2;
	$stats['MSRPperhrbeat']['prefix']="$";
	$stats['MSRPperhrbeat']['totaltype']="Math";
	/* */
	
	/* HistoricLow */
	$statname=$sourcekey=$criteria['Field']="HistoricLow";
	$stats['HistoricLow']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricLow']['datatype2']=2;
	$stats['HistoricLow']['prefix']="$";
	$stats['HistoricLow']['totaltype']="Sum";
	/* HistoricVariance */
	$statname=$sourcekey=$criteria['Field']="HistoricVariance";
	$stats['HistoricVariance']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricVariance']['datatype2']=2;
	$stats['HistoricVariance']['prefix']="$";
	$stats['HistoricVariance']['totaltype']="Math";
	/* HistoricVariancePct */
	$statname=$sourcekey="HistoricVariancePct";
	$criteria['Field']="HistoricLow";
	$stats['HistoricVariancePct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricVariancePct']['datatype2']=2;
	$stats['HistoricVariancePct']['suffix']="%";
	$stats['HistoricVariancePct']['totaltype']="Math";
	/* Historicperhr */
	$statname=$sourcekey=$criteria['Field']="Historicperhr";
	$stats['Historicperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Historicperhr']['datatype2']=2;
	$stats['Historicperhr']['prefix']="$";
	$stats['Historicperhr']['totaltype']="Math";
	/* HistoricLess2 */
	$statname=$sourcekey=$criteria['Field']="HistoricLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['HistoricLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricLess2']['datatype2']='hours';
	$stats['HistoricLess2']['totaltype']="Math";
	/* HistoricLess1 */
	$statname=$sourcekey=$criteria['Field']="HistoricLess1";
	$criteria['Value']=0.005;
	$datatype="Numeric";
	$stats['HistoricLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricLess1']['datatype2']=2;
	$stats['HistoricLess1']['prefix']="$";
	$stats['HistoricLess1']['totaltype']="Math";
	/* Historicperhrbeat */
	$statname=$sourcekey=$criteria['Field']="Historicperhrbeat";
	$stats['Historicperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Historicperhrbeat']['datatype2']=2;
	$stats['Historicperhrbeat']['prefix']="$";
	$stats['Historicperhrbeat']['totaltype']="Math";
	/* */
	
	
	/* Paid */
	$statname=$sourcekey=$criteria['Field']="Paid";
	$stats['Paid']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Paid']['datatype2']=2;
	$stats['Paid']['prefix']="$";
	$stats['Paid']['totaltype']="Sum";
	/* PaidVariance */
	$statname=$sourcekey=$criteria['Field']="PaidVariance";
	$stats['PaidVariance']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidVariance']['datatype2']=2;
	$stats['PaidVariance']['prefix']="$";
	$stats['PaidVariance']['totaltype']="Math";
	/* PaidVariancePct */
	$statname=$sourcekey="PaidVariancePct";
	$criteria['Field']="Paid";
	$stats['PaidVariancePct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidVariancePct']['datatype2']=2;
	$stats['PaidVariancePct']['suffix']="%";
	$stats['PaidVariancePct']['totaltype']="Math";
	/* Paidperhr */
	$statname=$sourcekey=$criteria['Field']="Paidperhr";
	$stats['Paidperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Paidperhr']['datatype2']=2;
	$stats['Paidperhr']['prefix']="$";
	$stats['Paidperhr']['totaltype']="Math";
	/* PaidLess2 */
	$statname=$sourcekey=$criteria['Field']="PaidLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['PaidLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidLess2']['datatype2']='hours';
	$stats['PaidLess2']['totaltype']="Math";
	/* PaidLess1 */
	$statname=$sourcekey=$criteria['Field']="PaidLess1";
	$criteria['Value']=0.005;
	$datatype="Numeric";
	$stats['PaidLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidLess1']['datatype2']=2;
	$stats['PaidLess1']['prefix']="$";
	$stats['PaidLess1']['totaltype']="Math";
	/* Paidperhrbeat */
	$statname=$sourcekey=$criteria['Field']="Paidperhrbeat";
	$stats['Paidperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Paidperhrbeat']['datatype2']=2;
	$stats['Paidperhrbeat']['prefix']="$";
	$stats['Paidperhrbeat']['totaltype']="Math";
	/* */
	
	
	/* SalePrice */
	$statname=$sourcekey=$criteria['Field']="SalePrice";
	$stats['SalePrice']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SalePrice']['datatype2']=2;
	$stats['SalePrice']['prefix']="$";
	$stats['SalePrice']['totaltype']="Sum";
	/* SaleVariance */
	$statname=$sourcekey=$criteria['Field']="SaleVariance";
	$stats['SaleVariance']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleVariance']['datatype2']=2;
	$stats['SaleVariance']['prefix']="$";
	$stats['SaleVariance']['totaltype']="Math";
	/* SaleVariancePct */
	$statname=$sourcekey="SaleVariancePct";
	$criteria['Field']="SalePrice";
	$stats['SaleVariancePct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleVariancePct']['datatype2']=2;
	$stats['SaleVariancePct']['suffix']="%";
	$stats['SaleVariancePct']['totaltype']="Math";
	/* Saleperhr */
	$statname=$sourcekey=$criteria['Field']="Saleperhr";
	$stats['Saleperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Saleperhr']['datatype2']=2;
	$stats['Saleperhr']['prefix']="$";
	$stats['Saleperhr']['totaltype']="Math";
	/* SaleLess2 */
	$statname=$sourcekey=$criteria['Field']="SaleLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['SaleLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleLess2']['datatype2']='hours';
	$stats['SaleLess2']['totaltype']="Math";
	/* SaleLess1 */
	$statname=$sourcekey=$criteria['Field']="SaleLess1";
	$criteria['Value']=0.005;
	$datatype="Numeric";
	$stats['SaleLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleLess1']['datatype2']=2;
	$stats['SaleLess1']['prefix']="$";
	$stats['SaleLess1']['totaltype']="Math";
	/* Saleperhrbeat */
	$statname=$sourcekey=$criteria['Field']="Saleperhrbeat";
	$stats['Saleperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Saleperhrbeat']['datatype2']=2;
	$stats['Saleperhrbeat']['prefix']="$";
	$stats['Saleperhrbeat']['totaltype']="Math";
	/* */
		
	
	/* AltSalePrice */
	$statname=$sourcekey=$criteria['Field']="AltSalePrice";
	$stats['AltSalePrice']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltSalePrice']['datatype2']=2;
	$stats['AltSalePrice']['prefix']="$";
	$stats['AltSalePrice']['totaltype']="Sum";
	/* AltSaleVariance */
	$statname=$sourcekey=$criteria['Field']="AltSaleVariance";
	$stats['AltSaleVariance']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltSaleVariance']['datatype2']=2;
	$stats['AltSaleVariance']['prefix']="$";
	$stats['AltSaleVariance']['totaltype']="Math";
	/* AltSaleVariancePct */
	$statname=$sourcekey="AltSaleVariancePct";
	$criteria['Field']="AltSalePrice";
	$stats['AltSaleVariancePct']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltSaleVariancePct']['datatype2']=2;
	$stats['AltSaleVariancePct']['suffix']="%";
	$stats['AltSaleVariancePct']['totaltype']="Math";
	/* Altperhr */
	$statname=$sourcekey=$criteria['Field']="Altperhr";
	$stats['Altperhr']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Altperhr']['datatype2']=2;
	$stats['Altperhr']['prefix']="$";
	$stats['Altperhr']['totaltype']="Math";
	/* AltLess2 */
	$statname=$sourcekey=$criteria['Field']="AltLess2";
	$criteria['Value']=.0003;
	$datatype="Duration";
	$stats['AltLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltLess2']['datatype2']='hours';
	$stats['AltLess2']['totaltype']="Math";
	/* AltLess1 */
	$statname=$sourcekey=$criteria['Field']="AltLess1";
	$criteria['Value']=0.005;
	$datatype="Numeric";
	$stats['AltLess1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltLess1']['datatype2']=2;
	$stats['AltLess1']['prefix']="$";
	$stats['AltLess1']['totaltype']="Math";
	/* Altperhrbeat */
	$statname=$sourcekey=$criteria['Field']="Altperhrbeat";
	$stats['Altperhrbeat']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['Altperhrbeat']['datatype2']=2;
	$stats['Altperhrbeat']['prefix']="$";
	$stats['Altperhrbeat']['totaltype']="Math";
	/* */

	return $stats;
}

function getmetastats($calculations,$stats=false){
	if($stats===false){
		$stats=getallstats($calculations);
	} 
	
	$TargetGameID=514;
	
	$criteria['Operator']="gt";
	$criteria['Value']=0;
	$criteria['round']=0;		
	$datatype="Duration";
	
	/* LaunchHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="LaunchHrsNext1";
	$stats['LaunchHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchHrsNext1']['datatype2']='hours';
	/* LaunchHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="LaunchHrsNext2";
	$stats['LaunchHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchHrsNext2']['datatype2']='hours';
	/* LaunchLess2 */
	$statname=$sourcekey=$criteria['Field']="LaunchLess2";
	$stats['LaunchLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchLess2']['datatype2']='hours';
	/* LaunchHrs5 */
	$statname=$sourcekey=$criteria['Field']="LaunchHrs5";
	$stats['LaunchHrs5']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchHrs5']['datatype2']='hours';
	/* launchhrsavg */
	$statname=$sourcekey=$criteria['Field']="launchhrsavg";
	$calculations=addHoursToTarget($calculations,"LaunchPrice",$statname,$stats['LaunchPrice']['Average']);
	$stats['launchhrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['launchhrsavg']['datatype2']='hours';
	/* launchhrsmean */
	$statname=$sourcekey=$criteria['Field']="launchhrsmean";
	$calculations=addHoursToTarget($calculations,"LaunchPrice",$statname,$stats['LaunchPrice']['HarMean']);
	$stats['launchhrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['launchhrsmean']['datatype2']='hours';
	/* launchhrsmedian */
	$statname=$sourcekey=$criteria['Field']="launchhrsmedian";
	$calculations=addHoursToTarget($calculations,"LaunchPrice",$statname,$stats['LaunchPrice']['Median']);
	$stats['launchhrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['launchhrsmedian']['datatype2']='hours';
	/* launchhrsgame */
	$statname=$sourcekey=$criteria['Field']="launchhrsgame";
	$calculations=addHoursToTarget($calculations,"LaunchPrice",$statname,$calculations[$TargetGameID]['Launchperhr']);
	$stats['launchhrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['launchhrsgame']['datatype2']='hours';
	
	
	/* MSRPHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="MSRPHrsNext1";
	$stats['MSRPHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPHrsNext1']['datatype2']='hours';
	/* MSRPHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="MSRPHrsNext2";
	$stats['MSRPHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPHrsNext2']['datatype2']='hours';
	/* MSRPLess2 */
	$statname=$sourcekey=$criteria['Field']="MSRPLess2";
	$stats['MSRPLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPLess2']['datatype2']='hours';
	/* MSRPHrs3 */
	$statname=$sourcekey=$criteria['Field']="MSRPHrs3";
	$stats['MSRPHrs3']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['MSRPHrs3']['datatype2']='hours';
	/* msrphrsavg */
	$statname=$sourcekey=$criteria['Field']="msrphrsavg";
	$calculations=addHoursToTarget($calculations,"MSRP",$statname,$stats['MSRP']['Average']);
	$stats['msrphrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['msrphrsavg']['datatype2']='hours';
	/* msrphrsmean */
	$statname=$sourcekey=$criteria['Field']="msrphrsmean";
	$calculations=addHoursToTarget($calculations,"MSRP",$statname,$stats['MSRP']['HarMean']);
	$stats['msrphrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['msrphrsmean']['datatype2']='hours';
	/* msrphrsmedian */
	$statname=$sourcekey=$criteria['Field']="msrphrsmedian";
	$calculations=addHoursToTarget($calculations,"MSRP",$statname,$stats['MSRP']['Median']);
	$stats['msrphrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['msrphrsmedian']['datatype2']='hours';
	/* msrphrsgame */
	$statname=$sourcekey=$criteria['Field']="msrphrsgame";
	$calculations=addHoursToTarget($calculations,"MSRP",$statname,$calculations[$TargetGameID]['MSRPperhr']);
	$stats['msrphrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['msrphrsgame']['datatype2']='hours';
	
	
	/* HistoricHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="HistoricHrsNext1";
	$stats['HistoricHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricHrsNext1']['datatype2']='hours';
	/* HistoricHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="HistoricHrsNext2";
	$stats['HistoricHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricHrsNext2']['datatype2']='hours';
	/* HistoricLess2 */
	$statname=$sourcekey=$criteria['Field']="HistoricLess2";
	$stats['HistoricLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricLess2']['datatype2']='hours';
	/* HistoricHrs3 */
	$statname=$sourcekey=$criteria['Field']="HistoricHrs3";
	$stats['HistoricHrs3']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['HistoricHrs3']['datatype2']='hours';
	/* histhrsavg */
	$statname=$sourcekey=$criteria['Field']="histhrsavg";
	$calculations=addHoursToTarget($calculations,"HistoricLow",$statname,$stats['HistoricLow']['Average']);
	$stats['histhrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['histhrsavg']['datatype2']='hours';
	/* histhrsmean */
	$statname=$sourcekey=$criteria['Field']="histhrsmean";
	$calculations=addHoursToTarget($calculations,"HistoricLow",$statname,$stats['HistoricLow']['HarMean']);
	$stats['histhrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['histhrsmean']['datatype2']='hours';
	/* histhrsmedian */
	$statname=$sourcekey=$criteria['Field']="histhrsmedian";
	$calculations=addHoursToTarget($calculations,"HistoricLow",$statname,$stats['HistoricLow']['Median']);
	$stats['histhrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['histhrsmedian']['datatype2']='hours';
	/* histhrsgame */
	$statname=$sourcekey=$criteria['Field']="histhrsgame";
	$calculations=addHoursToTarget($calculations,"HistoricLow",$statname,$calculations[$TargetGameID]['Historicperhr']);
	$stats['histhrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['histhrsgame']['datatype2']='hours';
	
	
	/* PaidHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="PaidHrsNext1";
	$stats['PaidHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidHrsNext1']['datatype2']='hours';
	/* PaidHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="PaidHrsNext2";
	$stats['PaidHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidHrsNext2']['datatype2']='hours';
	/* PaidLess2 */
	$statname=$sourcekey=$criteria['Field']="PaidLess2";
	$stats['PaidLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidLess2']['datatype2']='hours';
	/* PaidHrs3 */
	$statname=$sourcekey=$criteria['Field']="PaidHrs3";
	$stats['PaidHrs3']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['PaidHrs3']['datatype2']='hours';
	/* paidhrsavg */
	$statname=$sourcekey=$criteria['Field']="paidhrsavg";
	$calculations=addHoursToTarget($calculations,"Paid",$statname,$stats['Paid']['Average']);
	$stats['paidhrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['paidhrsavg']['datatype2']='hours';
	/* paidhrsmean */
	$statname=$sourcekey=$criteria['Field']="paidhrsmean";
	$calculations=addHoursToTarget($calculations,"Paid",$statname,$stats['Paid']['HarMean']);
	$stats['paidhrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['paidhrsmean']['datatype2']='hours';
	/* paidhrsmedian */
	$statname=$sourcekey=$criteria['Field']="paidhrsmedian";
	$calculations=addHoursToTarget($calculations,"Paid",$statname,$stats['Paid']['Median']);
	$stats['paidhrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['paidhrsmedian']['datatype2']='hours';
	/* paidhrsgame */
	$statname=$sourcekey=$criteria['Field']="paidhrsgame";
	$calculations=addHoursToTarget($calculations,"Paid",$statname,$calculations[$TargetGameID]['Paidperhr']);
	$stats['paidhrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['paidhrsgame']['datatype2']='hours';
	
	
	/* SaleHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="SaleHrsNext1";
	$stats['SaleHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleHrsNext1']['datatype2']='hours';
	/* SaleHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="SaleHrsNext2";
	$stats['SaleHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleHrsNext2']['datatype2']='hours';
	/* SaleLess2 */
	$statname=$sourcekey=$criteria['Field']="SaleLess2";
	$stats['SaleLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleLess2']['datatype2']='hours';
	/* SaleHrs3 */
	$statname=$sourcekey=$criteria['Field']="SaleHrs3";
	$stats['SaleHrs3']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['SaleHrs3']['datatype2']='hours';
	/* salehrsavg */
	$statname=$sourcekey=$criteria['Field']="salehrsavg";
	$calculations=addHoursToTarget($calculations,"SalePrice",$statname,$stats['SalePrice']['Average']);
	$stats['salehrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['salehrsavg']['datatype2']='hours';
	/* salehrsmean */
	$statname=$sourcekey=$criteria['Field']="salehrsmean";
	$calculations=addHoursToTarget($calculations,"SalePrice",$statname,$stats['SalePrice']['HarMean']);
	$stats['salehrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['salehrsmean']['datatype2']='hours';
	/* salehrsmedian */
	$statname=$sourcekey=$criteria['Field']="salehrsmedian";
	$calculations=addHoursToTarget($calculations,"SalePrice",$statname,$stats['SalePrice']['Median']);
	$stats['salehrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['salehrsmedian']['datatype2']='hours';
	/* salehrsgame */
	$statname=$sourcekey=$criteria['Field']="salehrsgame";
	$calculations=addHoursToTarget($calculations,"SalePrice",$statname,$calculations[$TargetGameID]['Saleperhr']);
	$stats['salehrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['salehrsgame']['datatype2']='hours';
	
	
	/* AltHrsNext1 */
	$statname=$sourcekey=$criteria['Field']="AltHrsNext1";
	$stats['AltHrsNext1']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltHrsNext1']['datatype2']='hours';
	/* AltHrsNext2 */
	$statname=$sourcekey=$criteria['Field']="AltHrsNext2";
	$stats['AltHrsNext2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltHrsNext2']['datatype2']='hours';
	/* AltLess2 */
	$statname=$sourcekey=$criteria['Field']="AltLess2";
	$stats['AltLess2']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltLess2']['datatype2']='hours';
	/* AltHrs3 */
	$statname=$sourcekey=$criteria['Field']="AltHrs3";
	$stats['AltHrs3']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['AltHrs3']['datatype2']='hours';
	/* althrsavg */
	$statname=$sourcekey=$criteria['Field']="althrsavg";
	$calculations=addHoursToTarget($calculations,"AltSalePrice",$statname,$stats['AltSalePrice']['Average']);
	$stats['althrsavg']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['althrsavg']['datatype2']='hours';
	/* althrsmean */
	$statname=$sourcekey=$criteria['Field']="althrsmean";
	$calculations=addHoursToTarget($calculations,"AltSalePrice",$statname,$stats['AltSalePrice']['HarMean']);
	$stats['althrsmean']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['althrsmean']['datatype2']='hours';
	/* althrsmedian */
	$statname=$sourcekey=$criteria['Field']="althrsmedian";
	$calculations=addHoursToTarget($calculations,"AltSalePrice",$statname,$stats['AltSalePrice']['Median']);
	$stats['althrsmedian']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['althrsmedian']['datatype2']='hours';
	/* althrsgame */
	$statname=$sourcekey=$criteria['Field']="althrsgame";
	$calculations=addHoursToTarget($calculations,"AltSalePrice",$statname,$calculations[$TargetGameID]['Altperhr']);
	$stats['althrsgame']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['althrsgame']['datatype2']='hours';
	
	return $stats;
}

function getmetametastats($calculations,$metastats=false){
	if($metastats===false){
		//$metastats=getmetastats($calculations);
	} 
	
	$criteria['Operator']="gt";
	$criteria['Value']=0;
	$criteria['round']=0;		
	$datatype="Duration";
	
	/* LaunchHrsAvgActive */
	$statname=$sourcekey=$criteria['Field']="LaunchHrsAvgActive";
	$stats['LaunchHrsAvgActive']=getStatRow($calculations,$statname,$datatype,$sourcekey,$criteria);
	$stats['LaunchHrsAvgActive']['datatype2']='hours';
	

}
?>