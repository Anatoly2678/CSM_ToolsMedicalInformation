<?php
//header('Content-Type: text/html; charset= utf-8');
class Controller_json extends Controller {
    function __construct() {
		$this->model = new model_json();
    }

    function action_index() {
        $this->model->json();
    }

    function action_export() {
        $this->model->excel();
    }

    function action_getMIMainReestr() {
        $this->model->GetMainMIReestr();
    }
}
?>