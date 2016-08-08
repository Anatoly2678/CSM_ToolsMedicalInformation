<?php
error_reporting(E_ALL | E_STRICT);
require_once('_application\phpmorphy\src\common.php'); // dirname(__FILE__) 
class Controller_morphy extends Controller {

	 function __construct() {
		// $this->model = new model_json();
    }
	
	function action_index()	{		
		$opts = array('storage' => PHPMORPHY_STORAGE_FILE, 'predict_by_suffix' => true, 'predict_by_db' => true, 'graminfo_as_text' => true,);
		$dir = '_application\phpmorphy\dicts';
		$lang = 'ru_RU';
		try {
    		$morphy = new phpMorphy($dir, $lang, $opts);
		} catch(phpMorphy_Exception $e) {
    		die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
		}
		echo "TEST";
		// $this->view->generate('404_view.php', 'cms_template.php');
	}

}
