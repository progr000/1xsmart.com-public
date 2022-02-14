<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $model \frontend\models\forms\AddHomeWorkForm */

use yii\bootstrap\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-profile',
    'action' => ['/methodist/add-home-work'],
    'options' => [
        'class'    => "img-progress-form",
    ],
]); ?>

    <div class="row steps">

        <?= $form->field($model, 'work_name')
            ->textInput([
                'placeholder' => $model->getAttributeLabel('work_name'),
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('work_name'),
            ])
            ->label(false)
        ?>

        <?= $form->field($model, 'work_description')
            ->textarea([
                'placeholder' => $model->getAttributeLabel('work_description'),
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('work_description'),
            ])
            ->label(false)
        ?>

        <?= $form->field($model, 'uploadedFile')
            ->fileInput([
                'placeholder' => $model->getAttributeLabel('uploadedFile'),
                'autocomplete' => "off",
                'aria-label'   => $model->getAttributeLabel('uploadedFile'),
            ])
            ->label(false)
        ?>

        <input type="submit" name="Save" value="Добавить задание" />

    </div>

<?php ActiveForm::end(); ?>