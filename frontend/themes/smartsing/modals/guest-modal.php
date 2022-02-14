<?php

/** @var $this yii\web\View */
/** @var $form yii\bootstrap\ActiveForm */
/** @var $LoginFormModel \frontend\models\forms\LoginForm */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$LoginFormModel = $this->context->model_login
//var_dump($this->context->model_login); exit;
?>

<!-- begin MODAL -->
<div class="modal" id="auth-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <div class="modal__title">Вход</div>
            <?php
            $form = ActiveForm::begin([
                'id' => "form-login",
                'action'  => Url::to(['/site/login'], CREATE_ABSOLUTE_URL),
                'options' => [
                    //'onsubmit'   => "return false",
                    //'onsubmit'   => "return onSubmitLogin()",
                    'class'      => "auth-frm",
                    //'novalidate' => "novalidate",
                ],
            ]);
            ?>
                <div class="form-row">
                    <div class="form-col form-col--wide">
                        <?=
                        $form->field($LoginFormModel, 'user_email')
                            ->textInput([
                                'type'         => "email",
                                'placeholder'  => 'Электронная почта', //$LoginFormModel->getAttributeLabel('user_email'),
                                'autocomplete' => "off",
                                'aria-label'   => 'Электронная почта', //$LoginFormModel->getAttributeLabel('user_email'),
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <div class="form-col form-col--wide">
                        <div class="input-wrap">
                            <?=
                            $form->field($LoginFormModel, 'password'/*, ['enableAjaxValidation' => false]*/)
                                ->passwordInput([
                                    'placeholder'  => 'Пароль', //$LoginFormModel->getAttributeLabel('password'),
                                    'autocomplete' => "off",
                                    'class' => "password-input",
                                    'aria-label'   => 'Пароль', //$LoginFormModel->getAttributeLabel('password'),
                                ])
                                ->label(false)
                            ?>
                            <button class="btn show-pass-btn" type="button" title="Показать пароль">
                                <svg class="svg-icon--no-eye svg-icon" width="25" height="25">
                                    <use xlink:href="#no-eye"></use>
                                </svg>
                                <svg class="svg-icon--eye svg-icon" width="25" height="20">
                                    <use xlink:href="#eye"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-col form-col--wide"><a class="forgot-pass-link js-open-modal" href="javascript:;" data-modal-id="forgot-pass-modal">Забыли пароль?</a></div>
                </div>
                <div class="form-footer"><button class="btn primary-btn primary-btn--c6 modal__submit-btn" type="submit">Войти</button></div>
            <?php
            ActiveForm::end();
            ?>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button"><svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

<!-- begin MODAL -->
<div class="modal" id="forgot-pass-modal">
    <div class="modal__content">
        <div class="modal__inner">
            <div class="modal__title">Сброс пароля</div>
            <p>Введите e-mail, указанный при регистрации, и вы получите на него ссылку для сброса пароля</p>
            <form class="reset-pass-frm">
                <div class="form-row">
                    <div class="form-col form-col--wide">
                        <input type="email" placeholder="Электронная почта" name="reset-email">
                    </div>
                </div>
                <div class="form-footer">
                    <button class="btn primary-btn primary-btn--c6 modal__submit-btn" type="submit">Сбросить пароль</button>
                </div>
            </form>
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
<div class="modal" id="request-form-sent-modal">
    <a href="#" id="request-form-sent-link" class="void-0 js-open-modal hidden" data-modal-id="request-form-sent-modal" style="display: none"></a>
    <div class="modal__content">
        <div class="modal__inner">
            <div class="modal__title">Успешная отправка</div>
            <p>Ваша заявка успешно отправлена.<br /> В ближайшее время с вами свяжется наш оператор.</p>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->

