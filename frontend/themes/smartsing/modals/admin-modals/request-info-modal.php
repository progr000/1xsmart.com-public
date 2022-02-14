<?php

/** @var $this yii\web\View */

?>

<!-- begin MODAL -->
<div class="modal" id="request-info-modal">
    <div class="modal__content select-wrap js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Подробная информация о клиенте</div>
            <div class="user-info user-info--portrait">
                <div class="user-info__user">
                    <img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                    <div class="user-info__main">
                        <div class="user-info__name" id="request-lead_name">{lead_name}</div>
                        <!--<div class="user-info__sex">Мужчина</div>-->
                    </div>
                </div>
                <div class="user-info__data user-info__data--2col">
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">ID</div>
                        <div class="user-info__data-item-value" id="request-lead_id">{lead_id}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата заявки</div>
                        <div class="user-info__data-item-value" id="request-lead_created">{lead_created}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Телефон</div>
                        <div class="user-info__data-item-value" id="request-lead_phone">{lead_phone}</div>
                    </div>
                    <div class="user-info__data-item editable-wrap">
                        <div class="user-info__data-item-label">
                            Комментарий
                            <button class="btn edit-btn js-open-edit" type="button">
                                <svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="user-info__data-item-value">
                            <div class="editable-value">
                                <div class="value" id="request-operator_notice">{operator_notice}</div>
                                <form class="editable-value__frm js-editable-frm">
                                    <textarea class="editable-value__input" id="request-textarea-operator_notice">
                                        {operator_notice}
                                    </textarea>
                                    <button class="editable-value__submit btn primary-btn primary-btn primary-btn--c6 sm-btn js-close-edit" type="submit">Сохранить</button>
                                    <button class="editable-value__cancel btn secondary-btn secondary-btn secondary-btn--c7 sm-btn js-close-edit" type="button">Отмена</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Электронная почта</div>
                        <div class="user-info__data-item-value" id="request-lead_email">{lead_email}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">IP-адрес, устройство пользователя</div>
                        <div class="user-info__data-item-value" id="request-additional_service_info">{additional_service_info}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label"><br /></div>
                        <div class="user-info__data-item-value"><br /></div>
                    </div>
                </div>
            </div>

        </div>

        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
        <button class="btn modal__back-btn js-close-modal" type="button"><svg class="svg-icon--left svg-icon" width="7" height="12">
                <use xlink:href="#left"></use>
            </svg>Назад
        </button>
    </div>
</div>
<!-- end MODAL -->
