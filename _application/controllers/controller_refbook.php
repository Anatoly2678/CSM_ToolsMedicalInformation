<?php
error_reporting(E_ERROR); // 
class Controller_refbook extends Controller {
    function __construct() {
    	$this->model = new model_refbook();
		  $this->model2 = new Lingua_Stem_Ru();
    	$this->view = new View();
    }

    function action_index() {
        $this->view->generate('listRefBook.php', 'cms_template.php'); //
    }

  	function action_filter() {
		$this->view->generate('listRefBook.php', 'cms_template.php',$_GET); //
	}

  	function action_unique() {
  		header("Content-type: text/html;charset=utf-8");
  		echo "Not Using";
		  echo $this->model2->stem_word('Котеровыми');
	}

	function action_uniqueSectionName() {
  		header("Content-type: text/html;charset=utf-8");
  		$this->model->setUniqueWordsSectionName();
	}

	function action_readUnique() {
  		header("Content-type: text/html;charset=utf-8");
      $this->model->getMainSectionNomenclature();
	}

	function action_json()
	{
		$this->model->setJSONHead();
		$result=$this->model->get_data();
		echo json_encode($result);
	}

}
?>