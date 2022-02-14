let AVAILABLE_DATES;
let $current_timeline_for_change = $('#current-timeline-for-change');
let $new_day_for_change = $('#new-day-for-change');
let $new_time_for_change = $('#new-time-for-change');
let $js_move_lesson_form = $('#js-move-lesson-form');
let $move_lesson_button = $('#move-lesson-button');
let $move_lesson_status = $('#move-lesson-status');

/**
 *
 */
function clearFormsError()
{
    $js_move_lesson_form.find('.input-wrap').each(function() {
        $(this).removeClass('has-error');
    });
    $js_move_lesson_form.find('.help-block').each(function() {
        $(this).addClass('hidden');
    });

    $move_lesson_button.addClass('locked');
    $move_lesson_status.hide();
}

/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('click', '.js-open-student-popup-schedule', function() {
        clearFormsError();

        let $this = $(this);
        let week_day = $this.data('week-day');
        let work_hour = $this.data('work-hour');
        //console.log(`.week-${week_day}`);
        //console.log(`.week-${week_day}-hour-${work_hour}`);

        let $test = $current_timeline_for_change.find('option').first();
        if (!$test.length) {
            return false;
        }
        $test.prop('selected', true);
        $current_timeline_for_change.find(`.week-${week_day}`).first().prop('selected', true);
        $current_timeline_for_change.find(`.week-${week_day}-hour-${work_hour}`).first().prop('selected', true);
        $current_timeline_for_change.trigger('change');
    });

    /**/
    $(document).on('change', '#current-timeline-for-change', function() {

        clearFormsError();

        let $selected = $current_timeline_for_change.find('option:selected').first();
        if ($selected.length) {

            //console.log('is_replacing = ', $selected.data('is-replacing'));
            let is_replacing = parseInt($selected.data('is-replacing'));
            if (is_replacing) {
                $('input[name="is_replacing"][value="1"]').prop('checked', true);
                $('input[name="is_replacing"]').prop('disabled', true);
            } else {
                $('input[name="is_replacing"][value="0"]').prop('checked', true);
                $('input[name="is_replacing"]').prop('disabled', false);
            }

            $('#text-current-timeline-for-change').html($selected.text());
            $.ajax({
                type: 'get',
                url: '/student/get-available-dates-for-changing-schedule',
                data: {
                    timeline_id: $selected.val()
                },
                dataType: 'json'
            }).done(function (response) {
                if ("data" in response && "status" in response && response.status) {

                    AVAILABLE_DATES = response.data;

                    $new_day_for_change.empty();
                    $new_time_for_change.empty();
                    let selected, selected2;
                    $.each(AVAILABLE_DATES, function(i, item_day) {
                        //console.log(item, i);
                        if (i == 'unselected') {
                            selected = 'selected="selected"';
                            $.each(item_day.hours, function(j, item_time) {
                                if (j == 'unselected') { selected2 = 'selected="selected"'; } else { selected2 = '' }
                                $new_time_for_change.append(`<option ${selected2} data-full_date_utz="${item_day.full_date_utz}" data-full_date_gmt="${item_day.full_date_gmt}" value="${item_time.schedule_id}">${item_time.print_hour}</option>`);
                            });
                        } else { selected = ''; }
                        $new_day_for_change.append(`<option ${selected} data-full_date_utz="${item_day.full_date_utz}" data-full_date_gmt="${item_day.full_date_gmt}" value="${i}">${item_day.print_day}</option>`);
                    });

                } else {
                    console.log(response);
                    prettyAlert('An internal server error occurred.');
                }
            });

        }
    });

    /**/
    $(document).on('change', '#new-day-for-change', function() {

        /**/
        clearFormsError();

        /**/
        if ($new_day_for_change.val() == 'unselected') {
            $new_day_for_change.parent().addClass('has-error');
            $new_day_for_change.parent().find('p').first().removeClass('hidden');
        }

        /**/
        let key = $(this).val();
        if (typeof AVAILABLE_DATES[key] != 'undefined') {
            $new_time_for_change.empty();
            let selected2;
            //console.log(AVAILABLE_DATES[key].hours);
            $.each(AVAILABLE_DATES[key].hours, function(j, item_time) {
                //console.log(item, i);
                if (j == 'unselected') {
                    selected2 = 'selected="selected"';
                } else {
                    selected2 = '';
                }
                $new_time_for_change.append(`<option ${selected2} data-timestamp_utz="${item_time.timestamp_utz}" data-timestamp_gmt="${item_time.timestamp_gmt}" data-date_gmt="${item_time.date_gmt}" value="${item_time.schedule_id}">${item_time.print_hour}</option>`);
            });
        }
    });

    /**/
    $(document).on('change', '#new-time-for-change', function() {

        /**/
        clearFormsError();

        /**/
        if ($new_time_for_change.val() == 'unselected') {
            $new_time_for_change.parent().addClass('has-error');
            $new_time_for_change.parent().find('p').first().removeClass('hidden');
        } else {
            $move_lesson_button.removeClass('locked');
        }
    });

    /**/
    $(document).on('click', '.js-move-lesson-button', function() {

        let error = false;

        /**/
        clearFormsError();

        /**/
        let current_timeline_id = $current_timeline_for_change.val();
        let teacher_schedule_id = $new_time_for_change.val();
        let new_day_val = $new_day_for_change.val();

        if (!current_timeline_id) {
            $current_timeline_for_change.parent().addClass('has-error');
            $current_timeline_for_change.parent().find('p').first().removeClass('hidden');
            error = true;
        }

        if (new_day_val == 'unselected') {
            $new_day_for_change.parent().addClass('has-error');
            $new_day_for_change.parent().find('p').first().removeClass('hidden');
            error = true;
        }

        if (teacher_schedule_id == 'unselected') {
            $new_time_for_change.parent().addClass('has-error');
            $new_time_for_change.parent().find('p').first().removeClass('hidden');
            error = true;
        }

        if (error) {
            return false;
        }


        let test = $new_time_for_change.find('option:selected').first();
        let new_timestamp_gmt_for_replacing = 0;
        if (test.length) {
            new_timestamp_gmt_for_replacing = test.data('timestamp_gmt');
        }
        /**/
        let data = {
            current_timeline_id: current_timeline_id,
            teacher_schedule_id: teacher_schedule_id,
            new_timestamp_gmt_for_replacing: new_timestamp_gmt_for_replacing,
            is_replacing: $('input[name="is_replacing"]:checked').val()
        };
        //console.log(data);

        $.ajax({
            type: 'get',
            url: '/student/move-lesson',
            data: data,
            dataType: 'json'
        }).done(function (response) {
            if ("data" in response && "status" in response && response.status) {

                /**/
                try { $.pjax.reload({container: "#current-timeline-for-change", async: false}); }
                    catch (e) { console.log('info:: Skipped. Not found pjax container #current-timeline-for-change for reload.'); }
                try { $.pjax.reload({container: "#dashboard-schedule-list", async: false}); }
                    catch (e) { console.log('info:: Skipped. Not found pjax container #dashboard-schedule-list for reload.'); }
                try { $.pjax.reload({container: "#popup-dashboard-schedule-list", async: false}); }
                    catch (e) { console.log('info:: Skipped. Not found pjax container #popup-dashboard-schedule-list for reload.'); }

                let $test = $current_timeline_for_change.find('option').first();
                if ($test.length) {
                    $test.prop('selected', true);
                }
                $current_timeline_for_change.trigger('change');
                $move_lesson_button.addClass('locked');
                $move_lesson_status.show();


            } else {
                console.log(response);
                if ("info_user" in response) {
                    prettyAlert(response.info_user);
                } else {
                    prettyAlert('An internal server error occurred.');
                }
                $move_lesson_button.removeClass('locked');
                $move_lesson_status.hide();
            }
        });

    });
});