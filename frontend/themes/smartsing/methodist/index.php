<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $NextLesson array */
/** @var $DashboardSchedule array */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\Functions;
use common\models\Users;
use frontend\models\search\NextLessons;

$this->title = Html::encode('Dashboard | Methodist area');

$next_lesson_text = "";
if (isset($NextLesson['timeline_timestamp'])) {
    $next_lesson_text = Functions::getTextWhenNextLesson(
        $NextLesson['timeline_timestamp'],
        $CurrentUser->user_timezone
    );
}

$left_minutes = intval(Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST/60);
if ($NextLesson && $NextLesson['timeline_timestamp'] < (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST)) {
    $btn_class = 'primary-btn--c6';
    $href = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']]);
    $title = '';
    $allow_button_timeout = 0;
    $href_timeout = $href;
} else {
    $btn_class = 'primary-btn--c6 locked void-0 masterTooltip';
    $href = '#';
    $title = "Войти в клас мжно будет<br />не раньше чем за " . $left_minutes . " " . Functions::left_minutes_ru_text($left_minutes)[0] . " до занятия.";
    $allow_button_timeout = $NextLesson['timeline_timestamp'] - (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST);
    $href_timeout = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']]);
}
?>
<style>
    span.in-for-week-day {
        *color: #000;
    }
</style>
<div class="dashboard dashboard--grid">

    <!-- -->
    <div class="dashboard__section">
        <div id="schedule-info-block"
             class="schedule-info dashboard__window win win win--grey"
             data-lesson-timeout-skipped="<?= NextLessons::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED ?>">
            <div class="win__top"></div>
            <div class="schedule-info__inner dashboard__window-inner">
                <div class="schedule-info__icon-wrap"><svg class="svg-icon--calendar-8 svg-icon" width="69" height="60">
                        <use xlink:href="#calendar-8"></use>
                    </svg></div>
                <div class="schedule-info__body">
                    <div class="schedule-info__current-info">
                        <?php Pjax::begin([
                            'id' => 'dashboard-next-lesson-time',
                            'timeout' => PJAX_TIMEOUT,
                            'options'=> ['class' => '']
                        ]); ?>
                        <?php if ($NextLesson) { ?>
                            <span id="lesson-in-progress" style="<?= $NextLesson['timeline_timestamp'] <= time() ? '' : 'display: none;' ?>">
                                В данный момент у вас проходит занятие.
                                Пожалуйста вернитесь в <a href="<?= $href_timeout ?>" target="_blank"><span class="highlight-c1">класс</span></a>, если вы из него вышли.
                            </span>
                            <span id="lesson-left-time" style="<?= $NextLesson['timeline_timestamp'] <= time() ? 'display: none;' : '' ?>">
                                Ваше следующее занятие<br />
                                <?= $next_lesson_text ?>
                            </span>
                        <?php } else { ?>
                            <span style="<?= $NextLesson['timeline_timestamp'] <= time() ? '' : 'display: none;' ?>">
                                На ближайшее время у вас не назначено никаких занятий.
                            </span>
                        <?php } ?>
                        <?php Pjax::end(); ?>
                    </div>
                    <div class="schedule-info__total-info">
                        <div class="schedule-info__total-info-title">Ваше расписание:</div>
                        <!--<div class="schedule-info__total-info-value">-->
                            <?php Pjax::begin([
                                'id' => 'dashboard-schedule-list',
                                'timeout' => PJAX_TIMEOUT,
                                'options'=> ['class' => 'schedule-info__total-info-value']
                            ]); ?>
                            <?php
                            if (!isset($DashboardSchedule) || !sizeof($DashboardSchedule)) {
                                echo 'на данный момент не задано.';
                            } else {
                                foreach ($DashboardSchedule as $key => $schedule) {
                                    ?>
                                    <a href="#"
                                       class="void-0 js-open-popup-schedule js-open-modal"
                                       data-modal-id="change-schedule-modal"
                                       data-week-day="<?= $key ?>"><?= Functions::getTextWeekDay($key, 'Up_') ?></a>:
                                    <?php
                                    foreach ($schedule as $time) {
                                        echo $time . "; ";
                                    }
                                    ?>
                                    <br/>
                                    <?php
                                }
                            }
                            ?>
                            <?php Pjax::end(); ?>
                        <!--</div>-->
                    </div>
                </div>
            </div>
            <div class="dashboard__footer text-right">
                <a class="text-light void-0 js-open-popup-schedule js-open-modal"
                   href="#"
                   data-week-day="<?= intval(date('N', $CurrentUser->_user_local_time)) ?>"
                   data-modal-id="change-schedule-modal">Поменять расписание</a>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="dashboard__section">
        <div class="class-entrance dashboard__window win win win--grey">
            <div class="win__top"></div>
            <div class="class-entrance__inner dashboard__window-inner">
                <div class="class-entrance__title">
                    Вводное занятие с учеником <?= isset($NextLesson['student_full_name']) ? $NextLesson['student_full_name'] : '' ?>
                </div>
                <div class="class-entrance__desc">
                    Методист должен присутсвовать на уроке за <?= $left_minutes ?> <?= Functions::left_minutes_ru_text($left_minutes)[0] ?> до начала урока,
                    а также все время на протяжении урока (30 минут), даже если ученик отсутствует.
                </div>
                <a id="entrance-class-button"
                   data-href-timeout="<?= $href_timeout ?>"
                   data-allow-button-timeout="<?= $allow_button_timeout ?>"
                   class="class-entrance__btn btn primary-btn primary-btn <?= $btn_class ?>"
                   target="_blank"
                   title="<?= $title ?>"
                   href="<?= $href ?>">Войти в класс</a>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="dashboard__section">
        <div class="dashboard__section-title title">Портрет ученика</div>
        <div class="dashboard__window win win--grey">
            <div class="win__top"></div>
            <div class="dashboard__window-inner">
                <?php if ($NextLesson) { ?>
                <div class="user-info user-info--portrait">
                    <div class="user-info__user">
                        <img class="user-info__ava"
                             src="<?= Users::staticGetProfilePhotoForWeb($NextLesson['student_photo'], '/assets/smartsing-min/images/no_photo.png') ?>"
                             alt=""
                             role="presentation" />
                        <div class="user-info__main">
                            <div class="user-info__name"><?= $NextLesson['student_full_name'] ?> (ID: <?= $NextLesson['student_user_id'] ?>)</div>
                            <div class="user-info__sex"><?= $NextLesson['user_gender'] === null ? 'Пол не выбран' : Users::getGender($NextLesson['user_gender'])  ?></div>
                        </div>
                    </div>
                    <div class="user-info__data user-info__data--2col">
                        <div class="user-info__data-item">
                            <div class="user-info__data-item-label">Возраст</div>
                            <div class="user-info__data-item-value"><?= Functions::ru_string_age(Users::staticGetAge($NextLesson['user_birthday'])) ?></div>
                        </div>
                        <div class="user-info__data-item">
                            <div class="user-info__data-item-label">Любимые жанры музыки:</div>
                            <div class="user-info__data-item-value">
                                <?php
                                $_music_genres = unserialize($NextLesson['user_music_genres']);
                                $tmp = [];
                                if (is_array($_music_genres)) {
                                    foreach ($_music_genres as $key => $item) {
                                        $tmp[] = Users::$_music_genres[$key];
                                    }
                                    echo implode(', ', $tmp);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="user-info__data-item user-info__data-item-full">
                            <div class="user-info__data-item-label">Разное</div>
                            <div class="user-info__data-item-value"><?= $NextLesson['user_additional_info'] ?></div>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="dashboard__window-inner">
                    <div class="class-entrance__desc">
                        Тут будет отображена инфрмация об ученике с которым предстоит провести занятие.
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="dashboard__section">
        <div class="dashboard__section-title title">План урока</div>
        <div class="dashboard__window win win--grey">
            <div class="win__top"></div>
            <div class="dashboard__window-inner dashboard__window-inner--no-pad">
                <div class="text-fade md-text-size">
                    <p>
                        5% времени - приветствие, настройка на урок.<br />
                        50-70% времени - распевки.<br />
                        Оставшееся время - разбор песен ученика. Внимание: Ученик включает минус или плюс песни на своей стороне!<br />
                    </p>
                </div>
            </div>
            <div class="dashboard__footer text-right">
                <a class="text-light js-open-modal void-0"
                   href="#"
                   data-modal-id="lesson-plan-modal">Показать весь план</a>
            </div>
        </div>
    </div>

</div>

<?= $this->render("../modals/methodist-modals/change-schedule-modal", [
    'CurrentUser' => $CurrentUser,
    'DASHBOARD_SCHEDULE_DATA' => $DashboardSchedule,
]) ?>

<?= $this->render("../modals/methodist-modals/lesson-plan-modal") ?>

