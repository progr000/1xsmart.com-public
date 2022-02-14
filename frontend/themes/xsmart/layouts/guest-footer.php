<?php

/** @var $static_action string */

use yii\helpers\Url;

if (!$static_action) { $static_action = ''; }
$selected_menu = '/' . $static_action;
if (!isset($MENU[$selected_menu])) {
    $selected_menu = '/';
}
$menu_prn = '';
foreach ($MENU as $menu_url => $menu_name) {
    if ($menu_url == '/') { continue; }
    if ($menu_url == $selected_menu) {
        $menu_prn .= '<div class="secondary-menu__item"><span class="secondary-menu__link _current">' . $menu_name . '</span></div>';
    } else {
        $menu_prn .= '<div class="secondary-menu__item"><a class="secondary-menu__link" href="' . Url::to([$menu_url], CREATE_ABSOLUTE_URL) . '">' . $menu_name  . '</a></div>';
    }
}

?>
<!-- begin .page-footer-->
<footer class="page-footer">
    <div class="page-footer__top"><a class="page-footer__logo" href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>"><img src="/assets/xsmart-min/images/logo.svg" alt=""></a>
        <div class="page-footer__inner">
            <div class="secondary-menu">
                <?= $menu_prn ?>
            </div>
        </div>
        <div class="guarantee"><img src="/assets/xsmart-min/images/guarantee-sm.png"><span><?= Yii::t('app/header', 'Guarantee2') ?></span></div>
    </div>
    <div class="page-footer__bottom">
        <div class="page-footer__copy"><?= Yii::t('app/header', 'Private_language_lessons') ?> | <?= Yii::$app->name ?></div>
        <div class="page-footer__links">
            <a href="<?= Url::to(['/privacy-policy'], CREATE_ABSOLUTE_URL) ?>" class="<?= ($static_action == "privacy-policy") ? 'void-0 _current' : '' ?>"><?= Yii::t('app/header', 'Privacy_Policy') ?></a>
            <a href="<?= Url::to(['/terms-of-use'], CREATE_ABSOLUTE_URL) ?>" class="<?= ($static_action == "terms-of-use") ? 'void-0 _current' : '' ?>"><?= Yii::t('app/header', 'Terms_of_use') ?></a>
        </div>
    </div>
</footer>
<!-- end .page-footer-->