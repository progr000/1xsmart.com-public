<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \frontend\models\forms\ProfileForm */

?>

<!-- begin MODAL -->
<div class="modal" id="view-my-video-modal">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('app/settings-and-profile', 'View_video') ?></div>
            <div class="video- video-about-teacher-" id="video-about-teacher-local">
                <video style="height: 400px"
                       id="user-local-video-profile"
                       width="100%"
                       height="auto"
                       src="<?= $CurrentUser->user_local_video ? Yii::$app->params['profileDirWeb'] . "/{$CurrentUser->user_local_video}?rnd=" . mt_rand(1, 10000000) : "" ?>"
                       controls>
                    Your browser does not support the video tag.
                </video>
                <a id="download-video-link" href="<?= $CurrentUser->user_local_video ? Yii::$app->params['profileDirWeb'] . "/{$CurrentUser->user_local_video}?rnd=" . mt_rand(1, 10000000) : "" ?>"
                   target="_blank"><?= Yii::t('app/settings-and-profile', 'Download_video_file') ?></a>
            </div>
        </div>
        <button class="modal__close-btn js-close-modal js-close-pdf-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->