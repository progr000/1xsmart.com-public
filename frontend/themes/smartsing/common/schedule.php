<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */

use common\helpers\Functions;
use frontend\assets\smartsing\common\ScheduleAsset;

ScheduleAsset::register($this);

$current_week_day = Functions::getDayOfWeek(time());
?>
<div class="row steps"
     id="user-schedule-content"
     data-week-day="<?= $current_week_day ?>"
     data-user-type="<?= $CurrentUser->user_type ?>">
    <div class="col-lg-12">
        <div class="row">
            <div id="week-days-list" class="col-lg-2">
                <a href="#" class="void-0 week-days allowed week-day-1" data-week-day="1">Понедельник</a><br />
                <a href="#" class="void-0 week-days allowed week-day-2" data-week-day="2">Вторник</a><br />
                <a href="#" class="void-0 week-days allowed week-day-3" data-week-day="3">Среда</a><br />
                <a href="#" class="void-0 week-days allowed week-day-4" data-week-day="4">Четверг</a><br />
                <a href="#" class="void-0 week-days allowed week-day-5" data-week-day="5">Пятница</a><br />
                <a href="#" class="void-0 week-days allowed week-day-6" data-week-day="6">Суббота</a><br />
                <a href="#" class="void-0 week-days allowed week-day-7" data-week-day="7">Воскресенье</a><br />
            </div>
            <div id="work-hours-list" class="col-lg-10">
                <?php
                $tz_lost = $CurrentUser->user_timezone / 3600;
                $minutes = intval(($tz_lost - floor($tz_lost)) * 60);
                if ($minutes < 10) { $minutes = "0{$minutes}"; }
                for ($i=0; $i<=23; $i++) {
                    if ($i < 10) {
                        $_prn = "0{$i}:{$minutes}";
                    } else {
                        $_prn = "{$i}:{$minutes}";
                    }
                    echo '<a href="#" class="void-0 work-hours allowed work-hour-' . $i . '" data-work-hour="' . $i . '">' . $_prn . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
