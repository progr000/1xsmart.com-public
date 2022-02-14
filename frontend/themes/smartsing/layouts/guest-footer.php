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
        $menu_prn .= '<div class="secondary-menu__item _current"><span>' . $menu_name . '</span></div>';
    } else {
        $menu_prn .= '<div class="secondary-menu__item"><a href="' . Url::to($menu_url, CREATE_ABSOLUTE_URL) . '">' . $menu_name . '</a></div>';
    }
}

?>
<!--begin .page-footer-->
<footer class="page-footer page-footer page-footer--no-margin">
    <div class="page-footer__inner">
        <div class="page-footer__logo-holder">
            <a class="page-footer__logo" href="<?= Url::to('/', CREATE_ABSOLUTE_URL) ?>">
                <img src="/assets/smartsing-min/images/logo-footer.png" alt="">
            </a>
            <div class="copy">
                <div>© 2020 — Sing in Smart style!</div>
                <a class="void-0 footer-link js-open-modal js-open-pdf-modal"
                   href="#"
                   data-title="Пользовательское соглашение"
                   data-content="/assets/smartsing-min/files/oferta.pdf"
                   data-modal-id="modal-private">Пользовательское соглашение</a>
                <br />
                <a class="void-0 footer-link js-open-modal js-open-pdf-modal"
                   href="#"
                   data-title="Политика конфиденциальности"
                   data-content="/assets/smartsing-min/files/confidentiality.pdf"
                   data-modal-id="modal-private">Политика конфиденциальности</a>
            </div>
        </div>
        <div class="secondary-menu">
            <?= $menu_prn ?>
        </div>
        <div class="social">
            <a class="social__item logo-card void-0" href="#">
                <img alt="logo visa master mir"
                     class="logo-visa-master-mir"
                     src="/assets/smartsing-min/images/logo-visa_mast_mir.png"/>
            </a>
            <a class="social__item logo-card void-0" href="#">
                <img alt="logo visa master mir"
                     class="logo-tinkoff"
                     src="/assets/smartsing-min/images/logo-tinkoff.svg"/>
            </a>
            <a class="social__item void-0" href="#">
                <svg class="svg-icon--vk-bg svg-icon" width="40" height="40">
                    <use xlink:href="#vk-bg"></use>
                </svg>
            </a>
            <a class="social__item void-0" href="#">
                <svg class="svg-icon--fb-bg svg-icon" width="40" height="40">
                    <use xlink:href="#fb-bg"></use>
                </svg>
            </a>
            <a class="social__item void-0" href="#">
                <svg class="svg-icon--inst-bg svg-icon" width="40" height="40">
                    <use xlink:href="#inst-bg"></use>
                </svg>
            </a>
        </div>
    </div>
</footer>
<!--end .page-footer-->