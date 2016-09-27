<?php
error_reporting(E_ERROR); //  | E_WARNING | E_PARSE
set_time_limit(0);
// ini_set('memory_limit', '512M');
class Controller_search extends Controller {
    function __construct() {
        $this->model = new model_search();
        $this->fullSearch = new model_fullSearch();
    }

    function action_index() {
//        var_dump($_REQUEST['searchAll']);
        if ($_REQUEST['searchAll']=="") {
            $this->model->searchReestr();
        } else {
            $this->fullSearch->search();
            // $this->model->searchAllReestr();
        }
        
//        $class_vars = get_class_vars(get_class($this->model));
//        print_r($class_vars);

    }

    function action_export() {
       $this->model->searchforExport();
    }
}
?>