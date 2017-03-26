/**
 * Created by Анатоли on 19.06.2016.
 */
function imgFormat(id, options, rowObject){
    var filterColName=Object.keys(rowObject)[0];
    var filterColValue=rowObject[filterColName];
    var ico='<span class="glyphicon glyphicon-send"></span>';
    var href='<a href="/viewreestr/filter?filter=col15&value='+filterColValue+'" target="_blank">'+ico+'</a>'
    return href;
}

function FilterResult () {
    var res=false;
    if (filter) {res=true;}
    return res;
}

function mysum(val, name, record) {
    return record.Section; // parseFloat(val||0) + parseFloat((record[name]||0));
}

$(document).ready(function () {
    jQuery("#jqRefBook").jqGrid({
        url:'/_application/json/jqList.php',
        url:'/refbook/json',
        datatype: "json",
        cache: true,
        mtype: "POST",
        search: FilterResult(),
        postData: {type: 'refbook',
        filters: '{"groupOp":"AND","rules":[{"field":"'+filter+'","op":"eq","data":"' + filtervalue + '"}]}'
        },
        page: 1,
        colNames:['#Раздел','Раздел','Подраздел','Код','Наименование','Описание','Перейти'], // ,'Подгруппа','Схожесть','Схожесть2'
        colModel:[

            {name:'col2_section',index:'col2_section', align:'center', width:300, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'Section',index:'Section', align:'center', width:300, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}
                ,summaryType:mysum
                , summaryTpl : '({0}) total'
            },
            {name:'SubSection',index:'SubSection', align:'center', width:300, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'col1',index:'col1', align:'center', width:100, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}, formatter:"integer",sorttype:"int"},
            {name:'col3',index:'col3', align:'center', width:300, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en'],
                defaultSearch: 'cn'}},
            {name:'col4',index:'col4', align:'left', width:500, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            // {name:'col3_first_word',index:'col3_first_word', align:'left', width:500, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            // {name:'col3_soundex',index:'col3_soundex', align:'left', width:500, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            // {name:'col3_metaphone',index:'col3_metaphone', align:'left', width:500, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name: 'ico', index:'ico', width: 100, align: 'center', formatter: imgFormat}
        ],
        rowNum:29000,
        caption: 'НОМЕНКЛАТУРНАЯ КЛАССИФИКАЦИЯ МЕДИЦИНСКИХ ИЗДЕЛИЙ ПО ВИДАМ',
        // rowList:[5000,10000,20000,40000],
        // scroll: true,
        pager: '#pgRefBook',
        sortname: 'col1',
        viewrecords: true,
        sortorder: "asc",
        // loadonce: false,
        loadonce: true,
        scroll: 0,
        width: "99%",
        height: '600px',
        autowidth: true,
        shrinkToFit: true,
        // shrinkToFit: false,
        // forceFit: true,
        ignoreCase: true,
        grouping:true,
        groupingView : {
            groupField : ['col2_section','SubSection'],
            groupColumnShow : [false,true],
            groupText : ['<b>{Section} - Всего значений в разделе (включая подразделы): {1} </b>','<b>{0} - Всего значений в подразделе: {1} </b>'],
            groupCollapse : true,
            groupOrder: ['asc', 'asc'],
            groupSummary : [false, false],
            groupDataSorted : true
        },
    });
    jQuery("#jqRefBook").jqGrid('navGrid','#pgRefBook',{edit:false,add:false,del:false,search:false});
    jQuery("#jqRefBook").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
    jQuery("#jqRefBook").jqGrid('navButtonAdd','#pgRefBook',{
        caption:"Экспорт в Excel",
        onClickButton : function () {
            jQuery("#jqRefBook").jqGrid('excelExport',{"url":"/_application/json/jqList.php",mtype: "POST"});
        }
    });
    $(".ui-pager-control select, .ui-pager-control input, .ui-search-toolbar input").css("color","black");
    $("#m-viewreestr").removeClass("active");
    $("#m-reestr").removeClass("active");
    $("#m-refbook").addClass("active");
    $("#m-report").removeClass("active");
    $("#m-handbooks").removeClass("active");
    $("#m-wordbymi").removeClass("active");
    
    var jqgridheight=$(".ui-jqgrid").height();
    var myheight = document.body.clientHeight; //$("body").height();
    var headjqgridheight=$(".ui-jqgrid-hbox").height();
    var footerjqgridheight=$(".ui-jqgrid-pager").height();
    var navbarheight = $(".navbar").height();
    var containerheight=$(".ui-jqgrid-bdiv").height();
    var sum=headjqgridheight+footerjqgridheight+navbarheight+containerheight;
    var deff = sum-myheight;
    var bodyjqgridheight = containerheight - deff-15-25;
    $(".ui-jqgrid-bdiv").height(bodyjqgridheight);

});