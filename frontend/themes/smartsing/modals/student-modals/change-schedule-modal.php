<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $StudentsTimeline \yii\db\ActiveRecord[] */

use yii\widgets\Pjax;
use common\helpers\Functions;
use frontend\models\schedule\CommonScheduleForm;
use frontend\assets\smartsing\student\ManageScheduleAsset;

ManageScheduleAsset::register($this);

?>
<div class="modal" id="change-schedule-modal-">
    <div class="modal__content">
        <div class="modal__inner">
            Обратитесь в нашу службу поддержки, и мы соглсуем расписание с вашим учителем, или подберем вам нового учителя.
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- begin MODAL -->
<div class="modal" id="change-schedule-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <form id="js-move-lesson-form" onsubmit="return false;">
                <div class="form-row">
                    <div class="form-col form-col--wide select-wrap new-student-modal input-wrap field-current-timeline-for-change required">
                        <?php Pjax::begin([
                            'id' => 'current-timeline-for-change',
                            'timeout' => PJAX_TIMEOUT,
                            'options'=> [
                                'class' => 'js-current-timeline-for-change js-move-lesson-field',
                                'tag' => 'select'
                            ],
                        ]); ?>
                        <!--<select class="-js-select js-current-timeline-for-change js-move-lesson-field"
                                id="current-timeline-for-change"
                                title="">-->
                            <?php
                            /** @var \common\models\StudentsTimeline $timeline */
                            foreach ($StudentsTimeline as $timeline) {
                                $week_day_gmt = Functions::getDayOfWeek($timeline->timeline_timestamp);
                                $work_hour_gmt = intval(date('H', $timeline->timeline_timestamp));

                                $tmp = CommonScheduleForm::dayAndHourFromGmtToTz($week_day_gmt, $work_hour_gmt, $CurrentUser->user_timezone);
                                ?>
                                <option value="<?= $timeline->timeline_id ?>"
                                        class="week-<?= $tmp['week_day'] ?> week-<?= $tmp['week_day'] ?>-hour-<?= $tmp['work_hour'] ?>"
                                        data-is-replacing="<?= $timeline->is_replacing ?>"
                                        data-week-day="<?= $tmp['week_day'] ?>"
                                        data-work-hour="<?= $tmp['work_hour'] ?>"><?=
                                    Functions::getTextWeekDay($tmp['week_day'], 'Up_') . ' ' .
                                    $CurrentUser->getDateInUserTimezoneByTimestamp($timeline->timeline_timestamp)
                                    ?></option>
                                <?php
                            }
                            ?>
                        <!--</select>-->
                        <?php Pjax::end(); ?>
                        <p class="hidden help-block help-block-error">Необходимо выбрать дату.</p>
                    </div>
                </div>
                <p>
                    Вы хотите перенести текущее занятие, запланированное на<br />
                    <strong id="text-current-timeline-for-change">{text-current-timeline-for-change}</strong>.<hr />
                    Укажите желаемую дату переноса<br />
                    из доступных вариантов учителя:
                </p>
                <div class="form-row">
                    <div class="form-col select-wrap new-student-modal input-wrap field-new-day-for-change required">
                        <select class="-js-select js-move-lesson-field"
                                id="new-day-for-change"
                                title="">
                            <option value="unselected">Выберите дату</option>
                        </select>
                        <p class="hidden help-block help-block-error">Необходимо выбрать дату.</p>
                    </div>
                    <div class="form-col select-wrap new-student-modal input-wrap field-new-time-for-change required">
                        <select class="-js-select js-move-lesson-field"
                                id="new-time-for-change"
                                title="">
                            <option value="unselected">Выберите время</option>
                        </select>
                        <p class="hidden help-block help-block-error">Необходимо выбрать время.</p>
                    </div>
                </div>
                <div class="check-row">
                    <div class="check-wrap">
                        <input id="is-replacing-true" class="js-move-lesson-field" type="radio" name="is_replacing" value="1" checked="checked" />
                        <label for="is-replacing-true"><span></span><span>Перенести разово</span></label>
                    </div>
                    <div class="check-wrap">
                        <input id="is-replacing-false" class="js-move-lesson-field" type="radio" name="is_replacing" value="0" />
                        <label for="is-replacing-false"><span></span><span>Перенести регулярно</span></label>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="btn primary-btn primary-btn--c6 locked modal__submit-btn js-move-lesson-button"
                            id="move-lesson-button"
                            type="button">Перенести занятие</button>
                    <div class="form-state" style="display: none;" id="move-lesson-status">Спасибо, занятие перенесено!</div>
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
