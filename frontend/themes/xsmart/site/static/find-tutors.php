<?php

/** @var $this yii\web\View */
/** @var $additionalData array */

use yii\helpers\Html;
use common\models\Users;
use frontend\assets\xsmart\student\FindTutorsAsset;

$this->title = Html::encode(Yii::t('static/find-tutors', 'title', ['APP_NAME' => Yii::$app->name]));

FindTutorsAsset::register($this);

$lang = Yii::$app->language;

?>

<?= Yii::t('static/find-tutors-css', 'css') ?>

<div class="content content--no-pad content--filter" id="find-tutor-filters">
    <div class="content-header">
        <h1 class="content-header__title"><?= Yii::t('static/find-tutors', 'Here_you_can') ?></h1>
        <div class="content-header__intro"><?= Yii::t('static/find-tutors', 'You_may_choose') ?></div>
    </div>
    <div class="filter filter--mob-holder js-mob-filter-holder"></div>
    <button class="filter-open-btn js-open-filter" type="button">
        <svg class="svg-icon-tools svg-icon" width="20" height="20">
            <use xlink:href="#tools"></use>
        </svg><?= Yii::t('static/find-tutors', 'More_filters') ?><span class="filtered-count"></span>
    </button>
    <div class="filter filter--single">
        <div class="filter__item js-mob-filter">
            <div class="filter__label"><?= Yii::t('static/find-tutors', 'Discipline') ?></div>
            <div class="filter__input-wrap" id="main-search-field">
                <select class="cmplx-select btn-select js-search-select -js-select has-overlay-select search-field"
                        data-placeholder="<?= Yii::t('static/find-tutors', 'Choose') ?>"
                        data-search-placeholder="<?= Yii::t('static/find-tutors', 'Search') ?>"
                        id="discipline-field"
                        name="TutorSearch[discipline_id]"
                        data-off-id="lng-select">
                    <option value="" data-placeholder="true"></option>
                    <option value=""><?= Yii::t('static/find-tutors', 'Choose') ?></option>
                    <!--<option value="0"><?= Yii::t('static/find-tutors', 'Any') ?></option>-->
                    <?php /** @var \common\models\Disciplines $discipline */
                    $discipline_name_field = "discipline_name_{$lang}";
                    foreach ($additionalData['disciplines'] as $discipline) {
                        $selected = '';
                        if (isset($_GET['discipline_id']) && $_GET['discipline_id'] == $discipline['discipline_id']) {
                            //$selected = 'selected="selected"';
                        }
                        echo '<option value="' . $discipline['discipline_id'] . '" ' . $selected . '>' . (isset($discipline[$discipline_name_field]) ? $discipline[$discipline_name_field] : $discipline['discipline_name_en']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="filter-panel js-filter">
        <button class="filter-close-btn js-close-filter" type="button">
            <svg class="svg-icon-close svg-icon" width="20" height="20">
                <use xlink:href="#close"></use>
            </svg>
        </button>
        <div class="filter _first" id="showed-filter">
            <div class="filter__item filter__item--shadow js-shadow-filter"></div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Can_teach_children') ?></div>
                <div class="filter__input-wrap">
                    <select id="user_can_teach_children-field"
                            name="TutorSearch[user_can_teach_children]"
                            class="js-search-select cmplx-select has-overlay-select search-field select-can-teach-children"
                            data-placeholder="<?= Yii::t('static/find-tutors', 'Any') ?>"
                            data-search-placeholder="<?= Yii::t('static/find-tutors', 'Any') ?>">
                        <option value="0" data-off-placeholder="true"><?= Yii::t('static/find-tutors', 'No_matter') ?></option>
                        <option value="1"><?= Yii::t('static/find-tutors', 'Yes') ?></option>
                    </select>
                </div>
            </div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Location') ?></div>
                <div class="filter__input-wrap">
                    <div class="cmplx-input has-overlay-input js-cmplx">
                        <input class="cmplx-input__input js-cmplx-input js-cmplx-select-input search-field"
                               type="text"
                               value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               readonly="readonly"
                               data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               name="TutorSearch[geo]"
                               id="geo-full-location-field"
                               data-ids="-1, -1, -1"
                               data-off-id="location-input" />
                        <div class="cmplx-input__dropdown js-cmplx-dropdown">
                            <!--input(type="text" placeholder="Search")-->
                            <select class="-js-select js-search-select js-cmplx-data geo-select search-field"
                                    data-placeholder="<?= Yii::t('static/find-tutors', 'Country') ?>"
                                    data-placeholder-ready="<?= Yii::t('static/find-tutors', 'Country') ?>"
                                    data-placeholder-any="<?= Yii::t('static/find-tutors', 'Any') ?>"
                                    data-placeholder-loading="<?= Yii::t('static/find-tutors', 'Loading') ?>"
                                    data-any-name="<?= Yii::t('static/find-tutors', 'Any') ?>"
                                    name="TutorSearch[country_id]"
                                    id="geo-country-field"
                                    data-off-id="country-select">
                                <option value="" data-placeholder="true"></option>
                                <option value="0"><?= Yii::t('static/find-tutors', 'Any') ?></option>
                                <?php
                                /** @var \common\models\Countries $country */
                                $country_name_field = "title_{$lang}";
                                foreach ($additionalData['countries'] as $country) {
                                    echo '<option value="' . $country['country_id'] . '">' . (isset($country[$country_name_field]) ? $country[$country_name_field] : $country['title_en']) . '</option>';
                                }
                                ?>
                            </select>
                            <select class="-js-select js-search-select js-cmplx-data geo-select search-field"
                                    id="geo-region-field"
                                    name="TutorSearch[region_id]"
                                    data-placeholder="<?= Yii::t('static/find-tutors', 'Select_country_before') ?>"
                                    data-placeholder-ready="<?= Yii::t('static/find-tutors', 'Region') ?>"
                                    data-placeholder-any="<?= Yii::t('static/find-tutors', 'Any') ?>"
                                    data-placeholder-select="<?= Yii::t('static/find-tutors', 'Select_country_before') ?>"
                                    data-placeholder-loading="<?= Yii::t('static/find-tutors', 'Loading') ?>">
                                <!--<option value="" data-placeholder="true"></option>-->
                            </select>
                            <select id="geo-city-field"
                                    class="-js-select js-search-select js-cmplx-data geo-select search-field"
                                    name="TutorSearch[city_id]"
                                    data-placeholder="<?= Yii::t('static/find-tutors', 'Select_region_before') ?>"
                                    data-placeholder-ready="<?= Yii::t('static/find-tutors', 'City') ?>"
                                    data-placeholder-any="<?= Yii::t('static/find-tutors', 'Any') ?>"
                                    data-placeholder-select="<?= Yii::t('static/find-tutors', 'Select_region_before') ?>"
                                    data-placeholder-loading="<?= Yii::t('static/find-tutors', 'Loading') ?>">
                                <!--<option value="" data-placeholder="true"></option>-->
                            </select>
                            <button class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button"><?= Yii::t('static/find-tutors', 'Choose') ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Availability') ?></div>
                <div class="filter__input-wrap">
                    <div class="cmplx-input has-overlay-input cmplx-modal js-cmplx-modal">
                        <input class="cmplx-input__input js-cmplx-input search-field"
                               type="text"
                               value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               readonly="readonly"
                               data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               name="TutorSearch[timing]"
                               id="time-input" />
                        <div class="cmplx-input__modal modal modal modal--light modal modal--wide" id="days-choose">
                            <div class="modal__inner">
                                <div class="modal__body">
                                    <div class="modal__title"><?= Yii::t('static/find-tutors', 'Availability') ?></div>
                                    <form class="modal__form" action="/">
                                        <div class="group-inputs">
                                            <div class="group-inputs__title"><?= Yii::t('static/find-tutors', 'Days') ?></div>
                                            <div class="check-row">
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-1" value="Monday" name="TutorSearch[_user_day_ability][1]" data-value="1" /><label for="day-1"><?= Yii::t('static/find-tutors', 'Monday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-2" value="Tuesday" name="TutorSearch[_user_day_ability][2]" data-value="2" /><label for="day-2"><?= Yii::t('static/find-tutors', 'Tuesday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-3" value="Wednesday" name="TutorSearch[_user_day_ability][3]" data-value="3" /><label for="day-3"><?= Yii::t('static/find-tutors', 'Wednesday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-4" value="Thursday" name="TutorSearch[_user_day_ability][4]" data-value="4" /><label for="day-4"><?= Yii::t('static/find-tutors', 'Thursday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-5" value="Friday" name="TutorSearch[_user_day_ability][5]" data-value="5" /><label for="day-5"><?= Yii::t('static/find-tutors', 'Friday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-6" value="Saturday" name="TutorSearch[_user_day_ability][6]" data-value="6" /><label for="day-6"><?= Yii::t('static/find-tutors', 'Saturday') ?></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="day-7" value="Sunday" name="TutorSearch[_user_day_ability][7]" data-value="7" /><label for="day-7"><?= Yii::t('static/find-tutors', 'Sunday') ?></label></div>
                                            </div>
                                        </div>
                                        <div class="group-inputs">
                                            <div class="group-inputs__title"><?= Yii::t('static/find-tutors', 'Time_interval') ?></div>
                                            <div class="check-row">
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="time-1" value="06-10" name="TutorSearch[_user_time_ability][1]" data-value="6-10" /><label for="time-1"><span class="check-icon-wrap"><svg class="svg-icon-sunrise svg-icon" width="20" height="13"><use xlink:href="#sunrise"></use></svg></span><span>06-10</span></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="time-2" value="10-13" name="TutorSearch[_user_time_ability][2]" data-value="10-13" /><label for="time-2"><span class="check-icon-wrap"><svg class="svg-icon-sun svg-icon" width="20" height="20"><use xlink:href="#sun"></use></svg></span><span>10-13</span></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="time-3" value="13-19" name="TutorSearch[_user_time_ability][3]" data-value="13-19" /><label for="time-3"><span class="check-icon-wrap"><svg class="svg-icon-dishes svg-icon" width="18" height="18"><use xlink:href="#dishes"></use></svg></span><span>13-19</span></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="time-4" value="19-00" name="TutorSearch[_user_time_ability][4]" data-value="19-0" /><label for="time-4"><span class="check-icon-wrap"><svg class="svg-icon-evening svg-icon" width="18" height="18"><use xlink:href="#evening"></use></svg></span><span>19-00</span></label></div>
                                                <div class="check-wrap"><input class="js-cmplx-data text-checkbox checkbox-select search-field" type="checkbox" id="time-5" value="00-06" name="TutorSearch[_user_time_ability][5]" data-value="0-6" /><label for="time-5"><span class="check-icon-wrap"><svg class="svg-icon-night svg-icon" width="18" height="18"><use xlink:href="#night"></use></svg></span><span>00-06</span></label></div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="modal__submit"><button class="primary-btn wide-mob-btn js-submit-modal-cmplx" type="submit"><?= Yii::t('static/find-tutors', 'Choose') ?></button></div>
                                </div>
                                <button class="modal__close-btn js-close-modal" type="button">
                                    <svg class="svg-icon-close svg-icon" width="22" height="22">
                                        <use xlink:href="#close"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter hidden-content flex" id="hidden-filter">
            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Course') ?></div>
                <div class="filter__input-wrap">
                    <div class="cmplx-input has-overlay-input has-filter js-cmplx">
                        <!--<input class="cmplx-input__input js-cmplx-input" type="text" value="<?= Yii::t('static/find-tutors', 'Any') ?>" readonly="readonly" data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />-->
                        <input class="cmplx-input__input js-cmplx-input search-field"
                               id="course-field"
                               name="TutorSearch[user_goals_of_education_text]"
                               type="text"
                               value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               readonly="readonly"
                               data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />
                        <input class="cmplx-input__input cmplx-input__input--filter js-cmplx-filter-input" type="text" value="" />
                        <div class="cmplx-input__dropdown js-cmplx-dropdown">
                            <?php foreach (Users::$_goals_of_education as $key => $item) { ?>
                                <div class="check-wrap">
                                    <input
                                        class="sm-checkbox js-cmplx-data checkbox-select search-field"
                                        name="TutorSearch[_user_goals_of_education][<?= $item ?>]"
                                        type="checkbox"
                                        data-value="<?= $item ?>"
                                        value="<?= Yii::t('models/Users', $item) ?>"
                                        id="course-<?= $item ?>" />
                                    <label for="course-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                </div>
                            <?php } ?>
                            <button class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button"><?= Yii::t('static/find-tutors', 'Choose') ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Price') ?></div>
                <div class="filter__input-wrap">
                    <select id="price-field"
                            name="TutorSearch[price]"
                            class="js-price-select cmplx-select price-select has-overlay-select search-field">
                    </select>
                </div>
            </div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Teacher_knows') ?></div>
                <div class="filter__input-wrap">
                    <div class="cmplx-input has-overlay-input has-filter js-cmplx">
                        <!--<input class="cmplx-input__input js-cmplx-input" type="text" value="<?= Yii::t('static/find-tutors', 'Any') ?>" readonly="readonly" data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />-->
                        <input class="cmplx-input__input js-cmplx-input search-field"
                               id="other-lang-field"
                               name="TutorSearch[user_speak_also_text]"
                               type="text"
                               value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               readonly="readonly"
                               data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />
                        <input class="cmplx-input__input cmplx-input__input--filter js-cmplx-filter-input" type="text" value="" />
                        <div class="cmplx-input__dropdown js-cmplx-dropdown">
                            <?php foreach (Users::$_languages as $key => $item) { ?>
                                <div class="check-wrap">
                                    <input
                                        class="sm-checkbox js-cmplx-data checkbox-select search-field"
                                        name="TutorSearch[_user_speak_also][<?= $item ?>]"
                                        type="checkbox"
                                        data-value="<?= $item ?>"
                                        value="<?= Yii::t('models/Users', $item) ?>"
                                        id="other-lng-<?= $item ?>" />
                                    <label for="other-lng-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                </div>
                            <?php } ?>
                            <button class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button"><?= Yii::t('static/find-tutors', 'Choose') ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter__item">
                <div class="filter__label"><?= Yii::t('static/find-tutors', 'Teacher_native') ?></div>
                <div class="filter__input-wrap">
                    <div class="cmplx-input has-overlay-input has-filter js-cmplx">
                        <!--<input class="cmplx-input__input js-cmplx-input" type="text" value="<?= Yii::t('static/find-tutors', 'Any') ?>" readonly="readonly" data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />-->
                        <input class="cmplx-input__input js-cmplx-input search-field"
                               id="native-field"
                               name="TutorSearch[user_are_native_text]"
                               type="text"
                               value="<?= Yii::t('static/find-tutors', 'Any') ?>"
                               readonly="readonly"
                               data-default-value="<?= Yii::t('static/find-tutors', 'Any') ?>" />
                        <input class="cmplx-input__input cmplx-input__input--filter js-cmplx-filter-input" type="text" value="" />
                        <div class="cmplx-input__dropdown js-cmplx-dropdown">
                            <?php foreach (Users::$_languages as $key => $item) { ?>
                                <div class="check-wrap">
                                    <input
                                        class="sm-checkbox js-cmplx-data checkbox-select search-field"
                                        name="TutorSearch[_user_are_native][<?= $item ?>]"
                                        type="checkbox"
                                        data-value="<?= $item ?>"
                                        value="<?= Yii::t('models/Users', $item) ?>"
                                        id="native-lng-<?= $item ?>" />
                                    <label for="native-lng-<?= $item ?>"><span></span><span><?= Yii::t('models/Users', $item) ?></span></label>
                                </div>
                            <?php } ?>
                            <button class="primary-btn sm-btn wide-btn js-cmplx-submit" type="button"><?= Yii::t('static/find-tutors', 'Choose') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-tools">
            <button class="toggle-hidden-btn js-toggle-hidden"
                    type="button"
                    data-active-caption="Hide filters"
                    data-default-caption="More filters"
                    data-hidden-id="hidden-filter"><?= Yii::t('static/find-tutors', 'More_filters') ?></button>
            <div class="sort">
                <div class="select-wrap">
                    <label class="select-label" for="sort-by"><?= Yii::t('static/find-tutors', 'Sort_by') ?></label>
                    <select class="js-select simple-select search-field"
                            name="sort"
                            id="sort-by">
                        <option value="exact_match"><?= Yii::t('static/find-tutors', 'Exact_match') ?></option>
                        <option value="price_lowest"><?= Yii::t('static/find-tutors', 'Price_lowest') ?></option>
                        <option value="price_highest"><?= Yii::t('static/find-tutors', 'Price_highest') ?></option>
                        <option value="rating"><?= Yii::t('static/find-tutors', 'Rating') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-wrapper">
    <div class="container container--narrow find-tutor-results-container own-pagination" id="find-tutor-results">

        <?php
        if (isset($_GET['back_from_tutor'])) {
            $test = Yii::$app->cache->get('FindTutorsRequestCacheForeHistoryBack_for_session_id_' . Yii::$app->getSession()->id);
            if ($test) {
                echo $test;
            }
        }
        ?>

    </div>
    <div class="container container--narrow find-tutor-progress-container" id="find-tutor-progress">
        <img src="/assets/xsmart-min/images/loader.svg" />
    </div>
</div>

<script>
    const priceData = [
        {
            text: '<?= Yii::t('static/find-tutors', 'Any') ?>',
            value: '0'
        },
        <?php foreach (Users::$_price_vars as $k=>$v) { ?>

        {
            innerHTML: '<span><?= Yii::t('models/Users', $v['name']) ?></span><img src="/assets/xsmart-min/images/price/<?= $k ?>.svg" alt="">',
            text: '<?= Yii::t('models/Users', $v['name']) ?>',
            value: '<?= $k ?>'
        },

        <?php } ?>

    ];
</script>


