<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $DashboardSchedule_v2 array */
/** @var $teacher_user_id */

use common\helpers\Functions;

$visible = [];
$expand_schedule = false;
foreach ($DashboardSchedule_v2 as $item1) {
    foreach ($item1['hours'] as $kh1=>$item_h1) {
        if ($item_h1['status'] > 0) {
            $visible[$kh1] = true;
        }
    }
}
if (!sizeof($visible)) {
    $expand_schedule = true;
}
?>

<!--<div class="schedule js-schedule">-->
    <div class="schedule__header">
        <button class="schedule__prev slider-nav-btn slider-nav-btn slider-nav-btn--prev js-schedule-prev" type="button"></button>
        <div class="schedule__toggle">
            <div class="check-wrap">
                <input class="js-expand-schedule switch-checkbox" type="checkbox" <?= $expand_schedule ? 'checked="checked"' : '' ?> id="schedule-expand22">
                <label for="schedule-expand22" id="t2"><span></span><span>Expand the schedule</span></label>
            </div>
        </div>
        <div class="schedule__title js-schedule-title">
            <?= Yii::t('app/common', "month_" . date('n', $DashboardSchedule_v2[0]['date'])) ?>
            <?= date('d', $DashboardSchedule_v2[0]['date']) ?>
            -
            <?= Yii::t('app/common', "month_" . date('n', $DashboardSchedule_v2[6]['date'])) ?>
            <?= date('d', $DashboardSchedule_v2[6]['date']) ?>
        </div>
        <button class="schedule__next slider-nav-btn slider-nav-btn slider-nav-btn--next js-schedule-next" type="button"></button>
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
        <div class="schedule__days js-schedule-carousel schedule-teacher-dashboard">
            <?php foreach ($DashboardSchedule_v2 as $key=>$item) { ?>
                <div class="schedule__day" data-month="<?= Yii::t('app/common', "month_" . date('n', $item['date'])) ?>" data-day="<?= date('d', $item['date']) ?>">
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
                            ?>

                            <?php if ($item_h['status'] == 1) {?>
                                <div data-hour="<?= $kh ?>"
                                     data-day="<?= $item['week_day'] ?>"
                                     class="schedule__booking-time sch-time-<?= $kh ?> schedule__booking-time--free js-has-tooltip -js-booking-btn -js-open-modal schedule-<?= $item['week_day'] ?>-<?= $kh ?>"
                                     data-tooltip="Unset"
                                     data-modal-id=""
                                     data-teacher-id="<?= $teacher_user_id ?>"
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
                            <?php } else if ($item_h['status'] == 2) { ?>
                                <div data-hour="<?= $kh ?>"
                                     data-day="<?= $item['week_day'] ?>"
                                     class="schedule__booking-time sch-time-<?= $kh ?> js-has-tooltip _disabled schedule-<?= $item['week_day'] ?>-<?= $kh ?>"
                                     data-tooltip="<?= $item_h['users'] ?>">
                                    <span><?= $prn_hour ?></span>
                                </div>
                            <?php } else { ?>
                                <div data-hour="<?= $kh ?>"
                                     data-day="<?= $item['week_day'] ?>"
                                     class="schedule__booking-time sch-time-<?= $kh ?> js-has-tooltip <?= $hidden ?> schedule-<?= $item['week_day'] ?>-<?= $kh ?>"
                                     data-tooltip="Set">
                                    <span><?= $prn_hour ?></span>
                                </div>
                            <?php } ?>

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
<!--</div>-->
