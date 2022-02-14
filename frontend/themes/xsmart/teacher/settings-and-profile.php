<?php

/** @var $this yii\web\View */
/** @var $Auth \common\models\Auth */
/** @var $CurrentUser \common\models\Users */
/** @var $ProfileForm \frontend\models\forms\ProfileForm */
/** @var $Disciplines array */
/** @var $TeachersDisciplines \common\models\TeachersDisciplines */
/** @var $countries array */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;
use common\models\Users;
use frontend\assets\xsmart\profileAsset;

profileAsset::register($this);

$this->title = Html::encode(Yii::t('app/settings-and-profile', 'title'));

$tab = (isset($_GET['tab']) && in_array($_GET['tab'], ['settings', 'profile']))
    ? $_GET['tab']
    : 'settings';

$select_disciplines[0] = '';
//$select_disciplines[-1] = 'fffff';
$lang = Yii::$app->language;
$discipline_name_field = "discipline_name_{$lang}";
foreach ($Disciplines as $k=>$v) {
    $select_disciplines[$v['discipline_id']] = isset($v[$discipline_name_field]) ? $v[$discipline_name_field] : $v['discipline_name_en'];
}

$ProfileForm->discipline_id = ($TeachersDisciplines) ? $TeachersDisciplines->discipline_id : 0;

$this->render('/helpers/link-account-function');

?>

<?= Yii::t('static/find-tutors-css', 'css') ?>
<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['teacher/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
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
                                                       value="1"
                                                       type="checkbox" <?= $CurrentUser->receive_lesson_notif ? 'checked="checked"' : '' ?> />
                                                <label for="start-lesson"><span></span><span><?= Yii::t('app/settings-and-profile', 'Start_lesson') ?></span></label>
                                            </div>
                                        </div>
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
                        'id' => 'form-profile-teacher',
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
                                            <div class="input-row">

                                                <?=
                                                $form2->field($ProfileForm, 'discipline_id', [
                                                    'enableAjaxValidation' => true,
                                                    'template' => '
                                                    <label class="lg-label" for="profile-disciplines">' . Yii::t('app/settings-and-profile', 'I_would_like_teach') . ' <span class="required-label">*</span></label>
                                                    {input}{error}{hint}',
                                                ])->dropDownList($select_disciplines, [
                                                    'id'         => "profile-disciplines",
                                                    'class'      => "lg-select js-select check-this-field",
                                                    'aria-label' => $ProfileForm->getAttributeLabel('disciplines'),
                                                    'data-placeholder' => Yii::t('app/settings-and-profile', 'Choose'),
                                                    'options' => [
                                                        0 => [
                                                            'data-placeholder' => "true",
                                                        ],
                                                    ],
                                                ])->label(false);
                                                ?>

                                                <div class="input-wrap">
                                                    <label class="lg-label" for="children-1"><?= Yii::t('app/settings-and-profile', 'I_can_teach_children') ?> <span class="required-label">*</span></label>
                                                    <div class="check-grid check-grid--3col radioboxes-required">

                                                        <div class="check-wrap">
                                                            <input id="children-1"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_can_teach_children]"
                                                                   value="<?= Users::YES ?>"
                                                                <?= $ProfileForm->user_can_teach_children === Users::YES ? 'checked="checked"' : '' ?> />
                                                            <label for="children-1"><span></span><span><?= Yii::t('app/settings-and-profile', 'Yes') ?></span></label>
                                                        </div>
                                                        <div class="check-wrap">
                                                            <input id="children-2"
                                                                   class="accent-radio"
                                                                   type="radio"
                                                                   name="ProfileForm[user_can_teach_children]"
                                                                   value="<?= Users::NO ?>"
                                                                <?= $ProfileForm->user_can_teach_children === Users::NO ? 'checked="checked"' : '' ?> />
                                                            <label for="children-2"><span></span><span><?= Yii::t('app/settings-and-profile', 'No') ?></span></label>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'General_information') ?></div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_first_name')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'First_name'),
                                                        'class' => "md-input check-this-field",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'First_name') . ' <span class="required-label">*</span>')
                                                ?>

                                                <?= $form2->field($ProfileForm, 'user_middle_name')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Middle_name'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Middle_name'))
                                                ?>

                                            </div>
                                            <div class="input-row">

                                                <?= $form2->field($ProfileForm, 'user_last_name')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Last_name'),
                                                        'class' => "md-input check-this-field",
                                                    ])
                                                    ->label(Yii::t('app/settings-and-profile', 'Last_name') . ' <span class="required-label">*</span>')
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
                                                    ->label(
                                                        Yii::t('app/settings-and-profile', 'Email') .
                                                        ' <span class="required-label">*</span>' .
                                                        '<span class="tooltip-label js-has-tooltip"
                                                              data-tooltip="' . Yii::t('app/settings-and-profile', 'info_Confidentiality') . '">
                                                            <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                        </span>'
                                                    )
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
                                                    ->label(
                                                        Yii::t('app/settings-and-profile', 'Phone') .
                                                        '<span class="tooltip-label js-has-tooltip"
                                                              data-tooltip="' . Yii::t('app/settings-and-profile', 'info_Confidentiality') . '">
                                                            <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                        </span>'
                                                    )
                                                ?>

                                                <?= $form2->field($ProfileForm, '_user_skype')
                                                    ->textInput([
                                                        'placeholder' => Yii::t('app/settings-and-profile', 'Enter'),
                                                        'autocomplete' => "off",
                                                        'aria-label'   => Yii::t('app/settings-and-profile', 'Skype_Telegram'),
                                                        'class' => "md-input",
                                                    ])
                                                    ->label(
                                                        Yii::t('app/settings-and-profile', 'Skype_Telegram') .
                                                        ' <span class="required-label">*</span>' .
                                                        '<span class="tooltip-label js-has-tooltip"
                                                              data-tooltip="' . Yii::t('app/settings-and-profile', 'info_Confidentiality') . '">
                                                            <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                        </span>'
                                                    )
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
                                                    <label for="profile-bday">
                                                        <?= Yii::t('app/settings-and-profile', 'Date_Birth') ?>
                                                        <span class="tooltip-label js-has-tooltip"
                                                              data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Confidentiality') ?>">
                                                            <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                        </span>
                                                    </label>
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
                                                <div class="input-wrap location-required">
                                                    <label for="name">
                                                        <?= Yii::t('app/settings-and-profile', 'I_am_phisically_in') ?>
                                                        <span class="required-label">*</span>
                                                        <span class="tooltip-label js-has-tooltip"
                                                              data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_I_am_phisically_in') ?>">
                                                            <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                        </span>
                                                    </label>
                                                    <div class="cmplx-input cmplx-input--lg has-overlay-input js-cmplx">
                                                        <input id="select-all-geo-ok1"
                                                               class="cmplx-input__input js-cmplx-input profile-geo-field js-cmplx-select-input"
                                                               -id="geo-full-location-field"
                                                               name="ProfileForm[geo_full]"
                                                               type="text"
                                                               value="<?= Yii::t('app/settings-and-profile', 'Choose') ?>"
                                                               readonly="readonly"
                                                               data-ids="-1, -1, -1"
                                                               data-default-value="<?= Yii::t('app/settings-and-profile', 'Choose') ?>" />
                                                        <div class="cmplx-input__dropdown js-cmplx-dropdown">
                                                            <!--<input type="text" placeholder="Search">-->
                                                            <select id="geo-country-field"
                                                                    name="ProfileForm[country_id]"
                                                                    class="-js-select js-search-select js-cmplx-data geo-select -js-select-deselect"
                                                                    data-any-name="<?= Yii::t('app/settings-and-profile', 'Choose') ?>"
                                                                    data-placeholder="<?= Yii::t('app/settings-and-profile', 'Country') ?>"
                                                                    data-placeholder-ready="<?= Yii::t('app/settings-and-profile', 'Country') ?>"
                                                                    data-placeholder-any="<?= Yii::t('app/settings-and-profile', 'Any') ?>"
                                                                    data-placeholder-loading="<?= Yii::t('app/settings-and-profile', 'Loading') ?>">
                                                                <option value="" data-placeholder="true"></option>
                                                                <!--<option value="0">irrelevant</option>-->
                                                                <?php
                                                                $country_name_field = "title_{$lang}";
                                                                /** @var \common\models\Countries $country */
                                                                foreach ($countries as $country) {
                                                                    if ($ProfileForm->country_id == intval($country['country_id'])) {
                                                                        $selected = ' selected="selected"';
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo '<option value="' . $country['country_id'] . '" ' . $selected . '>' . (isset($country[$country_name_field]) ? $country[$country_name_field] : $country['title_en']) . '</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                            <select id="geo-region-field"
                                                                    name="ProfileForm[region_id]"
                                                                    class="-js-select js-search-select js-cmplx-data geo-select"
                                                                    data-saved-val="<?= $ProfileForm->region_id ?>"
                                                                    data-placeholder="<?= Yii::t('app/settings-and-profile', 'Select_country_before') ?>"
                                                                    data-placeholder-ready="<?= Yii::t('app/settings-and-profile', 'Region') ?>"
                                                                    data-placeholder-any="<?= Yii::t('app/settings-and-profile', 'Region_no_matter') ?>"
                                                                    data-placeholder-select="<?= Yii::t('app/settings-and-profile', 'Select_country_before') ?>"
                                                                    data-placeholder-loading="<?= Yii::t('app/settings-and-profile', 'Loading') ?>">
                                                                <option value="" data-placeholder="true"></option>
                                                            </select>

                                                            <select id="geo-city-field"
                                                                    name="ProfileForm[city_id]"
                                                                    class="-js-select js-search-select js-cmplx-data geo-select"
                                                                    data-saved-val="<?= $ProfileForm->city_id ?>"
                                                                    data-placeholder="<?= Yii::t('app/settings-and-profile', 'Select_region_before') ?>"
                                                                    data-placeholder-ready="<?= Yii::t('app/settings-and-profile', 'City') ?>"
                                                                    data-placeholder-any="<?= Yii::t('app/settings-and-profile', 'City_no_matter') ?>"
                                                                    data-placeholder-select="<?= Yii::t('app/settings-and-profile', 'Select_region_before') ?>"
                                                                    data-placeholder-loading="<?= Yii::t('app/settings-and-profile', 'Loading') ?>">
                                                                <option value="" data-placeholder="true"></option>
                                                            </select>

                                                            <button id="select-all-geo-ok2" class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button"><?= Yii::t('app/settings-and-profile', 'Choose') ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-wrap">
                                                    <label for="gender-1"><?= Yii::t('app/settings-and-profile', 'Gender') ?></label>
                                                    <div class="check-grid check-grid--3col">

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
                                            <div class="profile-photo required-photo">
                                                <div class="profile-photo__label"><?= Yii::t('app/settings-and-profile', 'Your_photo') ?> <span class="required-label">*</span></div>
                                                <img id="user-profile-photo-container-mobile"
                                                     class="profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png?v=1') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_photo') ?></button>
                                                        <br />
                                                        <button class="profile-photo__remove-btn text-btn"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Delete') ?></button>
                                                    </div>

                                                    <div class="photo-disabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Upload_photo') ?></button>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="profile-photo" data-off-style="display: none;">
                                                <div class="profile-photo__label">
                                                    <?= Yii::t('app/settings-and-profile', 'Your_video_presentation') ?><span class="required-label">*</span>
                                                    <br />
                                                    <span class="tooltip-label js-has-tooltip"
                                                          data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Your_video_presentation') ?>">
                                                        <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                    </span>
                                                </div>
                                                <!--
                                                <div class="profile-photo__media">
                                                    <img class="profile-photo__video-screen" src="/assets/xsmart-min/files/video/screen_180x113.jpg" alt="" role="presentation" />
                                                    <div class="profile-photo__play video-play"></div>
                                                </div>
                                                -->
                                                <div class="profile-photo__tools">

                                                    <div class="video-enabled" style="text-align: center; display: <?= $ProfileForm->user_local_video ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="view-my-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'View_video') ?></button>
                                                        <br />
                                                        <button data-modal-id="upload-profile-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_video') ?></button>
                                                        <br />
                                                        <button class="profile-video__remove-btn text-btn"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Delete') ?></button>
                                                    </div>

                                                    <div class="video-disabled" style="text-align: center; display: <?= $ProfileForm->user_local_video ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Upload_video') ?></button>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="settings-section-or"><?= Yii::t('app/settings-and-profile', 'or') ?></div>

                                            <div class="profile-photo">
                                                <div class="profile-photo__label">
                                                    <?= Yii::t('app/settings-and-profile', 'Youtube_video') ?><span class="required-label">*</span>
                                                    <br />
                                                    <span class="tooltip-label js-has-tooltip"
                                                          data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Your_video_presentation') ?>">
                                                        <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                    </span>
                                                </div>
                                                <div class="profile-photo_">
                                                    <?= $form2->field($ProfileForm, 'user_youtube_video')
                                                        ->textInput([
                                                            'id' => 'id1-user_youtube_video',
                                                            'placeholder' => Yii::t('app/settings-and-profile', 'Youtube_link'),
                                                            'autocomplete' => "off",
                                                            'aria-label'   => Yii::t('app/settings-and-profile', 'Youtube_link'),
                                                            'class' => "md-input user-youtube-video check-this-field",
                                                        ])
                                                        ->label(false)
                                                    ?>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="form-section">
                                            <div class="form-section__title"><?= Yii::t('app/settings-and-profile', 'Language') ?></div>
                                            <div class="input-row input-row--lg-margin">
                                                <div class="input-wrap checkboxes-required">
                                                    <label for="lng-1"><?= Yii::t('app/settings-and-profile', 'My_Native') ?> <span class="required-label">*</span></label>
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
                                                <div class="input-wrap">
                                                    <label for="lng-21"><?= Yii::t('app/settings-and-profile', 'I_speak_also') ?></label>
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


                                        <div class="form-section">
                                            <div class="form-section__title form-section__title--sm-margin">
                                                <span><?= Yii::t('app/settings-and-profile', 'My_specialisation') ?></span>
                                                <span class="tooltip-label js-has-tooltip"
                                                      data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_My_specialisation') ?>">
                                                    <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                </span>
                                            </div>
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

                                        <div class="form-section">
                                            <div class="form-section__title form-section__title--sm-margin">
                                                <span><?= Yii::t('app/settings-and-profile', 'Price_per_hour') ?> <span class="required-label">*</span></span>
                                                <span class="tooltip-label js-has-tooltip"
                                                      data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Price_per_hour') ?>">
                                                    <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                </span>
                                            </div>

                                            <?= $form2->field($ProfileForm, 'user_price_peer_hour', [
                                                'template'=>'{input}<span class="unit">' . Functions::getInCurrency(1)['code'] . '</span>{error}{hint}',
                                                'options' => [
                                                    'tag' => 'div',
                                                    'class' => 'input-wrap input-wrap--unit input-wrap--fourth',
                                                ],
                                            ])
                                                ->textInput([
                                                    'placeholder' => '0.00',
                                                    'autocomplete' => "off",
                                                    'aria-label'   => Yii::t('app/settings-and-profile', 'Price_per_hour'),
                                                    'class' => "md-input text-right check-this-field",
                                                ])
                                                ->label(false)
                                            ?>

                                        </div>
                                        <div class="form-section">
                                            <div class="form-section__title form-section__title--sm-margin">
                                                <span><?= Yii::t('app/settings-and-profile', 'Detailed_information') ?> <span class="required-label">*</span></span>
                                                <span class="tooltip-label js-has-tooltip"
                                                      data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_user_additional_info') ?>">
                                                    <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                </span>
                                            </div>
                                            <?= $form2->field($ProfileForm, 'user_additional_info', [
                                                //'template'=>'{input}',
                                                //'template' => '{label}{input}{error}{hint}',
                                                'options' => [
                                                    //'tag' => false,
                                                ]
                                            ])
                                                ->textarea([
                                                    'placeholder' => Yii::t('app/settings-and-profile', 'Describe_here_please'),
                                                    'autocomplete' => "off",
                                                    'class' => "profile-user_additional_info check-this-field",
                                                    'aria-label'   => Yii::t('app/settings-and-profile', 'Detailed_information'),
                                                ])
                                                ->label(false)
                                            ?>
                                        </div>
                                        <div class="form-footer">
                                            <input type="hidden" name="tab" value="profile" />
                                            <button class="primary-btn wide-mob-btn teacher_settings_and_profile" type="submit" name="settings_and_profile"><?= Yii::t('app/settings-and-profile', 'Save') ?></button>
                                            <div class="form-footer__note required-notif">* <?= Yii::t('app/settings-and-profile', 'Required_field') ?></div>
                                        </div>
                                        <div class="own-page">
                                            <?= Yii::t('app/settings-and-profile', 'My_page_') ?> <a target="_blank" href="<?= Url::to(["tutor/{$CurrentUser->user_id}"], CREATE_ABSOLUTE_URL) ?>"><?= Url::to(["tutor/{$CurrentUser->user_id}"], CREATE_ABSOLUTE_URL) ?></a>
                                        </div>
                                    </div>
                                    <div class="settings-sidebar">
                                        <?php
                                        linkAccountWidget($Auth, false);
                                        ?>
                                        <div class="settings-sidebar-section">
                                            <div class="profile-photo required-photo">
                                                <div class="profile-photo__label"><?= Yii::t('app/settings-and-profile', 'Your_photo') ?> <span class="required-label">*</span></div>
                                                <img id="user-profile-photo-container-desktop"
                                                     class="profile-photo__ava any-place-user-ava"
                                                     src="<?= $ProfileForm->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png?v=1') ?>"
                                                     alt=""
                                                     role="presentation"/>
                                                <div class="profile-photo__tools">

                                                    <div class="photo-enabled" style="text-align: center; display: <?= $ProfileForm->user_photo ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="upload-profile-photo-modal"
                                                                class="profile-photo__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_photo') ?></button>
                                                        <br />
                                                        <button class="profile-photo__remove-btn text-btn"
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
                                        <div class="settings-sidebar-section" data-off-style="display: none;">
                                            <div class="profile-photo">
                                                <div class="profile-photo__label">
                                                    <?= Yii::t('app/settings-and-profile', 'Your_video_presentation') ?><span class="required-label">*</span>
                                                    <br />
                                                    <span class="tooltip-label js-has-tooltip"
                                                          data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Your_video_presentation') ?>">
                                                        <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                    </span>
                                                </div>
                                                <!--
                                                <div class="profile-photo__media">
                                                    <img class="profile-photo__video-screen" src="/assets/xsmart-min/files/video/screen_180x113.jpg" alt="" role="presentation" />
                                                    <div class="profile-photo__play video-play"></div>
                                                </div>
                                                -->
                                                <div class="profile-photo__tools">

                                                    <div class="video-enabled" style="text-align: center; display: <?= $ProfileForm->user_local_video ? 'inline-block' : 'none' ?>">
                                                        <button data-modal-id="view-my-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'View_video') ?></button>
                                                        <br />
                                                        <button data-modal-id="upload-profile-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Change_video') ?></button>
                                                        <br />
                                                        <button class="profile-video__remove-btn text-btn"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Delete') ?></button>
                                                    </div>

                                                    <div class="video-disabled" style="text-align: center; display: <?= $ProfileForm->user_local_video ? 'none' : 'inline-block' ?>">
                                                        <button data-modal-id="upload-profile-video-modal"
                                                                class="profile-video__add-btn secondary-btn sm-btn js-open-modal"
                                                                type="button"><?= Yii::t('app/settings-and-profile', 'Upload_video') ?></button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="settings-sidebar-section-or"><?= Yii::t('app/settings-and-profile', 'or') ?></div>

                                        <div class="settings-sidebar-section">
                                            <div class="profile-photo">
                                                <div class="profile-photo__label">
                                                    <?= Yii::t('app/settings-and-profile', 'Youtube_video') ?><span class="required-label">*</span>
                                                    <br />
                                                    <span class="tooltip-label js-has-tooltip"
                                                          data-tooltip="<?= Yii::t('app/settings-and-profile', 'info_Your_video_presentation') ?>">
                                                        <svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg>
                                                    </span>
                                                </div>
                                                <div class="profile-photo_">
                                                    <?= $form2->field($ProfileForm, 'user_youtube_video')
                                                        ->textInput([
                                                            'id' => 'id2-user_youtube_video',
                                                            'placeholder' => Yii::t('app/settings-and-profile', 'Youtube_link'),
                                                            'autocomplete' => "off",
                                                            'aria-label'   => Yii::t('app/settings-and-profile', 'Youtube_link'),
                                                            'class' => "md-input user-youtube-video",
                                                        ])
                                                        ->label(false)
                                                    ?>
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
<?php
if ($ProfileForm->user_type == Users::TYPE_TEACHER) {
    echo $this->render("../modals/upload-profile-video-modal");
    echo $this->render("../modals/view-my-video-modal", ['CurrentUser' => $CurrentUser]);
}
?>
