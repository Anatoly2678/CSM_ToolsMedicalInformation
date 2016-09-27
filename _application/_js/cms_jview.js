/**
 * Created by Анатоли on 01.06.2016.
 */
function Col5Shot(id, options, rowObject){
    var re = new RegExp("[a-z\d-]+|[а-яё\d-]+", "gi");
    var myArray = rowObject.col5.match(re);
    var resultStr = '';
    var postfix='.....';
    var length = 10;
    if (myArray.length < 10) {
        length = myArray.length;
        postfix='';
    }
    for (i = 0; i < length; i++) {
        resultStr = resultStr + myArray[i] + ' ';
    }
    return_opis = "<span style='white-space: normal;' title='" + rowObject.col5 + "'>" + resultStr + postfix + "</span>"; // " + rowObject.col5 + "
    return return_opis;
}

function URL(id, options, rowObject) {
    var filterColName=Object.keys(rowObject)[options.pos-2];
    var filterColValue=rowObject[filterColName];
    var href='<a href="/refbook/filter?filter=col1&value='+filterColValue+'" target="_blank" class="jview-url action-go-refbook">'+filterColValue+'</a>'
    return href
}

function FilterResult () {
    var res=false;
    if (filter) {res=true;}
    return res;
}
var getCookieHideColumn
$(document).ready(function () {
    getCookieHideColumn=$.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'get'},async: false}).responseJSON;

    jQuery("#jqGrid").jqGrid({
       // url:'/_application/json/jqList.php',
        url:'/search',
        datatype: "json",
        mtype: "POST",
        search: FilterResult(),
        postData: {type: 'main',
            searchAll: function() { return $("#searchAll").val(); },
            filters: '{"groupOp":"AND","rules":[{"field":"'+filter+'","op":"eq","data":"' + filtervalue + '"}]}',
            hideColumn : getCookieHideColumn
        },
        page: 1,
        // colNames:['Уникальный номер реестровой записи','Регистрационный номер медицинского изделия','Дата государственной регистрации медицинского изделия','Срок действия регистрационного удостоверения',
        //     'Наименование медицинского изделия','Наименование организации - заявителя медицинского изделия','Место нахождения организации-заявителя медицинского изделия',
        //     'Юридический адрес организации-заявителя медицинского изделия','Наименование организации-производителя медицинского изделия или организации-изготовителя медицинского изделия'
        //     ,'Место нахождения организации-производителя медицинского изделия или организации - изготовителя медицинского изделия'
        //     ,'Юридический адрес организации-производителя медицинского изделия или организации - изготовителя медицинского изделия'
        //     ,'Код Общероссийского классификатора продукции для медицинского изделия','Класс потенциального риска применения медицинского изделия'
        //     ,'Назначение медицинского изделия, установленное производителем','Вид медицинского изделия в соответствии с номенклатурной','Адрес места производства или изготовления медицинского изделия'
        //     ,'Сведения о взаимозаменяемых медицинских изделиях'],
        colNames:['Уникальный № реестровой записи','Регистрационный номер МИ','Дата гос. регистрации МИ','Действия (статус) РУ','Срок действия РУ',
            'Наименование МИ','Наименование организации - заявителя МИ','Место нахождения организации-заявителя МИ',
            'Юридический адрес организации-заявителя МИ','Наименование организации-производителя МИ или организации-изготовителя МИ'
            ,'Место нахождения организации-производителя МИ или организации - изготовителя МИ'
            ,'Юридический адрес организации-производителя МИ или организации - изготовителя МИ'
            ,'Код Общероссийского классификатора продукции для МИ','Класс потенциального риска применения МИ'
            ,'Назначение МИ, установленное производителем','Вид МИ в соответствии с номенклатурной','Адрес места производства или изготовления МИ'
            ,'Сведения о взаимозаменяемых МИ'],
        colModel:[
            {name:'col1',index:'col1', align:'center', width:150, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
            {name:'col2',index:'col2', align:'center', width:200, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
            {name:'col3',index:'col3', align:'center', width:150, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}, formatter:"date",
                searchoptions: { sopt:['rn'], dataInit: function(el) {
                    $(el).datepick($.extend({ showTrigger: '#calImg',
                        rangeSelect: true,
                onClose: function(dates) { $("#jqGrid")[0].triggerToolbar(); }
            }, $.datepick.regionalOptions['ru'] ));
                    $(el).datepick('option', {dateFormat: 'yyyy-mm-dd'});
                }}
            },
            {name:'col4_state',index:'col4_state', align:'center', width:150, searchoptions:{sopt:['eq']}, stype:"select",
                searchoptions: {value: "-1:Все;Бессрочно:Бессрочно;Отменено:Отменено;Действующий+Бессрочно:Действующие+Бессрочно;Действующий:Только действующие;Срок действия истек:Срок действия истек", defaultValue: "-1" }},
            {name:'col4_data',index:'col4_data', align:'center', width:150, formatter:"date"
            , searchoptions: { sopt:['le','lt','eq'], dataInit: function(el) {
                $(el).datepick($.extend({ showTrigger: '#calImg',
                        // rangeSelect: true,
                onClose: function(dates) { $("#jqGrid")[0].triggerToolbar(); }}
                , $.datepick.regionalOptions['ru'] ));
                $(el).datepick('option', {dateFormat: 'yyyy-mm-dd'});
    }
    }},
            {name:'col5',index:'col5', align:'center', width:500, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},title:false, sortable:false}, // ,formatter: Col5Shot
            {name:'col6',index:'col6', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col7',index:'col7', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col8',index:'col8', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col9',index:'col9', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col10',index:'col10', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col11',index:'col11', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col12',index:'col12', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col13',index:'col13', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col14',index:'col14', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col15',index:'col15', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']},formatter: URL},
            {name:'col16',index:'col16', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}},
            {name:'col17',index:'col17', align:'center', width:140, searchoptions:{sopt:['bw','cn','eq']}}
        ],
        rowNum: 100,
        scroll: 0,
        pager: "#pager2",
        width: "auto",
        height: '600px',
        autowidth: true,
        autoheight: true,
        // shrinkToFit: true,
        shrinkToFit: false,
        sortname: 'col3',
        forceFit: true,
        sortorder: "desc",
        viewrecords: true,
        rowList:[15,20,25,30,100,300,500],
        ignoreCase: true,
        rownumbers: false,
        rownumWidth: 40,
        multiselect: true,
        subGrid : true,

        subGridOptions: {
            "plusicon"  : "ui-icon-triangle-1-e",
            "minusicon" : "ui-icon-triangle-1-s",
            "openicon"  : "ui-icon-arrowreturn-1-e"
        },
        subGridRowExpanded: function(subgrid_id, row_id) {
            var subgrid_table_id, pager_id;
            subgrid_table_id = subgrid_id+"_t";
            pager_id = "p_"+subgrid_table_id;
            $("#"+subgrid_id).html("<div style='width:50%' class='subjqGrid'><table id='"+subgrid_table_id+"' class='scroll myss' style='margin: 0px; width: auto'></table><div id='"+pager_id+"' class='scroll' ></div></div>");
            jQuery("#"+subgrid_table_id).jqGrid({
                url:"/_application/json/jqListCustom.php?col5=full&col1=full&id="+row_id,
                datatype: "json",
                colNames: ['Наименование МИ'],
                colModel: [
                    {name:'col5',index:'col5', align:'center',align:"left"}
                ],
                rowNum:20,
                autowidth: true,
                width: "80%",
                shrinkToFit: true,
                height: '100%',
            });
        },
        ondblClickRow: function (rowId, iRow, iCol, e) {
            jQuery("#jqGrid").expandSubGridRow(rowId);
        }
    });
    jQuery("#jqGrid").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
    jQuery("#jqGrid").jqGrid('navButtonAdd','#pager2',{
        id:"export",
        caption:"Экспорт в Excel",
        onClickButton : function () {
            if (reestrType == 'viewreestr') {
                var searchTxt = $("#searchAll").val();
                var getCookieHideColumn=$.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'get'},async: false}).responseJSON;
                $("#jqGrid").setGridParam({
                    postData: {
                        hidecolumn: getCookieHideColumn
                    }
                })
                if (searchTxt) {
                    $("#jqGrid").setGridParam({
                        search: true, postData: {
                            filters: '{"groupOp":"OR","rules":[{"field":"col2","op":"cn","data":"' + searchTxt + '"},' +
                            '{"field":"col5","op":"cn","data":"' + searchTxt + '"},{"field":"col9","op":"cn","data":"' + searchTxt + '"},{"field":"col10","op":"cn","data":"' + searchTxt + '"}]}'
                        }
                    }).trigger("reloadGrid");
                }
            }
            // var getCookieHideColumn=$.ajax({url: '/search/export', type: 'POST',data: {type:'main', searchAll:searchTxt, filters: '{"groupOp":"OR","rules":[{"field":"col2","op":"cn","data":"' + searchTxt + '"},' +
            //                 '{"field":"col5","op":"cn","data":"' + searchTxt + '"},{"field":"col9","op":"cn","data":"' + searchTxt + '"},{"field":"col10","op":"cn","data":"' + searchTxt + '"}]}'
            //             ,hidecolumn: getCookieHideColumn,_search: true,sidx:'col3',sord:'desc'},async: false}).responseText;
            // console.log (getCookieHideColumn);
            // jQuery("#jqGrid").jqGrid('excelExport',{"url":"/_application/json/jqList.php"});
            jQuery("#jqGrid").jqGrid('excelExport',{"url":"/search/export"});
            // ExportDataToExcel("#jqGrid");
        }
    });
    jQuery("#jqGrid").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
    $(".ui-pager-control select, .ui-pager-control input, .ui-search-toolbar input").css("color","black");
    $("#m-remove-col").show();


function ExportDataToExcel(tableCtrl) {
ExportJQGridDataToExcel(tableCtrl,"sample.xlsx");
}
 
function ExportJQGridDataToExcel(tableCtrl, excelFilename) {  
var allJQGridData = $(tableCtrl).jqGrid('getGridParam', 'data');
 // var allJQGridData = $(tableCtrl).jqGrid('getRowData');
 var jqgridRowIDs = $(tableCtrl).getDataIDs(); // Fetch the RowIDs for this grid
 var headerData = $(tableCtrl).getRowData(jqgridRowIDs[0]);
 console.log (headerData);
 console.log (jqgridRowIDs);
}

    addminus=0;
    if (reestrType == 'viewreestr') {
        addminus=40;
        $("#m-reestr").removeClass("active");
        $("#m-viewreestr").addClass("active");
        $("#m-refbook").removeClass("active");
        $("#m-report").removeClass("active");
        $("#m-handbooks").removeClass("active");
    } else {
        $("#m-viewreestr").removeClass("active");
        $("#m-reestr").addClass("active");
        $("#m-refbook").removeClass("active");
        $("#m-report").removeClass("active");
        $("#m-handbooks").removeClass("active");
    }

    autoHeightGrid(addminus);
    $(".ui-jqgrid-bdiv").css("overflow","hidden !important");
    $( "<a href='#' class='ico-del-cell'><span class='glyphicon glyphicon-shopping-cart'></span></a>" ).insertBefore( ".ui-jqgrid-labels .ui-jqgrid-sortable" );

    // Auto Hide Column in table. Value sets in Cookies

    $.each( getCookieHideColumn, function( key, val ){
        var tabName='jqGrid'
        $("#m-remove-col .remove-col .divider").before('<li id="'+key+'" class="hide-col"><a href="#">'+val+'</a></li>')
        jQuery("#"+tabName).jqGrid('hideCol',key);
        $("#m-remove-col .btn").removeClass("btn-default");
        $("#m-remove-col .btn").addClass("btn-primary");
    })


    $('#searchAll').keyup(function(){
        if(event.keyCode==13) {
            filterGrid()
        }
    })

    $('#btnSearchAll').on('click',function(){
        filterGrid();
    });

    $('.ico-del-cell').on('click',function(){
        var tabName='jqGrid'
        var thisId=$(this).parent('th').prop("id")
        var curCol=thisId.replace(tabName+"_", "");
        var curNameCol=$(this).next("div").text();
        $.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'sethide',col_name:curCol,col_alias:curNameCol},async: true});
        $("#m-remove-col .remove-col .divider").before('<li id="'+curCol+'" class="hide-col"><a href="#">'+curNameCol+'</a></li>')
        jQuery("#"+tabName).jqGrid('hideCol',curCol);
        $("#m-remove-col .btn").removeClass("btn-default");
        $("#m-remove-col .btn").addClass("btn-primary");
        getCookieHideColumn=$.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'get'},async: false}).responseJSON;
        return false;
    })

    $('body').on('click', '#m-remove-col .hide-col', function(){
        var tabName='jqGrid'
        var curCol=$(this).prop('id');
        $.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'setshow',col_name:curCol},async: true});
        jQuery("#"+tabName).jqGrid('showCol',curCol);
        $(this).remove();
        var countHideColumn = $('#m-remove-col .hide-col').size();
        if (countHideColumn == 0) {
            $("#m-remove-col .btn").addClass("btn-default");
            $("#m-remove-col .btn").removeClass("btn-primary");
            return true;
        }
        getCookieHideColumn=$.ajax({url: '/_application/core/cookie.php', type: 'POST',data: {type:'get'},async: false}).responseJSON;
        return false;
    })
});

function autoHeightGrid(addminus) {
    var jqgridheight = $(".ui-jqgrid").height();
    var myheight = document.body.clientHeight;
    var headjqgridheight = $(".ui-jqgrid-hbox").height();
    var footerjqgridheight = $(".ui-jqgrid-pager").height();
    var navbarheight = $(".navbar").height();
    var containerheight = $(".ui-jqgrid-bdiv").height();
    var sum = headjqgridheight + footerjqgridheight + navbarheight + containerheight;
    var deff = sum - myheight;
    var bodyjqgridheight = containerheight - deff - 15 - addminus;
    $(".ui-jqgrid-bdiv").height(bodyjqgridheight);
}


function filterGrid() {
    var searchTxt=$("#searchAll").val();
    if (searchTxt) {
        newUrl='/search';
        $("#jqGrid").setGridParam({url:newUrl,search: true, postData: {filters:'{"groupOp":"OR","rules":[{"field":"col2","op":"cn","data":"'+searchTxt+'"},' +
        '{"field":"col5","op":"cn","data":"'+searchTxt+'"},{"field":"col9","op":"cn","data":"'+searchTxt+'"},{"field":"col10","op":"cn","data":"'+searchTxt+'"}]}'}}).trigger("reloadGrid");
        $("#jqGrid")[0].triggerToolbar();
    } else {
        // oldUrl='/_application/json/jqList.php';
        oldUrl='/search';
        $("#jqGrid").setGridParam({url:oldUrl});
        $("#jqGrid")[0].triggerToolbar();
    }
    return false;

}