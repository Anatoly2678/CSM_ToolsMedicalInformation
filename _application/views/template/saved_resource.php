<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--<html lang="ru-RU" class=" js csstransforms csstransforms3d csstransitions audio">-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ООО «ЦСМ»</title>
<!--<meta name="description" content="">-->
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">-->
<!--<link rel="stylesheet" href="/_css/template/common.css">-->
        <!--<style type="text/css"></style>-->

<!--        <script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="transition" src="/_js/template/transition.js"></script>-->
<!--        <script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="zoom" src="/_js/template/zoom.js"></script>-->
</head>
<!--<body class="landing  hide-advicons hide-video hide-reviewitem hide-maps hide-mainimg hide-counters">-->
<body>
    <div class="section" id="section-0">
            <div class="container">
            <!-- Editor settings -->
                <div class="block block-type-section_styles">
                    <div class="block-content"></div>
		</div>
		<div class="container__row">
                    <div class="container__row mb-50">
                        <!-- Logo -->
                        <div class="col-md-3">
                            <div class="block block-id-global-logo" style="">
                                <img src="<?php echo($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/_images/logo.png'); ?>">
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="col-md-9">
                            <div class="block block-type-header block-id-global-header">
                                <div class="block-content">
                                    <div class="block-text block-type-header-text textcontent">
                                        <p><span class="accenter">8-800-250-22-80</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
		</div>
		<div class="container__row">
                    <div class="block block-type-info block-state-standalone block-id-global-mainform">
                        <div class="block-content">
<!--                            <div class="block-text">
                                <h2 style="text-align: center;">Уникальное предложение!</h2>
                                    <p style="text-align: center; font-size: 28px;">
                                        Предложите посетителям сверхвыгодные условия, <br> от которых они не смогут отказаться
                                    </p>
                                    <p style="text-align: center; font-size: 28px; padding-top:50px;">
                                        <a class="btneditor-button" style="color: #ffffff ! important; border-radius: 5px; background: none repeat scroll 0% 0% #af063c; height: 50px; font-size: 20px; line-height: 50px;" href="http://preview.1438986.setup.ru/#feedback" rel="nofollow">
                                            <span>&nbsp;&nbsp;&nbsp;&nbsp;Подписаться&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </a>
                                    </p>
                                    <p style="text-align: center; font-size: 28px; padding-top:80px;">
                                        <img src="./582b4e44b711e4ae977567c0679388.png" width="70px">
                                    </p>
                            </div>-->
                            <?php include '_application/views/'.$content_view; ?>
                        </div>
                    </div>
                </div>
            </div>
	</div>
<!--        <script type="text/javascript" src="/_js/template/require.js"></script>-->
<!--        <script type="text/javascript" src="/_js/template/landing.min.js"></script>-->
    </body>
</html>