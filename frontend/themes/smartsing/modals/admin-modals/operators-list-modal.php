<?php

/** @var $this yii\web\View */
/** @var $listOperators \yii\db\ActiveRecord[] */

?>

<!-- begin MODAL -->
<div class="modal" id="operators-list-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Список операторов</div>
            <input type="hidden" id="operator-for-lead_id" value="0" />
            <div class="coach-students">
                <div class="coach-students__list users-brief-list users-brief-list--columns">
                    <?php
                    /** @var \common\models\Users $Operator */
                    foreach ($listOperators as $Operator) {
                        ?>
                        <div class="user-brief users-brief-list__item">
                            <img class="user-brief__ava-img"
                                 src="<?= $Operator->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') ?>"
                                 alt=""
                                 role="presentation" />
                            <div class="user-brief__data">
                                <div class="user-brief__name">
                                    <a class="user-brief__name-link js-assign-operator-for-lead void-0"
                                       data-operator_user_id="<?= $Operator->user_id ?>"
                                       href="#"><?= $Operator->user_full_name ?></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->
