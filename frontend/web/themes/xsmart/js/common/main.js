let IS_GUEST = true;
let USER_ID = null;
let USER_TYPE = 4;
let USER_TYPES = {};
let USER_TIMEZONE = 0;
let _LANG_URL = '';
let _LANGUAGE = 'en';

/**
 *
 */
function isSafari()
{
    return (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0);
}

/**
 *
 */
function hideSiteLoader()
{
    let $preloader = $('#site-loader-div');
    $preloader.delay(500).fadeOut('slow', function() {
        document.body.classList.remove('loaded');
    });
    if (!$preloader.length) {
        document.body.classList.remove('loaded');
    }
    setTimeout(function() {
        //document.body.classList.remove('loaded');
        if ($.cookie('cookie_polices_accept') != 1) {
            $('#cookie-policies-layer').slideDown(400);
        }
    }, 300);
}

/**
 * @returns {number}
 */
String.prototype.hashCode = function() {
    let hash = 0, i, chr;
    if (this.length === 0) return hash;
    for (i = 0; i < this.length; i++) {
        chr   = this.charCodeAt(i);
        hash  = ((hash << 5) - hash) + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
};

/**
 *
 */
function initToolTip()
{
    let $masterTooltip =  $('.masterTooltip');

    $masterTooltip.css({cursor: 'pointer'});
    let Touch = typeof window.ontouchstart != "undefined";
    if (Touch) {
        $masterTooltip.on('touchstart', function (e) {
            $('.tooltip2').remove();
            if ($(this)[0].hasAttribute('title')) {
                let touches = e.originalEvent.touches || [{}],
                    mousex = touches[0].pageX + 8 || 20,
                    mousey = touches[0].pageY - 8 || 20,
                    title = $(this).attr('title');
                if (title.length) {
                    $('<p class="tooltip2"></p>')
                        .text(title)
                        .appendTo('body')
                        .fadeIn('slow');
                    $('.tooltip2')
                        .css({top: mousey, left: mousex});

                    setTimeout(function () {
                        $('.tooltip2').fadeOut('slow', function () {
                            $('.tooltip2').remove();
                        })
                    }, 6000);
                }
            }
        });
    } else {
        $masterTooltip.hover(function () {
            // Hover over code
            if ($(this)[0].hasAttribute('title')) {
                let title = $(this).attr('title');
                //obj = $(this);
                $(this).data('tipText', title).removeAttr('title');
                if (title.length) {
                    $('<p class="tooltip2"></p>')
                        .html(title)
                        .appendTo('body')
                        .fadeIn('slow');
                }
            }
            if ($(this)[0].hasAttribute('value')) {
                let title = $(this).val();
                //obj = $(this);
                if (title.length) {
                    $('<p class="tooltip2"></p>')
                        .html(title)
                        .appendTo('body')
                        .fadeIn('slow');
                }
            }
        }, function () {
            // Hover out code
            if ($(this).data('tipText')) {
                $(this).attr('title', $(this).data('tipText'));
            }
            $('.tooltip2').remove();
        }).mousemove(function (e) {
            let mousex = e.pageX + 5; //Get X coordinates
            let mousey = e.pageY + 5; //Get Y coordinates
            $('.tooltip2')
                .css({top: mousey, left: mousex});
            if ($(this)[0].hasAttribute('value')) {
                $('.tooltip2').html($(this).val());
            }
        });
    }
}

/**
 * Pretty-Confirm-Window
 *
 * @param {function|boolean} funct_yes
 * @param {function|boolean} funct_no
 * @param {string} question
 * @param {string} button_yes
 * @param {string} button_no
 * @param {boolean} show_close_x
 */
function prettyConfirm(funct_yes=false, funct_no=false, question="", button_yes="", button_no="", show_close_x=false)
{
    let $pretty_confirm_modal = $('#pretty-confirm-modal');
    /* Устанавливаем текст вопроса для конфирма */
    if (question && typeof question == 'string' && $.trim(question) != '') {
        $('#pretty-confirm-question-text').html(question);
    }

    /* Устанавливаем текст для кнопки НЕТ */
    if ($.trim(button_no) != "") {
        $('#button-confirm-no').val(button_no);
    }

    /* Устанавливаем текст для кнопки ДА */
    if ($.trim(button_yes) != "") {
        $('#button-confirm-yes').val(button_yes);
    }

    /* если show_close_x true тогда показать крестик закрытия окна */
    if (show_close_x) {
        $('#confirm-close-x').show();
    } else {
        $('#confirm-close-x').hide();
    }

    if (typeof funct_yes == 'function') {
        /* Навешиваем событие на нажатие YES */
        $('.button-confirm-yes')
            .off("click")
            .on('click', function() {
                $('#button-confirm-yes').off("click");
                $('#button-confirm-no').off("click");
                $pretty_confirm_modal.css({ 'z-index': 0 });
                funct_yes();
            });
    } else {
        $('.button-confirm-yes')
            .off("click")
            .on('click', function() {
                $('#button-confirm-yes').off("click");
                $('#button-confirm-no').off("click");
                $pretty_confirm_modal.css({ 'z-index': 0 });
            });
    }

    /* Навешиваем событие на нажатие NO */
    if (typeof funct_no == 'function') {
        $('.button-confirm-no')
            .off("click")
            .on('click', function() {
                $('#button-confirm-yes').off("click");
                $('#button-confirm-no').off("click");
                $pretty_confirm_modal.css({ 'z-index': 0 });
                funct_no();
            });
    } else {
        $('.button-confirm-no')
            .off("click")
            .on('click', function() {
                $('#button-confirm-yes').off("click");
                $('#button-confirm-no').off("click");
                $pretty_confirm_modal.css({ 'z-index': 0 });
            });
    }

    /* Показываем попап конфирмации */
    $pretty_confirm_modal.addClass('_opened').css({ 'z-index': 99998 });
}

function prettyConfirm_v2(question="", button_yes="", button_no="", show_close_x=false)
{
    let $pretty_confirm_modal = $('#pretty-confirm-modal');
    $pretty_confirm_modal.addClass('_opened').css({ 'z-index': 99998 });

    $('.button-confirm-yes')
        .off("click")
        .on('click', function() {
            $('#button-confirm-yes').off("click");
            $('#button-confirm-no').off("click");
            $pretty_confirm_modal.css({ 'z-index': 0 });
            funct_yes();
        });
}

/**
 * @param {string} text
 * @param {function|boolean} funct_ok
 * @param {boolean} show_close_x
 * @param {string} button_ok_text
 */
function prettyAlert(text, funct_ok=false, show_close_x=true, button_ok_text="Ok")
{
    let $pretty_alert_modal = $('#pretty-alert-modal');
    /* Навешиваем событие на нажатие OK если оно есть */
    if (funct_ok && typeof funct_ok == 'function') {
        $('.button-alert-ok')
            .off("click")
            .on('click', function() {
                //$('#button-confirm-yes').off("click");
                //$('#button-confirm-no').off("click");
                $('#pretty-alert-modal-text').html('');
                $pretty_alert_modal.css({ 'z-index': 0 });
                funct_ok();
            });
    } else {
        $('.button-alert-ok')
            .off("click")
            .on('click', function() {
                //$('#button-confirm-yes').off("click");
                //$('#button-confirm-no').off("click");
                $('#pretty-alert-modal-text').html('')
                $pretty_alert_modal.css({ 'z-index': 0 });
            });
    }

    /* если show_close_x true тогда показать крестик закрытия окна */
    if (show_close_x) {
        $('#pretty-alert-close-x').show();
    } else {
        $('#pretty-alert-close-x').hide();
    }

    /* Устанавливаем текст кнопки OK*/
    $('#pretty-alert-button-ok').html(button_ok_text);

    /* Устанавливаем текст для алерта */
    $('#pretty-alert-modal-text').html(text);

    /* Показываем попап алерта */
    //$("#trigger-pretty-alert-modal").trigger( "click" );
    $pretty_alert_modal.addClass('_opened').css({ 'z-index': 99999 });
}

/**
 *
 */
$(document).ready(function() {

    /* язык */
    let $body_doc = $('body');
    let body_lang = $body_doc.attr('lang');
    if (typeof body_lang != 'undefined') {
        _LANGUAGE = body_lang;

        let url = window.location.href;
        let a = $('<a>', { href: url});
        //a.prop('protocol'); a.prop('hostname'); a.prop('search'); a.prop('hash');
        let path = a.prop('pathname');

        let regexp = new RegExp('(^/' + _LANGUAGE + '\$)|(^/' + _LANGUAGE + '/)');
        //console_log(regexp.test(path));
        if (regexp.test(path)) {
            _LANG_URL = '/' + _LANGUAGE;
        }
    }

    /* глобально можно поменять некоторые параметры аджакс перед отправкой
    (тут меняем урл для языковой версии и можем добавить любой доп-параметр) */
    $.ajaxSetup({
        beforeSend: function(jqXHR, settings) {
            settings.url = _LANG_URL + settings.url;
        },
        //data: {
        //    _any_token: 'i_am_any_token'
        //}
    });

    /* глобально для ошибки аджакс запросов */
    $(document).ajaxError(function (event, xhr, ajaxOptions, thrownError) {

        /* если ошибку вызвал запрос сохранения логов
         * или если непонятно какой запрос (нет параметра ajaxOptions.url)
         * то во избежание бесконечной рекурсии просто выход из этой функции
         */
        if ( !ajaxOptions || !("url" in ajaxOptions) || (ajaxOptions.url == '/site/store-js-console-log') ) {
            return void(0);
        }

        /* признак что аякс выролнен логером, тогда нельзя запускать console.log и подобное
         * просто выход из функции этой
         * */
        if ("is_logger" in ajaxOptions) {
            return void(0);
        }

        /**/
        if (xhr && ("status" in xhr) && ("statusText" in xhr) && ("responseText" in xhr)) {
            switch (xhr.status) {
                case 403:
                    console.log('Forbidden 403. Will be redirected to the main page.');
                    console.log(ajaxOptions);
                    //window.location.href = '/';
                    break;
                case 404:
                    console.log('Not Found 404. Will be redirected to the main page.');
                    console.log(ajaxOptions);
                    //window.location.href = '/';
                    break;
                case 500:
                    console.log(xhr.status);
                    console.log(xhr.statusText);
                    console.log(xhr.responseText);
                    console.log(ajaxOptions);
                    //prettyAlert('An internal (500) server error occurred.');
                    break;
                default:
                    console.log(xhr.status);
                    console.log(xhr.statusText);
                    console.log(xhr.responseText);
                    console.log(ajaxOptions);
                    //prettyAlert('An unknown (' + xhr.status + ') server error occurred. <br />Url: ' + ajaxOptions.url);
                    break;
            }
        }
    });

    /* таймзона */
    let u_tmp = new Date();
    USER_TIMEZONE = u_tmp.getTimezoneOffset() * -60;
    if ($.cookie('utz_is_set') === undefined) {
        console.log(USER_TIMEZONE);
        $.ajax({
            type: 'get',
            url: '/site/set-user-timezone',
            data: {utz: USER_TIMEZONE},
            dataType: 'json'
        }).done(function (response) {
            if ("status" in response && response.status) {
                $.cookie('utz_is_set', 1, { path: '/' });
                if ($('#tutor-info-for-js-tz-reload').length) { window.location.reload(); } // reload page for correct tz
                console.log('User-Timezone successfully set.');
            } else {
                console.log(response);
            }
        });
    }

    /**/
    initToolTip();

    /**/
    if (window.location.href.indexOf('#') > 0) {
        let tmp = window.location.href.split('#');
        if (typeof tmp[1] != 'undefined') {
            let $target = $(`#${tmp[1]}`);
            if ($target.length) {
                setTimeout(function() {
                    let destination = $target.offset().top;
                    $('html, body').animate({scrollTop: destination}, 1100);
                }, 1000);
            }
        }
    }

    /**/
    $(document).on('click', '.void-0', function () {
        return false;
    });

    /**/
    $(document).on('click', '.js-alert', function () {
        prettyAlert($(this).data('alert-text'));
    });

    /**/
    if (window.location.href.indexOf('login') > 0) {
        if (IS_GUEST) {
            let $el = $(document).find('.auth-btn--login').first();
            if ($el.length) {
                $el[0].click();
            }
        }
    }

    /**/
    if (window.location.href.indexOf('signup') > 0) {
        if (IS_GUEST) {
            let $el = $(document).find('.auth-btn--signup').first();
            if ($el.length) {
                $el[0].click();
            }
        }
    }

    /**/
    if (window.location.href.indexOf('request-password-reset') > 0) {
        if (IS_GUEST) {
            let $el = $(document).find('.btn--forgot-pass').first();
            if ($el.length) {
                $el[0].click();
            }
        }
    }

    /**/
    $('#class-room-iframe').height($(window).height() - 160);

    /**/
    $(document).on('click', '.js-open-pdf-modal', function () {
        $('#pdf-title').html($(this).data('title'));
        $('#pdf-iframe').attr('src', $(this).data('content'));
    });

    /**/
    $(document).on('click', '.js-close-pdf-modal', function () {
        $('#pdf-title').html('{title}');
        $('#pdf-iframe').attr('src', '');
    });

    /**/
    $(document).on('click', '.signup-as-tutor-button', function () {
        let $rdb = $(document).find('.signup-as-tutor').first();
        $rdb[0].click();
    });

    /**/
    $(document).on('click', '.js-send-contact-us-request', function () {

        let $this_button = $(this);
        let $this_button_text = $this_button;

        let $form = $(`#${$(this).data('form-id')}`);

        $form.yiiActiveForm('data').submitting = true;
        $form.yiiActiveForm('validate');


        window.setTimeout(function () {
            //$form.yiiActiveForm('validate');
            if ($form.find('.has-error').length) {
                flash_msg($translate_text_messages.attr('data-msg-3'), 'error', FLASH_TIMEOUT);
                //$this_button.removeClass('in-progress');
                //$this_button_text.html($this_button.data('ready-to-send'));
                return false;
            }

            $this_button.addClass('in-progress');
            $this_button_text.html($this_button.data('sent-in-progress'));

            let inp_obj = {};
            $form.find('.js-request-inputs').each(function () {
                let $el = $(this);
                inp_obj[$el.attr('name')] = $el.val();
                if ($(this).hasClass('js-request-select')) {
                    inp_obj[$el.attr('name') + '_text'] = $(this).find('option:selected').first().text();
                }
            });

            console.log('student-request::inp_obj=', inp_obj);
            $.ajax({
                type: 'post',
                url: '/site/save-contact-us-form',
                data: inp_obj,
                dataType: 'json'
            }).done(function (response) {

                if ("status" in response && response.status) {

                    //$('#request-form-sent-link')[0].click();
                    $form.find('.js-request-inputs').each(function () {
                        if ($(this).attr('type') != 'hidden') {
                            $(this).val('');
                        }
                    });

                    flash_msg(response.info, 'success', FLASH_TIMEOUT);

                } else {
                    console.log(response);
                    //prettyAlert($translate_text_messages.attr('data-msg-15'));
                }
                $this_button.removeClass('in-progress');
                $this_button_text.html($this_button.data('ready-to-send'));

            }).fail(function (response) {
                $this_button.removeClass('in-progress');
                $this_button_text.html($this_button.data('ready-to-send'));
            });

        }, 1000);

    });

    /**/
    $(document).on('click', '.cookie-layer__button', function () {
        $('#cookie-policies-layer').slideUp(400);
        $.cookie('cookie_polices_accept', "1", { expires: 365, path: '/' });
    });

});