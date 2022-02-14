<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $CurrentUser \common\models\Users */

use frontend\assets\xsmart\memberAsset;

/** init vars */
$DASHBOARD_SCHEDULE_DATA = [];
$static_action = Yii::$app->request->get('action', null);
$CurrentUser = $this->context->CurrentUser;

/** Register all assets (js + css) */
$this->render('js-css-assets', ['CurrentUser' => $CurrentUser]);
//memberAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render('head', ['CurrentUser' => $CurrentUser]) ?>
</head>
<body class="loaded"
      lang="<?= Yii::$app->language ?>"
      data-svg-path="/assets/xsmart-min/"
      data-is-debug="<?= YII_DEBUG ? 1 : 0 ?>"
      data-flash-timeout="<?= Yii::$app->params['FLASH_TIMEOUT'] ?>"
      data-default-lang="<?= Yii::$app->sourceLanguage ?>"
      data-uid="<?= $CurrentUser ? $CurrentUser->user_id : "null" ?>"
      data-user-status="<?= $CurrentUser ? $CurrentUser->user_status : "null" ?>"
      data-week-day="<?= intval(date('N', ($CurrentUser ? $CurrentUser->_user_local_time : time()) )) ?>"
      data-timestamp="<?= $CurrentUser ? $CurrentUser->_user_local_time : time() ?>"
      data-date="<?= date(SQL_DATE_FORMAT, ($CurrentUser ? $CurrentUser->_user_local_time : time()) ) ?>">
<?php $this->beginBody() ?>

<div class="page">


    <?= $content ?>


</div>

<?= $this->render('../modals/common-modal', ['CurrentUser' => $CurrentUser]) ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
