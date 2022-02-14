<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $step integer */
/** @var $date_start string */

use yii\helpers\Url;
use common\helpers\Functions;
use frontend\assets\xsmart\student\SetupScheduleAsset;

SetupScheduleAsset::register($this);

?>
<div id="set-schedule-step-conf"
     class="set-schedule-steps"
     data-current-step="<?= $step ?>"
     data-check-error-3="You must select a date/Необходимо выбрать дату"
     data-check-error-4="You need to set a schedule/Необходимо установить расписание">
    <input type="hidden" id="date-start-input" value="<?= $date_start ?>" />
</div>

<!-- STEP #1 -->
<div class="container set-schedule-steps set-schedule-step-1">
    <div class="thnx">
        <div class="thnx__icon success-icon"></div>
        <div class="thnx__title"><?= Yii::t('student/set-schedule', 'Thank_you') ?></div>
        <div class="thnx__desc"><?= Yii::t('student/set-schedule', 'In_few_minutes') ?></div>
    </div>
</div>
<div class="step step--no-top-pad set-schedule-steps set-schedule-step-1">
    <div class="step__inner">
        <div class="step__progress step-progress"><span class="_current"></span><span></span><span></span></div>
        <div class="step__title">
            <div>01</div>
            <div><?= Yii::t('student/set-schedule', 'How_many_times') ?></div>
        </div>
        <form>
            <div class="checkbox-group">
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-1" type="radio" name="days-count"><label for="days-count-1">1</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-2" type="radio" name="days-count"><label for="days-count-2">2</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-3" type="radio" name="days-count"><label for="days-count-3">3</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-4" type="radio" name="days-count"><label for="days-count-4">4</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-5" type="radio" name="days-count"><label for="days-count-5">5</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-6" type="radio" name="days-count"><label for="days-count-6">6</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-7" type="radio" name="days-count"><label for="days-count-7">7</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-8" type="radio" name="days-count"><label for="days-count-8">8</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-9" type="radio" name="days-count"><label for="days-count-9">9</label></div>
                <div class="check-text-wrap check-text-wrap--symbol"><input id="days-count-10" type="radio" name="days-count"><label for="days-count-10">10</label></div>
            </div>
        </form>
    </div>
</div>


<!-- STEP #2 -->
<div class="container set-schedule-steps set-schedule-step-2"></div>
<div class="step set-schedule-steps set-schedule-step-2">
    <div class="step__inner">
        <div class="step__progress step-progress"><span class="_passed"></span><span class="_current"></span><span></span></div>
        <div class="step__title">
            <div>02</div>
            <div><?= Yii::t('student/set-schedule', 'When_start') ?></div>
        </div>
        <div class="step__choice step__choice--begining js-wrap-dep">
            <form>
                <div class="checkbox-group checkbox-group--double">
                    <div class="check-text-wrap">
                        <input class="icon-check js-has-dep date-start-immediately"
                               id="begining-1"
                               type="radio"
                               name="begining"
                               checked="checked"
                               data-dependent="begining-calendar">
                        <label class="btn-label btn-label--accent" for="begining-1">
                            <svg class="svg-icon-rocket svg-icon" width="24" height="24">
                                <use xlink:href="#rocket"></use>
                            </svg><?= Yii::t('student/set-schedule', 'Immediately') ?>
                        </label>
                    </div>
                    <div class="check-text-wrap">
                        <input class="icon-check js-has-dep js-show-dep date-start-manually"
                               id="begining-2"
                               type="radio"
                               name="begining"
                               data-dependent="begining-calendar">
                        <label class="btn-label" for="begining-2"><?= Yii::t('student/set-schedule', 'Choose_a_date') ?></label>
                    </div>
                </div>
                <div class="step__body dependent-visibility" id="begining-calendar">
                    <div class="big-datepicker-holder">
                        <input id="my-js-big-datepicker" class="inline-datepicker-input js-big-datepicker" type="text">
                    </div>
                </div>
            </form>
            <div class="step__nav">
                <button class="step__nav-btn step__nav-btn--hidden primary-btn primary-btn primary-btn--accent"
                        type="button"
                        data-step="3"><?= Yii::t('student/set-schedule', 'Proceed') ?></button>
                <button class="step__nav-btn secondary-btn secondary-btn secondary-btn--neutral"
                        type="button"
                        data-step="1"><?= Yii::t('student/set-schedule', 'Back') ?></button>
            </div>
        </div>
    </div>
</div>


<!-- STEP #3 -->
<div class="container set-schedule-steps set-schedule-step-3"></div>
<div class="step set-schedule-steps set-schedule-step-3">
    <div class="step__inner">
        <div class="step__progress step-progress"><span class="_passed"></span><span class="_passed"></span><span class="_current"></span></div>
        <div class="step__title">
            <div>03</div>
            <div><?= Yii::t('student/set-schedule', 'What_time_study') ?></div>
        </div>
        <div class="step__choice step__choice--time time-setting js-wrap-dep">
            <form>
                <div class="checkbox-group">
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-1" type="radio" name="week-days" data-dependent="begining-time-1"><label class="down-arrow-label" for="day-1"><?= Yii::t('app/common', 'Up_monday') ?></label></div>
                    </div>
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-2" type="radio" name="week-days" data-dependent="begining-time-2"><label class="down-arrow-label" for="day-2"><?= Yii::t('app/common', 'Up_tuesday') ?></label></div>
                    </div>
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-3" type="radio" name="week-days" data-dependent="begining-time-3"><label class="down-arrow-label" for="day-3"><?= Yii::t('app/common', 'Up_wednesday') ?></label></div>
                    </div>
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-4" type="radio" name="week-days" data-dependent="begining-time-4"><label class="down-arrow-label" for="day-4"><?= Yii::t('app/common', 'Up_thursday') ?></label></div>
                    </div>
                </div>
                <div class="time-container js-time-container">
                    <?= Functions::drawSchedule(1, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(2, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(3, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(4, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(5, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(6, $CurrentUser->user_timezone) ?>
                    <?= Functions::drawSchedule(7, $CurrentUser->user_timezone) ?>
                </div>
                <div class="checkbox-group">
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-5" type="radio" name="week-days" data-dependent="begining-time-5"><label class="up-arrow-label" for="day-5"><?= Yii::t('app/common', 'Up_friday') ?></label></div>
                    </div>
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-6" type="radio" name="week-days" data-dependent="begining-time-6"><label class="up-arrow-label" for="day-6"><?= Yii::t('app/common', 'Up_saturday') ?></label></div>
                    </div>
                    <div class="day-time-holder">
                        <div class="check-text-wrap"><input class="js-show-time" id="day-7" type="radio" name="week-days" data-dependent="begining-time-7"><label class="up-arrow-label" for="day-7"><?= Yii::t('app/common', 'Up_sunday') ?></label></div>
                    </div>
                </div>
            </form>
            <div class="time-note"><?= Yii::t('student/set-schedule', 'It_local_time') ?> <?= $CurrentUser->_user_timezone_short_name?></div>
            <div class="step__nav">
                <button class="step__nav-btn-final primary-btn primary-btn primary-btn--accent"
                        type="button"
                        data-step="5"><?= Yii::t('student/set-schedule', 'Proceed') ?></button>
                <button class="step__nav-btn secondary-btn secondary-btn secondary-btn--neutral"
                        type="button"
                        data-step="2"><?= Yii::t('student/set-schedule', 'Back') ?></button>
            </div>
        </div>
    </div>
</div>


<!-- STEP #4 -->
<div class="container set-schedule-steps set-schedule-step-4"></div>
<div class="step set-schedule-steps set-schedule-step-4">

</div>


<!-- STEP #5 -->
<div class="container content content--flex set-schedule-steps set-schedule-step-5">
    <div class="thnx thnx--full">
        <div class="thnx__icon success-icon"></div>
        <div class="thnx__title"><?= Yii::t('student/set-schedule', 'Thank_you_5') ?></div>
        <div class="thnx__desc" id="thnx-msg-step5">
            <?= Yii::t('student/set-schedule', 'On_day_teacher_wait') ?>
        </div>
        <a class="thnx__back-link primary-btn" href="<?= Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('student/set-schedule', 'To_Main') ?></a>
    </div>
</div>




