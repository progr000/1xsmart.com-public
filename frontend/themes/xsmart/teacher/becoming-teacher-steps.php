<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $DashboardSchedule array */
/** @var $ProfileForm \frontend\models\forms\ProfileForm */
/** @var $TeachersDisciplines \common\models\TeachersDisciplines */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Users;

/**/
$ProfileForm->discipline_id = ($TeachersDisciplines) ? $TeachersDisciplines->discipline_id : 0;
$fill_your_information = false;
if ($ProfileForm->discipline_id &&
    $ProfileForm->user_email &&
    $ProfileForm->user_first_name &&
    $ProfileForm->user_last_name &&
    $ProfileForm->_user_skype &&
    $ProfileForm->country_id &&
    $ProfileForm->user_are_native &&
    $ProfileForm->user_price_peer_hour > 0 &&
    $ProfileForm->user_additional_info &&
    $ProfileForm->user_photo) {

    $fill_your_information = true;
}
$upload_short_video = false;
if ($ProfileForm->user_local_video || $ProfileForm->user_youtube_video) {
    $upload_short_video = true;
}
$specify_your_schedule = false;
if (isset($DashboardSchedule) && sizeof($DashboardSchedule))
{
    $specify_your_schedule = true;
}

?>


<div id="teacher-steps-approve"
     class="dashboard__section dashboard__section--wide panel <?= (($CurrentUser->teacher_profile_completed == Users::TEACHER_PROFILE_APPROVED) && ($fill_your_information && $upload_short_video && $specify_your_schedule)) ? 'hidden' : '' ?>">
    <div class="becoming-steps">
        <div class="becoming-steps__note"><?= Yii::t('teacher/index', 'To_start_you_have_') ?></div>
        <ul class="becoming-steps__steps steps-list">
            <li id="step-info" class="becoming-steps__step <?= $fill_your_information ? '_completed' : '' ?>"><a href="<?= Url::to(['user/settings-and-profile', 'tab' => 'profile']) ?>"><?= Yii::t('teacher/index', 'Fill_information') ?></a></li>
            <li id="step-video" class="becoming-steps__step <?= $upload_short_video ? '_completed' : '' ?>"><a href="<?= Url::to(['user/settings-and-profile', 'tab' => 'profile']) ?>"><?= Yii::t('teacher/index', 'Upload_video') ?></a></li>
            <li id="step-schedule" class="becoming-steps__step <?= $specify_your_schedule ? '_completed' : '' ?>"><a href="#" class="js-open-modal void-0" data-modal-id="schedule-tutor-active-popup"><?= Yii::t('teacher/index', 'Specify_schedule') ?></a></li>
        </ul>

        <?php $form = ActiveForm::begin(['action'=>['teacher/send-profile-for-approve']]) ?>
        <!--<input type="hidden" name="teacher_id" value="<?= $CurrentUser->user_id ?>"/>-->
        <button
            id="button-send-to-approve"
            class="becoming-steps__submit primary-btn primary-btn <?= (!($fill_your_information && $upload_short_video && $specify_your_schedule) || ($CurrentUser->teacher_profile_completed == Users::TEACHER_PROFILE_WAIT_APPROVE)) ? 'primary-btn--neutral' : '' ?> sm-btn wide-mob-btn"
            <?= (
                !($fill_your_information && $upload_short_video && $specify_your_schedule) ||
                ($CurrentUser->teacher_profile_completed == Users::TEACHER_PROFILE_WAIT_APPROVE)
                )
                ? ' disabled="disabled" '
                : '' ?>
            data-text-send="<?= Yii::t('teacher/index', 'Send_to_review') ?>"
            data-text-waiting="<?= Yii::t('teacher/index', 'Waiting_for_approve') ?>"
            type="submit"><?= (
                ($fill_your_information && $upload_short_video && $specify_your_schedule) &&
                ($CurrentUser->teacher_profile_completed == Users::TEACHER_PROFILE_WAIT_APPROVE)
            )
                ? Yii::t('teacher/index', 'Waiting_for_approve')
                : Yii::t('teacher/index', 'Send_to_review') ?>
        </button>
        <?php ActiveForm::end(); ?>

    </div>
</div>
