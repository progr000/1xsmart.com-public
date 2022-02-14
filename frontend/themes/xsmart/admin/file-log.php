<?php

/** @var $this yii\web\View */
/** @var $target string */
/** @var $log string */

use yii\helpers\Html;
use yii\helpers\Url;

$title = ucfirst($target);

$this->title = Html::encode("{$title} Log | Admin area");

?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['admin/'], CREATE_ABSOLUTE_URL) ?>">Main</a>
    <div class="crumbs__title"><?= $title ?> Log</div>
</div>

<div class="bg-wrapper">
    <div class="container">
        <textarea aria-label="log"
                  title="log"
                  style="width: 100%; height: 50vh; border: 1px solid #ccc;"><?= $log ?></textarea>
        <br />
        <a class="primary-btn wide-mob-btn"
           style="float: right"
           href="<?= Url::to(['file-log', 'target' => $target, 'clear' => 1], CREATE_ABSOLUTE_URL)?>">Clear log</a>
    </div>
</div>