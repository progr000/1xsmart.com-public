<?php

/** @var $model \frontend\models\forms\ProfileForm */

//use yii\imagine\Image;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Users;
use frontend\assets\smartsing\profileAsset;

profileAsset::register($this);

//$model->user_birthday_day   = intval(date('d', strtotime($model->user_birthday)));
//$model->user_birthday_month = intval(date('m', strtotime($model->user_birthday)));
//$model->user_birthday_year  = intval(date('Y', strtotime($model->user_birthday)));
// это теперь в ProfileForm::afterFind()

$this->title = Html::encode('Мой профиль');

?>
<div class="dashboard">
    <h1 class="page-title">Мой профиль</h1>
    <div class="profile win win win--grey">
        <div class="profile__top win__top"></div>
        <div class="profile__inner">
            <?php $form = ActiveForm::begin([
                'id' => 'form-profile',
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

                <div class="form-section">
                    <div class="form-fieldset">
                        <div class="form-fieldset__title">Основная информация</div>
                        <div class="profile-wrap">
                            <div class="profile-main">
                                <div class="form-row">
                                    <div class="form-col form-col--1_2">
                                        <?= $form->field($model, 'user_first_name')
                                            ->textInput([
                                                'placeholder' => $model->getAttributeLabel('user_first_name'),
                                                'autocomplete' => "off",
                                                'aria-label'   => $model->getAttributeLabel('user_first_name'),
                                                'class' => "editable-input",
                                            ])
                                            ->label('Имя:')
                                        ?>
                                    </div>
                                    <div class="form-col form-col--1_2">
                                        <?= $form->field($model, 'user_email')
                                            ->textInput([
                                                'placeholder' => $model->getAttributeLabel('user_email'),
                                                'autocomplete' => "off",
                                                'aria-label'   => $model->getAttributeLabel('user_email'),
                                                'class' => "editable-input",
                                            ])
                                            ->label('Электронная почта:')
                                        ?>
                                    </div>
                                    <div class="form-col form-col--1_2">
                                        <?= $form->field($model, 'user_phone')
                                            ->textInput([
                                                'placeholder' => $model->getAttributeLabel('user_phone'),
                                                'autocomplete' => "off",
                                                'aria-label'   => $model->getAttributeLabel('user_phone'),
                                                'class' => "editable-input",
                                            ])
                                            ->label('Телефон:')
                                        ?>
                                    </div>
                                    <div class="form-col form-col--1_2">
                                        <?= $form->field($model, '_user_skype')
                                            ->textInput([
                                                'placeholder' => $model->getAttributeLabel('_user_skype'),
                                                'autocomplete' => "off",
                                                'aria-label'   => $model->getAttributeLabel('_user_skype'),
                                                'class' => "editable-input", //"verified-input",
                                            ])
                                            ->label('Skype:')
                                        ?>
                                    </div>
                                    <div class="form-col form-col--1_2">
                                        <?= $form->field($model, 'password')
                                            ->passwordInput([
                                                'placeholder' => $model->getAttributeLabel('New password'),
                                                'autocomplete' => "off",
                                                'aria-label'   => 'new_password',
                                                //'value' => 'password'
                                            ])
                                            ->label('Пароль:')
                                        ?>
                                    </div>
                                    <div class="form-col form-col--1_2">
                                        <label>Дата рождения:</label>
                                        <div class="date-inputs-group">
                                            <div class="select-wrap">
                                                <?php
                                                function get_days()
                                                {
                                                    $ret = [];
                                                    for ($i=1; $i<=31; $i++) {
                                                        $ret[$i] = ($i > 9) ? "$i" : "0$i";
                                                    }
                                                    return $ret;
                                                }
                                                echo $form->field($model, 'user_birthday_day', [
                                                    'template'=>'<div class="select-wrap">{input}</div>',
                                                    'options' => [
                                                        'tag' => false,
                                                        //'class' => 'birthday-field',
                                                    ],
                                                ])->dropDownList(get_days(), [
                                                    'id'         => "profile-bday",
                                                    'class'      => "js-day-select",
                                                    'aria-label' => $model->getAttributeLabel('user_birthday_day'),
                                                    'data-placeholder' => "День",
                                                ])->label(false);
                                                ?>
                                            </div>
                                            <div class="select-wrap">
                                                <?php
                                                function get_months() {
                                                    $ret = [];
                                                    for ($i=1; $i<=12; $i++) {
                                                        $ret[$i] = ($i > 9) ? "$i" : "0$i";
                                                    }
                                                    return $ret;
                                                }
                                                echo $form->field($model, 'user_birthday_month', [
                                                    'template'=>'<div class="select-wrap">{input}</div>',
                                                    'options' => [
                                                        'tag' => false,
                                                        //'class' => 'birthday-field',
                                                    ],
                                                ])->dropDownList(get_months(), [
                                                    'id'         => "profile-bmonth",
                                                    'class'      => "js-month-select",
                                                    'aria-label' => $model->getAttributeLabel('user_birthday_month'),
                                                    'data-placeholder' => "Месяц",
                                                ])->label(false);
                                                ?>
                                            </div>
                                            <div class="select-wrap">
                                                <?php
                                                function get_years() {
                                                    $ret = [];
                                                    for ($i=1970; $i<=intval(date('Y')); $i++) {
                                                        $ret[$i] = "$i";
                                                    }
                                                    return $ret;
                                                }
                                                echo $form->field($model, 'user_birthday_year', [
                                                    'template'=>'<div class="select-wrap">{input}</div>',
                                                    'options' => [
                                                        'tag' => false,
                                                        //'class' => 'birthday-field',
                                                    ],
                                                ])->dropDownList(get_years(), [
                                                    'id'         => "profile-byear",
                                                    'class'      => "js-year-select",
                                                    'aria-label' => $model->getAttributeLabel('user_birthday_day'),
                                                    'data-placeholder' => "Год",
                                                ])->label(false);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col form-col--wide"><label>Пол</label>
                                        <div class="check-row check-row--5col">
                                            <div class="check-wrap">
                                                <input id="sex-1"
                                                       type="radio"
                                                       name="ProfileForm[user_gender]"
                                                       value="<?= Users::GENDER_MALE ?>"
                                                    <?= $model->user_gender === Users::GENDER_MALE ? 'checked="checked"' : '' ?> />
                                                <label for="sex-1"><span></span><span>Мужской</span></label>
                                            </div>
                                            <div class="check-wrap">
                                                <input id="sex-2"
                                                       type="radio"
                                                       name="ProfileForm[user_gender]"
                                                       value="<?= Users::GENDER_FEMALE ?>"
                                                    <?= $model->user_gender === Users::GENDER_FEMALE ? 'checked="checked"' : '' ?> />
                                                <label for="sex-2"><span></span><span>Женский</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-aside">
                                <div class="profile-photo-wrap">
                                    <div class="profile-photo">
                                        <div class="profile-photo__label">Ваше фото:</div>
                                        <div class="profile-photo__thumb">
                                            <img id="user-profile-photo-container"
                                                 src="<?= $model->getProfilePhotoForWeb('/assets/smartsing-min/images/upload_your_photo.png') ?>"
                                                 alt="">
                                        </div>
                                        <div class="profile-photo__text">
                                            <div class="profile-photo__label profile-photo__label--mob">Ваше фото:</div>

                                                <div class="photo-enabled" style="display: <?= $model->user_photo ? 'block' : 'none' ?>">
                                                    <button data-modal-id="upload-profile-photo-modal" class="profile-photo__add-btn btn js-open-modal" type="button">Изменить фото</button>
                                                    <button class="profile-photo__remove-btn btn" type="button">Удалить</button>
                                                </div>

                                                <div class="photo-disabled" style="display: <?= $model->user_photo ? 'none' : 'block' ?>">
                                                    <button data-modal-id="upload-profile-photo-modal" class="profile-photo__add-btn btn js-open-modal" type="button">Загрузить фото</button>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($model->user_type == Users::TYPE_STUDENT) { ?>
                    <div class="form-section">
                        <div class="form-fieldset">
                            <div class="form-fieldset__title">Был ли ранее музыкальный опыт:</div>
                            <div class="check-row check-row--4col">
                                <?php
                                $_music_experience = unserialize($model->user_music_experience);
                                foreach (Users::$_music_experience as $key => $item) {
                                    ?>
                                    <div class="check-wrap">
                                        <input id="radio-<?= $key ?>"
                                               name="ProfileForm[_user_music_experience][<?= $key ?>]"
                                               type="checkbox" <?= !empty($_music_experience[$key]) ? 'checked="checked"' : '' ?> />
                                        <label for="radio-<?= $key ?>"><span></span><span><?= $item ?></span></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-fieldset">
                            <div class="form-fieldset__title">Цели обучения:</div>
                            <div class="check-row check-row--4col">
                                <?php
                                $_learning_objectives = unserialize($model->user_learning_objectives);
                                foreach (Users::$_learning_objectives as $key => $item) {
                                    ?>
                                    <div class="check-wrap">
                                        <input id="radio-<?= $key ?>"
                                               name="ProfileForm[_user_learning_objectives][<?= $key ?>]"
                                               type="checkbox" <?= !empty($_learning_objectives[$key]) ? 'checked="checked"' : '' ?> />
                                        <label for="radio-<?= $key ?>"><span></span><span><?= $item ?></span></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-section">
                    <div class="form-fieldset">
                        <div class="form-fieldset__title">Нравятся музыкальные жанры:</div>
                        <div class="check-row check-row--4col">
                            <?php
                            $_music_genres = unserialize($model->user_music_genres);
                            foreach (Users::$_music_genres as $key => $item) {
                                ?>
                                <div class="check-wrap">
                                    <input
                                        id="radio-<?= $key ?>"
                                        name="ProfileForm[_user_music_genres][<?= $key ?>]"
                                        type="checkbox" <?= !empty($_music_genres[$key]) ? 'checked="checked"' : '' ?> />
                                    <label for="radio-<?= $key ?>"><span></span><span><?= $item ?></span></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php if ($model->user_type == Users::TYPE_TEACHER) { ?>
                    <div class="form-section">
                        <div class="form-fieldset">
                            <div class="form-fieldset__title">Видео-презентация:</div>

                            <div class="check-row check-row--4col">

                                <div class="video-enabled" style="display: <?= $model->user_local_video ? 'block' : 'none' ?>">
                                    <button data-modal-id="view-my-video-modal" class="profile-video__add-btn btn js-open-modal" type="button">Посмотреть видео</button>
                                    <br />
                                    <button data-modal-id="upload-profile-video-modal" class="profile-video__add-btn btn js-open-modal" type="button">Изменить видео</button>
                                    <br />
                                    <button class="profile-video__remove-btn btn" type="button">Удалить</button>
                                </div>

                                <div class="video-disabled" style="display: <?= $model->user_local_video ? 'none' : 'block' ?>">
                                    <button data-modal-id="upload-profile-video-modal" class="profile-video__add-btn btn js-open-modal" type="button">Загрузить видео</button>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php } ?>

                <div class="form-section">
                    <div class="form-fieldset">
                        <div class="form-fieldset__title">Любая дополнительная информация:</div>
                        <?= $form->field($model, 'user_additional_info')
                            ->textarea([
                                //'placeholder' => $model->getAttributeLabel('О себе'),
                                'autocomplete' => "off",
                                'aria-label'   => $model->getAttributeLabel('user_additional_info'),
                            ])
                            ->label(false)
                        ?>
                    </div>
                    <button class="profile-frm__submit btn primary-btn primary-btn--c6" type="submit">Сохранить</button>
                </div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?= $this->render("../modals/_member-modals/upload-profile-photo-modal") ?>
<?php
if ($model->user_type == Users::TYPE_TEACHER) {
    echo $this->render("../modals/_member-modals/upload-profile-video-modal");
    echo $this->render("../modals/teacher-modals/view-my-video-modal", ['CurrentUser' => $model]);
}
?>

