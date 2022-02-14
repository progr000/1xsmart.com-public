<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $modelFormFillsListSearch \frontend\models\admin\FormFillsListSearch */
/** @var $dataProviderFormFillsListSearch \yii\data\ActiveDataProvider */

use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;
use common\models\Users;
use frontend\models\forms\ProfileForm;
use frontend\assets\xsmart\admin\UserManageAsset;
use frontend\assets\xsmart\profileAsset;

UserManageAsset::register($this);
profileAsset::register($this);

$ProfileStudent = new ProfileForm(['formName' => 'ProfileStudent']);
$ProfileTeacher = new ProfileForm(['formName' => 'ProfileTeacher']);
?>

<!-- begin add-edit-student-popup -->
<a id="trigger-open-add-edit-student-popup"
   class="js-open-modal void-0"
   href="#"
   data-modal-id="add-edit-student-popup"
   style="display: none">open</a>
<div class="modal modal--light modal modal--lg-wide nested-modal-available -_opened" id="add-edit-student-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title action-add _hidden">Add student</div>
            <div class="modal__title action-edit _hidden">Edit student</div>
            <?php $form2 = ActiveForm::begin([
                'id' => 'form-profile-add-edit-student',
                'action'=>['admin/save-user-data'],
                'options' => [
                    'class'    => "profile-frm",
                    'onsubmit' => "return false;"
                ],
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'fieldConfig' => [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'input-wrap',
                    ],
                    'template' => '{label}{input}{error}{hint}',
                ]
            ]); ?>
                <input type="hidden" class="collect-input" name="ProfileStudent[user_id]" id="profilestudent-user_id" value="">
                <div class="settings-wrap">
                    <div class="settings-main">
                        <div class="form-section">

                            <div class="form-section__title">General information</div>

                            <div class="input-row">
                                <?= $form2->field($ProfileStudent, 'user_first_name')
                                    ->textInput([
                                        'placeholder' => 'Enter',
                                        'autocomplete' => "off",
                                        'aria-label'   => $ProfileStudent->getAttributeLabel('Name'),
                                        'class' => "md-input collect-input",
                                    ])
                                    ->label('Name')
                                ?>
                                <?= $form2->field($ProfileStudent, 'user_email', [
                                    'enableAjaxValidation' => true,
                                ])
                                    ->textInput([
                                        'placeholder' => 'Enter',
                                        'autocomplete' => "off",
                                        'aria-label'   => $ProfileStudent->getAttributeLabel('user_email'),
                                        'class' => "md-input collect-input",
                                    ])
                                    ->label('Email')
                                ?>
                            </div>

                            <div class="input-row">
                                <?= $form2->field($ProfileStudent, 'user_phone')
                                    ->textInput([
                                        'placeholder' => 'Enter',
                                        'autocomplete' => "off",
                                        'aria-label'   => $ProfileStudent->getAttributeLabel('user_phone'),
                                        'class' => "md-input collect-input",
                                    ])
                                    ->label('Phone')
                                ?>
                                <?= $form2->field($ProfileStudent, '_user_skype')
                                    ->textInput([
                                        'placeholder' => 'Enter',
                                        'autocomplete' => "off",
                                        'aria-label'   => $ProfileStudent->getAttributeLabel('_user_skype'),
                                        'class' => "md-input collect-input",
                                    ])
                                    ->label('Skype')
                                ?>
                            </div>

                            <div class="input-row">
                                <?= $form2->field($ProfileStudent, 'password')
                                    ->passwordInput([
                                        'placeholder' => 'Enter',
                                        'autocomplete' => "off",
                                        'aria-label'   => 'new_password',
                                        //'value' => 'password',
                                        'class' => "md-input collect-input",
                                    ])
                                    ->label('Password')
                                ?>
                            </div>

                            <div class="input-row">
                                <div class="input-wrap">
                                    <label for="profile-bday">Date of Birth</label>
                                    <div class="input-row input-row--3col">

                                        <?php
                                        echo $form2->field($ProfileStudent, 'user_birthday_day', [
                                            'template'=>'<div class="input-wrap">{input}</div>',
                                            'options' => [
                                                'tag' => false,
                                                //'class' => 'birthday-field',
                                            ],
                                        ])->dropDownList(Functions::get_days(), [
                                            //'id'         => "profile-bday",
                                            'class'      => "collect-input lg-select -js-select js-day-select js-select-my-height",
                                            'aria-label' => $ProfileStudent->getAttributeLabel('user_birthday_day'),
                                            'data-placeholder' => "Day",
                                        ])->label(false);
                                        ?>

                                        <?php
                                        echo $form2->field($ProfileStudent, 'user_birthday_month', [
                                            'template'=>'<div class="input-wrap">{input}</div>',
                                            'options' => [
                                                'tag' => false,
                                                //'class' => 'birthday-field',
                                            ],
                                        ])->dropDownList(Functions::get_months(), [
                                            //'id'         => "profile-bmonth",
                                            'class'      => "collect-input lg-select -js-select js-month-select js-select-my-height",
                                            'aria-label' => $ProfileStudent->getAttributeLabel('user_birthday_month'),
                                            'data-placeholder' => "Month",
                                        ])->label(false);
                                        ?>

                                        <?php
                                        echo $form2->field($ProfileStudent, 'user_birthday_year', [
                                            'template'=>'<div class="input-wrap">{input}</div>',
                                            'options' => [
                                                'tag' => false,
                                                //'class' => 'birthday-field',
                                            ],
                                        ])->dropDownList(Functions::get_years(), [
                                            //'id'         => "profile-byear",
                                            'class'      => "collect-input lg-select -js-select js-year-select js-select-my-height",
                                            'aria-label' => $ProfileStudent->getAttributeLabel('user_birthday_day'),
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
                                            <input id="student-sex-1"
                                                   class="collect-input accent-radio profilestudent-radio-user_gender_code profilestudent-user_gender_code-<?= Users::GENDER_MALE ?>"
                                                   type="radio"
                                                   name="ProfileStudent[user_gender]"
                                                   value="<?= Users::GENDER_MALE ?>"
                                                <?= $ProfileStudent->user_gender === Users::GENDER_MALE ? 'checked="checked"' : '' ?> />
                                            <label for="student-sex-1"><span></span><span>Male</span></label>
                                        </div>
                                        <div class="check-wrap">
                                            <input id="student-sex-2"
                                                   class="collect-input accent-radio profilestudent-radio-user_gender_code profilestudent-user_gender_code-<?= Users::GENDER_FEMALE ?>"
                                                   type="radio"
                                                   name="ProfileStudent[user_gender]"
                                                   value="<?= Users::GENDER_FEMALE ?>"
                                                <?= $ProfileStudent->user_gender === Users::GENDER_FEMALE ? 'checked="checked"' : '' ?> />
                                            <label for="student-sex-2"><span></span><span>Female</span></label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="profile-photo action-add _hidden">
                                <div class="profile-photo__label">Photo</div>
                                <div class="profile-photo__tools">
                                    save the user first<br />
                                    to add his photo
                                </div>
                            </div>
                            <div class="profile-photo action-edit _hidden">
                                <div class="profile-photo__label">Photo</div>
                                <img class="profilestudent-user_photo profile-photo__ava-1 profile-photo__ava managed-ava-user_photo"
                                     data-upload_your_photo="/assets/xsmart-min/images/upload_your_photo.png"
                                     data-user-id=""
                                     src="/assets/xsmart-min/images/upload_your_photo.png"
                                     alt=""
                                     role="presentation" />
                                <div class="profile-photo__tools">

                                    <div class="photo-enabled" style="text-align: center; display: none;">
                                        <button data-modal-id="upload-profile-photo-modal"
                                                class="profile-photo__add-btn secondary-btn sm-btn js-open-modal"
                                                type="button">Change photo</button>
                                        <br />
                                        <button class="profile-photo__remove-btn text-btn"
                                                type="button">Delete</button>
                                    </div>

                                    <div class="photo-disabled" style="text-align: center; display: inline-block;">
                                        <button data-modal-id="upload-profile-photo-modal"
                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                type="button">Upload photo</button>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-section">

                            <div class="form-section__title">Language</div>

                            <div class="input-row input-row--lg-margin">
                                <div class="input-wrap">
                                    <label for="lng-1">Native</label>
                                    <div class="check-grid">
                                        <?php
                                        $_user_are_native = unserialize($ProfileStudent->user_are_native);
                                        foreach (Users::$_languages as $key => $item) {
                                            ?>
                                            <div class="check-wrap">
                                                <input id="radio-user-are-native-<?= $item ?>"
                                                       class="collect-input accent-checkbox user-are-native"
                                                       data-lng="<?= $item ?>"
                                                       name="ProfileStudent[_user_are_native][<?= $item ?>]"
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
                                    <label for="lng-21">Speak also</label>

                                    <div class="check-grid">
                                        <?php
                                        $_user_speak_also = unserialize($ProfileStudent->user_speak_also);
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
                                                       class="collect-input accent-checkbox js-has-related user-speak-also"
                                                       name="ProfileStudent[_user_speak_also][<?= $item ?>]"
                                                       value="<?= $value ?>"
                                                    <?= $checked ?>
                                                    <?= $disabled ?>
                                                       type="checkbox" />
                                                <label for="radio-user-speak-also-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                                <div id="div-select-user-speak-also-<?= $item ?>"
                                                     class="div-select-user-speak-also related js-related <?= $visible ?> ">
                                                    <select id="select-user-speak-also-<?= $item ?>"
                                                            name="__ProfileStudent[_user_speak_also_select][<?= $item ?>]"
                                                            class="lng-level-select sm-select -js-select"
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

                            <div class="form-section__title form-section__title--sm-margin">Goals of education</div>

                            <div class="input-row input-row--lg-margin">
                                <div class="input-wrap">

                                    <div class="check-grid check-grid--3col">

                                        <?php
                                        $_user_goals_of_education = unserialize($ProfileStudent->user_goals_of_education);
                                        foreach (Users::$_goals_of_education as $key => $item) {
                                            ?>
                                            <div class="check-wrap">
                                                <input id="radio-user-goals-of-education-<?= $item ?>"
                                                       class="collect-input accent-checkbox user-goals-of-education"
                                                       data-goal="<?= $item ?>"
                                                       name="ProfileStudent[_user_goals_of_education][<?= $item ?>]"
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

                            <div class="form-section__title form-section__title--sm-margin">Additional information</div>

                            <?= $form2->field($ProfileStudent, 'user_additional_info', [
                                //'template'=>'{input}',
                                //'template' => '{label}{input}{error}{hint}',
                                'options' => [
                                    //'tag' => false,
                                ]
                            ])
                                ->textarea([
                                    'placeholder' => $ProfileStudent->getAttributeLabel('Describe here please'),
                                    'autocomplete' => "off",
                                    'class' => "collect-input profile-user_additional_info",
                                    'aria-label'   => $ProfileStudent->getAttributeLabel('user_additional_info'),
                                ])
                                ->label(false)
                            ?>

                        </div>

                    </div>

                    <div class="settings-sidebar">
                        <div class="settings-sidebar-section">

                            <div class="profile-photo action-add _hidden">
                                <div class="profile-photo__label">Photo</div>
                                <div class="profile-photo__tools">
                                    save the user first<br />
                                    to add his photo
                                </div>
                            </div>
                            <div class="profile-photo action-edit _hidden">
                                <div class="profile-photo__label">Photo</div>
                                <img class="profilestudent-user_photo profile-photo__ava-2 profile-photo__ava managed-ava-user_photo"
                                     data-upload_your_photo="/assets/xsmart-min/images/upload_your_photo.png"
                                     data-user-id=""
                                     src="/assets/xsmart-min/images/upload_your_photo.png"
                                     alt=""
                                     role="presentation" />
                                <div class="profile-photo__tools">

                                    <div class="photo-enabled" style="text-align: center; display: none; ">
                                        <button data-modal-id="upload-profile-photo-modal"
                                                class="profile-photo__add-btn secondary-btn sm-btn js-open-modal"
                                                type="button">Change photo</button>
                                        <br />
                                        <button class="profile-photo__remove-btn text-btn"
                                                type="button">Delete</button>
                                    </div>

                                    <div class="photo-disabled" style="text-align: center; display: inline-block;">
                                        <button data-modal-id="upload-profile-photo-modal"
                                                class="profile-photo__add-btn secondary-btn sm-btn profile-photo__add-btn js-open-modal"
                                                type="button">Upload photo</button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal__submit">
                    <button class="primary-btn wide-mob-btn save-student-data action-edit _hidden"
                            type="submit"
                            data-add-text="Add"
                            data-edit-text="Change">Change</button>
                    <button class="primary-btn wide-mob-btn save-student-data action-add _hidden"
                            type="submit"
                            data-add-text="Add"
                            data-edit-text="Change">Add</button>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end add-edit-student-popup -->

<!-- begin add-edit-teacher-popup -->
<a id="trigger-open-add-edit-teacher-popup"
   class="js-open-modal void-0"
   href="#"
   data-modal-id="add-edit-teacher-popup"
   style="display: none">open</a>
<div class="modal modal--light modal modal--lg-wide nested-modal-available -_opened" id="add-edit-teacher-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title">Add Teacher</div>
            <form action="/">
                <div class="settings-wrap">
                    <div class="settings-main">
                        <div class="form-section">
                            <div class="input-row">
                                <div class="input-wrap"><label class="lg-label" for="name">Like to teach <span class="required-label">*</span></label><select class="lg-select js-select" data-placeholder="Choose">
                                        <option value="" data-placeholder="true"></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select></div>
                                <div class="input-wrap"><label class="lg-label" for="gender-1">Can teach children <span class="required-label">*</span></label>
                                    <div class="check-grid check-grid--3col">
                                        <div class="check-wrap"><input class="accent-radio" type="radio" name="children" checked id="children-1"><label for="children-1"><span></span><span>Yes</span></label></div>
                                        <div class="check-wrap"><input class="accent-radio" type="radio" name="children" id="children-2"><label for="children-2"><span></span><span>No</span></label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-section__title">General information</div>
                            <div class="input-row">
                                <div class="input-wrap"><label for="name4">Name <span class="required-label">*</span></label><input class="md-input" type="text" value="Andrey" placeholder="Enter" id="name4"></div>
                                <div class="input-wrap"><label for="email4">Email <span class="required-label">*</span></label><input class="md-input" type="email" placeholder="Enter" id="email4"></div>
                            </div>
                            <div class="input-row">
                                <div class="input-wrap"><label for="phone4">Phone</label><input class="md-input" type="text" placeholder="Enter" id="phone4"></div>
                                <div class="input-wrap"><label for="skype4">Skype</label><input class="md-input" type="text" placeholder="Enter" id="skype4"></div>
                            </div>
                            <div class="input-row">
                                <div class="input-wrap"><label for="password4">Password</label><input class="md-input" type="password" placeholder="Enter" id="password4"></div>
                            </div>
                            <div class="input-row">
                                <div class="input-wrap"><label for="skype">Date of Birth</label>
                                    <div class="input-row input-row--3col">
                                        <div class="input-wrap"><select class="lg-select js-select" data-placeholder="Day">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="0">1</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                                <option value="5">6</option>
                                                <option value="6">7</option>
                                                <option value="7">8</option>
                                                <option value="8">9</option>
                                                <option value="9">10</option>
                                                <option value="10">11</option>
                                                <option value="11">12</option>
                                                <option value="12">13</option>
                                                <option value="13">14</option>
                                                <option value="14">15</option>
                                                <option value="15">16</option>
                                                <option value="16">17</option>
                                                <option value="17">18</option>
                                                <option value="18">19</option>
                                                <option value="19">20</option>
                                                <option value="20">21</option>
                                                <option value="21">22</option>
                                                <option value="22">23</option>
                                                <option value="23">24</option>
                                                <option value="24">25</option>
                                                <option value="25">26</option>
                                                <option value="26">27</option>
                                                <option value="27">28</option>
                                                <option value="28">29</option>
                                                <option value="29">30</option>
                                                <option value="30">31</option>
                                            </select></div>
                                        <div class="input-wrap"><select class="lg-select js-select" data-placeholder="Month">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="0">1</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option value="4">5</option>
                                                <option value="5">6</option>
                                                <option value="6">7</option>
                                                <option value="7">8</option>
                                                <option value="8">9</option>
                                                <option value="9">10</option>
                                                <option value="10">11</option>
                                                <option value="11">12</option>
                                            </select></div>
                                        <div class="input-wrap"><select class="lg-select js-select" data-placeholder="Year">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="1920">1921</option>
                                                <option value="1921">1922</option>
                                                <option value="1922">1923</option>
                                                <option value="1923">1924</option>
                                                <option value="1924">1925</option>
                                                <option value="1925">1926</option>
                                                <option value="1926">1927</option>
                                                <option value="1927">1928</option>
                                                <option value="1928">1929</option>
                                                <option value="1929">1930</option>
                                                <option value="1930">1931</option>
                                                <option value="1931">1932</option>
                                                <option value="1932">1933</option>
                                                <option value="1933">1934</option>
                                                <option value="1934">1935</option>
                                                <option value="1935">1936</option>
                                                <option value="1936">1937</option>
                                                <option value="1937">1938</option>
                                                <option value="1938">1939</option>
                                                <option value="1939">1940</option>
                                                <option value="1940">1941</option>
                                                <option value="1941">1942</option>
                                                <option value="1942">1943</option>
                                                <option value="1943">1944</option>
                                                <option value="1944">1945</option>
                                                <option value="1945">1946</option>
                                                <option value="1946">1947</option>
                                                <option value="1947">1948</option>
                                                <option value="1948">1949</option>
                                                <option value="1949">1950</option>
                                                <option value="1950">1951</option>
                                                <option value="1951">1952</option>
                                                <option value="1952">1953</option>
                                                <option value="1953">1954</option>
                                                <option value="1954">1955</option>
                                                <option value="1955">1956</option>
                                                <option value="1956">1957</option>
                                                <option value="1957">1958</option>
                                                <option value="1958">1959</option>
                                                <option value="1959">1960</option>
                                                <option value="1960">1961</option>
                                                <option value="1961">1962</option>
                                                <option value="1962">1963</option>
                                                <option value="1963">1964</option>
                                                <option value="1964">1965</option>
                                                <option value="1965">1966</option>
                                                <option value="1966">1967</option>
                                                <option value="1967">1968</option>
                                                <option value="1968">1969</option>
                                                <option value="1969">1970</option>
                                                <option value="1970">1971</option>
                                                <option value="1971">1972</option>
                                                <option value="1972">1973</option>
                                                <option value="1973">1974</option>
                                                <option value="1974">1975</option>
                                                <option value="1975">1976</option>
                                                <option value="1976">1977</option>
                                                <option value="1977">1978</option>
                                                <option value="1978">1979</option>
                                                <option value="1979">1980</option>
                                                <option value="1980">1981</option>
                                                <option value="1981">1982</option>
                                                <option value="1982">1983</option>
                                                <option value="1983">1984</option>
                                                <option value="1984">1985</option>
                                                <option value="1985">1986</option>
                                                <option value="1986">1987</option>
                                                <option value="1987">1988</option>
                                                <option value="1988">1989</option>
                                                <option value="1989">1990</option>
                                                <option value="1990">1991</option>
                                                <option value="1991">1992</option>
                                                <option value="1992">1993</option>
                                                <option value="1993">1994</option>
                                                <option value="1994">1995</option>
                                                <option value="1995">1996</option>
                                                <option value="1996">1997</option>
                                                <option value="1997">1998</option>
                                                <option value="1998">1999</option>
                                                <option value="1999">2000</option>
                                                <option value="2000">2001</option>
                                                <option value="2001">2002</option>
                                                <option value="2002">2003</option>
                                                <option value="2003">2004</option>
                                                <option value="2004">2005</option>
                                                <option value="2005">2006</option>
                                                <option value="2006">2007</option>
                                                <option value="2007">2008</option>
                                                <option value="2008">2009</option>
                                                <option value="2009">2010</option>
                                                <option value="2010">2011</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-row">
                                <div class="input-wrap"><label for="name">Phisically in</label>
                                    <div class="cmplx-input cmplx-input--lg has-overlay-input js-cmplx"><input class="cmplx-input__input js-cmplx-input" type="text" value="Choose" readonly="readonly" data-default-value="Choose" id="location-input4" />
                                        <div class="cmplx-input__dropdown js-cmplx-dropdown"><input type="text" placeholder="Search"><select class="js-select-deselect js-cmplx-data" data-placeholder="Country">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="1">USA</option>
                                                <option value="2">Poland</option>
                                                <option value="3">Russia</option>
                                                <option value="4">Germany</option>
                                                <option value="5">France</option>
                                            </select><select class="js-select-deselect js-cmplx-data" data-placeholder="City">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="11">Moscow</option>
                                                <option value="12">Saratov</option>
                                                <option value="13">Krasnodar</option>
                                                <option value="14">Nalchik</option>
                                                <option value="15">Sochi</option>
                                            </select><select class="js-select-deselect js-cmplx-data" data-placeholder="District of the city">
                                                <option value="" data-placeholder="true"></option>
                                                <option value="23">District 1</option>
                                                <option value="24">District 2</option>
                                                <option value="25">District 3</option>
                                                <option value="26">District 4</option>
                                                <option value="27">District 5</option>
                                            </select><button class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button">Choose</button></div>
                                    </div>
                                </div>
                                <div class="input-wrap"><label for="gender-1">Gender</label>
                                    <div class="check-grid check-grid--3col">
                                        <div class="check-wrap"><input class="accent-radio" type="radio" name="gender" checked id="gender-14"><label for="gender-14"><span></span><span>Male</span></label></div>
                                        <div class="check-wrap"><input class="accent-radio" type="radio" name="gender" id="gender-24"><label for="gender-24"><span></span><span>Female</span></label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-media">
                                <div class="profile-photo">
                                    <div class="profile-photo__label">Photo</div>
                                    <div class="profile-photo__tools"><button class="profile-photo__add-btn secondary-btn sm-btn" type="button">Upload</button></div>
                                </div>
                                <div class="profile-photo">
                                    <div class="profile-photo__label">Video presentation <span class="required-label">*</span></div>
                                    <div class="profile-photo__tools"><button class="profile-photo__add-btn secondary-btn sm-btn" type="button">Upload</button></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-section__title">Language</div>
                            <div class="input-row input-row--lg-margin">
                                <div class="input-wrap"><label for="lng-14">My Native <span class="required-label">*</span></label>
                                    <div class="check-grid">
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-14" id="lng-14" checked="checked" /><label for="lng-14"><span></span><span>English</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-24" id="lng-24" /><label for="lng-24"><span></span><span>Spanish</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-34" id="lng-34" /><label for="lng-34"><span></span><span>Russian</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-44" id="lng-44" /><label for="lng-44"><span></span><span>Ukrainian</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-54" id="lng-54" /><label for="lng-54"><span></span><span>French</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-64" id="lng-64" /><label for="lng-64"><span></span><span>Arabic</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-74" id="lng-74" /><label for="lng-74"><span></span><span>Portuguese</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-84" id="lng-84" /><label for="lng-84"><span></span><span>German</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-94" id="lng-94" /><label for="lng-94"><span></span><span>Chinese</span></label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-row input-row--lg-margin">
                                <div class="input-wrap"><label for="lng-214">I speak also</label>
                                    <div class="check-grid">
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="lng-21" id="lng-214" checked disabled><label for="lng-214"><span></span><span>English</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-224" id="lng-224" checked="checked" /><label for="lng-224"><span></span><span>Spanish</span></label>
                                            <div class="related js-related _visible"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-234" id="lng-234" /><label for="lng-234"><span></span><span>Russian</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-244" id="lng-244" checked="checked" /><label for="lng-244"><span></span><span>Ukrainian</span></label>
                                            <div class="related js-related _visible"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-254" id="lng-254" /><label for="lng-254"><span></span><span>French</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-264" id="lng-264" /><label for="lng-264"><span></span><span>Arabic</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-274" id="lng-274" /><label for="lng-274"><span></span><span>Portuguese</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-284" id="lng-284" /><label for="lng-284"><span></span><span>German</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                        <div class="check-wrap"><input class="accent-checkbox js-has-related" type="checkbox" name="lng-294" id="lng-294" /><label for="lng-294"><span></span><span>Chinese</span></label>
                                            <div class="related js-related"><select class="lng-level-select sm-select js-select">
                                                    <option value="A1">A1</option>
                                                    <option value="A2">A2</option>
                                                    <option value="B1">B1</option>
                                                    <option value="B2">B2</option>
                                                    <option value="C1">C1</option>
                                                    <option value="C2">C2</option>
                                                </select></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-section__title form-section__title--sm-margin"><span>Specialisation</span><span class="tooltip-label js-has-tooltip" data-tooltip="Specify few fields where you can teach students most efficiently"><svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg></span></div>
                            <div class="input-row input-row--lg-margin">
                                <div class="input-wrap">
                                    <div class="check-grid check-grid--3col">
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-14" id="goal-14-1" /><label for="goal-14"><span></span><span>Business language</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-24" id="goal-24-2" checked="checked" /><label for="goal-24"><span></span><span>Conversational language</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-34" id="goal-34-3" /><label for="goal-34"><span></span><span>Language for Traveling</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-44" id="goal-44-4" checked="checked" /><label for="goal-44"><span></span><span>Language for Beginners</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-54" id="goal-54-5" /><label for="goal-54"><span></span><span>Relocation to other country</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-74" id="goal-74-6" checked="checked" /><label for="goal-74"><span></span><span>IELTS</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-84" id="goal-84-7" /><label for="goal-84"><span></span><span>TOEFL</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-94" id="goal-94-8" /><label for="goal-94"><span></span><span>DELF</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-94" id="goal-94-9" /><label for="goal-94"><span></span><span>DALF</span></label></div>
                                        <div class="check-wrap"><input class="accent-checkbox" type="checkbox" name="goal-94" id="goal-94-0" /><label for="goal-94"><span></span><span>Other</span></label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-section__title form-section__title--sm-margin"><span>Price per hour <span class="required-label">*</span></span><span class="tooltip-label js-has-tooltip" data-tooltip="Set price you would like to be appointed to a student"><svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg></span></div>
                            <div class="input-wrap input-wrap--unit input-wrap--fourth"><input class="md-input text-right" type="text" value="15" placeholder="0"><span class="unit">$</span></div>
                        </div>
                        <div class="form-section">
                            <div class="form-section__title form-section__title--sm-margin"><span>Detailed information <span class="required-label">*</span></span><span class="tooltip-label js-has-tooltip" data-tooltip="List your strengths here, such as ease of communication or experience in teaching business vocabulary. Or maybe you can teach children with pleasure. It should be easy and understandable for a potential student to read this description."><svg class="svg-icon-info svg-icon" width="3" height="12"><use xlink:href="#info"></use></svg></span></div><textarea placeholder="Describe here please"></textarea>
                        </div>
                        <div class="form-footer"><button class="primary-btn wide-mob-btn" type="submit">Add</button>
                            <div class="form-footer__note">* Required field</div>
                        </div>
                    </div>
                    <div class="settings-sidebar">
                        <div class="settings-sidebar-section">
                            <div class="profile-photo">
                                <div class="profile-photo__label">Photo <span class="required-label">*</span></div>
                                <div class="profile-photo__tools"><button class="profile-photo__add-btn secondary-btn sm-btn" type="button">Upload</button></div>
                            </div>
                        </div>
                        <div class="settings-sidebar-section">
                            <div class="profile-photo">
                                <div class="profile-photo__label">Video presentation <span class="required-label">*</span></div>
                                <div class="profile-photo__tools"><button class="profile-photo__add-btn secondary-btn sm-btn" type="button">Upload</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end add-edit-teacher-popup -->

<!-- begin teacher-info--popup -->
<div class="modal modal--light modal modal--lg-wide" id="user-info--popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title">
                <div class="user-header">
                    <img class="user-header__ava"
                         id="user_photo"
                         src=""
                         alt=""
                         role="presentation" />
                    <span id="_user_display_name">null</span>
                    <a class="user-header__approve-btn primary-btn primary-btn primary-btn--accent sm-btn"
                       id="button-approve-user"
                       data-user_id=""
                       type="button">Approve</a>
                </div>
                <div class="-tutor-info__location location location location--lg">
                    <img src="/assets/xsmart-min/images/flags/undefined.svg" id="user_country_flag" alt="">
                    <span id="_user_location">null, null</span>
                </div>
            </div>
            <div class="tabs-wrap popup-profile">
                <div class="tabs tabs--btns tabs tabs--bg tabs tabs--nowrap js-tabs">
                    <div class="tabs__item js-tabs-item _current" id="tabs__item_personal">Personal information</div>
                    <div class="tabs__item js-tabs-item">Technical information</div>
                </div>
                <div class="tabs-content">
                    <div class="box _visible">
                        <div class="params-tbl params-tbl--columns params-tbl--sm">
                            <div class="params-tbl__row"><span>First Name:</span><span id="user_first_name">null</span></div>
                            <div class="params-tbl__row"><span>Last Name:</span><span id="user_last_name">null</span></div>
                            <div class="params-tbl__row"><span>Phone:</span><span id="user_phone">null</span></div>
                            <div class="params-tbl__row"><span>Email:</span><span id="user_email">null</span></div>
                            <div class="params-tbl__row"><span>Skype:</span><span id="_user_skype">null</span></div>
                            <div class="params-tbl__row"><span>Gender:</span><span id="user_gender">null</span></div>
                            <div class="params-tbl__row"><span>Date of Birth:</span><span id="user_birthday">null</span></div>
                            <div class="params-tbl__row"><span>Discipline:</span><span id="user_discipline">null</span></div>
                            <div class="params-tbl__row"><span>Native languages:</span><span id="user_are_native">null</span></div>
                            <div class="params-tbl__row"><span>Speak also:</span><span id="user_speak_also">null</span></div>
                            <div class="params-tbl__row"><span>Specialisation:</span><span id="user_goals_of_education">null</span></div>
                            <div class="params-tbl__row"><span>Price:</span><span id="user_price_peer_hour">null</span></div>
                        </div>
                        <div class="popup-profile-desc ww js-hidden-wrap" data-limit="2" data-mob-limit="2" id="user_additional_info">
                            <p>null</p>
                        </div>
                        <div class="schedule-wrap">
                            <!--
                            <div class="schedule-notice">
                                <div>Schedule:</div>
                                <div>
                                    <div>Monday: 08:00;</div>
                                    <div>Wednesday: 08:00;</div>
                                </div>
                            </div>
                            -->
                            <!--
                            <div class="schedule-change-tools js-schedule-tools">
                                <div class="schedule-change-info">
                                    <p>You are gong to change the lesson time with tutor _name of tutor_</p>
                                    <p>Choose new available time.</p>
                                </div>
                                <div class="schedule-change-type check-row">
                                    <div class="check-wrap"><input type="radio" name="change-type" checked id="change-type-122"><label for="change-type-122"><span></span><span>Change once</span></label></div>
                                    <div class="check-wrap"><input type="radio" name="change-type" id="change-type-222"><label for="change-type-222"><span></span><span>Change permanent</span></label></div>
                                </div>
                            </div>
                            -->
                            <div class="schedule-top-title">Teacher schedule:</div>


                            <div class="schedule js-schedule" id="user_schedule">
                                <?php
                                //                                /**/
                                //                                $scheduleModel = new TeachersScheduleForm();
                                //                                $scheduleModel->load([$scheduleModel->formName() => [
                                //                                    'user_id'       => 63,
                                //                                    'user_type'     => Users::TYPE_TEACHER,
                                //                                    'user_timezone' => $CurrentUser->user_timezone,
                                //                                ]]);
                                //                                echo $this->render('teacher-schedule-part', [
                                //                                    'teacher_user_id' => 63,
                                //                                    'CurrentUser' => $CurrentUser,
                                //                                    'DashboardSchedule_v2' => $scheduleModel->getScheduleForTwoWeekByDate($CurrentUser->_user_local_time, $CurrentUser->user_timezone, true)
                                //                                ]);
                                ?>
                            </div>
                            <!--.schedule-note Click on the lesson to select new lesson time with certain tutor. Please note you can change the lesson time not later than 5 hours before the planned time.-->
                        </div>
                    </div>
                    <div class="box">
                        <div class="params-tbl params-tbl--columns params-tbl--sm full_additional_server_info" id="full_additional_server_info">
                            <div class="params-tbl__row"><span>Http referer:</span><span>null</span></div>
                            <div class="params-tbl__row"><span>User agent:</span><span>null</span></div>
                            <div class="params-tbl__row"><span>IP address:</span><span>null</span></div>
                            <div class="params-tbl__row"><span>Students:</span><span>null</span></div>
                            <div class="params-tbl__row"><span>Payment system used:</span><span>null</span></div>
                            <div class="params-tbl__row"><span>Count of hours available:</span><span>null</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end teacher-info--popup -->

<?php if (isset($modelFormFillsListSearch, $dataProviderFormFillsListSearch)) { ?>
<!-- begin teacher-info--popup -->
<div class="modal modal--light modal modal--lg-wide" id="form-fills-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title">Form fills</div>

            <?php Pjax::begin([
                'id' => 'ff-list-content',
                'timeout' => PJAX_TIMEOUT,
                'options'=> ['tag' => 'div', 'class' => '']
            ]); ?>

            <!--
            <div class="reviews">
                <div class="review">
                    <div class="review__top">
                        <div class="review__name">Jana S.</div>
                        <div class="review__phone"><svg class="svg-icon-phone svg-icon" width="10" height="14">
                                <use xlink:href="#phone"></use>
                            </svg>+7 887 13 41 566</div>
                        <div class="review__date">22/04/2021 11:00</div><button class="review__close-btn" type="button"><svg class="svg-icon-close svg-icon" width="14" height="14">
                                <use xlink:href="#close"></use>
                            </svg></button>
                    </div>
                    <div class="review__body">
                        <p>Personal English teacher. For children, schoolchildren and adults. Classes are possible in Moscow and the region.</p>
                        <p>I teach English at any level, from Beginner to Advanced. I prepare for exams (OGE, USE, IELTS, etc.). I work with schoolchildren, applicants, university students (including language ones) with adults, classes in groups are possible.</p><button class="toggle-text-btn js-toggle-hidden-text" type="button">Show all text</button>
                        <div class="hidden-content">
                            <p>I am a certified Canadian ESOL instructor with over 10 years of experience. I am also an IELTS specialist, educational consultant, singer, actor and freelancer who was based out of Japan for 8 years.</p>
                            <p>I have many years of experience teaching both children and adults, and have taught in private language schools, universities, corporate businesses, and online with companies in Japan, South Korea, China, Israel, and Saudi Arabia.</p>
                        </div>
                    </div>
                </div>
                <div class="review">
                    <div class="review__top">
                        <div class="review__name">Pol D.</div>
                        <div class="review__phone"><svg class="svg-icon-phone svg-icon" width="10" height="14">
                                <use xlink:href="#phone"></use>
                            </svg>+3 432 13 41 521</div>
                        <div class="review__date">14/03/2021 07:12</div><button class="review__close-btn" type="button"><svg class="svg-icon-close svg-icon" width="14" height="14">
                                <use xlink:href="#close"></use>
                            </svg></button>
                    </div>
                    <div class="review__body">
                        <p>Personal English teacher. For children, schoolchildren and adults. Classes are possible in Moscow and the region.</p>
                        <p>I teach English at any level, from Beginner to Advanced. I prepare for exams (OGE, USE, IELTS, etc.). I work with schoolchildren, applicants, university students (including language ones) with adults, classes in groups are possible.</p><button class="toggle-text-btn js-toggle-hidden-text" type="button">Show all text</button>
                        <div class="hidden-content">
                            <p>I am a certified Canadian ESOL instructor with over 10 years of experience. I am also an IELTS specialist, educational consultant, singer, actor and freelancer who was based out of Japan for 8 years.</p>
                            <p>I have many years of experience teaching both children and adults, and have taught in private language schools, universities, corporate businesses, and online with companies in Japan, South Korea, China, Israel, and Saudi Arabia.</p>
                        </div>
                    </div>
                </div>
                <div class="review">
                    <div class="review__top">
                        <div class="review__name">Jana S.</div>
                        <div class="review__phone"><svg class="svg-icon-phone svg-icon" width="10" height="14">
                                <use xlink:href="#phone"></use>
                            </svg>+7 887 13 41 566</div>
                        <div class="review__date">22/04/2021 11:00</div><button class="review__close-btn" type="button"><svg class="svg-icon-close svg-icon" width="14" height="14">
                                <use xlink:href="#close"></use>
                            </svg></button>
                    </div>
                    <div class="review__body">
                        <p>Personal English teacher. For children, schoolchildren and adults. Classes are possible in Moscow and the region.</p>
                        <p>I teach English at any level, from Beginner to Advanced. I prepare for exams (OGE, USE, IELTS, etc.). I work with schoolchildren, applicants, university students (including language ones) with adults, classes in groups are possible.</p><button class="toggle-text-btn js-toggle-hidden-text" type="button">Show all text</button>
                        <div class="hidden-content">
                            <p>I am a certified Canadian ESOL instructor with over 10 years of experience. I am also an IELTS specialist, educational consultant, singer, actor and freelancer who was based out of Japan for 8 years.</p>
                            <p>I have many years of experience teaching both children and adults, and have taught in private language schools, universities, corporate businesses, and online with companies in Japan, South Korea, China, Israel, and Saudi Arabia.</p>
                        </div>
                    </div>
                </div>
                <div class="review">
                    <div class="review__top">
                        <div class="review__name">Pol D.</div>
                        <div class="review__phone"><svg class="svg-icon-phone svg-icon" width="10" height="14">
                                <use xlink:href="#phone"></use>
                            </svg>+3 432 13 41 521</div>
                        <div class="review__date">14/03/2021 07:12</div><button class="review__close-btn" type="button"><svg class="svg-icon-close svg-icon" width="14" height="14">
                                <use xlink:href="#close"></use>
                            </svg></button>
                    </div>
                    <div class="review__body">
                        <p>Personal English teacher. For children, schoolchildren and adults. Classes are possible in Moscow and the region.</p>
                        <p>I teach English at any level, from Beginner to Advanced. I prepare for exams (OGE, USE, IELTS, etc.). I work with schoolchildren, applicants, university students (including language ones) with adults, classes in groups are possible.</p><button class="toggle-text-btn js-toggle-hidden-text" type="button">Show all text</button>
                        <div class="hidden-content">
                            <p>I am a certified Canadian ESOL instructor with over 10 years of experience. I am also an IELTS specialist, educational consultant, singer, actor and freelancer who was based out of Japan for 8 years.</p>
                            <p>I have many years of experience teaching both children and adults, and have taught in private language schools, universities, corporate businesses, and online with companies in Japan, South Korea, China, Israel, and Saudi Arabia.</p>
                        </div>
                    </div>
                </div>
                <div class="review">
                    <div class="review__top">
                        <div class="review__name">Pol D.</div>
                        <div class="review__phone"><svg class="svg-icon-phone svg-icon" width="10" height="14">
                                <use xlink:href="#phone"></use>
                            </svg>+3 432 13 41 521</div>
                        <div class="review__date">14/03/2021 07:12</div><button class="review__close-btn" type="button"><svg class="svg-icon-close svg-icon" width="14" height="14">
                                <use xlink:href="#close"></use>
                            </svg></button>
                    </div>
                    <div class="review__body">
                        <p>Personal English teacher. For children, schoolchildren and adults. Classes are possible in Moscow and the region.</p>
                        <p>I teach English at any level, from Beginner to Advanced. I prepare for exams (OGE, USE, IELTS, etc.). I work with schoolchildren, applicants, university students (including language ones) with adults, classes in groups are possible.</p><button class="toggle-text-btn js-toggle-hidden-text" type="button">Show all text</button>
                        <div class="hidden-content">
                            <p>I am a certified Canadian ESOL instructor with over 10 years of experience. I am also an IELTS specialist, educational consultant, singer, actor and freelancer who was based out of Japan for 8 years.</p>
                            <p>I have many years of experience teaching both children and adults, and have taught in private language schools, universities, corporate businesses, and online with companies in Japan, South Korea, China, Israel, and Saudi Arabia.</p>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <?=
            ListView::widget([
                'pager' => [
                    // https://github.com/yiisoft/yii2/blob/master/framework/widgets/LinkPager.php

                    // Customzing options for pager container tag
                    'options' => [
                        //'tag' => 'div',
                        'class' => 'pages',
                        //'id' => 'pager-container',
                    ],

                    // Customzing CSS class for pager link
                    'linkOptions' => [
                        //'tag' => 'span',
                        'class' => 'pages__item',
                        'href' => '',
                    ],
                    'activePageCssClass' => 'pages__item--current_',

                    // Customzing CSS class for navigating link
                    'prevPageCssClass' => 'pages__item--prev_',
                    'nextPageCssClass' => 'pages__item--next_',
                    'firstPageCssClass' => null,
                    'lastPageCssClass' => null,
                ],
                'dataProvider' => $dataProviderFormFillsListSearch,
                'itemOptions' => [
                    'tag' => false,
                    'class' => '',
                ],
                'summary' => '<div class="showed">Showed <span>{begin, number}-{end, number}</span> of <span>{totalCount, number}</span></div>',
                'layout' => '
                    <div class="reviews">

                        {items}

                    </div>
                    {pager}
                ',
                'emptyText' => '<div class="reviews"><div class="review">Empty</div></div>',
                'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
                    /** @var $model \frontend\models\admin\FormFillsListSearch */

                    /* return html */
                    return '
                        <!-- tr -->
                        <div class="review" id="review_' . $model->lead_id . '">
                            <div class="review__top">
                                <div class="review__name">' . $model->lead_name . '</div>
                                <div class="review__phone">
                                    <svg class="svg-icon-phone svg-icon" width="10" height="14">
                                        <use xlink:href="#phone"></use>
                                    </svg>' . $model->lead_phone . '
                                </div>
                                <div class="review__date">' . $CurrentUser->getDateInUserTimezoneByDateString($model->lead_created, Yii::$app->params['datetime_short_format'], false) . '</div>
                                <button class="review__close-btn set-ff-as-read" type="button" data-lead_id="' . $model->lead_id . '">
                                    <svg class="svg-icon-close svg-icon" width="14" height="14">
                                        <use xlink:href="#close"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="review__body ww js-hidden-wrap" data-limit="2" data-mob-limit="2" >
                                ' . Functions::my_nl2br(Functions::formatLongString($model->lead_info)) . '
                            </div>
                        </div>
                        <!-- end tr -->
                    ';
                },
            ]);
            ?>

            <?php Pjax::end(); ?>

        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<?php } ?>

<?= $this->render("upload-profile-photo-modal") ?>