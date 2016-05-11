<?php
/**
 * Created by PhpStorm.
 * User: APerepelkin
 * Date: 21.04.2016
 * Time: 14:03
 */

class roszdravParsing extends Model{
    private static $parsURL = 'http://www.roszdravnadzor.ru/ajax/services/misearch';
   
    protected  function loadJSON ($params) {
        $url=self::$parsURL;
        $postData = '';
        foreach($params as $k => $v) {
            $postData .= $k . '='.$v.'&';
        }
        $postData = rtrim($postData, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        $output=curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($output);
        return (array)$obj;
    }

    /**
     * Function Create DB
     * Run Once
     */
    public function createDBExportRosZdrav() {
        $link = new mysqli(Host, User, Password);
        if (!$link) { die('Ошибка соединения: ' . $link->error); }
        $sql = 'CREATE DATABASE '.DB;
        if ($link->query($sql)) {
            echo "База ".DB." успешно создана<br>";
        } else {
            echo 'Ошибка при создании базы данных: <i>' . $link->error . "</i><br>";
        }
    }

    /**
     * Function Create Table Reestr
     * Run Once
     */
    public function createTableExportRosZdrav() {
        $link = new mysqli(Host, User, Password,DB);
        if (!$link) { die('Ошибка соединения: ' . $link->error); }
        $sql = "CREATE TABLE `".DB."`.`".TableReestr."` (
  col1 int(11) DEFAULT NULL COMMENT 'Уникальный номер реестровой записи',
  col2 text DEFAULT NULL COMMENT 'Регистрационный номер медицинского изделия',
  col3 date DEFAULT NULL COMMENT 'Дата государственной регистрации медицинского изделия',
  col4 text DEFAULT NULL COMMENT 'Срок действия регистрационного удостоверения',
  col5 longtext DEFAULT NULL COMMENT 'Наименование медицинского изделия',
  col6 text DEFAULT NULL COMMENT 'Наименование организации - заявителя медицинского изделия',
  col7 text DEFAULT NULL COMMENT 'Место нахождения организации-заявителя медицинского изделия',
  col8 text DEFAULT NULL COMMENT 'Юридический адрес организации-заявителя медицинского изделия',
  col9 text DEFAULT NULL COMMENT 'Наименование организации-производителя медицинского изделия или организации-изготовителя медицинского изделия',
  col10 text DEFAULT NULL COMMENT 'Место нахождения организации-производителя медицинского изделия или организации - изготовителя медицинского изделия',
  col11 text DEFAULT NULL COMMENT 'Юридический адрес организации-производителя медицинского изделия или организации - изготовителя медицинского изделия',
  col12 int(11) DEFAULT NULL COMMENT 'Код Общероссийского классификатора продукции для медицинского изделия',
  col13 int(11) DEFAULT NULL COMMENT 'Класс потенциального риска применения медицинского изделия в соответствии с номенклатурной классификацией медицинских изделий, утверждаемой Министерством здравоохранения Российской Федерации',
  col14 text DEFAULT NULL COMMENT 'Назначение медицинского изделия, установленное производителем',
  col15 int(11) DEFAULT NULL COMMENT 'Вид медицинского изделия в соответствии с номенклатурной классификацией медицинских изделий, утверждаемой Министерством здравоохранения Российской Федерации',
  col16 text DEFAULT NULL COMMENT 'Адрес места производства или изготовления медицинского изделия',
  col17 text DEFAULT NULL COMMENT 'Сведения о взаимозаменяемых медицинских изделиях'
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;";
        if ($link->query($sql)) {
            echo "Таблица ".TableReestr." успешно создана<br>";
        } else {
            echo 'Ошибка при создании таблицы: <i>' . $link->error . "</i><br>";
        }
    }

}