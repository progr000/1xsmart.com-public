<?php

/** @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = Html::encode('Finance | Admin area');

?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['admin/'], CREATE_ABSOLUTE_URL) ?>">Main</a>
    <div class="crumbs__title">Finance</div>
</div>

<div class="bg-wrapper">
    <div class="container">
    </div>
</div>