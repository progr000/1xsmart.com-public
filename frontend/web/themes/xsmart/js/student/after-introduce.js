/**
 *
 */
$(document).ready(function() {

    /**/
    $(document).on('change', '.teachers-variant', function () {

        for (let property in $(this).data()) {
            //console.log(property, $(this).attr(`data-${property}`));
            /**/
            let $test = $(`#${property}`);
            if ($test.length) {
                //console.log($test.html());
                $test.html($(this).attr(`data-${property}`));
            }

            /**/
            let $test1 = $(`.${property}`);
            if ($test1.length) {
                //console.log($test.html());
                if ($test1.hasClass('its-img')) {
                    $test1[0].src = $(this).attr(`data-${property}`);
                } else {
                    $test1.html($(this).attr(`data-${property}`));
                }
            }

            /**/
            let $test2 = $(`#button-${property}`);
            if ($test2.length) {
                let l_count = $test2.attr('data-lessons_count');
                $test2.attr('data-lesson_cost', $(this).attr(`data-cost_hour_if_by_${l_count}_hours`));
                $test2.attr('data-you_save', $(this).attr(`data-save_if_by_${l_count}_hours`));
                $test2.attr('data-total_price', $(this).attr(`data-${property}`));
                $test2.attr('data-teacher_user_id', $(this).attr('data-teacher_user_id'));

                $test2.attr('data-usd_lesson_cost', $(this).attr(`data-usd_cost_hour_if_by_${l_count}_hours`));
                $test2.attr('data-usd_you_save', $(this).attr(`data-usd_save_if_by_${l_count}_hours`));
                $test2.attr('data-usd_total_price', $(this).attr(`data-usd_${property}`));
                $test2.attr('data-teacher_user_id', $(this).attr('data-teacher_user_id'));
            }
        }

    });

    /**/
    $(document).find('.teachers-variant').first().prop('checked', false).trigger('change');
    $(document).find('.teachers-variant').first().prop('checked', true).trigger('change');

    /**/
    $(document).on('click', '.js-prepare-tinkoff-payment', function () {

        let $payment_params_button = $('#payment-params-button');
        for (let property in $(this).data()) {
            /**/
            //console.log(`data-${property}`, $(this).attr(`data-${property}`));
            $payment_params_button.attr(`data-${property}`, $(this).attr(`data-${property}`));
            /**/
            let $test1 = $(`.${property}`);
            if ($test1.length) {
                //console.log($test.html());
                if ($test1.hasClass('its-img')) {
                    $test1[0].src = $(this).attr(`data-${property}`);
                } else {
                    $test1.html($(this).attr(`data-${property}`));
                }
            }
        }
        $('#booking-popup').addClass('_opened');

    });

    /**/
    $(document).on('click', '.js-start-tinkoff-payment', function () {

        /**/
        // if (USER_TYPE != USER_TYPES.TYPE_STUDENT) {
        //     prettyAlert($translate_text_messages.attr('data-msg-19'));
        //     return false;
        // }

        /**/
        let $this = $(this);
        //alert($this.attr('data-timestamp-gmt'));
        $.ajax({
            type: 'get',
            url: '/student/start-tinkoff-payment-package-lessons',
            data: {
                teacher_user_id: $this.attr('data-teacher_user_id'),
                lessons_count: $this.attr('data-lessons_count'),
                amount: $this.attr('data-total_price'),
                currency: $this.attr('data-currency'),
                description: $this.attr('data-description')
            },
            dataType: 'json'
        }).done(function (response) {
            console.log(response);
            if ("status" in response) {
                if ("data" in response && "order_id" in response.data) {

                    $('#tinkoff-order_id').val(response.data.order_id);
                    $('#tinkoff-order_amount').val(response.data.order_amount);
                    $('#tinkoff-order_description').val(response.data.order_description);
                    //$('#tinkoff-payment-form')[0].submit();
                    pay($('#tinkoff-payment-form')[0]);

                } else {
                    console.log(response);
                    prettyAlert(response.info);
                }

            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
            }

        });
    });

});