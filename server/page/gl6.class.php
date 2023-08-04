<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/utility.inc.php";
include_once $GLOBALS['rootpath']."/inc/SteamFormat.class.php";

class gl6Page extends Page
{
	public function __construct() {
		$this->title="Index";
	}
	
	public function buildHtmlBody(){
		$output="";
$output .= "<table><tr><td valign=top>";
$output .= get_navmenu(false);
$output .= "<hr>";

//TODO: detect if required files are present and start an install process. (generate auth.php, create empty database etc)
/* Install Process:
Create auth.inc.php and authapi.inc.php in inc folder
prompt for and fill in values
Create database as UTF8_MB4_Unicode_CI
Add empty tables OR import from existing export
Fill lookups if empty
*/

$steamformat = new SteamFormat();
$output .= $steamformat->formatSteamLinks("",$this->getSettings()['LinkSteam']); 
$output .= '<hr>

<b>Game Library v6 - To Do List:</b>
<ul>
<li >Add a logging function to record all executed SQL statements in a local txt file.</li>
<li >use <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/grid-auto-flow">CSS Grid auto-flow</a> instead of tables</li>

<li >Backward compatiable export data<ul>
<li >Export as CSV</li>
<li >Connect to Google Sheet directly
<ul>
	<li ><a href="https://developers.google.com/sheets/api/quickstart/php">Google Sheet API</a></li>
	<li ><a href="https://getcomposer.org/">Composer</a> PHP Dependency Manager</li>
	<li ><a href="http://aljonngo.blogspot.com/2015/03/how-to-install-composer-in-windows-with.html">How to install composer in windows</a> with uniform server</li>
</ul></li>
</ul></li>

</ul>

<hr>
<b>Dynamic To Do list:</b>';

		//TODO: Add ability to scan multiple subfolders (currently only goes one deep)
		$directory    = ".";
		//TODO: Imrpove performance, takes 5+ seconds
		$scanned_directory=$this->dirToArray($directory); 

		$list="";
		foreach ($scanned_directory as $key => $file) {
			//TODO: Imrpove performance, Takes 5+ seconds
			$list.=$this->readFileLines($file,$key); 
		}
		$output .= "<ul>".$list."</ul>";

		$output .= '</td></tr></table>';
		return $output;
	}
	
	private function dirToArray($dir) {
	  
	   $result = array();

	   $cdir = scandir($dir);
	   foreach ($cdir as $key => $value)
	   {
		  if (!in_array($value,array(".","..")))
		  {
			 if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
			 {
				$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
			 }
			 else
			 {
				$result[] = $value;
			 }
		  }
	   }
	  
	   return $result;
	}

	private function readFileLines($file,$key,$path=".") {
		if(is_array($file) OR in_array((pathinfo($file)['extension'] ?? []),array("php","css"))) { 
			if(is_array($file)) {
				$useline=false;
				$todoline = "\r\n\r\n<li>";
				$todoline.= "[".$key."]";
				$subline1= "<ul>";
				foreach ($file as $key2 => $file2) {
					$line=$this->readFileLines($file2,$key2,$path."/".$key);
					$subline1.=$line;
				}
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
					$useline=false;
					$todoline = "\r\n\r\n<li>";
					$todoline.= "<a href='".$path . "/" . $file."'>".$file."</a>";
					$subline1= "<ul>";
					$read = fopen($path . "/" . $file,"r");
					$linenum=1;
					while(! feof($read))
					  {
						$pos=$line=fgets($read);
						if($line) {
							$pos=strpos($line,chr(47)."/TODO: ");
							if(!($pos===false)) {
								$subline1.= "<li>";
								$subline1.= $linenum. ": " . htmlentities(substr($line,$pos+8));
								$subline1.= "</li>";
								$useline=true;
							}
							//TODO: Title detection is not working perfectly
							$pos2=strpos($line,'$'.'title=');
							if(!($pos2===false)) {
								$pos3=strpos($line,'";');
								$todoline.= " [";
								$todoline.= htmlentities(substr($line,$pos+8,$pos3-8));
								$todoline.= "]";
							}
						}
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
}