<?php

/** @var $this yii\web\View */
/** @var $newMethodistForm \common\models\Users */

use yii\bootstrap\ActiveForm;

?>

<!-- begin MODAL -->
<div class="modal modal--w900" id="new-methodist-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title"
                 id="methodist-modal-title"
                 data-is-new-1="Добавить методиста"
                 data-is-new-0="Изменение методиста">{title}</div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-add-new-methodist',
                'action' => ['add-new-methodist'],
                'method' => 'post',
                //'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                //'validateOnSubmit' => false,
                'options' => [
                    'class'    => "add-new-methodists-frm",
                ],
                'fieldConfig' => [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'input-wrap',
                    ],
                    'template' => '{label}{input}{error}{hint}',
                ]
            ]); ?>
            <input type="hidden" name="user_id" value="0" id="methodist-user_id" />
            <div class="form-row form-row--sm-gap">
                <div class="form-col form-col--wide">
                    <?= $form->field($newMethodistForm, 'user_last_name')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('user_last_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('user_last_name'),
                            'class' => "-editable-input",
                            'id' => "methodist-user_last_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newMethodistForm, 'user_first_name')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('user_first_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('user_first_name'),
                            'class' => "-editable-input",
                            'id' => "methodist-user_first_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newMethodistForm, 'user_middle_name')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('user_middle_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('user_middle_name'),
                            'class' => "-editable-input",
                            'id' => "methodist-user_middle_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newMethodistForm, 'user_phone')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('user_phone'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('user_phone'),
                            'class' => "-editable-input",
                            'id' => "methodist-user_phone"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newMethodistForm, 'user_email', ['enableAjaxValidation' => true])
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('user_email'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('user_email'),
                            'class' => "-editable-input",
                            'id' => "methodist-user_email"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newMethodistForm, '_user_skype')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('_user_skype'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('_user_skype'),
                            'class' => "-editable-input",
                            'id' => "methodist-_user_skype"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newMethodistForm, '_user_telegram')
                        ->textInput([
                            'placeholder' => $newMethodistForm->getAttributeLabel('_user_telegram'),
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('_user_telegram'),
                            'class' => "-editable-input",
                            'id' => "methodist-_user_telegram"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newMethodistForm, 'additional_service_notice')
                        ->textarea([
                            'placeholder' => 'Общая информация',
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('additional_service_notice'),
                            'id' => "methodist-additional_service_notice"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newMethodistForm, 'admin_notice')
                        ->textarea([
                            'placeholder' => 'Коментарий админа',
                            'autocomplete' => "off",
                            'aria-label'   => $newMethodistForm->getAttributeLabel('admin_notice'),
                            'id' => "methodist-admin_notice"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <button class="btn primary-btn primary-btn--c6 modal__submit-btn wide-btn"
                            id="methodist-modal-btn-submit"
                            data-is-new-1="Добавить"
                            data-is-new-0="Изменить"
                            type="submit">{button}</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end MODAL -->
