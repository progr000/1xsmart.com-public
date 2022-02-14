<?php

/** @var $this yii\web\View */

use frontend\assets\smartsing\admin\UserInfoAsset;

UserInfoAsset::register($this);

?>

<a href="#"
   class="js-open-modal"
   id="trigger-teacher-info-modal"
   data-modal-id="teacher-info-modal"
   data-save-opened="1"
   style="display: none;">teacher-info-modal</a>
<!-- begin MODAL -->
<div class="modal" id="teacher-info-modal">
    <div class="modal__content select-wrap js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Подробная информация об учителе</div>
            <div class="user-info user-info--portrait">
                <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                    <div class="user-info__main">
                        <div class="user-info__name">Олег Петрович</div>
                        <div class="user-info__sex">Мужчина</div>
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
                        <div class="user-info__data-item-label">Источник</div>
                        <div class="user-info__data-item-value">
                            <div class="user-info__from">Сайт/<b>Телефон</b></div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Телефон</div>
                        <div class="user-info__data-item-value"><button class="btn load-btn load-btn--sm" type="button"><svg class="svg-icon--loading svg-icon" width="14" height="14">
                                    <use xlink:href="#loading"></use>
                                </svg><span>Загрузить</span></button></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата заявки</div>
                        <div class="user-info__data-item-value">21/05/2020</div>
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
                        <div class="user-info__data-item-label">Время первого перезвона</div>
                        <div class="user-info__data-item-value highlight-c2">21/05/2020</div>
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
                        <div class="user-info__data-item-label">Любымые жанры музыки:</div>
                        <div class="user-info__data-item-value">Рок, популярная музыка</div>
                    </div>
                    <div class="user-info__data-item editable-wrap">
                        <div class="user-info__data-item-label">Комментарий<button class="btn edit-btn js-open-edit" type="button"><svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg></button></div>
                        <div class="user-info__data-item-value">
                            <div class="editable-value">
                                <div class="value">Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд.</div>
                                <form class="editable-value__frm js-editable-frm"><textarea class="editable-value__input">Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд. Отличный ученик. Всё гуд.</textarea><button class="editable-value__submit btn primary-btn primary-btn primary-btn--c6 sm-btn js-close-edit" type="submit">Сохранить</button><button class="editable-value__cancel btn secondary-btn secondary-btn secondary-btn--c7 sm-btn js-close-edit" type="button">Отмена</button></form>
                            </div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Назначенный методист</div>
                        <div class="user-info__data-item-value">
                            <div class="select-wrap select-wrap--vw"><select class="simple-select hidden-selected sm-select js-select" data-placeholder="Выбрать">
                                    <option></option>
                                    <option>Иван</option>
                                    <option>Пётр</option>
                                    <option>Наталья</option>
                                </select></div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оператор, назначивший вводный урок</div>
                        <div class="user-info__data-item-value"><a href="#" data-modal-id="operator-info-modal" data-save-opened="1" class="js-open-modal-user-info void-0">Пётр</a></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата вводного урока</div>
                        <div class="user-info__data-item-value">25/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата первой оплаты</div>
                        <div class="user-info__data-item-value">25/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата первого оплаченного урока</div>
                        <div class="user-info__data-item-value">25/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оплаченная сумма</div>
                        <div class="user-info__data-item-value">5 000 <span class="rouble">d</span></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Платёжная система</div>
                        <div class="user-info__data-item-value">Visa</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Осталось оплаченных уроков</div>
                        <div class="user-info__data-item-value">2</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Проведено уроков</div>
                        <div class="user-info__data-item-value">5</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Проведено уроков за последние 7 дней</div>
                        <div class="user-info__data-item-value">5</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата последнего урока</div>
                        <div class="user-info__data-item-value">25/05/2020</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">История смены учителей</div>
                        <div class="user-info__data-item-value"><b>Олег Петрович</b>, Наталья Ивановна, Павел Иванович</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Расписание<button class="btn edit-btn js-open-modal" type="button" data-modal-id="change-schedule-student-modal-3" data-save-opened="1"><svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg></button></div>
                        <div class="user-info__data-item-value"><a href="javascript:;" data-modal-id="change-schedule-student-modal-3" data-save-opened="1" class="js-open-modal">вторник, четверг в 10 утра по мск</a></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оценки за связь</div>
                        <div class="user-info__data-item-value"><span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">10 раз</span>, из них <span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">5 звезд - 9 раз</span>, <span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">4 звезды - 1	раз</span></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оценки преподавателям</div>
                        <div class="user-info__data-item-value"><span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">10 раз</span>, <span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">5 звезд - 9 раз</span>, <span title="21/05/2020 - Олег Петрович, 22/05/2020 - Иван Иванович, 21/05/2020 - Роман Игоревич">4 звезды 1 раз</span></div>
                    </div>
                </div>
            </div>
            <div class="add-new-person">
                <div class="add-new-person__top"><a class="new-object-link add-new-person__btn js-add-new-person" href="javascript:;"><span class="new-object-link__icon-wrap"><svg class="svg-icon--plus svg-icon" width="13" height="13"><use xlink:href="#plus"></use></svg></span><span class="new-object-link__text">Добавить учителя ученику</span></a>
                    <form class="add-new-person__search"><input class="add-new-person__search-input" type="search" placeholder="Поиск" /></form>
                </div>
                <div class="add-new-person__data">
                    <div class="users-slider-wrap slider-wrap">
                        <div class="users-slider js-users-slider js-slider">
                            <div class="users-slider__item js-open-coach-details js-open-modal">
                                <div class="user-info user-info--card">
                                    <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                        <div>
                                            <div class="user-info__name">Наталья</div>
                                        </div>
                                    </div>
                                    <div class="user-info__data">
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Возраст</div>
                                            <div class="user-info__data-item-value">25 лет</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Город</div>
                                            <div class="user-info__data-item-value">Сочи, Россия</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">id</div>
                                            <div class="user-info__data-item-value">13243</div>
                                        </div>
                                        <div class="user-info__data-item"><button class="add-btn btn" type="button"><svg class="svg-icon--plus svg-icon" width="16" height="16">
                                                    <use xlink:href="#plus"></use>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="users-slider__item js-open-modal js-open-coach-details">
                                <div class="user-info user-info--card">
                                    <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                        <div>
                                            <div class="user-info__name">Олег</div>
                                        </div>
                                    </div>
                                    <div class="user-info__data">
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Возраст</div>
                                            <div class="user-info__data-item-value">28 лет</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Город</div>
                                            <div class="user-info__data-item-value">Краснодар, Россия</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">id</div>
                                            <div class="user-info__data-item-value">345876</div>
                                        </div>
                                        <div class="user-info__data-item"><button class="add-btn btn" type="button"><svg class="svg-icon--plus svg-icon" width="16" height="16">
                                                    <use xlink:href="#plus"></use>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="users-slider__item js-open-modal js-open-coach-details">
                                <div class="user-info user-info--card">
                                    <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                        <div>
                                            <div class="user-info__name">Дмитрий</div>
                                        </div>
                                    </div>
                                    <div class="user-info__data">
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Возраст</div>
                                            <div class="user-info__data-item-value">35 лет</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Город</div>
                                            <div class="user-info__data-item-value">Москва, Россия</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">id</div>
                                            <div class="user-info__data-item-value">765233</div>
                                        </div>
                                        <div class="user-info__data-item"><button class="add-btn btn" type="button"><svg class="svg-icon--plus svg-icon" width="16" height="16">
                                                    <use xlink:href="#plus"></use>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="users-slider__item js-open-coach-details js-open-modal">
                                <div class="user-info user-info--card">
                                    <div class="user-info__user"><img class="user-info__ava" src="/assets/smartsing-min/files/profile/user-avatar.svg" alt="" role="presentation" />
                                        <div>
                                            <div class="user-info__name">Наталья</div>
                                        </div>
                                    </div>
                                    <div class="user-info__data">
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Возраст</div>
                                            <div class="user-info__data-item-value">25 лет</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">Город</div>
                                            <div class="user-info__data-item-value">Сочи, Россия</div>
                                        </div>
                                        <div class="user-info__data-item">
                                            <div class="user-info__data-item-label">id</div>
                                            <div class="user-info__data-item-value">254311</div>
                                        </div>
                                        <div class="user-info__data-item"><button class="add-btn btn" type="button"><svg class="svg-icon--plus svg-icon" width="16" height="16">
                                                    <use xlink:href="#plus"></use>
                                                </svg></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="users-slider-nav slider-nav slider-nav--centered"><button class="btn slider-nav__item slider-nav__item--prev nav-btn nav-btn--sm nav-btn--shadow" type="button"><svg class="svg-icon--left svg-icon" width="6" height="12">
                                    <use xlink:href="#left"></use>
                                </svg></button><button class="btn slider-nav__item slider-nav__item--next nav-btn nav-btn--sm nav-btn--shadow" type="button"><svg class="svg-icon--right svg-icon" width="6" height="12">
                                    <use xlink:href="#right"></use>
                                </svg></button></div>
                    </div>
                </div>
            </div>
        </div><button class="btn modal__close-btn js-close-modal" type="button"><svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg></button><button class="btn modal__back-btn js-close-modal" type="button"><svg class="svg-icon--left svg-icon" width="7" height="12">
                <use xlink:href="#left"></use>
            </svg>Назад</button>
    </div>
</div>
<!-- end MODAL -->