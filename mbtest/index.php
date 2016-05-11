<?php
/**
 * Created by PhpStorm.
 * User: Анатолий
 * Date: 25.04.2016
 * Time: 21:50
 */
include "../_application/cfg/connectConfig.php";
include "../_application/core/model.php";
class TestBlock1 extends  Model {
    function __construct() {
        $this->connect();
    }

    function GetFullRecord() {
        echo "Выводит количество ВСЕХ записей: <br>";
        $sql="SELECT COUNT(*) as ResCount FROM reestr";
        $result =parent::$mysqliPublic->query($sql, MYSQLI_USE_RESULT);//  parent::$mysqliPublic
        while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
        }
        echo "ВСЕГО записей в БАЗЕ: ".($myArray[0][ResCount]);
    }

    function  GetRecordLastDay ($mydate = null) {
        $date = new DateTime($mydate);
        $lastday=$date->modify('-1 day');
        $txt=sprintf("Выводит количество записей за период: с %s по %s",$lastday->format('Y-m-d'),$lastday->format('Y-m-d'));
        echo $txt."<br>";
        $sql="SELECT COUNT(*) as ResCount FROM reestr WHERE col3='".$lastday->format('Y-m-d')."'";
        $result =parent::$mysqliPublic->query($sql, MYSQLI_USE_RESULT);//  parent::$mysqliPublic
        while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
        }
        echo sprintf("ВСЕГО записей за период с %s по %s в БАЗЕ: %d",$lastday->format('Y-m-d'),$lastday->format('Y-m-d'),($myArray[0][ResCount]));
    }

    function  GetRecordFiveDay ($mydate = null) {
        $date = new DateTime($mydate);
        $today = $date->format('Y-m-d');
        $lastday=$date->modify('-5 day')->format('Y-m-d');
        $txt=sprintf("Выводит количество записей за период: с %s по %s",$lastday,$today);
        echo $txt."<br>";
        $sql="SELECT COUNT(*) as ResCount FROM reestr WHERE col3 BETWEEN '".$lastday."' AND '".$today."'";
//        echo $sql;
        $result =parent::$mysqliPublic->query($sql, MYSQLI_USE_RESULT);//  parent::$mysqliPublic
        while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
        }
        echo sprintf("ВСЕГО записей за период с %s по %s в БАЗЕ: %d",$lastday,$today,($myArray[0][ResCount]));
    }
}
echo '<h1>Тестирование БЛОКА №1</h1>';
echo "<a href='?All=true'> ПОКАЗАТЬ ВСЕ ЗАПИСИ </a>";
echo ("<br>");
echo "<a href='?Last=true'> ПОКАЗАТЬ ЗАПИСИ ЗА ВЧЕРА</a>";
echo ("<br>");
echo "<a href='?Five=true'> ПОКАЗАТЬ ЗАПИСИ ЗА 5 ДНЕЙ</a>";
//echo ("<br>");
//echo "<a href='#'> ПОКАЗАТЬ ЗАПИСИ ЗА ВЧЕРА</a>";
$t1= new TestBlock1();
echo ("<br>");
echo ("<br>");
if ($_GET['All']) $t1->GetFullRecord();
if ($_GET['Last']) $t1->GetRecordLastDay();
if ($_GET['Five']) $t1->GetRecordFiveDay();
