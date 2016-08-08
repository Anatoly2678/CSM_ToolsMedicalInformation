<?php
/**
 * Created by PhpStorm.
 * User: Анатолий
 * Date: 16.05.2016
 * Time: 23:15
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