$(document).ready(function() {

    /**/
    $(document).on('click', '.js-open-modal-user-info', function () {

        let $this = $(this);
        let modal_id = $this.data('modal-id');
        let user_id = $this.data('user-id');
        let data_save_opened = $this.data('save-opened');

        if (user_id && modal_id) {

            $.ajax({
                type: 'get',
                url: '/user/get-user-info',
                data: {
                    user_id: user_id
                },
                dataType: 'json'
            }).done(function (response) {

                if ("data" in response && "status" in response && response.status) {

                    let $modal = $(`#${modal_id}`);
                    if ($modal.length) {

                        jQuery.each(response.data, function (i, val) {
                            console.log(i, ' = ', val);
                            if (i == 'user_photo') {
                                $modal.find(`.src-user_photo`).each(function () {
                                    $(this).attr('src', val);
                                });
                            }
                            $modal.find(`.value-${i}`).each(function () {
                                $(this).val(val);
                            });
                            $modal.find(`.${i}`).each(function () {
                                if (i == 'user_operator') {
                                    $(this).attr('data-user-id', response.data.operator_user_id);
                                }
                                if (i == 'user_methodist') {
                                    $(this).attr('data-user-id', response.data.methodist_user_id);
                                }

                                $(this).html(val);
                            });
                            //$("#" + i).append(document.createTextNode(" - " + val));
                        });

                        let $modal_trigger = $(`#trigger-${modal_id}`);
                        if ($modal_trigger.length) {
                            $modal_trigger[0].click();
                        }

                    }

                } else {
                    console.log(response);
                    prettyAlert('An internal server error occurred.');
                }

            });

        }
    });

});