let $lesson_for_move;
let $js_schedule_container = $('#js-schedule-container');
let $js_schedule_move_tools = $('#js-schedule-move-tools');
let $js_schedule_student_dashboard = $('#js-schedule-student-dashboard');
let $change_type_once = $('#change-type-once');
let $change_type_permanent = $('#change-type-permanent');

/**
 * @param {int} week
 */
function changeAvailableWeek(week)
{
    $('.available-week').addClass('hidden');
    $(`.available-week-${week}`).removeClass('hidden');
}

/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('click', '.js-booking-btn-active', function () {

        $js_schedule_container.toggleClass('_pending');
        $js_schedule_move_tools.toggleClass('_visible');

        let $move_from = $(this);

        $move_from.addClass('_active');
        $move_from.toggleClass('_pending');

        $('.lesson-with-tutor-name').html($move_from.attr('data-teacher_display_name'));

        let teacher_user_id = $move_from.attr('data-teacher-id');
        if ($move_from.hasClass('_pending')) {
            $('.js-booking-btn-free').addClass('_disabled');
            $(`.available_for_teacher_${teacher_user_id}`).removeClass('_disabled');
        } else {
            $('.js-booking-btn-free').removeClass('_disabled');
        }

        $change_type_once[0].checked = true;
        if ($move_from.attr('data-schedule-id') == '') {
            $change_type_permanent[0].checked = false;
            $change_type_permanent[0].disabled = true;
        } else {
            $change_type_permanent[0].disabled = false;
        }

    });

    /**/
    $(document).on('click', '.js-booking-btn-free', function () {

        let $self = $(this);
        let hour_status = parseInt($self.attr('data-hour-status'));
        //console.log(`hour_status = ${hour_status}`);

        if (hour_status == 0) {
            let $move_to  = $self;
            let $move_from = $js_schedule_student_dashboard.find('._pending').first();
            let teacher_display_name = $move_from.attr('data-teacher_display_name');
            let title = $move_from.attr('title');
            let teacher_user_id = $move_from.attr('data-teacher-id');

            //console.log($move_from);
            if ($move_from.length) {
                console.log(`begin move from ${$move_from.attr('data-print-date')} to ${$move_to.attr('data-print-date')}`);

                let data = {
                    move_type: $('#change-type-once').is(':checked') ? 'once' : 'permanent',

                    week_day_to: $move_to.attr('data-day'),
                    work_hour_to: $move_to.attr('data-hour'),
                    timestamp_gmt_to: $move_to.attr('data-timestamp-gmt'),

                    week_day_from: $move_from.attr('data-day'),
                    work_hour_from: $move_from.attr('data-hour'),
                    timestamp_gmt_from: $move_from.attr('data-timestamp-gmt'),
                    timeline_id_from: $move_from.attr('data-timeline-id'),
                    teacher_user_id: $move_from.attr('data-teacher-id')
                };

                $.ajax({
                    type: 'post',
                    url: '/student/schedule-move-lesson',
                    data: data,
                    dataType: 'json'
                }).done(function (response) {

                    if ("status" in response &&
                        "data" in response &&
                        "hours" in response.data
                        && response.status) {

                        jQuery.each(response.data.hours, function (key, val) {
                            console.log(key, ' = ', val);

                            let $el = $(`#${val.id}`);
                            if ($el.length) {
                                //console.log(val['timeline-id']);
                                if (val.status == 1) {
                                    $el.attr('data-hour-status', '1');
                                    $el.attr('data-timeline-id', val['timeline-id']);
                                    $el.attr('data-schedule-id', val['schedule-id']);
                                    $el.attr('data-teacher-id', val['teacher-id']);
                                    $el.attr('data-teacher_display_name', teacher_display_name);
                                    $el.attr('title', title);
                                    $el.removeClass('js-booking-btn-free');
                                    $el.removeClass(`available_for_teacher_${teacher_user_id}`);
                                    $el.addClass('js-booking-btn-active');
                                    $el.addClass('_active');
                                    $el.removeClass('is-free');
                                } else {
                                    $el.attr('data-hour-status', '0');
                                    $el.attr('data-timeline-id', '');
                                    $el.attr('data-schedule-id', '');
                                    $el.attr('data-teacher-id', '');
                                    $el.attr('data-teacher_display_name', '');
                                    $el.attr('title', '');
                                    //$move_from[0].dataset.dataHourStatus = 0;
                                    $el.removeClass('js-booking-btn-active');
                                    $el.addClass('js-booking-btn-free');
                                    $el.addClass(`available_for_teacher_${teacher_user_id}`);
                                    $el.removeClass('_active');
                                    $el.removeClass('_pending');
                                    $el.addClass('is-free');
                                }
                            }

                        });

                        $('.js-booking-btn-free').removeClass('_disabled');

                        //response.data.hours
                        //console.log(response.data.hours);
                        //$move_to.attr('data-hour-status', '1');
                        ////$move_to[0].dataset.dataHourStatus = 1;
                        //$move_to.removeClass('js-booking-btn-free');
                        //$move_to.addClass('js-booking-btn-active');
                        //$move_to.addClass('_active');
                        //
                        //$move_from.attr('data-hour-status', '0');
                        //$move_from.attr('data-timeline-id', '');
                        ////$move_from[0].dataset.dataHourStatus = 0;
                        //$move_from.removeClass('js-booking-btn-active');
                        //$move_from.addClass('js-booking-btn-free');
                        //$move_from.removeClass('_active');
                        //$move_from.removeClass('_pending');


                        /**/
                        try { $.pjax.reload({container: "#dashboard-schedule-list", async: false}); }
                        catch (e) { console.log('info:: Skipped. Not found pjax container #dashboard-schedule-list for reload.'); }
                        try { $.pjax.reload({container: "#popup-dashboard-schedule-list", async: false}); }
                        catch (e) { console.log('info:: Skipped. Not found pjax container #popup-dashboard-schedule-list for reload.'); }
                        if (typeof changeAvailableWeek == 'function') {
                            changeAvailableWeek(parseInt($('#which-show-available-week').attr('data-show-available-week')));
                        }

                    } else {

                        $move_from.removeClass('_pending');
                        console.log(response);
                        prettyAlert(response.info);

                    }

                    $js_schedule_container.removeClass('_pending');
                    $js_schedule_move_tools.removeClass('_visible');

                }).fail(function(response) {

                    $move_from.removeClass('_pending');
                    $js_schedule_container.removeClass('_pending');
                    $js_schedule_move_tools.removeClass('_visible');
                    console.log(response);
                    //prettyAlert($translate_text_messages.attr('data-msg-15'));

                });

            } else {
                console.log('nothing to move');
            }
        }

    });

    /**/
    $(document).on('click', '.js-change-week', function() {
        let week = $(this).data('show-available-week');
        $('#which-show-available-week').attr('data-show-available-week', week);
        changeAvailableWeek(week);
    });

});