<?php

/** @var $this yii\web\View */
/** @var $button_class string */
/** @var $form_class string */
/** @var $form_id string */
/** @var $model \frontend\models\forms\IndexRequestForm */

use yii\bootstrap\ActiveForm;

if (isset($type) && $type == 'coaches') {
    $request_type = 'teacher';
    $field = 'request_fio';
} else {
    $request_type = 'student';
    $field = 'request_name';
}
?>

<?php $form = ActiveForm::begin([
    'id' => $form_id,
    'action' => null,
    'options' => [
        'onsubmit'   => "return false",
        'class'    => "{$form_class}",
    ],
    //'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    //'validateOnSubmit' => false,
    'fieldConfig' => [
        'options' => [
            'tag' => 'div',
            'class' => 'lead-action__input-wrap input-wrap input-wrap input-wrap--icon',
        ],
        'template' => '{label}{input}{hint}{error}',
    ]
]); ?>

    <input type="hidden" name="IndexRequestForm[request_type]" id="<?= "{$form_id}-request_type" ?>" class="js-request-inputs" value="<?= $request_type ?>" />

    <?= $form->field($model, "request_phone")
        ->textInput([
            'id' => "{$form_id}-request_phone",
            'placeholder' => $model->getAttributeLabel('request_phone'),
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('request_phone'),
            'class' => "phone-input js-request-inputs",
        ])
        ->label(false)
        ->hint(
            '<svg class="svg-icon--phone-2 svg-icon" width="30" height="25">
                                    <use xlink:href="#phone-2"></use>
                                </svg>
                                <svg class="svg-icon--phone-2-color svg-icon" width="30" height="25">
                                    <use xlink:href="#phone-2-color"></use>
                                </svg>',
            ['tag' => false]
        );
    ?>

    <?= $form->field($model, $field)
        ->textInput([
            'id' => "{$form_id}-{$field}",
            'placeholder' => $model->getAttributeLabel($field),
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel($field),
            'class' => "js-request-inputs",
        ])
        ->label(false)
        ->hint(
            '<svg class="svg-icon--user-2 svg-icon" width="26" height="27">
                                    <use xlink:href="#user-2"></use>
                                </svg>
                                <svg class="svg-icon--user-2-color svg-icon" width="26" height="27">
                                    <use xlink:href="#user-2-color"></use>
                                </svg>',
            ['tag' => false]
        );
    ?>

    <?= $form->field($model, "request_email")
        ->textInput([
            'id' => "{$form_id}-request_email",
            'type' => "email",
            'placeholder' => $model->getAttributeLabel('request_email'),
            'autocomplete' => "off",
            'aria-label'   => $model->getAttributeLabel('request_email'),
            'class' => "js-request-inputs",
        ])
        ->label(false)
        ->hint(
            '<svg class="svg-icon--mail svg-icon" width="26" height="19">
                                    <use xlink:href="#mail"></use>
                                </svg>
                                <svg class="svg-icon--mail-color svg-icon" width="26" height="19">
                                    <use xlink:href="#mail-color"></use>
                                </svg>',
            ['tag' => false]
        );
    ?>

    <button
        class="js-send-student-request <?= $button_class ?>"
        type="button" data-form-id="<?= $form_id ?>"
        data-ready-to-send="Отправить заявку"
        data-sent-in-progress="Отправляется..."><span>Отправить заявку</span>
        <svg class="svg-icon--melody svg-icon" width="20" height="20">
            <use xlink:href="#melody"></use>
        </svg>
    </button>

    <?php if (isset($type) && $type == 'cost') { ?>
    <p class="private-info text-center">
        Заполняя форму я
        <a href="#"
           class="void-0 private-link js-open-modal js-open-pdf-modal"
           data-title="Соглашение"
           data-content="/assets/smartsing-min/files/private.pdf"
           data-modal-id="modal-private">соглашаюсь на обработку персональных данных</a>.
    </p>
    <?php } ?>


<?php ActiveForm::end(); ?>