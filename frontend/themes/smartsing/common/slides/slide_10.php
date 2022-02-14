<?php

/** @var $CurrentUser \common\models\Users */

use common\helpers\Functions;
use common\models\Payments;

?>
<div class="present__text">
    <div class="present__title present__title--fz3">
        Занятия с
        <button type="button" class="btn inline-tabs-item --js-inline-tabs-item _current" data-box-id="box-1">опытным преподавателем</button>
        <!--
        или
        <button type="button" class="btn inline-tabs-item js-inline-tabs-item" data-box-id="box-2">преподавателем-экспертом</button>
        -->
    </div>
</div>
<div class="tabs-content tabs-content--scroll">
    <div class="box _visible" id="box-1">
        <div class="price-holder scroll-holder">
            <div class="price-block price-block--wide">
                <div class="price present__price">

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
    <div class="box" id="box-2-off" style="display: none;">
        <div class="price-holder scroll-holder">
            <div class="price-block price-block--wide">
                <div class="price present__price">
                    <div class="price__item">
                        <div class="price__top">
                            <div class="price__title"><span>4</span> урока</div>
                            <div class="price__discount">Скидка -5%</div>
                        </div>
                        <div class="price__price">
                            <div class="price__price-value">3 036<span class="rouble">b</span></div>
                            <div class="price__price-note">Цена за урок</div>
                        </div>
                        <div class="price__total">
                            <div class="price__old-price">12 784<span class="rouble">b</span></div><button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-open-modal" type="button" data-modal-id="payment-system-modal">12 144<span 		class="rouble">d</span></button>
                        </div>
                    </div>
                    <div class="price__item">
                        <div class="price__top">
                            <div class="price__title"><span>8</span> уроков</div>
                            <div class="price__discount">Скидка -15%</div>
                        </div>
                        <div class="price__price">
                            <div class="price__price-value">2 716<span class="rouble">b</span></div>
                            <div class="price__price-note">Цена за урок</div>
                        </div>
                        <div class="price__total">
                            <div class="price__old-price">25 568<span class="rouble">b</span></div><button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-open-modal" type="button" data-modal-id="payment-system-modal">21 732<span 		class="rouble">d</span></button>
                        </div>
                    </div>
                    <div class="price__item">
                        <div class="price__top">
                            <div class="price__title"><span>16</span> уроков</div>
                            <div class="price__discount">Скидка -25%</div>
                        </div>
                        <div class="price__price">
                            <div class="price__price-value">2 397<span class="rouble">b</span></div>
                            <div class="price__price-note">Цена за урок</div>
                        </div>
                        <div class="price__total">
                            <div class="price__old-price">51 136<span class="rouble">b</span></div><button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-open-modal" type="button" data-modal-id="payment-system-modal">38 352<span 		class="rouble">d</span></button>
                        </div>
                    </div>
                    <div class="price__item">
                        <div class="price__top">
                            <div class="price__title"><span>32</span> урока</div>
                            <div class="price__discount">Скидка -32%</div>
                        </div>
                        <div class="price__price">
                            <div class="price__price-value">2 173<span class="rouble">b</span></div>
                            <div class="price__price-note">Цена за урок</div>
                        </div>
                        <div class="price__total">
                            <div class="price__old-price">102 272<span class="rouble">b</span></div><button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-open-modal" type="button" data-modal-id="payment-system-modal">69 544<span 		class="rouble">d</span></button>
                        </div>
                    </div>
                    <div class="price__item">
                        <div class="price__top">
                            <div class="price__title"><span>64</span> урока</div>
                            <div class="price__discount">Скидка -39%</div>
                        </div>
                        <div class="price__price">
                            <div class="price__price-value">1 950<span class="rouble">b</span></div>
                            <div class="price__price-note">Цена за урок</div>
                        </div>
                        <div class="price__total">
                            <div class="price__old-price">204 590<span class="rouble">b</span></div><button class="price__total-price btn primary-btn primary-btn primary-btn--c3 js-open-modal" type="button" data-modal-id="payment-system-modal">124 800<span 		class="rouble">d</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
