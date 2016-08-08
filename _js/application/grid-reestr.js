/**/
var _OpisName = function (index, datafield, value, defaultvalue, column, rowdata){
    if (value != '') {
        var re = new RegExp("[a-z\d-]+|[а-яё\d-]+", "gi");
        var myArray = rowdata.col5.match(re);
        var resultStr = '';
        var length = 10;
        if (myArray.length < 10) {
            length = myArray.length;
        }
        for (i = 0; i < length; i++) {
            resultStr = resultStr + myArray[i] + ' ';
        }
        return_opis = '<span style="white-space: normal;" title="' + rowdata.col5 + '">' + resultStr + '</span>';
    } else {
        return_opis='';
    }
    return return_opis;
}

var _Mark = function (index, datafield, value, defaultvalue, column, rowdata) {
    var today = new Date();
    var col4_date = new Date(rowdata.col4);
    var element = $(defaultvalue);
    // console.log(element);
    // console.info(element[0]);
    // console.info(element[0].outerHTML);
    var ret_element= element[0].outerHTML;
    if (today > col4_date) {
        element.css({ 'background-color': 'coral', 'margin-top': '1px','width':'100%','color':'white' });
        ret_element= element[0].outerHTML;
    }
    return ret_element;
};

var complaintTypes = new Array();
var complaintTypesFiltergroup = new $.jqx.filter();

$(document).ready(function () {
    var source =
        {
        datatype: "json",
        datafields: [
            { name: 'col1', type: 'string' },
            { name: 'col2', type: 'string' },
            { name: 'col3', type: 'date', map: 'col3' },
            { name: 'col4', type: 'date' },
            { name: 'col5', type: 'string' },
            { name: 'col6', type: 'string' },
            { name: 'col7', type: 'string' },
            { name: 'col8', type: 'string' },
            { name: 'col9', type: 'string' },
            { name: 'col10', type: 'string' },
            { name: 'col11', type: 'string' },
            { name: 'col12', type: 'string' },
            { name: 'col13', type: 'string' },
            { name: 'col14', type: 'string' },
            { name: 'col15', type: 'string' },
            { name: 'col16', type: 'string' },
            { name: 'col17', type: 'string' }
        ],
        url: '/_application/json/ReestrList.php',
        cache: true,
        pagenum: 0,
        root: 'Rows',
        sortcolumn: 'col3',
        sortdirection: 'desc',
        beforeprocessing: function(data) {
            source.totalrecords = data[0].TotalRows;
        },
        filter: function() {
            $("#jqxgrid_reestr").jqxGrid('updatebounddata', 'filter');
        },
        sort: function() {
            $("#jqxgrid_reestr").jqxGrid('updatebounddata', 'sort');
        },
    };
    // var dataAdapter = new $.jqx.dataAdapter(source);



    var mysettings
    var dataAdapter = new $.jqx.dataAdapter(source, {
        downloadComplete: function(data, status, xhr) {
            if (!source.totalRecords) {
                source.totalRecords = data.length;
            }
        },
        loadError: function(xhr, status, error) {
            throw new Error(error);
        },
        // beforeSend(jqXHR, settings) {
        //     mysettings = settings;
        // }
    });


    $("#jqxgrid_reestr").jqxGrid({
         width: '100%',
         height: '95%',
         columnsheight: 60,
         columnsresize: true,
         source: dataAdapter,
         localization: getLocalization('ru'),
         sortable: true,
         filterable: true,
         showfilterrow: true,
         groupable: true,
         showaggregates: false,
         statusbarheight: 30,
         virtualmode: true,
         pageable: true,
         showstatusbar: false,
         autorowheight: true,
         altrows: true,
         pagesize: 30,
         rendergridrows: function(obj) {
             return obj.data;
         },
         columns: [
             { text: 'Уникальный номер реестровой записи', datafield: 'col1', align: 'center', cellsalign: 'center', width:'5%'}, //,cellsrenderer:_Mark
             { text: 'Регистрационный номер медицинского изделия', datafield: 'col2', align: 'center', cellsalign: 'center', width:'10%'},
             { text: 'Дата государственной регистрации медицинского изделия', datafield: 'col3', align: 'center', cellsalign: 'center', width:'10%',cellsformat: 'dd.MM.yyyy'
                 , filtertype: 'range'},
             { text: 'Срок действия регистрационного удостоверения', datafield: 'col4', align: 'center', cellsalign: 'center', width:'10%',filtertype: 'list',
               filteritems: ['Бессрочно','Только действующие','Отменено','С датами'], filtercondition: 'starts with',cellsformat: 'dd.MM.yyyy' },
             { text: 'Наименование медицинского изделия', datafield: 'col5', align: 'center', cellsalign: 'left', width:'40%'}, // ,cellsrenderer: _OpisName
             { text: 'Наименование организации - заявителя медицинского изделия', datafield: 'col6', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Место нахождения организации-заявителя медицинского изделия', datafield: 'col7', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Юридический адрес организации-заявителя медицинского изделия', datafield: 'col8', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Наименование организации-производителя медицинского изделия или организации-изготовителя медицинского изделия', datafield: 'col9', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Место нахождения организации-производителя медицинского изделия или организации - изготовителя медицинского изделия', datafield: 'col10', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Юридический адрес организации-производителя медицинского изделия или организации - изготовителя медицинского изделия', datafield: 'col11', align: 'center', cellsalign: 'center', width:'20%'},
             { text: 'Код Общероссийского классификатора продукции для медицинского изделия', datafield: 'col12', align: 'center', cellsalign: 'center', width:'10%'},
             { text: 'Класс потенциального риска применения медицинского изделия', datafield: 'col13', align: 'center', cellsalign: 'center', width:'10%'},
             { text: 'Назначение медицинского изделия, установленное производителем', datafield: 'col14', align: 'center', cellsalign: 'center', width:'10%'},
             { text: 'Вид медицинского изделия в соответствии с номенклатурной', datafield: 'col15', align: 'center', cellsalign: 'center', width:'10%'},
             { text: 'Адрес места производства или изготовления медицинского изделия', datafield: 'col16', align: 'center', cellsalign: 'center', width:'30%'},
             { text: 'Сведения о взаимозаменяемых медицинских изделиях', datafield: 'col17', align: 'center', cellsalign: 'center', width:'10%'},
         ],
         ready: function () {
             $('#jqxgrid_reestr').jqxGrid({ pagesizeoptions: ['30','50', '100', '200','500','1000']});
         }
    });
    $("#excelExport").jqxButton();
    $("#excelExport").click(function () {
        var myurl=mysettings.url+'&export=excel';
        var pos = myurl.indexOf('filterscount=0');
        if('-1' != pos) {
            alert ("Экспорт слишком большой? невозможно! \r\n Добавьте фильтры");
        } else {
        $("#jqxgrid_reestr").jqxGrid('exportdata', 'xls', 'jqxGrid', true, null, true, myurl);
        }
        return false;
    });
 //   $("#jqxgrid_reestr").jqxGrid('autoresizecolumns');
 //    $('#jqxgrid_reestr').jqxGrid({ pagerbuttonscount: 15});
 })