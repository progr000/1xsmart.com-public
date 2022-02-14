<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $modelSearch \frontend\models\search\OperatorsListSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $newOperatorForm \common\models\Users */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use frontend\assets\smartsing\admin\UsersListAsset;

UsersListAsset::register($this);

$this->title = Html::encode('Список операторов | Admin area');

?>

<?php Pjax::begin([
    'id' => 'operators-list-content',
    'timeout' => PJAX_TIMEOUT,
    'options'=> ['class' => 'dashboard']
]); ?>

<a class="new-object-link js-open-modal btn-add-new-operator js-btn-add-new-operator void-0"
   href="#"
   data-is-new="1"
   data-user_id="0"
   data-user_first_name=""
   data-user_middle_name=""
   data-user_last_name=""
   data-user_phone=""
   data-user_email=""
   data-_user_skype=""
   data-_user_telegram=""
   data-additional_service_notice=""
   data-admin_notice=""
   data-modal-id="new-operator-modal">
            <span class="new-object-link__icon-wrap">
                <svg class="svg-icon--plus svg-icon" width="13" height="13">
                    <use xlink:href="#plus"></use>
                </svg>
            </span>
    <span class="new-object-link__text">Добавить оператора</span>
</a>

<div class="flex-header h1">
    <h1>Список операторов</h1>


    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['admin/operators-list'],
        'id' => 'filter-search-operators-list',
        'options' => [
            'data' => ['pjax' => true
            ],
            'class' => 'filter-search-list',
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

            <?= $form->field($modelSearch, 'filter')
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
                        'data-name' => "дате начала работы &darr;",
                    ],
                    'sort-desc-created' => [
                        'data-sort-val' => "-created",
                        'data-name' => "дате начала работы &uarr;",
                    ],
                    'sort-asc-id' => [
                        'data-sort-val' => "id",
                        'data-name' => "ID &darr;",
                    ],
                    'sort-desc-id' => [
                        'data-sort-val' => "-id",
                        'data-name' => "ID &uarr;",
                    ],
                    'sort-asc-name' => [
                        'data-sort-val' => "name",
                        'data-name' => "ФИО &darr;",
                    ],
                    'sort-desc-name' => [
                        'data-sort-val' => "-name",
                        'data-name' => "ФИО &uarr;",
                    ],
                ];
                $items = [];
                foreach ($options as $k=>$v) {
                    $items[$k] = $v['data-name'];
                }
                ?>
                <?= $form->field($modelSearch, 'sort', [
                    'inputOptions' => [
                        'class' => 'list-search-sort',
                        'id' => 'operatorslistsearch-sort',
                    ],
                ])
                    ->dropDownList($items, [
                        'encode' => false,
                        'options' => $options,
                    ])
                    ->label(false)
                ?>
            </div>
            <input type="hidden" name="sort" id="sort-data" class="field-sort-data">
            <input type="submit" id="submit-filter-operators-list" style="display: none;">

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?=
ListView::widget([
    'dataProvider' => $dataProvider,
    //'itemOptions' => ['class' => 'item'],
    'itemOptions' => [
        'tag' => false,
        'class' => '',
    ],
    'summary' => 'Страница <b>{page, number}</b>. Показаны записи с <b>{begin, number}</b> по <b>{end, number}</b> из <b>{totalCount, number}</b>.',

    'layout' => '

        <section class="section">

            <div class="info-sowed-num-off-num">

                {summary}

            </div>

            <div class="coaches-details-list">

                {items}

            </div>

            <div class="pages">

                {pager}

            </div>

        </section>
    ',
    'emptyText' => '<div class="presets-empty">' . ($modelSearch->filter ? 'По заданному поиску нет результатов' : 'Еще нет методистов' ) . '</div>', //$this->render('presets-list-nodata'),
    'emptyTextOptions' => ['tag' => false],
    'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
        /** @var $model \frontend\models\search\OperatorsListSearch */


        /* online status */
        if ($model->_user_online) {
            $online_status = '<div class="user-details__data-item-label color-green">Онлайн</div>';
        } else {
            $online_status = '
                <div class="user-details__data-item-label">Был онлайн</div>
                <div class="user-details__data-item-value">
                    ' . $CurrentUser->getUserOnlineStatus($model->_user_last_visit) . '
                </div>
            ';
        }


        /* comments */
        $comments_short =
            $model->user_additional_info . ' ' .
            $model->admin_notice . ' ' .
            $model->additional_service_notice . ' ' .
            $model->additional_service_info;
        $comments =
            "<hr />" .
            "<b>Пользователь о себе:</b><br />{$model->user_additional_info}<hr />" .
            "<b>Админ:</b><br />{$model->admin_notice}<hr />" .
            "<b>Общая информация:</b><br />{$model->additional_service_notice}<hr />" .
            "<b>Сервисная информация:</b><br />{$model->additional_service_info}<hr />";


        /* return html */
        return '
            <!--.coach-details-->
            <div class="coach-details coach-details--operator coaches-details-list__item">
                <!-- -->
                <div class="coach-details__sidebar">
                    <div class="user-details coach-details__user">
                        <!-- фото методиста -->
                        <div class="user-details__ava js-messaging">
                            <img class="user-details__ava-img"
                                 src="' . $model->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                                 alt=""
                                 role="presentation" />
                            <button class="user-details__dialog-btn dialog-btn btn messaging js-open-messaging"
                                    type="button"
                                    data-user-id="' . $model->user_id . '"
                                    data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $model->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                                <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                    <use xlink:href="#dialog"></use>
                                </svg>
                            </button>
                        </div>
                        <!-- данные о методисте -->
                        <div class="user-details__info">
                            <a class="user-details__name link js-open-modal-user-info void-0"
                               href="#"
                               data-user-id="' . $model->user_id . '"
                               data-modal-id="operator-info-modal"><span>' . $model->user_full_name . '</span></a>
                            <div class="user-details__data">
                                <div class="user-details__data-item">
                                    <div class="user-details__data-item-label">ID: ' . $model->user_id . '</div>
                                </div>
                                <div class="user-details__data-item">
                                    ' . $online_status . '
                                </div>
                            </div>
                            <br />
                            <a class="color-red font-bold link js-open-modal js-btn-add-new-operator void-0"
                               href="#"
                               data-is-new="0"
                               data-user_id="' . $model->user_id . '"
                               data-user_first_name="' . $model->user_first_name . '"
                               data-user_middle_name="' . $model->user_middle_name . '"
                               data-user_last_name="' . $model->user_last_name . '"
                               data-user_phone="' . $model->user_phone . '"
                               data-user_email="' . $model->user_email . '"
                               data-_user_skype="' . $model->_user_skype . '"
                               data-_user_telegram="' . $model->_user_telegram . '"
                               data-additional_service_notice="' . $model->additional_service_notice . '"
                               data-admin_notice="' . $model->admin_notice . '"
                               data-lead-id="0"
                               data-modal-id="new-operator-modal">Изменить</a>
                            <br />
                            <a class="color-green font-bold link"
                               target="_blank"
                               href="' . Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token], false) . '">Войти в акк</a>
                        </div>
                    </div>
                </div>
                <!-- -->
                <div class="coach-details__main">
                    <!-- -->
                    <div class="coach-details__top">
                        <div class="stat-grid">
                            <div class="stat-grid__section">
                                <div class="stat-item">
                                    <div class="stat-item__num"><a href="javascript:;" data-modal-id="phones-students-modal" class="js-open-modal highlight-c2">7</a></div>
                                    <div class="stat-item__desc">Назначенных вводных уроков за сегодня</div>
                                </div>
                            </div>
                            <div class="stat-grid__section">
                                <div class="stat-item">
                                    <div class="stat-item__num"><a href="javascript:;" data-modal-id="phones-students-modal" class="js-open-modal ">61</a></div>
                                    <div class="stat-item__desc">Звонков за сегодня</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -->
                    <div class="coach-details__bottom">
                        <!-- события оператора -->
                        <div class="coach-details__section">
                            <div class="dynamic-data _loaded">
                                <div class="dynamic-data__title">События</div>
                                <div class="dynamic-data__data">
                                    <div class="events-brief-list">
                                        <div class="event-brief events-brief-list__item">
                                            <div class="event-brief__student">
                                                <div class="event-brief__title">Принял <b>звонок</b> с номера 313 13 44 331</div>
                                            </div>
                                            <div class="event-brief__date">21/05/2020, 15:30</div>
                                        </div>
                                        <div class="event-brief events-brief-list__item">
                                            <div class="event-brief__student">
                                                <div class="event-brief__title">Ответил <b>в чате</b> на сообщение от</div>
                                                <div class="event-brief__user"><img class="event-brief__ava-img" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" /><a class="event-brief__name-link js-open-modal" href="javascript:;" data-modal-id="customer-full-modal">Данила</a></div>
                                            </div>
                                            <div class="event-brief__date">21/05/2020, 15:30</div>
                                        </div>
                                        <div class="event-brief events-brief-list__item">
                                            <div class="event-brief__student">
                                                <div class="event-brief__title">Отправил <b>сообщение</b> на bagd@fgmail.com</div>
                                            </div>
                                            <div class="event-brief__date">21/05/2020, 15:30</div>
                                        </div>
                                    </div>
                                </div>
                                <a class="dynamic-data__more-link js-open-modal"
                                   href="javascript:;"
                                   data-modal-id="show-all-events-modal">Смотреть все</a>
                            </div>
                        </div>

                        <!-- коменты к оператору -->
                        <div class="coach-details__section">
                            <div class="dynamic-data _loaded">
                                <div class="dynamic-data__title">Коментарии и информация</div>
                                <div class="dynamic-data__data">
                                    <div class="events-brief-list">
                                        ' .
                                        mb_substr(
                                            $comments_short,
                                            0,
                                            200
                                        ) .
                                        '
                                    </div>
                                </div>
                                <a class="dynamic-data__more-link js-open-modal js-full-comment-link void-0"
                                   href="#"
                                   data-comment-title="Комментарии и информация"
                                   data-comment-full-text="' . $comments . '"
                                   data-modal-id="comment-text-modal">Читать полностью</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--.coach-details-->
        ';
    },
]);
?>

<?php Pjax::end(); ?>



<?= $this->render("../modals/admin-modals/new-operator-modal", ['newOperatorForm' => $newOperatorForm]) ?>
<?= $this->render("../modals/admin-modals/operator-info-modal") ?>
<?= $this->render("../modals/admin-modals/show-all-events-modal") ?>
