<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/template.inc.php";

class Page
{
	private $title="Page Title";
	private $body="";
	
	public function outputBody(){
		return $this->body;
	}
	
	public function outputHtml(){
		echo Get_Header($this->title);
		echo $this->outputBody();
		echo Get_Footer();
	}
}