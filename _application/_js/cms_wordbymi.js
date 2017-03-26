function urnz(id, options, rowObject){
    var filterColName=Object.keys(rowObject)[0];
    var filterColValue=rowObject[filterColName];
    filterColName='col1';
    var ico='<span class="glyphicon glyphicon-send"></span>';
    var href='<a style="color: #0b3e6f; text-decoration: underline" href="/viewreestr/filter?filter='+filterColName+'&value='+filterColValue+'" target="_blank" title="Ссылка на МИ">'+filterColValue+'</a>'
    return href;
}

col="1";
jQuery("#jqGridMI").jqGrid({
    url:'wordbymi/json',
    mtype: "POST",
    postData: {
        col: col,
    },
    datatype: "json",
    colNames:['УНРЗ','Кол-во слов найденных','Слова найденные','Корень','Раздел присвоенный для слов','Вид МИ в соотв с номенкл','Раздел присвоенный для МИ',
    'Совпали разделы','Кол-во совпадений','% верно','% неверно'],
    colModel:[
        {name:'col1',index:'col1', align:'center', width:100, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}, formatter: urnz},
        {name:'cnt',index:'cnt', align:'center', width:100, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"},
        {name:'wordConcat',index:'wordConcat', align:'center', width:450, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
        {name:'root',index:'root', align:'center', width:150, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}, hidden: true},
        {name:'selectorWord',index:'selectorWord', align:'center', width:150, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
        {name:'col15',index:'col15', align:'center', width:100, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"},
        {name:'selectorMI',index:'selectorMI', align:'center', width:100, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
        {name:'match_value',index:'match_value', align:'center', width:150, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
        {name:'match_count',index:'match_count', align:'center', width:100, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"},
        {name:'percen_true',index:'percen_true', align:'center', width:100, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"},
        {name:'percent_false',index:'percent_false', align:'center', width:100, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"}
    ],
    rowNum:50,
    rowList:[20,50,100,200],
    pager: '#jqGridMI_Pager',
    sortname: 'cnt',
    viewrecords: true,
    sortorder: "desc",
        scroll: 0,
    loadonce: true,
    width: "auto",
    height: '600px',
    autowidth: true,
    shrinkToFit: true,
    forceFit: true,
    ignoreCase: true,
});
jQuery("#jqGridMI").jqGrid('navGrid','#jqGridMI_Pager',{edit:false,add:false,del:false,search:false});
jQuery("#jqGridMI").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
jQuery("#jqGridMI").jqGrid('navButtonAdd','#jqGridMI_Pager',{
    caption:"Экспорт в Excel",
    onClickButton : function () {
        jQuery("#jqGridMI").jqGrid('excelExport',{"url":"wordbymi/export"});
    }
});
jQuery("#jqGridMI").jqGrid('navButtonAdd','#jqGridMI_Pager',{
    caption:"Обновить данные",
    onClickButton : function () {
        retResponseText=$.ajax({url: 'wordbymi/update', type: 'POST',async: true,
            beforeSend: function (data, textStatus) {
                $("#jqGridMI").closest(".ui-jqgrid").find('.ui-jqgrid-view').css('opacity','0.5');
                $("#jqGridMI").closest(".ui-jqgrid").find('.loading').show();
            },
            success: function (data, textStatus) {
                console.log (data);
                console.log (textStatus);
                if (data == "0" && textStatus === "success") {
                    $("#jqGridMI").closest(".ui-jqgrid").find('.ui-jqgrid-view').css('opacity','1');
                    $("#jqGridMI").closest(".ui-jqgrid").find('.loading').hide();
                    $("#jqGridMI").setGridParam({datatype:'json'}).trigger('reloadGrid',[{page:1}]);
                }
            }
        }).responseText;

        // jQuery("#jqGridMI").jqGrid('excelExport',{"url":"wordbymi/update"});
        //
        // idGrid="#"+$(this).attr('id');
        // var columnNames = $(idGrid).jqGrid('getGridParam','colNames');
        // var getpostData = $(idGrid).getGridParam('postData');
        // $(idGrid).setGridParam({
        //     postData: {
        //         colums: columnNames,
        //         col:getpostData.col,
        //         filename: columnNames[0],
        //         charset:'windows-1251'
        //     }
        // }).trigger("reloadGrid");
        // jQuery(idGrid).jqGrid('excelExport',{"url":"/json/export"});
    }
});



$("#m-viewreestr").removeClass("active");
$("#m-reestr").removeClass("active");
$("#m-refbook").removeClass("active");
$("#m-report").removeClass("active");
$("#m-handbooks").removeClass("active");
$("#m-wordbymi").addClass("active");

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