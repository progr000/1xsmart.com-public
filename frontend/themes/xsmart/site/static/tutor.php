<?php

/** @var $this yii\web\View */
/** @var $additionalData array */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Functions;
use common\models\Users;
use frontend\assets\xsmart\TutorAsset;
use frontend\assets\xsmart\PaymentAsset;

//var_dump($additionalData['schedule']);
$enable_first_week = false;
if ($CurrentUser && in_array($CurrentUser->user_status, [Users::STATUS_AFTER_INTRODUCE, Users::STATUS_AFTER_PAYMENT, Users::STATUS_ACTIVE])) {
    $enable_first_week = true;
}
/*
foreach ($additionalData['schedule'] as $k=>$v) {
    if ($k <= 6) {
        foreach ($v['hours'] as $kh=>$vh) {
            if ($vh['status'] == 1) {
                $enable_first_week = true;
            }
        }
    }
}
*/

/** @var \common\models\Users $tutor */
/** @var \common\models\Reviews $reviews */
$tutor = $additionalData['tutor'];
$schedule = $additionalData['schedule'];
$reviews = $additionalData['reviews'];

$this->title = Html::encode(Yii::t('static/tutor', 'title', ['APP_NAME' => Yii::$app->name, 'tutor_name' => $tutor->_user_display_name]));

TutorAsset::register($this);
PaymentAsset::register($this);

/**/
$lang = Yii::$app->language;

/**/
$tutor->initAdditionalDataForModel();

/**/
$youtube_video_id = Functions::getYoutubeVideoID($tutor->user_youtube_video);

//var_dump($additionalData['schedule']);
$visible = [];
foreach ($additionalData['schedule'] as $item1) {
    foreach ($item1['hours'] as $kh1=>$item_h1) {
        if ($item_h1['status'] > 0) {
            $visible[$kh1] = true;
            //if ($kh1 > 0) { $visible[$kh1 - 1] = true; }
            //if ($kh1 < 23) { $visible[$kh1 + 1] = true; }
        }
    }
}
?>

<div class="content content--with-back-link content--no-pad" xmlns="http://www.w3.org/1999/html">

    <a class="back-link" href="javascript:history.back()">
        <svg class="svg-icon-left-arrow svg-icon" width="20" height="8">
            <use xlink:href="#left-arrow"></use>
        </svg>
    </a>

    <div class="tutor-info <?= $enable_first_week ? '' : 'tutor-schedule-show-second-week' ?>" id="tutor-info-for-js-tz-reload">
        <div class="tutor-info__media">
            <?php if ($youtube_video_id) { ?>
                <div class="video">
                    <a class="video__link" href="https://youtu.be/<?= $youtube_video_id ?>">
                        <picture>
                            <source srcset="https://i.ytimg.com/vi_webp/<?= $youtube_video_id ?>/maxresdefault.webp"
                                    type="image/webp">
                            <img class="video__media"
                                 src="https://i.ytimg.com/vi/<?= $youtube_video_id ?>/maxresdefault.jpg"
                                 alt="" />
                        </picture>
                    </a>
                    <div class="tutor-info__play video-play video__btn"></div>
                </div>
            <?php } else { ?>
                <div class="no-video-tutor">
                    <p><?= Yii::t('static/tutor', 'No_video_present') ?></p>
                </div>
            <?php } ?>
        </div>
        <div class="tutor-info__data">
            <div class="tutor-info__title">
                <div class="tutor-info__name"><span><?= $tutor->_user_display_name ?></span></div>
                <div class="tutor-info__rating rating" id="tutor-main-rating"><?= $tutor->user_rating ?></div>
            </div>
            <div class="tutor-info__location location location location--lg">
                <img src="<?= Functions::getCountryImage($tutor->___country_code) ?>" alt="">
                <span><?= Functions::concatCountryCityName($tutor->___country_name, $tutor->___city_name) ?></span>
            </div>
            <?php
            $discipline_name = '';
            $TeachersDiscipline = $tutor->getMainDisciplineForThisTeacher();
            if ($TeachersDiscipline) {
                $field = "discipline_name_{$lang}";
                $discipline_name = $TeachersDiscipline->discipline_name_en;
                if ($TeachersDiscipline->hasAttribute($field)) {
                    $discipline_name = $TeachersDiscipline->{$field};
                }
            }
            ?>
            <div class="tutor-info__params params-tbl">
                <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Discipline_') ?></span><span><?= $discipline_name ?></span></div>
                <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Native_speaker_') ?></span><span><?= implode(', ', $tutor->___native_vars) ?></span></div>
                <?php if ($tutor->user_can_teach_children) { ?>
                    <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Can_teach_children_') ?></span><span><svg class="svg-icon-baby-boy svg-icon" width="20" height="18"><use xlink:href="#baby-boy"></use></svg></span></div>
                <?php } ?>
                <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Reviews_') ?></span><span id="tutor-main-reviews"><?= $tutor->user_reviews ?></span></div>
                <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Lessons_') ?></span><span><?= $tutor->user_lessons_spent ?></span></div>
            </div>
            <div class="tutor-info__rate">
                <div class="rate rate--lg"><?= Functions::getInCurrency($tutor->user_price_peer_hour)['sum'] ?> <?= Functions::getInCurrency($tutor->user_price_peer_hour)['name_lover'] ?>/<?= Yii::t('static/tutor', 'hour') ?></div>
                <div class="rate-level rate-level--lg"><img src="/assets/xsmart-min/images/price/<?= $tutor->___user_price_key ?>.svg" alt=""></div>
            </div>
            <div class="tutor-info__btns">
                <a class="tutor-info__schedule-btn primary-btn wide-btn js-scroll-to" href="#schedule"><?= Yii::t('static/tutor', 'Schedule_a_lesson') ?></a>
                <button class="tutor-info__chat-btn secondary-btn wide-btn <?= ($CurrentUser ? 'js-open-chat-with' : 'js-open-modal') ?>"
                        type="button"
                        data-opponent_user_id="<?= $tutor->user_id ?>"
                        data-opponent_display_name="<?= $tutor->_user_display_name ?>"
                        data-opponent_first_name="<?= $tutor->user_first_name ?>"
                        data-opponent_last_name="<?= $tutor->user_last_name ?>"
                        data-opponent_photo="<?= $tutor->user_photo ?>"
                        data-opponent_type="<?= $tutor->user_type ?>"
                        data-modal-id="<?= ($CurrentUser ? 'chat' : 'signup-popup') ?>"><?= Yii::t('static/tutor', 'Contact_the_tutor') ?></button>
            </div>
        </div>
    </div>
    <div class="tutor-desc ww">
        <?= nl2br(Functions::formatLongString($tutor->user_additional_info)) ?>
    </div>
    <section class="page-section page-section page-section--sm-margin" id="schedule">
        <h2 class="page-section__title"><?= Yii::t('static/tutor', 'Schedule') ?></h2>
        <a name="schedule"></a>
        <div class="schedule js-schedule">
            <div class="schedule__header">
                <button class="schedule__prev slider-nav-btn slider-nav-btn slider-nav-btn--prev js-schedule-prev" type="button"></button>
                <div class="schedule__toggle">
                    <div class="check-wrap">
                        <input class="js-expand-schedule switch-checkbox" type="checkbox" id="schedule-expand">
                        <label for="schedule-expand"><span></span><span><?= Yii::t('static/tutor', 'Expand_schedule') ?></span></label>
                    </div>
                </div>
                <div class="schedule__title js-schedule-title">
                    <?= Yii::t('app/common', "month_" . date('n', $additionalData['schedule'][0]['date'])) ?>
                    <?= date('d', $additionalData['schedule'][0]['date']) ?>
                    -
                    <?= Yii::t('app/common', "month_" . date('n', $additionalData['schedule'][6]['date'])) ?>
                    <?= date('d', $additionalData['schedule'][6]['date']) ?>
                </div>
                <?php if (!$CurrentUser) { ?>
                    <div class="schedule__timezone">
                        <form method="get" id="form-change-timezone" action="<?= Url::current(['#' => 'schedule'], CREATE_ABSOLUTE_URL) ?>">
                        <div class="select-wrap">

                            <label class="select-label" for="timezone-select"><?= Yii::t('static/tutor', 'Timezone_') ?></label>
                            <?= Html::dropDownList('timezone', $additionalData['current_tz'], Functions::get_list_of_timezones('offset_short_name'/*Yii::$app->language*/), [
                                'id'         => "timezone-select",
                                'class'      => "js-select simple-select tutor-timezone-change",
                                'aria-label' => "time-zone",
                            ]) ?>

                        </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="schedule__timezone">
                        <div class="select-wrap">
                            <label class="select-label static-tz" for="timezone-select"><?= Yii::t('static/tutor', 'Timezone_') ?></label>
                            <span class="static-tz"><?= $CurrentUser->_user_timezone_short_name ?></span>
                        </div>
                    </div>
                <?php } ?>
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
                            echo '<div class="schedule__time-value ' . $hidden . '" data-hour="' . $i . '">' . $i_prn . '</div>';
                        }
                        ?>
                        <div class="schedule__time-value"></div>
                        <!--
                        <div class="schedule__time-value _hidden">00:30</div>
                        <div class="schedule__time-value _hidden">01:00</div>
                        -->
                    </div>
                </div>
                <div class="schedule__days js-schedule-carousel">
                    <?php
                    $slide_on_count = 0;
                    $sl_i = 0;
                    foreach ($additionalData['schedule'] as $key=>$item) {
                        if ($CurrentUser) {
                            $now = time() + $CurrentUser->user_timezone;
                        } else {
                            $now = time() + $additionalData['current_tz'];
                        }
                        $_current = '';
                        if (date('j', $now) == date('j', $item['date'])) {
                            $_current = '_current';
                            $slide_on_count = $sl_i;
                        }
                        $sl_i++
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
                                    ?>

                                    <?php if ($item_h['status'] == 1) {?>
                                        <div data-hour="<?= $kh ?>"
                                             class="schedule__booking-time schedule__booking-time--free js-has-tooltip js-booking-btn js-open-modal"
                                             data-tooltip="Book the lesson!"
                                             data-modal-id="<?= $CurrentUser ? 'booking-popup' : 'signup-popup' ?>"
                                             data-teacher-id="<?= $tutor->user_id ?>"
                                             data-timestamp-gmt="<?= ($item['date'] + $kh * 60 * 60) - $additionalData['current_tz'] ?>"
                                             data-date-gmt="<?= date('Y-m-d, H:i:s', ($item['date'] + $kh * 60 * 60) - $additionalData['current_tz']) ?>"
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
                                             class="schedule__booking-time -js-has-tooltip _disabled"
                                             data-tooltip="<?= $item_h['users'] ?>">
                                            <span><?= $prn_hour ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div data-hour="<?= $kh ?>" class="schedule__booking-time <?= $hidden ?>"></div>
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
        </div>
    </section>
    <div style="display: none" id="slider-scroll-info" data-slide_on_count="<?= $slide_on_count ?>"></div>
</div>
<section class="page-section page-section page-section--sm-margin">
    <h2 class="page-section__title container"><?= Yii::t('static/tutor', 'Testimonials') ?></h2>
    <div class="bg-wrapper">
        <div class="container">
            <div class="testimonials-slider-wrap slider-wrap" id="reviews-container" data-teacher_user_id="<?= $tutor->user_id ?>">


                <?= $this->render('tutor-reviews', [
                    'reviews' => $reviews,
                    'CurrentUser' => $CurrentUser,
                    'tutor' => $tutor,
                ]) ?>


            </div>
            <div class="btns">
                <button class="primary-btn <?= ($CurrentUser) ? 'js-open-review-modal' : 'js-open-modal' ?>"
                        type="button"
                        <?= (!$CurrentUser || in_array($CurrentUser->user_type, [Users::TYPE_ADMIN, Users::TYPE_STUDENT])) ? '' : 'disabled="disabled"' ?>
                        data-teacher_user_id="<?= $tutor->user_id ?>"
                        data-have_no_this_teacher="<?= Yii::t('static/tutor', 'You_have_no_lesson_this_teacher') ?>"
                        data-already_leave_review="<?= Yii::t('static/tutor', 'You_already_leave_review') ?>"
                        data-modal-id="<?= ($CurrentUser) ? 'review-popup' : 'signup-popup' ?>"><?= Yii::t('static/tutor', 'Leave_review') ?></button>
            </div>
        </div>
    </div>
</section>


<!-- begin review-popup -->
<?php if ($CurrentUser) { ?>
    <div class="modal modal--light -_opened" id="review-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('static/tutor', 'Leave_review') ?></div>
            <form class="modal__form" action="" id="review-form">
                <div class="input-wrap">
                    <div class="active-rating-wrap">
                        <div class="active-rating js-rating-wrap">
                            <button class="active-rating__item btn js-rating-btn"
                                    type="button"
                                    title="1"
                                    data-mark="<?= Yii::t('static/tutor', 'very_bad') ?>">
                                <svg class="svg-icon-star svg-icon" width="25" height="24">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </button>
                            <button class="active-rating__item btn js-rating-btn"
                                    type="button"
                                    title="2"
                                    data-mark="<?= Yii::t('static/tutor', 'bad') ?>">
                                <svg class="svg-icon-star svg-icon" width="25" height="24">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </button>
                            <button class="active-rating__item btn js-rating-btn"
                                    type="button"
                                    title="3"
                                    data-mark="<?= Yii::t('static/tutor', 'so_so') ?>">
                                <svg class="svg-icon-star svg-icon" width="25" height="24">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </button>
                            <button class="active-rating__item btn js-rating-btn"
                                    type="button"
                                    title="4"
                                    data-mark="<?= Yii::t('static/tutor', 'good') ?>">
                                <svg class="svg-icon-star svg-icon" width="25" height="24">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </button>
                            <button class="active-rating__item btn js-rating-btn"
                                    type="button"
                                    title="5"
                                    data-mark="<?= Yii::t('static/tutor', 'excellent') ?>">
                                <svg class="svg-icon-star svg-icon" width="25" height="24">
                                    <use xlink:href="#star"></use>
                                </svg>
                            </button>
                            <input class="js-rating-input"
                                   name="review-rating"
                                   type="hidden"
                                   value="0"
                                   id="review-rating">
                        </div>
                        <div class="active-rating-text"><?= Yii::t('static/tutor', 'Your_mark_') ?> <span class="js-rating-text"></span></div>
                    </div>
                </div>
                <div class="input-wrap"><textarea id="review-textarea" placeholder="<?= Yii::t('static/tutor', 'Review_text') ?>"></textarea></div>
                <div class="modal__submit"><button id="js-submit-review" class="accent-btn wide-mob-btn" type="submit"><?= Yii::t('static/tutor', 'Send') ?></button></div>
            </form>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<?php } ?>
<!-- end review-popup -->

<!-- begin booking-popup -->
<?php if ($CurrentUser) { ?>
    <div class="modal modal--light" id="booking-popup">
        <div class="modal__inner">
            <div class="modal__body">
                <div class="modal__title"><?= Yii::t('static/tutor', 'Credit_Card') ?></div>
                <div class="modal__desc">
                    <p><?= Yii::t('static/tutor', 'You_going_book_lesson') ?> <span class="booking-tutor-name"><?= $tutor->user_first_name ?></span>.</p>
                </div>
                <div class="tutor-teaser">
                    <div class="tutor-teaser__top">
                        <img class="tutor-teaser__ava" src="<?= $tutor->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') ?>" alt="" role="presentation" />
                        <div class="tutor-teaser__header">
                            <div class="tutor-teaser__name booking-tutor-name"><?= $tutor->user_first_name ?></div>
                            <div class="tutor-teaser__location location">
                                <img src="<?= Functions::getCountryImage($tutor->___country_code) ?>" alt="">
                                <span class="booking-tutor-geo"><?= Functions::concatCountryCityName($tutor->___country_name, $tutor->___city_name) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="tutor-teaser__level">
                        <div class="tutor-teaser__rating rating"><?= $tutor->user_rating ?></div>
                        <div class="tutor-teaser__reviews"><?= Yii::t('static/tutor', 'reviews', ['count' => $tutor->user_reviews]) ?></div>
                    </div>
                </div>
                <div class="modal__section-title"><?= Yii::t('static/tutor', 'Lesson_details') ?></div>
                <div class="params-tbl">
                    <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Time_') ?></span><span id="booking-selected-date" data-user_timezone="<?= $CurrentUser ? $CurrentUser->_user_timezone_short_name : $additionalData['current_tz'] ?>">{selected-date}</span></div>
                    <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Lesson_duration_') ?></span><span>1 <?= Yii::t('static/tutor', 'hour') ?><!-- - <?= $tutor->user_price_peer_hour ?> usd--></span></div>
                    <!--<div class="params-tbl__row"><span>Transaction fee:</span><span>0.3 usd</span></div>-->
                    <div class="params-tbl__row"><span><?= Yii::t('static/tutor', 'Total_') ?></span><span><?= Functions::getInCurrency($tutor->user_price_peer_hour)['sum'] ?> <?= Functions::getInCurrency($tutor->user_price_peer_hour)['name_lover'] ?></span></div>
                </div>

                <div class="modal__submit">
                    <button class="primary-btn wide-mob-btn void-0 js-start-tinkoff-payment"
                            id="payment-params-button"
                            type="button"
                            data-teacher-id="<?= $tutor->user_id ?>"
                            data-lessons-count="1"
                            data-amount="<?= $tutor->user_price_peer_hour ?>"
                            data-timestamp-gmt=""
                            data-date-gmt=""
                            data-currency="usd"
                            data-description="For fist lesson with teacher <?= $tutor->user_first_name ?> (id=<?= $tutor->user_id ?>)."
                            data-action=""><?= Yii::t('static/tutor', 'Proceed_checkout') ?></button>
                    <div class="guarantee"><img src="/assets/xsmart-min/images/guarantee-sm.png"><span><?= Yii::t('static/tutor', 'Guarantee') ?></span></div>
                </div>

            </div>
            <button class="modal__close-btn js-close-modal" type="button">
                <svg class="svg-icon-close svg-icon" width="30" height="30">
                    <use xlink:href="#close"></use>
                </svg>
            </button>
        </div>
    </div>
<?php } ?>
<!-- end booking-popup -->

<!-- begin tinkoff form -->
<?php if ($CurrentUser) { ?>
    <div style="display: none;">
        <form name="TinkoffPayForm" id="tinkoff-payment-form" onsubmit="pay(this); return false;">
            <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="<?= Yii::$app->params['tinkoff_terminal_key'] ?>" />
            <input class="tinkoffPayRow" type="hidden" name="frame" value="true" />
            <input class="tinkoffPayRow" type="hidden" name="language" value="<?= Yii::$app->language ?>" />
            <input class="tinkoffPayRow"
                   id="tinkoff-order_amount"
                   type="text"
                   placeholder="order_amount"
                   name="amount"
                   value="{amount}"
                   required />
            <input class="tinkoffPayRow"
                   type="text"
                   id="tinkoff-order_id"
                   placeholder="order_id"
                   name="order"
                   value="{order-id}" />
            <input class="tinkoffPayRow"
                   type="text"
                   id="tinkoff-order_description"
                   placeholder="order_description"
                   name="description"
                   value="{description}" />
            <input class="tinkoffPayRow"
                   type="text"
                   placeholder="user_full_name"
                   name="name"
                   value="<?= $CurrentUser->user_full_name ?>" />
            <input class="tinkoffPayRow"
                   type="text"
                   placeholder="E-mail"
                   name="email"
                   value="<?= $CurrentUser->user_email ?>" />
            <input class="tinkoffPayRow"
                   type="text"
                   placeholder="Phone"
                   name="phone"
                   value="<?= $CurrentUser->user_phone ?>" />
            <input class="tinkoffPayRow" type="submit" value="Start pay" />
        </form>
    </div>
<?php } ?>
<!-- end tinkoff form -->