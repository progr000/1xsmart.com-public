<?php

/** @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Html::encode(Yii::t('static/privacy-policy', 'title', ['APP_NAME' => Yii::$app->name]));

?>

<div class="content">

    <?= Yii::t('static/privacy-policy', 'text', ['APP_NAME' => Yii::$app->name]) ?>

</div>