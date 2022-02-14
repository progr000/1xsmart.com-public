<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */

use common\helpers\Functions;
use frontend\assets\smartsing\methodist\ManageScheduleAsset;

ManageScheduleAsset::register($this);

$no_show_popup_schedule = true;
?>

<div class="dashboard- dashboard-schedule" id="main-schedule-container" data-current-week-day="<?= date('N', $CurrentUser->_user_local_time) ?>">
    <div class="step step--time win win--grey">

        <div class="step__inner">
            <div class="step__title">
                <div>Укажите в какое время вы сможете проводить вводные занятия?</div>
            </div>
            <div class="step__choice step__choice--time time-setting js-wrap-dep">
                <form>
                    <div class="checkbox-group">
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-1" type="radio" name="week-days" data-dependent="begining-time-1" data-day="1">
                                <label class="down-arrow-label" for="day-1">Понедельник</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-2" type="radio" name="week-days" data-dependent="begining-time-2" data-day="2">
                                <label class="down-arrow-label" for="day-2">Вторник</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-3" type="radio" name="week-days" data-dependent="begining-time-3" data-day="3">
                                <label class="down-arrow-label" for="day-3">Среда</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-4" type="radio" name="week-days" data-dependent="begining-time-4" data-day="4">
                                <label class="down-arrow-label" for="day-4">Четверг</label>
                            </div>
                        </div>
                    </div>
                    <div class="time-container js-time-container">
                        <div class="time-pending">
                            Занятие с учеником <b>Павел (id: 123456)</b>. Выберите новое время занятий.
                        </div>
                        <div class="time-desc">
                            Пожалуйста выберите время, когда Вы будете доступны для проведения
                            вводного урока <span class="js-day"></span>:
                        </div>
                        <?= Functions::drawSchedule(1, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(2, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(3, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(4, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(5, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(6, $CurrentUser->user_timezone) ?>
                        <?= Functions::drawSchedule(7, $CurrentUser->user_timezone) ?>
                        <div class="time-note">Время указано по <?= $CurrentUser->_user_timezone_short_name?></div>
                    </div>
                    <div class="checkbox-group">
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-5" type="radio" name="week-days" data-dependent="begining-time-5" data-day="5">
                                <label class="up-arrow-label" for="day-5">Пятница</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-6" type="radio" name="week-days" data-dependent="begining-time-6" data-day="6">
                                <label class="up-arrow-label" for="day-6">Суббота</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap">
                                <input class="js-show-time" id="day-7" type="radio" name="week-days" data-dependent="begining-time-7" data-day="7">
                                <label class="up-arrow-label" for="day-7">Воскресенье</label>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
