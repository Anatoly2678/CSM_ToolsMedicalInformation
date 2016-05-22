<?php
include "model_export.php";
class model_exportroszdrav extends roszdravParsing {

	/** Get Date from URL POST Request (constant 'sitename' in function).
	 * @param $params - POST parametrs from Array
	 * @return $return - json data
	 */
	public function loadExportRoszdrav($params) {
		$result=$this->loadJSON($params);
	 	return $result;
	}

	/** Parse Request from URL and Add\Update in SQL Table
	 * @param $json - Data array
	 */
	public function insertToTable($json) {
		$this->connect();
		foreach ($json[data] as $key=>$value) {
			$res=$this->getValuefromArray($value);
			$this->get_data_real($this->insertToSQL($res));
			print_r($res);
			echo "<hr>";
		}
		$this->close();
	}

	/** Parse Single data
	 * @param $Myarr - input OBJClass Array
	 * @return array key=>value. Value dynamic value ('title' or 'value') 
	 * (if 'title' is not found, then value='label')
	 */
	private function getValuefromArray($Myarr) {
		$ret_array=array();
		foreach ((array)$Myarr as $key=>$value) {
			$arrVal=(array)$value;
			if ($arrVal[title]) {
				$ret_val=$arrVal[title];
			} else {
				$ret_val=$arrVal[label];
				if ($key =='col3') {$ret_val=date("Y-m-d", strtotime($ret_val));}
			}
			$ret_array[$key] = "'".addslashes($ret_val)."'";
		}
		unset($ret_array[DT_RowId]);
		unset($ret_array[col18]);
		ksort($ret_array);
		return($ret_array);
	}

	/** Insert to SQL
	 * @param $arr_return
	 */
	private  function  insertToSQL($arr_return) {
		$arr_return[col12]=str_replace(' ', '', $arr_return[col12]);
		$columns = implode(", ",array_keys($arr_return));
		$values = implode(", ", $arr_return);
		$sql = "INSERT INTO `".TableReestr."`($columns, data_record) SELECT $values, CURRENT_TIMESTAMP";
//		die($sql);
		return $sql;
	}

	/** Delete duplicates records in table 
	 *  Draft Procedure
	 */ 	
	public function deleteDuplicate() {
		$sql = "DELETE FROM reestr_distinct";
		$this->query_data($sql);
		$sql = "INSERT INTO reestr_distinct SELECT DISTINCT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, now(),NULL FROM reestr";
		$this->query_data($sql);
		return 0;
	}

	public  function updatecol4_data() {
//		$this->connect();
		 $sql="select rd.col1,rd.col4 from reestr_distinct rd where rd.col4 <> 'Бессрочно' ORDER BY rd.col1"; // limit 40

//		print_r($sql);
//		die ();
		$result = $this->get_data($sql);
//		$rows = $result->fetch_array();
		$col4="";
		$col1="";
		$stmt = parent::$mysqliPublic->stmt_init();
		if (!($stmt = parent::$mysqliPublic->prepare("UPDATE `" . TableReestrDistinct. "` SET col4_data= (?) WHERE col1=(?)"))) {
			echo "Не удалось подготовить запрос: (" . parent::$mysqliPublic->errno . ") " . parent::$mysqliPublic->error;
		}
		if (!$stmt->bind_param("ss", $col4, $col1)) {
			echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($row = $result->fetch_array()) {
			$cur_data=$this->StringToDate($row[col4]);
			$col1=$row[col1];
			$col4=$cur_data;
			if ($cur_data != 'NULL') {
				$stmt->execute();
				echo(sprintf("%s -> %s = '%s'", $row[col1], $row[col4], $col4));
				echo "<br>";
			}
		}
//		$this->close();
	}

	private  function  StringToDate($str) {
		$str=preg_replace("/(.*)(\d{2}\.\d{2}\.\d{4})/", "$2", $str);
//		print_r($str);
		$ret_val=date("Y-m-d", strtotime($str));
		if ($ret_val == '1970-01-01') {$ret_val='NULL';}
//		echo ($ret_val);
		return $ret_val;
	}
}
?>