<div class="panel-group" id="accordionReport" style="visibility: hidden">
<?php

//$collapse = array("col3"=>"Дата гос.регистрации МИ", "col4"=>"Срок действия РУ", "col6"=>"Наименование организации - заявителя МИ",
//    'col9'=>'Наименование организации-производителя МИ или организации-изготовителя МИ',
//    'col11'=>'Юридический адрес организации-производителя МИ или организации - изготовителя МИ',
//    'col12'=>'Код Общероссийского классификатора продукции для МИ');
$collapse = array('col1'=>'Уникальный № реестровой записи','col2'=>'Регистрационный номер МИ','col3'=>'Дата гос. регистрации МИ','col4_state'=>'Действия (статус) РУ','col4_data'=>'Срок действия РУ',
    'col5'=>'Наименование МИ','col6'=>'Наименование организации - заявителя МИ','col7'=>'Место нахождения организации-заявителя МИ',
    'col8'=>'Юридический адрес организации-заявителя МИ','col9'=>'Наименование организации-производителя МИ или организации-изготовителя МИ'
,'col10'=>'Место нахождения организации-производителя МИ или организации - изготовителя МИ'
,'col11'=>'Юридический адрес организации-производителя МИ или организации - изготовителя МИ'
,'col12'=>'Код Общероссийского классификатора продукции для МИ','col13'=>'Класс потенциального риска применения МИ'
,'col14'=>'Назначение МИ, установленное производителем','col15'=>'Вид МИ в соответствии с номенклатурной','col16'=>'Адрес места производства или изготовления МИ'
,'col17'=>'Сведения о взаимозаменяемых МИ');


foreach ($collapse as  $key=>$col) {
    $inCollapse="";
//    if ($key == "col4")
    $inCollapse="in";
    echo ("<div class=\"panel panel-default\">");
    echo ("<div class=\"panel-heading\">");
    echo ("<h4 class=\"panel-title\">");
    echo ("<a data-toggle=\"collapse\" class=\"clickAccordion\" data-parent=\"#accordion\" href=\"#".$key."\">");
    echo ($col."</a>");
    echo ("</h4>");
    echo ("</div>");
    echo ("<div id=".$key." class=\"panel-collapse collapse $inCollapse\">"); //  in
    echo ("<div class=\"panel-body\"><table id=\"jqReport".$key."\"></table><div id=\"pager".$key."\"></div>");
    echo ("</div>");
    echo ("</div>");
    echo ("</div>");
}
?>
</div>

<link rel="stylesheet" type="text/css" href="/_js/datepick/smoothness.datepick.css"> 
<link rel="stylesheet" type="text/css" href="/_js/datepick/ui.datepick.css"> 
<link rel="stylesheet" type="text/css" href="/_js/datepick/ui-south-street.datepick.css">
<script type="text/javascript" src="/_js/datepick/jquery.plugin.js"></script>
<script type="text/javascript" src="/_js/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="/_js/datepick/jquery.datepick-ru.js"></script>

<?php
foreach ($collapse as  $key=>$col) {
    echo ("<script type=\"text/javascript\" src=\"/_application/_js/cms_report_".$key.".js\"></script>");
}
?>
<script type="text/javascript" src="/_application/_js/cms_report.js"></script>