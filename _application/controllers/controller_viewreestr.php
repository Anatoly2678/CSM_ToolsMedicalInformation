<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
apache_response_headers();

class Controller_viewreestr extends Controller
{
		function __construct()
	{
//		$this->model = new model_test();
		 $this->view = new View();
	}
	
	function action_index()
	{
//            $this->model->get_data();
//		console.log (1111);
            $this->view->generate('cms_reestr.php', 'cms_template.php'); //
	}

}


?>