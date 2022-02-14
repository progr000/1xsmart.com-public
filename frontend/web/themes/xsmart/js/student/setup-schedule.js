let $date_start = $('#date-start-input');
let $schedule_conf = $('#set-schedule-step-conf');
let currentStepNum;

/**
 * @param {int} num
 * @returns {*}
 */
function showStep(num)
{
    $('.set-schedule-steps').hide();
    $(`.set-schedule-step-${num}`).show();
    window.scroll(0, 0);

    let funct = `after_show_step_${num}`;
    if (typeof window[funct] == 'function') {
        return window[funct]();
    }

    return void(0);
}

/**
 * @returns {*}
 */
function after_show_step_3()
{
    try {
        $('#day-1').trigger('click');
    } catch (e) {

    }
    return void(0);
}

/**
 * @param {int} num
 */
function changeStep(num)
{
    let funct = `before_change_step_${num}`;
    if (typeof window[funct] == 'function') {
        if (!window[funct]()) {
            return false;
        }
    }

    if (typeof user_schedule == 'undefined' && num > 2) {
        prettyAlert($translate_text_messages.attr('data-msg-20'));
        return false;
    }

    $.ajax({
        type: 'get',
        url: '/student/change-step',
        data: {
            step: num,
            date_start: $date_start.val()
        },
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response && response.status) {

            if (response.data.changed) {

                showStep(num);

            } else {
                prettyAlert(response.data.info);
            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });

    return true;
}

/**
 * @returns {boolean}
 */
function before_change_step_3()
{
    let test = $.trim($date_start.val());
    if (test == '') {
        prettyAlert($schedule_conf.data('check-error-3'));
        return false;
    }

    return true;
}

/**
 * @returns {boolean}
 */
function before_change_step_4()
{
    if (total_selected_count_lesson == 0) {
        prettyAlert($schedule_conf.data('check-error-4'));
        return false;
    }

    return true;
}

/**
 *
 */
function selectDateOnDatePicker()
{
    if ($date_start.length) {
        //console.log($date_start.val());
        let test = $date_start.val().split('-');
        //console.log(test);
        let $day = parseInt(test[2]),
            $month = parseInt(test[1]) - 1,
            $year = parseInt(test[0]);

        let $click_el;
        $('#begining-calendar').find('.datepicker--cell-day').each(function () {
            //console.log($(this));
            $(this).removeClass('-selected-');
            if ($(this).data('date') == $day && $(this).data('month') == $month && $(this).data('year') == $year) {
                $(this).addClass('-selected-');
            }
        });
    }
}

/**
 *
 */
function getMyTeacherSchedule()
{
    $.ajax({
        type: 'get',
        url: '/student/get-my-teacher-schedule',
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response && response.status) {

            user_schedule = response.data;
            //console.log(user_schedule);
            for (let i = 0; i < user_schedule.length; i++) {
                //console.log(user_schedule[i]);
                for (let j = 0; j < user_schedule[i].length; j++) {

                    let $ch = $(`#time-${j}-${i}`);
                    if ($ch.length) {
                        if (user_schedule[i][j].status > 0) {
                            $(`#day-${i}`).addClass('_active');
                            //console.log(`#time-${j}-${i}`);
                        } else {
                            //$(`#day-${i}`).removeClass('_active');
                        }
                        //console.log(`${i} -- ${j} == ${user_schedule[i][j]}`);
                        //console.log(`#time-${j}-${i}`);
                        //console.log($(`#time-${j}-${i}`).length);
                        $ch[0].disabled = (user_schedule[i][j].status == 0 /*|| user_schedule[i][j].status == 2*/);
                        if ("users_json" in user_schedule[i][j]) {
                            let user_json = user_schedule[i][j].users_json;
                            for (let uj = 0; uj < user_json.length; uj++) {
                                //alert(user_json[uj].user_id);
                                if (user_json[uj].user_id === null) {
                                    // значит доступная ячейка - свободная (белая)
                                } else if (user_json[uj].user_id == USER_ID) {
                                    // значит доступная ячейка - свободная (синяя)
                                    total_selected_count_lesson++;
                                    $ch[0].checked = true;
                                } else {
                                    // ячейка занята другим юзером
                                    $ch[0].disabled = true;
                                }
                            }
                        }
                        //if (user_schedule[i][j].status == 2) {
                        //    $ch.addClass('_active');
                        //    $ch.attr('data-students', user_schedule[i][j].users)
                        //}
                    }
                }
            }

            /**/
            //if (typeof getCountLessonsLeftToDistribute == 'function') {
            //    getCountLessonsLeftToDistribute(null)
            //}


        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }
    });
}

/**
 *
 */
function generateTimelinesAfterSetupSchedule()
{
    /**/
    if (total_selected_count_lesson == 0) {
        prettyAlert($schedule_conf.data('check-error-4'));
        return false;
    }

    /**/
    $.ajax({
        type: 'get',
        url: '/student/generate-timelines-after-setup-schedule',
        data: {
            //step: num,
            date_start: $date_start.val()
        },
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response) {
            if (response.status) {

                console.log(response.data);
                $('#choose-teacher-modal').find('.js-close-modal').first().trigger('click');
                let $thnx =  $('#thnx-msg-step5');
                let tmp = $thnx.html();

                let replaced =  tmp.replace(/\{([a-z\_]+)\}/g, function (s, e) {
                    return response.data[e];
                });

                $thnx.html(replaced);

                $('#teacher-user_photo')[0].src = response.data.user_photo;

                showStep(5);

            } else {

                prettyAlert(response.data);
                console.log(response.data);

            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }
    });

    return true;
}

/** ***** **/
$(document).ready(function() {

    /* попробуем перевести данные для календаря, если есть перевод */
    if (TRANSLATE_CALENDAR[_LANGUAGE] !== undefined) {
        //console.log(_LANGUAGE);
        //console.log($.fn.datepicker.language.en);
        $.fn.datepicker.language.en = TRANSLATE_CALENDAR[_LANGUAGE];
    }

    /* попробуем получить расписание ученика, если оно есть */
    //getSchedule();
    getMyTeacherSchedule();

    /* если есть элемент который указывает нам текущий день недели, то переключим расписание на этот день недели */
    let $test = $('#main-schedule-container');
    if ($test.length && typeof $test.data('current-week-day') != 'undefined') {
        $(`#day-${$test.data('current-week-day')}`).trigger('click');
    }

    /* создадим календарь для выбора даты начала занятий */
    let maxCountDayAfterStart = 41;
    let dEnd = new Date();
    //dEnd.setMonth(dEnd.getMonth() + 3);
    dEnd.setDate(dEnd.getDate() + maxCountDayAfterStart);
    let $dp = $('#my-js-big-datepicker').datepicker({
        inline: true,
        language: 'en',
        dateFormat: 'yyyy-mm-dd',
        classes: 'big-datepicker',
        navTitles: {
            days: 'MM',
            months: 'MM',
            years: 'yyyy'
        },
        prevHtml: '<svg class="svg-icon" width="11" height="20"><use xlink:href="#left-2"></use></svg>',
        nextHtml: '<svg class="svg-icon" width="11" height="20"><use xlink:href="#right-2"></use></svg>',
        startDate: new Date($date_start.val()),
        defaultDate: new Date($date_start.val()),
        minDate: new Date(),
        maxDate: dEnd
    });
    setTimeout(function() {
        selectDateOnDatePicker();
    }, 100);

    /* покажем первый шаг */
    showStep(parseInt($schedule_conf.data('current-step')));

    /* переход на второй шаг при выборе количества предпочитаемых занятий в неделю */
    $(document).on('click', 'input[name=days-count]', function () {
        changeStep(2);
    });

    /* смена шагов при клике на соответствующую кнопку */
    $(document).on('click', '.step__nav-btn', function () {
        if (typeof $(this).data('check') !== 'undefined') {
            let funct = $(this).data('check');
            if (typeof window[funct] == 'function') {
                let test = window[funct]();
                if (!test) {
                    prettyAlert($(this).data('check-error'));
                    return false;
                }
            }
        }
        changeStep($(this).data('step'));
    });

    $(document).on('click', '.step__nav-btn-final', function () {
        generateTimelinesAfterSetupSchedule()
    });

    /* переход на третий шаг в случае клика на кнопку даты "Незамедлительно" */
    $(document).on('click', '.date-start-immediately', function () {
        //$('#date-start-select-calendar').hide();
        let d = new Date();
        let Y = d.getFullYear();
        let M = d.getMonth() + 1;
        let D = d.getDate();
        M = (M<10) ? '0' + M : M;
        D = (D<10) ? '0' + D : D;
        let d_str = Y + '-' + M + '-' + D;
        $date_start.val(d_str);
        changeStep(3);
    });

    /* обработка календаря при его открытии когда клик на кнопке выбора даты вручную */
    $(document).on('click', '.date-start-manually', function () {
        //$('.datepicker--cell-day').removeClass('-selected-');
        //$date_start.val('');
        selectDateOnDatePicker();
    });

    /* обработка клика на конкретной дате календаря */
    $(document).on('click', '.datepicker--cell-day', function() {
        if (!$(this).hasClass('-selected-')) {
            if (!$(this).hasClass('-disabled-')) {
                let day = parseInt($(this).data('date'));
                let month = parseInt($(this).data('month')) + 1;
                let year = parseInt($(this).data('year'));
                if (day < 10) {
                    day = '0' + day;
                }
                if (month < 10) {
                    month = '0' + month;
                }

                $date_start.val(year + '-' + month + '-' + day);
            }
        } else {
            $date_start.val('');
        }
    });

    /* установка или снятие отметки с конкретного часа в расписании */
    $(document).on('change', '.js-beginning-time', function() {
        changeWorkHour($(this));
    });

    /* открытие детальной информации об учителе */
    $(document).on('click', '.js-open-coach-details', function() {
        let $this = $(this);
        let $video_about_teacher = $('#video-about-teacher');
        let $video_about_teacher_local = $('#video-about-teacher-local');

        let $coach_detail_modal = $('#choose-teacher-modal');
        $coach_detail_modal.find('.teacher_user_id').first().attr('data-teacher_user_id', $this.data('teacher_user_id'));
        $coach_detail_modal.find('.user_photo').first().attr('src', $this.data('user_photo'));
        $coach_detail_modal.find('.user_first_name').first().html($this.data('user_first_name'));
        $coach_detail_modal.find('.user_age').first().html($this.data('user_age'));
        $coach_detail_modal.find('.user_music_genres').first().html($this.data('user_music_genres'));
        $coach_detail_modal.find('.user_additional_info').first().html($this.data('user_additional_info'));
        $coach_detail_modal.find('.admin_notice').first().html($this.data('admin_notice'));


        if ($this.data('youtube_video') !== null && $this.data('youtube_video') != 'null') {

            //alert($this.data('youtube_video'));
            //let $coach_detail_modal = $(`#${$(this).data('modal-id')}`);
            $coach_detail_modal.find('.youtube_video').first().attr('href', $this.data('youtube_video'));
            $coach_detail_modal.find('.youtube_image_webp').first().attr('srcset', $this.data('youtube_image_webp'));
            $coach_detail_modal.find('.youtube_image_jpg').first().attr('src', $this.data('youtube_image_jpg'));
            $coach_detail_modal.find('.youtube_video_id').first().attr('data-youtube_video_id', $this.data('youtube_video_id'));

            $video_about_teacher_local.hide();
            $video_about_teacher.show();
            setupTeacherVideo();

        } else if ($this.data('user_local_video') != 'null') {

            //alert(2);
            $('#user-local-video')[0].src = $this.data('user_local_video');
            $video_about_teacher.hide();
            $video_about_teacher_local.show();

        } else {
            $video_about_teacher.hide();
            $video_about_teacher_local.hide();
        }

        /**/
        //console.log($this[0].dataset);
        //jQuery.each($this[0].dataset, function (key, val) {
        //    $coach_detail_modal.find(`.${key}`).first().html(val);
        //});
    });

});
