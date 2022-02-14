<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $newStudentForm \common\models\Users */
/** @var $listOperators \yii\db\ActiveRecord[] */

use yii\bootstrap\ActiveForm;
use common\models\Users;

/**/
if (isset($listOperators)) {
    $operatorsOptions = '<option class="placeholder"
        value="0"
        data-disabled="disabled"
        selected="selected"
        data-hidden="hidden">Оператор не назначен</option>';
    foreach ($listOperators as $operator) {
        /** @var $operator \common\models\Users */
        //if (true) { $selected = ' selected="selected"'; }
        $operatorsOptions .= '<option value="' . $operator->user_id . '">' . $operator->user_full_name . ' (id: ' . $operator->user_id . ')</option>';
    }
}
?>
<!-- begin MODAL -->
<div class="modal modal--w900 new-student-modal" id="new-student-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title"
                 id="student-modal-title"
                 data-is-new-1="Добавить ученика"
                 data-is-new-0="Изменение ученика">{title}</div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-add-new-student',
                'action' => ['admin-common/add-new-student'],
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
                <input type="hidden" name="user_id" value="0" id="student-user_id" />
                <div class="form-row form-row--sm-gap">
                    <div class="form-col form-col--1_2">
                        <?= $form->field($newStudentForm, 'user_first_name')
                            ->textInput([
                                'id' => 'student-user_first_name',
                                'placeholder' => $newStudentForm->getAttributeLabel('user_first_name'),
                                'autocomplete' => "off",
                                'aria-label'   => $newStudentForm->getAttributeLabel('user_first_name'),
                                'class' => "-editable-input",
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <div class="form-col form-col--1_2">
                        <?= $form->field($newStudentForm, 'user_phone')
                            ->textInput([
                                'id' => 'student-user_phone',
                                'placeholder' => $newStudentForm->getAttributeLabel('user_phone'),
                                'autocomplete' => "off",
                                'aria-label'   => $newStudentForm->getAttributeLabel('user_phone'),
                                'class' => "-editable-input",
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <div class="form-col form-col--1_2">
                        <?= $form->field($newStudentForm, 'user_email', ['enableAjaxValidation' => true])
                            ->textInput([
                                'id' => 'student-user_email',
                                'placeholder' => $newStudentForm->getAttributeLabel('user_email'),
                                'autocomplete' => "off",
                                'aria-label'   => $newStudentForm->getAttributeLabel('user_email'),
                                'class' => "-editable-input",
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <div class="form-col form-col--1_2">
                        <?= $form->field($newStudentForm, '_user_skype')
                            ->textInput([
                                'id' => 'student-_user_skype',
                                'placeholder' => $newStudentForm->getAttributeLabel('_user_skype'),
                                'autocomplete' => "off",
                                'aria-label'   => $newStudentForm->getAttributeLabel('_user_skype'),
                                'class' => "-editable-input",
                            ])
                            ->label(false)
                        ?>
                    </div>

                    <?php if (isset($operatorsOptions)) { ?>
                    <div class="form-col form-col--1_2 select-wrap">
                        <select class="-js-select"
                                title=""
                                name="Users[operator_user_id]"
                                id="student-operator_user_id">
                            <?= $operatorsOptions ?>
                        </select>
                    </div>
                    <?php } ?>

                    <div class="form-col form-col--1_2 select-wrap">
                        <select class="-js-select"
                                title=""
                                name="Users[teacher_user_id]"
                                id="student-teacher_user_id"
                                data-current-val=""
                                disabled="disabled">
                        </select>
                    </div>
                    <!-- -->
                    <div class="form-col form-col--1_2 select-wrap">
                        <select class="-js-select"
                                title=""
                                name="Users[methodist_user_id]"
                                id="student-methodist_user_id"
                                data-current-val=""
                                disabled="disabled">
                        </select>
                    </div>
                    <div class="form-col form-col--1_2">
                        <!--<input class="js-date-time-picker" type="text" placeholder="Дата и время вводного урока" data-timepicker="true">-->
                        <select class="-js-select"
                                title=""
                                name="introduce_lesson_time"
                                id="student-introduce_lesson_time"
                                data-current-val=""
                                disabled="disabled">
                        </select>
                    </div>
                    <!-- -->

                    <div class="form-col form-col--wide">
                        <?= $form->field($newStudentForm, 'additional_service_notice')
                            ->textarea([
                                'id' => 'student-additional_service_notice',
                                'placeholder' => 'Общая информация',
                                'autocomplete' => "off",
                                'aria-label'   => $newStudentForm->getAttributeLabel('additional_service_notice'),
                                'rows' => '2',
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <div class="form-col form-col--wide">
                        <?php
                        if ($CurrentUser->user_type == Users::TYPE_ADMIN) {
                            echo $form->field($newStudentForm, 'admin_notice')
                                ->textarea([
                                    'id' => 'student-admin_notice',
                                    'placeholder' => 'Коментарий админа',
                                    'autocomplete' => "off",
                                    'aria-label'   => $newStudentForm->getAttributeLabel('admin_notice'),
                                    'rows' => '2',
                                ])
                                ->label(false);
                        } else {
                            echo $form->field($newStudentForm, 'operator_notice')
                                ->textarea([
                                    'id' => 'student-operator_notice',
                                    'placeholder' => 'Коментарий оператора',
                                    'autocomplete' => "off",
                                    'aria-label'   => $newStudentForm->getAttributeLabel('operator_notice'),
                                    'rows' => '2',
                                ])
                                ->label(false);
                        }
                        ?>
                    </div>
                    <div class="form-col form-col--wide">
                        <button class="btn primary-btn primary-btn--c6 modal__submit-btn wide-btn"
                                id="student-modal-btn-submit"
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
<div class="tpl" style="display: none;">
    <select id="tpl-loading-teachers" title="" style="display: none;">
        <option class="placeholder"
                value="0"
                disabled="disabled"
                selected="selected"
                hidden="hidden">Загрузка учителей...</option>
    </select>
    <select id="tpl-loading-methodists" title="" style="display: none;">
        <option class="placeholder"
                value="0"
                disabled="disabled"
                selected="selected"
                hidden="hidden">Загрузка методистов...</option>
    </select>
    <select id="tpl-loading-date" title="" style="display: none;">
        <option class="placeholder"
                value="0"
                disabled="disabled"
                selected="selected"
                hidden="hidden">Дата вводного занятия...</option>
    </select>
</div>