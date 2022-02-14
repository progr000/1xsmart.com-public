<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'user_created') ?>

    <?= $form->field($model, 'user_updated') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?= $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'verification_token') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'user_first_name') ?>

    <?php // echo $form->field($model, 'user_middle_name') ?>

    <?php // echo $form->field($model, 'user_last_name') ?>

    <?php // echo $form->field($model, 'user_full_name') ?>

    <?php // echo $form->field($model, 'user_email') ?>

    <?php // echo $form->field($model, 'user_phone') ?>

    <?php // echo $form->field($model, 'user_last_pay') ?>

    <?php // echo $form->field($model, 'user_token') ?>

    <?php // echo $form->field($model, 'user_hash') ?>

    <?php // echo $form->field($model, 'user_status') ?>

    <?php // echo $form->field($model, 'user_type') ?>

    <?php // echo $form->field($model, 'operator_user_id') ?>

    <?php // echo $form->field($model, 'operator_notice') ?>

    <?php // echo $form->field($model, 'methodist_user_id') ?>

    <?php // echo $form->field($model, 'methodist_notice') ?>

    <?php // echo $form->field($model, 'teacher_user_id') ?>

    <?php // echo $form->field($model, 'teacher_notice') ?>

    <?php // echo $form->field($model, 'user_balance') ?>

    <?php // echo $form->field($model, 'user_last_ip') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
