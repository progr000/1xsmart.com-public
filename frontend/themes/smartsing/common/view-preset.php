<?php

/** @var $this \yii\web\View */
/** @var $Preset \common\models\Presets */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Users;
use frontend\assets\smartsing\ViewPresetAsset;

ViewPresetAsset::register($this);

$this->title = Html::encode("Пресет {$Preset->preset_name}");

$back_controller = $CurrentUser->user_type == Users::TYPE_METHODIST ? 'methodist' : 'admin';
?>

<div class="dashboard hw"
     id="music-xml-data"
     data-music-xml="<?= $Preset->getPresetWebPath() ?>">
    <h1>Пресет <?= $Preset->preset_name ?></h1>
    <div class="hw__header">
        <a class="back-link" href="<?= Url::to([$back_controller . '/presets'], CREATE_ABSOLUTE_URL) ?>">
            <svg class="svg-icon--arrow-left svg-icon" width="25" height="12">
                <use xlink:href="#arrow-left"></use>
            </svg>Назад
        </a>
    </div>
    <div class="hw__player js-fixed-pw-player">

        <div class="hw-player js-hw-player calibrate-div-button hidden" id="calibrate-div">
            <button id="btn-calibrate" class="" type="button" title="Калибровка">
                Калибровка
            </button>
        </div>

        <div class="hw-player js-hw-player hidden" id="player-buttons-div">

            <div class="hw-player__controls">

                <button id="btn-first" class="hw-player__control btn" type="button">
                    <svg class="svg-icon--play-back svg-icon" width="13" height="14">
                        <use xlink:href="#play-back"></use>
                    </svg>
                </button>
                <button id="btn-prev" class="hw-player__control hw-player__control--mob-top btn" type="button"><svg class="svg-icon--rewind svg-icon" width="25" height="14">
                        <use xlink:href="#rewind"></use>
                    </svg>
                </button>

                <button id="btn-play" class="hw-player__control hw-player__control--lg btn" type="button" title="Play">
                    <svg class="svg-icon--play svg-icon" width="21" height="28">
                        <use xlink:href="#play"></use>
                    </svg>
                </button>
                <button id="btn-pause" class="hw-player__control hw-player__control--lg btn" type="button" title="Pause">
                    <svg class="svg-icon--pause svg-icon" width="12" height="28">
                        <use xlink:href="#pause"></use>
                    </svg>
                </button>
                <button id="btn-stop" class="hw-player__control hw-player__control--lg btn" type="button" title="Stop">
                    <svg class="svg-icon--stop svg-icon" width="21" height="28">
                        <use xlink:href="#stop"></use>
                    </svg>
                </button>

                <div class="hw-player__select">
                    <div class="select-wrap">
                        <select id="count_notes_to_play" class="hw-player__track-select round-select js-select">
                            <option value="-1">All</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <button id="btn-replay" class="hw-player__control btn" type="button"><svg class="svg-icon--replay svg-icon" width="33" height="34">
                        <use xlink:href="#replay"></use>
                    </svg>
                </button>
                <button id="btn-next" class="hw-player__control hw-player__control--mob-bottom btn" type="button">
                    <svg class="svg-icon--forward svg-icon" width="25" height="14">
                        <use xlink:href="#forward"></use>
                    </svg>
                </button>
                <button id="btn-last" class="hw-player__control hw-player__control--mob-hidden btn" type="button">
                    <svg class="svg-icon--play-forward svg-icon" width="13" height="14">
                        <use xlink:href="#play-forward"></use>
                    </svg>
                </button>
            </div>

            <div class="hw-player__params js-hw-params">

                <div class="hw-player__range-slider-wrap">
                    <div class="hw-player__range-slider-label">Громкость</div>
                    <input type="range" id="osmd-ap-volume" name="osmd-ap-volume" min="0" max="10" value="3" step="0.1" class="masterTooltip">
                    <!--<div id="volume-slider" class="hw-player__range-slider js-range-slider" data-min="0" data-max="4" data-start="2"></div>-->
                </div>
                <div class="hw-player__range-slider-wrap">
                    <div class="hw-player__range-slider-label">Скорость</div>
                    <input type="range" id="osmd-ap-bpm" name="osmd-ap-bpm" min="10" max="600" value="100" step="1" class="masterTooltip">
                    <!--<div class="hw-player__range-slider js-range-slider" data-min="1" data-max="10" data-start="3"></div>-->
                </div>
                <!--
                <div class="hw-player__range-slider-wrap">
                    <div class="hw-player__range-slider-label">Чувствительность</div>
                    <input type="range" id="osmd-ap-sensitivity" name="osmd-ap-sensitivity" min="0" max="4" value="" step="0.1" class="masterTooltip">
                    <!--<div class="hw-player__range-slider js-range-slider" data-min="1" data-max="10" data-start="3"></div>
                </div>
                -->

            </div>

            <button class="btn open-params-btn js-open-params"
                    type="button"
                    data-title="Показать дополнительные настройки"
                    data-title-hidden="Скрыть дополнительные настройки">Показать дополнительные настройки</button>
        </div>

    </div>
    <div class="hw__desc">
        <!--
        Коэф.чувст: <input type="text" id="rms-factor" value="8" style="width: 60px; padding: 2px 10px; height: 40px" />
        &nbsp;&nbsp;&nbsp;
        Ож.ноты: <input type="text" id="wait-note" value="10" style="width: 60px; padding: 2px 10px; height: 40px" />сек
        -->
        <!--
        &nbsp;&nbsp;&nbsp;
        Откл.частоты: <input type="text" id="--pitch-deviation" value="20" style="width: 60px; padding: 2px 10px; height: 40px" />%
        -->
        <div id="system-action" class="system-action"></div>
        <div id="user-action" class="user-action"></div>

        <!--<div class="h3 text-center"><?= $Preset->preset_name ?></div>-->
    </div>
    <div class="hw__workspace present-info-content-view-preset"
         id="present-info-content"
         data-is-home-work="1">

        <div id="loading-preset">
            <h2>Дождитесь загрузки пресета...</h2>
        </div>
        <div id="osmd-canvas"></div>

    </div>
</div>
