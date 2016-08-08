<div class="row cms-form">
    <div class="col-md-12">
        <div class="input-group">
            <div class="form-group has-success has-feedback">
                <input type="text" class="form-control input-sm" placeholder="Общий поиск" id="searchAll">
                <span class="glyphicon glyphicon-ok form-control-feedback"></span>
            </div>
            <span class="input-group-btn">
                <button class="btn btn-default btn-sm" type="button" id="btnSearchAll"><span class="glyphicon glyphicon-search"></span> Найти</button>
            </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="jqGrid"></table>
        <div id="pager2"></div>
    </div>
</div>
<script type="text/javascript"> 
    var reestrType="viewreestr";
    var filter="<?php echo($filter);?>";
    var filtervalue="<?php echo($value);?>";
</script>
<link rel="stylesheet" type="text/css" href="/_js/datepick/smoothness.datepick.css"> 
<link rel="stylesheet" type="text/css" href="/_js/datepick/ui.datepick.css"> 
<link rel="stylesheet" type="text/css" href="/_js/datepick/ui-south-street.datepick.css">
<script type="text/javascript" src="/_js/datepick/jquery.plugin.js"></script>
<script type="text/javascript" src="/_js/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="/_js/datepick/jquery.datepick-ru.js"></script>
<script type="text/javascript" src="/_application/_js/cms_jview.js"></script>

<style>
    .subjqGrid .ui-jqgrid .ui-jqgrid-bdiv {overflow: hidden !important;}
    .ico-del-cell {
        /*top: 2px;*/
        /*position: absolute;*/
        left: 3px;
        float: left;
    }
    .ui-th-ltr, .ui-jqgrid .ui-jqgrid-htable th.ui-th-ltr {
        position: relative;
    }

    .jview-url {
        color: red !important;
        text-decoration: underline;
    }
</style>