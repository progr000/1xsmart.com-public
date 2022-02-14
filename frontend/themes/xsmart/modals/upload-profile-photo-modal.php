<?php

/** @var $this yii\web\View */
/** @var $form yii\bootstrap\ActiveForm */
/** @var $LoginFormModel \frontend\models\forms\LoginForm */

use frontend\assets\xsmart\ssiUploaderAsset;

ssiUploaderAsset::register($this);

?>

<!-- begin MODAL -->
<div class="modal" id="upload-profile-photo-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <input type="hidden" name="user_id" id="upload-img-for-user-id" value="0" />
            <input type="file" name="user_profile_photo" id="ssi-upload" />
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->
