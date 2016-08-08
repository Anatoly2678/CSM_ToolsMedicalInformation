<?php
include "../cfg/connectConfig.php";
header("Content-type: text/json;charset=utf-8");
$mysqli = new mysqli(Host, User, Password, DB);
if (mysqli_connect_errno()) { echo "Подключение невозможно: ".mysqli_connect_error(); }
$mysqli->set_charset("utf8");
$pagenum = $_GET['pagenum'];
$pagesize = $_GET['pagesize'];
$filterscount=(int)$_GET['filterscount'];
$startindex=(int)$_GET['recordstartindex'];
$endindex=(int)$_GET['recordendindex'];
$rowsshow=$endindex-$startindex;
$sortdata=$_GET['sortdatafield'];
$sortorder=$_GET['sortorder'];
$export=$_GET['export'];
if ($export =='excel') {
//	header("Content-type: application/vnd-ms-excel");
//	header("Content-Disposition: attachment; filename=medvistavka-export.xls");
}

$order='';
if ($sortdata) {
	if ($sortdata == 'col4') {$sortdata='col4_data';}
	$order='ORDER BY r.'.$sortdata.' '.$sortorder;
}
$where='';
$start = $pagenum * $pagesize;
if ($filterscount>0) {
	$where .= ' WHERE ';
	$ff = 0;
	while ($ff < $filterscount) {
		$col=$_GET['filterdatafield'.$ff];
		$val=$_GET['filtervalue'.$ff];
		$filter=$_GET['filtercondition'.$ff];
		switch ($col) {
			case 'col4':
				$filter='CONTAINS';
				$col ='col4_data';
				switch ($val) {
					case 'Бессрочно':
						$filter='MANUAL';
						$manualwhere='('.$col.' IS NULL) AND ';
						break;
					case 'Отменено':
						$filter='MANUAL';
						$manualwhere='('.$col.' <"'.date("Y-m-d").'") AND ';
						break;
					case 'С датами':
						$filter='MANUAL';
						$manualwhere='('.$col.' IS NOT NULL) AND ';
						break;
					case 'Только действующие':
						$filter='MANUAL';
						$manualwhere='('.$col.' IS NULL OR '.$col.' >= "'.date("Y-m-d").'") AND ';
						break;
				}
				break;
		}
		switch ($filter) {
			case 'CONTAINS':
				$where .= $col. " LIKE '%".string_array_like($col,$val)."%' AND ";
				break;
			case 'GREATER_THAN_OR_EQUAL':
				$where .= $col. ">='".date("Y-m-d", strtotime($val))."' AND ";
				break;
			case 'LESS_THAN_OR_EQUAL':
				$where .= $col. "<='".date("Y-m-d", strtotime($val))."' AND ";
				break;
			case 'MANUAL':
				$where .=$manualwhere;
				break;
		}
		$ff++;
	}
	$where = substr($where, 0, -4);
}


//$query = "SELECT SQL_CALC_FOUND_ROWS r.col1, r.col2,
//DATE_FORMAT(r.col3,'%d.%m.%Y') col3, r.col4, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10,
//r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r $where $order LIMIT $start, $pagesize"; //$start, $pagesize

$query = "SELECT SQL_CALC_FOUND_ROWS r.col1, r.col2, DATE_FORMAT(r.col3,'%d.%m.%Y') col3,
CASE WHEN r.col4_data IS NULL OR r.col4_data='0000-00-00' THEN 'Бессрочно' ELSE r.col4_data END col4, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r $where $order LIMIT $start, $pagesize"; //$start, $pagesize

//print_r($query);
//die();

if ($export) {
//	$query = "SELECT r.col1, r.col2,
//DATE_FORMAT(r.col3,'%d.%m.%Y') col3, r.col4, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10,
//r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r $where $order"; //$start, $pagesize
	$query = "SELECT SQL_CALC_FOUND_ROWS r.col1, r.col2, DATE_FORMAT(r.col3,'%d.%m.%Y') col3,
CASE WHEN r.col4_data IS NULL OR r.col4_data='0000-00-00' THEN 'Бессрочно' ELSE r.col4_data END col4, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r $where $order"; //$start, $pagesize

	if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
	include ('excelexport.php');
	return false;
}

$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
if (!$rows =$mysqli->query($sql)) { echo "Error GET record: " . $mysqli->error."<br>"; };
$rows=$rows->fetch_assoc();
$total_rows = $rows['found_rows'];
while($row = $result->fetch_assoc()) {
	$customers[] = $row;
}
$data[] = array(
	'TotalRows' => $total_rows,
	'Rows' => $customers
);
echo json_encode($data);
return false;

function string_array_like($col,$val) {
	$ret_val="";
	switch ($col) {
		case 'col5':
			$arr_val = explode(" ", $val);
			$ret_val = implode("%", $arr_val);
			break;
		default:
			$ret_val=$val;
			break;
	}
	return $ret_val;
}
?>