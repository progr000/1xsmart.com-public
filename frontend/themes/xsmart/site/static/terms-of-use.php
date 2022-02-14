<?php

/** @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Html::encode(Yii::t('static/terms-of-use', 'title', ['APP_NAME' => Yii::$app->name]));

?>

<div class="content">

    <?= Yii::t('static/terms-of-use', 'text', ['APP_NAME' => Yii::$app->name]) ?>

</div>