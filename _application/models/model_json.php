<?php
class Model_json extends Model {
    public static $repNo;           // Report Number
    public static $export=false;    // Export Flag


    public function GetMainMIReestr() {
        self::$export=false;
        $this->connect();
        $this->setJSONHead();
        $this->GetMainMIReestrData();
//        $this->GetPagingGroupArray(self::$repNo,$where);
//        $this->GetArrayGroupReport(self::$repNo,$where);
        $this->close();
    }

    private function GetMainMIReestrData() {
        $responce=array(1=>array(id=>1, miName=>"Анестезиология и реанимация"),2=>array(id=>2, miName=>"Общебольничные и вспомогательные изделия"),3=>array(id=>3, miName=>"Реабилитация и физиотерапия"),
            4=>array(id=>4, miName=>"Лаборатория"),5=>array(id=>5, miName=>"Пластическая хирургия и косметология"),6=>array(id=>6, miName=>"Стоматология"));
        echo json_encode($responce);
        return false;


        $query="SELECT id, miName FROM mi_reestr_main";
        if ($result = $this->get_data($query)) {
            $rows = array();
            $i=0;
            while($row = $result->fetch_assoc()) {
                $responce[]=$row;
                $i++;
            }
            if (self::$export == false) {
                echo json_encode($responce);
            }
        }
    }

/** Create JSON
*/
    public function json() {
        self::$repNo=$_POST['col'];
        self::$export=false;
            if (self::$repNo) {
                $this->connect();
                $this->setJSONHead();
                $where=$this->CreateFilterString("HAVING");
                $this->GetPagingGroupArray(self::$repNo,$where);
                $this->GetArrayGroupReport(self::$repNo,$where);
                $this->close();
        }
    }

/** Create Excel
* $_GET[filename]= file name for export
*/
    public function excel() {
        self::$repNo=$_GET['col'];
        self::$export=true;
        if (self::$repNo) {
            $this->connect();
            $this->setExcelHead($_GET[filename].".xls");
            $where=$this->CreateFilterString("HAVING");
            $this->GetPagingGroupArray(self::$repNo,$where);
            $this->GetArrayGroupReport(self::$repNo,$where);
            $this->close();
        }
    }

/** Get Count Array grouping data after Filter
*/
    private function GetPagingGroupArray($col,$whereString='') {
        $query= "SELECT COUNT(*) count FROM (SELECT COUNT(*) count_col".$col." FROM reestr_distinct GROUP BY col".$col." $whereString) s1";
        $this->SetPagination($query);
    }

/** Get Data Array grouping data after Filer
*/
    private function GetArrayGroupReport($col,$whereString='') {
        if (self::$export==false) {
            $query = "SELECT col" . $col . ", COUNT(*) count_col" . $col . " FROM reestr_distinct GROUP BY col" . $col . " $whereString ORDER BY " . parent::$sidx . " " . parent::$sord . " LIMIT " . parent::$start . " , " . parent::$limit;
        } else {
            $query = "SELECT col" . $col . ", COUNT(*) count_col" . $col . " FROM reestr_distinct GROUP BY col" . $col . " $whereString ORDER BY " . parent::$sidx . " " . parent::$sord;
        }
        $responce="";
        $responce->page = parent::$page;
        $responce->total = parent::$total_pages;
        $responce->records = parent::$count;
        if ($result = $this->get_data($query)) {
            $rows = array();
            $i=0;
            while($row = $result->fetch_assoc()) {
                $responce->rows[$i]['cell']=$row;
                $i++;
            }
            if (self::$export == false) {
                echo json_encode($responce);
            } else {
                $array = (array)$responce;
                $this->createTable($_GET[colums],$array[rows]);
            }
        }
    }

/** Dynamicly Create Table for Export to Excel
* $columns - Array columns from Table
* $arraydata - Array data
* $_GET[charset] - Set convert charset
*/
    private function createTable($columns,$arraydata) {
        echo "<table border=\"1\">";
        echo "<tr>";
        foreach ($columns as $column) {
            $column=iconv('utf-8',$_GET[charset].'//TRANSLIT',$column);
            echo("<th>".$column."</th>");
        }
        echo "</tr>";
        foreach ($arraydata as $row) {
            echo "<tr>";
            foreach ($row[cell] as $cel) {
                $cel=iconv('utf-8',$_GET[charset].'//TRANSLIT',$cel);
                echo "<td align='center'>".$cel."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}