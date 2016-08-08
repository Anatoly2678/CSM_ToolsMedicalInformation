<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
apache_response_headers();

class Controller_reestr extends Controller {
    function __construct() {
        $this->view = new View();
    }
    function action_index() {
        $this->view->generate('cms_reestr.php', 'cms_template.php'); //
    }
}
?>