<?php
class Model
{
	public static $mysqliPublic;
	public static $start;
	public static $page; // get the requested page
	public static $oper; // get the requested page
	public static $limit; // get how many rows we want to have into the grid
	public static $sidx; // get index row - i.e. user click to sort
	public static $sord; // get the direction
	public static $_search;// — Булево значение, если запрос с условием поиска оно принимает истинное значение;
	public static $count;
	public static $total_pages;
	/*
		Модель обычно включает методы выборки данных, это могут быть:
			> методы нативных библиотек pgsql или mysql;
			> методы библиотек, реализующих абстракицю данных. Например, методы библиотеки PEAR MDB2;
			> методы ORM;
			> методы для работы с NoSQL;
			> и др.
	*/
	public function connect() {
		$mysqli = new mysqli(Host, User, Password, DB);
		if (mysqli_connect_errno()) { echo "Подключение невозможно: ".mysqli_connect_error(); }
		self::$mysqliPublic=$mysqli;
		return self::$mysqliPublic;
	}

	public function close() {
		self::$mysqliPublic->close();	
   	}

  	public function getSelfSQLI() {
	  return self::$mysqliPublic;
  	}
	
	public function testEcho($msg) {
		print_r($msg);
	}

	public function setJSONHead() {
		set_time_limit(0);
//		ini_set('memory_limit', '200M');
		header("Content-type: text/json;charset=utf-8");
		$this->SetParam();
	}

	public function setHTMLHead() {
		set_time_limit(0);
//		ini_set('memory_limit', '200M');
		header("Content-type: text/html;charset=utf-8");
		$this->SetParam();
	}

	public function setExcelHead($fileExportName) {
	// header('Content-Encoding: windows-1251');
	header('Content-Encoding: utf-8');
	// header("Content-type: application/vnd-ms-excel;charset=windows-1251");
	header("Content-type: application/vnd-ms-excel;charset=utf-8");
	header("Content-Disposition: attachment; filename=$fileExportName");
	$this->SetParam();
	}

	private function SetParam() {
		self::$page = $_REQUEST['page']; // get the requested page
		self::$oper = $_REQUEST['oper']; // get the requested page
		self::$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
		self::$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
		self::$sord = $_REQUEST['sord']; // get the direction
		self::$_search = $_REQUEST['_search'];// — Булево значение, если запрос с условием поиска оно принимает истинное значение;
	}

	public function SetPagination($sql) {
		if(!self::$sidx) self::$sidx =1;
		$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
		if($totalrows) {
			self::$limit = $totalrows;
		}
		if (!$sql) {die("Необходимо передать параметр SQL");}
		if (!$result =self::$mysqliPublic->query($sql)) { echo "Error GET record: " . self::$mysqliPublic->error."<br>"; };
		$row = $result->fetch_assoc();
		self::$count = $row['count'];
		if( self::$count >0 ) {
			self::$total_pages = ceil(self::$count/self::$limit);
		} else {
			self::$total_pages = 0;
		}
		if (self::$page > self::$total_pages) self::$page=self::$total_pages;
		if (self::$limit<0) self::$limit = 0;
		self::$start = self::$limit*self::$page - self::$limit; // do not put $limit*($page - 1)
		if (self::$start<0) self::$start = 0;
	}

	public function CreateFilterString($operand ="WHERE") {
		$searchString ="";
		if(self::$_search == 'true'){
			if (isset($_POST['filters'])) $filters = $_POST['filters'];// Фильтры для поиска
			if (isset($_POST['searchField'])) $searchField = $_POST['searchField']; // Фильтр по одному полю (имя)
			if (isset($_POST['searchOper'])) $searchOper = $_POST['searchOper']; // Фильтр по одному полю (операция)
			if (isset($_POST['searchString'])) $searchString = $_POST['searchString']; // Фильтр по одному полю (значение)
			if (isset($_GET['filters'])) $filters = $_GET['filters'];// Фильтры для поиска
			if (isset($_GET['searchField'])) $searchField = $_GET['searchField']; // Фильтр по одному полю (имя)
			if (isset($_GET['searchOper'])) $searchOper = $_GET['searchOper']; // Фильтр по одному полю (операция)
			if (isset($_GET['searchString'])) $searchString = $_GET['searchString']; // Фильтр по одному полю (значение)

			$searchString = $this->generateSearchString($filters, $searchField, $searchOper, $searchString);
			if ($searchString != '') $searchString = $operand." ".$searchString." ";
		}
		return $searchString;
	}

	private function generateSearchString($filters, $searchField, $searchOper, $searchString){
		$where = '';
		if($filters){
			$filters = json_decode($filters);
			$where .= $this->generateSearchStringFromObj($filters);
		}
		return $where;
	}

	private function generationStringFromLikeBeetweenText($col,$val) {
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

	private function generateSearchStringFromObj($filters){
		$where = '';
		// Генерация условий группы фильтров
		if(count($filters)) foreach($filters->rules as $index => $rule){
			if ($rule->data == '-1') {
				unset ($filters->rules [$index]);
			}
			if ($rule->data != '-1') {
				$rule->data = addslashes($rule->data);
				$where .= "`" . preg_replace('/-|\'|\"/', '', $rule->field) . "`";
				switch ($rule->op) { // В будущем будет больше вариантов для всех вохможных условий jqGrid
					case 'eq':
						if ($rule->data == 'Действующий+Бессрочно' && $rule->field =='col4_state') {
							$where = str_replace("`col4_state`","(`col4_state`", $where);
							$where .= "='Действующий' OR ".$rule->field. " = 'Бессрочно')";
						}
						else {
							$where .= " = '" . $rule->data . "'";
						}
						break;
					case 'rn':
                    	$daterange=(explode(" - ",$rule->data));
                    	$where = str_replace("`".$rule->field."`","(`".$rule->field."`", $where);
                    	$where .= " >= '" . $daterange[0] . "' AND ". "`" . preg_replace('/-|\'|\"/', '', $rule->field) . "`"." <= '".$daterange[1]. "')";
                    	break;
					case 'ne':
						$where .= " != '" . $rule->data . "'";
						break;
					case 'le':
						$where .= " <= '" . $rule->data . "'";
						break;
					case 'lt':
						$where .= " < '" . $rule->data . "'";
						break;
					case 'ge':
						$where .= " >= '" . $rule->data . "'";
						break;
					case 'gt':
						$where .= " > '" . $rule->data . "'";
						break;
					case 'bw':
						$where .= " LIKE '" . $rule->data . "%'";
						break;
					case 'bn':
						$where .= " NOT LIKE '" . $rule->data . "%'";
						break;
					case 'ew':
						$where .= " LIKE '%" . $rule->data . "'";
						break;
					case 'en':
						$where .= " NOT LIKE '%" . $rule->data . "'";
						break;
					case 'cn':
						$ruleCustomData=$this->generationStringFromLikeBeetweenText($rule->field,$rule->data);
//                    $where .= " LIKE '%" . $rule->data . "%'";
						$where .= " LIKE '%" . $ruleCustomData . "%'";
						break;
					case 'nc':
						$where .= " NOT LIKE '%" . $rule->data . "%'";
						break;
					case 'nu':
						$where .= " IS NULL";
						break;
					case 'nn':
						$where .= " IS NOT NULL";
						break;
					case 'in':
						$where .= " IN ('" . str_replace(",", "','", $rule->data) . "')";
						break;
					case 'ni':
						$where .= " NOT IN ('" . str_replace(",", "','", $rule->data) . "')";
						break;
				}
				// Добавить логику соединения, если это не последние условие
				if (count($filters->rules) != ($index + 1))
					$where .= " " . addslashes($filters->groupOp) . " ";
			}
		}
		$rest = substr($where, -4);
		if (trim($rest) == 'AND') { $where =  substr($where, 0, -4); }

		// Генерация условий подгруппы фильтров
		$isSubGroup = false;
		if(isset($filters->groups))
			foreach($filters->groups as $groupFilters){
				$groupWhere = self::generateSearchStringFromObj($groupFilters);
				// Если подгруппа фильтров содержит условия, то добавить их
				if($groupWhere){
					// Добавить логику соединения, если условия подгруппы фильтров добавляются после условий фильтров этой группы
					// или после условий других подгрупп фильтров
					if(count($filters->rules) or $isSubGroup) $where .= " ".addslashes($filters->groupOp)." ";
					$where .= $groupWhere;
					$isSubGroup = true; // Флаг, определяющий, что было хоть одно условие подгрупп фильтров
				}
			}

		if($where)
			return '('.$where.')';

		return ''; // Условий нет
	}
	
	// метод вставки\обновления данных
	public function query_data($sql=null) {
		if (!self::$mysqliPublic->set_charset("utf8")) {printf("Ошибка при загрузке набора символов utf8: %s\n", self::$mysqliPublic->error);}
 		if (!$result =self::$mysqliPublic->multi_query($sql)) { echo "Error updating record: " . self::$mysqliPublic->error."<br>"; };
 		return $result;
	}

	// метод выборки данных
	public function get_data($sql=null,$param=null) {
		self::$mysqliPublic->set_charset("utf8");
 		if (!$result =self::$mysqliPublic->query($sql,$param)) { echo "Error GET record: " . self::$mysqliPublic->error."<br>"; };
 		return $result;
	}
	
	public  function nobuffquery ($sql) {
		self::$mysqliPublic->use_result($sql);
		$result = self::$mysqliPublic->use_result();
		return $result;
	}  

	// метод выборки данных
	public function get_data_real($sql=null) {
		if (!self::$mysqliPublic->set_charset("utf8")) {
			printf("Ошибка при загрузке набора символов utf8: %s\n", self::$mysqliPublic->error);
		}
		if (!$result =self::$mysqliPublic->real_query($sql)) { echo "Error GET record: " . self::$mysqliPublic->error."<br>"; };
 	return $result;
	}

	/** Определение части речи слова на PHP одной функцией
	 * @param $string 1. прилагательные; 2. причастие; 3. глагол; 4. существительное; 5. наречие; 6. числительное; 7. союз; 8. предлог
	 * @return array
     */
	public function PartOfSpeechRUS($string) {

		// Окончания, через |, сами пропишете (нужно писать также, как и сейчасб т.е. ой|а)
		// ВНИМАНИЕ!!! ЕСЛИ ВЫ РАБОТАЕТЕ С КОДИРОВКОЙ UTF-8, ТО ПЕРЕД "i" ПОСТАВЬТЕ "u"
		$completions = "/ой|а$/ui";

		// Строка, в которой заменяем
		$string = "Это строка, в которой нужно обрезать у слов окончания, которые указаны в массиве окончаний";
		echo $string;
		// Удаляем окончания
		$string = preg_replace($completions, "", $string);
echo "<br>";
		echo $string;

		die();

		echo "
        Группы окончаний:
        1. прилагательные; 2. причастие; 3. глагол; 4. существительное; 5. наречие; 6. числительное; 7. союз; 8. предлог
       ";
		echo "<hr>";
print_r($string);
		echo "<hr>";
		$groups = array(
			1 => array ('ее','ие','ые','ое','ими','ыми','ей','ий','ый','ой','ем','им','ым','ом',
				'его','ого','ему','ому','их','ых','ую','юю','ая','яя','ою','ею'),
			2 => array ('ивш','ывш','ующ','ем','нн','вш','ющ','ущи','ющи','ящий','щих','щие','ляя'),
			3 => array ('ила','ыла','ена','ейте','уйте','ите','или','ыли','ей','уй','ил','ыл','им','ым','ен',
				'ило','ыло','ено','ят','ует','уют','ит','ыт','ены','ить','ыть','ишь','ую','ю','ла','на','ете','йте',
				'ли','й','л','ем','н','ло','ет','ют','ны','ть','ешь','нно'),
			4 => array ('а','ев','ов','ье','иями','ями','ами','еи','ии','и','ией','ей','ой','ий','й','иям','ям','ием','ем',
				'ам','ом','о','у','ах','иях','ях','ы','ь','ию','ью','ю','ия','ья','я','ок', 'мва', 'яна', 'ровать','ег','ги','га','сть','сти'),
			5 => array ('чно', 'еко', 'соко', 'боко', 'роко', 'имо', 'мно', 'жно', 'жко','ело','тно','льно','здо','зко','шо','хо','но'),
			6 => array ('чуть','много','мало','еро','вое','рое','еро','сти','одной','двух','рех','еми','яти','ьми','ати',
				'дного','сто','ста','тысяча','тысячи','две','три','одна','умя','тью','мя','тью','мью','тью','одним'),
			7 => array ('более','менее','очень','крайне','скоре','некотор','кажд','други','котор','когд','однак',
				'если','чтоб','хот','смотря','как','также','так','зато','что','или','потом','эт','тог','тоже','словно',
				'ежели','кабы','коли','ничем','чем'),
			8 => array ('в','на','по','из')
		);

		$res=array();
		$string=mb_strtolower($string);
		$words=explode(' ',$string);
			print_r($words);
		foreach ($words as $wk=>$w){
			$len_w=mb_strlen($w);
			foreach ($groups as $gk=>$g){
				foreach ($g as $part){
					$len_part=mb_strlen($part);
					if (
						mb_substr($w,-$len_part)==$part && $res[$wk][$gk]<$len_part //любая часть речи, окончания
						|| mb_strpos($w,$part)>=(round(2*$len_w)/5) && $gk==2 //причастие, от 40% и правее от длины слова
						|| mb_substr($w,0,$len_part)==$part && $res[$wk][$gk]<$len_part && $gk==7 //союз, сначала слОва
						|| $w==$part //полное совпадение
					) {
							echo $w.':'.$part."(".$gk.")<br>";
						if ($w!=$part) $res[$wk][$gk]=mb_strlen($part); else $res[$wk][$gk]=99;
					}

				}
			}
			if (!isset($res[$wk][$gk])) $res[$wk][$gk]=0;
		}
		$result=array();
		foreach($res as $r) {
			arsort($r);
			array_push($result,key($r));
		}
		return $result;
	}

}