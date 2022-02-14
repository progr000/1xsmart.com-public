<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $modelSearch \frontend\models\search\MethodistsListSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $newMethodistForm \common\models\Users */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\Users;
use frontend\assets\smartsing\admin\UsersListAsset;

UsersListAsset::register($this);

$this->title = Html::encode('Список методистов | Admin area');

?>

<?php Pjax::begin([
    'id' => 'methodists-list-content',
    'timeout' => PJAX_TIMEOUT,
    'options'=> ['class' => 'dashboard']
]); ?>

<a class="new-object-link js-open-modal btn-add-new-methodist js-btn-add-new-methodist void-0"
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
   data-modal-id="new-methodist-modal">
            <span class="new-object-link__icon-wrap">
                <svg class="svg-icon--plus svg-icon" width="13" height="13">
                    <use xlink:href="#plus"></use>
                </svg>
            </span>
    <span class="new-object-link__text">Добавить методиста</span>
</a>

<div class="flex-header h1">
    <h1>Список методистов</h1>


    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['admin/methodists-list'],
        'id' => 'filter-search-methodists-list',
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
                    'sort-asc-tmt' => [
                        'data-sort-val' => "tmt",
                        'data-name' => "Ближайшему уроку &darr;",
                    ],
                    'sort-desc-tmt' => [
                        'data-sort-val' => "-tmt",
                        'data-name' => "Ближайшему уроку &uarr;",
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
                        'id' => 'methodistslistsearch-sort',
                    ],
                ])
                    ->dropDownList($items, [
                        'encode' => false,
                        'options' => $options,
                    ])
                    ->label(false)
                ?>
                <!--
                <select class="-sort-select -hidden-selected -js-select" id="-methodists-list-sort">
                    <option>ближайшему уроку</option>
                    <option>рейтингу</option>
                </select>
                -->
            </div>
            <input type="hidden" name="sort" id="sort-data" class="field-sort-data">
            <input type="submit" id="submit-filter-methodists-list" style="display: none;">

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
        /** @var $model \frontend\models\search\MethodistsListSearch */


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


        /* students list for methodist */
        $students_container_id = "students-list-container-for-methodist-{$model->user_id}";
        $students_list = '
            <div class="shaded-list__inner users-brief-list users-brief-list-methodist-list"
                 id="' . $students_container_id . '"
                 data-title-for-modal="Ученики методиста ' . $model->user_full_name . '">
        ';
        $Students = $model->getStudentsForThisMethodist();
        if (sizeof($Students)) {
            $i = 1;
            foreach ($Students as $student) {
                /** @var $student \common\models\Users */
                if ($i > 6) {
                    $class = 'users-brief-list__item-more-6';
                } else {
                    $class = '';
                }
                $students_list .= '
                    <div class="user-brief users-brief-list__item ' . $class . ' ' . ($student->_user_online ? '_online' : '') . '">
                        <img class="user-brief__ava-img"
                             src="' . $student->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                             alt=""
                             role="presentation" />
                        <div class="user-brief__data">
                            <div class="user-brief__name">
                                <a class="user-brief__name-link js-open-modal-user-info void-0"
                                    href="#"
                                    data-user-id="' . $student->user_id . '"
                                    data-modal-id="student-info-modal">' . $student->user_first_name . '</a>
                                <button class="user-brief__open-dialog dialog-btn btn messaging js-open-messaging"
                                        type="button"
                                        data-user-id="' . $student->user_id . '"
                                        data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $student->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                                    <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                        <use xlink:href="#dialog"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="user-brief__time-ago">' .($student->_user_online ? 'онлайн' : $CurrentUser->getUserOnlineStatus($student->_user_last_visit)) . '</div>
                        </div>
                    </div>
                ';
                $i++;
            }
        } else {
            $students_list .= 'Нет учеников, закрепленных за этим методистом';
        }
        $students_list .= '
            </div>
        ';
        if (sizeof($Students) > 0) {
            $students_list .= '
            <div class="shaded-list__right">
                <a class="shaded-list__more-link js-open-modal js-show-all-data void-0"
                   href="#"
                   data-modal-id="show-all-users-modal"
                   data-get-content-from-div-id="' . $students_container_id . '">Смотреть все</a>
            </div>
        ';
        }


        /* teacher list for methodist */
        $teacher_container_id = "teacher-list-container-for-methodist-{$model->user_id}";
        $teacher_list = '
            <div class="shaded-list__inner users-brief-list users-brief-list-methodist-list"
                 id="' . $teacher_container_id . '"
                 data-title-for-modal="Учителя методиста ' . $model->user_full_name . '">
        ';
        $Teachers = $model->getTeachersForThisMethodist();
        if (sizeof($Teachers)) {
            $i = 1;
            foreach ($Teachers as $teacher) {
                /** @var $teacher \common\models\Users */
                if ($i > 6) {
                    $class = 'users-brief-list__item-more-6';
                } else {
                    $class = '';
                }
                $teacher_list .= '
                    <div class="user-brief users-brief-list__item ' . $class . ' ' . ($teacher->_user_online ? '_online' : '') . '">
                        <img class="user-brief__ava-img"
                             src="' . $teacher->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                             alt=""
                             role="presentation" />
                        <div class="user-brief__data">
                            <div class="user-brief__name">
                                <a class="user-brief__name-link js-open-modal-user-info void-0"
                                    href="#"
                                    data-user-id="' . $teacher->user_id . '"
                                    data-modal-id="teacher-info-modal">' . $teacher->user_first_name . '</a>
                                <button class="user-brief__open-dialog dialog-btn btn messaging js-open-messaging"
                                        type="button"
                                        data-user-id="' . $teacher->user_id . '"
                                        data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $teacher->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                                    <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                        <use xlink:href="#dialog"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="user-brief__time-ago">' .($teacher->_user_online ? 'онлайн' : $CurrentUser->getUserOnlineStatus($teacher->_user_last_visit)) . '</div>
                        </div>
                    </div>
                ';
                $i++;
            }
        } else {
            $teacher_list .= 'Нет учителей, закрепленных за этим методистом';
        }
        $teacher_list .= '
            </div>
        ';
        if (sizeof($Teachers) > 0) {
            $teacher_list .= '
            <div class="shaded-list__right">
                <a class="shaded-list__more-link js-open-modal js-show-all-data void-0"
                   href="#"
                   data-modal-id="show-all-users-modal"
                   data-get-content-from-div-id="' . $teacher_container_id . '">Смотреть все</a>
            </div>
        ';
        }



        /* lessons for this methodist */
        $lessons_container_id = "lessons-list-container-for-methodist-{$model->user_id}";
        $lessons_list = '
            <div class="dynamic-data__data">
                <div class="events-brief-list events-brief-list-methodist-list"
                     id="' . $lessons_container_id . '"
                     data-title-for-modal="Расписание методиста ' . $model->user_full_name . '">
        ';
        $Lessons = $model->getLessonsForThisMethodist();
        if (sizeof($Lessons)) {
            $i = 1;
            foreach ($Lessons as $lesson) {
                /** @var $lesson array */
                if ($i > 4) {
                    $class = 'events-brief-list__item-more-4';
                } else {
                    $class = '';
                }

                $lessons_list .= '
                    <div class="event-brief events-brief-list__item ' . $class . '">
                        <div class="event-brief__student">
                            <div class="event-brief__title">'
                                . ($lesson['timeline_timestamp'] < time() ? '<b>Идет урок</b> с ' : '<b>Урок</b> с ') . '
                            </div>
                            <div class="event-brief__user">
                                <img class="event-brief__ava-img"
                                     src="' . Users::staticGetProfilePhotoForWeb($lesson['user_photo'], '/assets/smartsing-min/images/no_photo.png') . '"
                                     alt=""
                                     role="presentation" />
                                <a class="event-brief__name-link js-open-modal-user-info void-0"
                                   href="#"
                                   data-user-id="' . $lesson['user_id'] . '"
                                   data-modal-id="student-info-modal">' . $lesson['user_first_name'] . '</a>
                            </div>
                        </div>
                        <a class="event-brief__open-link"
                           href="#">
                            <svg class="svg-icon--eye-2 svg-icon" width="18" height="11">
                                <use xlink:href="#eye-2"></use>
                            </svg>
                        </a>
                        <div class="event-brief__date">' . $CurrentUser->getDateInUserTimezoneByTimestamp($lesson['timeline_timestamp']) . '</div>
                    </div>
                ';
                $i++;
            }
        } else {
            $lessons_list .= 'Еще не назначено занятий с учениками';
        }
        $lessons_list .= '
                </div>
            </div>
        ';
        if (sizeof($Lessons) > 0) {
            $lessons_list .= '
                <a class="dynamic-data__more-link js-open-modal js-show-all-data void-0"
                   href="#"
                   data-modal-id="show-all-schedule-modal"
                   data-get-content-from-div-id="' . $lessons_container_id . '">Смотреть все</a>
            ';
        }

        /* comments */
        $comments_short =
            $model->user_additional_info . ' ' .
            $model->admin_notice . ' ' .
            $model->operator_notice . ' ' .
            $model->additional_service_notice . ' ' .
            $model->additional_service_info;
        $comments =
            "<hr />" .
            "<b>Пользователь о себе:</b><br />{$model->user_additional_info}<hr />" .
            "<b>Админ:</b><br />{$model->admin_notice}<hr />" .
            "<b>Опертор:</b><br />{$model->operator_notice}<hr />" .
            "<b>Общая информация:</b><br />{$model->additional_service_notice}<hr />" .
            "<b>Сервисная информация:</b><br />{$model->additional_service_info}<hr />";

        /* return html */
        return '
            <!--.coach-details-->
            <div class="coach-details coaches-details-list__item">
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
                               data-modal-id="methodist-info-modal"><span>' . $model->user_full_name . '</span></a>
                            <div class="user-details__data">
                                <div class="user-details__data-item">
                                    <div class="user-details__data-item-label">ID: ' . $model->user_id . '</div>
                                </div>
                                <div class="user-details__data-item">
                                    ' . $online_status . '
                                </div>
                            </div>
                            <br />
                            <a class="color-red font-bold link js-open-modal js-btn-add-new-methodist void-0"
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
                               data-modal-id="new-methodist-modal">Изменить</a>
                            <br />
                            <a class="color-green font-bold link"
                               target="_blank"
                               href="' . Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token], false) . '">Войти в акк</a>
                        </div>
                    </div>
                </div>
                <div class="coach-details__main">
                    <!-- ученики методиста -->
                    <div class="coach-details__top">
                        <div class="coach-details__top-title"><span>Ученики, прикрепленные к методисту</span><span><!--15 учеников за последнюю неделю--></span></div>
                        <div class="shaded-list">

                            ' . $students_list . '

                        </div>
                    </div>
                    <!-- учителя методиста -->
                    <div class="coach-details__top">
                        <div class="coach-details__top-title"><span>Учителя, прикрепленные к методисту</span><span><!--2 учителя за последнюю неделю--></span></div>
                        <div class="shaded-list">

                            ' . $teacher_list . '

                        </div>
                    </div>
                    <div class="coach-details__bottom">
                        <!-- расписание методиста -->
                        <div class="coach-details__section">
                            <div class="dynamic-data _loaded">
                                <div class="dynamic-data__title">Расписание</div>

                                ' . $lessons_list . '

                            </div>
                        </div>

                        <!-- события методиста -->
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
                                <div class="dynamic-data _loaded" style="display: none;">
                                    <div class="dynamic-data__title">События</div>
                                    <div class="dynamic-data__data">
                                        <div class="events-brief-list">
                                            <div class="event-brief event-brief--rating events-brief-list__item">
                                                <div class="event-brief__student">
                                                    <div class="event-brief__title">
                                                        Получил <b>отзыв</b>
                                                        <span class="event-brief__rating rating rating rating--xs js-rating" title="4" data-rating="4"></span><span>от</span>
                                                    </div>
                                                    <div class="event-brief__user">
                                                        <img class="event-brief__ava-img" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                                        <a class="event-brief__name-link js-open-modal" href="#" data-modal-id="customer-full-modal">Роман</a>
                                                    </div>
                                                </div>
                                                <div class="event-brief__date">21/05/2020, 15:30</div>
                                            </div>
                                            <div class="event-brief event-brief--achieve events-brief-list__item">
                                                <div class="event-brief__student">
                                                    <div class="event-brief__title">
                                                        Выдал <b>достижение</b>
                                                        <span class="event-brief__achieve achieve-sign"></span><span>для</span>
                                                    </div>
                                                    <div class="event-brief__user">
                                                        <img class="event-brief__ava-img" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                                        <a class="event-brief__name-link js-open-modal" href="#" data-modal-id="customer-full-modal">Данила</a>
                                                    </div>
                                                </div>
                                                <div class="event-brief__date">21/05/2020, 15:30</div>
                                            </div>
                                            <div class="event-brief event-brief--rating events-brief-list__item">
                                                <div class="event-brief__student">
                                                    <div class="event-brief__title">
                                                        Получил <b>отзыв</b>
                                                        <span class="event-brief__rating rating rating rating--xs js-rating" title="4" data-rating="4"></span><span>от</span>
                                                    </div>
                                                    <div class="event-brief__user">
                                                        <img class="event-brief__ava-img" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                                        <a class="event-brief__name-link js-open-modal" href="#" data-modal-id="customer-full-modal">Елена</a>
                                                    </div>
                                                </div>
                                                <div class="event-brief__date">21/05/2020, 15:30</div>
                                            </div>
                                            <div class="event-brief event-brief--rating events-brief-list__item">
                                                <div class="event-brief__student">
                                                    <div class="event-brief__title">
                                                        Получил <b>отзыв</b>
                                                        <span class="event-brief__rating rating rating rating--xs js-rating" title="4" data-rating="4"></span><span>от</span>
                                                    </div>
                                                    <div class="event-brief__user">
                                                        <img class="event-brief__ava-img" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                                        <a class="event-brief__name-link js-open-modal" href="#" data-modal-id="customer-full-modal">Елена</a>
                                                    </div>
                                                </div>
                                                <div class="event-brief__date">21/05/2020, 15:30</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="dynamic-data__more-link js-open-modal" href="#" data-modal-id="coach-events-modal">Смотреть все</a>
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



<?= $this->render("../modals/admin-modals/new-methodist-modal", ['newMethodistForm' => $newMethodistForm]) ?>
<?= $this->render("../modals/admin-modals/methodist-info-modal") ?>
<?= $this->render("../modals/admin-modals/student-info-modal") ?>
<?= $this->render("../modals/admin-modals/teacher-info-modal") ?>
<?= $this->render("../modals/admin-modals/show-all-users-modal") ?>
<?= $this->render("../modals/admin-modals/show-all-schedule-modal") ?>
