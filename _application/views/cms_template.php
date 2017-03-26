<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>ООО «ЦСМ»</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/_js/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/_js/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="/_css/smoothness/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="/_css/smoothness/jquery-ui.structure.min.css">
        <link rel="stylesheet" type="text/css" href="/_css/smoothness/jquery-ui.theme.min.css">
        <link rel="stylesheet" type="text/css" href="/JGrid/ui.jqgrid.css">
        <link rel="stylesheet" type="text/css" href="/_application/_css/cms_jview.css">
        
        <script type="text/javascript" src="/_js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="/JGrid/grid.locale-ru.js"></script>
        <script type="text/javascript" src="/JGrid/jquery.jqGrid.js"></script>
        <script type="text/javascript" src="/JGrid/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="/JGrid/jquery.ui.datepicker.js"></script>
        <script type="text/javascript" src="/JGrid/jquery.ui.datepicker-ru.js"></script>
        <script type="text/javascript" src="/_js/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                    <ul class="nav nav-pills">
                        <li id="m-viewreestr"><a href="/viewreestr">ГОС РЕЕСТР МИ</a></li>
                        <li id="m-refbook"><a href="/refbook">НОМЕНКЛАТУРА МИ ПО ВИДАМ</a></li>
                        <li id="m-report"><a href="/report">ОТЧЕТЫ</a></li>
                        <li id="m-wordbymi"><a href="/wordbymi">СЛОВА в МИ</a></li>
                        <li id="m-remove-col" style="display: none">
                            <div class="btn-group" style="margin-top: 5px">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Удаленные столбцы <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu remove-col">
                                    <li role="separator" class="divider"></li>
                                </ul>
                            </div>
                        </li>
                        <li id="m-handbooks"><a href="/handbooks">СПРАВОЧНИКИ</a></li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">
                <?php include '_application/views/'.$content_view; ?>
            </div>
    </body>
</html>
