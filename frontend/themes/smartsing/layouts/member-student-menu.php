<?php
/** @var $this \yii\web\View */
/** @var $content string */
/** @var $tpl string */
/** @var $controller string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use common\models\Users;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;

?>
<div class="user-menu-holder user-menu-holder--slide js-slide-user-menu <?= $hide_left_menu ? '_left' : '' ?>">
    <div class="user-menu-top">
        <a href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>">
            <picture>
                <source srcset="/assets/smartsing-min/images/logo-h43.png" media="(max-width: 1600px)">
                <source srcset="/assets/smartsing-min/images/logo-menu.png">
                <img class="user-menu-logo" srcset="/assets/smartsing-min/images/logo-menu.png" alt="">
            </picture>
        </a>
        <span>Smart<br>Sing</span>
        <button class="btn slide-user-menu-btn js-slide-user-menu" type="button">
            <svg class="svg-icon--left svg-icon" width="10" height="18">
                <use xlink:href="#left"></use>
            </svg>
        </button>
    </div>
    <div class="user-menu-section user-menu-section--first user-menu-section--mob">
        <div class="available-lessons"><svg class="svg-icon--microphone-red svg-icon" width="30" height="30">
                <use xlink:href="#microphone-red"></use>
            </svg>Доступно уроков: <span>10</span></div>
        <div class="top-up"><button class="btn arrow-btn" type="button"><span>Пополнить</span></button></div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($controller_id == 'student' && $action_id == "index") ? '_current void-0' : '' ?>"
               title="Главная"
               href="<?= ($controller_id == 'student' && $action_id == "index") ? '#' : Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Главная</span></span>
            </a>
            <!--
            <a class="user-menu__item user-menu__item--homework js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
               title="Домашние задания"
               href="<?= Url::to(['user/home-works'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Домашние задания</span></span>
            </a>
            -->
            <!-- Расписание занятий -->
            <?php if ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) { ?>
                <a class="user-menu__item user-menu__item--schedule js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "set-schedule") ? '_current void-0' : '' ?>"
                   title="Расписание занятий"
                   href="<?= ($action_id == "set-schedule") ? '#' : Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Расписание занятий</span></span>
                </a>
            <?php } else { ?>
                <a class="user-menu__item user-menu__item--schedule js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "schedule") ? '_current void-0' : '' ?>"
                   title="Расписание занятий"
                   href="<?= ($action_id == "schedule") ? '#' : Url::to(['student/schedule'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Расписание занятий</span></span>
                </a>
            <?php } ?>
            <!-- Расписание занятий -->
            <a class="user-menu__item user-menu__item--video js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "device-test") ? '_current void-0' : '' ?>"
               href="<?= ($action_id == "device-test") ? '#' : Url::to(['user/device-test'], CREATE_ABSOLUTE_URL) ?>"
               title="Тест видео и аудио">
                <span class="user-menu__item-span"><span>Тест видео и аудио</span></span>
            </a>
            <!--
            <a class="user-menu__item user-menu__item--training js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
               title="Тренажер для голоса"
               href="javascript:;">
                <span class="user-menu__item-span"><span>Тренажер для голоса</span></span>
            </a>
            -->
        </div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item user-menu__item--profile js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "profile") ? '_current void-0' : '' ?>"
               title="Мой профиль"
               href="<?= ($action_id == "profile") ? '#' : Url::to(['user/profile'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Мой профиль</span></span>
            </a>
            <!-- Оплата -->
            <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                <a class="user-menu__item user-menu__item--payment js-user-menu-item void-0 js-alert <?= $hide_left_menu ? '_hidden' : '' ?>"
                   data-alert-text="Перед оплатой уроков, вам нужно пройти бесплатное вводное занятие с нашим методистом."
                   href="#">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) { ?>
                <a class="user-menu__item user-menu__item--payment js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
                   href="<?= Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } else { ?>
                <a class="user-menu__item user-menu__item--payment js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "payment") ? '_current void-0' : '' ?>"
                   href="<?= ($action_id == "payment") ? '#' : Url::to(['student/payment'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } ?>
            <!-- Оплата -->
            <!--
            <a class="user-menu__item user-menu__item--gift js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
               title="Программа лояльности"
               href="javascript:;">
                <span class="user-menu__item-span"><span>Программа лояльности</span></span>
            </a>
            -->
            <a class="user-menu__item user-menu__item--tools js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "settings") ? '_current void-0' : '' ?>"
               title="Настройки"
               href="<?= ($action_id == "settings") ? '#' : Url::to(['user/settings'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Настройки</span></span>
            </a>
        </div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item user-menu__item--logout js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
               title="Выход"
               href="<?= Url::to(['/site/logout'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Выход</span></span>
            </a>
        </div>
    </div>
    <div class="user-menu-section user-menu-section--mob">
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
    </div>
</div>