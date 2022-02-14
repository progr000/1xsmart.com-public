<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $CurrentUser \common\models\Users */

use common\models\Users;
use frontend\assets\xsmart\memberAsset;

/** init vars */
$DASHBOARD_SCHEDULE_DATA = [];
$static_action = Yii::$app->request->get('action', null);
//$CurrentUser = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;
$CurrentUser = $this->context->CurrentUser;

/*menu*/
require_once('menu-list.php');

/** Register all assets (js + css) */
$this->render('js-css-assets', ['CurrentUser' => $CurrentUser]);
memberAsset::register($this);

/** */
switch ($CurrentUser->user_type) {
    case Users::TYPE_STUDENT:
        $tpl = 'student';
        $controller = 'student';
        break;
    case Users::TYPE_TEACHER:
        $tpl = 'teacher';
        $controller = 'teacher';
        break;
    case Users::TYPE_ADMIN:
        $tpl = 'admin';
        $controller = 'admin';
        break;
    default:
        $tpl = 'student';
        $controller = 'student';
        break;
}
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

<!-- begin .alert-messages-->
<?= $this->render('alert-messages', ['CurrentUser' => $CurrentUser]) ?>
<!-- end .alert-messages-->

<div class="page">


    <?= $this->render("member-{$tpl}-header", [
        'CurrentUser'   => $CurrentUser,
        'static_action' => $static_action,
        'tpl' => $tpl,
        'MENU' => $MENU,
        'controller' => $controller,
    ]) ?>


    <?= $content ?>

    <!--begin .page-footer-->
    <?= $this->render('member-footer', [
        'CurrentUser' => $CurrentUser,
        'static_action' => $static_action,
        'MENU' => $MENU,
    ]) ?>
    <!--end .page-footer-->


</div>

<?= $this->render('../modals/common-modal', ['CurrentUser' => $CurrentUser]) ?>
<?= $this->render('../modals/chat-modal', ['CurrentUser' => $CurrentUser]) ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
