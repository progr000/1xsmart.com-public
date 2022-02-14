<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $CurrentUser \common\models\Users */

use common\models\Users;

/** init vars */
$static_action = Yii::$app->request->get('action', null);
//$CurrentUser = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;
$CurrentUser = $this->context->CurrentUser;

/** Register all assets (js + css) */
$this->render('js-css-assets', ['CurrentUser' => $CurrentUser]);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render('head', ['CurrentUser' => $CurrentUser]) ?>
</head>
<body class="loaded"
      lang="<?= Yii::$app->language ?>"
      data-is-debug="<?= YII_DEBUG ? 1 : 0 ?>"
      data-flash-timeout="<?= Yii::$app->params['FLASH_TIMEOUT'] ?>"
      data-default-lang="<?= Yii::$app->sourceLanguage ?>"
      data-uid="<?= $CurrentUser ? $CurrentUser->user_id : "null" ?>"
      data-user-status="<?= $CurrentUser ? $CurrentUser->user_status : "null" ?>">
<?php $this->beginBody() ?>

<!-- begin .alert-messages-->
<?= $this->render('alert-messages', ['CurrentUser' => $CurrentUser]) ?>
<!-- end .alert-messages-->

<div class="page bg">

    <?= $content ?>

    <?= $this->render("member-after-menu", [
        'CurrentUser'   => $CurrentUser,
        'static_action' => $static_action,
    ]) ?>

    <?= $this->render("member-after-footer-" . ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE ? '3' : '4'), [
        'CurrentUser'   => $CurrentUser,
        'static_action' => $static_action,
    ]) ?>

</div>

<?= $this->render('../modals/common-modal', ['CurrentUser' => $CurrentUser]) ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
