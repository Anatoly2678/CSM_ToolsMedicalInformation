<?php
/** d
 * Created by PhpStorm.
 * User: Анатолий
 * Date: 16.05.2016
 * Time: 23:15
 */

//similar_text("анестезиологии", "анестезии", $percent);
//print_r($percent);
//echo "<br>";
//$a1=soundex("Orto-nitrofenil-beta-galaktozid differentsiruyuschiy disk IVD");
//$a2=soundex("Analizator svintsa");
//print_r($a1." --- ".$a2);
//
//die();


//include  ("_application/core/LinguaStemRu.php");
require_once  ("_application/core/LinguaStemRu.php");
$stemmer = new LinguaStemRu();
//$stemmer = new LinguaStemRu();
// using :
 $word = 'Ядерный';
LinguaStemRu::word($word);
 echo $word; // бега
echo "<hr>";
$word = 'прочный';
LinguaStemRu::word($word);
echo $word; // бега
echo "<hr>";
$word = 'красный';
LinguaStemRu::word($word);
echo $word; // бега
echo "<hr>";
$word = 'раствор';
LinguaStemRu::word($word);
echo $word; // бега
echo "<hr>";
$word = 'ИВД';
LinguaStemRu::word($word);
echo $word; // бега
//echo $stemmer->word('Ядерный прочный красный раствор ИВД') . "<br/>";
//echo $stemmer->stem_word('анестезии') . "<br/>";
//echo $stemmer->stem_word('для анестезиологии') . "<br/>";
//echo $stemmer->stem_word('анестезиолог') . "<br/>";
//echo "<hr>";
//echo $stemmer->stem_word('Автомобиль') . "<br/>";
//echo $stemmer->stem_word('Автомобилем') . "<br/>";
//echo $stemmer->stem_word('Автомобиля') . "<br/>";
//echo $stemmer->stem_word('автомобил') . "<br/>";


die("OK");


include ("_application/core/model.php");
include ("_application/core/search.php");

$ss=new Search();


$myarr=array('Стерилизатор паровой для агара','Стерилизатор на основе гамма-излучения','Стерилизатор газовый формальдегидный','Стерилизатор микроволновой для неупакованных изделий','Стерилизатор паровой для неупакованных изделий','Стерилизатор микроволновой для жидкостей','Стерилизатор паровой для жидкостей','Стерилизатор озоновый/на основе пероксида водорода','Стерилизатор сухожаровой','Стерилизатор сухожаровой','Стерилизатор химический жидкостный','Стерилизатор химический жидкостный','Стерилизатор-кипятильник','Стерилизатор-кипятильник','Стерилизатор этиленоксидный','Стерилизатор этиленоксидный/паровой','Стерилизатор плазменный','Стерилизатор газовый на основе перекиси водорода','Стерилизатор паровой для упакованных изделий','Стерилизатор микроволновой для изделий в стерильной упаковке','Стерилизатор на основе диоксида хлора','Стерилизатор электролитический','Стерилизатор электронно-лучевой','Стерилизатор инокуляционных петель','Стерилизатор рентгеновский','Стерилизатор паровой для неупакованных изделий/изделий в упаковке');
$myarr=array("анестезиологии", "анестезиологический","анестедизологический", "анестезии","миг","мир");

$myarr=array("Аноскоп","Видеогастродуоденоскоп","Видеогастроскоп","Видеодуоденоскоп","Видеоколоноскоп","Видеопанкреатоскоп","Видеосигмоидоскоп","Видеохоледохоскоп","Видеоэзофагоскоп","Видеоэнтероскоп","Гастродуоденоскоп","Гастроскоп","Дуоденоскоп","Колоноскоп","Панкреатоскоп","Проктоскоп","Ректороманоскоп","Ректоскоп","Сигмоидоскоп","Холедохоскоп","Эзофагоскоп","Эндоскоп","Энтероскоп","Стерилизатор","Стерилизатор-кипятильник","Стерилизированный","Анестизиия","анестезиологии", "анестезиологический","анестедизологический", "анестезии","madre","padre");
$res="<table border=1>";
$res .="<tr><td>Наименование</td><td>Наименование-сравнение</td><td>Степень различия</td><td>Вероятность совпадения в %</td></tr>";

$word_translit=array();
foreach ($myarr as $val1) {
    $word_translit[] = $ss->translit($val1);
}
print_r($word_translit);
die();

/*
$val0=$_GET['txt'];
$len_val0=mb_strlen($val0);
$res="";
$i=1;
foreach ($myarr as $val1) {
    $len_val1=mb_strlen($val1);
    $var1 = similar_text($val0, $val1, $tmp);
    $int = levenshtein($val0,$val1,1,1,1);
    $mm = metaphone ($val1);
    $m1=$int-$len_val0;
    $cc=mb_strlen($val1)/2;
    // if ($tmp>10 && $var1>=$cc) {
    $color="black";
    if ($tmp>45) { // && $int<$len_val1
        $color="red";
    $res .="<span style='color:$color'>($i) $val0($len_val0) - $val1($len_val1) = $tmp ($var1 + $cc) ** $int -- ($m1)</span><br>";
    }
    // $res .="($i) $val0($len_val0) - $val1($len_val1) = $tmp ($var1 + $cc) ** $int<br>";
    $i++;
}

echo $res;
die();

*/
/*
foreach ($myarr as $val) {
    foreach ($myarr as $val1) {
        $int = levenshtein($ss->dropBackWords($val1), $ss->dropBackWords($val));
        $var1 = similar_text($ss->dropBackWords($val1), $ss->dropBackWords($val), $tmp);
        $cc=mb_strlen($ss->dropBackWords($val1))/2;
        if ($tmp>50 && $var1>=$cc) {
            $res .= "<tr><td>".$ss->dropBackWords($val)."</td><td>".$ss->dropBackWords($val1)."</td><td>$int</td><td>$tmp</td></tr>";
        }
    }
}
$res .="</table>";
echo $res;
die();
*/

        /***
         * Смотрите функцию similar_text():
        $sovpalo=similar_text($stroka1,$stroka2,$prc);
        Возвращаемое значение: — кол-во совпавших символов.
        Проверка:
        if ($prc>10 && $sovpalo>=mb_strlen($stroka1)/2) {  (если процент совпадения больше 10 и кол-во совпавших символов больше половины) помещаем в подпункт...  }
         */
//echo $_SERVER['DOCUMENT_ROOT'];

//$colors = array('желтые');
//$lengths= array('длинные');
//$types = array('штаны');
//$strings = array();
//for ($i=0; $i<count($colors); $i++) {
//    for ($j=0; $j<count($lengths); $j++) {
//        for ($k=0; $k<count($types); $k++) {
//            $strings[] = "$colors[$i] $lengths[$j] $types[$k]";
//        }
//    }
//}
//echo implode('<br>', $strings);


//$sample0 = array(1,2,3,4);
//$sample1 = array('один','два','три');
//$stream = &$sample0;
//$count = count($stream);
//$php0 = null;
//$php1 = 'echo ';
//for ($index = 0; $index < $count; $index++)
//{
//    $php0.='for($a'.$index.'=0;$a'.$index.'<$count;$a'.$index.'++)';
//    $php1.='$stream[$a'.$index.'],\' \',';
//}
//$php1.='\'<br/>\';';
//eval($php0.$php1);


$data=array('название','мед','изделия');
$countIter=pow(count($data),count($data))-1;
for($i=0;$i<=$countIter;$i++)
{
    $curMask = base_convert($i,10,count($data));
    while(strlen($curMask)<count($data))
    {
        $curMask = '0'.$curMask;
    }
    $newWorld='%';
    $FLAG = false;
    for ($j=0;$j<strlen($curMask);$j++)
    {
        if(strpos($newWorld,$data[$curMask[$j]])!==FALSE)
        {
            $FLAG = true;
        }
        $newWorld .=$data[$curMask[$j]].'%';
    }
    if((strlen($newWorld)<count($data))or($FLAG))
    {
        continue;
    };
    echo '( col5 LIKE \''.$newWorld.'\') OR ';

}