let CurrentTimeZone = 0;
let CurrentWeekDay = 1;
let CurrentTimestamp = 0;

let $real_clock = $('#real-clock');
let $real_clock_date = $('#real-clock-date');
let $real_clock_time = $('#real-clock-time');
let $entrance_button = $('#entrance-class-button');
let $minutes_left_to_next_lesson = $('#minutes-left-to-next-lesson');


/**
 * @param value
 * @param tz
 * @returns {{day: number, month: number, year: number, hour: number, min: number, sec: number}}
 */
function getDateData(value=null, tz=null)
{
    let current_date;
    if (value) {
        current_date = new Date(value);
    } else {
        current_date = new Date();
    }
    if (tz !== null) {
        current_date.setTime(current_date.getTime() + current_date.getTimezoneOffset()*60*1000 + tz*1000);
    }
    let day = current_date.getDate();
    if (day < 10) { day = '0' + day; }
    let month = current_date.getMonth() + 1;
    if (month < 10) { month = '0' + month; }
    let year = current_date.getFullYear();
    let hour = current_date.getHours();
    if (hour < 10) { hour = '0' + hour; }
    let min = current_date.getMinutes();
    if (min < 10) { min = '0' + min; }
    let sec = current_date.getSeconds();
    if (sec < 10) { sec = '0' + sec; }

    return {
        day: day,
        month: month,
        year: year,
        hour: hour,
        min: min,
        sec: sec
    }
}

/**
 *
 */
function setRealClock()
{
    if ($real_clock.length) {
        let data = getDateData(null, CurrentTimeZone);
        $real_clock_date.html(`${data.day}/${data.month}/${data.year}`);
        $real_clock_time.html(`${data.hour}:${data.min}:${data.sec}`);

        tmt = setTimeout(function () {
            setRealClock();
        }, 1000);
    }
}

/**
 * @param {int} minutes
 * @returns {*}
 */
const $minutes_translate = $('#minutes-translate');
function left_minutes_ru_text(minutes)
{
    minutes = parseInt(minutes);
    if (minutes >= 10 && minutes <= 20) {
        return $minutes_translate.data('minut'); //'minutes'; //'минут';
    }
    let test = minutes%10;
    if (test == 1) {
        return $minutes_translate.data('minutu'); //'minute'; //'минуту';
    }
    if (test >= 2 && test <= 4) {
        return $minutes_translate.data('minuti'); //'minutes'; //'минуты';
    }
    return $minutes_translate.data('minut'); //'minutes'; //'минут';
}

/**
 *
 */
function recalcLeftToNextLesson() {
    //alert(parseInt(Math.floor(Date.now() / 1000)));
    //console.log('info:: function recalcLeftToNextLesson started');
    $minutes_left_to_next_lesson = $('#minutes-left-to-next-lesson');
    if ($minutes_left_to_next_lesson.length) {

        let $schedule_info_block = $('#schedule-info-block');
        let $minutes_left_text_to_next_lesson = $('#minutes-left-text-to-next-lesson');
        let $lesson_in_progress = $('#lesson-in-progress');
        let $lesson_left_time = $('#lesson-left-time');
        let delta = 30;
        //let seconds_left = parseInt($minutes_left_to_next_lesson.attr('data-seconds-left')) - delta;
        //console.log(seconds_left);
        let seconds_left = parseInt($minutes_left_to_next_lesson.attr('data-lesson-utc-timestamp')) - parseInt(Math.floor(Date.now() / 1000));
        //console.log(seconds_left);
        let minutes_left = parseInt(Math.ceil(seconds_left / 60));

        $minutes_left_to_next_lesson.attr('data-seconds-left', seconds_left);
        $minutes_left_to_next_lesson.html(minutes_left);
        $minutes_left_text_to_next_lesson.html(left_minutes_ru_text(minutes_left));
        if (seconds_left < 60) {
            let $mltb = $('#minutes-left-text-before');
            $mltb.html($mltb.data('translate'));
            //$minutes_left_to_next_lesson.html('');
            $minutes_left_to_next_lesson.html(minutes_left);
            $minutes_left_text_to_next_lesson.html(left_minutes_ru_text(1));
        }

        if (seconds_left > 0) {
            $lesson_in_progress.hide();
            $lesson_left_time.show();
            //setTimeout(recalcLeftToNextLesson, delta * 1000);
        } else {
            $lesson_in_progress.show();
            $lesson_left_time.hide();

            if ($entrance_button.length) {
                $entrance_button
                    .attr('href', $entrance_button.data('href-timeout'))
                    .attr('title', '')
                    .removeClass('void-0')
                    .removeClass('locked')
                    .removeClass('masterTooltip');
            }
            if ($schedule_info_block.length) {
                let test_lesson_skipped = parseInt($schedule_info_block.data('lesson-timeout-skipped')) + seconds_left;
                if (test_lesson_skipped <= -200) {

                    $lesson_in_progress.html($lesson_in_progress.data('lesson-finished'));
                    $lesson_left_time.html($lesson_in_progress.html());
                    $entrance_button
                        .attr('href', '#')
                        .attr('title', $lesson_in_progress.html())
                        .html($lesson_in_progress.html())
                        .addClass('void-0')
                        .addClass('locked')
                        .addClass('masterTooltip');

                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                }
            }
        }

        //if (seconds_left > 0) {
            setTimeout(recalcLeftToNextLesson, delta * 500);
        //}

    }
}

/**
 *
 */
let tmtPjaxNextLesson;
function reloadPjaxToNextLesson() {
    $minutes_left_to_next_lesson = $('#minutes-left-to-next-lesson');
    if (!$minutes_left_to_next_lesson.length) {
        tmtPjaxNextLesson = setTimeout(function() {
            try { $.pjax.reload({container: "#dashboard-next-lesson-time", async: false}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #dashboard-next-lesson-time for reload.'); }
            reloadPjaxToNextLesson();
        }, 10 * 60 * 1000); //каждые 10 минут
    } else {
        clearTimeout(tmtPjaxNextLesson);
        recalcLeftToNextLesson();
    }
}

/**
 *
 */
$(document).ready(function() {

    CurrentWeekDay = $(document.body).data('week-day');
    //console.log('CurrentWeekDay::CurrentWeekDay=', CurrentWeekDay);
    CurrentTimeZone = $real_clock.data('time-zone');
    setRealClock();

    /**/
    if ($entrance_button.length) {
        let allow_entrance_timeout = $entrance_button.data('allow-button-timeout');
        if (allow_entrance_timeout > 0 && allow_entrance_timeout < 2147480) { // нельзя использовать для сетТаймаут число больше чем 2147483647
            setTimeout(function () {
                $entrance_button
                    .attr('href', $entrance_button.data('href-timeout'))
                    .attr('title', '')
                    .removeClass('void-0')
                    .removeClass('locked')
                    .removeClass('masterTooltip');
            }, allow_entrance_timeout * 1000);
        }
    }

    /**/
    if ($('#dashboard-next-lesson-time').length) {
        if ($minutes_left_to_next_lesson.length) {
            console.log('Will be recalc by JS every 30 seconds');
            recalcLeftToNextLesson();
        } else {
            console.log('Will be reloaded by PAJAX every 10 minutes');
            reloadPjaxToNextLesson();
        }
    }

    /**/
    $(document).on('click', '.js-open-popup-schedule', function () {
        $(`#day-${$(this).data('week-day')}`).trigger('click');
    });

    /**/
    $(document).on('click', '.slide-user-menu-btn', function () {
        let $body = $('body');
        let hide_left_menu = !$body.hasClass('has-slide-menu');

        let $hamburger = $(document).find('.hamburger-btn').first();
        if ($hamburger.length && $hamburger.is(':visible')) {
            hide_left_menu = 0;
        }

        let $user_menu_holder = $('.user-menu-holder');
        if (hide_left_menu) {
            $user_menu_holder.find('.user-menu__item').each(function() {
                $(this).addClass('masterTooltip');
                $(this).attr('title', $(this).text());
            });
            initToolTip();
        } else {
            $user_menu_holder.find('.user-menu__item').each(function() {
                $(this).removeClass('masterTooltip');
                $(this).attr('title', '');
            });
        }

        $.cookie('hide_left_menu', hide_left_menu ? 1 : 0);
    });

});