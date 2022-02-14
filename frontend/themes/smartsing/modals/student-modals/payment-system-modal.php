<?php

/** @var $this yii\web\View */

?>
<div class="modal" id="payment-system-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <div class="modal__title">Выберите платежную систему</div>
            <form>
                <div class="form-row">
                    <div class="input-wrap input-wrap--single">
                        <div class="select-wrap">
                            <select class="icon-select js-icon-select">
                                <option value="1" data-icon="/assets/smartsing-min/images/payments/paypal.svg" selected>PayPal</option>
                                <option value="2" data-icon="/assets/smartsing-min/images/payments/pig.svg">Бонусные баллы (3 000)</option>
                                <option value="3" data-icon="/assets/smartsing-min/images/payments/yandex.svg">Яндекс.Деньги</option>
                            </select>
                        </div>
                    </div>
                </div>
                <p>Сейчас вы покупаете <strong>пакет из 4 уроков на сумму 1 370 рублей.</strong> Нажмите Оплатить и Вы будете перенаправлены на страницу оплаты.</p>
                <div class="form-footer"><button class="btn primary-btn primary-btn--c6 modal__submit-btn" type="submit">Оплатить</button></div>
            </form>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
