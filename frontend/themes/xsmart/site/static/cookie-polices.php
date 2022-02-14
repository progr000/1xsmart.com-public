<?php

/** @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Html::encode(Yii::t('static/cookie-polices', 'title', ['APP_NAME' => Yii::$app->name]));

?>

<div class="content">

    <?= Yii::t('static/cookie-polices', 'text', ['APP_NAME' => Yii::$app->name]) ?>

</div>