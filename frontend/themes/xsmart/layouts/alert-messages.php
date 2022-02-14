<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */

use common\widgets\Alert;
use frontend\assets\xsmart\AlertMessagesAsset;

AlertMessagesAsset::register($this);

/*
'error'   => 'alert-error',   - red
'danger'  => 'alert-danger',  - yellow
'warning' => 'alert-warning'  - yellow
'info'    => 'alert-info',    - blue
'success' => 'alert-success', - green
*/

/*
Yii::$app->session->setFlash('test_1', [
    'message'   => 'test-alert-message1',
    'ttl'       => 0,
    'showClose' => true,
    'alert_id' => 'alert-test-1',
    'type' => 'success',
    'alert_action' => 'test1-action',
    'class' => 'alert-success',
    //'auto_close_callback' => 'alert(1)',
]);
Yii::$app->session->setFlash('test_2', [
    'message'   => 'test-alert-message2',
    'ttl'       => 3000,
    'showClose' => true,
    'alert_id' => 'alert-test-1',
    'type' => 'success',
    'alert_action' => 'test1-action',
    'class' => 'alert-success',
    //'auto_close_callback' => 'alert(1)',
]);
Yii::$app->session->setFlash('test_3', [
    'message'   => 'test-alert-message3',
    'ttl'       => 0,
    'showClose' => false,
    'alert_id' => 'alert-test-2',
    'type' => 'danger',
    //'class' => 'alert-danger',
    //'auto_close_callback' => 'alert(1)',
]);
*/

/* Если пользователь еще не подтвердил свой емейл */
//if (!Yii::$app->user->isGuest &&
//    ($user->user_status != Users::STATUS_CONFIRMED) &&
//    ($user->user_closed_confirm == Users::CONFIRM_UNCLOSED)) {
//        Yii::$app->session->setFlash('alert_confirm_email', [
//            'message'   =>
//                (
//                $user->license_type == \common\models\Licenses::TYPE_FREE_TRIAL
//                    ? Yii::t('app/flash-messages', 'Confirm_email_plus_trial')
//                    : Yii::t('app/flash-messages', 'Confirm_email')
//                ),
//            'ttl'       => 0,
//            'showClose' => true,
//            'alert_id' => 'alert-confirm-email',
//            'type' => 'danger',
//        ]);
//}

?>

<!-- translate -->
<div id="translate-text-messages" class="hidden"
     data-msg-1="<?= Yii::t('app/alert-messages', 'During_this_time_lesson') ?>"
     data-msg-2="<?= Yii::t('app/alert-messages', 'Date_in_past') ?>"
     data-msg-3="<?= Yii::t('app/alert-messages', 'Wrong_form_data') ?>"
     data-msg-4="<?= Yii::t('app/alert-messages', 'interlocutor_not_hear') ?>"
     data-msg-5="<?= Yii::t('app/alert-messages', 'interlocutor_not_see') ?>"
     data-msg-6="<?= Yii::t('app/alert-messages', 'student_disconnected') ?>"
     data-msg-7="<?= Yii::t('app/alert-messages', 'teacher_connected') ?>"
     data-msg-8="<?= Yii::t('app/alert-messages', 'student_connected') ?>"
     data-msg-9="<?= Yii::t('app/alert-messages', 'teacher_disconnected') ?>"
     data-msg-10="<?= Yii::t('app/alert-messages', 'You_ended_session') ?>"
     data-msg-11="<?= Yii::t('app/alert-messages', 'turned_off_microphone') ?>"
     data-msg-12="<?= Yii::t('app/alert-messages', 'turned_on_microphone') ?>"
     data-msg-13="<?= Yii::t('app/alert-messages', 'turned_off_camera') ?>"
     data-msg-14="<?= Yii::t('app/alert-messages', 'turned_on_camera') ?>"
     data-msg-15="<?= Yii::t('app/alert-messages', 'internal_error') ?>"
     data-msg-16="<?= Yii::t('app/alert-messages', 'Lost_socket') ?>"
     data-msg-17="<?= Yii::t('app/alert-messages', 'Socket_error') ?>"
     data-msg-18="<?= Yii::t('app/alert-messages', 'Saved') ?>"
     data-msg-19="<?= Yii::t('app/alert-messages', 'is_for_student_payment') ?>"
     data-msg-20="<?= Yii::t('app/alert-messages', 'Waiting_schedule') ?>"
     data-msg-n=""></div>

<!-- begin #alert-block-container -->
<div id="alert-block-container">
    <?php echo Alert::widget(); ?>
    <!--
    <div id="alert-confirm-email" class="alert-danger alert-has-close alert fade in" data-ttl="0" data-type="danger" data-alert-action="">
        <button type="button" class="close close-alert close-alert-alert-confirm-email" data-dismiss="alert" aria-hidden="true" data-alert-id="alert-confirm-email" data-flash-dialog="alert_confirm_email">×</button>
        dsdcdsjcsdcndsjkcndskcsdkmcs<br />dlkmcsdlmcsdkl
    </div>
    -->
</div>
<!-- end #alert-block-container -->


<!-- begin #flash-tpl -->
<div id="flash-tpl" style="display: none;">
    <div class="alert">
        <button type="button" class="close close-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <span class="flash-message">{flash-message}</span>
    </div>
</div>
<!-- end #flash-tpl -->

<!-- begin #alert-template -->
<div id="alert-template" style="display: none;">
    <div class="mc-snackbar">
        <div class="mc-snackbar-container mc-snackbar-container--snackbar-icon">
            <div class="mc-snackbar-icon success"></div>
            <p class="mc-snackbar-title">{alert-message}</p>
            <button class="mc-snackbar-actions mc-button-styleless mc-snackbar-close">
                <span class="mc-button-content">Close</span>
            </button>
        </div>
    </div>
</div>
<!-- end #alert-template -->

<!-- begin #alert-snackbar-container -->
<div class="mc-snackbar-holder-backdrop" id="alert-snackbar-container"></div>
<!-- end #alert-snackbar-container -->
