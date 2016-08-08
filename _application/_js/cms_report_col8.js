/**
 * Created by Анатоли on 09.06.2016.
 */
function imgFormat(id, options, rowObject){
    var filterColName=Object.keys(rowObject)[0];
    var filterColValue=rowObject[filterColName];
    var ico='<span class="glyphicon glyphicon-send"></span>';
    var href='<a href="/viewreestr/filter?filter='+filterColName+'&value='+filterColValue+'" target="_blank">'+ico+'</a>'
    return href;
}

var col="8"
jQuery("#jqReportcol"+col).jqGrid({
    url:'/json',
    mtype: "POST",
    postData: {
        col: col,
    },
    datatype: "local",
    colNames:['Юридический адрес организации-заявителя МИ','Кол-во','Перейти'],
    colModel:[
        {name:'col'+col,index:'col'+col, align:'center', width:250, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
        {name:'count_col'+col,index:'count_col'+col, align:'center', width:140, searchoptions:{sopt:['le','ge','eq']}, formatter:"integer",sorttype:"int"},
        {name: 'ico', index:'ico', width: 30, align: 'center', formatter: imgFormat}
    ],
    rowNum:50,
    rowList:[20,50,100,200],
    pager: '#pagercol'+col,
    sortname: 'count_col'+col,
    viewrecords: true,
    sortorder: "desc",
    loadonce: false,
    width: "auto",
    height: '300px',
    autowidth: true,
    shrinkToFit: true,
    // shrinkToFit: false,
    forceFit: true,
    ignoreCase: true,
});
jQuery("#jqReportcol"+col).jqGrid('navGrid','#pagercol'+col,{edit:false,add:false,del:false,search:false});
jQuery("#jqReportcol"+col).jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
jQuery("#jqReportcol"+col).jqGrid('navButtonAdd','#pagercol'+col,{
    caption:"Экспорт в Excel",
    onClickButton : function () {
        idGrid="#"+$(this).attr('id');
        var columnNames = $(idGrid).jqGrid('getGridParam','colNames');
        var getpostData = $(idGrid).getGridParam('postData');
        $(idGrid).setGridParam({
            postData: {
                colums: columnNames,
                col:getpostData.col,
                filename: columnNames[0],
                charset:'windows-1251'
            }
        }).trigger("reloadGrid");
        jQuery(idGrid).jqGrid('excelExport',{"url":"/json/export"});
    }
});