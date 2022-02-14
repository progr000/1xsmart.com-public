<?php

/** @var $additional_header_class string */
/** @var $selected_menu string */
/** @var $static_action string */
/** @var $MENU array */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;

if (!$static_action) { $static_action = ''; }
$selected_menu = '/' . $static_action;
if (!isset($MENU[$selected_menu])) {
    $selected_menu = '/';
}
$selected_menu_prn =
    '<div class="main-menu__item main-menu__item--top js-top-menu-item">' .
        '<a href="' . /*Url::to($selected_menu, CREATE_ABSOLUTE_URL)*/ '#' . '" class="void-0">' . $MENU[$selected_menu] . '</a>' .
    '</div>';
$other_menu_prn = '';
foreach ($MENU as $menu_url => $menu_name) {
    if ($menu_url == $selected_menu) { continue; }
    $other_menu_prn .= '<div class="main-menu__item"><a href="' . Url::to($menu_url, CREATE_ABSOLUTE_URL) . '">' . $menu_name . '</a></div>';
}
$menu_prn = $selected_menu_prn . '<div class="main-menu__dropdown">' . $other_menu_prn . '</div>';
?>
<div class="page-header js-page-header <?= $additional_header_class /*'page-header page-header--over'*/ ?>">
    <div class="page-header__inner">
        <button class="main-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-main-menu" type="button">
            <div class="hamburger"><span></span><span></span><span></span><span></span></div>
        </button>
        <a class="page-header__logo" href="<?= Url::to('/', CREATE_ABSOLUTE_URL) ?>">
            <picture>
                <source srcset="/assets/smartsing-min/images/logo_60x93@2x.png 2x, /assets/smartsing-min/images/logo_60x93.png 1x"><img src="/assets/smartsing-min/images/logo_60x93.png" alt="">
            </picture>
            <span class="page-header__title">Smart <br>Sing</span>
        </a>
        <div class="page-header__desc">Школа вокала - индивидуальные занятия вокалом в школе Smart Sing</div>
        <div class="main-menu main-menu--top js-main-menu">
            <?= $menu_prn ?>
            <a class="main-menu__phone" href="tel:<?= str_replace(['(', ')', ' ', '-'], '', Yii::$app->params['contact_phone']) ?>">
                <svg class="svg-icon--phone svg-icon" width="20" height="20">
                    <use xlink:href="#phone"></use>
                </svg><?= Yii::$app->params['contact_phone'] ?>
            </a>
            <button class="main-menu-close-btn btn js-close-main-menu" type="button"><svg class="svg-icon--close-2 svg-icon" width="20" height="20">
                    <use xlink:href="#close-2"></use>
                </svg>
            </button>
        </div>
        <a class="page-header__phone" href="tel:<?= str_replace(['(', ')', ' ', '-'], '', Yii::$app->params['contact_phone']) ?>">
            <svg class="svg-icon--phone svg-icon" width="20" height="20">
                <use xlink:href="#phone"></use>
            </svg><?= Yii::$app->params['contact_phone'] ?>
        </a>
        <?php if (!$CurrentUser) { ?>
            <a class="page-header__login btn secondary-btn js-open-modal void-0" href="#" data-modal-id="auth-modal">
                <span>Войти</span>
                <svg class="svg-icon--key svg-icon" width="20" height="19">
                    <use xlink:href="#key"></use>
                </svg>
            </a>
        <?php } else { ?>
            <a class="page-header__login btn secondary-btn" href="<?= Url::to('user/', CREATE_ABSOLUTE_URL) ?>">
                <span>В кабинет</span>
                <svg class="svg-icon--key svg-icon" width="20" height="19">
                    <use xlink:href="#key"></use>
                </svg>
            </a>
        <?php } ?>
    </div>
</div>