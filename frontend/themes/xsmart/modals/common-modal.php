<?php

/** @var $this yii\web\View */
/** @var $ContactForm \frontend\models\forms\ContactForm */

use yii\bootstrap\ActiveForm;
use frontend\models\forms\ContactForm;

$FormFill = new ContactForm(['request_name', 'request_phone', 'request_text']);
$ContactForm = new ContactForm(['request_name', 'request_email', 'request_text']); //$this->context->model_contact;
?>

<!-- begin faq-popup MODAL -->
<div class="modal modal--light modal modal--ultrawide" id="faq-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/faq', "Frequently_Asked_Questions", ['APP_NAME' => Yii::$app->name]) ?></div>
            <div class="tabs-wrap modal-faq">
                <ul class="tabs js-tabs">
                    <li class="tabs__item js-tabs-item _current"><svg class="svg-icon-question svg-icon" width="24" height="24">
                            <use xlink:href="#question"></use>
                        </svg><?= Yii::t('modals/faq', "General_Questions_Title", ['APP_NAME' => Yii::$app->name]) ?></li>
                    <li class="tabs__item js-tabs-item"><svg class="svg-icon-invoice svg-icon" width="24" height="22">
                            <use xlink:href="#invoice"></use>
                        </svg><?= Yii::t('modals/faq', "Billing_Title", ['APP_NAME' => Yii::$app->name]) ?></li>
                    <li class="tabs__item js-tabs-item"><svg class="svg-icon-clicker svg-icon" width="24" height="23">
                            <use xlink:href="#clicker"></use>
                        </svg><?= Yii::t('modals/faq', "Teacher_questions_Title", ['APP_NAME' => Yii::$app->name]) ?></li>
                </ul>
                <div class="tabs-content">
                    <div class="box _visible">
                        <?= Yii::t('modals/faq', "General_Questions_QA", ['APP_NAME' => Yii::$app->name]) ?>
                    </div>
                    <div class="box">
                        <?= Yii::t('modals/faq', "Billing_QA", ['APP_NAME' => Yii::$app->name]) ?>
                    </div>
                    <div class="box">
                        <?= Yii::t('modals/faq', "Teacher_questions_QA", ['APP_NAME' => Yii::$app->name]) ?>
                    </div>
                </div>
            </div>
        </div><button class="modal__close-btn js-close-modal" type="button"><svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg></button>
    </div>
</div>
<!-- end faq-popup MODAL -->

<!-- begin form-fill-popup MODAL -->
<div class="modal" id="form-fill-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/contact', "Contact_Us") ?></div>
            <?php $form = ActiveForm::begin([
                'id' => "form-fill-form",
                'action' => null,
                'options' => [
                    'onsubmit'   => "return false",
                    'class'    => "modal__form",
                ],
                'enableClientValidation' => true,
            ]); ?>

            <?=
            $form->field($FormFill, 'request_name', [
                'template' => '
                            <div class="input-wrap">
                                {input}
                                <label class="icon-label" for="contact-name">
                                    <svg class="svg-icon-user svg-icon" width="20" height="22">
                                        <use xlink:href="#user"></use>
                                    </svg>
                                </label>
                                {error}{hint}
                            </div>
                        '
            ])->textInput([
                'id'           => "form-fill-name",
                'type'         => "text",
                'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                'placeholder'  => Yii::t('modals/contact', "Name"),
                'autocomplete' => "off",
                'aria-label'   => Yii::t('modals/contact', "Name"),
            ])->label(false)
            ?>

            <?=
            $form->field($FormFill, 'request_phone', [
                'template' => '
                                <div class="input-wrap">
                                    {input}
                                    <label class="icon-label" for="contact-email">
                                        <svg class="svg-icon-phone svg-icon" width="20" height="15">
                                            <use xlink:href="#mail"></use>
                                        </svg>
                                    </label>
                                    {error}{hint}
                                </div>
                            '
            ])->textInput([
                'id'           => "form-fill-phone",
                'type'         => "tel",
                'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                'placeholder'  => Yii::t('modals/contact', "Phone"),
                'autocomplete' => "off",
                'aria-label'   => Yii::t('modals/contact', "Phone"),
            ])->label(false)
            ?>

            <?=
            $form->field($FormFill, 'request_text', [
                'template' => '
                                    <div class="input-wrap">
                                        {input}
                                        {error}{hint}
                                    </div>
                                '
            ])->textarea([
                'id'           => "form-fill-text",
                'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                'placeholder'  => Yii::t('modals/contact', "Message"),
                'autocomplete' => "off",
                'aria-label'   => Yii::t('modals/contact', "Message"),
            ])->label(false)
            ?>

            <!--<img src="/assets/xsmart-min/images/captcha.jpg.html" alt="">-->
            <div class="modal__submit">
                <button
                    class="accent-btn wide-mob-btn js-send-contact-us-request"
                    data-form-id="form-fill-form"
                    data-ready-to-send="<?= Yii::t('modals/contact', "Send_message") ?>"
                    data-sent-in-progress="<?= Yii::t('modals/contact', "Sending") ?>"
                    type="submit"><?= Yii::t('modals/contact', "Send_message") ?></button>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end form-fill-popup MODAL -->

<!-- begin contact-popup MODAL -->
<div class="modal" id="contact-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/contact', "Contact_Us") ?></div>
            <?php $form = ActiveForm::begin([
                'id' => "contact-us-form",
                'action' => null,
                'options' => [
                    'onsubmit'   => "return false",
                    'class'    => "modal__form",
                ],
                'enableClientValidation' => true,
            ]); ?>

                <?=
                $form->field($ContactForm, 'request_name', [
                    'template' => '
                            <div class="input-wrap">
                                {input}
                                <label class="icon-label" for="contact-name">
                                    <svg class="svg-icon-user svg-icon" width="20" height="22">
                                        <use xlink:href="#user"></use>
                                    </svg>
                                </label>
                                {error}{hint}
                            </div>
                        '
                ])->textInput([
                    'id'           => "contact-name",
                    'type'         => "text",
                    'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                    'placeholder'  => Yii::t('modals/contact', "Name"),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/contact', "Name"),
                ])->label(false)
                ?>

                <?=
                $form->field($ContactForm, 'request_email', [
                    'template' => '
                                <div class="input-wrap">
                                    {input}
                                    <label class="icon-label" for="contact-email">
                                        <svg class="svg-icon-phone svg-icon" width="20" height="15">
                                            <use xlink:href="#mail"></use>
                                        </svg>
                                    </label>
                                    {error}{hint}
                                </div>
                            '
                ])->textInput([
                    'id'           => "contact-email",
                    'type'         => "tel",
                    'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                    'placeholder'  => Yii::t('modals/contact', "Email"),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/contact', "Email"),
                ])->label(false)
                ?>

                <?=
                $form->field($ContactForm, 'request_text', [
                    'template' => '
                                    <div class="input-wrap">
                                        {input}
                                        {error}{hint}
                                    </div>
                                '
                ])->textarea([
                    'id'           => "contact-text",
                    'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                    'placeholder'  => Yii::t('modals/contact', "Message"),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/contact', "Message"),
                ])->label(false)
                ?>

                <!--<img src="/assets/xsmart-min/images/captcha.jpg.html" alt="">-->
                <div class="modal__submit">
                    <button
                        class="accent-btn wide-mob-btn js-send-contact-us-request"
                        data-form-id="contact-us-form"
                        data-ready-to-send="<?= Yii::t('modals/contact', "Send_message") ?>"
                        data-sent-in-progress="<?= Yii::t('modals/contact', "Sending") ?>"
                        type="submit"><?= Yii::t('modals/contact', "Send_message") ?></button>
                </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end contact-popup MODAL -->

<!-- begin MODAL -->
<div class="modal" id="pretty-alert-modal">
    <div class="modal__content alert-content">
        <div class="modal__inner pretty-alert">
            <p id="pretty-alert-modal-text">{$ALERT_TEXT}</p>
            <form>
                <div class="form-footer">
                    <button id="pretty-alert-button-ok"
                            class="btn primary-btn primary-btn--c6 modal__submit-btn js-close-modal"
                            type="button"><?= Yii::t('modals/pretty-alert', "Ok") ?></button>
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
    <div class="modal__content alert-content">
        <div class="modal__inner pretty-confirm">
            <p id="pretty-confirm-question-text"><?= Yii::t('modals/pretty-alert', "are_you_sure") ?></p>
            <form>
                <div class="form-footer">
                    <button id="pretty-confirm-button-yes"
                            class="button-confirm-yes btn primary-btn primary-btn--c6 modal__submit-btn js-close-modal"
                            type="button"><?= Yii::t('modals/pretty-alert', "Yes") ?></button>
                    &nbsp;
                    <button id="pretty-confirm-button-no"
                            class="button-confirm-no btn primary-btn primary-btn--c1 modal__submit-btn js-close-modal"
                            type="button"><?= Yii::t('modals/pretty-alert', "No") ?></button>
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

<!-- begin MODAL -->
<div class="cookie-info opened" id="cookie-policies-layer" style="display: none;">
    <div class="container">
        <!--
        <svg class="icon icon-info">
            <use xlink:href="/#info"></use>
        </svg>
        -->
        <div class="cookie-info__text">
            <p><?= Yii::t('static/cookie-polices', 'popup', ['APP_NAME' => Yii::$app->name]) ?></p>
        </div>
        <button class="cookie-info__close-btn btn primary-btn sm-btn cookie-layer__button" type="button">OK</button>
    </div>
</div>
<!-- end MODAL -->