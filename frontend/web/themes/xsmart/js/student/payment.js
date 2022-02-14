
/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('click', '.js-start-tinkoff-payment', function () {

        /**/
         if (USER_TYPE != USER_TYPES.TYPE_STUDENT) {
             prettyAlert($translate_text_messages.attr('data-msg-19'));
             return false;
         }

        /**/
        let $this = $(this);
        //alert($this.attr('data-timestamp-gmt'));
        $.ajax({
            type: 'get',
            url: '/student/start-tinkoff-payment-first-lesson-xsmart',
            data: {
                teacher_id: $this.attr('data-teacher-id'),
                lesson_count: $this.attr('data-lessons-count'),
                timestamp_gmt: $this.attr('data-timestamp-gmt'),
                date_gmt: $this.attr('data-date-gmt'),
                //amount: $this.attr('data-amount'),
                //currency: $this.attr('data-currency'),
                description: $('#booking-selected-date').html()
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

    /**
     *
     */
    $(document).on('click', '.t-close-frame-desktop', function () {
        if ($('#booking-popup').length) {
            window.location.reload();
        } else {
            window.location.href = '/student';
        }
    });


});