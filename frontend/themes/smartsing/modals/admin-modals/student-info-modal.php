<?php

/** @var $this yii\web\View */

use frontend\assets\smartsing\admin\UserInfoAsset;

UserInfoAsset::register($this);

?>

<a href="#"
   class="js-open-modal"
   id="trigger-student-info-modal"
   data-modal-id="student-info-modal"
   data-save-opened="1"
   style="display: none;">student-info-modal</a>
<!-- begin MODAL -->
<div class="modal" id="student-info-modal">
    <div class="modal__content select-wrap js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Подробная информация об ученике</div>
            <div class="user-info user-info--portrait">
                <div class="user-info__user">
                    <img class="user-info__ava src-user_photo"
                         src="/assets/smartsing-min/files/profile/user-avatar.svg"
                         alt=""
                         role="presentation" />
                    <div class="user-info__main">
                        <div class="user-info__name user_full_name">{user_full_name}</div>
                        <div class="user-info__sex user_gender">{user_gender}</div>
                    </div>
                </div>
                <div class="user-info__data user-info__data--2col">
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">ID</div>
                        <div class="user-info__data-item-value user_id">{user_id}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Сервисная информация:</div>
                        <div class="user-info__data-item-value additional_service_info">{additional_service_info}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Телефон</div>
                        <div class="user-info__data-item-value user_phone">{user_phone}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата регистрации</div>
                        <div class="user-info__data-item-value user_created">{user_created}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Скайп</div>
                        <div class="user-info__data-item-value _user_skype">{_user_skype}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Электронная почта</div>
                        <div class="user-info__data-item-value user_email">{user_email}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Возраст</div>
                        <div class="user-info__data-item-value user_age">{user_age}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Любымые жанры музыки:</div>
                        <div class="user-info__data-item-value">Рок, популярная музыка</div>
                    </div>
                    <div class="user-info__data-item editable-wrap">
                        <div class="user-info__data-item-label">
                            Ваш комментарий
                            <button class="btn edit-btn js-open-edit" type="button">
                                <svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="user-info__data-item-value">
                            <div class="editable-value">
                                <div class="value owner_user_notice">{owner_user_notice}</div>
                                <form class="editable-value__frm js-editable-frm"
                                      onsubmit="return false;">
                                    <input type="hidden" name="user_id" value="{user_id}" class="value-user_id">
                                    <textarea class="editable-value__input value-owner_user_notice">{owner_user_notice}</textarea>
                                    <button class="editable-value__submit btn primary-btn primary-btn primary-btn--c6 sm-btn js-close-edit" type="submit">Сохранить</button>
                                    <button class="editable-value__cancel btn secondary-btn secondary-btn secondary-btn--c7 sm-btn js-close-edit" type="button">Отмена</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Назначенный методист</div>
                        <div class="user-info__data-item-value">
                            <a href="#"
                               data-modal-id="methodist-info-modal"
                               data-save-opened="1"
                               data-user-id="{methodist_user_id}"
                               class="js-open-modal-user-info void-0 user_methodist">{user_methodist}</a>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оператор, назначивший вводный урок</div>
                        <div class="user-info__data-item-value">
                            <a href="#"
                               data-modal-id="operator-info-modal"
                               data-save-opened="1"
                               data-user-id="{operator_user_id}"
                               class="js-open-modal-user-info void-0 user_operator">{user_operator}</a>
                        </div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата вводного урока</div>
                        <div class="user-info__data-item-value introduce_lesson_date">{introduce_lesson_date}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата последней оплаты</div>
                        <div class="user-info__data-item-value user_last_pay">{user_last_pay}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Оплаченная сумма</div>
                        <div class="user-info__data-item-value"><span class="user_balance">{user_balance}</span> <span class="rouble">d</span></div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Осталось оплаченных уроков</div>
                        <div class="user-info__data-item-value user_lessons_available">{user_lessons_available}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Проведено уроков</div>
                        <div class="user-info__data-item-value user_lessons_completed">{user_lessons_completed}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Дата последнего урока</div>
                        <div class="user-info__data-item-value">{user_last_lesson}</div>
                    </div>
                    <div class="user-info__data-item">
                        <div class="user-info__data-item-label">Расписание<button class="btn edit-btn js-open-modal" type="button" data-modal-id="change-schedule-student-modal-3" data-save-opened="1"><svg class="svg-icon--edit svg-icon" width="14" height="14">
                                    <use xlink:href="#edit"></use>
                                </svg></button></div>
                        <div class="user-info__data-item-value"><a href="javascript:;" data-modal-id="change-schedule-student-modal-3" data-save-opened="1" class="js-open-modal">вторник, четверг в 10 утра по мск</a></div>
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
