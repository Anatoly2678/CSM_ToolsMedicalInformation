/**
 * Created by Анатоли on 16.08.2016.
 */
jQuery("#grdWords").jqGrid({
    url:'/handbooks/words',
    mtype: "POST",
    datatype: "json",
    postData: {handbooksType: 'Words'},
    colNames:['Ключ','Слово'],
    colModel:[
        {name:'id',index:'id', align:'center', width:150, searchoptions:{sopt:['eq','le','ge']}, formatter:"integer",sorttype:"int"},
        {name:'word',index:'word', align:'center', width:750, searchoptions:{sopt:['cn','eq','bw','bn',,'nc','ew','en']}},
    ],
    rowNum:100,
    rowList:[20,50,100,200,300],
    pager: '#pgWords',
    sortname: 'word',
    viewrecords: true,
    sortorder: "desc",
    loadonce: true,
    multiselect: true,
    height: '500px',
    width : null,
    shrinkToFit : false,
    forceFit: true,
    ignoreCase: true
});
jQuery("#grdWords").jqGrid('navGrid','#pgWords',{edit:false,add:false,del:true,search:false});
jQuery("#grdWords").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});