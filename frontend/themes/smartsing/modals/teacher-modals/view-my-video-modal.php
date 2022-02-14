<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \frontend\models\forms\ProfileForm */

?>

<!-- begin MODAL -->
<div class="modal modal--frame modal--sm-pad" id="view-my-video-modal">
    <div class="modal__content" style="min-width: 90%;">
        <div class="modal__inner">
            <div class="modal__title" id="pdf-title">ваше видео-приветствие</div>
            <div class="video- video-about-teacher-" id="video-about-teacher-local">
                <video style="height: 400px"
                       id="user-local-video-profile"
                       width="100%"
                       height="auto"
                       src="<?= $CurrentUser->user_local_video ? Yii::$app->params['profileDirWeb'] . "/{$CurrentUser->user_local_video}?rnd=" . mt_rand(1, 10000000) : "" ?>"
                       controls>
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        <button class="btn modal__close-btn js-close-modal js-close-pdf-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->