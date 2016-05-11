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
		/*
		 * 	-- Создание копии таблицы без дубликатов во временной таблице
			CREATE TEMPORARY TABLE bad_temp AS SELECT DISTINCT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17 FROM reestr;
			-- Удаление всех записей из старой таблицы
			DELETE FROM reestr;
			-- Добавление записей без дублей
			INSERT INTO reestr SELECT *, NOW() FROM bad_temp;
			-- Удаление временной таблицы
			DROP TABLE bad_temp;
		 */
		$sql = "DELETE FROM reestr_distinct";
		$this->query_data($sql);
		$sql = "INSERT INTO reestr_distinct SELECT DISTINCT col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, now() FROM reestr";
		$this->query_data($sql);
		return 0;
	}
}
?>