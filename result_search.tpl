<div id="search_result_element<? echo $value[id] ?>">
    &nbsp;&nbsp; &nbsp;<h4><a href='index.php?cat=<?=$value[category] ?>&amp;mat=<?=$value[id] ?>&amp;style=1'><?=$value[title] ?></a></h4>
    &nbsp;&nbsp;&nbsp; <? $text = "<p>".substr($value[text],0, 430)."</span>...</p>"; echo $text; ?>
    &nbsp;&nbsp;&nbsp; <p>Количество совпадений: <?=$value[relevation];?></p>