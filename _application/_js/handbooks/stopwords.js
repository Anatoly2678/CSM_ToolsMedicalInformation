/**
 * Created by Анатоли on 22.08.2016.
 */
jQuery("#grdStopWords").jqGrid({
    url:'/handbooks/stopwords',
    mtype: "POST",
    datatype: "json",
    postData: {handbooksType: 'StopWords'},
    colNames:['Ключ','Слово'],
    colModel:[
        {name:'id',index:'id', align:'center', width:150, searchoptions:{sopt:['eq','le','ge']}, formatter:"integer",sorttype:"int"},
        {name:'stopWord',index:'stopWord', align:'center', width:750, searchoptions:{sopt:['cn','eq','bw','bn',,'nc','ew','en']}},
    ],
    rowNum:100,
    rowList:[20,50,100,200,300],
    pager: '#pgStopWords',
    sortname: 'stopWord',
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
jQuery("#grdStopWords").jqGrid('navGrid','#pgStopWords',{edit:false,add:false,del:true,search:false});
jQuery("#grdStopWords").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});