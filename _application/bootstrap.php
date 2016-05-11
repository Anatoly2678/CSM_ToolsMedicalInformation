<?php
ini_set("max_execution_time", 0);
ignore_user_abort(true);
set_time_limit(0);
sleep(5);
require_once 'cfg/connectConfig.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
Route::start(); // запускаем маршрутизатор
?>