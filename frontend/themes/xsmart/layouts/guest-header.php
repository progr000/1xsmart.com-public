<?php

/** @var $additional_header_class string */
/** @var $selected_menu string */
/** @var $static_action string */
/** @var $MENU array */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use frontend\widgets\langSwitch\langSwitchWidget;
use frontend\widgets\currencySwitch\currencySwitchWidget;

if (!$static_action) { $static_action = ''; }
$selected_menu = '/' . $static_action;
if (!isset($MENU[$selected_menu])) {
    $selected_menu = '/';
}
$menu_prn = '';
foreach ($MENU as $menu_url => $menu_name) {
    if ($menu_url == '/') { continue; }
    if ($menu_url == $selected_menu) {
        $menu_prn .= '<div class="main-menu__item"><span class="main-menu__link _current">' . $menu_name . '</span></div>';
    } else {
        $menu_prn .= '<div class="main-menu__item"><a class="main-menu__link" href="' . Url::to([$menu_url], CREATE_ABSOLUTE_URL) . '">' . $menu_name . '</a></div>';
    }
}
?>
<!-- begin .page-header-->
<header class="page-header js-page-header <?= $additional_header_class /* page-header--bordered */ ?>">
    <div class="page-header__top">
        <a class="page-header__logo" href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>"><img src="/assets/xsmart-min/images/logo.svg" alt=""><img src="/assets/xsmart-min/images/logo-white.svg" alt=""></a>
        <div class="page-header__inner"><button class="btn menu-btn js-open-main-menu" type="button"><span class="hamburger"><span></span><span></span></span><span><?= Yii::t('app/header', 'Menu') ?></span></button></div>
        <div class="page-header__controls">
            <!-- begin CURRENCY -->
            <?= currencySwitchWidget::widget(['theme' => 'dark']) ?>
            <!-- begin CURRENCY -->
            <!-- begin LANG -->
            <?= langSwitchWidget::widget(['theme' => 'dark']) ?>
            <!-- end LANG -->
            <?php if (!$CurrentUser) { ?>
            <button class="secondary-btn secondary-btn--light sm-btn auth-btn auth-btn--login js-open-modal" type="button" data-modal-id="login-popup"><?= Yii::t('app/header', 'Log_in') ?></button>
            <button class="primary-btn sm-btn auth-btn auth-btn--signup js-open-modal" type="button" data-modal-id="signup-popup"><?= Yii::t('app/header', 'Sign_Up') ?></button>
            <?php } else { ?>
                <a class="secondary-btn secondary-btn--light sm-btn auth-btn auth-btn--login" href="<?= Url::to(['user/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Member_Area') ?></a>
            <?php } ?>
        </div>
        <div class="main-menu-holder js-main-menu">
            <div class="main-menu-wrap">
                <nav class="main-menu">
                    <?= $menu_prn ?>
                </nav>
                <div class="guarantee"><img src="/assets/xsmart-min/images/guarantee-sm.png"><span><?= Yii::t('app/header', 'Guarantee') ?></span></div>
            </div>
            <div class="main-menu-controls">
                <?php if (!$CurrentUser) { ?>
                <button class="primary-btn sm-btn auth-btn auth-btn--signup js-open-modal" type="button" data-modal-id="signup-popup"><?= Yii::t('app/header', 'Sign_Up') ?></button>
                <?php } ?>
                <!-- begin CURRENCY -->
                <?= currencySwitchWidget::widget() ?>
                <!-- begin CURRENCY -->
                <!-- begin LANG -->
                <?= langSwitchWidget::widget() ?>
                <!-- end LANG -->
            </div>
            <div class="guarantee"><img src="/assets/xsmart-min/images/guarantee-sm.png"><span><?= Yii::t('app/header', 'Guarantee') ?></span></div>
        </div>
    </div>

    <?= $this->params['additional_header_promo'] ?>

</header>
<!-- end .page-header-->