<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 15.07.2016
 * Time: 21:38
 */
Class Search extends Model {
    /**
     * Убираем окончания в словах
     * @param $word одно слово
     * @return mixed одно слово
     */
    public function dropBackWords($word) {
        $reg = "/(ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|он|ют|ат)$/ui";
        $word = preg_replace($reg,'',$word);
        return $word;
    }

    /**
     * Убираем спец символы
     * @param $word одно слово
     * @return mixed одно слово
     */
    public function dropSpecSymbol($word) {
        $reg = "/(,|;|-|\"|\.|$|#|№|\(|\)|ТУ|\*|\+|[0-9]|мм|шт|тел)/";
        $word = preg_replace($reg,'',$word);
        return $word;
    }

    /**
     * Убираем СТОП-СЛОВА, которые не имеют значения в поиске
     * @param $query строка
     * @return mixed строка
     */
    public function stopWords($query) {
        $reg = "/\s(под|много|что|когда|где|или|которые|поэтому|для|все|будем|как|с|и|из|-|на|в|по)\s/im";
        $query = preg_replace($reg,' ',mb_strtolower($query, 'UTF-8'));
        return $query;
    }

    public function GetKeysFindValues($txtSearch,$outputKey) {
        $txtSearch = mb_strtolower($txtSearch, 'UTF-8');
        $txtSearch = $this->stopWords($txtSearch);
        $txtSearch = explode(' ', $txtSearch);

        foreach ($txtSearch as $key => $value) {
            $txtSearchArray[$key] = $this->dropBackWords($value);
        }
        $coutn_srch=count($txtSearchArray);
        $txtSearchArray=implode("|", $txtSearchArray);
        print_r($txtSearchArray);
        echo " = ";
        print_r($coutn_srch);
        echo "<hr>";
        die();

        $this->connect();
        $sql = "SELECT col1, col2, col3, col4, LOWER(col5) col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, col4_data, col4_state 
        FROM reestr_distinct"; // where  col1 in ('9714','9715','9994','9995')
        $sql = "SELECT LOWER(col5) col5 FROM reestr_distinct"; // where  col1 in ('9714','9715','9994','9995')
        $result = $this->get_data($sql);

        while($row = $result->fetch_assoc()) {
            $comma_separated = $row[col5]; // implode(" ", array_values($row));
            preg_match_all("/(".$srchDrop.")/is", $comma_separated, $output_array);
            $res = array_unique($output_array[0]);
            if (count($res)>=$coutn_srch) {
                print_r($row);
                //     print_r(count($res));
                echo "<hr>";
            }
        }
        $this->close();
    }

}