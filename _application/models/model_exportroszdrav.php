<?php
include "model_export.php";
class model_exportroszdrav extends roszdravParsing {

	public function changeParseURL($url) {
		parent::$parsURL=$url;
	}

	/** Set shot MI values in SQL table.
	 * Read full MI values in SQL table, when
	 * update shot MI values in other col table
	 */
	public function setShotMIValues() {
		$sql="SELECT col1, col5, col5_shot FROM reestr_distinct WHERE col5_shot IS null or col5_shot =''";
		$result = $this->get_data($sql);
		while ($row = $result->fetch_assoc()) {
			$resShot=addslashes($this->get10words($row[col5]));
			$id=$row[col1];
			$sqlU="UPDATE reestr_distinct SET col5_shot = '$resShot' WHERE col1='$id'";
			$this->get_data($sqlU);
		}
	}

	/** Cuts offer to 10 words
	 * $txt - offer
	 * $len - len cut (10 default)
	 */
	private function get10words($txt,$len=10) {
		$txt = str_ireplace(array("\r","\n",'\r','\n'),' ', $txt);
		$arr_str = explode(" ", $txt);
		$arr = array_slice($arr_str, 0, $len);
		$new_str = implode(" ", $arr);
		if (count($arr_str) > $len) {
			$new_str .= ' ......';
		}
		return $new_str;
	}
	
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
	 * $type - Type Export (main='reestr'; mi='nomenclature')
	 */
	public function insertToTable($json,$type='main') {
		$this->connect();
		foreach ($json[data] as $key=>$value) {
			switch ($type) {
				case 'main':
						$res=$this->getValuefromArray($value,$type);
						$this->get_data_real($this->insertToSQL($res,TableReestr));
					break;
				case 'mi':
					$res=$this->getValuefromArray($value,$type);
					$this->get_data_real($this->insertToSQL($res,TableMi));
					break;
				default :
					$res='None Data';
					break;
			}
		}
		$this->close();
	}

	/** Parse Single data
	 * @param $Myarr - input OBJClass Array; $type - Type Export (main='reestr'; mi='nomenclature')
	 * @return array key=>value. Value dynamic value ('title' or 'value') 
	 * (if 'title' is not found, then value='label')
	 */
	private function getValuefromArray($Myarr,$type) {
		$ret_array=array();
		foreach ((array)$Myarr as $key=>$value) {
			$arrVal=(array)$value;
			if ($arrVal[title]) {
				$ret_val=$arrVal[title];
			} else {
				$ret_val=$arrVal[label];
				if ($key =='col3' && $type =='main') {$ret_val=date("Y-m-d", strtotime($ret_val));}
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
	private  function  insertToSQL($arr_return,$tablename) {
		if  ($type =='main') {
			$arr_return[col12] = str_replace(' ', '', $arr_return[col12]);
		}
		$columns = implode(", ",array_keys($arr_return));
		$values = implode(", ", $arr_return);
		$sql = "INSERT INTO `".$tablename."`($columns, data_record) SELECT $values, CURRENT_TIMESTAMP";
		return $sql;
	}

	/** Delete duplicates records in table 
	 *  Draft Procedure
	 */ 	
	public function deleteDuplicate() {
		$sql = "DELETE FROM reestr_distinct";
		$this->query_data($sql);
		$sql = "INSERT INTO reestr_distinct SELECT DISTINCT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, now(),NULL,'Бессрочно',NULL FROM reestr";
		$this->query_data($sql);
		return 0;
	}

	/** Update Col4 in Main Reestr
	*/
	public  function updatecol4_data() {
		$sql="select rd.col1,rd.col4 from reestr_distinct rd where rd.col4 <> 'Бессрочно' ORDER BY rd.col1 desc"; // limit 40
		$result = $this->get_data($sql);
		$col4="";
		$col4_state="";
		$col1="";
		$stmt = parent::$mysqliPublic->stmt_init();
		if (!self::$mysqliPublic->set_charset("utf8")) {printf("Ошибка при загрузке набора символов utf8: %s\n", self::$mysqliPublic->error);}
		if (!($stmt = parent::$mysqliPublic->prepare("UPDATE `" . TableReestrDistinct. "` SET col4_data= (?),col4_state=(?) WHERE col1=(?)"))) {
			echo "Не удалось подготовить запрос: (" . parent::$mysqliPublic->errno . ") " . parent::$mysqliPublic->error;
		}
		if (!$stmt->bind_param("sss", $col4, $col4_state, $col1)) {
			echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($row = $result->fetch_assoc()) {
			$cur_date=$this->StringToDate($row[col4]);
			$col1=$row[col1];
			$col4=$cur_date;

			$find='Отменено';
			$find_pos = strpos($row[col4], $find);
			$col4_state='Отменено';
			if ($find_pos === false) {
				$cur_date_now=date("Y-m-d");
				if ($cur_date <=$cur_date_now) { 
					$col4_state='Срок действия истек';
				} else {
					$col4_state='Действующий';
				}
			}
			if ($cur_date != 'NULL') {
				$stmt->execute();
				echo(sprintf("%s -> %s = '%s'", $row[col1], $row[col4], $col4));
				echo "<br>";
			}
		}
	}

	private  function  StringToDate($str) {
		$str=preg_replace("/(.*)(\d{2}\.\d{2}\.\d{4})/", "$2", $str);
		$ret_val=date("Y-m-d", strtotime($str));
		if ($ret_val == '1970-01-01') {$ret_val='NULL';}
		return $ret_val;
	}
}
?>