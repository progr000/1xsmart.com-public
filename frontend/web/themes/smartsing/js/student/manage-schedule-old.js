
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
        changeWorkHour($(this));
    });

});