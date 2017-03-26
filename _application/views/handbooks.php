<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
<!--            <li><a href="#words" data-toggle="tab">Слова</a></li>-->
            <li class="active"> <a href="#miUnique" data-toggle="tab">Слова</a></li>
            <li ><a href="#synonyms" data-toggle="tab">Синонимы</a></li>
            <!-- Убрать ! из тэгов -->
            <li class="disabled"><a href="#endof!" data-toggle="tab" >Окончания</a></li>
            <li class="disabled"><a href="#stopwords!" data-toggle="tab">СТОП-слова</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade" id="words">
                <div class="col-md-12" style="padding: 10px 0px;">
                    <table id="grdWords"></table>
                    <div id="pgWords"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="synonyms">
                <div class="row" style="padding: 10px 0;">
                    <div class="col-md-3">
                        <div class="form-group ">
                            <input type="text" class="form-control input-sm" placeholder="Слово (если слова нет, то автоматически попадет в словарь)" id="addWord">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" placeholder="Синонимы, через ;(точка с запятой),если слова нет, то автоматически попадет в словарь" id="addSynonyms">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-plus"></span> Добавить</button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="grdSynonyms"></table>
                        <div id="pgSynonyms"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="endof">
                <div class="row" style="padding: 10px 0;">
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" placeholder="Окончания, через ;(точка с запятой),если слова нет, то автоматически попадет в словарь" id="addEndOf">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-plus"></span> Добавить</button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="grdEndOf"></table>
                        <div id="pgEndOf"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="stopwords">
                <div class="row" style="padding: 10px 0;">
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" placeholder="СТОП-слова, через ;(точка с запятой),если слова нет, то автоматически попадет в словарь" id="addStopWords">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-plus"></span> Добавить</button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="grdStopWords"></table>
                        <div id="pgStopWords"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane active fade in" id="miUnique">
                <div class="row" style="padding: 10px 0;">
                    <div class="col-md-12">
                        <table id="grdMIUnique"></table>
                        <div id="pgMIUnique"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/_application/_js/handbooks/handbooks.js"></script>
<script type="text/javascript" src="/_application/_js/handbooks/words.js"></script>
<script type="text/javascript" src="/_application/_js/handbooks/miunique.js"></script>
<script type="text/javascript" src="/_application/_js/handbooks/synonyms.js"></script>
<script type="text/javascript" src="/_application/_js/handbooks/endof.js"></script>
<script type="text/javascript" src="/_application/_js/handbooks/stopwords.js"></script>
