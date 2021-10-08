<?php
$time_start = microtime(true);
include 'inc/functions.inc.php';
$title="Index";
echo Get_Header($title);
?>
<table><tr><td valign=top>
<?php echo get_navmenu(false); ?>
<hr>
<?php 
$settings=getsettings();
echo formatSteamLinks("",$settings['LinkSteam']); 
?>
<hr>

<b>Game Library v6 - To Do List:</b>
<ul>
<li class="hidden">DYNAMIC - Update settings to include new values: cntTraded, CountDupes, WeightMSRP, WeightPlay, WeightWant, Inflation, CountWntAs</li>
<li class="hidden">DYNAMIC - Add column in settings to pull setting description from database. Also update description for all settings</li>
<li >Add a logging function to record all executed SQL statements in a local txt file.</li>
<li class="hidden">DYNAMIC - Settings: Add Restore Defaults Button</li>
<li class="hidden">DYNAMIC - Stickey column headings need manual adjustment for multi-row headers</li>
<li class="hidden">DYNAMIC - Stickey column headings show behind CSS fancy checkboxes</li>
<li class="hidden">DYNAMIC - CSS fancy checkboxes appear below inline text</li>
<li >use <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/grid-auto-flow">CSS Grid auto-flow</a> instead of tables</li>


<li class="hidden">DONE: Fix CSS for dropdown Menu. It shows behind other page elements. (visible on <a href="settings.php">Settings</a>)</li>
<li class="hidden">DONE: Add css for top menu bar to be stickey</li>
<li class="hidden">DONE: Fix CSS for dropdown Menu. second tier. the gap is too big.</li>

<li >Backward compatiable export data<ul>
<li >Export as CSV</li>
<li >Connect to Google Sheet directly
<ul>
	<li ><a href="https://developers.google.com/sheets/api/quickstart/php">Google Sheet API</a></li>
	<li ><a href="https://getcomposer.org/">Composer</a> PHP Dependency Manager</li>
	<li ><a href="http://aljonngo.blogspot.com/2015/03/how-to-install-composer-in-windows-with.html">How to install composer in windows</a> with uniform server</li>
</ul></li>
</ul></li>

<li class="hidden"><a href="additem.php">Add Items</a>
<ul>
<li>Enforce required fields</li>
<li>Test null values</li>
<li>Add links to 'New' buttons for transaction and product</li>
<li>move description column to pop up text</li>
<li>Add infobox for selected product and transaction</li>
<li>FIX - DLC does not appear in Ajax lookup</li>
</ul></li>
<li class="hidden" ></li>
</ul>

<b class="hidden">Old To Do List:</b>
<ul class="hidden">
<li >Calculations Sort field sorts dates as text</li>
<li >Alt paid needs some corrections in the calculation for when games have zero hours.</li>
<li >Alt paid needs some corrections in the calculation for when a bundle has zero total hours.</li>
<li >Play total needs to be added to unplayable DLC so they can get $/hr calculations</li>
<li >Paid total should include DLC (this will make free games with paid DLC show as not free)</li>
</ul>

<hr>
<b>Dynamic To Do list:</b>
<?php
$directory    = ".";
//$scanned_directory = array_diff(scandir($directory), array('..', '.'));
$scanned_directory=dirToArray($directory);

//print_r($scanned_directory);
?>
<?php
//DONE: Fix Dynamic todo list to not have blank lines (there is some issue with extra ul tags)
//DONE: Fix Dynamic todo list to not show files and folders with no todo items.
//DONE: Dynamic todo list only looks one folder deep, make recursive.
//DONE: Add page titles for easier read.
//DONE: Add filename links to jump to relevant page
//DONE: Dynamic todo not detecting comments that are not indented at least one character.

$list="";
foreach ($scanned_directory as $key => $file) {
	//echo $key." "; var_dump($file);echo "<br>";
	$list.=readFileLines($file,$key);
}
echo "<ul>".$list."</ul>";

?>


</td></tr></table>
<?php echo Get_Footer(); ?>

<?php
function dirToArray($dir) {
  
   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         }
         else
         {
            $result[] = $value;
         }
      }
   }
  
   return $result;
}

function readFileLines($file,$key,$path=".") {
	//echo "path: [[". $path."]] key: [[".$key."]] file: [["; var_dump($file);echo "]]<br>";//DEBUG
	//If it is a file or directory, proceed
	if(is_array($file) OR in_array(pathinfo($file)['extension'],array("php","css"))) { 
	//echo "use path: [[". $path."]] key: [[".$key."]] file: [["; var_dump($file);echo "]]<br>";//DEBUG
		if(is_array($file)) {
			//echo "<hr> **Array**<br>";//DEBUG
			$useline=false;
			$todoline = "\r\n\r\n<li>";
			$todoline.= "[".$key."]";
			$subline1= "<ul>";
			foreach ($file as $key2 => $file2) {
				//echo $key2." "; var_dump($file2);echo "<br>";
				//echo $path."/".$key . "<br>";
				$line=readFileLines($file2,$key2,$path."/".$key);
				//echo "line: ";  var_dump($line); echo "<br>";//DEBUG
				//echo "<br>".htmlentities($line)."</br>";//DEBUG
				$subline1.=$line;
			}
			//echo "<hr>";//DEBUG
			$subline1.= "</ul>";
			if (isset($line) && $line != null) {
				$todoline.=$subline1;
				$useline=true;
			}
			$todoline.= "</li>";
			
			
			if($useline) { 
				return ($todoline); 
			} 
		} else {
			if(in_array(pathinfo($file)['extension'],array("php","css"))) {
			//if(pathinfo($file)['extension']=="php") {
				//echo "2nd use path: [[". $path."]] key: [[".$key."]] file: [["; var_dump($file);echo "]]<br>";//DEBUG
				$useline=false;
				$todoline = "\r\n\r\n<li>";
				$todoline.= "<a href='".$path . "/" . $file."'>".$file."</a>";
				$subline1= "<ul>";
				$read = fopen($path . "/" . $file,"r");
				$linenum=1;
				while(! feof($read))
				  {
					$line=fgets($read);
					$pos=strpos($line,chr(47)."/TODO: ");
					if(!($pos===false)) {
						$subline1.= "<li>";
						$subline1.= $linenum. ": " . htmlentities(substr($line,$pos+8));
						$subline1.= "</li>";
						$useline=true;
					}
					/* */
					$pos2=strpos($line,'$'.'title=');
					if(!($pos2===false)) {
						$pos3=strpos($line,'";');
						$todoline.= " [";
						$todoline.= htmlentities(substr($line,$pos+8,$pos3-8));
						$todoline.= "]";
					}
					/* */
					$linenum++;
				  }
				fclose($read);
				$todoline .= $subline1;
				$todoline.= "</ul>";
				$todoline.= "</li>";
				if($useline) { 
					return ($todoline);
				} 
			}
		}
	}
}

?>