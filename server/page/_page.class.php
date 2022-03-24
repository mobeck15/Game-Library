<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/template.inc.php";

class Page
{
	protected $title="Page Title";
	protected $body="Page Body";
	
	public function outputBody(){
		return $this->body;
	}

	public function buildHtmlBody(){
		$this->body = "Page Body";
	}
	
	public function outputHtml(){
		echo Get_Header($this->title);
		echo $this->buildHtmlBody();
		//echo $this->body;
		echo Get_Footer();
	}
}