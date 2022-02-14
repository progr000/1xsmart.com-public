<?php

/** @var $static_action string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use common\models\Users;

$action_id = Yii::$app->controller->action->id;

?>
<header class="member-header"><button class="user-menu-btn btn square-btn hamburger-btn js-toggle-user-menu" type="button">
        <div class="hamburger"><span></span><span></span><span></span><span></span></div>
    </button>
    <div class="member-header__section member-header__section--left">
        <div class="date-time">
            <svg class="svg-icon--clock-white svg-icon" width="30" height="30">
                <use xlink:href="#clock-white"></use>
            </svg>
            <span id="real-clock"
                  data-time-zone="<?= $CurrentUser->user_timezone ?>"
                  data-timezone-short-name="<?= $CurrentUser->_user_timezone_short_name ?>">
            <?= date(Yii::$app->params['datetime_format'], $CurrentUser->_user_local_time)?> <?= $CurrentUser->_user_timezone_short_name ?>
        </span>
        </div>
        <div class="available-lessons">
            <svg class="svg-icon--microphone-red svg-icon" width="30" height="30">
                <use xlink:href="#microphone-red"></use>
            </svg>Доступно уроков: <span><?= $CurrentUser->user_lessons_available + $CurrentUser->_user_lessons_assigned ?></span>
        </div>
        <div class="top-up">
            <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                <a class="btn arrow-btn arrow-btn--grey void-0 js-alert"
                   data-alert-text="Перед оплатой уроков, вам нужно пройти бесплатное вводное занятие с нашим методистом."
                   href="#"><span>Пополнить</span></a>
            <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) { ?>
                <a class="btn arrow-btn arrow-btn--grey"
                   href="<?= Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL) ?>"><span>Пополнить</span></a>
            <?php } else { ?>
                <a class="btn arrow-btn arrow-btn--grey js-scroll-to <?= ($action_id == "payment") ? 'js-alert void-0' : '' ?>"
                   data-alert-text="Пожалуйста, выберите подходящий вам пакет уроков."
                   href="<?= ($action_id == "payment") ? '#price' : Url::to(['student/payment', '#' => 'price'], CREATE_ABSOLUTE_URL) ?>"><span>Пополнить</span></a>
            <?php } ?>
            <!--<button class="btn arrow-btn arrow-btn--grey" type="button"><span>Пополнить</span></button>-->
        </div>
    </div>
    <div class="member-header__section member-header__section--right">
        <div class="user-block">
            <?php
            if ($CurrentUser->user_status == Users::STATUS_ACTIVE) {
                $type_user_teacher = 'Ваш преподаватель';
                $src = $CurrentUser->teacherUser
                    ? $CurrentUser->teacherUser->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png')
                    : '/assets/smartsing-min/images/not_set.png';
                $name_user_teacher = $CurrentUser->teacherUser
                    ? $CurrentUser->teacherUser->user_first_name
                    : 'Не задан';
            } else {
                $type_user_teacher = 'Ваш методист';
                $src = $CurrentUser->methodistUser
                    ? $CurrentUser->methodistUser->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png')
                    : '/assets/smartsing-min/images/not_set.png';
                $name_user_teacher = $CurrentUser->methodistUser
                    ? $CurrentUser->methodistUser->user_first_name
                    : 'Не задан';
            }
            ?>
            <img class="user-block__ava"
                 src="<?= $src ?>"
                 alt=""
                 role="presentation" />
            <div class="user-block__data">
                <div class="user-block__position">
                    <?= $type_user_teacher ?>
                </div>
                <div class="user-block__name">
                    <?= $name_user_teacher ?>
                </div>
            </div>
        </div>
        <!--
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
                            <form class="message-frm"><input class="message-frm__input sm-input" type="text" placeholder="Напишите сообщение" /><button class="message-frm__submit btn primary-btn primary-btn primary-btn--c6 sm-btn" type="submit">Отправить</button></form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</header>
