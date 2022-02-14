<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $CurrentUser \common\models\Users */


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


<div class="page bg">

    <?= $content ?>

</div>


<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
