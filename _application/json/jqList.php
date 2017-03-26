<?php
include "../cfg/connectConfig.php";
require_once '../core/model.php';
require_once '../core/search.php';
error_reporting(E_ERROR);

$search = new Search();

    header("Content-type: text/json;charset=utf-8");
    $page = $_REQUEST['page']; // get the requested page
    $oper = $_REQUEST['oper']; // get the requested page
    $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
    $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
    $sord = $_REQUEST['sord']; // get the direction
    $_search = $_REQUEST['_search'];// — Булево значение, если запрос с условием поиска оно принимает истинное значение;
    $type = $_POST['type'];
    if ($oper =='excel') {
        $_POST['filters']=$_GET['filters'];
        $_POST['searchField']=$_GET['searchField'];
        $_POST['searchOper']=$_GET['searchOper'];
        $_POST['searchString']=$_GET['searchString'];
        $_POST['type'] = $_GET['type'];
        $type = $_GET['type'];
        $_search='true';
        $f_name="";
        switch ($type) {
            case 'main':
                $f_name="ГОС РЕЕСТР МИ.xls";
                header("Content-type: application/vnd-ms-excel");
                header("Content-Disposition: attachment; filename=".$f_name);
                break;
            case 'refbook':
                $f_name="НОМЕНКЛАТУРНАЯ КЛАССИФИКАЦИЯ МИ ПО ВИДАМ.xls";
//                header("Content-type: text/html;charset=utf-8");
                header("Content-type: application/vnd-ms-excel");
                header("Content-Disposition: attachment; filename=".$f_name);
                break;
        }
    }
    $searchString ="";
    if($_search == 'true'){
        if (isset($_POST['filters'])) $filters = $_POST['filters'];// Фильтры для поиска
        if (isset($_POST['searchField'])) $searchField = $_POST['searchField']; // Фильтр по одному полю (имя)
        if (isset($_POST['searchOper'])) $searchOper = $_POST['searchOper']; // Фильтр по одному полю (операция)
        if (isset($_POST['searchString'])) $searchString = $_POST['searchString']; // Фильтр по одному полю (значение)
        $searchString = generateSearchString($filters, $searchField, $searchOper, $searchString);
        if ($searchString != '') {
            $searchString = "WHERE ".$searchString." ";
            $pos = strpos($searchString, "(`` = '')");
            if ($pos) {
                $searchString = "";
            }
        }
    }
    if(!$sidx) $sidx =1;
    $totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
    if($totalrows) {
        $limit = $totalrows;
    }
    $mysqli = new mysqli(Host, User, Password, DB);
    if (mysqli_connect_errno()) { echo "Подключение невозможно: ".mysqli_connect_error(); }
    $mysqli->set_charset("utf8");
    switch ($type) {
        case 'main' :
            $query= "SELECT COUNT(*) AS count FROM reestr_distinct ".$searchString;
//            die($query);
            break;
        case 'refbook' :
            $searchString=str_replace("`Section`","CONCAT(mrs.col2_section,'. ', ms.SectionName)",$searchString);
            $searchString=str_replace("`SubSection`","CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName)",$searchString);
            $query="SELECT COUNT(*) count";
            $query .=" FROM mi_reestr_section mrs";
            $query .=" INNER JOIN mi_section ms  ON mrs.col2_section=ms.id AND ms.ParentId=0 INNER JOIN mi_section ms1";
            $query .=" ON mrs.col2_section=ms1.ParentId AND mrs.col2_subsection= ms1.id ".$searchString;
//            die($query);
            break;
        default :
            echo "Необходимо передать параметр TYPE";
            die();
    }
    if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if( $count >0 ) {
        $total_pages = ceil($count/$limit);
    } else {
        $total_pages = 0;
    }
    if ($page > $total_pages) $page=$total_pages;
    if ($limit<0) $limit = 0;
    $start = $limit*$page - $limit; // do not put $limit*($page - 1)
    if ($start<0) $start = 0;
    switch ($type) {
        case 'main' :
            $query = "SELECT r.col1, r.col2,  r.col3, r.col4_state, r.col4, r.col4_data, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
          r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r ".$searchString." ORDER BY $sidx $sord LIMIT $start , $limit"; //$start, $pagesize
//            die ($query);
            break;
        case 'refbook' :
            $searchString=str_replace("`Section`","CONCAT(mrs.col2_section,'. ', ms.SectionName)",$searchString);
            $searchString=str_replace("`SubSection`","CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName)",$searchString);
            $query="SELECT col1,col2,mrs.col2_section, CONCAT(mrs.col2_section,'. ', ms.SectionName) Section, mrs.col2_subsection,";
            $query .=" CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName) SubSection, mrs.col3, '' col4  FROM mi_reestr_section mrs"; // mrs.col4
            $query .=" INNER JOIN mi_section ms  ON mrs.col2_section=ms.id AND ms.ParentId=0 INNER JOIN mi_section ms1";
            $query .=" ON mrs.col2_section=ms1.ParentId AND mrs.col2_subsection= ms1.id ".$searchString.""; // ORDER BY $sidx $sord LIMIT $start , $limit
//             die ($query);
            break;
        default :
            echo "Необходимо передать параметр TYPE";
            die();
    }

    if ($oper == 'excel') {
        switch ($type) {
            case 'main' :
                $query = "SELECT r.col1, r.col2,  r.col3, r.col4_state, r.col4, r.col4_data, r.col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
                r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM reestr_distinct r ".$searchString." ORDER BY $sidx $sord"; //$start, $pagesize
                if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
                include ('excelexport.php');
                return false;
                break;
            case 'refbook' :
               $query ="SELECT DISTINCT col1, col2, col3, col4 FROM mi_reestr ".$searchString." ORDER BY $sidx $sord";
               $searchString=str_replace("`Section`","CONCAT(mrs.col2_section,'. ', ms.SectionName)",$searchString);
               $searchString=str_replace("`SubSection`","CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName)",$searchString);

                $query = "SELECT col1,col2,mrs.col2_section, CONCAT(mrs.col2_section,'. ', ms.SectionName) Section, mrs.col2_subsection,";
                $query .= " CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName) SubSection, mrs.col3, mrs.col4,mrs.col3_first_word,mrs.col3_soundex, mrs.col3_metaphone  FROM mi_reestr_section mrs";
                $query .= " INNER JOIN mi_section ms  ON mrs.col2_section=ms.id AND ms.ParentId=0 INNER JOIN mi_section ms1";
                $query .= " ON mrs.col2_section=ms1.ParentId AND mrs.col2_subsection= ms1.id ".$searchString." AND mrs.col3_first_word is not NULL ORDER BY $sidx $sord";
                if (!$result =$mysqli->query($query)) {
                    echo "Error GET record: " . $mysqli->error."<br>";
                    die ('Oooops');
                };
                include ('excelrefbook.php');
                return false;
                break;
            default :
                echo "Необходимо передать параметр TYPE";
                die();
        }
    }

    if (!$result =$mysqli->query($query)) { echo "Error GET record: " . $mysqli->error."<br>"; };
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $rows = array();
    $i=0;
    while($row = $result->fetch_assoc()) {
        $responce->rows[$i]['id']=$row[col1];
        $responce->rows[$i]['cell']=($row);
        $i++;
    }
    echo json_encode($responce);

    function generateSearchString($filters, $searchField, $searchOper, $searchString){
        $where = '';
        if($filters){
            $filters = json_decode($filters);
            $where .= generateSearchStringFromObj($filters);
        }
        return $where;
    }

function generationStringFromLikeBeetweenText($col,$val,$type) {
    $ret_val="";
    switch ($col) {
        case 'col5':
            if ($type == 'main') {
                $arr_val = explode(" ", $val);
                $ret_val = implode("%", $arr_val);
            }
            break;
        case 'col7':
        case 'col8':
        case 'col10':
        case 'col11':
            if ($type == 'main') {
                $arr_val = explode(" ", $val);
                $ret_val=GetSynonyms($arr_val);
//                $ret_val=$arr_val;
                // print_r($arr_val);
                // print_r($ret_val);
                // die();
            }
            break;
        case 'col3':
        case 'col4':
            if ($type == 'refbook') {
                $arr_val = explode(" ", $val);
                $ret_val = implode("%", $arr_val);
            }
            break;
        default:
            $ret_val=$val;
            break;
    }
//    die("123");
    return $ret_val;
}


function GetSynonyms($stringArray) {
    $where='';
    foreach ($stringArray as $key=>$string) {
        $delimeter=' OR ';
        if (count($stringArray)-1 == $key) {$delimeter='';}
        $where .=' CONCAT(original," ",synonyms) LIKE "%'.$string.'%"'.$delimeter;
    }
//    print_r($key);
//        print_r($where);
//    $ret_val = implode("%", $stringArray);
    $query ="SELECT CONCAT(original,',',synonyms) words  FROM synonyms WHERE $where";
//print_r($query);
}

function generateSearchStringFromObj($filters){
    $where = '';
    // Генерация условий группы фильтров
    if(count($filters)) foreach($filters->rules as $index => $rule){
        if ($rule->data == '-1') {
            unset ($filters->rules [$index]);
        }
        if ($rule->data != '-1') {
            // SELECT COUNT(*) AS count FROM reestr_distinct WHERE (`col6` = 'ЗАО \'Вектор-Бест\'') 
            $rule->data = addslashes($rule->data);
            $where .= "`" . preg_replace('/-|\'|\"/', '', $rule->field) . "`";
            $where = str_replace("\'","\"", $where);
            switch ($rule->op) { // В будущем будет больше вариантов для всех вохможных условий jqGrid
                case 'eq':
                    if ($rule->data == 'Действующий+Бессрочно' && $rule->field =='col4_state') {
                        $where = str_replace("`col4_state`","(`col4_state`", $where);
                        $where .= "='Действующий' OR ".$rule->field. " = 'Бессрочно')";
                    }
                    else {
                        $where_data = str_replace("\'","\"", $rule->data);
                        $where .= " = '" . $where_data . "'";
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
//                    $ruleCustomData=generationStringFromLikeBeetweenText($rule->field,$rule->data,$_POST['type']);
                    $field=$rule->field;
                    $rule=GetcCombinationsWords($rule->data,$rule->field);
                    $ruleCustomData = $rule[word];
                    $iscombination=$rule[combination];
                    if ($iscombination) {
                        $searchsStr=('`'.$field.'`');
                        $where = str_replace($searchsStr,'',$where);
                    }
                    $where .= $ruleCustomData;
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

function GetcCombinationsWords($arrayString,$col) {
    $retString = array();
    switch ($col) {
        case 'col5':
            $data = explode(' ', $arrayString);
            $countIter = pow(count($data), count($data))-1;
            for ($i = 0; $i<=$countIter; $i++) {
                $curMask = base_convert($i, 10, count($data));
                while (strlen($curMask)<count($data)) {
                    $curMask = '0'.$curMask;
                }
                $newWorld = '%';
                $FLAG = false;
                for ($j = 0; $j < strlen($curMask); $j++) {
                    if (strpos($newWorld, $data[$curMask[$j]]) !== FALSE) {
                        $FLAG = true;
                    }
                    $newWorld .= $data[$curMask[$j]] . '%';
                }
                if ((strlen($newWorld) < count($data)) or ($FLAG)) {
                    continue;
                };
            $retString[word] .= '( `' . $col . '` LIKE \'' . $newWorld . '\') OR ';
            }
            $retString[combination]=1;
            $retString[word] ='('.$retString[word].')';
            $retString[word] =trim(substr_replace($retString[word] , '', -5, -1));
            break;
        default:
            $retString[combination]=0;
            $retString[word]=generationStringFromLikeBeetweenText($col,$arrayString,$_POST['type']);
            $retString[word]=" LIKE '%" .$retString[word]. "%'";
            break;
    }
    return $retString;
}




/* Сопоставление номенклатуры и МИ
 *
 * SELECT mrs.col1 codMI, mrs.col2 RazdelMI, mrs.col3 NameMI, rd.col1 URNZ, rd.col5 FillNameMI, rd.col15 VidMINomen FROM reestr_distinct rd
  INNER JOIN mi_reestr_section mrs
  ON rd.col5 LIKE CONCAT('%',mrs.col3,'%')
  LIMIT 10


INSERT INTO temp_mi ( codMI,RazdelMI,NameMI,URNZ,FillNameMI,VidMINomen)
SELECT mrs.col1 codMI, mrs.col2 RazdelMI, mrs.col3 NameMI, rd.col1 URNZ, rd.col5 FillNameMI, rd.col15 VidMINomen FROM reestr_distinct rd
  INNER JOIN mi_reestr_section mrs
  ON rd.col5 LIKE CONCAT('%',mrs.col3,'%')
  LIMIT 10


SELECT * FROM (SELECT rd.col1,rd.col5, rd.col15 FROM reestr_distinct rd
  WHERE rd.col4_state='Бессрочно'
  ORDER BY rd.col5
  LIMIT 100,200) a1
  INNER JOIN mi_reestr_section mrs
  ON a1.col5 LIKE CONCAT('%',mrs.col3,'%')



CREATE TABLE synonyms (
  id int(11) NOT NULL AUTO_INCREMENT,
  original varchar(255) DEFAULT NULL COMMENT 'Оригинал слова',
  synonyms text DEFAULT NULL COMMENT 'Синонимы, через запятую',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Синонимы';

 **/