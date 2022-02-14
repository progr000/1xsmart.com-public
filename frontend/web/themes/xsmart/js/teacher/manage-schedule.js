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

    /* установка или снятие отметки с конкретного часа в расписании */
    $(document).on('click', '.schedule__booking-time', function() {

        /* teacher can't cancel lesson */
        if ($(this).hasClass('lesson_scheduled')) {
            flash_msg(
                $translate_text_messages.attr('data-msg-1'),
                'danger',
                FLASH_TIMEOUT,
                true,
                { user: $(this).data('users') }
            );
            return false;
        }

        /* can't change past */
        if ($(this).hasClass('_disabled')) {
            flash_msg(
                $translate_text_messages.attr('data-msg-2'),
                'danger',
                FLASH_TIMEOUT
            );
            return false;
        }
        if ($(this).hasClass('_locked-out-of-date')) {
            flash_msg(
                $translate_text_messages.attr('data-msg-2'),
                'danger',
                FLASH_TIMEOUT
            );
            return false;
        }

        changeWorkHour($(this));
    });


    const event = document.createEvent('Event');
    event.initEvent('change', true, true);
    document.querySelector('#schedule-expand22').dispatchEvent(event);

    //document.querySelector('#schedule-expand22').checked = true;
    //$('#t2').trigger('click');
    //$('#schedule-expand22').trigger('change');

    /**/
    $(document).on('click', '.js-change-week', function() {
        let week = $(this).data('show-available-week');
        $('#which-show-available-week').attr('data-show-available-week', week);
        changeAvailableWeek(week);
    });

});
