/**
 * Created by Анатоли on 22.08.2016.
 */
jQuery("#grdEndOf").jqGrid({
    url:'/handbooks/endof',
    mtype: "POST",
    datatype: "json",
    postData: {handbooksType: 'EndOf'},
    colNames:['Ключ','Слово'],
    colModel:[
        {name:'id',index:'id', align:'center', width:150, searchoptions:{sopt:['eq','le','ge']}, formatter:"integer",sorttype:"int"},
        {name:'endOfWord',index:'endOfWord', align:'center', width:750, searchoptions:{sopt:['cn','eq','bw','bn',,'nc','ew','en']}},
    ],
    rowNum:100,
    rowList:[20,50,100,200,300],
    pager: '#pgEndOf',
    sortname: 'endOfWord',
    viewrecords: true,
    sortorder: "desc",
    loadonce: true,
    multiselect: true,
    height: '500px',
    width : null,
    shrinkToFit : false,
    forceFit: true,
    ignoreCase: true,
    editurl:'wee'
});
jQuery("#grdEndOf").jqGrid('navGrid','#pgEndOf',{edit:false,add:false,del:true,search:false});
jQuery("#grdEndOf").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
jQuery("#grdEndOf").jqGrid('inlineNav','#pgEndOf',{editParams: {keys: true, extraparam: {action:'weekID', userID:'123'}}});