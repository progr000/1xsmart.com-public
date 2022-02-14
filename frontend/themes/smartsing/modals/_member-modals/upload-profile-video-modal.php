<?php

/** @var $this yii\web\View */
/** @var $form yii\bootstrap\ActiveForm */
/** @var $LoginFormModel \frontend\models\forms\LoginForm */

?>

<!-- begin MODAL -->
<div class="modal" id="upload-profile-video-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <input type="file" name="user_profile_video" id="ssi-upload-video" />
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->
