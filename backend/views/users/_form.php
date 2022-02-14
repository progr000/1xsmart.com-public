<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Users;

/* @var $this yii\web\View */
/* @var $model common\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_first_name')->textInput() ?>

    <?= $form->field($model, 'user_middle_name')->textInput() ?>

    <?= $form->field($model, 'user_last_name')->textInput() ?>

    <?= $form->field($model, 'user_full_name')->textInput() ?>

    <?= $form->field($model, 'user_email')->textInput() ?>

    <?= $form->field($model, 'user_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_last_pay')->textInput() ?>


    <?= !$model->isNewRecord
        ? $form
            ->field($model, 'user_token')->textInput(['maxlength' => true, 'readonly' => true])
            ->label('
                Login token<br />
                [<a href="' . Url::to(['/users/generate-token', 'id' => $model->user_id]) . '"><small style="color: #FF0000;">Generate new</small></a>]
                &nbsp;
                [<a href="' . Url::to(['/users/delete-token', 'id' => $model->user_id]) . '"><small style="color: #FF0000;">Delete token</small></a>]
                ')
        : ''
    ?>

    <?= $form->field($model, 'user_status')->dropDownList(Users::getStatuses()) ?>

    <?= $form->field($model, 'user_type')->dropDownList(Users::getTypes()) ?>

    <?= $form->field($model, 'operator_user_id')->textInput() ?>

    <?= $form->field($model, 'operator_notice')->textInput() ?>

    <?= $form->field($model, 'methodist_user_id')->textInput() ?>

    <?= $form->field($model, 'methodist_notice')->textInput() ?>

    <?= $form->field($model, 'teacher_user_id')->textInput() ?>

    <?= $form->field($model, 'teacher_notice')->textInput() ?>

    <?= $form->field($model, 'user_balance')->textInput() ?>

    <?= $form->field($model, 'user_lessons_available')->textInput() ?>

    <?= $form->field($model, 'user_lessons_completed')->textInput() ?>

    <?= $form->field($model, 'user_lessons_missed')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
