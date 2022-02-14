<?php

/* @var $this yii\web\View */
/* @var $user common\models\Users */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= $user->user_first_name ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
