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

switch ($CurrentUser->user_type) {
    case Users::TYPE_STUDENT:
        $tpl = 'student';
        $controller = 'student';
        break;
    case Users::TYPE_METHODIST:
        $tpl = 'methodist';
        $controller = 'methodist';
        break;
    case Users::TYPE_TEACHER:
        $tpl = 'teacher';
        $controller = 'teacher';
        break;
    case Users::TYPE_OPERATOR:
        $tpl = 'operator';
        $controller = 'operator';
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

<body lang="<?= Yii::$app->language ?>"
      data-is-debug="<?= YII_DEBUG ? 1 : 0 ?>"
      data-flash-timeout="<?= Yii::$app->params['FLASH_TIMEOUT'] ?>"
      data-default-lang="<?= Yii::$app->sourceLanguage ?>"
      data-uid="<?= $CurrentUser ? $CurrentUser->user_id : "null" ?>">
<?php $this->beginBody() ?>

<!-- begin .alert-messages-->
<?= $this->render('alert-messages', ['CurrentUser' => $CurrentUser]) ?>
<!-- end .alert-messages-->

<div class="page">


    <?= $content ?>


</div>

<?= $this->render('../modals/common-modal', ['CurrentUser' => $CurrentUser]) ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
