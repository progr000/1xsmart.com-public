<?php

/** @var $this yii\web\View */
/** @var $additionalData array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode(Yii::t('static/disciplines', 'title', ['APP_NAME' => Yii::$app->name]));

$this->params['additional_page_class'] = 'earth-bg';

$lang = Yii::$app->language;
$discipline_name_field = "discipline_name_{$lang}";

?>

<div class="content">
    <h1 class="page-title text-center"><?= Yii::t('static/disciplines', 'Disciplines') ?></h1>
    <div class="columns">
        <p><?= Yii::t('static/disciplines', 'Our_company_provides') ?></p>
        <p><?= Yii::t('static/disciplines', 'You_can_be_anywhere') ?></p>
    </div>
    <div class="btns btns--grid">
        <?php
        /** @var \common\models\Disciplines $discipline */
        foreach ($additionalData as $discipline) {
            echo '<a class="secondary-btn" href="' .
                Url::to(['/find-tutors', 'TutorSearch[discipline_id]' => $discipline['discipline_id']], CREATE_ABSOLUTE_URL) .
                '">' . (isset($discipline[$discipline_name_field]) ? $discipline[$discipline_name_field] : $discipline['discipline_name_en']) . '</a>';
        }
        ?>
    </div>
</div>