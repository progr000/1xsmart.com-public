<?php

/** @var $this yii\web\View */
/** @var $users array */
/** @var $CurrentUser \common\models\Users */

use common\models\Users;

foreach ($users as $user) {
    $user_type = Yii::t('app/chat', 'Support');
    if ($user['user_type'] == Users::TYPE_TEACHER) { $user_type = Yii::t('app/chat', 'Teacher_name'); }
    if ($user['user_type'] == Users::TYPE_STUDENT) { $user_type = Yii::t('app/chat', 'Student_name'); }
    $opponent_name = Users::getDisplayName($user['user_first_name'], $user['user_last_name']);
    ?>
    <div class="chat__user -js-user-chat-link js-user-chat-link-my opponent-<?= $user['opponent_user_id'] ?> <?= $user['count_new'] ? '_has-new' : '' ?>"
         data-opponent_user_id="<?= $user['opponent_user_id'] ?>"
         data-opponent_name="<?= $opponent_name ?>">
        <div class="chat__user-ava">
            <img src="<?= Users::staticGetProfilePhotoForWeb($user['user_photo'], '/assets/xsmart-min/images/no_photo.png') ?>" alt="">
        </div>
        <div class="chat__user-info">
            <div class="chat__user-label"><?= $user_type ?></div>
            <div class="chat__user-name"><?= $opponent_name ?></div>
        </div>
        <div class="chat__user-last-time"></div>
    </div>
    <?php
}
?>
