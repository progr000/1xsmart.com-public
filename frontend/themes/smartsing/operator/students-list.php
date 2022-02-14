<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $modelSearch \frontend\models\search\StudentsListSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $newStudentForm \common\models\Users */
/** @var $listOperators \yii\db\ActiveRecord[] */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\Users;
use frontend\assets\smartsing\admin\UsersListAsset;

UsersListAsset::register($this);

$this->title = Html::encode('Список учеников | Admin area');

?>

<?php Pjax::begin([
    'id' => 'students-list-content',
    'timeout' => PJAX_TIMEOUT,
    'options'=> ['class' => 'dashboard']
]); ?>

    <a class="new-object-link js-open-modal btn-add-new-student js-btn-add-new-student void-0"
       href="#"
       data-is-new="1"
       data-user_id="0"
       data-user_first_name=""
       data-user_phone=""
       data-user_email=""
       data-_user_skype=""
       data-_user_telegram=""
       data-operator_user_id="0"
       data-methodist_user_id="0"
       data-teacher_user_id="0"
       data-introduce_lesson_time="0"
       data-additional_service_notice=""
       data-admin_notice=""
       data-modal-id="new-student-modal">
            <span class="new-object-link__icon-wrap">
                <svg class="svg-icon--plus svg-icon" width="13" height="13">
                    <use xlink:href="#plus"></use>
                </svg>
            </span>
        <span class="new-object-link__text">Добавить ученика</span>
    </a>

    <div class="flex-header h1">
        <h1>Список учеников</h1>


        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['operator/students-list'],
            'id' => 'filter-search-students-list',
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
                            'data-name' => "дате регистрации &darr;",
                        ],
                        'sort-desc-created' => [
                            'data-sort-val' => "-created",
                            'data-name' => "дате регистрации &uarr;",
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
                            'data-name' => "Имени &darr;",
                        ],
                        'sort-desc-name' => [
                            'data-sort-val' => "-name",
                            'data-name' => "Имени &uarr;",
                        ],
                        /*
                        'sort-asc-tmt' => [
                            'data-sort-val' => "tmt",
                            'data-name' => "Ближайшему уроку &darr;",
                        ],
                        'sort-desc-tmt' => [
                            'data-sort-val' => "-tmt",
                            'data-name' => "Ближайшему уроку &uarr;",
                        ],
                        */
                    ];
                    $items = [];
                    foreach ($options as $k=>$v) {
                        $items[$k] = $v['data-name'];
                    }
                    ?>
                    <?= $form->field($modelSearch, 'sort', [
                        'inputOptions' => [
                            'class' => 'list-search-sort',
                            'id' => 'studentslistsearch-sort',
                        ],
                    ])
                        ->dropDownList($items, [
                            'encode' => false,
                            'options' => $options,
                        ])
                        ->label(false)
                    ?>
                </div>
                <input type="hidden" name="sort" id="student-sort-data" class="field-sort-data">
                <input type="submit" id="submit-filter-students-list" style="display: none;">

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

            <div class="lessons-list students-list">

                {items}

            </div>

            <div class="pages">

                {pager}

            </div>

        </section>
    ',
    'emptyText' => '<div class="presets-empty">' . ($modelSearch->filter ? 'По заданному поиску нет результатов' : 'Еще нет учеников' ) . '</div>', //$this->render('presets-list-nodata'),
    'emptyTextOptions' => ['tag' => false],
    'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
        /** @var $model \frontend\models\search\StudentsListSearch */


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

        /* last_pay */
        $last_pay_status = '<span class="highlight-c1">Нет</span>';
        if ($model->user_last_pay) {
            $last_pay_status =
                '<span class="highlight-c2">Да</span>: ' . $CurrentUser->getDateInUserTimezoneByDateString($model->user_last_pay);
        }

        /* lessons for this student */
//        $lessons_container_id = "lessons-list-container-for-student-{$model->user_id}";
//        $lessons_list = '
//            <div class="dynamic-data__data">
//                <div class="events-brief-list events-brief-list-student-list"
//                     id="' . $lessons_container_id . '"
//                     data-title-for-modal="Расписание ученика ' . $model->user_full_name . '">
//        ';
//        $Lessons = $model->getLessonsForThisStudent();
//        if (sizeof($Lessons)) {
//            $i = 1;
//            foreach ($Lessons as $lesson) {
//                /** @var $lesson array */
//                if ($i > 4) {
//                    $class = 'events-brief-list__item-more-4';
//                } else {
//                    $class = '';
//                }
//
//                $lessons_list .= '
//                    <div class="event-brief events-brief-list__item ' . $class . '">
//                        <div class="event-brief__student">
//                            <div class="event-brief__title">'
//                    . ($lesson['timeline_timestamp'] < time() ? '<b>Идет урок</b> с ' : '<b>Урок</b> с ') . '
//                            </div>
//                            <div class="event-brief__user">
//                                <img class="event-brief__ava-img"
//                                     src="' . Users::staticGetProfilePhotoForWeb($lesson['user_photo'], '/assets/smartsing-min/images/no_photo.png') . '"
//                                     alt=""
//                                     role="presentation" />
//                                <a class="event-brief__name-link js-open-modal-user-info void-0"
//                                   href="#"
//                                   data-user-id="' . $lesson['user_id'] . '"
//                                   data-modal-id="student-info-modal">' . $lesson['user_first_name'] . '</a>
//                            </div>
//                        </div>
//                        <a class="event-brief__open-link"
//                           href="#">
//                            <svg class="svg-icon--eye-2 svg-icon" width="18" height="11">
//                                <use xlink:href="#eye-2"></use>
//                            </svg>
//                        </a>
//                        <div class="event-brief__date">' . $CurrentUser->getDateInUserTimezoneByTimestamp($lesson['timeline_timestamp']) . '</div>
//                    </div>
//                ';
//                $i++;
//            }
//        } else {
//            $lessons_list .= 'Еще не назначено занятий с учениками';
//        }
//        $lessons_list .= '
//                </div>
//            </div>
//        ';
//        if (sizeof($Lessons) > 0) {
//            $lessons_list .= '
//                <a class="dynamic-data__more-link js-open-modal js-show-all-data void-0"
//                   href="#"
//                   data-modal-id="show-all-schedule-modal"
//                   data-get-content-from-div-id="' . $lessons_container_id . '">Смотреть все</a>
//            ';
//        }

        /* методист ученика */
        $operator = $model->getOperatorForThisUser();
        if ($operator) {
            $operatorHtml = '
            <div class="user-brief users-brief-list__item  ">
                <img class="user-brief__ava-img"
                     src="' . $operator->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                     alt=""
                     role="presentation">
                <div class="user-brief__data">
                    <div class="user-brief__name">
                        <a class="user-brief__name-link js-open-modal-user-info void-0"
                           href="#"
                           data-user-id="' . $operator->user_id . '"
                           data-modal-id="methodist-info-modal">' . $operator->user_full_name. '</a>
                        <button class="user-brief__open-dialog dialog-btn btn messaging js-open-messaging"
                                type="button"
                                data-user-id="' . $operator->user_id . '"
                                data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $operator->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                            <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                <use xlink:href="#dialog"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="user-brief__time-ago">' . ($operator->_user_online ? 'онлайн' : $CurrentUser->getUserOnlineStatus($operator->_user_last_visit)) . '</div>
                </div>
            </div>
            ';
        } else {
            $operatorHtml = 'Не задан';
        }

        /* методист ученика */
        $methodist = $model->getMethodistForThisUser();
        if ($methodist) {
            $methodistHtml = '
            <div class="user-brief users-brief-list__item  ">
                <img class="user-brief__ava-img"
                     src="' . $methodist->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                     alt=""
                     role="presentation">
                <div class="user-brief__data">
                    <div class="user-brief__name">
                        <a class="user-brief__name-link js-open-modal-user-info void-0"
                           href="#"
                           data-user-id="' . $methodist->user_id . '"
                           data-modal-id="methodist-info-modal">' . $methodist->user_full_name. '</a>
                        <button class="user-brief__open-dialog dialog-btn btn messaging js-open-messaging"
                                type="button"
                                data-user-id="' . $methodist->user_id . '"
                                data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $methodist->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                            <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                <use xlink:href="#dialog"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="user-brief__time-ago">' . ($methodist->_user_online ? 'онлайн' : $CurrentUser->getUserOnlineStatus($methodist->_user_last_visit)) . '</div>
                </div>
            </div>
            ';
        } else {
            $methodistHtml = 'Не задан';
        }

        /* учитель ученика */
        $teacher = $model->getTeacherForThisUser();
        if ($teacher) {
            $teacherHtml = '
            <div class="user-brief users-brief-list__item  ">
                <img class="user-brief__ava-img"
                     src="' . $teacher->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png') . '"
                     alt=""
                     role="presentation">
                <div class="user-brief__data">
                    <div class="user-brief__name">
                        <a class="user-brief__name-link js-open-modal-user-info void-0"
                           href="#"
                           data-user-id="' . $teacher->user_id . '"
                           data-modal-id="teacher-info-modal">' . $teacher->user_full_name. '</a>
                        <button class="user-brief__open-dialog dialog-btn btn messaging js-open-messaging"
                                type="button"
                                data-user-id="' . $teacher->user_id . '"
                                data-href-video-chat="' . Url::to(['user/conference-room', 'room' => $teacher->_user_conference_room_hash], CREATE_ABSOLUTE_URL) . '">
                            <svg class="svg-icon--dialog svg-icon" width="15" height="18">
                                <use xlink:href="#dialog"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="user-brief__time-ago">' . ($teacher->_user_online ? 'онлайн' : $CurrentUser->getUserOnlineStatus($teacher->_user_last_visit)) . '</div>
                </div>
            </div>
            ';
        } else {
            $teacherHtml = 'Не задан';
        }

        /* comments */
        $comments_short =
            $model->user_additional_info . ' ' .
            $model->admin_notice . ' ' .
            $model->operator_notice . ' ' .
            $model->methodist_notice . ' ' .
            $model->teacher_notice . ' ' .
            $model->additional_service_notice . ' ' .
            $model->additional_service_info;
        $comments =
            "<hr />" .
            "<b>Пользователь о себе:</b><br />{$model->user_additional_info}<hr />" .
            "<b>Админ:</b><br />{$model->admin_notice}<hr />" .
            "<b>Опертор:</b><br />{$model->operator_notice}<hr />" .
            "<b>Методист:</b><br />{$model->methodist_notice}<hr />" .
            "<b>Учитель:</b><br />{$model->teacher_notice}<hr />" .
            "<b>Общая информация:</b><br />{$model->additional_service_notice}<hr />" .
            "<b>Сервисная информация:</b><br />{$model->additional_service_info}<hr />";

        /* return html */
        return '
            <!--.student-details-->
            <div class="lesson lesson--operator-view">
                <div class="lesson__cell">
                    <div class="user-details coach-details__user">
                        <!-- фото ученика -->
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
                        <!-- данные об ученике -->
                        <div class="user-details__info">
                            <a class="user-details__name link js-open-modal-user-info void-0"
                               href="#"
                               data-user-id="' . $model->user_id . '"
                               data-modal-id="student-info-modal"><span>' . $model->user_first_name . '</span></a>
                            <div class="user-details__data">
                                <div class="user-details__data-item">
                                    <div class="user-details__data-item-label">ID: ' . $model->user_id . '</div>
                                </div>
                                <div class="user-details__data-item">
                                    ' . $online_status . '
                                </div>
                            </div>
                            <br />
                            <a class="color-red font-bold link js-open-modal js-btn-add-new-student void-0"
                               href="#"
                               data-is-new="0"
                               data-user_id="' . $model->user_id . '"
                               data-user_first_name="' . $model->user_first_name . '"
                               data-user_phone="' . $model->user_phone . '"
                               data-user_email="' . $model->user_email . '"
                               data-_user_skype="' . $model->_user_skype . '"
                               data-_user_telegram="' . $model->_user_telegram . '"
                               data-operator_user_id="' . intval($model->operator_user_id) . '"
                               data-methodist_user_id="' . intval($model->methodist_user_id) . '"
                               data-teacher_user_id="' . intval($model->teacher_user_id) . '"
                               data-introduce_lesson_time="0"
                               data-additional_service_notice="' . $model->additional_service_notice . '"
                               data-operator_notice="' . $model->operator_notice . '"
                               data-modal-id="new-student-modal">Изменить</a>
                        </div>
                    </div>
                </div>

                <!-- row -->
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Телефон</div>
                        <div class="item__value">' . $model->user_phone . '</div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Оператор</div>
                        <div class="item__value">

                            ' . $operatorHtml . '

                        </div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Дата регистрации</div>
                        <div class="item__value">
                            ' . $CurrentUser->getDateInUserTimezoneByDateString($model->user_created) . '
                        </div>
                    </div>
                </div>

                <!-- row -->
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Электронная почта</div>
                        <div class="item__value">' . $model->user_email . '</div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Методист</div>
                        <div class="item__value">

                            ' . $methodistHtml . '

                        </div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Статус оплаты</div>
                        <div class="item__value">' . $last_pay_status . '</div>
                    </div>
                </div>

                <!-- row -->
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Статус ученика</div>
                        <div class="item__value">' . Users::getStatus($model->user_status) . '</div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Учитель</div>
                        <div class="item__value">

                            ' . $teacherHtml . '

                        </div>
                    </div>
                </div>
                <div class="lesson__cell">
                    <div class="item">
                        <div class="item__label">Комментарии и информация</div>
                        <div class="item__value">
                        <span>
                            ' .
                            mb_substr(
                                $comments_short,
                                0,
                                50
                            ) .
                            '
                        </span></div>
                    </div>
                    <a class="full-comment-link light-link js-open-modal js-full-comment-link void-0"
                       href="#"
                       data-comment-title="Комментарии и информация"
                       data-comment-full-text="' . $comments . '"
                       data-modal-id="comment-text-modal">Читать полностью</a>
                </div>

            </div>
            <!--.student-details-->
        ';
    },
]);
?>
<?php Pjax::end(); ?>


<?= $this->render("../modals/admin-modals/new-student-modal", [
    'newStudentForm' => $newStudentForm,
    'CurrentUser'    => $CurrentUser,
]) ?>
<?= $this->render("../modals/admin-modals/student-info-modal") ?>
<?= $this->render("../modals/admin-modals/operator-info-modal") ?>
<?= $this->render("../modals/admin-modals/methodist-info-modal") ?>
<?= $this->render("../modals/admin-modals/teacher-info-modal") ?>
