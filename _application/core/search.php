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
        $reg = "/(ий|ии|а|мка|нка|яка|ла|на|оа|ра|са|та|уа|чница|шница|чица|шица|щица|йца|ша|ща|уща|б|яб|в|юв|г|ог|рг|яг|ад|ид|йд|яд|е|вие|гие|гование|дование|рование|сование|дание|жание|щание|вние|дение|жение|ление|мение|пение|рение|чение|шение|ыние|яние|рие|сие|тие|чие|ле|ме|ое|пе|ше|ще|ые|ье|ё|ж|з|яз|и|ки|ли|ти|фи|ьи|ай|ей|ий|вский|гский|сический|тический|фический|шеский|щеский|мский|нский|янский|оский|рский|сский|тский|фский|ткий|укий|який|лий|ний|пий|чий|ший|вающий|гающий|ящий|бой|вой|гой|ной|пой|уй|бый|ивый|аовый|мовый|новый|явый|дый|мый|аный|гный|дный|еный|зный|иный|мный|анный|енный|ённый|инный|онный|оный|пный|рный|сный|атный|итный|йтный|ятный|фный|чный|шный|щный|альный|ельный|ёльный|ильный|яльный|юный|яй|к|ёк|аик|мик|ник|оик|шик|щик|юик|рок|сок|як|л|м|ан|ин|йн|ян|о|во|го|ьо|п|ар|нр|ор|яр|с|т|ёт|ит|рт|ст|ят|у|ф|х|ц|ч|ш|щ|ы|бь|сь|бать|авать|звать|ивать|овать|рвать|ывать|гать|кать|лать|тать|хать|щать|еть|зть|аить|зить|лить|нить|оить|сить|тить|йть|лть|рть|асть|лсть|ость|мость|ность|пость|хость|рсть|ыть|ять|фь|щь|э|ю|я|зя|бия|сия|тия|чия|йя|ря|еся|уся|баться|ваться|гаться|щаться|еться|зться|иться|оться|яся|тя|яя)$/ui";
        if (mb_strlen($word)>3) {
            $word = preg_replace($reg, '', $word);
        }
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
        $reg = "/\s(под|много|что|для|когда|где|или|которые|который|поэтому|все|будем|как|с|и|из|на|в|по)\s/im";
//        $query = preg_replace($reg,' ',mb_strtolower($query, 'UTF-8'));
//        $query = preg_grep($reg, explode("\n", ,mb_strtolower($query, 'UTF-8')));
        $query = preg_split($reg, mb_strtolower($query, 'UTF-8'));
        $query= implode(" ",$query);
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

    public function translit($str)
  {
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya"
      );
      return strtr($str,$tr);
  }

}