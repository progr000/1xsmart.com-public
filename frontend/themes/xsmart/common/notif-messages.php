<?php

/** @var $this yii\web\View */
/** @var $messages array */
/** @var $CurrentUser \common\models\Users */

use common\models\Payments;

//echo "<pre>";var_dump($messages);echo "</pre>";

foreach ($messages as $data) {
    $current_message_user_id = null;
    ?>
    <div class="notify <?= $data['p_status'] == Payments::STATUS_PAYED ? 'notify--success' : 'notify--warning' ?>">
        <div class="notify__text"><?= $data['p_status'] == Payments::STATUS_PAYED ? 'Payment received' : 'Payment canceled' ?></div>
        <div class="notify__meta"><?= $CurrentUser->getDateInUserTimezoneByDateString($data['p_date'], Yii::$app->params['datetime_short_format'], false) ?></div>
    </div>
    <?php
}
?>
