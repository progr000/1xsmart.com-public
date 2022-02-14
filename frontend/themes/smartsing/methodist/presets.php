<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $presetAddForm \frontend\models\forms\AddPresetForm */
/** @var $presetsModel \frontend\models\search\PresetsSearch */
/** @var $presetsDataProvider \yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\Presets;
use frontend\assets\smartsing\common\PresetsListAsset;

$this->title = Html::encode('Пресеты | Methodist area');

PresetsListAsset::register($this);

?>
<div class="dashboard">
    <h1 class="page-title">Пресеты</h1>
    <p>В данном разделе Вы можете создавать и загружать собственные шаблоны распевок/занятий на тренажере для голоса. Пресеты создаются в программе <a href="https://musescore.org/en/download" target="_blank"><strong>«Muse Score 3»</strong></a>. Методисты и учителя создающие уникальные качественные пресеты получают приоритетный доступ к новым ученикам.</p>

    <?php $form = ActiveForm::begin([
        'id' => 'form-add-preset',
        'action' => ['/methodist/add-preset'],
        'options' => [
            'class'    => "new-preset-frm",
        ],
    ]); ?>
        <div class="form-section">
            <div class="form-section__title">Добавить пресет</div>
            <div class="form-row">

                <?= $form->field($presetAddForm, 'preset_name', [
                    'options' => ['class' => 'form-group form-col form-col--wide']
                ])
                    ->textInput([
                        'placeholder' => 'Заголовок',
                        'autocomplete' => "off",
                        'aria-label'   => 'Заголовок',
                    ])
                    ->label(false)
                ?>

                <?= $form->field($presetAddForm, 'preset_description', [
                    'options' => ['class' => 'form-group form-col form-col--wide']
                ])
                    ->textarea([
                        'placeholder' => 'Описание',
                        'autocomplete' => "off",
                        'aria-label'   => 'Описание',
                    ])
                    ->label(false)
                ?>

                <?= $form->field($presetAddForm, 'preset_upl_file', [
                    'options' => ['class' => 'form-group form-col form-col--wide']
                ])
                    ->fileInput([
                        'placeholder' => 'Музыкальный XML файл',
                        'autocomplete' => "off",
                        'aria-label'   => 'Музыкальный XML файл',
                    ])
                    ->label('Музыкальный XML файл: ')
                ?>

                <?= $form->field($presetAddForm, 'preset_upl_image', [
                    'options' => ['class' => 'form-group form-col form-col--wide']
                ])
                    ->fileInput([
                        'placeholder' => 'Файл картинки',
                        'autocomplete' => "off",
                        'aria-label'   => 'Файл картинки',
                    ])
                    ->label('Файл картинки: ')
                ?>

                <?= $form->field($presetAddForm, 'preset_level', [
                    'options' => ['class' => 'form-col form-col--2_3 select-wrap']
                ])->dropDownList(Presets::getLevels(), [
                    //'id'         => "profile-bday",
                    'class'      => "js-select",
                    'aria-label' => "Уровень сложности",
                    'data-placeholder' => "Уровень сложности",
                ])->label(false);
                ?>

                <div class="form-col form-col--1_3"><button class="btn primary-btn primary-btn--c6" type="submit">Добавить</button></div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

    <div class="presets-catalog">

        <?php Pjax::begin([
            'id' => 'presets-list-content',
            'timeout' => PJAX_TIMEOUT,
        ]); ?>

        <?=
        ListView::widget([
            'dataProvider' => $presetsDataProvider,
            //'itemOptions' => ['class' => 'item'],
            'itemOptions' => [
                'tag' => false,
                'class' => '',
            ],
            'layout' => '
            <div class="hw-grid">

                {items}

            </div>

            <div class="pages">
                {pager}
            </div>
            ',
            'emptyText' => '<div class="presets-empty">Еще нет загруженных пресетов</div>', //$this->render('presets-list-nodata'),
            'emptyTextOptions' => ['tag' => false],
            'itemView' => function ($model, $key, $index, $widget) {
                /** @var $model \common\models\Presets */
                return '
                <div class="hw-grid__item">
                    <div class="preset-card preset-card--white win win win--lg">
                        <div class="win__top"></div>
                        <a class="preset-card__media" href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">
                            <img src="' . Yii::$app->params['presetsDirWeb'] . "/" . $model->preset_image . '" alt="" />
                        </a>
                        <div class="preset-card__header">
                            <div class="preset-card__info">
                                <a class="preset-card__title"
                                   data-pjax="0"
                                   href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">Пресет <b>' . $model->preset_name . '</b></a>
                                <div class="preset-card__state">' . Presets::getStatus($model->preset_status) . '</div>
                            </div>
                            <a class="preset-card__remove remove-btn btn js-preset-remove void-0"
                               data-pjax="0"
                               data-href="' . Url::to(['user/delete-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '"
                               href="#">
                                <svg class="svg-icon--close svg-icon" width="10" height="10">
                                    <use xlink:href="#close"></use>
                                </svg>
                                <span>удалить</span>
                            </a>
                        </div>
                    </div>
                </div>
                ';
            },
        ]);
        ?>

        <?php Pjax::end(); ?>

    </div>
</div>
