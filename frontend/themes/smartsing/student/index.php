<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $NextLesson array */
/** @var $DashboardSchedule array */
/** @var $StudentsTimeline \yii\db\ActiveRecord[] */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\Functions;
use common\models\Users;
use frontend\models\search\NextLessons;

$this->title = Html::encode('Панель ученика');

/**/
$next_lesson_text = "";
if (isset($NextLesson['timeline_timestamp'])) {
    $next_lesson_text = Functions::getTextWhenNextLesson(
        $NextLesson['timeline_timestamp'],
        $CurrentUser->user_timezone
    );
}

/**/
$left_minutes = intval(Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT/60);
if ($NextLesson && $NextLesson['timeline_timestamp'] < (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT)) {
    $btn_class = 'primary-btn--c6';
    if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
        $href = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
    } else {
        $href = Url::to(['user/educational-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
    }
    $title = '';
    $allow_button_timeout = 0;
    $href_timeout = $href;
} else {
    $btn_class = 'primary-btn--c6 locked void-0 masterTooltip';
    $href = '#';
    $title = "Вы сможете войти в класс за {$left_minutes} " . Functions::left_minutes_ru_text($left_minutes)[0] . " до начала занятия.";
    $allow_button_timeout = $NextLesson['timeline_timestamp'] - (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT);

    if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
        $href_timeout = Url::to(['user/introductory-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
    } else {
        $href_timeout = Url::to(['user/educational-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
    }
}

/**/
$schedule_link['href']  = '#';
$schedule_link['class'] = 'void-0';
$schedule_link['alert_text'] = '';
if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
    $schedule_link['href'] = '#';
    $schedule_link['class'] = 'void-0 js-alert';
    $schedule_link['alert_text'] = 'Расписание можно будет установить после вводного занятия с методистом.';
} elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) {
    $schedule_link['href'] = Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL);
    $schedule_link['class'] = '';
    $schedule_link['alert_text'] = '';
} elseif ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
    $schedule_link['href'] = Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL);
    $schedule_link['class'] = '';
    $schedule_link['alert_text'] = '';
} elseif ($CurrentUser->user_status == Users::STATUS_ACTIVE && $StudentsTimeline && $CurrentUser->teacher_user_id) {
    $schedule_link['href'] = '#';
    $schedule_link['class'] = 'void-0 js-open-student-popup-schedule js-open-modal';
    $schedule_link['alert_text'] = '';
} else {
    $schedule_link['href'] = Url::to(['student/schedule'], CREATE_ABSOLUTE_URL);
    $schedule_link['class'] = '';
    $schedule_link['alert_text'] = '';
}
?>

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
                                <a href="<?= $schedule_link['href'] ?>"
                                   class="<?= $schedule_link['class'] ?>"
                                   data-alert-text="<?= $schedule_link['alert_text'] ?>"
                                   data-modal-id="change-schedule-modal"
                                   data-week-day="<?= $key ?>"
                                   data-work-hour="null"><?= Functions::getTextWeekDay($key, 'Up_') ?></a>:
                                <?php
                                foreach ($schedule as $tk => $time) {
                                    ?>
                                    <a href="<?= $schedule_link['href'] ?>"
                                       class="<?= $schedule_link['class'] ?>"
                                       data-alert-text="<?= $schedule_link['alert_text'] ?>"
                                       data-modal-id="change-schedule-modal"
                                       data-week-day="<?= $key ?>"
                                       data-work-hour="<?= $tk ?>"><?= $time ?></a>;
                                    <?php
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
                <a class="text-light <?= $schedule_link['class'] ?>"
                   href="<?= $schedule_link['href'] ?>"
                   data-alert-text="<?= $schedule_link['alert_text'] ?>"
                   data-week-day="<?= intval(date('N', $CurrentUser->_user_local_time)) ?>"
                   data-work-hour="null"
                   data-modal-id="change-schedule-modal">Поменять расписание</a>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="dashboard__section">
        <div class="class-entrance dashboard__window win win win--grey">
            <div class="win__top"></div>
            <div class="class-entrance__inner dashboard__window-inner">

                <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                    <div class="class-entrance__title">
                        Вводное занятие с методистом <?= isset($NextLesson['teacher_full_name']) ? $NextLesson['teacher_full_name'] : '' ?>
                    </div>
                    <div class="class-entrance__desc">
                        Вы сможете войти в класс за <?= $left_minutes ?> <?= Functions::left_minutes_ru_text($left_minutes)[0] ?> до начала занятия.
                    </div>
                <?php } else { ?>
                    <div class="class-entrance__title">
                        Регулярное занятие с преподавателем <?= isset($NextLesson['teacher_full_name']) ? $NextLesson['teacher_full_name'] : '' ?>
                    </div>
                    <div class="class-entrance__desc">
                        Вы сможете войти в класс за <?= $left_minutes ?> <?= Functions::left_minutes_ru_text($left_minutes)[0] ?> до начала занятия.
                    </div>
                <?php } ?>

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
    <div class="dashboard__section" id="progress-block" style="display: none;">
        <div class="dashboard__section-title title">Достижения</div>
        <div class="achieve-slider-wrap slider-wrap">
            <div class="achieve-slider js-achieve-slider js-slider">
                <div class="achieve-slider__item">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+100 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+100 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/microphone-check-green.png" alt=""></div>
                        <div class="achieve-card__text">Пробный урок пройден</div>
                        <div class="achieve-card__rating">+120 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+60 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card achieve-card--accent">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/logo-yellow.png" alt=""></div>
                        <div class="achieve-card__text">Новое<br>достижение!</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+80 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+100 баллов</div>
                    </div>
                </div>
                <div class="achieve-slider__item swiper-slide">
                    <div class="achieve-card">
                        <div class="achieve-card__icon-wrap"><img src="/assets/smartsing-min/images/icons/home-green.png" alt=""></div>
                        <div class="achieve-card__text">Домашнее задание выполнено</div>
                        <div class="achieve-card__rating">+100 баллов</div>
                    </div>
                </div>
            </div>
            <div class="achieve-slider-nav slider-nav slider-nav--couple"><button class="btn slider-nav__item slider-nav__item--prev nav-btn nav-btn--sm swiper-btn swiper-btn--prev" type="button"><svg class="svg-icon--left svg-icon" width="6" height="12">
                        <use xlink:href="#left"></use>
                    </svg></button><button class="btn slider-nav__item slider-nav__item--next nav-btn nav-btn--sm swiper-btn swiper-btn--next" type="button"><svg class="svg-icon--right svg-icon" width="6" height="12">
                        <use xlink:href="#right"></use>
                    </svg></button></div>
        </div><!-- iframe.achieve-slider-frame-->
    </div>

    <!-- -->
    <div class="dashboard__section" id="statistics-block" style="display: none;">
        <div class="dashboard__section-title title">Статистика</div>
        <div class="stat dashboard__window win win win--grey">
            <div class="stat__top win__top">
                <div class="stat-colors">
                    <div class="stat-colors__item stat-colors__item--c1"></div>
                    <div class="stat-colors__item stat-colors__item--c3"></div>
                    <div class="stat-colors__item stat-colors__item--c2"></div>
                </div>
            </div>
            <div class="stat__inner dashboard__window-inner">
                <ul class="stat-list">
                    <li class="stat-list__item">
                        <div class="stat-list__value"><span class="highlight-c1">25</span>/30 нот</div>
                        <div class="stat-list__label">Координация слуха и голос</div>
                    </li>
                    <li class="stat-list__item">
                        <div class="stat-list__value">С3-B4 = 8 нот. <span class="highlight-c2">(+0.5)</span></div>
                        <div class="stat-list__label">Диапазон</div>
                    </li>
                    <li class="stat-list__item">
                        <div class="stat-list__value"><span class="highlight-c2">4</span></div>
                        <div class="stat-list__label">Домашних заданий выполнено</div>
                    </li>
                    <li class="stat-list__item">
                        <div class="stat-list__value"><span class="highlight-c2">5</span></div>
                        <div class="stat-list__label">Уроков пройдено</div>
                    </li>
                    <li class="stat-list__item">
                        <div class="stat-list__value"><span class="highlight-c3">1</span></div>
                        <div class="stat-list__label">Песен выучено</div>
                    </li>
                    <li class="stat-list__item">
                        <div class="stat-list__value"><span class="highlight-c2">100%</span></div>
                        <div class="stat-list__label">Без пропусков</div>
                    </li>
                </ul>
            </div>
            <div class="dashboard__footer text-right"><a class="text-light js-open-modal void-0" href="#" data-modal-id="stat-info-modal">Как это считается?</a></div>
        </div>
    </div>

</div>

<?= $this->render("../modals/student-modals/change-schedule-modal", [
    'CurrentUser' => $CurrentUser,
    'DASHBOARD_SCHEDULE_DATA' => $DashboardSchedule,
    'StudentsTimeline' => $StudentsTimeline,
]) ?>

<?= $this->render("../modals/student-modals/stat-info-modal") ?>

