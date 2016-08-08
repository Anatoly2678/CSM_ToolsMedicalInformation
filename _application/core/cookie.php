<?php
$type=$_POST['type'];

switch ($type) {
	case 'sethide':
	$value = $_POST['col_alias'];
		setcookie($_POST['col_name'], $value);
		break;
	case 'setshow':
		setcookie ($_POST['col_name'], "", time() - 3600);
		break;
	case 'get':
		header("Content-type: text/json;charset=utf-8");
		echo json_encode($_COOKIE);
		break;
	
	default:
		die ("TYPE is not install");
		break;
}