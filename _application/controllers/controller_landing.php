<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE); 

class Controller_landing extends Controller {
	function __construct() {
		$this->view = new View();
	}
	function action_index() {
		$this->view->generate('landing.php', 'landing_template.php'); // landing.php
	}

//	function action_filter() {
//		$this->view->generate('listReestr.php', 'cms_template.php',$_GET); //
//	}
}
?>