<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $presetsModel \frontend\models\search\PresetsSearch */
/** @var $presetsDataProviderAwaiting \yii\data\ActiveDataProvider */
/** @var $presetsDataProviderApproved \yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\Presets;
use frontend\assets\smartsing\common\PresetsListAsset;

$this->title = Html::encode('Пресеты | Admin area');

PresetsListAsset::register($this);

?>
<div class="dashboard">
    <h1 class="page-title">Пресеты</h1>

    <section class="section">

        <?php Pjax::begin([
            'id' => 'presets-awaiting-list-content',
            'timeout' => PJAX_TIMEOUT,
        ]); ?>

            <div class="flex-header">
                <h2 class="page-title">Пресеты заявки</h2>

                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['admin/presets'],
                    'id' => 'filter-search-presets-list',
                    'options' => [
                        'data' => ['pjax' => true
                        ],
                        'class' => 'filter-search-presets-list',
                    ],
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                            //'class' => 'input-wrap',
                        ],
                        'template' => '{input}',
                    ]
                ]); ?>
                <div class="filter filter--search">
                    <div class="filter__item">

                        <?= $form->field($presetsModel, 'filter1')
                            ->textInput([
                                'placeholder' => "Поиск",
                                'autocomplete' => "off",
                                'aria-label'   => "Поиск",
                                'class' => "search-input search-input--w260",
                            ])
                            ->label(false)
                        ?>

                    </div>
                    <div class="filter__item">
                        <label for="date-sort">Сортировка по:</label>
                        <div class="filter__select-wrap select-wrap select-wrap--w260">
                            <?php
                            $options = [
                                'sort-asc-created' => [
                                    'data-sort-val' => "created",
                                    'data-name' => "дате создания &darr;",
                                ],
                                'sort-desc-created' => [
                                    'data-sort-val' => "-created",
                                    'data-name' => "дате создания &uarr;",
                                ],
                                'sort-asc-id' => [
                                    'data-sort-val' => "methodist",
                                    'data-name' => "Методисту &darr;",
                                ],
                                'sort-desc-id' => [
                                    'data-sort-val' => "-methodist",
                                    'data-name' => "Методисту &uarr;",
                                ],
                                'sort-asc-name' => [
                                    'data-sort-val' => "name",
                                    'data-name' => "Названию &darr;",
                                ],
                                'sort-desc-name' => [
                                    'data-sort-val' => "-name",
                                    'data-name' => "Названию &uarr;",
                                ],
                            ];
                            $items = [];
                            foreach ($options as $k=>$v) {
                                $items[$k] = $v['data-name'];
                            }
                            ?>
                            <?= $form->field($presetsModel, 'sort1', [
                                'inputOptions' => [
                                    'class' => 'list-search-sort',
                                    'id' => 'presets-list-search-sort1',
                                ],
                            ])
                                ->dropDownList($items, [
                                    'encode' => false,
                                    'options' => $options,
                                ])
                                ->label(false)
                            ?>
                        </div>
                        <input type="hidden" name="sort" id="sort-data1">
                        <input type="submit" id="submit-filter-presets-list1" style="display: none;">

                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
            <?=
            ListView::widget([
                'dataProvider' => $presetsDataProviderAwaiting,
                //'itemOptions' => ['class' => 'item'],
                'itemOptions' => [
                    'tag' => false,
                    'class' => '',
                ],
//                'sorter' => [
//                    'options' => [
//                        'class' => 'dropdown-menu'
//                    ],
//                    'attributes' => [
//                        't1.preset_created',
//                        't1.preset_name',
//                        't1.methodist_user_id',
//                    ]
//                ],
                'layout' => '
                    <!--{___sorter}-->
                    <div class="presets-catalog">

                        <div class="hw-grid">

                            {items}

                        </div>

                        <div class="pages">
                            {pager}
                        </div>

                    </div>
                ',
                'emptyText' => '<div class="presets-empty">Еще нет новых пресетов-заявок</div>', //$this->render('presets-list-nodata'),
                'emptyTextOptions' => ['tag' => false],
                'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
                    /** @var $model \common\models\Presets */
                    /** @var \common\models\Users $methodist */
                    $methodist = $model->getMethodistUser();
                    return '
                        <div class="hw-grid__item">
                            <div class="preset-card preset-card--white win win win--lg">
                                <div class="win__top"></div>
                                <a class="preset-card__media" href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">
                                    <img src="' . Yii::$app->params['presetsDirWeb'] . "/" . $model->preset_image . '" alt="" />
                                </a>
                                <div class="preset-card__cell">
                                    <a class="preset-card__title" href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">' . $model->preset_name . '<span class="preset-card__id">(ID: ' . $model->preset_id . ')</span></a>
                                </div>
                                <div class="preset-card__section">
                                    <div class="preset-card__data">
                                        <div class="preset-card__cell">
                                            <div class="user-block user-block--sm">
                                                <div class="user-block__ava">
                                                    <img src="' . $methodist->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '" alt="" />
                                                </div>
                                                <div class="user-block__data">
                                                    <div class="user-block__position">Методист (ID: ' . $model->methodist_user_id . ')</div>
                                                    <div class="user-block__name">
                                                        <a class="user-block__name-link js-open-modal-user-info void-0"
                                                           href="#"
                                                           data-user-id="' . $methodist->user_id . '"
                                                           data-methodist-name="' . $methodist->user_first_name . '"
                                                           data-methodist-phone="' . $methodist->user_phone . '"
                                                           data-modal-id="methodist-info-modal">' . $methodist->user_first_name . '</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preset-card__cell">
                                            <div class="item">
                                                <div class="item__label">Дата</div>
                                                <div class="item__value">' . $CurrentUser->getDateInUserTimezoneByDateString($model->preset_created) . '</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preset-card__controls bid-controls">
                                        <a class="bid-controls__btn bid-controls__btn--accept btn"
                                           data-pjax="0"
                                           href="' . Url::to(['admin/change-preset-status', 'status' => Presets::STATUS_APPROVED, 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">Подтвердить</a>
                                        <a class="bid-controls__btn bid-controls__btn--cancel btn"
                                           data-pjax="0"
                                           href="' . Url::to(['admin/change-preset-status', 'status' => Presets::STATUS_REJECTED, 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">Отказать</a>
                                        <a class="bid-controls__btn bid-controls__btn--delete btn js-preset-remove void-0"
                                           data-pjax="0"
                                           data-href="' . Url::to(['user/delete-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '"
                                           href="#">Удалить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                },
            ]);
            ?>

        <?php Pjax::end(); ?>

    </section>

    <section class="section">

        <?php Pjax::begin([
            'id' => 'presets-approved-list-content',
            'timeout' => PJAX_TIMEOUT,
        ]); ?>

            <div class="flex-header">
                <h2 class="page-title">Пресеты в работе</h2>

                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['admin/presets'],
                    'id' => 'filter-search-presets-list2',
                    'options' => [
                        'data' => ['pjax' => true
                        ],
                        'class' => 'filter-search-presets-list',
                    ],
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                            //'class' => 'input-wrap',
                        ],
                        'template' => '{input}',
                    ]
                ]); ?>
                <div class="filter filter--search">
                    <div class="filter__item">

                        <?= $form->field($presetsModel, 'filter2')
                            ->textInput([
                                'placeholder' => "Поиск",
                                'autocomplete' => "off",
                                'aria-label'   => "Поиск",
                                'class' => "search-input search-input--w260",
                            ])
                            ->label(false)
                        ?>

                    </div>
                    <div class="filter__item">
                        <label for="date-sort">Сортировка по:</label>
                        <div class="filter__select-wrap select-wrap select-wrap--w260">
                            <?php
                            $options = [
                                'sort-asc-created' => [
                                    'data-sort-val' => "created",
                                    'data-name' => "дате создания &darr;",
                                ],
                                'sort-desc-created' => [
                                    'data-sort-val' => "-created",
                                    'data-name' => "дате создания &uarr;",
                                ],
                                'sort-asc-id' => [
                                    'data-sort-val' => "methodist",
                                    'data-name' => "Методисту &darr;",
                                ],
                                'sort-desc-id' => [
                                    'data-sort-val' => "-methodist",
                                    'data-name' => "Методисту &uarr;",
                                ],
                                'sort-asc-name' => [
                                    'data-sort-val' => "name",
                                    'data-name' => "Названию &darr;",
                                ],
                                'sort-desc-name' => [
                                    'data-sort-val' => "-name",
                                    'data-name' => "Названию &uarr;",
                                ],
                            ];
                            $items = [];
                            foreach ($options as $k=>$v) {
                                $items[$k] = $v['data-name'];
                            }
                            ?>
                            <?= $form->field($presetsModel, 'sort2', [
                                'inputOptions' => [
                                    'class' => 'list-search-sort',
                                    'id' => 'presets-list-search-sort2',
                                ],
                            ])
                                ->dropDownList($items, [
                                    'encode' => false,
                                    'options' => $options,
                                ])
                                ->label(false)
                            ?>
                        </div>
                        <input type="hidden" name="sort" id="sort-data2">
                        <input type="submit" id="submit-filter-presets-list2" style="display: none;">

                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
            <?=
            ListView::widget([
                'dataProvider' => $presetsDataProviderApproved,
                //'itemOptions' => ['class' => 'item'],
                'itemOptions' => [
                    'tag' => false,
                    'class' => '',
                ],
                'layout' => '
                    <div class="presets-catalog">

                        <div class="hw-grid">

                            {items}

                        </div>

                        <div class="pages">
                            {pager}
                        </div>

                    </div>
                ',
                'emptyText' => '<div class="presets-empty">Еще нет пресетов в работе</div>', //$this->render('presets-list-nodata'),
                'emptyTextOptions' => ['tag' => false],
                'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
                    /** @var $model \common\models\Presets */
                    /** @var \common\models\Users $methodist */
                    $methodist = $model->getMethodistUser();
                    return '
                        <div class="hw-grid__item">
                            <div class="preset-card preset-card--white win win win--lg">
                                <div class="win__top"></div>
                                <a class="preset-card__media" href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">
                                    <img src="' . Yii::$app->params['presetsDirWeb'] . "/" . $model->preset_image . '" alt="" />
                                </a>
                                <div class="preset-card__cell">
                                    <a class="preset-card__title" href="' . Url::to(['user/view-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '">' . $model->preset_name . '<span class="preset-card__id">(ID: ' . $model->preset_id . ')</span></a>
                                </div>
                                <div class="preset-card__section">
                                    <div class="preset-card__data">
                                        <div class="preset-card__cell">
                                            <div class="user-block user-block--sm">
                                                <div class="user-block__ava">
                                                    <img src="' . $methodist->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '" alt="" />
                                                </div>
                                                <div class="user-block__data">
                                                    <div class="user-block__position">Методист (ID: ' . $model->methodist_user_id . ')</div>
                                                    <div class="user-block__name">
                                                        <a class="user-block__name-link js-open-modal-user-info void-0"
                                                           href="#"
                                                           data-user-id="' . $methodist->user_id . '"
                                                           data-methodist-name="' . $methodist->user_first_name . '"
                                                           data-methodist-phone="' . $methodist->user_phone . '"
                                                           data-modal-id="methodist-info-modal">' . $methodist->user_first_name . '</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preset-card__cell">
                                            <div class="item">
                                                <div class="item__label">Дата</div>
                                                <div class="item__value">' . $CurrentUser->getDateInUserTimezoneByDateString($model->preset_created) . '</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preset-card__controls bid-controls">
                                        <a class="bid-controls__btn bid-controls__btn--delete btn js-preset-remove void-0"
                                           data-pjax="0"
                                           data-href="' . Url::to(['user/delete-preset', 'id' => $model->preset_id], CREATE_ABSOLUTE_URL) . '"
                                           href="#">Удалить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                },
            ]);
            ?>

        <?php Pjax::end(); ?>

    </section>
</div>



<?= $this->render("../modals/admin-modals/methodist-info-modal") ?>

