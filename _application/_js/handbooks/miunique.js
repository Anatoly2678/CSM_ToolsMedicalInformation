jQuery("#grdMIUnique").jqGrid({
    url:'/handbooks/miunuque',
    mtype: "POST",
    datatype: "json",
    postData: {handbooksType: 'MIUnique'},
    colNames:['Ключ','Слово','Приставка','Корень','СВОЙ корень','Соединение','Суффикс','Окончание','Основа','Откл?'],
    colModel:[
        {name:'id',index:'id', align:'center', width:100, searchoptions:{sopt:['eq','le','ge']}, formatter:"integer",sorttype:"int"},
        {name:'word',index:'word', align:'center', width:250, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'prefix',index:'prefix', align:'center', width:100, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'root',index:'root', align:'center', width:150, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'customRoot',index:'customRoot', align:'center', width:150, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'vowel',index:'vowel', align:'center', width:100, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'suffix',index:'suffix', align:'center', width:150, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'ending',index:'ending', align:'center', width:100, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'basis',index:'basis', align:'center', width:250, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true},
        {name:'isExclude',index:'isExclude', align:'center', width:50, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable: true,formatter:'checkbox',edittype:"checkbox",editoptions:{value:"1:0",defaultValue:"1"}},

    ],
    rowNum:100,
    rowList:[20,50,100,200,300],
    pager: '#pgMIUnique',
    sortname: 'word',
    viewrecords: true,
    sortorder: "asc",
    loadonce: true,
    multiselect: false,
    height: '500px',
    width : null,
    shrinkToFit : false,
    forceFit: true,
    ignoreCase: true,
    editurl:'/handbooks/miunuqueupdate',
    loadComplete: function(data) {
        if (jQuery("#grdMIUnique").jqGrid('getGridParam','datatype') === "json") {
            setTimeout(function(){
                jQuery("#grdMIUnique").trigger("reloadGrid");
            },100);
        }
    }
});
// jQuery("#grdMIUnique").jqGrid('sortGrid','prefix', true, 'asc');
jQuery("#grdMIUnique").jqGrid('navGrid','#pgMIUnique',{edit:true,add:false,del:false,search:false},
    {
        afterSubmit: function(){
            $("#grdMIUnique").setGridParam({datatype:'json'}).trigger('reloadGrid',[{page:1}]);
            // console.log (data);
            // var myObject = eval('(' + data.responseText + ')');
            // $('#studentset').setGridParam({data: myObject}).trigger("reloadGrid");
            $(".ui-icon-closethick").trigger('click');
        },
        modal:false,
        jqModal: true
    },
    {},{},{}
);
jQuery("#grdSMIUnique").jqGrid('filterToolbar',{searchOperators : true,autoSearch: false,searchOnEnter : true});
