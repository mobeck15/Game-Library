<?php
declare(strict_types=1);
require_once $GLOBALS['rootpath']."/inc/template.inc.php";

class Page
{
	protected $title="Page Title";
	protected $body=" Body ";
	
	public function outputBody(){
		return $this->body;
	}

	public function buildHtmlBody(){
		$this->body = "Page Body";
	}
	
	public function outputHtml(){
		$output = Get_Header($this->title);
		$output .= $this->buildHtmlBody();
		//$output .= $this->body;
		$output .= Get_Footer();
		return $output;
	}
}