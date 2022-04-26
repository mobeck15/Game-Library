<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/page/_page.class.php";
include_once $GLOBALS['rootpath']."/inc/export.inc.php";

//from https://stackoverflow.com/questions/22195493/export-mysql-database-using-php-only

//ENTER THE RELEVANT INFO BELOW
//$backup_name        = "mybackup.sql";
//$tables             = "Your tables";
//$tables             = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');
//$this->tables         = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');
//$tables = array('gl_cpi');

//or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
// if you have only one table then add your table name on $tables = 'your_table_name' of if you want add multi table then pass array on $tables, like $tables = array('tbl_1','tbl_2','tbl_3'); on line no.8. no need to define any other parameter.

//Done: This exports all data including views which cause problems. Update to only export data tables.
//TODO: Add Import functions.
		
class exportPage extends Page
{
	private $dataAccessObject;
	private $tables;
	
	public function __construct() {
		$this->title = "Export";
		$this->tables = array('gl_history','gl_items','gl_keywords','gl_products','gl_settings','gl_status','gl_transactions','gl_cpi');
	}

	public function outputHtml(){
		$output="";
		if(isset($_GET['export'])) {
			Export_Database($this->tables, $backup_name=false );
		} else {
			$output .= Get_Header($this->title);
			$output .= $this->buildHtmlBody();
			$output .= Get_Footer();
		}
		return $output;
	}
	
	public function buildHtmlBody(){
		$output = '<form><input type="submit" name="export" value="Export"></form>';
		return $output;
	}
}	