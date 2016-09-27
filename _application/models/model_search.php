<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 15.07.2016
 * Time: 22:04
 */

class model_fullSearch extends Search {
    public function echoTest() {
        print_r("echoTEST");
    }

    public function search() {
        header("Content-type: text/json;charset=utf-8");
        $page = $_REQUEST['page']; // get the requested page
        $oper = $_REQUEST['oper']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
        $sord = $_REQUEST['sord']; // get the direction
        $_search = $_REQUEST['_search'];// — Булево значение, если запрос с условием поиска оно принимает истинное значение;
        $filter=$this->generateAgainstString($_REQUEST['searchAll']);
        $sqlDB="";
        if(!$sidx) $sidx =1;
        $totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
        if($totalrows) {
            $limit = $totalrows;
        }
        $this->connect();
        // Get Count Records
        $countSQL="SELECT COUNT(*) count FROM ". TableReestrDistinct." WHERE MATCH (col2,col5,col9, col10) AGAINST ('$filter' IN BOOLEAN MODE)";
        $result = $this->get_data($countSQL);
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

        $query = "SELECT r.col1, r.col2,  r.col3, r.col4_state, r.col4, r.col4_data, r.col5_shot col5, r.col6, r.col7, r.col8, r.col9, r.col10, 
          r.col11, r.col12, r.col13, r.col14, r.col15,  r.col16,  r.col17 FROM ". TableReestrDistinct." r  
          WHERE MATCH (col2,col5,col9, col10) AGAINST ('$filter' IN BOOLEAN MODE) ORDER BY $sidx $sord LIMIT $start , $limit"; //$start, $pagesize

        $result = $this->get_data($query);
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
    }

    private function generateAgainstString($text) {
        $responce=$this->stopWords($text);
        $responce=explode(" ",$responce);
        $responceFinish=array();
        foreach ($responce as $value) {
            $responceFinish[]="+".$this->dropBackWords($value)."*";
        }
        $responceFinish = implode(" ",$responceFinish);
        return $responceFinish;
    }

}

class model_search extends Search {
    public static $reestrFullArray;
    public static $hideColumn;
    public static $lowColumn;
    public static $reestrSQL;
    public static $reestrSQLCount;
    public static $smartSearch;
    private static $reestArray;
    private static $filtersFlag;
    private static $searchFiltersArray;
    private static $filter;
    private static $rows;
    private static $smartSearchFlag;

    /**
     * model_search constructor. Init using column & Init static variable
     */
    function __construct() {
        self::$filter = array("eq"=>" = '@s'","ne"=>" != '@s'","le"=>" <= '@s'","lt"=>" < '@s'","ge"=>" >= '@s'","gt"=>" > '@s'"
        ,"bw"=>" LIKE '@s%'","bn"=>" NOT LIKE '@s%'","ew"=>" LIKE '%@s'","en"=>" NOT LIKE '%@s'","cn"=>" LIKE '%@s%'","nc"=>" NOT LIKE '%@s%'"
        ,"nu"=>" IS NULL","nn"=>" IS NOT NULL"); // eq = custom; rn = none!!!!
        self::$reestrFullArray=array('col1','col2','col3','col4_state','col4_data','col5','col6','col7','col8','col9','col10','col11','col12','col13','col14','col15','col16','col17');
        self::$lowColumn=array('col2','col5','col9','col10'); //array('col2','col5','col9','col10');
        self::$smartSearch=array('col2','col5','col9','col10'); //array('col2','col5','col9','col10');
        self::$hideColumn=$_REQUEST['hideColumn'];
        self::$reestArray =self::$reestrFullArray;
        $sqlColumn=implode(", ", self::$reestArray);
        foreach (self::$lowColumn as $valLowColumn) {
           $sqlColumn = str_replace($valLowColumn, "LOWER($valLowColumn) $valLowColumn", $sqlColumn);
        }
        self::$reestrSQL="SELECT $sqlColumn FROM ". TableReestrDistinct;
    }

    /**
     * Save in static variable with Request
     */
    public function initParams() {
        self::$filtersFlag = $_REQUEST['_search']; //
        self::$searchFiltersArray = $_REQUEST['filters']; //
        self::$searchFiltersArray = json_decode(self::$searchFiltersArray);
        self::$page = $_REQUEST['page'];
        self::$limit = $_REQUEST['rows'];
        self::$sidx = $_REQUEST['sidx'];
        self::$sord = $_REQUEST['sord'];
        self::$oper = $_REQUEST['oper'];
        if ($_REQUEST['searchAll']) { self::$smartSearchFlag =1;}
    }

    public function searchforExport() {
        $this->initParams();
        // $this->setHTMLHead();
        $this->setExcelHead("ГОС РЕЕСТР МИ.xls");
        $this->connect();
        $sqlWhere=$this->CreateWhere();
        $sqlQuery=self::$reestrSQL;
        if ($sqlWhere[sql]) {
            $sqlWhereTxt=" WHERE ".$sqlWhere[sql];
            $sqlQuery=$sqlQuery.$sqlWhereTxt." ORDER BY ".self::$sidx." ".self::$sord;
        } else {
            $sqlQuery=$sqlQuery." ORDER BY ".self::$sidx." ".self::$sord;
        }

        $ret=$this->CreateFindIndex($sqlQuery,$sqlWhere[smartfilter]);
        $this->GenerateJson($ret);
        $this->close();
    }
    
    public function searchAllReestr() {
        $this->initParams();
        $this->connect();
        $this->setJSONHead();
        $sqlQuery=self::$reestrSQL;
        $against=$this->generateAgainstString($_REQUEST['searchAll']);
        $colSmartSearch=implode(", ",self::$smartSearch);
//        print_r($colSmartSearch);
        $whereAgainst=$this->addMatch($colSmartSearch,$against);
        $sql = $sqlQuery.$whereAgainst;
//        die ($sql);
        $ret = $this->createDateArray($sql);
        $ret=$this->GenerateJson($ret);
//        print_r($ret);
    }

    public function searchReestr() {
        $this->initParams();
        $this->connect();
        $this->setJSONHead();
        $sqlWhere=$this->CreateWhere();
        $sqlQuery=self::$reestrSQL;
        if ($sqlWhere[sql]) {
            $sqlWhereTxt=" WHERE ".$sqlWhere[sql];
            $sqlQuery=$sqlQuery.$sqlWhereTxt." ORDER BY ".self::$sidx." ".self::$sord;
        } else {
            $sqlQuery=$sqlQuery." ORDER BY ".self::$sidx." ".self::$sord;
        }
        $ret=$this->CreateFindIndex($sqlQuery,$sqlWhere[smartfilter]);
        $this->GenerateJson($ret);
    }

    private function generateAgainstString($text) {
        $responce=$this->stopWords($text);
        $responce=explode(" ",$responce);
        $responceFinish=array();
        foreach ($responce as $value) {
            $responceFinish[]="+".$this->dropBackWords($value)."*";
        }
        return $responceFinish;
    }

    private function addMatch($colName,$arrAgainst) {
        $s1=implode(" ",$arrAgainst);
        $returnWhere=" WHERE MATCH ($colName) AGAINST ('$s1' IN BOOLEAN MODE)";
        return $returnWhere;
//        WHERE MATCH (rd.col5) AGAINST ('+упаковк* +плазменн* +стерилизац*' IN BOOLEAN MODE)
    }


    /**
     * Generate Where Request for SQL Table
     * @return array
     * SQL = SQL Text
     * smartfilter = Array for Smart Filter
     */
    private function CreateWhere() {
        $delimiter=self::$searchFiltersArray->groupOp;
        foreach (self::$searchFiltersArray->rules as $index => $rule) {
            if (array_key_exists($rule->op,  self::$filter)) {
                $val=str_replace("@s",trim($rule->data),self::$filter[$rule->op]);
                $data = explode(" ", trim($rule->data));
                if ($rule->data != '-1' && $rule->data) {
                    $filter[$rule->field] = $val;
                }
                if (in_array($rule->field, self::$smartSearch)) {
                    $val=str_replace("@s",trim($data[0]),self::$filter[$rule->op]);
                    $filter[$rule->field] = $val;
                    if (count($data)>1) {
                        $smartFilter[$rule->field] = $rule->data;
                    }
                }
            }
        }
        $txtFilter=$this->SplitFilter($filter,$delimiter);
        $txtFilter=$this->specialCase($txtFilter);
        $ret_arr=array("sql"=>$txtFilter,"smartfilter"=>$smartFilter);
        return ($ret_arr);
    }

    private function specialCase($sql) {
        $constant1 = "col4_state = 'Действующий+Бессрочно'";
        $replace1 = "(col4_state = 'Действующий' OR col4_state = 'Бессрочно')";
        if (strpos($sql, $constant1) !== false) {
            $ret = str_replace($constant1, $replace1, $sql);
        } else {
            $ret=$sql;
        }
        return $ret;
    }

    private function SplitFilter($filter,$delimiter) {
        $return="";
        foreach ($filter as $key=>$value) {
            $return .="$key$value $delimiter ";
        }
        $return=substr($return, 0, -4);
        return trim($return);
    }

    private function GenerateJson($ret) {
        if(!self::$sidx) self::$sidx =1;
        $totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
        if($totalrows) {
            self::$limit = $totalrows;
        }
        if( self::$count >0 ) {
            self::$total_pages = ceil(self::$count/self::$limit);
        } else {
            self::$total_pages = 0;
        }
        if (self::$page > self::$total_pages) self::$page=self::$total_pages;
        if (self::$limit<0) self::$limit = 0;
        self::$start = self::$limit*self::$page - self::$limit; // do not put $limit*($page - 1)
        if (self::$start<0) self::$start = 0;
        $responce = $ret; //(object) array();
        $responce->start = self::$start;
        $responce->limit = self::$limit;
        $responce->page = self::$page;
        $responce->total = self::$total_pages;
        $responce->records = self::$count;
        if (!isset(self::$oper)) {
            echo json_encode($responce);
        } else {
            $result=(array)$responce;
            $result=$result[rows];
            include ("_application/json/excelexport.php");
            // print_r($result[rows]);
        }
    }

    private function createDateArray($query) {
        $result=$this->get_data($query);
        $i=0;
        $retResponce = (object) array();
        while($row = $result->fetch_assoc()) {
            $retResponce->rows[$i]['id'] = $row[col1];
            $retResponce->rows[$i]['cell'] = ($row);
            $i++;
        }
        return $retResponce;
    }

    private function CreateFindIndex($sqlQuery,$filter,$oper=null) {
//        $filter =array('col5'=>'мед отход');
        $filter=array_values($filter);
        $filter=array_unique($filter);
        $filter = explode(" ", $filter[0]);
        $retResponce = (object) array();
        $countFilter=count($filter); //слов больше чем 1
        if ($countFilter>=1) {
            $filters = implode(" ", ($filter));
            $filters = mb_strtolower($filters, 'UTF-8');
            $filters = $this->stopWords($filters);
            $filters = explode(" ", $filters);
            foreach ($filters as $key => $value) {
                $srchDrop[$key] = $this->dropBackWords($value);
            }
            $coutn_srch = count($srchDrop);
            $srchDrop = implode("|", $srchDrop);
        }
        // $this->connect();
        $result = $this->get_data($sqlQuery);
        self::$count=0;
        $i=0;
        self::$start = self::$limit*self::$page - self::$limit; // do not put $limit*($page - 1)
        if (self::$start<0) self::$start = 0;
        while($row = $result->fetch_assoc()) {
            $flag_insert=1;
            if (self::$smartSearch && $countFilter>1 && $flag_insert==1) {
                $smartRow = array_intersect_key($row,array_flip(self::$smartSearch));
                $comma_separated = implode(" ", array_values($smartRow));
                preg_match_all("/(".$srchDrop.")/is", $comma_separated, $output_array);
                $res = array_unique($output_array[0]);
                if (count($res)>=$coutn_srch ) { //|| (self::$smartSearchFlag == 1 && count($res)>=2)
                    $flag_insert=1;
                } else {
                    $flag_insert=0;
                }
            }
            if ($flag_insert == 1) {
                if ((self::$count>=self::$start && self::$count<=self::$start+self::$limit) || self::$oper !== null) {
                    $retResponce->rows[$i]['id'] = $row[col1];
                    $retResponce->rows[$i]['cell'] = ($row);
                    $i++;
                }
                self::$count++;
            }

        }
        // $this->close();
        return $retResponce;
    }

}