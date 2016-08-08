<div class="row cms-form">
    <div class="col-md-2"></div>
    <div class="col-md-8">
            <div class="form-group has-success has-feedback">
                <input type="text" class="form-control input-sm" placeholder="Бренды, модели, ключевые слова...." id="searchAll">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
    </div>
    <div class="col-md-2"></div>
</div>

<div class="row cms-form">
    <div ng-app="PageApp" ng-controller="SecondCtrl as second" class="col-md-6">
        <div ng-show="loading">Идет загрузка данных, ожидайте......</div>
        <div>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default" ng-repeat="track in second.tracks | limitTo : 2" id={{track.id}}>
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{track.id}}"+>
                                {{track.miName}}
                            </a>
                        </h4>
                    </div>
                    <div id="collapse{{track.id}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.js"></script>
<script src="_application/_js/angular/model/mi_reestr_main.js"></script>