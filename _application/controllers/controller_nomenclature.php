<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 06.09.2016
 * Time: 20:46
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
apache_response_headers();

class Controller_nomenclature extends Controller {
    function __construct() {
        $this->model = new model_nomenclature();
    }
    function action_index() {
//        $this->model->connect();
        $this->model->updateKeywordsOne();
//        $this->model->MIreestrDistinct();
//        $this->model->close();
    }
    
    function action_findSubSection() {
        $this->model->findSubSection();
    }
}
?>