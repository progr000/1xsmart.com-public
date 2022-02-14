<?php

/** @var $model \common\models\Users */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;

$this->title = Html::encode('Настройки');

?>

<div class="dashboard">
        <h1 class="page-title">Настройки</h1>
        <div class="settings dashboard__window dashboard__window dashboard__window--block win win win--grey">
            <div class="win__top"></div>
            <div class="win__inner">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-settings',
                    'action'=>['/user/settings'],
                    'options' => [
                        'class'    => "settings-frm",
                    ],
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                        ],
                        //'template' => '{label}{input}{error}{hint}',
                    ]
                ]); ?>
                    <div class="form-fieldset">
                        <div class="form-fieldset__title">Часовой пояс</div>
                        <div class="select-wrap timezone-select">

                            <?= $form->field($model, 'user_timezone', [
                                'template'=>'{input}',
                            ])->dropDownList(Functions::get_list_of_timezones('name'/*Yii::$app->language*/), [
                                'id'         => "timezone-vars",
                                'class'      => "js-select",
                                'aria-label' => "time-zone",
                            ])->label(false)
                            ?>
                        </div>
                    </div>
                    <div class="form-fieldset">
                        <div class="form-fieldset__title">E-mail уведомления</div>
                        <div class="check-wrap check-wrap--switch">
                            <input class="switch-check"
                                   name="Users[receive_system_notif]"
                                   id="system-notify"
                                   value="1"
                                   checked="checked"
                                   readonly="readonly"
                                   type="checkbox" <?= $model->receive_system_notif ? 'checked="checked"' : '' ?> />
                            <label for="system-notify"><span></span><span>Системные</span></label>
                        </div>
                        <div class="check-wrap check-wrap--switch">
                            <input class="switch-check"
                                   name="Users[receive_lesson_notif]"
                                   id="lesson-notify"
                                   value="1"
                                   type="checkbox" <?= $model->receive_lesson_notif ? 'checked="checked"' : '' ?> />
                            <label for="lesson-notify"><span></span><span>О начале урока</span></label>
                        </div>
                    </div>
                <button class="profile-frm__submit btn primary-btn primary-btn--c6" type="submit">Сохранить</button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


<?= $form->field($model, 'receive_system_notif', [
    'template' => "<div>{input}</div><label>{label}</label>",

    'options' => [
        'tag' => false,
    ],
])->checkbox([
    'id'    => "system-notify",
    'class' => "switch-check",
])->label('Системные');
?>
