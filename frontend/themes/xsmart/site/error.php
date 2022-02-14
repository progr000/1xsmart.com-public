<?php

/** @var $name string */
/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;

$this->title = $name;

?>

<div class="content content--no-pad">
    <div class="content-header">
        <h1 class="content-header__title"><?= Html::encode($this->title) ?></h1>
        <div class="content-header__intro"><?= nl2br(Html::encode($message)) ?></div>
    </div>
</div>