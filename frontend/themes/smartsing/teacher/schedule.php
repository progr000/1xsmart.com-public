<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $NextLesson array */
/** @var $DashboardSchedule array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\helpers\Functions;
use common\models\Users;

$this->title = Html::encode('Расписание занятий | Teacher area');

$next_lesson_text = "";
if (isset($NextLesson['timeline_timestamp'])) {
    $next_lesson_text = Functions::getTextWhenNextLesson(
        $NextLesson['timeline_timestamp'],
        $CurrentUser->user_timezone
    );
}

$left_minutes = intval(Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER/60);
if ($NextLesson && $NextLesson['timeline_timestamp'] < (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER)) {
    $btn_class = 'primary-btn--c6';
    $href = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']]);
    $title = '';
    $allow_button_timeout = 0;
    $href_timeout = $href;
} else {
    $btn_class = 'primary-btn--c6 locked void-0 masterTooltip';
    $href = '#';
    $title = "Войти в клас мжно будет<br />не раньше чем за " . $left_minutes . " " . Functions::left_minutes_ru_text($left_minutes)[0] . " до занятия.";
    $allow_button_timeout = $NextLesson['timeline_timestamp'] - (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER);
    $href_timeout = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']]);
}
?>

<div class="dashboard">
    <h1 class="page-title">Расписание занятий</h1>
    <div class="schedule-info schedule-info--lg dashboard__window dashboard__window dashboard__window--block win win win--grey">
        <div class="win__top"></div>
        <div class="schedule-info__inner dashboard__window-inner">
            <picture class="schedule-info__media">
                <source srcset="/assets/smartsing-min/images/clock-3-w240.png" media="(max-width: 1400px)">
                <source srcset="/assets/smartsing-min/images/clock-3.png">
                <img srcset="/assets/smartsing-min/images/clock-3.png" alt="">
            </picture>
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
    <div class="notice-grid">
        <div class="notice-grid__item">
            <div class="notice-card">
                <div class="notice-card__icon-wrap">
                    <svg class="svg-icon--refresh-color svg-icon" width="40" height="46">
                        <use xlink:href="#refresh-color"></use>
                    </svg>
                </div>
                <div class="notice-card__text">
                    <div class="notice-card__title">Вы можете перенести занятие(я) <br>разово или регулярно!</div>
                    <div class="notice-card__desc">В случае если у вас изменились обстоятельства не теряйте уроки и переносите их на удобное для Вас время</div>
                </div>
            </div>
        </div>
        <div class="notice-grid__item">
            <div class="notice-card">
                <div class="notice-card__icon-wrap">
                    <svg class="svg-icon--calendar-9 svg-icon" width="40" height="40">
                        <use xlink:href="#calendar-9"></use>
                    </svg>
                </div>
                <div class="notice-card__text">
                    <div class="notice-card__title">Сообщите нам о своих планах <br>не менее чем за 10 часов</div>
                    <div class="notice-card__desc">Таким образом мы сможем скорректировать Ваш график наиболее эффективно, для проведения результативных регулярных уроков.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render("../modals/teacher-modals/change-schedule-modal", [
    'CurrentUser' => $CurrentUser,
    'DASHBOARD_SCHEDULE_DATA' => $DashboardSchedule,
]) ?>
