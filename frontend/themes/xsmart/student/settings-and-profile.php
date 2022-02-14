<?php

/** @var $this yii\web\View */
/** @var $Auth \common\models\Auth */
/** @var $CurrentUser \common\models\Users */
/** @var $ProfileForm \frontend\models\forms\ProfileForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;
use common\models\Users;
use frontend\assets\xsmart\profileAsset;

profileAsset::register($this);

$this->title = Html::encode('Settings and Profile');

$tab = (isset($_GET['tab']) && in_array($_GET['tab'], ['settings', 'profile']))
    ? $_GET['tab']
    : 'settings';

$this->render('/helpers/link-account-function');
?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
    <div class="crumbs__title"><?= Yii::t('app/settings-and-profile', 'Settings') ?></div>
</div>
<div class="bg-wrapper">
    <div class="container">
        <div class="tabs-wrap">
            <div class="tabs tabs--btns tabs tabs--nowrap js-tabs">
                <div class="tabs__item js-tabs-item <?= $tab == 'settings' ? '_current' : '' ?>"><?= Yii::t('app/settings-and-profile', 'General') ?></div>
                <div class="tabs__item js-tabs-item <?= $tab == 'profile' ? '_current' : '' ?>"><?= Yii::t('app/settings-and-profile', 'Profile') ?></div>
            </div>
            <div class="tabs-content">
                <div class="box <?= $tab == 'settings' ? '_visible' : '' ?>">
                    <?php $form1 = ActiveForm::begin([
                        'id' => 'form-settings',
                        'action'=>['settings'],
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
                        <div class="screen screen--sm screen screen--left">
                            <div class="screen__header"></div>
                            <div class="screen__body screen__body--sm-top-pad screen__body screen__body--mob--sm-pad">
                                <div class="settings-wrap">
                                    <div class="settings-main">
                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'Time_zone') ?></div>
                                            <?= $form1->field($CurrentUser, 'user_timezone', [
                                                'template'=>'{input}',
                                            ])->dropDownList(Functions::get_list_of_timezones('offset_short_name'/*'name'*//*Yii::$app->language*/), [
                                                'id'         => "timezone-vars",
                                                'class'      => "lg-select wide-select js-select",
                                                'aria-label' => "time-zone",
                                            ])->label(false)
                                            ?>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'Email_notifications') ?></div>
                                            <div class="check-wrap check-wrap--switch">
                                                <input class="js-expand-schedule switch-checkbox switch-checkbox--accent"
                                                       name="Users[receive_system_notif]"
                                                       id="system"
                                                       value="1"
                                                       readonly="readonly"
                                                       checked="checked"
                                                        <?= ''/*$CurrentUser->receive_system_notif ? 'checked="checked"' : ''*/ ?>
                                                       type="checkbox" />
                                                <label for="system"><span></span><span><?= Yii::t('app/settings-and-profile', 'System') ?></span></label>
                                            </div>
                                            <div class="check-wrap check-wrap--switch">
                                                <input class="js-expand-schedule switch-checkbox switch-checkbox--accent"
                                                       name="Users[receive_lesson_notif]"
                                                       id="start-lesson"
                                                       <?= $CurrentUser->receive_lesson_notif ? 'checked="checked"' : '' ?>
                                                       value="1"
                                                       type="checkbox" />
                                                <label for="start-lesson"><span></span><span><?= Yii::t('app/settings-and-profile', 'Start_lesson') ?></span></label>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'Autoconfirm') ?></div>
                                            <div class="check-wrap">
                                                <input class="accent-radio"
                                                       type="radio"
                                                       name="Users[user_confirm_lesson]"
                                                       value="<?= Users::CONFIRM_LESSON_1 ?>"
                                                       <?= $CurrentUser->user_confirm_lesson == Users::CONFIRM_LESSON_1 ? 'checked="checked"' : '' ?>
                                                       id="confirm-1">
                                                <label for="confirm-1"><span></span><span><?= Yii::t('app/settings-and-profile', 'Confirm_v1') ?></span></label>
                                            </div>
                                            <div class="check-wrap">
                                                <input class="accent-radio"
                                                       type="radio"
                                                       name="Users[user_confirm_lesson]"
                                                       value="<?= Users::CONFIRM_LESSON_2 ?>"
                                                       <?= $CurrentUser->user_confirm_lesson == Users::CONFIRM_LESSON_2 ? 'checked="checked"' : '' ?>
                                                       id="confirm-2">
                                                <label for="confirm-2"><span></span><span><?= Yii::t('app/settings-and-profile', 'Confirm_v2') ?></span></label>
                                            </div>
                                        </div>
                                        <div class="form-note"><?= Yii::t('app/settings-and-profile', 'All_the_scheduled_lessons') ?></div>
                                        <div class="form-footer">
                                            <input type="hidden" name="tab" value="settings" />
                                            <button class="primary-btn wide-mob-btn" type="submit" name="settings_and_profile"><?= Yii::t('app/settings-and-profile', 'Save') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="box <?= $tab == 'profile' ? '_visible' : '' ?>">
                    <?php $form2 = ActiveForm::begin([
                        'id' => 'form-profile-student',
                        'action'=>['profile'],
                        'options' => [
                            'class'    => "profile-frm",
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'tag' => 'div',
                                'class' => 'input-wrap',
                            ],
                            'template' => '{label}{input}{error}{hint}',
                        ]
                    ]); ?>
                        <div class="screen screen--sm screen screen--left">
                            <div class="screen__header"></div>
                            <div class="screen__body screen__body--sm-top-pad screen__body screen__body--mob--sm-pad">
                                <div class="settings-wrap">
                                    <div class="settings-main">
                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'General_information') ?></div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_first_name')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Name'),
                                                        'class' => "md-input check-this-field",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Name') . ' <span class="required-label">*</span>')
                                                ?>

                                                <?= $form2->field($ProfileForm, 'user_email', [
                                                'enableAjaxValidation' => true,
                                                ])
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Email'),
                                                        'class' => "md-input check-this-field",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Email') . ' <span class="required-label">*</span>')
                                                ?>

                                            </div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_phone')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Phone'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Phone'))
                                                ?>

                                                <?= $form2->field($ProfileForm, '_user_skype')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Skype_Telegram'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Skype_Telegram'))
                                                ?>

                                            </div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'password')
                                                    ->passwordInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Password'),
                                                        //'value' => 'password',
                                                        'class' => "md-input",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Password'))
                                                ?>

                                                <div class="input-wrap">
                                                    <label for="profile-bday"><?= Yii::t('app/settings-and-profile', 'Date_Birth') ?></label>
                                                    <div class="input-row input-row--3col">

                                                        <?php
                                                        echo $form2->field($ProfileForm, 'user_birthday_day', [
                                                            'template'=>'<div class="input-wrap">{input}</div>',
                                                            'options' => [
                                                                'tag' => false,
                                                                //'class' => 'birthday-field',
                                                            ],
                                                        ])->dropDownList(Functions::get_days(), [
                                                            'id'         => "profile-bday",
                                                            'class'      => "lg-select js-select js-day-select",
                                                            'aria-label' => Yii::t('app/settings-and-profile', 'Day'),
                                                            'data-placeholder' => Yii::t('app/settings-and-profile', 'Day'),
                                                        ])->label(false);
                                                        ?>

                                                        <?php
                                                        echo $form2->field($ProfileForm, 'user_birthday_month', [
                                                            'template'=>'<div class="input-wrap">{input}</div>',
                                                            'options' => [
                                                                'tag' => false,
                                                                //'class' => 'birthday-field',
                                                            ],
                                                        ])->dropDownList(Functions::get_months(), [
                                                            'id'         => "profile-bmonth",
                                                            'class'      => "lg-select js-select js-month-select",
                                                            'aria-label' => Yii::t('app/settings-and-profile', 'Month'),
                                                            'data-placeholder' => Yii::t('app/settings-and-profile', 'Month'),
                                                        ])->label(false);
                                                        ?>

                                                        <?php
                                                        echo $form2->field($ProfileForm, 'user_birthday_year', [
                                                            'template'=>'<div class="input-wrap">{input}</div>',
                                                            'options' => [
                                                                'tag' => false,
                                                                //'class' => 'birthday-field',
                                                            ],
                                                        ])->dropDownList(Functions::get_years(), [
                                                            'id'         => "profile-byear",
                                                            'class'      => "lg-select js-select js-year-select",
                                                            'aria-label' => Yii::t('app/settings-and-profile', 'Year'),
                                                            'data-placeholder' => Yii::t('app/settings-and-profile', 'Year'),
                                                        ])->label(false);
                                                        ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="input-row">
                                                <div class="input-wrap">
                                                    <label for="gender-1"><?= Yii::t('app/settings-and-profile', 'Gender') ?></label>
                                                    <div class="check-grid">

                                                        <div class="check-wrap">
                                                            <input id="sex-1"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_gender]"
                                                                   value="<?= Users::GENDER_MALE ?>"
                                                                <?= $ProfileForm->user_gender === Users::GENDER_MALE ? 'checked="checked"' : '' ?> />
                                                            <label for="sex-1"><span></span><span><?= Yii::t('app/settings-and-profile', 'Male') ?></span></label>
                                                        </div>
                                                        <div class="check-wrap">
                                                            <input id="sex-2"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_gender]"
                                                                   value="<?= Users::GENDER_FEMALE ?>"
                                                                <?= $ProfileForm->user_gender === Users::GENDER_FEMALE ? 'checked="checked"' : '' ?> />
                                                            <label for="sex-2"><span></span><span><?= Yii::t('app/settings-and-profile', 'Female') ?></span></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            linkAccountWidget($Auth, true);
                                            ?>
                                            <div class="profile-photo">
                                                <div class="profile-photo__label"><?= Yii::t('app/settings-and-profile', 'Your_photo') ?></div>
                                                <img id="user-profile-photo-container-mobile"
                                                     class="profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_photo') ?></button>
                                                        <button class="profile-photo__remove-btn text-btn profile-photo__remove-btn"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Delete') ?></button>
                                                    </div>

                                                    <div class="photo-disabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Upload_photo') ?></button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'Language') ?></div>
                                            <div class="input-row input-row--lg-margin">
                                                <div class="input-wrap">
                                                    <label for="lng-1"><?= Yii::t('app/settings-and-profile', 'You_are_Native') ?></label>
                                                    <div class="check-grid">
                                                        <?php
                                                        $_user_are_native = unserialize($ProfileForm->user_are_native);
                                                        foreach (Users::$_languages as $key => $item) {
                                                            ?>
                                                            <div class="check-wrap">
                                                                <input id="radio-user-are-native-<?= $item ?>"
                                                                       class="accent-checkbox user-are-native"
                                                                       data-lng="<?= $item ?>"
                                                                       name="ProfileForm[_user_are_native][<?= $item ?>]"
                                                                       <?= !empty($_user_are_native[$item]) ? 'checked="checked"' : '' ?>
                                                                       type="checkbox" />
                                                                <label for="radio-user-are-native-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-row input-row--lg-margin">
                                                <div class="input-wrap"><label for="lng-21"><?= Yii::t('app/settings-and-profile', 'You_speak_also') ?></label>
                                                    <div class="check-grid">
                                                        <?php
                                                        $_user_speak_also = unserialize($ProfileForm->user_speak_also);
                                                        foreach (Users::$_languages as $key => $item) {

                                                            $disabled = "";
                                                            $visible = "";
                                                            $checked = "";
                                                            $value  = "off";
                                                            if (!empty($_user_speak_also[$item])) {
                                                                $checked = 'checked="checked"';
                                                                $visible = '_visible';
                                                                $value = $_user_speak_also[$item];
                                                            }
                                                            if (!empty($_user_are_native[$item])) {
                                                                $checked = 'checked="checked"';
                                                                $disabled = 'disabled="disabled"';
                                                                $visible = '';
                                                                $value = 'NATIVE';
                                                            }

                                                            ?>

                                                            <div class="check-wrap">
                                                                <input id="radio-user-speak-also-<?= $item ?>"
                                                                       data-lng="<?= $item ?>"
                                                                       class="accent-checkbox js-has-related user-speak-also"
                                                                       name="ProfileForm[_user_speak_also][<?= $item ?>]"
                                                                       value="<?= $value ?>"
                                                                        <?= $checked ?>
                                                                        <?= $disabled ?>
                                                                       type="checkbox" />
                                                                <label for="radio-user-speak-also-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                                                <div id="div-select-user-speak-also-<?= $item ?>"
                                                                     class="related js-related <?= $visible ?>">
                                                                    <select id="select-user-speak-also-<?= $item ?>"
                                                                            name="__ProfileForm[_user_speak_also_select][<?= $item ?>]"
                                                                            class="lng-level-select sm-select js-select"
                                                                            data-for="radio-user-speak-also-<?= $item ?>">
                                                                        <?php
                                                                        foreach (Users::$_speak_levels as $key2 => $item2) {
                                                                            $selected_level = "";
                                                                            if (!empty($_user_speak_also[$item]) && $_user_speak_also[$item] == $item2) {
                                                                                $selected_level = 'selected="selected"';
                                                                            }
                                                                            echo "<option {$selected_level} value=\"{$item2}\">{$item2}</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($ProfileForm->user_type == Users::TYPE_STUDENT) { ?>
                                        <div class="form-section">
                                            <div class="form-section__title form-section__title--sm-margin"><?= Yii::t('app/settings-and-profile', 'Goals_of_education') ?></div>
                                            <div class="input-row input-row--lg-margin">
                                                <div class="input-wrap">
                                                    <div class="check-grid check-grid--3col">

                                                        <?php
                                                        $_user_goals_of_education = unserialize($ProfileForm->user_goals_of_education);
                                                        foreach (Users::$_goals_of_education as $key => $item) {
                                                            ?>
                                                            <div class="check-wrap">
                                                                <input id="radio-user-goals-of-education-<?= $item ?>"
                                                                       class="accent-checkbox user-goals-of-education"
                                                                       data-goal="<?= $item ?>"
                                                                       name="ProfileForm[_user_goals_of_education][<?= $item ?>]"
                                                                    <?= !empty($_user_goals_of_education[$item]) ? 'checked="checked"' : '' ?>
                                                                       type="checkbox" />
                                                                <label for="radio-user-goals-of-education-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="form-section">
                                            <div class="form-section__title form-section__title--sm-margin"><?= Yii::t('app/settings-and-profile', 'Additional_information') ?></div>
                                            <?= $form2->field($ProfileForm, 'user_additional_info', [
                                                    //'template'=>'{input}',
                                                    //'template' => '{label}{input}{error}{hint}',
                                                    'options' => [
                                                        //'tag' => false,
                                                    ]
                                                ])
                                                ->textarea([
                                                    'placeholder' => $ProfileForm->getAttributeLabel('Describe here please'),
                                                    'autocomplete' => "off",
                                                    'class' => "profile-user_additional_info",
                                                    'aria-label'   => $ProfileForm->getAttributeLabel('user_additional_info'),
                                                ])
                                                ->label(false)
                                            ?>
                                        </div>
                                        <div class="form-footer">
                                            <input type="hidden" name="tab" value="profile" />
                                            <input type="hidden" name="ProfileForm[user_price_peer_hour]" value="1.00" />
                                            <button class="primary-btn wide-mob-btn" type="submit" name="settings_and_profile"><?= Yii::t('app/settings-and-profile', 'Save') ?></button>
                                            <div class="form-footer__note required-notif">* <?= Yii::t('app/settings-and-profile', 'Required_field') ?></div>
                                        </div>
                                    </div>
                                    <div class="settings-sidebar">
                                        <?php
                                        linkAccountWidget($Auth, false);
                                        ?>
                                        <div class="settings-sidebar-section">
                                            <div class="profile-photo">
                                                <div class="profile-photo__label"><?= Yii::t('app/settings-and-profile', 'Your_photo') ?></div>
                                                <img id="user-profile-photo-container-desktop"
                                                     class="profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_photo') ?></button>
                                                        <button class="profile-photo__remove-btn text-btn profile-photo__remove-btn"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Delete') ?></button>
                                                    </div>

                                                    <div class="photo-disabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Upload_photo') ?></button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->render("../modals/upload-profile-photo-modal") ?>