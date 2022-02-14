<?php

/** @var $static_action string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;

?>
<header class="member-header member-header--method">
    <button class="user-menu-btn btn square-btn hamburger-btn js-toggle-user-menu" type="button">
        <div class="hamburger"><span></span><span></span><span></span><span></span></div>
    </button>
    <div class="member-header__title">Admin area</div>
    <div class="member-header__date date-time">
        <svg class="svg-icon--clock-white svg-icon" width="30" height="30">
            <use xlink:href="#clock-white"></use>
        </svg>
        <span id="real-clock"
              data-time-zone="<?= $CurrentUser->user_timezone ?>"
              data-timezone-short-name="<?= $CurrentUser->_user_timezone_short_name ?>">
            <?= date(Yii::$app->params['datetime_format'], $CurrentUser->_user_local_time)?> <?= $CurrentUser->_user_timezone_short_name ?>
        </span>
    </div>
    <div class="support-block js-support">
        <button class="support-block__btn btn square-btn square-btn square-btn--lg js-open-support _active" type="button">
            <span class="btn-icon-wrap">
                <svg class="svg-icon--dialog svg-icon" width="20" height="22">
                    <use xlink:href="#dialog"></use>
                </svg>
            </span>
        </button>
        <div class="support-block__panel tabs-wrap tabs-wrap tabs-wrap--left js-support-panel">
            <ul class="tabs tabs tabs--left js-tabs">
                <li class="tabs__item -js-tabs-item">
                    <a target="_blank"
                       href="<?= Url::to(['user/conference-room', 'room' => 'common-public-room' /*$CurrentUser->_user_conference_room_hash*/], CREATE_ABSOLUTE_URL) ?>">Общая видео-комната</a>
                </li>
                <li class="tabs__item -js-tabs-item">
                    <a target="_blank"
                       href="<?= Url::to(['user/conference-room', 'room' => $CurrentUser->_user_conference_room_hash], CREATE_ABSOLUTE_URL) ?>">Моя видео-комната</a>
                </li>
                <li class="tabs__item js-tabs-item">Новости</li>
                <li class="tabs__item js-tabs-item _current">Служба поддержки</li>
            </ul>
            <div class="tabs-content">
                <div class="box"></div>
                <div class="box _visible">
                    <div class="messages-wrapper">
                        <div class="scroll-wrapper js-scroll">
                            <div class="messages-stack scroll-content">
                                <div class="message-item">
                                    <div class="message-item__name">Олег</div>
                                    <div class="message-item__date">21/05/2020, 15:30</div>
                                    <div class="message-item__text">
                                        <p>Здравствуйте Андрей, вы получили новое достижение «Домашнее задание выполнено», поздравляю)</p>
                                    </div>
                                </div>
                                <div class="message-item message-item message-item--own">
                                    <div class="message-item__name">Андрей</div>
                                    <div class="message-item__date">21/05/2020, 15:26</div>
                                    <div class="message-item__text">
                                        <p>Здравствуйте, когда у меня второй урок?</p>
                                    </div>
                                </div>
                                <div class="message-item _new">
                                    <div class="message-item__name">Олег</div>
                                    <div class="message-item__date">21/05/2020, 15:30</div>
                                    <div class="message-item__text">
                                        <p>Здравствуйте Андрей, завтра в 11.00. Смотрите в пункте меню «Расписание занятий»</p>
                                    </div>
                                </div>
                                <div class="message-item _new">
                                    <div class="message-item__name">Олег</div>
                                    <div class="message-item__date">21/05/2020, 15:30</div>
                                    <div class="message-item__text">
                                        <p>Здравствуйте Андрей, вы получили новое достижение «Домашнее задание выполнено», поздравляю)</p>
                                    </div>
                                </div>
                                <div class="message-item _new">
                                    <div class="message-item__name">Олег</div>
                                    <div class="message-item__date">21/05/2020, 15:30</div>
                                    <div class="message-item__text">
                                        <p>Здравствуйте Андрей, завтра в 11.00. Смотрите в пункте меню «Расписание занятий»</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="message-frm">
                            <input class="message-frm__input sm-input" type="text" placeholder="Напишите сообщение" />
                            <button class="message-frm__submit btn primary-btn primary-btn primary-btn--c6 sm-btn" type="submit">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
