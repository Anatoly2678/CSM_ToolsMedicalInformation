<?php
	session_start();
	include 'cfg/connectConfig.php';
	$_GET['url']='exportroszdrav';
	require_once 'core/model.php';
	require_once 'core/view.php';
	require_once 'core/controller.php';
	require_once 'core/route.php';
	echo "START";
	Route::start($_GET['url']); // запускаем маршрутизатор
	echo "END";
?>


