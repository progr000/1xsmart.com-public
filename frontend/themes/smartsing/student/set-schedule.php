<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $step integer */
/** @var $date_start string */

use yii\helpers\Url;
use common\helpers\Functions;
use frontend\assets\smartsing\student\SetupScheduleAsset;

SetupScheduleAsset::register($this);

?>
<div id="set-schedule-step-conf"
     class="set-schedule-steps"
     data-current-step="<?= $step ?>"
     data-check-error-3="Необходимо выбрать дату"
     data-check-error-4="Необходимо установить расписание">
    <input type="hidden" id="date-start-input" value="<?= $date_start ?>" />
</div>

<!-- STEP #1 -->
<div class="after-top gradient-5 after-top--has-margin set-schedule-steps set-schedule-step-1">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="trial trial trial--after-payment container"><img class="trial__img accept-img" src="/assets/smartsing-min/images/accept.svg" alt="" role="presentation" />
        <!--<div class="trial__title trial__title--sm">Спасибо. <span class="highlight-c2">Оплата произведена</span></div>-->
        <div class="trial__title trial__title--sm">Оплата зачислена успешно. <br /><span class="highlight-c2">Теперь можно настроить расписание</span></div>
        <!--<div class="trial__desc">Через несколько минут вам зачислятся уроки</div>-->
    </div>
</div>
<div class="container set-schedule-steps set-schedule-step-1">
    <div class="step win win win--grey">
        <div class="win__top"></div>
        <div class="step__progress step-progress"><span class="_passed"></span><span></span><span></span><span></span></div>
        <div class="step__inner">
            <div class="step__title">
                <div>01.</div>
                <div>Сколько раз в неделю вы готовы заниматься?</div>
            </div>
            <div class="step__num num">01</div>
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
</div>

<!-- STEP #2 -->
<div class="top-gradient-5 set-schedule-steps set-schedule-step-2">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="container">
        <div class="step win win win--grey">
            <div class="win__top"></div>
            <div class="step__progress step-progress"><span class="_passed"></span><span class="_passed"></span><span></span><span></span></div>
            <div class="step__inner">
                <div class="step__title">
                    <div>02.</div>
                    <div>Когда вы готовы начать заниматься?</div>
                </div>
                <div class="step__num num">02</div>
                <div class="step__choice step__choice--begining js-wrap-dep">
                    <form>
                        <div class="checkbox-group">
                            <div class="check-text-wrap">
                                <input class="icon-check js-has-dep date-start-immediately"
                                       id="begining-1"
                                       type="radio"
                                       name="begining"
                                       checked="checked"
                                       data-dependent="begining-calendar">
                                <label class="btn-label" for="begining-1">
                                    <svg class="svg-icon--rocket-color svg-icon" width="50" height="50">
                                        <use xlink:href="#rocket-color"></use>
                                    </svg>
                                    <svg class="svg-icon--rocket svg-icon" width="50" height="50">
                                        <use xlink:href="#rocket"></use>
                                    </svg>Незамедлительно
                                </label>
                            </div>
                            <div class="check-text-wrap">
                                <input class="icon-check js-has-dep js-show-dep date-start-mannualy"
                                       id="begining-2"
                                       type="radio"
                                       name="begining"
                                       data-dependent="begining-calendar">
                                <label class="btn-label" for="begining-2">
                                    <svg class="svg-icon--calendar-6-color svg-icon" width="50" height="44">
                                        <use xlink:href="#calendar-6-color"></use>
                                    </svg>
                                    <svg class="svg-icon--calendar-6 svg-icon" width="50" height="44">
                                        <use xlink:href="#calendar-6"></use>
                                    </svg>Выберите дату
                                </label>
                            </div>
                        </div>
                    </form>
                    <div class="step__body dependent-visibility" id="begining-calendar">
                        <div class="big-datepicker-holder">
                            <input id="my-js-big-datepicker"
                                   class="inline-datepicker-input js-big-datepicker"
                                   type="text" />
                        </div>
                    </div>
                    <div class="step__nav">
                        <button class="step__nav-btn btn secondary-btn secondary-btn secondary-btn--c7 lg-btn round-btn" data-step="1" type="button">Назад</button>
                        <button class="step__nav-btn step__nav-btn--hidden btn primary-btn primary-btn primary-btn--c7 lg-btn round-btn"
                                data-step="3"
                                type="button">Продолжить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- STEP #3 -->
<div class="top-gradient-5 set-schedule-steps set-schedule-step-3">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="container">
        <div class="step step step--time win win win--grey">
            <div class="win__top"></div>
            <div class="step__progress step-progress"><span class="_passed"></span><span class="_passed"></span><span class="_passed"></span><span></span></div>
            <div class="step__inner">
                <div class="step__title">
                    <div>03.</div>
                    <div>В какое время вам удобно заниматься?</div>
                </div>
                <div class="step__num num">03</div>
                <div class="step__choice step__choice--time time-setting js-wrap-dep">
                    <form>
                        <div class="checkbox-group">
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-1" type="radio" name="week-days" data-dependent="begining-time-1"><label class="down-arrow-label" for="day-1">Понедельник</label></div>
                            </div>
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-2" type="radio" name="week-days" data-dependent="begining-time-2"><label class="down-arrow-label" for="day-2">Вторник</label></div>
                            </div>
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-3" type="radio" name="week-days" data-dependent="begining-time-3"><label class="down-arrow-label" for="day-3">Среда</label></div>
                            </div>
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-4" type="radio" name="week-days" data-dependent="begining-time-4"><label class="down-arrow-label" for="day-4">Четверг</label></div>
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
                            <div class="time-note">Время указано по <?= $CurrentUser->_user_timezone_short_name?></div>
                        </div>
                        <div class="checkbox-group">
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-5" type="radio" name="week-days" data-dependent="begining-time-5"><label class="up-arrow-label" for="day-5">Пятница</label></div>
                            </div>
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-6" type="radio" name="week-days" data-dependent="begining-time-6"><label class="up-arrow-label" for="day-6">Суббота</label></div>
                            </div>
                            <div class="day-time-holder">
                                <div class="check-text-wrap"><input class="js-show-time" id="day-7" type="radio" name="week-days" data-dependent="begining-time-7"><label class="up-arrow-label" for="day-7">Воскресенье</label></div>
                            </div>
                        </div>
                    </form>
                    <div class="step__nav">
                        <button class="step__nav-btn btn secondary-btn secondary-btn secondary-btn--c7 lg-btn round-btn" data-step="2" type="button">Назад</button>
                        <button class="step__nav-btn step__nav-btn--hidden btn primary-btn primary-btn primary-btn--c7 lg-btn round-btn"
                                data-step="4"
                                type="button">Продолжить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- STEP #4 -->
<div class="top-gradient-5 set-schedule-steps set-schedule-step-4">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="container">
        <div class="step step step--time win win win--grey">
            <div class="win__top"></div>
            <div class="step__progress step-progress"><span class="_passed"></span><span class="_passed"></span><span class="_passed"></span><span class="_passed"></span></div>
            <div class="step__inner">
                <div class="step__title">
                    <div>04.</div>
                    <div class="no-teachers-found teachers-result">Нет учителей с подходящим расписанием.</div>
                    <div class="searching-teachers teachers-result">Подождите, идет поиск</div>
                    <div class="teachers-found teachers-result">С кем из преподавателей вы бы хотели заниматься?</div>
                </div>
                <div class="step__num num">04</div>
                <div class="coaches-slider-wrap slider-wrap">
                    <div class="no-teachers-found teachers-result centered">
                        Свяжитесь с нашим саппортом, и мы подберем вам учителя индивидуально
                    </div>
                    <div class="searching-teachers teachers-result centered">
                        Идет подбор учителей соответственно вашему расписанию
                    </div>
                    <div class="coaches-slider -js-wide-coaches-slider -js-slider teachers-result teachers-found" id="teachers-list">
                        <!-- .begin list of teachers -->



                        <!-- .end list of teachers -->
                    </div>
                    <div class="coaches-slider-nav slider-nav slider-nav--centered">
                        <button class="btn slider-nav__item slider-nav__item--prev nav-btn nav-btn--sm nav-btn--shadow" type="button">
                            <svg class="svg-icon--left svg-icon" width="6" height="12">
                                <use xlink:href="#left"></use>
                            </svg>
                        </button>
                        <button class="btn slider-nav__item slider-nav__item--next nav-btn nav-btn--sm nav-btn--shadow" type="button">
                            <svg class="svg-icon--right svg-icon" width="6" height="12">
                                <use xlink:href="#right"></use>
                            </svg>
                        </button>
                    </div>

                    <div class="step__nav">
                        <button class="step__nav-btn btn secondary-btn secondary-btn secondary-btn--c7 lg-btn round-btn" data-step="3" type="button">Назад</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- tpl for step4 -->
<div id="tpl-teacher" style="display: none;">
    <a class="coaches-slider__item js-open-coach-details js-open-modal void-0"
       id="teacher-{teacher_user_id}"
       href="#"
       data-teacher_user_id="{teacher_user_id}"
       data-user_photo="{user_photo}"
       data-user_first_name="{user_first_name}"
       data-user_age="{user_age}"
       data-user_music_genres="{user_music_genres}"
       data-user_additional_info="{user_additional_info}"
       data-admin_notice="{admin_notice}"
       data-youtube_video="{user_youtube_video}"
       data-youtube_image_webp="{youtube_image_webp}"
       data-youtube_image_jpg="{youtube_image_jpg}"
       data-youtube_video_id="{youtube_video_id}"
       data-modal-id="choose-teacher-modal"
       data-user_local_video="{user_local_video}"
       data-save-opened="1">
        <div class="user-info user-info--card">
            <div class="user-info__user">
                <img class="user-info__ava user-photo-tpl"
                     data-src="{user_photo}"
                     src=""
                     alt=""
                     role="presentation" />
                <div>
                    <div class="user-info__name">{user_first_name}</div>
                </div>
            </div>
            <div class="user-info__data">
                <div class="user-info__data-item">
                    <div class="user-info__data-item-label">Возраст</div>
                    <div class="user-info__data-item-value">{user_age}</div>
                </div>
                <!--
                <div class="user-info__data-item">
                    <div class="user-info__data-item-label">Город</div>
                    <div class="user-info__data-item-value">Нахуй бы он тут всрался, нет этого в верстке и описании задачи</div>
                </div>
                -->
            </div>
        </div>
    </a>
</div>


<!-- STEP #5 -->
<div class="gradient-5 set-schedule-steps set-schedule-step-5">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="container">
        <div class="thnx">
            <div class="thnx__icon-wrap"><svg class="svg-icon--calendar-7 svg-icon" width="140" height="123">
                    <use xlink:href="#calendar-7"></use>
                </svg>
            </div>
            <div class="thnx__title">Спасибо</div>
            <div class="thnx__msg" id="thnx-msg-step5">
                <strong>{week_day} {short_date}</strong> в <strong>{short_time}</strong> преподаватель <strong>{user_first_name}</strong> будет ждать вас онлайн в вашем личном кабинете
            </div>
            <a class="thnx__link btn primary-btn primary-btn primary-btn--c6" href="<?= Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>">На главную</a>
        </div>
    </div>
</div>


<?= $this->render("../modals/student-modals/choose-teacher-modal") ?>

