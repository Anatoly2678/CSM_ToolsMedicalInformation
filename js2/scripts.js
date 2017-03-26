

// Пользовательские скрипты
$(document).ready(function() {
    // Куки для мобильной версии
    // $('#footer').find('.nomobile a, .mobile a').click(function() {
    //     if ($.cookie('mbl')) {
    //         $.removeCookie('mbl', {path: '/'});
    //     } else {
    //         $.cookie('mbl', 'no', {path: '/'});
    //     }
    //     location.reload();
    // });

    // // сбор информации о источнике перехода
    if( typeof sbjs == 'object' ) {
        sbjs.init({
            'lifetime': 1
            // 'session_length': 1
        });
        if( typeof sbjs.get != 'object' ) {
            sbjs.get = {
                'current': {}
            }
        }
        var addFields = [];
        addFields.push($('<input>', {'type': 'hidden', 'name': 'type_bpm'}).val(sbjs.get.current.typ || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'utm_source_bpm'}).val(sbjs.get.current.src || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'utm_medium_bpm'}).val(sbjs.get.current.mdm || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'utm_campaign_bpm'}).val(sbjs.get.current.cmp || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'utm_content_bpm'}).val(sbjs.get.current.cnt || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'utm_term_bpm'}).val(sbjs.get.current.trm || ''));
        addFields.push($('<input>', {'type': 'hidden', 'name': 'referral'}).val(sbjs.get.current_add.rf || ''));

        if (location.href.search('utm_') != -1) {
            $('input[name="referral"]').remove();
            addFields.push($('<input>', {'type': 'hidden', 'name': 'referral'}).val(location.href || ''));
        }

        // var dl_utm = $.cookie('dl_utm');
        // if( dl_utm && typeof (dl_utm = JSON.parse(dl_utm)) !== undefined ) {
        //     $('input[name="referral"]').remove();
        //     addFields.push($('<input>', {'type': 'hidden', 'name': 'referral'}).val('http://' + location.host + dl_utm.referral || ''));
        //     // for( var i in dl_utm ) {
        //     //     addFields.push($('<input>', {'type': 'hidden', 'name': i}).val(dl_utm[i] || ''));
        //     // }
        // }

        $('form[action="https://vkarmane-online.bpmonline.com/login.aspx"], form[action="https://vkarmane-online.bpmonline.com/register.aspx"]').append(addFields);
    }

    // // Настройки ajax
    // $.ajaxSetup({
    //     type: 'POST',
    //     url: '/mod/ajax.php',
    //     async: true
    // });

    // // Слайдер
    // $('.main-slider').p69Slider();

    // // Вывод даты
    // function formatDate(date)
    // {
    //     var monthList = new Array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
    //                               'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');
    //     var day   = date.getDate(),
    //         month = date.getMonth(),
    //         year  = date.getFullYear();

    //     return day + ' ' + monthList[month] + ' ' + year;
    // }

    // // Клик на меню
    // $('#header .menu').click(function() {
    //     if ($(this).width() <= 640) {
    //         $(this).toggleClass('sm');
    //     }
    // });

    // Изменение калькулятора
    function changeCalc(calc)
    {         console.log("2222222222");
        var persent = parseFloat(calc.data('persent')) || 0;
        var limits  = calc.data('limits').toString() || '';

        var sum    = parseInt(calc.find('.block-sum .input input').val()) || 0;
        var period = parseInt(calc.find('.block-period .input input').val())  || 0;
        
        calc.find('form input[name="sum"]').val(sum);
        calc.find('form input[name="period"]').val(period);
        
        var total = Math.ceil(sum + (period*sum*persent/100));
        /*
        if (persents) {
            for (var i in persents[period]) {
                if (sum >= i) {
                    persent = parseFloat(persents[period][i]);
                }
            }
            
            total = Math.ceil(sum + (sum*persent/100));
        }
        */
        
        var date  = new Date();
        date.setDate(date.getDate() + period);
        date = formatDate(date);

        calc.find('.block-result .total dd').html(total);
        calc.find('.block-result .date dd').html(date);

        if (limits) {
            limits = limits.replace(' ', '').split(',');

            var text = '';
            var names = new Array('со второго', 'с третьего', 'с четвертого', 'с пятого',
                                  'с шестого', 'с седьмого', 'с восьмого', 'с девятого', 'с десятого');

            for (var i in limits) {
                if (sum >= limits[i]) {
                    text = 'Сумма свыше ' + limits[i] + ' рублей доступна ' + names[i] + ' займа';
                }
            }

            calc.find('.block-result .note').html(text);
        }
    }

    // Калькулятор
    $('.calc').find('.cicle input').each(function() {
        var cicle    = $(this);
        var animFrom = parseInt(cicle.data('min')) || 0;
        var animTo   = parseInt(cicle.val()) || 0;

        cicle.knob({
            release: function(val) {
                $(this.$[0]).closest('.block').find('.input input').val(val);
                changeCalc(cicle.closest('.calc'));
            }
        });

        $({animVal: animFrom}).animate({animVal: animTo}, {
            duration: 1000,
            easing: 'swing',
            step: function() {
                cicle.val(Math.ceil(this.animVal)).trigger('change');
            }
        });
    });

    // Кнопки калькулятора
    $('.calc').find('.plus, .minus').click(function() {
        var cicle = $(this).closest('.block').find('.cicle input');
        var val   = parseInt(cicle.val()) || 0;
        var step  = parseInt(cicle.data('step')) || 0;
        var min   = parseInt(cicle.data('min')) || 0;
        var max   = parseInt(cicle.data('max')) || 0;

        var newVal = ($(this).is('.minus')) ? val - step : val + step;

        if (newVal <= min) {
            newVal = min;
        } else if (newVal > max) {
            newVal = max;
        }

        cicle.val(newVal).trigger('change');
    });

    // Поля ввода калькулятора
    $('.calc').find('.input input').change(function() {
        var min = parseInt($(this).attr('min')) || 0;
        var max = parseInt($(this).attr('max')) || 0;
        var val = parseInt($(this).val()) || 0;

        if (val <= min) {
            $(this).val(min);
            val = min;
        } else if (val > max) {
            $(this).val(max);
            val = max;
        }

        var cicle = $(this).closest('.block').find('.cicle input');
        cicle.val(val).trigger('change');
    });


    // Показать всплывающее окно
    // function showDlg(type) {
    //     var dlg  = $('#' + type + '-dlg');
    //     var form = dlg.find('.form');

    //     dlg.dialog({
    //         width:520,
    //         modal: true,
    //         show: {effect: 'fade', speed: 400},
    //         open: function() {
    //             form.find('.text, .textarea').val('');
    //         },
    //         close: function() {
    //             dlg.dialog('destroy');
    //         }
    //     });
    // }

    // // Инициализация всплывающих окон
    // $('.init-dlg').click(function() {
    //     var type = $(this).data('dlg');
    //     showDlg(type);
    // });

    // if ($('#alert-dlg').length) {
    //     showDlg('alert');
    // }

    // Валидация форм
    // $('.form').submit(function() {
    //     var valid = true;

    //     $(this).find('[required="required"]').each(function() {
    //         if (!$(this).val()) {
    //             $(this).focus();
    //             valid = false;

    //             return false;
    //         }
    //     });

    //     if (valid) {
    //         $(this).find('input[name="spam"]').val('stop');
	   //  ga('send','event','callbackform','submit');
	   //  yaCounter27445353.reachGoal('callbackformsubmit');
    //     } else {
    //         return false;
    //     }
    // });

    // // Выбор файла
    // $('input[name^="files"]').change(function() {
    //     var names = [];

    //     for (var i = 0; i < $(this).get(0).files.length; ++i) {
    //         names.push($(this).get(0).files[i].name);
    //     }

    //     $(this).closest('.file').find('input[name="fileselect"]').val(names);
    // });

    // // Вопрос ответ
    // $('.faq-list').find('.question').click(function() {
    //     $(this).toggleClass('sel').next('.answer').toggle();
    // });

    // // Схлопывающиеся блоки
    // $('.clps-cap').click(function() {
    //     $(this).next('.clps-txt').toggle();
    // });

    // // Окна по хэшу
    // if(window.location.hash == '#new-call') {
    //     window.location.hash = '';
    //     $('.init-dlg[data-dlg="call"]').click();
    // }
    // if(window.location.hash == '#new-letter') {
    //     window.location.hash = '';
    //     $('.init-dlg[data-dlg="letter"]').click();
    // }

});
