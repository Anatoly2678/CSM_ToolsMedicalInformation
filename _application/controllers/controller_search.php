<?php
error_reporting(E_ERROR); //  | E_WARNING | E_PARSE
set_time_limit(0);
// ini_set('memory_limit', '512M');
class Controller_search extends Controller {
    function __construct() {
        $this->model = new model_search();
    }

    function action_index() {
        $this->model->searchReestr();
        
//        $class_vars = get_class_vars(get_class($this->model));
//        print_r($class_vars);

    }

    function action_export() {
       $this->model->searchforExport();
    }
}
?>