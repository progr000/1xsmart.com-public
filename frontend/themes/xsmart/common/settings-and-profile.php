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
        <a class="crumbs__link" href="<?= Url::to(['user/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
        <div class="crumbs__title">Settings</div>
    </div>
    <div class="bg-wrapper">
        <div class="container">
            <div class="tabs-wrap">
                <div class="tabs tabs--btns tabs tabs--nowrap js-tabs">
                    <div class="tabs__item js-tabs-item <?= $tab == 'settings' ? '_current' : '' ?>">General</div>
                    <div class="tabs__item js-tabs-item <?= $tab == 'profile' ? '_current' : '' ?>">Profile</div>
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
                                            <div class="form-section__title">Time zone</div>
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
                                            <div class="form-section__title">Email notifications</div>
                                            <div class="check-wrap check-wrap--switch">
                                                <input class="js-expand-schedule switch-checkbox switch-checkbox--accent"
                                                       name="Users[receive_system_notif]"
                                                       id="system"
                                                       value="1"
                                                       readonly="readonly"
                                                       type="checkbox" <?= $CurrentUser->receive_system_notif ? 'checked="checked"' : '' ?> />
                                                <label for="system"><span></span><span>System</span></label>
                                            </div>
                                        </div>
                                        <div class="form-footer">
                                            <input type="hidden" name="tab" value="settings" />
                                            <button class="primary-btn wide-mob-btn" type="submit" name="settings_and_profile">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="box <?= $tab == 'profile' ? '_visible' : '' ?>">
                        <?php $form2 = ActiveForm::begin([
                            'id' => 'form-profile-common',
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
                                            <div class="form-section__title">General information</div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_first_name')
                                                    ->textInput([
                                                        'placeholder' => 'Enter',
                                                        'autocomplete' => "off",
                                                        'aria-label'   => $ProfileForm->getAttributeLabel('Name'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label('Name <span class="required-label">*</span>')
                                                ?>

                                                <?= $form2->field($ProfileForm, 'user_email', [
                                                    'enableAjaxValidation' => true,
                                                ])
                                                    ->textInput([
                                                        'placeholder' => 'Enter',
                                                        'autocomplete' => "off",
                                                        'aria-label'   => $ProfileForm->getAttributeLabel('user_email'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label('Email <span class="required-label">*</span>')
                                                ?>

                                            </div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_phone')
                                                    ->textInput([
                                                        'placeholder' => 'Enter',
                                                        'autocomplete' => "off",
                                                        'aria-label'   => $ProfileForm->getAttributeLabel('user_phone'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label('Phone')
                                                ?>

                                                <?= $form2->field($ProfileForm, '_user_skype')
                                                    ->textInput([
                                                        'placeholder' => 'Enter',
                                                        'autocomplete' => "off",
                                                        'aria-label'   => $ProfileForm->getAttributeLabel('_user_skype'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label('Skype/Telegram')
                                                ?>

                                            </div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'password')
                                                    ->passwordInput([
                                                        'placeholder' => 'Enter',
                                                        'autocomplete' => "off",
                                                        'aria-label'   => 'new_password',
                                                        //'value' => 'password',
                                                        'class' => "md-input",
                                                    ])
                                                    ->label('Password')
                                                ?>

                                                <div class="input-wrap">
                                                    <label for="profile-bday">Date of Birth</label>
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
                                                            'aria-label' => $ProfileForm->getAttributeLabel('user_birthday_day'),
                                                            'data-placeholder' => "Day",
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
                                                            'aria-label' => $ProfileForm->getAttributeLabel('user_birthday_month'),
                                                            'data-placeholder' => "Month",
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
                                                            'aria-label' => $ProfileForm->getAttributeLabel('user_birthday_day'),
                                                            'data-placeholder' => "Year",
                                                        ])->label(false);
                                                        ?>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="input-row">
                                                <div class="input-wrap">
                                                    <label for="gender-1">Gender</label>
                                                    <div class="check-grid">

                                                        <div class="check-wrap">
                                                            <input id="sex-1"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_gender]"
                                                                   value="<?= Users::GENDER_MALE ?>"
                                                                <?= $ProfileForm->user_gender === Users::GENDER_MALE ? 'checked="checked"' : '' ?> />
                                                            <label for="sex-1"><span></span><span>Male</span></label>
                                                        </div>
                                                        <div class="check-wrap">
                                                            <input id="sex-2"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_gender]"
                                                                   value="<?= Users::GENDER_FEMALE ?>"
                                                                <?= $ProfileForm->user_gender === Users::GENDER_FEMALE ? 'checked="checked"' : '' ?> />
                                                            <label for="sex-2"><span></span><span>Female</span></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            linkAccountWidget($Auth, true);
                                            ?>
                                            <div class="profile-photo">
                                                <div class="profile-photo__label">Your photo</div>
                                                <img id="user-profile-photo-container-mobile"
                                                     class="profile-photo__ava-1 profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button">Change photo</button>
                                                        <button class="profile-photo__remove-btn text-btn profile-photo__remove-btn"
                                                                type="button">Delete</button>
                                                    </div>

                                                    <div class="photo-disabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button">Upload photo</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-footer">
                                            <input type="hidden" name="tab" value="profile" />
                                            <button class="primary-btn wide-mob-btn" type="submit" name="settings_and_profile">Save</button>
                                            <div class="form-footer__note">* Required field</div>
                                        </div>
                                    </div>
                                    <div class="settings-sidebar">
                                        <?php
                                        linkAccountWidget($Auth, false);
                                        ?>
                                        <div class="settings-sidebar-section">
                                            <div class="profile-photo">
                                                <div class="profile-photo__label">Your photo</div>
                                                <img id="user-profile-photo-container-desktop"
                                                     class="profile-photo__ava-2 profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button">Change photo</button>
                                                        <button class="profile-photo__remove-btn text-btn profile-photo__remove-btn"
                                                                type="button">Delete</button>
                                                    </div>

                                                    <div class="photo-disabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button">Upload photo</button>
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