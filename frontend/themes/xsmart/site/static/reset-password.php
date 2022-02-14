<?php

/** @var $this yii\web\View */
/** @var $form yii\bootstrap\ActiveForm */
/** @var $ResetPasswordForm \frontend\models\forms\ResetPasswordForm */
/** @var $token string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Html::encode(Yii::t('static/reset-password', 'title', ['APP_NAME' => Yii::$app->name]));

?>

<div class="content small-width">
    <h1 class="page-title text-center"><?= Yii::t('static/reset-password', 'Create_new_password') ?></h1>
    <div class="">
        <?php
        $form = ActiveForm::begin([
            'id' => "form-reset",
            'action'  => Url::to(['reset-password', 'token' => $token], CREATE_ABSOLUTE_URL),
            'options' => [
                'class'  => "modal__form",
                'method' => "post",
            ],
            'enableClientValidation' => true,
        ]);
        ?>

        <?=
        $form->field($ResetPasswordForm, 'password', [
            'template' => '
                <div class="input-wrap">
                    {input}
                    <label class="icon-label" for="reset-password">
                        <svg class="svg-icon-key svg-icon" width="20" height="20">
                            <use xlink:href="#key"></use>
                        </svg>
                    </label>
                    {error}{hint}
                </div>
            '
        ])->textInput([
            'id'           => "reset-password",
            'type'         => "password",
            'class'        => "icon-input",
            'placeholder'  => Yii::t('static/reset-password', 'Enter_password'),
            'autocomplete' => "off",
            'aria-label'   => Yii::t('static/reset-password', 'Enter_password'),
        ])->label(false)
        ?>

        <?=
        $form->field($ResetPasswordForm, 'password_repeat', [
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
            'id'           => "reset-password-confirm",
            'type'         => "password",
            'class'        => "icon-input",
            'placeholder'  => Yii::t('static/reset-password', 'Confirm_password'),
            'autocomplete' => "off",
            'aria-label'   => Yii::t('static/reset-password', 'Confirm_password'),
        ])->label(false)
        ?>

        <div class="modal__submit"><button class="accent-btn wide-mob-btn" type="submit"><?= Yii::t('static/reset-password', 'Save_new_password') ?></button></div>

        <?php
        ActiveForm::end();
        ?>
    </div>
</div>