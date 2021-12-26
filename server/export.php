<?php
$GLOBALS['rootpath']=$GLOBALS['rootpath'] ?? ".";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";
require_once $GLOBALS['rootpath']."/inc/export.inc.php";
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
		$tables = array('gl_cpi');
		//$tables = 'gl_cpi';


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