<?php
//указываем путь к root файла,
//отталкиваясь от этого пути будет идти рекурсия в глубину
$HOME = dirname(__FILE__);

//если в качестве ОС стоит Windows, то $WIN = 1;
//это нужно для правильного составления пути
$WIN = 1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>UTF8 BOM FINDER and REMOVER</title>
    <style>
        body { font-size: 10px; font-family: Arial, Helvetica, sans-serif; background: #FFF; color: #000; }
        .found { color: #F30; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
<?php
$BOMBED = array();
RecursiveFolder($HOME);
if (!empty($BOMBED)){
    echo '<h2>Эти файлы имели кодировку UTF8 и хранили BOM символы, но по результатам скрипта все символы BOM были удалены:</h2><p class="found">';
    foreach ($BOMBED as $utf) { echo $utf ."<br />\n"; }
    echo '</p>';
} else {
    echo "<h2>По результатам скрипта: ни одного файла с кодировкой UTF8 с BOM символами не найдено.</h2>";
}


// функция рекурсивно находит все файлы BOM
function RecursiveFolder($sHOME) {
    global $BOMBED, $WIN;

    $win32 = ($WIN == 1) ? "\\" : "/";

    $folder = dir($sHOME);

    $foundfolders = array();
    while ($file = $folder->read()) {
        if($file != "." and $file != "..") {
            if(filetype($sHOME . $win32 . $file) == "dir"){
                $foundfolders[count($foundfolders)] = $sHOME . $win32 . $file;
            } else {
                $content = file_get_contents($sHOME . $win32 . $file);
                $BOM = SearchBOM($content);
                if ($BOM) {
                    $BOMBED[count($BOMBED)] = $sHOME . $win32 . $file;

// удаляем первые три символа
                    $content = substr($content,3);
// сохраняем обратно в файл
                    file_put_contents($sHOME . $win32 . $file, $content);
                }
            }
        }
    }
    $folder->close();

    if(count($foundfolders) > 0) {
        foreach ($foundfolders as $folder) {
            RecursiveFolder($folder, $win32);
        }
    }
}

// проверяем наличие BOM символов в файле
function SearchBOM($string) {
    if(substr($string,0,3) == pack("CCC",0xef,0xbb,0xbf)) return true;
    return false;
}

?>
</body>
</html>