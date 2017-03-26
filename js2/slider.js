/*!
 * slider jQuery Plugin v1.0
 * Copyright 2013, pit69
 */

(function($) {
    $.fn.p69Slider = function(options) {
        var settings = $.extend({
            'delay': 2
        }, options);

        var that = this;
        $(window).load(function() {
            that.each(function() {
                var cont = $(this);
                var timer = null;
                var inAct = false;

                var init = function() {
                    cont.find('.load').remove();

                    var nav = cont.find('.navigation');
                    nav.find('li').bind('click.p69Slider', select);
                    nav.find('li').first().click();

                    if (nav.find('li').length > 1) {
                        nav.fadeIn(400);

                        timer = setTimeout(slide, 1000*settings.delay);
                        cont.bind('mouseover.p69Slider', function() {
                            clearTimeout(timer);
                        }).bind('mouseleave.p69Slider', function() {
                            clearTimeout(timer);
                            timer = setTimeout(slide, 1000*settings.delay);
                        });
                    }
                };

                var show = function(id) {
                    var id = id || 1;

                    var selSlide = cont.find('.slider li[data-id="' + id + '"]');
                    var showSlide = cont.find('.slider li.show');

                    if (selSlide.length && selSlide.not('.show') && !inAct) {
                        inAct = true;

                        if (showSlide.length) {
                            showSlide.removeClass('show').css({'z-index': 1}).fadeOut(400);
                        }

                        selSlide.addClass('show').css({'z-index': 2}).fadeIn(400, function() {
                            inAct = false;
                        });

                        return true;
                    } else {
                        return false;
                    }
                };

                var select = function() {
                    var id = $(this).data('id');

                    if (show(id) != false) {
                        cont.find('.navigation li').removeClass('sel');
                        $(this).addClass('sel');
                    }
                };

                var slide = function() {
                    var sel = cont.find('.navigation .sel');

                    if (sel.next().length) {
                        sel.next().click();
                    } else {
                        cont.find('.navigation li').first().click();
                    }

                    timer = setTimeout(slide, 1000*settings.delay);
                };

                init();
            });
        });
        return this;
    };
})(jQuery);