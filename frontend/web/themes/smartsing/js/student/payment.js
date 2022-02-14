
/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('click', '.js-start-tinkoff-payment', function () {

        /**/
        if (USER_TYPE != USER_TYPES.TYPE_STUDENT) {
            prettyAlert('Вы являетесь методистом (или учителем).<br />Этот слайд предназначен для оплаты учеником.<br />Вам не нужно выполнять оплату.');
            return false;
        }

        /**/
        let $this = $(this);
        $.ajax({
            type: 'get',
            url: '/student/start-tinkoff-payment',
            data: {
                lesson_count: $this.data('lessons-count'),
            },
            dataType: 'json'
        }).done(function (response) {
            if ("data" in response && "status" in response && response.status) {

                if (response.data.order_id) {

                    $('#tinkoff-order_id').val(response.data.order_id);
                    $('#tinkoff-order_amount').val(response.data.order_amount);
                    $('#tinkoff-order_description').val(response.data.order_description);
                    //$('#tinkoff-payment-form')[0].submit();
                    pay($('#tinkoff-payment-form')[0]);

                } else {
                    prettyAlert(response.data.info);
                }

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });
    });

});