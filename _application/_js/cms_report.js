/**
 * Created by Анатоли on 09.06.2016.
 */
$(".clickAccordion").click(function () {
    var _href=$(this).attr("href");
    $(".panel-collapse").removeClass("in");
    _href = _href.substr(1);
    $("#jqReport"+_href).setGridParam({datatype:'json'}).trigger('reloadGrid',[{page:1}]);
})

function getNumberfromString(str) {
    var num = parseInt(str.replace(/\D+/g,""));
    return num;
}

$(document).ready(function () {
    $("#jqReportcol3").setGridParam({datatype:'json'}).trigger('reloadGrid',[{page:1}]);

    $("#m-viewreestr").removeClass("active");
    $("#m-reestr").removeClass("active");
    $("#m-refbook").removeClass("active");
    $("#m-report").addClass("active");
})

$(window).load(function() {
    $(".panel-collapse").each(function( index ) {
        var _id=$(this).attr("id");
        if (_id !="col3") {
            $(this).removeClass("in");
        }
    });
    $("#accordionReport").css("visibility","visible");
});