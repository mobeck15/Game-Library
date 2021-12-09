<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require $GLOBALS['rootpath']."/inc/auth.inc.php";

$title="Export";

//from https://stackoverflow.com/questions/22195493/export-mysql-database-using-php-only

//ENTER THE RELEVANT INFO BELOW
$mysqlUserName      = $username;
$mysqlPassword      = $password;
$mysqlHostName      = $servername;
$DbName             = $dbname;
//$backup_name        = "mybackup.sql";
//$tables             = "Your tables";
//$tables             = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');
$tables             = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');

//or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
// if you have only one table then add your table name on $tables = 'your_table_name' of if you want add multi table then pass array on $tables, like $tables = array('tbl_1','tbl_2','tbl_3'); on line no.8. no need to define any other parameter.

//Done: This exports all data including views which cause problems. Update to only export data tables.
//TODO: Add Import functions.

if(isset($_GET['export'])) {
Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables, $backup_name=false );
} else {
	echo Get_Header($title);
	?>
	<form>
		<input type="submit" name="export" value="Export">
	</form>
	<?php
	echo Get_Footer();
}


function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )
{
	ini_set('display_errors',0);
	
	$mysqli = new mysqli($host,$user,$pass,$name); 
	$mysqli->select_db($name); 
	$mysqli->query("SET NAMES 'utf8'");

	$queryTables    = $mysqli->query('SHOW TABLES'); 
	while($row = $queryTables->fetch_row()) 
	{ 
		$target_tables[] = $row[0]; 
	}   
	//echo "<p><b>Tables:</b>";
	//var_dump($target_tables);
	
	if($tables !== false) 
	{ 
		$target_tables = array_intersect( $target_tables, $tables); 
	}
	
	//echo "<p><b>Tables2:</b>";
	//var_dump($target_tables);
	//echo "<p>";
	
	foreach($target_tables as $table)
	{
		$result         =   $mysqli->query('SELECT * FROM '.$table);  
		$fields_amount  =   $result->field_count;  
		$rows_num       =   $mysqli->affected_rows;     
		$res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
		$TableMLine     =   $res->fetch_row();
		$content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
		{
			while($row = $result->fetch_row())  
			{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )  
				{
						$content .= "\nINSERT INTO ".$table." VALUES";
				}
				$content .= "\n(";
				for($j=0; $j<$fields_amount; $j++)  
				{ 
					$row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
					if (isset($row[$j]))
					{
						$content .= '"'.$row[$j].'"' ; 
					}
					else 
					{   
						$content .= '""';
					}     
					if ($j<($fields_amount-1))
					{
							$content.= ',';
					}      
				}
				$content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
				{   
					$content .= ";";
				} 
				else 
				{
					$content .= ",";
				} 
				$st_counter=$st_counter+1;
			}
		} $content .="\n\n\n";
	}
	//$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
	$backup_name = $backup_name ? $backup_name : $name."__(".date('Y-d-d_H-i-s').").sql";
	//$backup_name = $backup_name ? $backup_name : $name.".sql";
	header('Content-Type: application/octet-stream');   
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
	echo $content; exit;
}

