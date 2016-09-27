/**
 * Created by Анатоли on 19.08.2016.
 */
jQuery("#grdSynonyms").jqGrid({
    url:'/handbooks/synonyms',
    mtype: "POST",
    datatype: "json",
    postData: {handbooksType: 'Synonyms'},
    colNames:['Слово','Синонимы'],
    colModel:[
        {name:'word',index:'word', align:'center', width:200,  searchoptions:{sopt:['eq','cn','bw','bn',,'nc','ew','en']}},
        {name:'synonym',index:'synonym', align:'center', width:800,  searchoptions:{sopt:['cn','eq','bw','bn',,'nc','ew','en']}},
    ],
    rowNum:100,
    rowList:[20,50,100,200,300],
    pager: '#pgSynonyms',
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
jQuery("#grdSynonyms").jqGrid('navGrid','#pgSynonyms',{edit:false,add:false,del:true,search:false});
jQuery("#grdSynonyms").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
// jQuery("#grdSynonyms").trigger("reloadGrid");