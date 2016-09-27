<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 04.07.2016
 * Time: 21:16
 */
class model_dataupdate extends Search {

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


    /** Get Section MI and Split on Section from table "mi_reestr"
     * and Insert distinct record section in table "mi_section"
     */
    public function createSectionMI() {
        $sql="SELECT DISTINCT mr.col2 FROM mi_reestr mr ORDER by mr.col2";
        $result = $this->get_data($sql);
        $Arr=array();
        $SubArr=array();
        while($row = $result->fetch_assoc()) {
            preg_match_all("/((([0-9]{1,4})(.*\D))|(([0-9]{1,2}.[0-9]{1,4})(.*\D)))/", $row[col2], $output_array);
            $text=explode(".", $output_array[0][0]);
            preg_match("/([0-9]{1,2}).([0-9]{1,2}). (.*\D)/", $output_array[0][1], $output_array_include);
            $SubArr[]=array('Code'=>$output_array_include[1],'subCode'=>$output_array_include[2],'subName'=>$output_array_include[3]);
            $Arr[$text[0]] = trim($text[1]);
        }
        $sql="DELETE FROM mi_section";
        $result = $this->get_data($sql);
        foreach ($Arr as $key=>$value) {
            $sql="INSERT INTO mi_section (id,SectionName) VALUES ($key,'$value')";
            $result = $this->get_data($sql);
        }
        foreach ($SubArr as $CurSubArr) {
            if ($CurSubArr[subCode]) {
                $sql="INSERT INTO mi_section (id,SectionName,ParentId) VALUES ($CurSubArr[subCode],'$CurSubArr[subName]',$CurSubArr[Code])";
                $result = $this->get_data($sql);
            }
        }
    }
    
    public function MIreestrDistinct() {
        print_r("Clear Tabel<br>");
        $sql="DELETE FROM mi_reestr_section";
        $result = $this->get_data($sql);
        print_r("Insert Tabel....<br>");
        $sql = "INSERT INTO mi_reestr_section (col1 ,col2 ,col3 ,col4,col2_section,col2_subsection ) 
SELECT DISTINCT col1, SUBSTRING_INDEX(col2, '.', -1), col3, col4,SUBSTRING_INDEX(col2, '.', 1),SUBSTRING_INDEX(SUBSTRING_INDEX(col2, '.', -2),'.',1) FROM mi_reestr";
        $result = $this->get_data($sql);
        print_r("Done....");
    } 
    
    



    public function TestMyAlgoritm() {
        $string = 'Системы нейрохирургические PS Medical имплантируемые шунтирующие- Contoured (клапан, катетер вентрикулярный, катетер кардио-перитонеальный); 
- Burr Hole (клапан, катетер вентрикулярный, катетер кардио-перитонеальный); 
- Delta (клапан, катетер вентрикулярный, катетер кардио-перитонеальный); 
- Ultra Small (клапан, катетер вентрикулярный, катетер кардио-перитонеальный); 
- люмбо-перитонеальная (катетер люмбо-перитонеальный, коннектор, игла Туоки, фиксатор).';

        print_r($this->PartOfSpeechRUS($string));
    }

    public function GetMISimilar() {
        $query1="SELECT rd.col1 URNZ, rd.col2 RegNoMI, rd.col5 MI, rd.col15 NomenklCod FROM reestr_distinct rd WHERE rd.col15>1 ORDER BY URNZ LIMIT 10";
        $arr1=array();
        $result = $this->get_data($query1);
        while($row = $result->fetch_assoc()) {
            array_push($arr1, $row);
        }

        $query2="SELECT id, col1, col3 FROM mi_reestr_section LIMIT 1000";
        $arr2=array();
        $result2 = $this->get_data($query2);
        while($row2 = $result2->fetch_assoc()) {
            array_push($arr2, $row2);
        }
//         print_r($arr1);
//         echo "<hr>";
//         print_r($arr2);

$phonetic = Phonetic::app()->run();

// // Process string with a Beider-Morse algorithm and retrieve BM phonetic keys
// $p = $phonetic->BMSoundex->getPhoneticKeys('привет МИр! май!');

// // Try to guess string's language
// $l = $phonetic->BMSoundex->getPossibleLanguages('Отдыхай Вася');

// // Retrieve all supported languages
// $g = $phonetic->BMSoundex->getLanguages();

// // Process string with a Beider-Morse algorithm and after that with Daitch-Mokotoff Soundex
// $b = $phonetic->BMSoundex->getNumericKeys('ОПА НА');

// print_r($p);
// echo "<br>";
// print_r($l);
// echo "<br>";
// print_r($g);
// echo "<br>";
// print_r($b);
// echo "<br>";echo "<br>";
// $p = $phonetic->BMSoundex->getPhoneticKeys('This is Спарта!');
// $n = $phonetic->BMSoundex->getNumericKeys('This is Спарта!');
// print_r($p);echo "<br>";
// print_r($n);
// echo "<br>";echo "<br>";
// print_r($phonetic->BMSoundex->getNumericKeys('привет МИр!'));echo "<br>";
// print_r($phonetic->BMSoundex->getNumericKeys('пирвет МИр!'));echo "<br>";
// print_r($phonetic->BMSoundex->getNumericKeys('прювет Мурка!'));

// $arr1=array('Этот мир такой большой'); //,'Маша ела кашу','Что сегодня почитать'
// $arr2=array('Это очень большой мир','красная плесень','огромный мир','большого мира много','читать книгу','Большой мир, большие возможности','этот мир очень','большой мир','как прекрасен этот мир','мир такой');

// $array1 = array("a" => "green", "red", "blue");
// $array2 = array("b" => "green", "yellow", "red");
// $result = array_intersect($array1, $array2);
// print_r($result);

//echo "123";
        $table="<table border=1><tr><th>УРНЗ</th><th>МИ</th><th>Номенклатура</th><th>Код Номенклатуры из заявки</th></tr>";
        foreach ($arr1 as $key => $value) {
//            print_r($value);
//            print_r($phonetic->BMSoundex->getNumericKeys($value[MI]));
            $m1=$this->MergeArray($phonetic->BMSoundex->getNumericKeys($value[MI]));
            foreach ($arr2 as $key2 => $value2) {
//                print_r($value2[col3]);
                $m2=$this->MergeArray($phonetic->BMSoundex->getNumericKeys($value2[col3]));

// print_r($m1);
// echo "<br>";
// print_r($m2);

                 $result = $result = array_intersect($m1, $m2);
                 if (count($result)>=10) {
//                     print_r($result);
//                     echo "<br>";echo "<br>";
//                     print_r($value[MI]);
//                     echo "<br>";
//                     print_r($value2[col3]);
                     $table .="<tr>";
                     $table .="<td>".$value[URNZ]."</td>";
                     $table .="<td>".$value[MI]."</td>";
                     $table .="<td>".$value2[col3]."</td>";
                     $table .="<td>".$value[NomenklCod]."</td>";
                     $table .="</tr>";

                 }
//                 print_r($result);
//                 echo "<br>";
            }
//            echo "<hr>";
        }
        $table .="</table>";
        echo $table;

 die();



        //
        // foreach ($arr1 as $key => $value) {
        //     foreach ($arr2 as $key2 => $value2) {
        //         $var = levenshtein($value[MI], $value2[col3]);

        //         $p1 = $phonetic->BMSoundex->getNumericKeys($value[MI]);
        //         $p2 = $phonetic->BMSoundex->getNumericKeys($value2[col3]);

        //         print_r($p1);
        //         echo "<br>";echo "<br>";
        //         print_r($p2);
        //         echo "<hr>";
        //         // print_r($var);
        //         // echo "<br>";
        //         // var_dump(metaphone($value[MI], 15));
        //         // echo " - ";
        //         // var_dump(metaphone($value2[col3], 15));
        //         // echo "<br>";

        //         $var1 = similar_text($value[MI], $value2[col3], $tmp);
        //         // if ($tmp >=65) {
        //         if ($var >0 && $var<90) {
        //             $table .="<tr>";
        //             $table .="<td>".$value[URNZ]."</td>";
        //             $table .="<td>".$value[MI]."</td>";
        //             $table .="<td>".$value2[col3]."</td>";
        //             $table .="<td>".$value[NomenklCod]."</td>";
        //             $table .="</tr>";
        //             // print_r($tmp);
        //             // echo "<br>";
        //         }
        //     }
        // }
        // $table .="</table>";
        // echo $table;
}

public function MergeArray($Myarray) {
    $result2=array();
    foreach ($Myarray as $key => $value) {
        foreach ($value as $key0 => $value0) {
            array_push($result2, $value0);
        }
    }
    return($result2);
}


    public function miReestrTransalte() {
        $sql="UPDATE mi_reestr_section SET col3_translate = NULL,col3_soundex = NULL,col3_metaphone = NULL,col3_first_word = NULL";
        $this->get_data($sql);
        $arr=array();
            $sql="SELECT id, col1, col2, col3, col4 FROM mi_reestr_section WHERE col3_soundex IS NULL OR col3_soundex =''"; // LIMIT 10000
        print_r($sql);
        echo "<hr>";
        $result = $this->get_data($sql);
        while($row = $result->fetch_assoc()) {
            $id=$row[id];
            preg_match_all("/(.*)(,|для)/Usi", $row[col3], $col3a);
            if ($col3a[0]) {
                $col3a=$col3a[1][0];
            } else {
                $col3a=$row[col3];
            }
            $col3a=addslashes($col3a);
            $trans=$this->translit($col3a);
            echo "<hr>";
            $firstWord=$col3a;
            $metaphone=metaphone($trans);
            $sound=soundex($trans);
            $trans=addslashes($trans);
            // ^(\S+)\s+(\S+) - взять 2 слова с предложения
            $arr[]=array($row[col1]=>array($row[col3],$trans,$sound));
            $sqlupdate="UPDATE mi_reestr_section SET col3_translate = '$trans',col3_soundex = '$sound', col3_metaphone='$metaphone',col3_first_word='$firstWord' WHERE id = $id;";
            $this->get_data($sqlupdate);
            print_r($sqlupdate);
            echo "<br>";
        }
        return 0;
    }

}

class model_search extends Model {
    /** которая будет отрезать у слов запроса окончания для повышения уровня релевантности:
     * @param $word
     * @return mixed
     */
    public function dropBackWords($word) { //тут мы обрабатываем одно слово
        $reg = "/(ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|он|ют|ат)$/ui"; //данная регулярная функция будет искать совпадения окончаний
        $word = preg_replace($reg,'',$word); //убиваем окончания
        return $word;
    }

    public function dropSpecSymbol($word) { //тут мы обрабатываем одно слово
        $reg = "/(,|;|-|\"|\.|$|#|№|\(|\)|ТУ|\*|\+|[0-9]|мм|шт|тел)/"; //данная регулярная функция будет искать совпадения окончаний
        $word = preg_replace($reg,'',$word); //убиваем окончания
        return $word;
    }

    /**  стоп-слов, которые нам не нужны в поиске и которые встречаются сплошь и рядом в любом тексте
     * @param $query
     * @return mixed
     */
    public function stopWords($query) { //тут мы обрабатываем весь поисковый запрос
        $reg = "/\s(под|много|что|когда|где|или|которые|поэтому|для|все|будем|как|с|и|из|-|на|в|по)\s/im"; //данная регулярка отрежет все стоп-слова отбитые пробелами
        $query = preg_replace($reg,' ',mb_strtolower($query, 'UTF-8')); //убиваем стоп-слова
        return $query;
    }

    public function explodeQuery($query) { 	//функция вызова поисковой строки
        $query = $this->stopWords($query); 	//используем написанную нами ранее функцию для удаления стоп-слов
        $words = explode(" ",$query); 	//разбиваем поисковый запрос на слова через пробел и заносим все слова в массив
        $i = 0; 						//устанавливаем начало массива в 0, помним что нумерация в массивах начинается с 0
        $keywords = ""; 				//создаем пустой массив
        foreach ($words as $word) { 	//в цикле для массива words создаем элемент word
            $word = trim($word);
            if (strlen($word)<6) {		//если слово короче 6 символов то убиваем его
                unset($word);
            }
            else {						//иначе выполняем следующее
                if (strlen($word)>8) {
                    $keywords[$i]=$this->dropBackWords($word);	//наша функция чистки окончаний для слов длинее 8 символов и занесение их в созданный нами массив
                    $i++;								//наращиваем значение i для того чтобы перейти к следующему элементу
                }
                else {
                    $keywords[$i]=$word; 				//если короче 8 символов то просто добавляем в массив
                    $i++;
                }
            }
        }
        return $keywords; //возвращаем полученный массив
    }

    public function searchWord($word) {
        if(isset($word)) {
            $query = trim($word); //делаем небольшую чистку, можете добавить еще защиту от различных инъекций и подозрительных переменных, которые могут ввести вам вредные пользователи
            $keywords = $this->explodeQuery($query); //тут наша функция с первой части урока
            $this->connect();
            $sql = "SELECT col1, col5 FROM reestr_distinct";
            $result = $this->get_data($sql);
            while($row = $result->fetch_assoc()) {
                $materials[$row[col1]] = $row;
//                array_push($arr1, $row);
            }
//            print_r($keywords);
            $this->close();
            echo $this->searchResult($materials,$keywords); //выводим наш результат поиска, функцию мы рассмотрим ниже
        }
    }

    public function searchResult($materials, $keywords) {
        foreach ($materials as $material) { //Выше мы сформировали массив $materials который мы теперь выводим разбивая на элементы массива $material
            $title = htmlspecialchars(strip_tags($material[col1]));	//Тут мы чистим все значения массива - title, text и keywords от тегов и посторонних символов
            $text = htmlspecialchars(strip_tags($material[col5]));		//как вариант можно еще все слова перевести в нижний регистр
            $key = htmlspecialchars(strip_tags($material[col5]));
            $wordWeight =0; //вес слова запроса приравниваем к 0
            foreach ($keywords as $word) { 	//теперь для каждого поискового слова из запроса ищем совпадения в тексте
                $reg = "/(".$word.")/"; 	//маска поиска для регулярной функции
                /*
                    Автоматически наращиваем вес слова для каждого элемента массива.
                    Так же сюда можно включить например поле description если оно у вас есть.
                    Оставляем переменную $out, которая выводит значение поиска. Она нам может и не пригодится, но пусть будет, может быть вы найдете ей применение.
                */
                $wordWeight = preg_match_all($reg, $title, $out);	//как вариант можно еще для слов в заголовке вес увеличивать в два раза
                $wordWeight += preg_match_all($reg, $text, $out);	//но это вам понадобиться если вы будете выводить материалы в порядке убывания по релевантности
                $wordWeight += preg_match_all($reg, $key, $out);	//мы же пока этого делать не будем
                $material[relevation] += $wordWeight; //увеличиваем вес всего материала на вес поискового слова

                //раскрашиваем найденные слова функцией, которую мы писали в первой части урока
                $title = $this->colorSearchWord($word, $title, "violet");
                $text = $this->colorSearchWord($word, $text, "violet");
                $key = $this->colorSearchWord($word, $key, "violet"); //незнаю зачем ключевые слова окрасил, их ведь не обязательно выводить пользователю :)
            }
            //Теперь ищем те материалы, у которых временный атрибут relevation не равен 0
            if($material[relevation]!=0) {
                //Возвращаем массивы в нормальное состояние с уже обработанными данными
                $material[title] = $title;
                $material[text] = $text;
                $material[keywords] = $key;

//                print_r($material);

                echo $this->simpleToTemplate($material, "result_search"); //новая функция, которая вернет нам шаблон с результатами поиска
            }
            //Иначе просто грохаем весь элемент material за ненадобностью
            else {
                unset($material);
            }
        }
    }

    private function colorSearchWord($word, $string, $color) {
        $replacement = "<span style='color:".$color."; border-bottom:1px dashed ".$color.";'>".$word."</span>";
        $result = str_replace($word, $replacement, $string);
        return $result;
    }

    private function simpleToTemplate($value, $template) {
        ob_start(); // Включаем буферизацию вывода, чтобы шаблон не вывелся в месте вызова функции
        // Подключаем необходимый нам шаблон, который просто ждет наш массив
        include('/'.$template.'.tpl');
        return ob_get_clean(); //Возвращаем результат буфера и очищаем его
    }


    public function setKeyWords() {
        $this->connect();
        $sql = "SELECT col1, col5 FROM reestr_distinct LIMIT 10000";
        $result = $this->get_data($sql);
        while($row = $result->fetch_assoc()) {
            $keyw=$this->count_words($this->stopWords($this->dropSpecSymbol($row[col5])));
            $keyw = array_diff($keyw, array('1'));
            if (count($keyw)>0) {
                echo '<pre>';
                print_R($keyw);
                echo '</pre>';
            }
        }

        $this->close();
    }

    public function count_words($A) {
        $A = array_count_values(explode(' ',$A));
        unset($A['']);
        array_multisort($A, SORT_DESC);
        return $A;
    }


}

class test_search extends model_search {
    public function GetTestSearch($srch) {
        $srch = mb_strtolower($srch, 'UTF-8');
        $srch = $this->stopWords($srch);
        $srch = explode(' ', $srch);
        foreach ($srch as $key => $value) {
            $srchDrop[$key] = $this->dropBackWords($value);
        }
        $coutn_srch=count($srchDrop);
        $srchDrop=implode("|", $srchDrop);
        print_r($srchDrop);
        echo " = ";
        print_r($coutn_srch);
        echo "<hr>";
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

    public function GetcCombinationsWords($arrayString,$col) {
        $arrayString=$this->dropBackWords($arrayString);
        $arrayString=$this->stopWords($arrayString);
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
            case 'array':
                $data = explode(' ', $arrayString);
                $retString=$data;
                break;
            default:
                $retString[combination]=0;
                $retString[word]=$arrayString;
                $retString[word]=" LIKE '%" .$retString[word]. "%'";
                break;
        }
        return $retString;
    }


}