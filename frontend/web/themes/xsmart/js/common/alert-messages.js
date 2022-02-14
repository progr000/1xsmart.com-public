const $translate_text_messages = $('#translate-text-messages');

let FLASH_TIMEOUT = 5000;
let dist_between_flash = 20;

let $alert_block_container = $('#alert-block-container');
let $alert_snackbar_container = $('#alert-snackbar-container');

/**
 * @param {string} message
 * @param {string} type
 * @param {int} timeout
 * @param {object|null} replace
 * @param {string|null} action
 */
function snackbar(message, type, timeout=0, replace=null, action=null)
{
    if (!action) { action = null; }
    let message_text;
    let el = document.getElementById('flash-' + message);
    let m_class = ('class_' + message.hashCode()).replace('-', '');
    //console_log(m_class);
    if (!(el === null)) {
        message_text = $('#flash-' + message).html();
    } else {
        message_text = message;
    }
    if (!(replace === null)) {
        if (typeof replace == 'object') {
            message_text = message_text.replace(/\{([a-zA-Z0-9\_]+)\}/g, function (s, e) {
                return replace[e];
            });
        }
    }

    let $mcsnackbar = $('#alert-template').find('.mc-snackbar').first().clone();
    $mcsnackbar.addClass(m_class);

    $mcsnackbar.find('.mc-snackbar-icon').first()
        .removeClass()
        .addClass('mc-snackbar-icon ' + type);
    let $test = $alert_snackbar_container.find('.' + m_class).first();
    if ($test.length) {
        $test.remove();
    }

    $mcsnackbar.find('.mc-snackbar-title').first().html(message_text);
    $mcsnackbar.show();
    $alert_snackbar_container.append($mcsnackbar);

    if (timeout) {
        $mcsnackbar.delay(timeout).fadeOut(300, function () {
            $(this).remove();
            //$mcsnackbar.remove();
        });
    }

}

/**
 * @param {string} message
 * @param {string} type
 * @param {int} timeout
 * @param {boolean} showClose
 * @param {object|null} replace
 * @param {string|null} action
 */
function flash_msg(message, type, timeout=0, showClose=true, replace=null, action=null, additional_class='')
{
    if (!action) { action = null; }
    //console_log(message);
    let message_text;
    let el = document.getElementById('flash-' + message);
    let m_class = ('class_flash_' + message.hashCode()).replace('-', '');
    //console_log(m_class);
    if (!(el === null)) {
        message_text = $('#flash-' + message).html();
    } else {
        message_text = message;
    }
    if (!(replace === null)) {
        if (typeof replace == 'object') {
            message_text = message_text.replace(/\{([a-zA-Z0-9\_]+)\}/g, function (s, e) {
                return replace[e];
            });
        }
    }

    let $test = $alert_block_container.find('.' + m_class).first();
    if ($test.length) {
        //return;
        $test.remove();
        rePositionFlashMessages();
        //$test.fadeOut(100, function () {
        //    $(this).remove();
        //});
    }

    let init_top = dist_between_flash;
    $alert_block_container.find('.alert').each(function() {
        init_top += parseInt($(this).height()) + dist_between_flash;
    });

    if (init_top == 0) { init_top = dist_between_flash; }
    let $flash = $('#flash-tpl').find('.alert').first().clone();
    $flash.css({top: (init_top) + 'px'});

    $flash.addClass(m_class);
    $flash.addClass('alert-' + type);
    if (additional_class.length) {
        $flash.addClass(additional_class);
    }

    if (!showClose) {
        $flash.find('button').first().remove();
    }

    $flash.find('.flash-message').first().html(message_text);
    $flash.show();
    if (timeout) {
        $flash.delay(timeout).fadeOut(300, function () {
            $(this).remove();
            rePositionFlashMessages();
        });
    }
    //console_log($flash);
    if (timeout >= 0) {
        $alert_block_container.append($flash);
    } else {
        $flash.remove();
    }
}

function rePositionFlashMessages()
{
    let count_flash = 0;
    let init_top = 0;
    let height_prev = 0;
    $alert_block_container.find('.alert').each(function() {

        init_top += height_prev + dist_between_flash;
        $(this).css( { top: (init_top) + 'px' });
        if ($(this)[0].hasAttribute('data-ttl')) {
            if ($(this).attr('data-ttl') > 0) {
                $(this).delay($(this).attr('data-ttl')).fadeOut('slow', function () {
                    $(this).remove();
                    rePositionFlashMessages();

                    if ($(this)[0].hasAttribute('data-auto-close-callback')) {
                        let funct = new Function($(this).attr('data-auto-close-callback'));
                        funct();
                    }

                });
            }
        }
        count_flash++;
        height_prev = parseInt($(this).height());

    });
}

/**
 *  **
 *  **
 */
$(document).ready(function() {

    let test_ft = $('body').data('flash-timeout');
    if (test_ft != 'undefined') {
        FLASH_TIMEOUT = test_ft;
    }

    $(document).on('click', '.close-alert', function() {
        $(this).parent().remove();
        rePositionFlashMessages();
    });

    $(document).on('click', '.mc-snackbar-close', function() {
        $(this).parent().parent().remove();
    });

    rePositionFlashMessages();

});