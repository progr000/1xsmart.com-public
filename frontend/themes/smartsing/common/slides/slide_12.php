<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */

use yii\bootstrap\ActiveForm;
use common\models\Users;
use common\models\MethodistTimeline;

$model = new Users();

$model->user_level_general_notice = "У вас есть природные задатки для пения. Однако для совершенствования бархатистости и певучести имеется достаточно большой потенциал для развития";
$model->user_level_general = 4;
$model->user_level_range_notice = "Ваш рабочий диапазон находится в средних границах, вы достаточной устойчиво можете взять ноты в своем рабочем диапазоне";
$model->user_level_range = 4;
$model->user_level_coordination_notice = "Вы неплохо координируете слух и голос в диапазоне С3-С4 что составляет меньше вашего рабочего диапазона. Вы можете подпеть в несложных песнях";
$model->user_level_coordination =4;
$model->user_level_timbre_notice = "У вас есть природные задатки для пения. Однако для совершенствования бархатистости и певучести имеется достаточно большой потенциал для развития";
$model->user_level_timbre = 6;

$model->notes_played = 15;
$model->notes_hit = 6;
$model->notes_close = 2;
$model->notes_lowest = "C3";
$model->notes_highest = "C4";

$model->_lesson_status;
?>
<div class="present__text is-not-for-send">

    <?php if ($CurrentUser->user_type == Users::TYPE_METHODIST) { ?>
        <h1 class="present__title">Завершение <span class="highlight-c1">вводного урока</span> в школе вокала <span class="highlight-c1">Smart Sing</span></h1>
        <div class="present__desc">
            Тут вы как методист должны выставить результирующие данные по ученику и отправить их на сервер.
            После этого ученик увидит страницу с результатами и предложением перейти к оплате
        </div>
    <?php } else { ?>
        <h1 class="present__title">Завершение <span class="highlight-c1">регулярного урока</span> в школе вокала <span class="highlight-c1">Smart Sing</span></h1>
        <div class="present__desc">
            Тут вы как преподаватель должны выставить результирующие данные по уроку и отправить их на сервер.
        </div>
    <?php } ?>

    <?php $form = ActiveForm::begin([
        'id' => 'result-form',
        //'action' => null,
        'options' => [
            'onsubmit'   => "return false",
            'class'    => "result-frm",
        ],
        //'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        //'validateOnSubmit' => false,
        'fieldConfig' => [
            'options' => [
                'tag' => 'div',
                'class' => 'input-wrap',
            ],
            'template' => '{label}{input}{hint}{error}',
        ]
    ]); ?>

    <input type="hidden" name="room_hash" class="data-inputs" id="room_hash" />
    <input type="hidden" name="student_user_id" class="data-inputs" id="student_user_id" />

    <?php
    $lesson_statuses = MethodistTimeline::$_lesson_statuses;
    unset($lesson_statuses[MethodistTimeline::STATUS_AWAIT]);
    ?>
    <?= $form->field($model, "_lesson_status")
        ->dropDownList($lesson_statuses, [
            'id' => "_lesson_status",
            'aria-label'   => "Статус урока",
            'class' => "-editable-input data-inputs -inp-count-notes results",
        ])
        ->label("Статус урока:")
    ?>

    <?= $form->field($model, "_lesson_notice")
        ->textarea([
            'id' => "_lesson_notice",
            'placeholder' => "Кратко о проведенном уроке. Поле обязательно к заполнению",
            'autocomplete' => "off",
            'aria-label'   => "Об уроке",
            'class' => "data-inputs results",
        ])
        ->label(false)
    ?>

    <hr />



    <?php if ($CurrentUser->user_type == Users::TYPE_METHODIST) { ?>

        <?= $form->field($model, "user_level_general")
            ->dropDownList(Users::$_general_levels, [
                'id' => "user_level_general",
                'aria-label'   => $model->getAttributeLabel('user_level_general'),
                'class' => "-editable-input data-inputs -inp-count-notes results",
            ])
            ->label("Общий уровень:")
        ?>

        <?= $form->field($model, "user_level_general_notice")
            ->textarea([
                'id' => "user_level_general_notice",
                'placeholder' => "Короткое описание общего уровня ученика",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_general_notice'),
                'class' => "data-inputs results",
            ])
            ->label(false)
        ?>

        <hr />

        <?= $form->field($model, "user_level_range")
            ->textInput([
                'id' => "user_level_range",
                'placeholder' => "",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_range'),
                'class' => "-editable-input data-inputs inp-count-notes results",
            ])
            ->label("Диапазон (0-" . Users::level_range_max . "):")
            ->hint('если вы считаете что эти параметры у ученика лучше, то увеличьте значения')
        ?>

        <?= $form->field($model, "user_level_range_notice")
            ->textarea([
                'id' => "user_level_range_notice",
                'placeholder' => "Короткое описание диапазона",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_range_notice'),
                'class' => "data-inputs results",
            ])
            ->label(false)
        ?>

        <hr />

        <?= $form->field($model, "user_level_coordination")
            ->textInput([
                'id' => "user_level_coordination",
                'placeholder' => "",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_coordination'),
                'class' => "-editable-input data-inputs inp-count-notes results",
            ])
            ->label("Координация (0-" . Users::level_coordination_max . "):")
            ->hint('если вы считаете что эти параметры у ученика лучше, то увеличьте значения')
        ?>

        <?= $form->field($model, "user_level_coordination_notice")
            ->textarea([
                'id' => "user_level_coordination_notice",
                'placeholder' => "Короткое описание координации слуха и голоса",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_coordination_notice'),
                'class' => "data-inputs results",
            ])
            ->label(false)
        ?>

        <hr />

        <?= $form->field($model, "user_level_timbre")
            ->textInput([
                'id' => "user_level_timbre",
                'placeholder' => "",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_timbre'),
                'class' => "-editable-input data-inputs inp-count-notes results",
            ])
            ->label("Тембр (0-" . Users::level_timbre_max . "):")
            ->hint('если вы считаете что эти параметры у ученика лучше, то увеличьте значения')
        ?>

        <?= $form->field($model, "user_level_timbre_notice")
            ->textarea([
                'id' => "user_level_timbre_notice",
                'placeholder' => "Короткое описание тембра голоса, чистоты и бархатистости",
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('user_level_timbre_notice'),
                'class' => "data-inputs results",
            ])
            ->label(false)
        ?>

        <hr />

    <?php } ?>



    <?= $form->field($model, "notes_played")
        ->textInput([
            'id' => "notes_played",
            'placeholder' => "",
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('notes_played'),
            'class' => "-editable-input data-inputs inp-count-notes results",
        ])
        ->label("Нот проиграно:")
    ?>
    <?= $form->field($model, "notes_hit")
        ->textInput([
            'id' => "notes_hit",
            'placeholder' => "",
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('notes_hit'),
            'class' => "-editable-input data-inputs inp-count-notes results",
        ])
        ->label("Нот попал:")
    ?>
    <?= $form->field($model, "notes_close")
        ->textInput([
            'id' => "notes_close",
            'placeholder' => "",
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('notes_close'),
            'class' => "-editable-input data-inputs inp-count-notes results",
        ])
        ->label("Нот не попал (но рядом):")
    ?>
    <?= $form->field($model, "notes_lowest")
        ->textInput([
            'id' => "notes_lowest",
            'placeholder' => "",
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('notes_lowest'),
            'class' => "-editable-input data-inputs inp-count-notes results",
        ])
        ->label("Нижняя нота:")
    ?>
    <?= $form->field($model, "notes_highest")
        ->textInput([
            'id' => "notes_highest",
            'placeholder' => "",
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('notes_highest'),
            'class' => "-editable-input data-inputs inp-count-notes results",
        ])
        ->label("Верхняя нота:")
    ?>

    <hr />

    <button type="button" class="primary-btn primary-btn--c6 send-student-result">Сохранить результаты ученика и завершить урок</button>

    <?php ActiveForm::end(); ?>

</div>