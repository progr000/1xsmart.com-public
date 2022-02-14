<?php

/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;
use common\helpers\Functions;
use common\models\Users;
use common\models\Payments;
use frontend\assets\smartsing\student\PaymentAsset;

$this->title = Html::encode('Время сделать шаг');

PaymentAsset::register($this);

?>
<style>.tinkoffPayRow{display:block; margin:5px; width:200px !important;}</style>
<!--include header-after-->
<div class="after-top gradient-5">
    <header class="page-header js-page-header">
        <div class="page-header__inner"><button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
        </div>
    </header>
    <div class="trial container"><img class="trial__img" src="/assets/smartsing-min/images/melody.png" alt="" role="presentation" />
        <div class="trial__title">Время сделать шаг</div>
        <div class="trial__desc"><strong><?= $CurrentUser->user_first_name ?></strong>, на вводном занятии ваш текущий уровень был определен как <span class="highlight-text"><strong>"<?= Users::$_general_levels[$CurrentUser->user_level_general] ?>"</strong></span></div>
    </div>
</div>
<section class="page-section">
    <div class="container">
        <div class="stages">
            <div class="stages__item">
                <div class="stage-card">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/arrows.png" alt=""></div>
                        <div class="stage-card__title">Диапазон</div>
                        <div class="stage-card__desc"><?= $CurrentUser->user_level_range_notice ?></div>
                        <div class="stage-card__progress progress-bar js-animate js-progress-bar" data-total="<?=Users::level_range_max ?>" data-progress="<?= $CurrentUser->user_level_range ?>"></div>
                    </div>
                </div>
            </div>
            <div class="stages__item">
                <div class="stage-card">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/google-voice.png" alt=""></div>
                        <div class="stage-card__title">Координация<br>слуха и голоса</div>
                        <div class="stage-card__desc"><?= $CurrentUser->user_level_coordination_notice ?></div>
                        <div class="stage-card__progress progress-bar js-animate js-progress-bar" data-total="<?=Users::level_coordination_max ?>" data-progress="<?= $CurrentUser->user_level_coordination ?>"></div>
                    </div>
                </div>
            </div>
            <div class="stages__item">
                <div class="stage-card">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/pillow.png" alt=""></div>
                        <div class="stage-card__title">Тембр голоса, чистота и бархатистость</div>
                        <div class="stage-card__desc"><?= $CurrentUser->user_level_timbre_notice ?></div>
                        <div class="stage-card__progress progress-bar js-animate js-progress-bar" data-total="<?=Users::level_timbre_max ?>" data-progress="<?= $CurrentUser->user_level_timbre ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title">Получите новые навыки</h2>
        <div class="price-holder scroll-holder">
            <div class="price-block price-block--wide price-block--page win win--grey">
                <div class="win__top"></div>
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
</section>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title"><span class="highlight-c1">Поможем</span> усовершенствовать каждый из навыков</h2>
        <div class="stages-scheme">
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/arrows.png" alt=""></div>
                        <div class="stage-card__title">Диапазон</div>
                        <div class="stage-card__desc">Можно раширить диапазон владения голосом как минимум на большую терцию в обе стороны. Это физиология. Существуют специальные дыхательные и вокальные упражнения, позволяющие расширить именно рабочий, а не номинальный диапазон, добавить и укрепить фальцет для верхнего регистра.</div>
                    </div>
                </div>
                <div class="stages-scheme__num num">01</div>
                <div class="path-1"></div>
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/google-voice.png" alt=""></div>
                        <div class="stage-card__title">Координация<br>слуха и голоса</div>
                        <div class="stage-card__desc">В процессе пения перед мозгом стоит задача синхронизировать слух и мышцы голосовых связок для синхронной работы. В нашей интерактивной платформе на распевках вы дополнительно сможете увидеть визуально точность попадания вашего голоса в ноты. Тем самым процесс обучения будет проходить более эффективно.</div>
                    </div>
                </div>
                <div class="stages-scheme__num num">02</div>
                <div class="path-2"></div>
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><img src="/assets/smartsing-min/files/stages/pillow.png" alt=""></div>
                        <div class="stage-card__title">Тембр голоса, чистота и бархатистость</div>
                        <div class="stage-card__desc">Тембр голоса дается нам индивидуально от природы и его не изменить. Однако певучесть голоса, а также такие характеристики такие как чистота, бархатистость и цепкость можно развить до отличных показателей у любого человека. Прокачивайте эти навыки на распеваках, при выполнении домашних заданий и конечно же при разборе Ваших любимых песен!</div>
                    </div>
                </div>
                <div class="stages-scheme__num num">03</div>
                <div class="path-3"></div>
            </div>
            <div class="stages-scheme__item stages-scheme__item--end js-animate">
                <picture>
                    <source srcset="/assets/smartsing-min/images/logo_w220@2x.png 2x, /assets/smartsing-min/images/logo_w220.png 1x" media="(min-width: 769px) and (max-width: 1600px)">
                    <source srcset="/assets/smartsing-min/images/logo-lg.png"><img srcset="/assets/smartsing-min/images/logo-lg.png" alt=""></picture>
            </div>
        </div>
    </div>
</section>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title">Получите новые навыки</h2>
        <div class="price-win win win--grey">
            <div class="win__top"></div>
            <div class="price-holder scroll-holder">
                <div class="price-block">
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
                </div>
            </div>
        </div>
    </div>
</section>
<div class="call">
    <div class="call__inner">
        <div class="call__title">Ответим на любой вопрос</div>
        <a class="call__phone" href="tel:<?= str_replace(['(', ')', ' ', '-'], '', Yii::$app->params['contact_phone']) ?>">
            <svg class="svg-icon--phone svg-icon" width="40" height="40">
                <use xlink:href="#phone"></use>
            </svg><?= Yii::$app->params['contact_phone'] ?>
        </a>
        <img class="call__img" src="/assets/smartsing-min/images/headphones.png" alt="" role="presentation" />
    </div>
</div>


<?= $this->render("../modals/student-modals/payment-system-modal") ?>