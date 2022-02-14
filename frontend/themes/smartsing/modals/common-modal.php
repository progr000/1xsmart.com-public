<?php

/* @var $this yii\web\View */

?>

<!-- begin MODAL -->
<div class="modal modal--frame modal--sm-pad" id="modal-private">
    <div class="modal__content" style="min-width: 90%;">
        <div class="modal__inner">
            <div class="modal__title" id="pdf-title">{title}</div>
            <iframe src="/assets/smartsing-min/files/oferta.pdf" id="pdf-iframe" frameborder="0" height="90%" width="100%"></iframe>
        </div>
        <button class="btn modal__close-btn js-close-modal js-close-pdf-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

<!-- begin MODAL -->
<div class="modal" id="comment-text-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <div class="modal__title modal__title-receiver">{title}</div>
            <p class="receiver-container">{comment}</p>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

<!-- begin MODAL -->
<div class="modal" id="pretty-alert-modal">
    <div class="modal__content">
        <div class="modal__inner pretty-alert">
            <p id="pretty-alert-modal-text">{$ALERT_TEXT}</p>
            <form>
                <div class="form-footer">
                    <button id="pretty-alert-button-ok"
                            class="btn primary-btn primary-btn--c6 modal__submit-btn js-close-modal"
                            type="button">Ok</button>
                </div>
            </form>
        </div>
        <button id="pretty-alert-close-x" class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

<!-- begin MODAL -->
<div class="modal" id="pretty-confirm-modal">
    <div class="modal__content">
        <div class="modal__inner pretty-confirm">
            <p id="pretty-confirm-question-text">Вы уверены?</p>
            <form>
                <div class="form-footer">
                    <button id="pretty-confirm-button-yes"
                            class="button-confirm-yes btn primary-btn primary-btn--c6 modal__submit-btn js-close-modal"
                            type="button">Да</button>
                    &nbsp;
                    <button id="pretty-confirm-button-no"
                            class="button-confirm-no btn primary-btn primary-btn--c1 modal__submit-btn js-close-modal"
                            type="button">Нет</button>
                </div>
            </form>
        </div>
        <button id="confirm-close-x" class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

<!-- begin messaging -->
<div class="messaging-block__panel js-messaging-panel _hidden" id="messaging-user-panel">
    <ul class="tabs tabs tabs--left">
        <li class="tabs__item">
            <a href="#"
               class="messaging-user-panel-text-chat"
               id="messaging-user-panel-video-chat"
               data-pjax="0"
               target="_blank">Видео комната</a>
        </li>
        <li class="tabs__item">
            <a class="messaging-user-panel-text-chat void-0"
               id="messaging-user-panel-text-chat"
               data-pjax="0"
               href="#">Сообщение</a>
        </li>
    </ul>
</div>
<!-- end messaging -->

<!-- begin PRELOADER -->
<div class="preloader" id="site-loader-div">
    <div id="loader">
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<!-- end PRELOADER -->
