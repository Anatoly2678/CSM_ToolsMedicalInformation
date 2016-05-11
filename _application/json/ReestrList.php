<?php
include "../cfg/connectConfig.php";
//include "../core/model.php";
$connect = mysql_connect(Host, User, Password);
$bool = mysql_select_db(DB, $connect);
$pagenum = $_GET['pagenum'];
$pagesize = $_GET['pagesize'];
$filterscount=(int)$_GET['filterscount'];
$startindex=(int)$_GET['recordstartindex'];
$endindex=(int)$_GET['recordendindex'];
$rowsshow=$endindex-$startindex;

$sortdata=$_GET['sortdatafield'];
$sortorder=$_GET['sortorder'];
$order='';
if ($sortdata) {$order='ORDER BY r.'.$sortdata.' '.$sortorder;}

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
			if ($val == 'С датами') {
				$filter='MANUAL';
				$manualwhere='('.$col.' <>  "Бессрочно" AND '.$col.' NOT LIKE "Отменено%") AND ';
			}
			if ($val == 'Только действующие') {
				$filter='MANUAL';
				$manualwhere='('.$col.' NOT LIKE "Отменено%") AND ';
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

//	die ($where);
}
// DISTINCT
$query = "SELECT SQL_CALC_FOUND_ROWS r.col1, r.col2, 
DATE_FORMAT(r.col3,'%d.%m.%Y') col3, r.col4, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r $where $order LIMIT $start, $pagesize"; //$start, $pagesize
// SQL_CALC_FOUND_ROWS
//die ($query);
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
//$sql = "SELECT COUNT(*) as UniqueRecordCount FROM (SELECT DISTINCT r.col1 FROM reestr r) AS a;";
$rows = mysql_query($sql);
$rows = mysql_fetch_assoc($rows);
$total_rows = $rows['found_rows'];
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
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