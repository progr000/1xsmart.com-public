<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $step integer */
/** @var $date_start string */

use yii\helpers\Html;
use common\models\Users;

$this->title = Html::encode('Оплата произведена');

?>
<div class="container">
    <div class="page-top"><img class="page-top__img" src="/assets/xsmart-min/images/finish.svg" alt="" role="presentation" />
        <div class="page-top__title js-open-modal" data-modal-id="booking-popup2">Payment was successful</div>
        <div class="page-top__intro">
                <b><?= $CurrentUser ? $CurrentUser->user_first_name : 'Dear customer'?></b>, thanks. <br />
                <?php
                if ($CurrentUser) {
                    if ($CurrentUser->after_payment_action == Users::AFTER_INTRO_ACTION) {
                        ?>
                        The payment has been successfully completed.<br />
                        Your teacher will wait for you at the arranged time in our virtual classroom. <br/>
                        Now you will be redirected to the main page.
                        <?php
                    } elseif ($CurrentUser->after_payment_action == Users::AFTER_PACKAGE_ACTION) {
                        ?>
                        The payment has been successfully completed.<br />
                        The lessons package was successfully purchased. <br/>
                        Lesson hours will be credited to your account soon. <br/>
                        Now you will now be redirected to the schedule setup page.
                        <?php
                    } else {
                        ?>
                        The payment has been successfully completed.
                        <?php
                    }
                } else {
                    ?>
                    The payment has been successfully completed.<br />
                    Now you will be redirected to the main page.
                    <?php
                }
                ?>
            </div>

    </div>
</div>

<?php
/*
const STATUS_DELETED   = 0;
const STATUS_INACTIVE  = 1;
const STATUS_BEFORE_INTRODUCE = 2; // до того как прошло вводное занятие с методистом
const STATUS_AFTER_INTRODUCE = 3;  // после вводного занятия с методистом, будем показывать что нужно сделать шаг и купить курс
const STATUS_AFTER_PAYMENT = 4;    // после того как юзер сделал оплату но еще не установил себе расписание
const STATUS_ACTIVE    = 10;
*/
?>
<script>

    if (window.location.href.indexOf('reloaded') < 0) {
        window.location.href = '/site/payment-success?reloaded=1';
    }

    let USER_AFTER_PAYMENT_ACTION = <?= $CurrentUser ? $CurrentUser->after_payment_action : Users::NO_ACTION ?>;
    setTimeout(function() {

        if (USER_AFTER_PAYMENT_ACTION == <?= Users::AFTER_PACKAGE_ACTION ?>) {
            /* если статс юзера уже подтвержден (оплата прошла), то тогда первое условие - перебросим его на страницу установки расписания */
            //window.history.pushState({}, '', window.parent.location.href);
            //window.parent.location.href = '/student/set-schedule';
            window.parent.location.href = '/student/set-schedule?payment=success';

        } else if (USER_AFTER_PAYMENT_ACTION == <?= Users::AFTER_INTRO_ACTION ?>) {

            window.parent.location.href = '/student?payment=success';

        } else {
            /* иначе релоадим страницу в ифрейме, каздые 30 секунд, и надеемся что первое условие вскоре сработает*/
            setTimeout(function() {
                console.log('reloading...');
                window.location.reload();
//                let $elIfr = $(document).find('.t-frame').first();
//                if ($elIfr.length) {
//                    $elIfr[0].src = $elIfr[0].src;
//                }
            }, 15000);
        }
    }, 15000);

</script>
