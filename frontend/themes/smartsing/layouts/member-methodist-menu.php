<?php
/** @var $this \yii\web\View */
/** @var $content string */
/** @var $tpl string */
/** @var $controller string */
/** @var $hide_left_menu boolean */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;

?>
<div class="user-menu-holder user-menu-holder--slide js-slide-user-menu <?= $hide_left_menu ? '_left' : '' ?>">
    <div class="user-menu-top"><a href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>">
            <picture>
                <source srcset="/assets/smartsing-min/images/logo-h43.png" media="(max-width: 1600px)">
                <source srcset="/assets/smartsing-min/images/logo-menu.png"><img class="user-menu-logo" srcset="/assets/smartsing-min/images/logo-menu.png" alt="">
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
            <a class="user-menu__item js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($controller_id == 'methodist' && $action_id == "index") ? '_current' : '' ?>"
               title="Главная"
               href="<?= Url::to(['methodist/'], CREATE_ABSOLUTE_URL) ?>">
                <span class="user-menu__item-span"><span>Главная</span></span>
            </a>
            <a class="user-menu__item user-menu__item--student js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?>" href="javascript:;" title="Список учеников">
                <span class="user-menu__item-span"><span>Список учеников</span></span>
            </a>
            <a class="user-menu__item user-menu__item--coaches js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "teachers-list") ? '_current' : '' ?>"
               href="<?= Url::to(['methodist/teachers-list'], CREATE_ABSOLUTE_URL) ?>"
               title="Список учителей">
                <span class="user-menu__item-span"><span>Список учителей</span></span>
            </a>
            <a class="user-menu__item user-menu__item--schedule js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "schedule") ? '_current' : '' ?>"
               href="<?= Url::to(['methodist/schedule'], CREATE_ABSOLUTE_URL) ?>"
               title="Расписание занятий">
                <span class="user-menu__item-span"><span>Расписание занятий</span></span>
            </a>
            <a class="user-menu__item user-menu__item--presets js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "presets" || $action_id == "view-preset") ? '_current' : '' ?>"
               href="<?= Url::to(['methodist/presets'], CREATE_ABSOLUTE_URL) ?>"
               title="Пресеты">
                <span class="user-menu__item-span"><span>Пресеты</span></span>
            </a>
            <a class="user-menu__item user-menu__item--video js-user-menu-item <?= $hide_left_menu ? '_hidden' : '' ?> <?= ($action_id == "device-test") ? '_current' : '' ?>"
               href="<?= Url::to(['user/device-test'], CREATE_ABSOLUTE_URL) ?>"
               title="Тест видео и аудио">
                <span class="user-menu__item-span"><span>Тест видео и аудио</span></span>
            </a>


            <!--
            <a class="user-menu__item user-menu__item--video js-user-menu-item" href="/user/introductory-class-room?room=333" title="">
                <span class="user-menu__item-span"><span>Комната (учитель)</span></span>
            </a>
            <a class="user-menu__item user-menu__item--video js-user-menu-item" href="/user/introductory-class-room?room=333&is_test_student=1" title="">
                <span class="user-menu__item-span"><span>Комната (ученик)</span></span>
            </a>
            -->

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
