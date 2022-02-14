<?php

/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;
use common\helpers\Functions;
use common\models\Payments;
use frontend\assets\smartsing\student\PaymentAsset;

$this->title = Html::encode('Оплата');

PaymentAsset::register($this);

?>
<div class="dashboard">

    <section class="section payment-variants">
        <div class="flex-header section__title">
            <h1 class="page-title">Оплата</h1>
            <!--
            <div class="flex-header__right-block">
                <div class="icon-block">
                    <div class="icon-block__icon-wrap">
                        <svg class="svg-icon--pig svg-icon" width="50" height="46">
                            <use xlink:href="#pig"></use>
                        </svg>
                    </div>
                    <div class="icon-block__text">
                        <div class="icon-block__label">Ваши бонусы</div>
                        <div class="icon-block__value">3 000 баллов</div>
                    </div>
                </div>
            </div>
            -->
        </div>
        <div class="price-win win win--grey tabs-wrap">
            <div class="win__top"></div>
            <div class="price-block__desc">
                <p>
                    Пожалуйста, выберите подходящий вам пакет уроков. <br />
                    <!--
                    Занятия с <button type="button" class="btn inline-tabs-item js-inline-tabs-item _current" data-box-id="box-1">опытным преподавателем</button>
                    или <button type="button" class="btn inline-tabs-item js-inline-tabs-item" data-box-id="box-2">преподавателем-экспертом</button>.
                    -->
                </p>
            </div>
            <div class="tabs-content">

                    <div class="price-holder scroll-holder">
                        <div class="price-block">
                            <div class="price present__price" id="price">
                                <?php
                                foreach (Payments::$_AVAILABLE_AMOUNTS as $k=>$v) {
                                    ?>
                                    <div class="price__item">
                                        <div class="price__top">
                                            <div class="price__title"><span><?= $k ?></span> урок<?= Functions::string_count_left_suffix($k) ?></div>
                                            <div class="price__discount">Скидка -<?= Payments::$_AVAILABLE_AMOUNTS[$k]['discount'] ?></div>
                                        </div>
                                        <div class="price__price">
                                            <div class="price__price-value"><?= number_format(Payments::$_AVAILABLE_AMOUNTS[$k]['rub_for_one'], 0, '', ' ') ?><span class="rouble">b</span></div>
                                            <div class="price__price-note">Цена за урок</div>
                                        </div>
                                        <div class="price__total">
                                            <div class="price__old-price"><?= number_format((Payments::$_AVAILABLE_AMOUNTS[$k]['rub_no_discount']), 0, '', ' ') ?><span class="rouble">b</span></div>
                                            <button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-start-tinkoff-payment -js-open-modal"
                                                    type="button"
                                                    data-lessons-count="<?= $k ?>"
                                                    data-amount="<?= Payments::$_AVAILABLE_AMOUNTS[$k]['rub'] ?>"
                                                    data-description="<?= $k ?> урока SmartSing"
                                                    data-modal-id-off="payment-system-modal"><?= number_format(Payments::$_AVAILABLE_AMOUNTS[$k]['rub'], 0, '', ' ') ?><span class="rouble">d</span></button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                            <!-- begin tinkoff form -->
                            <div style="display: none;">
                                <form name="TinkoffPayForm" id="tinkoff-payment-form" onsubmit="pay(this); return false;">
                                    <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="<?= Yii::$app->params['tinkoff_terminal_key'] ?>" />
                                    <input class="tinkoffPayRow" type="hidden" name="frame" value="true" />
                                    <input class="tinkoffPayRow" type="hidden" name="language" value="ru" />
                                    <input class="tinkoffPayRow"
                                           id="tinkoff-order_amount"
                                           type="text"
                                           placeholder="Сумма заказа"
                                           name="amount"
                                           value="{amount}"
                                           required />
                                    <input class="tinkoffPayRow"
                                           type="text"
                                           id="tinkoff-order_id"
                                           placeholder="Номер заказа"
                                           name="order"
                                           value="{order-id}" />
                                    <input class="tinkoffPayRow"
                                           type="text"
                                           id="tinkoff-order_description"
                                           placeholder="Описание заказа"
                                           name="description"
                                           value="{description}" />
                                    <input class="tinkoffPayRow"
                                           type="text"
                                           placeholder="ФИО плательщика"
                                           name="name"
                                           value="<?= $CurrentUser->user_full_name ?>" />
                                    <input class="tinkoffPayRow"
                                           type="text"
                                           placeholder="E-mail"
                                           name="email"
                                           value="<?= $CurrentUser->user_email ?>" />
                                    <input class="tinkoffPayRow"
                                           type="text"
                                           placeholder="Контактный телефон"
                                           name="phone"
                                           value="<?= $CurrentUser->user_phone ?>" />
                                    <input class="tinkoffPayRow" type="submit" value="Оплатить" />
                                </form>
                            </div>
                            <!-- end tinkoff form -->

                        </div>
                    </div>

            </div>
        </div>
    </section>

    <section class="section" style="display: none;">
        <h2 class="section__title">История занятий</h2>
        <div class="list-table history">
            <div class="list-table__header">
                <div class="list-table__header-cell">Дата</div>
                <div class="list-table__header-cell">Учитель</div>
                <div class="list-table__header-cell">Статус</div>
                <div class="list-table__header-cell">Баланс уроков</div>
            </div>
            <div class="list-table__body">
                <div class="list-table__item history__item">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-1_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Олег Петрович</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--tick svg-icon" width="22" height="18">
                            <use xlink:href="#tick"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--cancel">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-2_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Давид Голиафыч</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок не состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--failure">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-3_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Надежда Памфилова</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Ученик отсутствовал <br>на уроке</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-1_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Олег Петрович</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--tick svg-icon" width="22" height="18">
                            <use xlink:href="#tick"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--cancel">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-2_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Олег Петрович</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок не состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--failure">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-3_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Надежда Памфилова</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Ученик отсутствовал <br>на уроке</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-1_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Олег Петрович</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--tick svg-icon" width="22" height="18">
                            <use xlink:href="#tick"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--cancel">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-2_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Олег Петрович</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Урок не состоялся</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
                <div class="list-table__item history__item history__item--failure">
                    <div class="list-table__cell history__cell history__cell--date"><svg class="svg-icon--clock svg-icon" width="22" height="22">
                            <use xlink:href="#clock"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Дата</div><time datetime="2014-04-22">22/04/2014 11:00</time>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell">
                        <div class="user-item"><img class="user-item__ava" src="/assets/smartsing-min/files/profile/ava-3_40x40.jpg" alt="" role="presentation" />
                            <div class="user-item__text">
                                <div class="hidden-label">Учитель</div>
                                <div class="user-item__name">Надежда Памфилова</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell history__cell--state"><svg class="svg-icon--close svg-icon" width="19" height="19">
                            <use xlink:href="#close"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Статус</div><span>Ученик отсутствовал <br>на уроке</span>
                        </div>
                    </div>
                    <div class="list-table__cell history__cell"><svg class="svg-icon--microphone-red svg-icon" width="22" height="22">
                            <use xlink:href="#microphone-red"></use>
                        </svg>
                        <div class="list-table__text">
                            <div class="hidden-label">Баланс уроков</div>
                            <div><span class="text-light">Осталось уроков:</span> <span>10</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pages list-table__pages">
                <div class="pages__item _current">1</div><a class="pages__item" href="javascript:;">2</a><a class="pages__item" href="javascript:;">3</a>
                <div class="pages__total">стр.1/3</div>
            </div>
        </div>
    </section>
</div>