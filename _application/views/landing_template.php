<html> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>ООО «ЦСМ»</title> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="stylesheet" href="/_js/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="/_js/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/_js/notification/notifit.css">
        <link rel="stylesheet" type="text/css" href="/_application/_css/landing.css">
    </head> 
    <body> 
        <div class="container-fluid menu top-fix">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Навигация</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo" href="#">
                        <img src="/_images/logo.png">
                    </a>
                </div>
                <div class="collapse navbar-collapse navbar-right menu" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="#">Новости</a></li>
                        <li><a href="#">Услуги</a></li>
                        <li><a href="#">Личный кабинет</a></li>
                    </ul>
                </div>
            </nav>
        </div>

            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active">
                        <img data-src="holder.js/100px300/auto/index.htm#777:#555/text:First slide" alt="First slide">
                        <div class="carousel-caption" style="color: black;">
                            <h3>Первый слайд</h3>
                            <p>Первое описание к слайду</p>
                        </div>
                    </div>
                    <div class="item">
                        <img data-src="holder.js/100px300/auto/index.htm#666:#444/text:Second slide" alt="Second slide" >
                        <div class="carousel-caption" style="color: black;">
                            <h3>Второй слайд</h3>
                            <p>Второе описание к слайду, что-то пишем здесь много много</p>
                        </div>

                    </div>
                    <div class="item">
                        <img data-src="holder.js/100px300/auto/index.htm#555:#333/text:Third slide" alt="Third slide" >
                    </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>

        <div class="container-fluid" id="page1" ng-app="PageApp" ng-controller="SecondCtrl">
            <div class="row">
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-6 col-md-10 col-sm-10 col-xs-12 text-center">
                    <h4><p class="text-muted">Ищите среди 60 000 медицинских изделий</p></h4>
                </div>
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-6 col-md-10 col-sm-10 col-xs-12 text-center">
                    <div class="form-group has-success has-feedback">
                        <input type="text" class="form-control " placeholder="Наименование медицинского изделия, производителя или номер РУ" id="searchAll">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                    <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-6 col-md-10 col-sm-10 col-xs-12 text-center">
                    <button type="button" class="btn btn-info btn-lg" id="getFullAccess">Получить полный доступ</button>
                </div>
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
            <div class="row cms-form">
            <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div  class="col-lg-3 col-md-5 col-sm-5 col-xs-6  text-center">
                    <div ng-show="loading">Идет загрузка данных, ожидайте......</div>
                    <div>
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default" ng-repeat="(keytrack, track) in tracks | limitTo : 3" id={{track.id}}>
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{track.id}}"+>
                                            {{track.miName}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{track.id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul ng-controller="MiNames">
                                            <li ng-repeat="mi in mins | filter: { id: keytrack+1 }">{{mi.miName}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5 col-sm-5 col-xs-6  text-center">
                    <div ng-show="loading">Идет загрузка данных, ожидайте......</div>
                    <div>
                        <div class="panel-group" id="accordion2">
                            <div class="panel panel-default" ng-repeat="track in tracks | limitTo : -3" id={{track.id}}>
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse{{track.id}}"+>
                                            {{track.miName}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{track.id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        СПИСКИ
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>             
            </div>
        </div>

        <div class="container-fluid" id="page2">
            <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12 text-center">
                    <div>
                        <h2>О проекте</h2>
                        <p>здесь будет что то о проекте, то что будет!</p>
                    </div>
                </div> 
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
        </div>

        <div class="container-fluid" id="page3">
            <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12 text-center">
                    <div>
                        <h2>О нас</h2>
                    <p>здесь будет что то о НАС!</p>
                        </div>
                </div> 
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
        </div>

        <div class="container-fluid" id="page4">
            <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12 text-center">
                    <div>
                        <h2>Наши клиенты</h2>
                        <p>здесь будут НАШИ клиенты</p>
                    </div>
                </div> 
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
        </div>

        <div class="container-fluid" id="page5">
            <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
                <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12 text-center">
                    <div>
                        <h2>Нужна консультация? Свяжитесь с нами</h2>
                        <p>Мы всегда рады помочь. Обратитесь по телефону, email или в соцсетях.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-info btn-lg" id="feedBack">Связаться со специалистом</button>
                    </div>
                </div> 
                <div class="col-lg-2 col-md-1 col-sm-1 col-xs-0 hidden-xs"> </div>
            </div>
        </div>

        <script type="text/javascript" src="/_js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="/_js/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/_js/notification/notifit.js"></script>
        <script type="text/javascript" src="/_js/bootstrap/js/holder.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.js"></script>
        <script src="_application/_js/angular/model/mi_reestr_main.js"></script>
        <script src="_application/_js/application/landing.js"></script>
    </body>
</html>
