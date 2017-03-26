<?php
error_reporting(E_ALL | E_STRICT);
//print_r (dirname(__FILE__).'/../phpmorphy/src/common.php');
require_once(dirname(__FILE__).'/../phpmorphy/src/common.php'); // dirname(__FILE__) 
//die();
//require_once('docs/_application/phpmorphy/src/common.php'); // dirname(__FILE__) 

class Model_refbook extends Model {
    
    public function getUniqueWords() {
        $this->connect();
        $query="SELECT id,col2U,col3U,col4U FROM mi_resstr_unique_words LIMIT 20";
        echo ($query);
        $result=$this->get_data($query);
        $i=0;
            while($row = $result->fetch_assoc()) {
                var_dump($row);
                $responce->rows[$i]['id']=$row[id];
                $responce->rows[$i]['cell']=($row);
                $i++;
            }
        $this->close();
        echo ($responce);

    }

    public function setUniqueWordsSectionName() {
        $this->connect();
        $query="SELECT id, SectionName, ParentId FROM mi_section ORDER by ParentId, id";
        $result=$this->get_data($query);
        $resArray=array();
        $t="<table border=1>";
        $t .="<tr>";
        $t .="<td align='center'>№ раздела</td>";
        $t .="<td align='center'>Раздел\подраздел</td>";
        $t .="<td align='center'>Слова\повторения</td>";
        $t .="</tr>";
        while($row = $result->fetch_assoc()) {
            $SectionName=$this->countTheNumberOfIdenticalWords($row[SectionName]);
            $t .="<tr>";
            if ($row[ParentId] == "0") {
                $s1=$row[id];
            } else {
                $s1=$row[ParentId].".".$row[id];
            }
            $t .="<td>$s1</td>";
            $t .="<td>$row[SectionName]</td>";
            $m0="";
            foreach ($SectionName[all] as $key=>$value) {
                $m0 .= $key."($value) <br>";
            }
            $m1=$m0;
            $t .="<td>$m1</td>";
            $t .="</tr>";
            $resArray=$resArray+$SectionName[all];
        }
        $myResArray=(array_keys($resArray)); //array_count_values
        $finalArray=array();
        foreach ($myResArray as $key => $value) {
            $finalArray[]=$this->dropBackWords($value);
        }
        $finalArray=array_count_values($finalArray);
        array_multisort($finalArray, SORT_DESC); 
        $t .="</table>";
        echo ($t);
        $this->close();
    }

    public function setUniqueWords() {
        $this->connect();
        $sql="DELETE FROM mi_resstr_unique_words";
        $result = $this->get_data($sql);

        $query="SELECT id,  col2,  col3, col4 FROM mi_reestr_section LIMIT 5";
        $result=$this->get_data($query);
        while($row = $result->fetch_assoc()) {
            $colID=$row[id];
            $col2U=$this->countTheNumberOfIdenticalWords($row[col2]);
            $col3U=$this->countTheNumberOfIdenticalWords($row[col3]);
            $col4U=$this->countTheNumberOfIdenticalWords($row[col4]);

            $sql="INSERT INTO mi_resstr_unique_words (id,col2U,col3U,col4U,col2UCount,col3UCount,col4UCount) VALUES ($colID,'$col2U[words]','$col3U[words]','$col4U[words]','$col2U[count]','$col3U[count]','$col4U[count]')";
            $this->get_data($sql);
        }
        $this->close();
    }

    private function countTheNumberOfIdenticalWords ($str) {
        $str=str_replace("/", " ", $str);
        $str=str_replace("(", "", $str);
        $str=str_replace(")", "", $str);
        $str=str_replace(",", "", $str);
        $str=str_replace(".", "", $str);
        $str=str_replace("'", "", $str);
        $str=str_replace("\"", "", $str);
        $str=$this->stopWords($str);
        $s1=explode(' ',$str);
        $str="";
        foreach ($s1 as $value) {
            $str .=" ".$this->dropBackWords($value);
        }

        $str = array_count_values(explode(' ',$str)); 
        unset($str['']); 
        array_multisort($str, SORT_DESC); 
        // $ret="";
        // print_r(array_values($str));
        $countStr= implode(";", array_values($str));
        $strWords= implode(";", array_keys($str));
        $arr=array('words'=>$strWords,'count'=>$countStr,'all'=>$str);
        // $str=json_encode($str);
        return $arr;
    }

    private function stopWords($query) {
        $reg = "/\s(под|много|что|когда|где|или|которые|поэтому|для|все|будем|как|с|и|из|-|на|в|по)\s/im";
        $query = preg_replace($reg,' ',mb_strtolower($query, 'UTF-8'));
        return $query;
    }

    private function dropBackWords($word) { //тут мы обрабатываем одно слово
        $reg = "/(ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|он|ют|ат|ющие|ы|ие|ы|ия|их)$/ui"; //данная регулярная функция будет искать совпадения окончаний
        $word = preg_replace($reg,'',$word); //убиваем окончания
        return $word;
    }

    /** Делаем анализ только по осноным разделам номенклатуры
    *
    */

    public function getMainSectionNomenclature () {
        $this->connect();
        $query="SELECT id, SectionName, ParentId FROM mi_section  ORDER by id"; //WHERE ParentId<>0
        $result=$this->get_data($query);
        $table = $this->initTable();
        $allSquare=array();
        $stringSquare="";
        while($row = $result->fetch_assoc()) {
            $curOffer=$row['SectionName'];
            $curOfferStopWords=$this->stopWords($curOffer);
            $curArr=explode(" ",$curOfferStopWords);

            array_push($allSquare, $curArr);

//            $square=$this->getSquareWord($curOfferStopWords);
//            $stringSquare .= implode(";", $square);
//            $stringSquare .= ";";
//            $squareStr=$this->arrToStr($square);
//            $squareCount=$this->arrCountValue($square);
//            $squareCountStr=$this->arrToStr($squareCount,1);
//            $table .= $this->rowTable($curOffer,$squareStr,$squareCountStr);
        }
//        print_r($allSquare);
//        echo "<hr>";
        $query="DELETE FROM dtable";
        $this->query_data($query);
        $arrOut=$this->multiArrToSingleArr($allSquare);
        print_r($arrOut);
        echo "<hr>";
        $r1=new ruSoundex();
        foreach ($arrOut as $key=>$value) {
//            $ofWords = mb_strtoupper($value, 'UTF-8');
            $r2=$r1->str2url($value);
            $r2s=soundex($r2);
//            $r2s=metaphone($r2,5);
            $query="INSERT INTO dtable (rus_name,trans_name,soundex_name ) VALUES('$value' ,'$r2','$r2s');";
            $this->query_data($query);
//            print_r($value." - ". $r2." - ". soundex($r2));
//            echo "<br>";
        }

//        $stringSquare=explode(";", $stringSquare);
//        $countSquareInSection = $this->arrCountValue($stringSquare);
//        $table .="<tr>";
//        $table .="<td colspan=3>";
//        $table .=$this->arrToStr($countSquareInSection,1);
//        $table .="</td></tr>";
        $table .="</table>";
        echo ($table);
        $this->close();

    }

    private function multiArrToSingleArr($arr) {
        $arrOut = array();
        foreach($arr as $subArr){
            $arrOut = array_merge($arrOut,$subArr);
        }
        return $arrOut;
    }

    private function arrToStr($arr,$keyTrue=0) {
        $m0="";
        foreach ($arr as $key=>$value) {
            if ($keyTrue == 0) {
                $m0 .= "$value <br>";
            } else {
            $m0 .= $key." ($value) <br>";
            }
        }
        return $m0;
    }

    private function initTable() {
        $t="<table border=1>";
        $t .="<tr>";
        $t .="<td align='center'>Раздел</td>";
        $t .="<td align='center'>Корни</td>";
        $t .="<td align='center'>Слова\повторения</td>";
        $t .="</tr>";
        return $t;
    }

    private function rowTable($par1,$par2,$par3) {
        $trow ="<tr>";
        $trow .="<td>$par1</td>";
        $trow .="<td>$par2</td>";
        $trow .="<td>$par3</td>";
        $trow .="</tr>";
        return $trow;
    }

    private function arrCountValue($str,$delimeter=' ') {
        $str=implode(" ", $str);
        $str = array_count_values(explode(" ",$str)); 
        unset($str['']); 
        array_multisort($str, SORT_DESC); 
        return $str;
    }

    public function getSquareWord($offerOfWords) {
        $ofWords = mb_strtoupper($offerOfWords, 'UTF-8');
        $wordsArray=explode(" ", strtoupper($ofWords));
        $opts = array('storage' => PHPMORPHY_STORAGE_FILE, 'predict_by_suffix' => true, 'predict_by_db' => true, 'graminfo_as_text' => true,);
        $dir = dirname(__FILE__).'/../phpmorphy/dicts';
        $lang = 'ru_RU';
        try {
            $morphy = new phpMorphy($dir, $lang, $opts);
        } catch(phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
        }
        $words = $wordsArray; //array('АНЕСТЕЗИОЛОГИЧЕСКИЕ', 'И', 'РЕСПИРАТОРНЫЕ', 'МЕДИЦИНСКИЕ', 'ИЗДЕЛИЯ');
        // $words = array('АНЕСТЕЗИОЛОГИЧЕСКИЕ', 'И', 'РЕСПИРАТОРНЫЕ', 'МЕДИЦИНСКИЕ', 'ИЗДЕЛИЯ', 'ЛОГОС', 'ПАКЛЯ', 'РЕНТГЕН', 'РЕНТГЕНОВЫЙ');
        if(function_exists('iconv')) {
            foreach($words as &$word) {
                $word = iconv('utf-8', $morphy->getEncoding(), $word);
            }
            unset($word);
        }
        $retArr=array();

        try {
            foreach($words as $word) {
                $pseudo_root = $morphy->getPseudoRoot($word);
                $retArr[]=$pseudo_root[0];
            }
            // $retArr=array_flip ($retArr);
            // unset($retArr['']);
            // $retArr = array_diff($retArr, array(''));
            return $retArr;
        } catch(phpMorphy_Exception $e) {
            die('Error occured while text processing: ' . $e->getMessage());
        }

        // print_r($words);
    }

    public function get_data()
    {
        $dth= $this->connectPDO();
        $sql="SELECT mrs.col2_section, mrs.col1, CONCAT(mrs.col2_section,'. ', ms.SectionName) Section, CONCAT(mrs.col2_section,'.',mrs.col2_subsection,'. ',ms1.SectionName) SubSection, 
          mrs.col3, mrs.col4  
          FROM mi_reestr_section mrs 
          INNER JOIN mi_section ms  ON mrs.col2_section=ms.id AND ms.ParentId=0 
          INNER JOIN mi_section ms1 ON mrs.col2_section=ms1.ParentId AND mrs.col2_subsection= ms1.id"; // LIMIT 10
        $result=$this->getAllRecinArray($sql);
        $dth=NULL;
        return $result;
    }
}

class Lingua_Stem_Ru
{
    var $VERSION = "0.02";
    var $Stem_Caching = 0;
    var $Stem_Cache = array ();
    var $VOWEL = '/аеиоуыэюя/';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';
    var $REFLEXIVE = '/(с[яь])$/';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
    var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
    var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
    var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';

    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace ($re, $to, $s);
        return $orig !== $s;
    }

    function m($s, $re)
    {
        return preg_match($re, $s);
    }

    function stem_word($word)
    {
        print_r("START");
        $word = strtolower($word);
        $word = strtr($word, 'ё', 'е');
        # Check against cache of stemmed words
        if ($this->Stem_Caching && isset ($this->Stem_Cache[$word])) {
        return $this->Stem_Cache[$word];
    }
        $stem = $word;
        do {
            if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # Step 1
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');

              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }

          # Step 2
          $this->s($RV, '/и$/', '');

          # Step 3
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/', '');

          # Step 4
          if (!$this->s($RV, '/ь$/', '')) {
              $this->s($RV, '/ейше?/', '');
              $this->s($RV, '/нн$/', 'н');
          }

          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }

    function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }

    function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }
}

class ruSoundex {
    public function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }

    private function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }



    public function transliterate($st) {
        $st = strtr($st,
            "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
            "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
        );
        $st = strtr($st, array(
            'ё'=>"yo",    'х'=>"h",  'ц'=>"ts",  'ч'=>"ch", 'ш'=>"sh",
            'щ'=>"shch",  'ъ'=>'',   'ь'=>'',    'ю'=>"yu", 'я'=>"ya",
            'Ё'=>"Yo",    'Х'=>"H",  'Ц'=>"Ts",  'Ч'=>"Ch", 'Ш'=>"Sh",
            'Щ'=>"Shch",  'Ъ'=>'',   'Ь'=>'',    'Ю'=>"Yu", 'Я'=>"Ya",
        ));
        return $st;
    }
//echo transliterate("У попа была собака, он ее любил.");



    public function ru_soundex($source)
    {
        $res = '';

        $literal = array();
// ассоциативный массив букв
// параметры звуков гласный / согласный

// для гласных переход буквы в звук(и), редуцированный/нет, предполагаемые правила ударения исходя из кол-ва слогов (stressed syllable)
// реализована проверка предполагаемого ударения

// для согласных переход букв[ы] в звук(и), редуцируемый/нет, правила редуцирования

// vowel
        $literal['А'] = array('status'=>'гласный','sound'=>'а','stressed'=>'а'); // никогда не меняется
        $literal['Е'] = array('status'=>'гласный','sound'=>'и','stressed'=>'э', 'АаЕеЁёИиОоУуЭэЮюЯяЬьЫыЪъ' => 'йэ'); // - особые правила, для этой буквы, стоящей после указанных, а также в начале слов
        $literal['Ё'] = array('status'=>'гласный','sound'=>'о','stressed'=>'о', 'АаЕеЁёИиОоУуЭэЮюЯяЬьЫыЪъ' => 'йо');
        $literal['И'] = array('status'=>'гласный','sound'=>'и','stressed'=>'и');
        $literal['О'] = array('status'=>'гласный','sound'=>'а','stressed'=>'о');
        $literal['У'] = array('status'=>'гласный','sound'=>'у','stressed'=>'у');
        $literal['Ы'] = array('status'=>'гласный','sound'=>'ы','stressed'=>'ы');
        $literal['Э'] = array('status'=>'гласный','sound'=>'э','stressed'=>'э');
        $literal['Ю'] = array('status'=>'гласный','sound'=>'у','stressed'=>'у', 'АаЕеЁёИиОоУуЭэЮюЯяЬьЫыЪъ' => 'йу');
        $literal['Я'] = array('status'=>'гласный','sound'=>'а','stressed'=>'а', 'АаЕеЁёИиОоУуЭэЮюЯяЬьЫыЪъ' => 'йа'); // заяц произносится как [зайец]
        $v_pattern = 'АаЕеЁёИиОоУуЭэЮюЯяЬьЫыЪъ';

// кстати, надо добавить выкусывание гласных из концов слов, заканчивающихся на согласный-гласный-звонкий согласный (-ром, -лем, итд) гласная очень часто сглатывается
// зы: это здесь не реализовано %)
// проверено: soundex и сам с этим неплохо справляется

// звонкие согласные редуцируются при удвоении.
// звонкие согласные переходят в парный глухой перед глухим
// глухие редуцируются полностью перед глухими.

// consonant
// в отличие от гласных, для согласных условие "стоит перед указанной или в конце слова"
        $literal['Б'] = array('status'=>'согласный','sound'=>'б', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'п');
        $literal['В'] = array('status'=>'согласный','sound'=>'в', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'ф');
        $literal['Г'] = array('status'=>'согласный','sound'=>'Г', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'к');
        $literal['Д'] = array('status'=>'согласный','sound'=>'д', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'т');
        $literal['Ж'] = array('status'=>'согласный','sound'=>'ж', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'ш');
        $literal['З'] = array('status'=>'согласный','sound'=>'з', 'КкПпСсТтФфХхЦцЧчШшЩщ' => 'с');
        $literal['Й'] = array('status'=>'согласный','sound'=>'й');
        $literal['К'] = array('status'=>'согласный','sound'=>'к', 'КкПпСсТтФфХхЦцЧчШшЩщ' => '');
        $literal['Л'] = array('status'=>'согласный','sound'=>'л');
        $literal['М'] = array('status'=>'согласный','sound'=>'м');
        $literal['Н'] = array('status'=>'согласный','sound'=>'н');
        $literal['П'] = array('status'=>'согласный','sound'=>'п', 'КкПпСсТтФфХхЦцЧчШшЩщ' => '');
        $literal['Р'] = array('status'=>'согласный','sound'=>'р');
        $literal['С'] = array('status'=>'согласный','sound'=>'с'); // а вот С не хочет редуцироваться, на первый взгляд...
        $literal['Т'] = array('status'=>'согласный','sound'=>'т', 'КкПпСсТтФфХхЦцЧчШшЩщ' => '');
        $literal['Ф'] = array('status'=>'согласный','sound'=>'ф', 'КкПпСсТтФфХхЦцЧчШшЩщ' => ''); // спорно
        $literal['Х'] = array('status'=>'согласный','sound'=>'х');
        $literal['Ц'] = array('status'=>'согласный','sound'=>'ц');
        $literal['Ч'] = array('status'=>'согласный','sound'=>'чь'); // всегда мягкий
        $literal['Ш'] = array('status'=>'согласный','sound'=>'ш');
        $literal['Щ'] = array('status'=>'согласный','sound'=>'щь');

// спецсимволы
        $literal['Ъ'] = array('status'=>'знак','sound'=>' '); // только разделительный. делит жёстко
        $literal['Ь'] = array('status'=>'знак','sound'=>'ь'); // даже если делит, то мягко

        $literal['ТС'] = array('status'=>'сочетание','sound'=>'ц');
        $literal['ТЬС'] = $literal['ТС'];
        $literal['ШЬ'] = array('status'=>'сочетание','sound'=>'ш'); // всегда твёрдый. и это не единстенный рудимент языка

        $literal['СОЛНЦ'] = array('status'=>'сочетание','sound'=>'сонц');
        $literal['ЯИЧНИЦ'] = array('status'=>'сочетание','sound'=>'еишниц');
        $literal['КОНЕЧНО'] = array('status'=>'сочетание','sound'=>'канешно');
        $literal['ЧТО'] = array('status'=>'сочетание','sound'=>'што');
        $literal['ЗАЯ'] = array('status'=>'сочетание','sound'=>'зайэ'); // да-да. не только [зайэц], но и [зайэвльэнийэ]




        $sound = $this->str_to_upper($source);

// сначала сочетания
        foreach( array_filter($literal,
            create_function('$item','if( $item["status"] === "сочетание") return true; return false;'))
                 as $sign => $translate )
            $sound = str_replace($sign,$translate["sound"],$sound);

// потом знаки
        foreach( array_filter($literal,
            create_function('$item','if( $item["status"] === "знак") return true; return false;'))
                 as $sign => $translate )
            $sound = str_replace($sign,$translate["sound"],$sound);


// разделяем на слова, определяем кол-во слогов, заменяем ударный/безударный гласный (единственный или предполагая второй в двух-трёхсложном слове, предпредпоследний - в остальных)

        $words = preg_split('~[,.\~`1234567890-=\~!@#$%^&*()_+|{}\]\];:\'"<>/? ]~', $sound, -1, PREG_SPLIT_NO_EMPTY);

// гласные
        foreach( array_filter($literal,
            create_function('$item','if( $item["status"] === "гласный") return true; return false;'))
                 as $sign => $translate )
        {
// для каждого слова
            foreach( $words as &$word )
            {
// кол-во гласных
                $vowel = preg_match_all("~[$v_pattern]~", $word, $del_me );
// готовим
                $cur_pos = 0;
                $cur_vowel = 0;
                while( false !== $cur_pos = strpos($word,$sign,$cur_pos) )
                {
                    $cur_vowel++;
// print $cur_pos.' = '.$sound[$cur_pos]."<br />\r\n";
                    if( sizeof($translate)==4 && ($cur_pos === 0 || strpos( $v_pattern , $word[$cur_pos-1] )))
                    {
                        $word = substr_replace($word,$translate[$v_pattern],$cur_pos,1);
                    }
                    elseif( 1 == $vowel )
                        $word = substr_replace($word,$translate["stressed"],$cur_pos,1); //
                    elseif( 2 == $vowel && 1 == $cur_vowel )
                        $word = substr_replace($word,$translate["stressed"],$cur_pos,1); // предполагаем, что в двухсложных словах первый слог ударный
                    elseif( 3 <= $vowel && $cur_vowel == $vowel - 2 )
                        $word = substr_replace($word,$translate["stressed"],$cur_pos,1); // предполагаем, что слог ударный предпредпоследний
                    else
                        $word = substr_replace($word,$translate["sound"],$cur_pos,1);
                    $cur_pos++;
                }
            }
        }

        $sound = implode( $words, ' ' ); // клеим обратно

// согласные
        foreach( array_filter($literal,
            create_function('$item','if( $item["status"] === "согласный") return true; return false;'))
                 as $sign => $translate )
        {
// готовим
            $cur_pos = 0;
            while( false !== $cur_pos = strpos($sound,$sign,$cur_pos) )
            {
// print $cur_pos.' = '.$sound[$cur_pos]."<br />\r\n";
                if( sizeof($translate)==3 )
                {
                    $x = array_pop(array_keys($translate)); // снимаем третий элемент
                    if( strpos( $x, $sound[$cur_pos+1] ) || $cur_pos === strlen($sound) )
                    {
                        $sound = substr_replace($sound,$translate[$x],$cur_pos,1);
                    } elseif ( $sound[$cur_pos] === $sound[$cur_pos+1] )
                        $sound = substr_replace($sound,$translate["sound"],$cur_pos,2); // все двойные редуцируются
                    else
                        $sound = substr_replace($sound,$translate["sound"],$cur_pos,1);

                } else
                {
                    $sound = substr_replace($sound,$translate["sound"],$cur_pos,1);
                }

                $cur_pos++;
            }
        }
// алес. фонемы привели к одному виду
// дальше используем любой алгоритм для вычисления числового эквивалента

// но остаётся сомнение. очень хочется расстаться с глухими предлогами перед глухими согласными ("к скалам")


        $sound = preg_replace('~[,.\~`1234567890-=\~!@#$%^&*()_+|{}\]\];:\'"<>/? ]~','',$sound) ;

// print $sound;
// print str_to_translit($sound);
// print soundex(str_to_translit($sound));

        $res = $this->str_to_upper($source[0]).substr(soundex($this->str_to_translit($sound)),1);

        return $res;
    }

    private function str_to_upper($str){
    return strtr($str,
        "abcdefghijklmnopqrstuvwxyz".
        "\xE0\xE1\xE2\xE3\xE4\xE5".
        "\xb8\xe6\xe7\xe8\xe9\xea".
        "\xeb\xeC\xeD\xeE\xeF\xf0".
        "\xf1\xf2\xf3\xf4\xf5\xf6".
        "\xf7\xf8\xf9\xfA\xfB\xfC".
        "\xfD\xfE\xfF",
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
        "\xC0\xC1\xC2\xC3\xC4\xC5".
        "\xA8\xC6\xC7\xC8\xC9\xCA".
        "\xCB\xCC\xCD\xCE\xCF\xD0".
        "\xD1\xD2\xD3\xD4\xD5\xD6".
        "\xD7\xD8\xD9\xDA\xDB\xDC".
        "\xDD\xDE\xDF");
}

    private function str_to_translit($str){
    return strtr($str,
        "abcdefghijklmnopqrstuvwxyz".
        "\xE0\xE1\xE2\xE3\xE4\xE5".
        "\xb8\xe6\xe7\xe8\xe9\xea".
        "\xeb\xeC\xeD\xeE\xeF\xf0".
        "\xf1\xf2\xf3\xf4\xf5\xf6".
        "\xf7\xf8\xf9\xfA\xfB\xfC".
        "\xfD\xfE\xfF",
        "abcdefghijklmnopqrstuvwxyz".
        "abvgde".
        "?*ziik".
        "lmnopr".
        "stufhc".
        "4ww\"y`".
        "eua");
}
}
?>