let $default_wallet_wor_withdraw = $('#default-wallet-wor-withdraw');
let slim = null;

/**
 *
 */
$(document).ready(function() {

    slim = new SlimSelect({
        select: $default_wallet_wor_withdraw[0],
        showSearch: false,
        data: paymentsData
    });

    /**/
    $(document).on('change', '#default-wallet-wor-withdraw', function() {
        $.ajax({
            type: 'get',
            url: '/teacher/change-wallet-for-withdraw',
            data: {
                wallet: $(this).val()
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && response.status && "info" in response) {
                flash_msg(response.info, 'success', FLASH_TIMEOUT);
            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
                flash_msg(response.info, 'error', FLASH_TIMEOUT);
            }

        });
    });

    $(document).on('click', '.js-change-wallet-requisites', function() {

        let $form = $('#form-wallet-requisites');
        let $this_button = $(this);

        if ($this_button.hasClass('in-progress')) {
            return false;
        }

        let wallet_paypal = $('#wallet-paypal').val();
        let wallet_yandex = $('#wallet-yandex').val();

        if ($.trim(wallet_paypal) == '' && $.trim(wallet_yandex) == '') {
            flash_msg($('#js-requisites-info').text(), 'error', FLASH_TIMEOUT);
            return false;
        }

        $form.yiiActiveForm('data').submitting = true;
        $form.yiiActiveForm('validate');

        window.setTimeout(function () {

            if ($form.find('.has-error').length) {
                flash_msg($translate_text_messages.attr('data-msg-3'), 'error', FLASH_TIMEOUT);
                //$this_button.removeClass('in-progress');
                //$this_button_text.html($this_button.data('ready-to-send'));
                return false;
            }

            $this_button.addClass('in-progress');
            $this_button.html($this_button.data('save-in-progress'));

            $.ajax({
                type: 'post',
                url: '/teacher/save-data-for-wallets',
                data: {
                    wallet_paypal: wallet_paypal,
                    wallet_yandex: wallet_yandex
                },
                dataType: 'json'
            }).done(function (response) {

                if ("status" in response && response.status && "info" in response) {

                    flash_msg(response.info, 'success', FLASH_TIMEOUT);

                } else {
                    console.log(response);
                    //prettyAlert($translate_text_messages.attr('data-msg-15'));
                    flash_msg(response.info, 'error', FLASH_TIMEOUT);
                }
                $this_button.removeClass('in-progress');
                $this_button.html($this_button.data('ready-to-save'));

                //try { $.pjax.reload({container: "#teacher-wallets-form", async: true}); }
                //catch (e) { console.log('info:: Skipped. Not found pjax container #teacher-wallets-form for reload.'); }
                //$default_wallet_wor_withdraw.selectize()[0].selectize.destroy();
                //$default_wallet_wor_withdraw.empty();
                //try {
                    console.log(response.paymentsData);
                    paymentsData = response.paymentsData;
                    //$default_wallet_wor_withdraw.selectize()[0].selectize.destroy();
                    slim.destroy();
                    //$default_wallet_wor_withdraw.empty();
                    slim = new SlimSelect({
                        select: $default_wallet_wor_withdraw[0],
                        showSearch: false,
                        data: paymentsData
                    });

                //}
                //catch (e) { console.log(e); }

            }).fail(function (response) {

                $this_button.removeClass('in-progress');
                $this_button.html($this_button.data('ready-to-save'));

            });

        }, 1000);

    });

    /**/
    $(document).on('ready pjax:end', function (event) {
        //$('#default-wallet-wor-withdraw').selectize();
        //SlimSelect();
    });

});