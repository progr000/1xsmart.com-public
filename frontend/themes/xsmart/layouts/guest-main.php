<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $CurrentUser \common\models\Users */

use common\models\Users;
use frontend\assets\xsmart\guestAsset;

/** init vars */
$static_action = Yii::$app->request->get('action', null);
//$CurrentUser = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;
$CurrentUser = $this->context->CurrentUser;

/*menu*/
require_once('menu-list.php');

/** Register all assets (js + css) */
$this->render('js-css-assets', ['CurrentUser' => $CurrentUser]);
guestAsset::register($this);

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
      data-uid="<?= $CurrentUser ? $CurrentUser->user_id : "null" ?>">
<?php $this->beginBody() ?>

<!-- begin .alert-messages-->
<?= $this->render('alert-messages', ['CurrentUser' => $CurrentUser]) ?>
<!-- end .alert-messages-->

<?php
if (!isset($this->params['additional_header_class'])) { $this->params['additional_header_class'] = ''; }
if (!isset($this->params['additional_header_promo'])) { $this->params['additional_header_promo'] = ''; }
if (!isset($this->params['additional_page_class'])) { $this->params['additional_page_class'] = ''; }
?>
<div class="page <?= $this->params['additional_page_class'] ?>">


    <?php
    if (!$CurrentUser) {
        echo $this->render('/layouts/guest-header', [
            'additional_header_class' => $this->params['additional_header_class'],
            'static_action' => $static_action,
            'MENU' => $MENU,
            'CurrentUser' => $CurrentUser,
        ]);
    } else {
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
        echo $this->render("member-{$tpl}-header", [
            'CurrentUser'   => $CurrentUser,
            'static_action' => $static_action,
            'tpl' => $tpl,
            'MENU' => $MENU,
            'controller' => $controller,
        ]);
    }
    ?>



    <?= $content ?>



    <?php
    if (!$CurrentUser) {
        echo $this->render('guest-footer', [
            'static_action' => $static_action,
            'MENU' => $MENU,
            'CurrentUser' => $CurrentUser,
        ]);
    } else {
        echo $this->render('member-footer', [
            'static_action' => $static_action,
            'MENU' => $MENU,
            'CurrentUser' => $CurrentUser,
        ]);
    }
    ?>

</div>

<?= $this->render('../modals/common-modal', ['CurrentUser' => $CurrentUser]) ?>
<?= $this->render('../modals/guest-modal', ['CurrentUser' => $CurrentUser]) ?>
<?= $CurrentUser ? $this->render('../modals/chat-modal', ['CurrentUser' => $CurrentUser]) : '' ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
