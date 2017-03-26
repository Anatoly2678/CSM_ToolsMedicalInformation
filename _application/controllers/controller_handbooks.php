<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

class Controller_handbooks extends Controller {
    function __construct() {
        $this->view = new View();
        $this->model=new model_handbooks();
    }
    function action_index() {
        $this->view->generate('handbooks.php', 'cms_template.php'); // landing.php
    }

    function action_words() {
        $this->model->getWords();
    }

    function action_endof() {
        $this->model->getEndOf();
    }

    function action_stopwords() {
        $this->model->getStopWords();
    }

    function action_synonyms() {
        $this->model->getSynonyms();   
    }
    
    function action_miunuque() {
        $this->model->getMIUnique();
    }

    function action_miunuqueupdate() {
        $this->model->updateMIUnique();
//        var_dump('update');
    }

//	function action_filter() {
//		$this->view->generate('listReestr.php', 'cms_template.php',$_GET); //
//	}
}
?>