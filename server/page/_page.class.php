<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/template.inc.php";
require_once $GLOBALS['rootpath']."/inc/getSettings.inc.php";
require_once $GLOBALS['rootpath']."/inc/dataAccess.class.php";

class Page
{
	protected $title="Page Title";
	protected $body=" Body ";
	protected $dataAccessObject;
	protected $settings;
	
	protected function getDataAccessObject(){
		if(!isset($this->dataAccessObject)){
			$this->dataAccessObject= new dataAccess();
		}
		return $this->dataAccessObject;
	}

	protected function getSettings(){
		if(!isset($this->settings)){
			$this->settings = getsettings();
		}
		return $this->settings;
	}

	public function outputBody(){
		return $this->body;
	}

	public function buildHtmlBody(){
		$this->body = "Page Body";
	}
	
	public function outputHtml(){
		//Need to use echo here so any PHP errors generated during buildHtmlBody show below the Header.
		//Otherwise they will be under the top navigation bar.
		
		//$output = Get_Header($this->title);
		echo Get_Header($this->title);
		//$output .= $this->buildHtmlBody();
		echo $this->buildHtmlBody();
		//$output .= Get_Footer();
		echo Get_Footer();
		//return $output;
		return "";
	}
}