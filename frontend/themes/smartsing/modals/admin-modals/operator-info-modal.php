<?php

/** @var $this yii\web\View */

use frontend\assets\smartsing\admin\UserInfoAsset;

UserInfoAsset::register($this);

?>

<a href="#"
   class="js-open-modal"
   id="trigger-operator-info-modal"
   data-modal-id="operator-info-modal"
   data-save-opened="1"
   style="display: none;">operator-info-modal</a>
<!-- begin MODAL -->
<div class="modal" id="operator-info-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Подробная информация об операторе</div>
            <div class="user-info user-info--portrait">
                <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                    <div class="user-info__main">
                        <div class="user-info__name">Олег</div>
                    </div>
                </div>
                <div class="user-info__data user-info__data--2col">
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">ID</div>
                        <div class="user-info__data-item-value">123453</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">IP-адрес, устройство пользователя</div>
                        <div class="user-info__data-item-value">IP: 45.52.69.12, HTTP_USER_AGENT: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3563.0 Safari/537.3</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Телеграм</div>
                        <div class="user-info__data-item-value">@vasya_ru</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Телефон</div>
                        <div class="user-info__data-item-value">0 (999) 123-43-54</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Скайп</div>
                        <div class="user-info__data-item-value">vasya_ru</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Электронная почта</div>
                        <div class="user-info__data-item-value">student@gmail.com</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Возраст</div>
                        <div class="user-info__data-item-value">25 лет</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Город</div>
                        <div class="user-info__data-item-value">Краснодар, Россия</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Опыт горячей/холодной линии</div>
                        <div class="user-info__data-item-value"><b>Да</b>/Нет</div>
                    </div>
                    <div class="user-info__data-item editable-wrap">
                        <div class="user-info__data-item-label">Комментарий<button class="btn edit-btn js-open-edit" type="button"><svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg></button></div>
                        <div class="user-info__data-item-value">
                            <div class="editable-value">
                                <div class="value">Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд.</div>
                                <form class="editable-value__frm js-editable-frm"><textarea class="editable-value__input">Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд. Отличный оператор. Всё гуд.</textarea><button class="editable-value__submit btn primary-btn primary-btn primary-btn--c6 sm-btn js-close-edit" type="submit">Сохранить</button><button class="editable-value__cancel btn secondary-btn secondary-btn secondary-btn--c7 sm-btn js-close-edit" type="button">Отмена</button></form>
                            </div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата начала работы</div>
                        <div class="user-info__data-item-value">21/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Назначено вводных уроков за последние 7 дней</div>
                        <div class="user-info__data-item-value">5</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Расписание</div>
                        <div class="user-info__data-item-value"><a href="javascript:;" data-modal-id="change-schedule-student-modal-3" data-save-opened="1" class="js-open-modal">вторник, четверг в 10 утра по мск</a></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">КПД</div>
                        <div class="user-info__data-item-value"><a href="javascript:;" data-modal-id="first-lessons-modal" data-save-opened="1" class="js-open-modal highlight-c2">7</a>/<a href="javascript:;" data-modal-id="talks-modal" data-save-opened="1" class="js-open-modal">10</a></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Выплачено оператору</div>
                        <div class="user-info__data-item-value">100 000<span class="rouble">d</span></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата последней выплаты</div>
                        <div class="user-info__data-item-value">25/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Платежная система</div>
                        <div class="user-info__data-item-value">Сбербанк</div>
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