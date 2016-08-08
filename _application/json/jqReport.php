<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 09.06.2016
 * Time: 20:54
 */
set_time_limit(0);
ini_set('memory_limit', '200M');
include "../cfg/connectConfig.php";
    header("Content-type: text/json;charset=utf-8");
    $col=$_GET['col'];
//    $page = $_REQUEST['page']; // get the requested page
//    $oper = $_REQUEST['oper']; // get the requested page
//    $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
    $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
    $sord = $_REQUEST['sord']; // get the direction

if ($oper =='excel') {
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=11.xls");
}
//
//    $searchString ="";
//    if($_search == 'true'){
//        if (isset($_GET['filters'])) $filters = $_GET['filters'];// Фильтры для поиска
//        if (isset($_GET['searchField'])) $searchField = $_GET['searchField']; // Фильтр по одному полю (имя)
//        if (isset($_GET['searchOper'])) $searchOper = $_GET['searchOper']; // Фильтр по одному полю (операция)
//        if (isset($_GET['searchString'])) $searchString = $_GET['searchString']; // Фильтр по одному полю (значение)
//        $searchString = generateSearchString($filters, $searchField, $searchOper, $searchString);
//        if ($searchString != '') $searchString = "WHERE ".$searchString." ";
//    }
//    if(!$sidx) $sidx =1;
//    $totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
//    if($totalrows) {
//        $limit = $totalrows;
//    }
    $mysqli = new mysqli(Host, User, Password, DB);
    if (mysqli_connect_errno()) { echo "Подключение невозможно: ".mysqli_connect_error(); }
    $mysqli->set_charset("utf8");
    $query= "SELECT col".$col.", COUNT(*) count_col".$col." FROM reestr_distinct GROUP BY col".$col." HAVING count(*)>1 ORDER BY $sidx $sord";


//    if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
//    $row = $result->fetch_assoc();
//    $count = $row['count'];
//    if( $count >0 ) {
//        $total_pages = ceil($count/$limit);
//    } else {
//        $total_pages = 0;
//    }
//    if ($page > $total_pages) $page=$total_pages;
//    if ($limit<0) $limit = 0;
//    $start = $limit*$page - $limit; // do not put $limit*($page - 1)
//    if ($start<0) $start = 0;
//    $query = "SELECT r.col1, r.col2,  r.col3, r.col4_state, r.col4, r.col4_data, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10,
//    r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r ".$searchString." ORDER BY $sidx $sord LIMIT $start , $limit"; //$start, $pagesize

    if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
//    $responce->page = $page;
//    $responce->total = $total_pages;
//    $responce->records = $count;
    $rows = array();
    $i=0;
    while($row = $result->fetch_assoc()) {
//        $responce->rows[$i]['id']=$row[col1];
            $responce->rows[$i]['cell']=$row;
        $i++;
    }
    echo json_encode($responce);

