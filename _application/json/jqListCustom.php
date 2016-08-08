<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 25.06.2016
 * Time: 12:44
 */
include "../cfg/connectConfig.php";
header("Content-type: text/json;charset=utf-8");
$id=$_GET[id];
//print_r($_POST);
//print_r($_GET);
$mysqli = new mysqli(Host, User, Password, DB);
if (mysqli_connect_errno()) { echo "Подключение невозможно: ".mysqli_connect_error(); }
$mysqli->set_charset("utf8");
$query ="SELECT DISTINCT col5 FROM reestr_distinct WHERE col1='$id'";
//print_r($query);

if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
$row = $result->fetch_assoc();
/*
$responce->page = 1;
$responce->total = 1;
$responce->records = 1;
$rows = array();
$i=0;
while($row = $result->fetch_assoc()) {
    $responce->rows[$i]['id']=$id;
    $responce->rows[$i]['cell']=$row;
    $i++;
}
echo json_encode($responce);
*/
echo "[".json_encode($row)."]";
//print_r($row);