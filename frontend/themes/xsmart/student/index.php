<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $NextLesson array */
/** @var $DashboardSchedule array */
/** @var $StudentsTimeline \yii\db\ActiveRecord[] */
/** @var $DashboardSchedule_v2 array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\helpers\Functions;
use common\models\Users;
use frontend\models\search\NextLessons;
use frontend\assets\xsmart\student\ChangeScheduleAsset;
use frontend\models\schedule\StudentsScheduleForm;

ChangeScheduleAsset::register($this);

/**/
$this->title = Html::encode(Yii::t('student/index', 'Dashboard') . ' | ' . Yii::t('student/index', 'Student_area'));

/* подготовка данных по доступному расписанию учителя и по по Arranged lessons */
//var_dump($DashboardSchedule_v2);exit;
$DashboardSchedule_available = [];
$DashboardSchedule_arranged = [];
$DashboardSchedule_arranged_on_main = [];
//var_dump($DashboardSchedule_v2);exit;
foreach ($DashboardSchedule_v2 as $k=>$v) {
    if ($k<=6) { $week = 1; } else { $week = 2; }
    foreach ($v['hours'] as $k2=>$v2) {

        /**/
        if ($k2 < 10) {
            $_prn = "0{$k2}:00";
        } else {
            $_prn = "{$k2}:00";
        }

        /**/
        if ($v2['status'] == 1) {

            $DashboardSchedule_available[$week][$v['week_day']][$k2] = $_prn;
        }

        /**/
        if ($v2['status'] == 1 /*&& isset($v2['is_arranged'])*/) {
            $DashboardSchedule_arranged[$week][$v['week_day']][$k2] = $_prn;

            //var_dump($v2['date']);
            $DashboardSchedule_arranged_on_main[$k][$v['week_day']][] = [
                'timestamp' => $v2['date'],
                'week_day'  => intval($v['week_day']),
                '_prn'      => $_prn,
            ];
        }
    }
}
foreach ($DashboardSchedule_available as $k=>$v) {
    ksort($DashboardSchedule_available[$k]);
}
foreach ($DashboardSchedule_arranged as $k=>$v) {
    ksort($DashboardSchedule_arranged[$k]);
}
//var_dump($DashboardSchedule_available);exit;

/**/
$teacher = $CurrentUser->getTeacherForThisUser();

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
    $btn_class = '';
    $href = Url::to(['user/educational-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
    $title = '';
    $allow_button_timeout = 0;
    $href_timeout = $href;
} else {
    $btn_class = 'primary-btn--neutral locked void-0 tooltip-element js-has-tooltip';
    $href = '#';
    $title = Yii::t('student/index', 'able_to_enter_class', ['left_minutes' => $left_minutes, 'minutes_text' => Functions::left_minutes_ru_text($left_minutes)[0]]);
    $allow_button_timeout = $NextLesson['timeline_timestamp'] - (time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT);
    $href_timeout = Url::to(['user/educational-class-room', 'room' => $NextLesson['room_hash']], CREATE_ABSOLUTE_URL);
}

/**/
$schedule_link['href']  = '#';
$schedule_link['class'] = 'void-0';
$schedule_link['alert_text'] = '';
if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
    $schedule_link['href'] = '#';
    $schedule_link['class'] = 'void-0 js-alert';
    $schedule_link['alert_text'] = Yii::t('student/index', 'Available_after_completing_introductory');
} elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) {
    $schedule_link['href'] = Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL);
    $schedule_link['class'] = '';
    $schedule_link['alert_text'] = '';
} elseif ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT && $CurrentUser->teacher_user_id) {
    $schedule_link['href'] = Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL);
    $schedule_link['class'] = '';
    $schedule_link['alert_text'] = '';
} elseif ($CurrentUser->user_status == Users::STATUS_ACTIVE && $CurrentUser->teacher_user_id) {
    $schedule_link['href'] = '#';
    $schedule_link['class'] = 'void-0 js-open-modal';
    $schedule_link['alert_text'] = '';
} else {
    $schedule_link['href'] = '#';
    $schedule_link['class'] = 'void-0 js-alert';
    $schedule_link['alert_text'] = Yii::t('student/index', 'Something_wrong');
}

/**/
$visible = [];
$expand_schedule = false;
//var_dump($DashboardSchedule_v2);
foreach ($DashboardSchedule_v2 as $item1) {
    foreach ($item1['hours'] as $kh1=>$item_h1) {
        //var_dump($item_h1['status']);
        if ($item_h1['status'] < 2) {
            $visible[$kh1] = true;
            //if ($kh1 > 0) { $visible[$kh1 - 1] = true; }
            //if ($kh1 < 23) { $visible[$kh1 + 1] = true; }
        }
    }
}
if (!sizeof($visible)) {
    $expand_schedule = true;
//    for ($i=0; $i<=23; $i++) {
//        $visible[$i] = true;
//    }
}
?>


<div class="crumbs container">
    <a class="crumbs__link void-0" href="<?= Url::to(['/student'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
</div>
<div class="bg-wrapper">
    <div class="container">
        <div class="dashboard">

            <div class="dashboard__section">
                <div class="screen screen--sm screen screen--left schedule-info"
                     id="schedule-info-block"
                     data-lesson-timeout-skipped="<?= NextLessons::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED ?>">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="dashboard-schedule">
                            <div class="dashboard-schedule__icon-wrap">
                                <svg class="svg-icon-library svg-icon" width="70" height="70">
                                    <use xlink:href="#library"></use>
                                </svg>
                            </div>
                            <div class="dashboard-schedule__info">

                                <span style="display: none"
                                      id="minutes-translate"
                                      data-minut="<?= Yii::t('app/common', 'minut') ?>"
                                      data-minutu="<?= Yii::t('app/common', 'minutu') ?>"
                                      data-minuta="<?= Yii::t('app/common', 'minuta') ?>"
                                      data-minuti="<?= Yii::t('app/common', 'minuti') ?>"></span>
                                <!--
                                <div class="dashboard-schedule__next-class">
                                    Your next class is on the <span>26th Of May, 2021</span>
                                </div>
                                -->

                                <?php Pjax::begin([
                                    'id' => 'dashboard-next-lesson-time',
                                    'timeout' => PJAX_TIMEOUT,
                                    'options'=> ['class' => 'dashboard-schedule__next-class']
                                ]); ?>
                                <?php if ($NextLesson) { ?>
                                    <span class="dashboard-clear"
                                          id="lesson-in-progress"
                                          data-lesson-finished="<?= Yii::t('student/index', 'Lesson_is_finished') ?>"
                                          style="<?= $NextLesson['timeline_timestamp'] <= time() ? '' : 'display: none;' ?>">
                                        <?= Yii::t('student/index', 'At_moment_you_have_lesson', [
                                            'teacher_name' => '<a href="' . Url::to(["/tutor/{$NextLesson['teacher_user_id']}"]) . '">' . Users::getDisplayName($NextLesson['teacher_first_name'], $NextLesson['teacher_last_name']) . '</a>',
                                            'class_room'   => '<a href="' . $href_timeout . '" target="_blank"><span class="highlight-c1 no-block">class-room</span></a>',
                                        ]) ?>
                                    </span>
                                    <span class="dashboard-clear"
                                          id="lesson-left-time"
                                          style="<?= $NextLesson['timeline_timestamp'] <= time() ? 'display: none;' : '' ?>">
                                        <?= Yii::t('student/index', 'Your_next_class', [
                                            'next_text'    => $next_lesson_text,
                                            'teacher_name' => '<a href="' . Url::to(["/tutor/{$NextLesson['teacher_user_id']}"]) . '">' . Users::getDisplayName($NextLesson['teacher_first_name'], $NextLesson['teacher_last_name']) . '</a>',
                                        ]) ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="dashboard-clear" style="<?= $NextLesson['timeline_timestamp'] <= time() ? '' : 'display: none;' ?>">
                                        <?= Yii::t('student/index', 'have_no_classes_future') ?>
                                    </span>
                                <?php } ?>
                                <?php Pjax::end(); ?>



                                <?php Pjax::begin([
                                    'id' => 'dashboard-schedule-list',
                                    'timeout' => PJAX_TIMEOUT,
                                    'options'=> ['class' => 'dashboard-schedule__schedule']
                                ]); ?>

                                <div><?= Yii::t('student/index', 'Arranged_lessons_') ?></div>
                                <div>
                                    <?php
                                    if (!isset($DashboardSchedule_arranged_on_main) || !sizeof($DashboardSchedule_arranged_on_main)) {
                                        echo Yii::t('student/index', 'no_arranged_lessons');
                                    } else {

                                        foreach ($DashboardSchedule_arranged_on_main as $v) {
                                            //var_dump($v);
                                            foreach ($v as $k2=>$v2) {
                                                ?>
                                                <a href="#"
                                                   class="void-0"
                                                   data-week-day="<?= $k2 ?>"><?= Functions::getTextWeekDay($k2, 'Up_') . ' '. date('d/m', $v2[0]['timestamp'] + $CurrentUser->user_timezone) ?></a>:
                                                &nbsp;&nbsp;&nbsp;
                                                <?php
                                                foreach ($v2 as $time) {
                                                    echo $time['_prn'] . "; ";
                                                }
                                                ?>

                                                <br/>
                                                <?php
                                            }
                                        }

                                    }
                                    ?>
                                </div>

                                <!--
                                <div><?= Yii::t('student/index', 'Your_schedule_') ?></div>
                                <div>
                                    <?php
                                    /*
                                    if (!isset($DashboardSchedule) || !sizeof($DashboardSchedule)) {
                                        echo Yii::t('student/index', 'currently_not_set');
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
                                    */
                                    ?>
                                </div>
                                -->
                                <?php Pjax::end(); ?>

                            </div>
                        </div>
                        <a class="button dashboard-schedule-btn <?= $schedule_link['class'] ?>"
                           href="<?= $schedule_link['href'] ?>"
                           data-alert-text="<?= $schedule_link['alert_text'] ?>"
                           data-week-day="<?= intval(date('N', $CurrentUser->_user_local_time)) ?>"
                           data-work-hour="null"
                           data-old-modal-id="change-schedule-modal"
                           data-modal-id="schedule-active-popup"><?= Yii::t('student/index', 'Change_schedule') ?></a>
                    </div>
                </div>
            </div>

            <div class="dashboard__section">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body screen__body--no-pad">
                        <div class="enter-classroom">
                            <!--
                            <button class="enter-classroom__btn primary-btn primary-btn primary-btn--neutral" type="button">Enter to the classroom</button>
                            -->
                            <a id="entrance-class-button"
                               data-href-timeout="<?= $href_timeout ?>"
                               data-allow-button-timeout="<?= $allow_button_timeout ?>"
                               class="enter-classroom__btn primary-btn <?= $btn_class ?>"
                               target="_blank"
                               data-tooltip="<?= $title ?>"
                               title="<?= $title ?>"
                               href="<?= $href ?>"><?= Yii::t('student/index', 'Enter_classroom') ?></a>
                        </div>
                        <?php if ($title) { ?>
                        <div class="class-room-locked"><?= $title ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- schedule-student-active-popup -->
<?php if ($CurrentUser->user_status == Users::STATUS_ACTIVE && $CurrentUser->teacher_user_id) { ?>
<div class="modal modal--light modal modal--md-wide nested-modal-available" id="schedule-active-popup">
    <div class="modal__inner">
        <form class="modal__body">

            <?php Pjax::begin([
                'id' => 'popup-dashboard-schedule-list',
                'timeout' => PJAX_TIMEOUT,
                'options'=> ['tag'=>'div', 'class' => 'schedule-notices']
            ]); ?>
            <div class="schedule-notice">
                <div><?= Yii::t('student/index', 'Your_schedule_') ?></div>
                <div>
                    <?php
                    if (!isset($DashboardSchedule) || !sizeof($DashboardSchedule)) {
                        echo Yii::t('student/index', 'currently_not_set');
                    } else {
                        foreach ($DashboardSchedule as $key => $schedule) {
                            ?>
                            <?= Functions::getTextWeekDay($key, 'Up_') ?>:
                            <?php
                            foreach ($schedule as $tk => $time) {
                                echo $time . "; ";
                            }
                            ?>
                            <br/>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="schedule-notice">
                <div><?= Yii::t('student/index', 'Arranged_lessons_') ?></div>
                <div>
                    <?php
                    if (!isset($DashboardSchedule_arranged) || !sizeof($DashboardSchedule_arranged)) {
                        echo '<div>' . Yii::t('student/index', 'currently_not_set') . '</div>';
                    } else {
                        foreach ($DashboardSchedule_arranged as $key_week => $week) {
                            foreach ($week as $key => $schedule) {
                                ?>
                                <div class="available-week available-week-<?= $key_week ?> <?= $key_week == 2 ? 'hidden' : '' ?>" data-week-day="<?= $key ?>">
                                    <?= Functions::getTextWeekDay($key, 'Up_') ?>:
                                    <?php
                                    foreach ($schedule as $time) {
                                        echo $time . "; ";
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <!--
            <div class="schedule-notice">
                <div>Your schedule:</div>
                <div>
                    <div>Monday: 08:00;</div>
                    <div>Wednesday: 08:00;</div>
                </div>
            </div>
            -->
            <?php Pjax::end(); ?>

            <div class="schedule-change-tools js-schedule-tools" id="js-schedule-move-tools">
                <div class="schedule-change-info">
                    <p><?= Yii::t('student/index', 'going_change_lesson', ['teacher_name' => '<span class="lesson-with-tutor-name">' . $teacher->_user_display_name . '</span>']) ?></p>
                    <p><?= Yii::t('student/index', 'Choose_time') ?></p>
                </div>
                <div class="schedule-change-type check-row">
                    <div class="check-wrap">
                        <input type="radio" name="change-type" checked id="change-type-once" />
                        <label for="change-type-once"><span></span><span><?= Yii::t('student/index', 'Change_once') ?></span></label>
                    </div>
                    <div class="check-wrap">
                        <input type="radio" name="change-type" id="change-type-permanent" />
                        <label for="change-type-permanent"><span></span><span><?= Yii::t('student/index', 'Change_permanent') ?></span></label>
                    </div>
                </div>
            </div>
            <div class="schedule js-schedule" id="js-schedule-container">
                <div class="schedule__header">
                    <button class="schedule__prev slider-nav-btn slider-nav-btn slider-nav-btn--prev js-schedule-prev js-change-week" data-show-available-week="1" type="button"></button>
                    <div class="schedule__toggle">
                        <div class="check-wrap">
                            <input class="js-expand-schedule switch-checkbox" type="checkbox" <?= $expand_schedule ? 'checked="checked"' : '' ?> id="schedule-expand2">
                            <label for="schedule-expand2"><span></span><span><?= Yii::t('student/index', 'Expand_schedule') ?></span></label>
                        </div>
                    </div>
                    <div class="schedule__title js-schedule-title" id="which-show-available-week" data-show-available-week="1">
                        <?= Yii::t('app/common', "month_" . date('n', $DashboardSchedule_v2[0]['date'])) ?>
                        <?= date('d', $DashboardSchedule_v2[0]['date']) ?>
                        -
                        <?= Yii::t('app/common', "month_" . date('n', $DashboardSchedule_v2[6]['date'])) ?>
                        <?= date('d', $DashboardSchedule_v2[6]['date']) ?>
                    </div>
                    <div class="schedule__timezone">
                        <div class="select-wrap">
                            <label class="select-label static-tz" for="timezone-select"><?= Yii::t('student/index', 'Timezone_') ?></label>
                            <span class="static-tz"><?= $CurrentUser->_user_timezone_short_name ?></span>
                            <!--
                            <label class="select-label" for="timezone-select2">Timezone:</label>
                            <select class="js-select simple-select" id="timezone-select2">
                                <option value="GMT+3 Moscow" selected>GMT+3 Moscow</option>
                                <option value="GMT+4 Armenia">GMT+4 Armenia</option>
                                <option value="GMT+5 Pakistan">GMT+5 Pakistan</option>
                                <option value="GMT+6 Omsk">GMT+6 Omsk</option>
                                <option value="GMT+7 Kranoyask">GMT+7 Kranoyask</option>
                            </select>
                            -->
                        </div>
                    </div>
                    <button class="schedule__next slider-nav-btn slider-nav-btn slider-nav-btn--next js-schedule-next js-change-week" data-show-available-week="2" type="button"></button>
                </div>
                <div class="schedule__calendar">
                    <div class="schedule__time">
                        <div class="schedule__time-header"></div>
                        <div class="schedule__times">
                            <?php
                            for ($i=0; $i<=23; $i++) {
                                $i_prn = "{$i}:00";
                                if ($i < 10) { $i_prn = "0{$i}:00"; }
                                $hidden = '_hidden';
                                if (isset($visible[$i])) { $hidden = ''; }
                                echo '<div class="schedule__time-value sch-time-'. $i .' ' . $hidden . '" data-hour="' . $i . '">' . $i_prn . '</div>';
                            }
                            ?>
                            <div class="schedule__time-value"></div>
                        </div>
                    </div>
                    <div class="schedule__days js-schedule-carousel schedule-student-dashboard" id="js-schedule-student-dashboard">
                        <?php
                        foreach ($DashboardSchedule_v2 as $key=>$item) {
                            $now = time() + $CurrentUser->user_timezone;
                            $_current = '';
                            if (date('j', $now) == date('j', $item['date'])) {
                                $_current = '_current';
                            }
                            ?>
                            <div class="schedule__day <?= $_current ?>" data-month="<?= Yii::t('app/common', "month_" . date('n', $item['date'])) ?>" data-day="<?= date('d', $item['date']) ?>">
                                <div class="schedule__day-header"><?= Functions::getTextWeekDay($item['week_day'], 'short_') . ' ' . date('d', $item['date']) ?></div>
                                <div class="schedule__booking-times">
                                    <?php
                                    foreach ($item['hours'] as $kh => $item_h) {

                                        /**/
                                        $prn_hour = "{$kh}:00";
                                        if ($kh < 10) { $prn_hour = "0{$kh}:00"; }

                                        /**/
                                        $hidden = '_hidden';
                                        if (isset($visible[$kh])) { $hidden = ''; }

                                        $teacher_display_name = '';
                                        $teacher_user_id = '';
                                        $title = '';
                                        $available_for_teachers_ids = '';

                                        /**/
                                        if ($item_h['status'] == 1) {
                                            $hour_class = "schedule__booking-time sch-time-{$kh} schedule-{$item['week_day']}-{$kh} js-booking-btn-active _active";
                                            $teacher_display_name = $item_h['teacher_display_name'];
                                            $teacher_user_id = $item_h['teacher_user_id'];
                                            $title = "Lesson with teacher {$item_h['teacher_display_name']}";
                                        } else if ($item_h['status'] == 2) {
                                            $hour_class = "schedule__booking-time sch-time-{$kh} schedule-{$item['week_day']}-{$kh} {$hidden} _disabled " . (isset($item_h['additional_class']) ? $item_h['additional_class'] : '');
                                        } else {
                                            $hour_class = "schedule__booking-time is-free sch-time-{$kh} schedule-{$item['week_day']}-{$kh} js-booking-btn-free";
                                            $available_for_teachers_ids = isset($item_h['available_for_teachers_ids'])
                                                ? $item_h['available_for_teachers_ids']
                                                : '';
                                            if ($available_for_teachers_ids) {
                                                $t_list = explode(',', $available_for_teachers_ids);
                                                $t_class = "";
                                                foreach ($t_list as $t_id) {
                                                    $t_id = intval($t_id);
                                                    $t_class .= " available_for_teacher_{$t_id}";
                                                }
                                                $hour_class .= $t_class;
                                            }
                                        }
                                        ?>

                                        <div id="hour-<?= $item_h['date'] ?>"
                                             title="<?= $title ?>"
                                             data-hour="<?= $kh ?>"
                                             data-day="<?= $item['week_day'] ?>"
                                             class="<?= $hour_class ?>"
                                             data-hour-status="<?= $item_h['status'] ?>"
                                             data-available_for_teachers_ids="<?= $available_for_teachers_ids ?>"
                                             data-modal-id=""
                                             data-teacher_display_name="<?= $teacher_display_name ?>"
                                             data-teacher-id="<?= $teacher_user_id /*$CurrentUser->teacher_user_id*/ ?>"
                                             data-timeline-id="<?= isset($item_h['timeline_id']) ? $item_h['timeline_id'] : '' ?>"
                                             data-schedule-id="<?= isset($item_h['schedule_id']) ? $item_h['schedule_id'] : '' ?>"
                                             data-timestamp-gmt="<?= ($item['date'] + $kh * 60 * 60) - $CurrentUser->user_timezone ?>"
                                             data-date-gmt="<?= date('Y-m-d, H:i:s', ($item['date'] + $kh * 60 * 60) - $CurrentUser->user_timezone) ?>"
                                             data-test="<?= $item_h['date'] ?>"
                                             data-print-date="<?=
                                                 Functions::getTextWeekDay($item['week_day'], 'Up_') .
                                                 ', ' .
                                                 Yii::t('app/common', "month_" . date('n', $item['date'])) .
                                                 ' ' .
                                                 date('d, Y', $item['date']) . " at {$prn_hour}"
                                             ?>">
                                            <span><?= $prn_hour ?></span>
                                        </div>

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="schedule__mob-nav">
                    <button class="schedule__prev slider-nav-btn slider-nav-btn slider-nav-btn--prev js-schedule-prev" type="button"></button>
                    <button class="schedule__next slider-nav-btn slider-nav-btn slider-nav-btn--next js-schedule-next" type="button"></button>
                </div>
            </div>
            <div class="schedule-note"><?= Yii::t('student/index', 'Click_lesson_to_select', ['CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS' => StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS]) ?></div>
        </form>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<?php } ?>