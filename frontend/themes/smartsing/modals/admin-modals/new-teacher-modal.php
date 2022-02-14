<?php

/** @var $this yii\web\View */
/** @var $newTeacherForm \common\models\Users */

use yii\bootstrap\ActiveForm;

?>

<!-- begin MODAL -->
<div class="modal modal--w900" id="new-teacher-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title"
                 id="teacher-modal-title"
                 data-is-new-1="Добавить учителя"
                 data-is-new-0="Изменение учителя">{title}</div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-add-new-teacher',
                'action' => ['add-new-teacher'],
                'method' => 'post',
                //'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                //'validateOnSubmit' => false,
                'options' => [
                    'class'    => "add-new-teachers-frm",
                ],
                'fieldConfig' => [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'input-wrap',
                    ],
                    'template' => '{label}{input}{error}{hint}',
                ]
            ]); ?>
            <input type="hidden" name="lead_id" value="0" id="teacher-lead_id" />
            <input type="hidden" name="user_id" value="0" id="teacher-user_id" />
            <div class="form-row form-row--sm-gap">
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'user_last_name')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('user_last_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('user_last_name'),
                            'class' => "-editable-input",
                            'id' => "teacher-user_last_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'user_first_name')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('user_first_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('user_first_name'),
                            'class' => "-editable-input",
                            'id' => "teacher-user_first_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'user_middle_name')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('user_middle_name'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('user_middle_name'),
                            'class' => "-editable-input",
                            'id' => "teacher-user_middle_name"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newTeacherForm, 'user_phone')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('user_phone'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('user_phone'),
                            'class' => "-editable-input",
                            'id' => "teacher-user_phone"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newTeacherForm, 'user_email', ['enableAjaxValidation' => true])
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('user_email'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('user_email'),
                            'class' => "-editable-input",
                            'id' => "teacher-user_email"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newTeacherForm, '_user_skype')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('_user_skype'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('_user_skype'),
                            'class' => "-editable-input",
                            'id' => "teacher-_user_skype"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--1_2">
                    <?= $form->field($newTeacherForm, '_user_telegram')
                        ->textInput([
                            'placeholder' => $newTeacherForm->getAttributeLabel('_user_telegram'),
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('_user_telegram'),
                            'class' => "-editable-input",
                            'id' => "teacher-_user_telegram"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'user_youtube_video')
                        ->textInput([
                            'placeholder' => "Ссылка на видео в Youtube",
                            'autocomplete' => "off",
                            'aria-label'   => "youtube-video",
                            'class' => "-editable-input",
                            'id' => "teacher-user_youtube_video"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'additional_service_notice')
                        ->textarea([
                            'placeholder' => 'Общая информация',
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('additional_service_notice'),
                            'id' => "teacher-additional_service_notice"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <?= $form->field($newTeacherForm, 'admin_notice')
                        ->textarea([
                            'placeholder' => 'Коментарий админа',
                            'autocomplete' => "off",
                            'aria-label'   => $newTeacherForm->getAttributeLabel('admin_notice'),
                            'id' => "teacher-admin_notice"
                        ])
                        ->label(false)
                    ?>
                </div>
                <div class="form-col form-col--wide">
                    <button class="btn primary-btn primary-btn--c6 modal__submit-btn wide-btn"
                            id="teacher-modal-btn-submit"
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
