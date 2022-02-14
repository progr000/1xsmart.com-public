<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $DASHBOARD_SCHEDULE_DATA array */

use yii\widgets\Pjax;
use common\helpers\Functions;
use frontend\assets\smartsing\methodist\ManageScheduleAsset;

ManageScheduleAsset::register($this);

?>

<!-- begin MODAL -->
<div class="modal" id="change-schedule-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <p class="lg-text-size">Вы являетесь методистом школы <strong>Smart Sing</strong>, новые ученики
                приходят в школу регулярно, а следовательно они нуждаются в вводном уроке.</p>
            <p>В данный момент Ваше расписание:</p>
            <!--<p class="lg-text-size">-->
            <?php Pjax::begin([
                'id' => 'popup-dashboard-schedule-list',
                'timeout' => PJAX_TIMEOUT,
                'options'=> ['tag'=>'p', 'class' => 'lg-text-size']
            ]); ?>
            <?php
            foreach ($DASHBOARD_SCHEDULE_DATA as $key=>$schedule) {
                ?>
                <span data-week-day="<?= $key ?>">
                    <b><?= Functions::getTextWeekDay($key, 'Up_') ?>:</b>
                    <?php
                    foreach ($schedule as $time) {
                        echo $time . "; ";
                    }
                    ?>
                </span>
                <br />
                <?php
            }
            ?>
            <?php Pjax::end(); ?>
            <!--</p>-->
            <p>Вы собираетесь изменить расписание для дня:</p>
            <form>
                <div class="time-setting js-wrap-dep">
                    <div class="checkbox-group checkbox-group--xs">
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-1" type="radio" name="week-days"
                                       data-dependent="begining-time-1" data-day="1">
                                <label class="down-arrow-label" for="day-1">Понедельник</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-2" type="radio" name="week-days"
                                       data-dependent="begining-time-2" data-day="2">
                                <label class="down-arrow-label" for="day-2">Вторник</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-3" type="radio" name="week-days"
                                       data-dependent="begining-time-3" data-day="3">
                                <label class="down-arrow-label" for="day-3">Среда</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-4" type="radio" name="week-days"
                                       data-dependent="begining-time-4" data-day="4">
                                <label class="down-arrow-label" for="day-4">Четверг</label>
                            </div>
                        </div>
                    </div>
                    <div class="time-container js-time-container">
                        <div class="time-pending">Занятие с учеником <b>Павел (id: 123456)</b>. Выберите новое время
                            занятий.
                        </div>
                        <div class="time-desc">Пожалуйста выберите время, когда Вы будете доступны для проведения
                            вводного урока <span class="js-day"></span>:
                        </div>
                        <?= Functions::drawSchedule(1, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(2, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(3, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(4, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(5, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(6, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <?= Functions::drawSchedule(7, $CurrentUser->user_timezone, [
                            0 => "time-setting__body time-group dependent-visibility",
                            1 => "checkbox-group checkbox-group--xs checkbox-group--center js-time-group",
                            2 => "check-text-wrap check-text-wrap--xxs",
                        ]) ?>
                        <div class="time-note">Время указано по <?= $CurrentUser->_user_timezone_short_name?></div>
                    </div>
                    <div class="checkbox-group checkbox-group--xs">
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-5" type="radio" name="week-days"
                                       data-dependent="begining-time-5" data-day="5">
                                <label class="up-arrow-label" for="day-5">Пятница</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-6" type="radio" name="week-days"
                                       data-dependent="begining-time-6" data-day="6">
                                <label class="up-arrow-label" for="day-6">Суббота</label>
                            </div>
                        </div>
                        <div class="day-time-holder">
                            <div class="check-text-wrap check-text-wrap--xs">
                                <input class="js-show-time" id="day-7" type="radio" name="week-days"
                                       data-dependent="begining-time-7" data-day="7">
                                <label class="up-arrow-label" for="day-7">Воскресенье</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="check-row check-row--offset change-form-controls active-form-controls">
                    <div class="check-wrap"><input id="move-date-31" type="radio" name="move-date" checked><label
                            for="move-date-31"><span></span><span>Перенести разово</span></label></div>
                    <div class="check-wrap"><input id="move-date-32" type="radio" name="move-date"><label
                            for="move-date-32"><span></span><span>Перенести регулярно</span></label></div>
                </div>
                <div class="form-footer save-form-controls active-form-controls">
                    <button class="btn primary-btn primary-btn--c6 modal__submit-btn" type="submit">Сохранить
                    </button>
                </div>
            </form>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->
