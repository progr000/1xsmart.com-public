<?php

/** @var $this \yii\web\View */
/** @var $content string */
/** @var $tpl string */
/** @var $controller string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;

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
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($controller_id == 'operator' && $action_id == "index") ? '_current' : '' ?>"
               title="Главная"
               href="<?= Url::to(['operator/'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Главная</span></span>
            </a>
            <a class="user-menu__item user-menu__item--student js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "students-list") ? '_current' : '' ?>"
               href="<?= Url::to(['operator/students-list'], CREATE_ABSOLUTE_URL) ?>"
               title="Список учеников">
                <span class="user-menu__item-span"><span>Список учеников</span></span>
            </a>
            <a class="user-menu__item user-menu__item--coaches js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "teachers-list") ? '_current' : '' ?>"
               href="<?= Url::to(['operator/teachers-list'], CREATE_ABSOLUTE_URL) ?>"
               title="Список учителей">
                <span class="user-menu__item-span"><span>Список учителей</span></span>
            </a>
            <a class="user-menu__item user-menu__item--method js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "methodists-list") ? '_current' : '' ?>"
               href="<?= Url::to(['operator/methodists-list'], CREATE_ABSOLUTE_URL) ?>"
               title="Список методистов">
                <span class="user-menu__item-span"><span>Список методистов</span></span>
            </a>
        </div>
    </div>
    <div class="user-menu-section">
        <div class="user-menu">
            <a class="user-menu__item user-menu__item--profile js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "profile") ? '_current' : '' ?>"
               title="Мой профиль"
               href="<?= Url::to(['user/profile'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Мой профиль</span></span>
            </a>
            <a class="user-menu__item user-menu__item--finance js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>"
               href="javascript:;"
               title="Финансы">
                <span class="user-menu__item-span"><span>Финансы</span></span>
            </a>
            <a class="user-menu__item user-menu__item--tools js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "settings") ? '_current' : '' ?>"
               title="Настройки"
               href="<?= Url::to(['user/settings'], CREATE_ABSOLUTE_URL) ?>">
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
</div>

