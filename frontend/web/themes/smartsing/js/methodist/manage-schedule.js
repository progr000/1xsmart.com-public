
/** ***** **/
$(document).ready(function() {

    /* попробуем получить расписание ученика, если оно есть */
    getSchedule();

    /* если есть элемент который указывает нам текущий день недели, то переключим расписание на этот день недели */
    let $test = $('#main-schedule-container');
    if ($test.length && typeof $test.data('current-week-day') != 'undefined') {
        $(`#day-${$test.data('current-week-day')}`).trigger('click');
    }

    /* установка или снятие отметки с конкретного часа в расписании */
    $(document).on('change', '.js-beginning-time', function() {

        /* checks for methodist */
        //if (USER_TYPE == USER_TYPES.TYPE_METHODIST) {
        if ($(this).hasClass('_active')) {
            setPendingData(
                `На это время у вас уже назначены вводные занятия с учеником(ами) ${$(this).data('students')}. Обратитесь в саппорт для изменения времени занятий.`,
                'danger',
                10000
            );
            return false;
        }
        //}

        changeWorkHour($(this));
    });

});