<?php

/** @var $static_action string */

use yii\helpers\Url;

?>
<!--begin .page-footer-->
<footer class="page-footer">
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
    </div>
</footer>
<!--end .page-footer-->