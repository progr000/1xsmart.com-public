<?php

/** @var $this yii\web\View */
/** @var $form yii\bootstrap\ActiveForm */
/** @var $LoginFormModel \frontend\models\forms\LoginForm */
/** @var $SignupFormModel \frontend\models\forms\SignupForm */
/** @var $PasswordResetRequest \frontend\models\forms\PasswordResetRequestForm */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;
use common\models\Users;

$LoginFormModel = $this->context->model_login;
$SignupFormModel = $this->context->model_signup;
$PasswordResetRequest = $this->context->model_password_reset_request;

function socialAuthLinks()
{
    $social_key_text = [
        'google'   => '<svg class="svg-icon-google svg-icon" width="22" height="22"><use xlink:href="#google"></use></svg>',
        'facebook' => '<svg class="svg-icon-fb svg-icon" width="11" height="22"><use xlink:href="#fb"></use></svg>',
    ];
    $authAuthChoice = AuthChoice::begin([
        'baseAuthUrl' => ['site/auth'],
        'popupMode' => true,
        'options' => ['class' => 'social-auth__links'],
    ]);
    foreach ($authAuthChoice->getClients() as $key=>$client) {
        if (isset($social_key_text[$key])) { $text = $social_key_text[$key]; } else { $text = 'Unknown'; }
        echo $authAuthChoice->clientLink(
            $client,
            $text,
            ['class' => "social-auth__link -auth-icon {$key}"]
        );
        //echo $authAuthChoice->createClientUrl($client);
    }
    AuthChoice::end();
}
?>

<!-- begin login-popup MODAL -->
<div class="modal" id="login-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/login', 'Log_in') ?></div>

            <?php
            $form = ActiveForm::begin([
                'id' => "form-login",
                'action'  => Url::to(['/login'], CREATE_ABSOLUTE_URL),
                'options' => [
                    'class' => "modal__form",
                ],
            ]);
            ?>

                <?=
                $form->field($LoginFormModel, 'user_email', [
                    'template' => '
                        <div class="input-wrap">
                            {input}
                            <label class="icon-label" for="login-email">
                                <svg class="svg-icon-mail svg-icon" width="20" height="15">
                                    <use xlink:href="#mail"></use>
                                </svg>
                            </label>
                            {error}{hint}
                        </div>
                    '
                ])->textInput([
                        'id'           => "login-email",
                        'type'         => "email",
                        'class'        => "icon-input",
                        'placeholder'  => Yii::t('modals/login', 'Email'),
                        'autocomplete' => "off",
                        'aria-label'   => Yii::t('modals/login', 'Email'),
                ])->label(false)
                ?>

                <?=
                $form->field($LoginFormModel, 'password', [
                    'template' => '
                            <div class="input-wrap">
                                {input}
                                <label class="icon-label" for="login-password">
                                    <svg class="svg-icon-key svg-icon" width="20" height="20">
                                        <use xlink:href="#key"></use>
                                    </svg>
                                </label>
                                {error}{hint}
                            </div>
                        '
                ])->textInput([
                    'id'           => "login-password",
                    'type'         => "password",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/login', 'Password'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/login', 'Password'),
                ])->label(false)
                ?>

                <p><?= Yii::t('modals/login', 'Forgot_password') ?></p>
                <div class="modal__submit">
                    <button class="accent-btn wide-mob-btn" type="submit"><?= Yii::t('modals/login', 'Login_to_account') ?></button>
                    <div class="social-auth">
                        <div class="social-auth__title"><?= Yii::t('modals/login', 'through_social') ?></div>
                        <?php
                        socialAuthLinks()
                        ?>
                    </div>
                </div>
            <?php
            ActiveForm::end();
            ?>

        </div>
        <div class="modal__footer"><?= Yii::t('modals/login', 'No_account') ?>
            <a href="#" data-modal-id="signup-popup" class="js-open-modal void-0"><?= Yii::t('modals/login', 'Register') ?></a>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end login-popup MODAL -->

<!-- begin signup-popup MODAL -->
<div class="modal" id="signup-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/signup', 'Registration') ?></div>
            <?php
            $form = ActiveForm::begin([
                'id' => "form-signup",
                'action'  => Url::to(['/signup'], CREATE_ABSOLUTE_URL),
                'options' => [
                    'class'      => "modal__form",
                ],
            ]);
            ?>

                <div class="text-radio-row">
                    <div class="check-wrap text-radio-wrap">
                        <input type="radio" name="SignupForm[user_type]" value="<?= Users::TYPE_STUDENT ?>" checked id="role-student">
                        <label for="role-student" class="signup-as-student"><?= Yii::t('modals/signup', 'Sign_up_as_student') ?></label>
                    </div>
                    <div class="check-wrap text-radio-wrap">
                        <input type="radio" name="SignupForm[user_type]" value="<?= Users::TYPE_TEACHER ?>" id="role-tutor">
                        <label for="role-tutor" class="signup-as-tutor"><?= Yii::t('modals/signup', 'Sign_up_as_tutor') ?></label>
                    </div>
                </div>

                <?=
                $form->field($SignupFormModel, 'username', [
                    'template' => '
                            <div class="input-wrap">
                                {input}
                                <label class="icon-label" for="signup-name">
                                    <svg class="svg-icon-user svg-icon" width="17" height="20">
                                        <use xlink:href="#user"></use>
                                    </svg>
                                </label>
                                {error}{hint}
                            </div>
                        '
                ])->textInput([
                    'id'           => "signup-name",
                    'type'         => "text",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/signup', 'Name'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/signup', 'Name'),
                ])->label(false)
                ?>

                <?=
                $form->field($SignupFormModel, 'email', [
                    'enableAjaxValidation' => true,
                    'template' => '
                                <div class="input-wrap">
                                    {input}
                                    <label class="icon-label" for="signup-email">
                                        <svg class="svg-icon-mail svg-icon" width="20" height="15">
                                            <use xlink:href="#mail"></use>
                                        </svg>
                                    </label>
                                    {error}{hint}
                                </div>
                            '
                ])->textInput([
                    'id'           => "signup-email",
                    'type'         => "email",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/signup', 'Email'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/signup', 'Email'),
                ])->label(false)
                ?>

                <?=
                $form->field($SignupFormModel, 'password', [
                    'template' => '
                                <div class="input-wrap">
                                    {input}
                                    <label class="icon-label" for="signup-password">
                                        <svg class="svg-icon-key svg-icon" width="20" height="20">
                                            <use xlink:href="#key"></use>
                                        </svg>
                                    </label>
                                    {error}{hint}
                                </div>
                            '
                ])->textInput([
                    'id'           => "signup-password",
                    'type'         => "password",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/signup', 'Password'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/signup', 'Password'),
                ])->label(false)
                ?>

                <?=
                $form->field($SignupFormModel, 'password_repeat', [
                    'template' => '
                                    <div class="input-wrap">
                                        {input}
                                        <label class="icon-label" for="signup-password-confirm">
                                            <svg class="svg-icon-key svg-icon" width="20" height="20">
                                                <use xlink:href="#key"></use>
                                            </svg>
                                        </label>
                                        {error}{hint}
                                    </div>
                                '
                ])->textInput([
                    'id'           => "signup-password-confirm",
                    'type'         => "password",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/signup', 'Confirm_password'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/signup', 'Confirm_password'),
                ])->label(false)
                ?>


                <?=
                $form->field($SignupFormModel, 'acceptRules', [
                    'template' => "",
                    'inputTemplate' => "",
                    'options' => ['class' => "check-wrap private form-group"],
                    'checkboxTemplate'=>'
                    <div class="check-wrap">
                        {input}
                        <label for="agreement"><span></span><span ' . Yii::t('modals/signup', 'style_i_accept') . '>' . Yii::t('modals/signup', 'I_accept') . '</span></label>
                        {error}{hint}
                    </div>
                    ',
                ])
                    ->checkbox([
                        'id' => "agreement",
                        'class' => "dark-marker-checkbox",
                        'value' => true,
                        'autocomplete' => "off",
                        'inputTemplate' => "",
                        'checked' => true,
                    ])
                    ->label(false)
                ?>


                <div class="modal__submit">
                    <button class="accent-btn wide-mob-btn" type="submit"><?= Yii::t('modals/signup', 'Register_now') ?></button>
                    <div class="social-auth">
                        <div class="social-auth__title"><?= Yii::t('modals/signup', 'through_social') ?></div>
                        <?php
                        socialAuthLinks()
                        ?>
                    </div>
                </div>

            <?php
            ActiveForm::end();
            ?>
        </div>
        <div class="modal__footer"><?= Yii::t('modals/signup', 'have_account') ?></div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end signup-popup MODAL -->

<!-- begin terms-of-use-popup MODAL -->
<div class="modal" id="terms-of-use-popup">
    <div class="modal__inner">
        <div class="modal__body">

            <iframe src="<?= Url::to(['/terms-of-use', 'empty-layout' => 1], CREATE_ABSOLUTE_URL) ?>"></iframe>

        </div>
        <div class="modal__footer"><?= Yii::t('modals/signup', 'Return_to_register') ?></div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end terms-of-use-popup MODAL -->

<!-- begin forgot-pass-popup MODAL -->
<div class="modal" id="forgot-pass-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('modals/forgot-pass', 'Enter_your_Email') ?></div>

            <?php
            $form = ActiveForm::begin([
                'id' => "form-forgot",
                'action'  => Url::to(['/request-password-reset'], CREATE_ABSOLUTE_URL),
                'options' => [
                    'class'      => "modal__form",
                ],
                'enableClientValidation' => true,
            ]);
            ?>

                <?=
                $form->field($PasswordResetRequest, 'email', [
                    'template' => '
                            <div class="input-wrap">
                                {input}
                                <label class="icon-label" for="forgot-email">
                                    <svg class="svg-icon-mail svg-icon" width="20" height="15">
                                        <use xlink:href="#mail"></use>
                                    </svg>
                                </label>
                                {error}{hint}
                            </div>
                        '
                ])->textInput([
                    'id'           => "forgot-email",
                    'type'         => "email",
                    'class'        => "icon-input",
                    'placeholder'  => Yii::t('modals/forgot-pass', 'Email'),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/forgot-pass', 'Email'),
                ])->label(false)
                ?>

                <div class="modal__submit"><button class="accent-btn wide-mob-btn" type="submit"><?= Yii::t('modals/forgot-pass', 'Send_request') ?></button></div>

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
<!-- end forgot-pass-popup MODAL -->
