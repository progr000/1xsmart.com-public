<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use common\models\Users;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;

?>
<!--begin User menu-->
<div class="user-menu-holder user-menu-holder--hidden js-user-menu">
    <a class="user-menu-top" href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>"><img src="/assets/smartsing-min/images/logo-menu.png" alt=""><span>Smart<br>Sing</span></a>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item <?= ($controller_id == 'student' && $action_id == "index") ? '_current void-0' : '' ?>"
               href="<?= ($controller_id == 'student' && $action_id == "index") ? '#' : Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Главная</span></span>
            </a>
            <a class="user-menu__item user-menu__item--homework"
               href="<?= Url::to(['user/home-works'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Домашние задания</span></span>
            </a>
            <?php
            if ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
                ?>
                <a class="user-menu__item user-menu__item--schedule <?= ($action_id == "set-schedule") ? '_current void-0' : '' ?>"
                   href="<?= ($action_id == "set-schedule") ? '#' : Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Расписание занятий</span></span>
                </a>
                <?php
            } else {
                ?>
                <a class="user-menu__item user-menu__item--schedule <?= ($action_id == "schedule") ? '_current void-0' : '' ?>"
                   href="<?= ($action_id == "schedule") ? '#' : Url::to(['student/schedule'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Расписание занятий</span></span>
                </a>
                <?php
            }
            ?>
            <a class="user-menu__item user-menu__item--video <?= ($action_id == "device-test") ? '_current void-0' : '' ?>"
               href="<?= ($action_id == "device-test") ? '#' : Url::to(['user/device-test'], CREATE_ABSOLUTE_URL) ?>"
               title="Тест видео и аудио">
                <span class="user-menu__item-span"><span>Тест видео и аудио</span></span>
            </a>
            <a class="user-menu__item user-menu__item--training"
               href="javascript:;">
                <span class="user-menu__item-span"><span>Тренажер для голоса</span></span>
            </a>
        </div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item user-menu__item--profile <?= ($action_id == "profile") ? '_current void-0' : '' ?>"
               href="<?= ($action_id == "profile") ? '#' : Url::to(['user/profile'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Мой профиль</span></span>
            </a>
            <!-- Оплата -->
            <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                <a class="user-menu__item user-menu__item--payment void-0 js-alert"
                   data-alert-text="Перед оплатой уроков, вам нужно пройти бесплатное вводное занятие с нашим методистом."
                   href="#">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) { ?>
                <a class="user-menu__item user-menu__item--payment <?= ($action_id == "after-introduce") ? '_current void-0' : '' ?>"
                   href="<?= ($action_id == "after-introduce") ? '#' : Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } else { ?>
                <a class="user-menu__item user-menu__item--payment"
                   href="<?= Url::to(['student/payment'], CREATE_ABSOLUTE_URL) ?>">
                    <span class="user-menu__item-span"><span>Оплата</span></span>
                </a>
            <?php } ?>
            <!-- Оплата -->
            <a class="user-menu__item user-menu__item--gift"
               href="javascript:;">
                <span>Программа лояльности</span>
            </a>
            <a class="user-menu__item user-menu__item--tools <?= ($action_id == "settings") ? '_current void-0' : '' ?>"
               href="<?= ($action_id == "settings") ? '#' : Url::to(['user/settings'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Настройки</span></span>
            </a>
        </div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item user-menu__item--logout js-user-menu-item"
               href="<?= Url::to(['/site/logout'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Выход</span></span>
            </a>
        </div>
    </div>
    <button class="user-menu-close-btn btn js-close-user-menu" type="button">
        <svg class="svg-icon--close-2 svg-icon" width="20" height="20">
            <use xlink:href="#close-2"></use>
        </svg>
    </button>
</div>
<!--end User menu-->

