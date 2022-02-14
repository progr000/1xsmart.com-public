let total_selected_count_lesson = 0;
let user_schedule;


/**
 *
 */
function getSchedule()
{
    $.ajax({
        type: 'get',
        url: '/user/get-schedule',
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response && response.status) {

            user_schedule = response.data;
            //console.log(user_schedule);
            for (let i = 0; i < user_schedule.length; i++) {
                //console.log(user_schedule[i]);
                for (let j = 0; j < user_schedule[i].length; j++) {
                    total_selected_count_lesson += (user_schedule[i][j].status > 0) ? 1 : 0;
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
                        $ch[0].checked = (user_schedule[i][j].status > 0);
                        if (user_schedule[i][j].status == 2) {
                            $ch.addClass('_active');
                            $ch.attr('data-students', user_schedule[i][j].users)
                        }
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
 * @param {object} $obj
 */
function changeWorkHour($obj)
{
    //console.log($obj[0].checked); return false;
    //if (typeof MAX_LESSONS_FOR_WEEK !== 'undefined') {
    //    if (total_selected_count_lesson >= MAX_LESSONS_FOR_WEEK) {
    //        prettyAlert('Нельзя выбрать больше ' + MAX_LESSONS_FOR_WEEK + ' занятий');
    //        return false;
    //    }
    //}

    /**/
    let hour_status;
    let work_hour = $obj.data('hour');
    let week_day  = $obj.data('day');
    //alert($obj[0].checked);
    if ($obj[0].hasAttribute('type') && $obj[0].type == 'checkbox') {
        if ($obj[0].checked) {
            hour_status = 1;
        } else {
            hour_status = 0;
        }
    } else {
        if ($obj.hasClass('schedule__booking-time--free')) {
            hour_status = 0;
        } else {
            hour_status = 1;
        }
    }

    let data = {
        week_day: week_day,
        work_hour: work_hour,
        hour_status: hour_status
    };
    if ((typeof $date_start !== 'undefined') && $date_start.length) {
        data.date_start = $date_start.val();
    }
    //setPendingData(
    //    `Обработка запроса...`,
    //    'working',
    //    10000
    //);
    $.ajax({
        type: 'get',
        url: '/user/change-schedule',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response && response.status) {

            if (response.data.changed) {
                if (hour_status == 1) {

                    //$(`.schedule-${week_day}-${work_hour}`).addClass('schedule__booking-time--free');
                    $('.js-schedule-carousel').find(`.schedule-${week_day}-${work_hour}`).each(function() {
                        if ($(this).hasClass('_locked-out-of-date')) {
                            $(this).addClass('_disabled');
                        }
                        if (!$(this).hasClass('_disabled')) {
                            $(this)
                                .addClass('schedule__booking-time--free')
                                .attr('data-tooltip', "Unset");
                        }
                    });

                    $(`.sch-time-${work_hour}`).removeClass('_hidden');
                    //$obj.addClass('active');
                    //user_schedule[week_day][work_hour].status = 1;
                    total_selected_count_lesson++;
                    //setPendingData(
                    //    `Вы успешно добавили новое время в свое расписание`,
                    //    'success',
                    //    10000
                    //);
                } else {

                    let $sch = $(`.schedule-${week_day}-${work_hour}`);
                    $sch
                        .removeClass('schedule__booking-time--free')
                        .attr('data-tooltip', "Set");

                    $('.js-schedule-carousel').find(`.schedule-${week_day}-${work_hour}`).each(function() {
                        if ($(this).hasClass('_locked-out-of-date') || $(this).hasClass('_disabled')) {
                            $(this).removeClass('_disabled');
                        }
                    });

                    let allow_hidden = true;
                    $('.js-schedule-carousel').find(`.sch-time-${work_hour}`).each(function() {
                        if ($(this).hasClass('schedule__booking-time--free') ||
                            $(this).hasClass('lesson_scheduled') ||
                            $(this).hasClass('_active') ||
                            $(this).hasClass('_disabled')) {
                            allow_hidden = false;
                        }
                    });
                    if (allow_hidden) {
                        $(`.sch-time-${work_hour}`).addClass('_hidden');
                    }
                    //$obj.removeClass('active');
                    //user_schedule[week_day][work_hour].status = 0;
                    total_selected_count_lesson--;
                    //setPendingData(
                    //    `Вы успешно отменили время в своем расписании`,
                    //    'success',
                    //    10000
                    //);
                }
            } else {
                prettyAlert(response.data.info);
                $obj[0].checked = !$obj[0].checked;
                //setPendingData(
                //    response.data.info,
                //    'danger',
                //    10000
                //);
            }

            /**/
            try { $.pjax.reload({container: "#dashboard-schedule-list", async: false}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #dashboard-schedule-list for reload.'); }
            try { $.pjax.reload({container: "#popup-dashboard-schedule-list", async: false}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #popup-dashboard-schedule-list for reload.'); }
            if (typeof changeAvailableWeek == 'function') {
                changeAvailableWeek(parseInt($('#which-show-available-week').attr('data-show-available-week')));
            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

        //let total_check = 0;
        //for (let j = 0; j < user_schedule[week_day].length; j++) {
        //    total_check += (user_schedule[week_day][j].status > 0) ? 1 : 0;
        //}
        //if (total_check > 0) {
        //    $(`#day-${week_day}`).addClass('_active');
        //} else {
        //    $(`#day-${week_day}`).removeClass('_active');
        //}

        /* for teacher */
        if (USER_TYPE == USER_TYPES['TYPE_TEACHER']) {
            let $button_send_to_approve = $('#button-send-to-approve');
            if (!$('.js-schedule-carousel').find('.schedule__booking-time--free').length) {
                $('#teacher-steps-approve').removeClass('hidden');
                $('#step-schedule').removeClass('_completed');
                $button_send_to_approve.addClass('primary-btn--neutral');
                $button_send_to_approve[0].disabled = true;
            } else {
                $('#step-schedule').addClass('_completed');
                if ($('#step-info').hasClass('_completed') && $('#step-video').hasClass('_completed')) {
                    $button_send_to_approve.removeClass('primary-btn--neutral');
                    $button_send_to_approve[0].disabled = false;
                    $button_send_to_approve.text($button_send_to_approve.data('text-send'));
                }
            }
        }

    });
}
