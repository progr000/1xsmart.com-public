<?php

/** @var $Presets \yii\db\ActiveRecord */
/** @var $preset \common\models\Presets */
/** @var $CurrentUser \common\models\Users */
/** @var $is_slave boolean */
/** @var $is_test_student boolean */

use yii\helpers\Url;
use common\models\Users;

if ($CurrentUser->user_type == Users::TYPE_STUDENT || $is_test_student) {
    $is_slave = true;
}
?>
<div id="get-offset-top" class="present__text preset__text_slide2 is-editable- is-osmd">
    <h1 class="present__title present__title--fz2"><strong>Диапазон, координация</strong> голоса и слуха, <strong>тембр</strong> голоса</h1>
    <!--
    <div class="present__desc">
        <blockquote>В процессе упражнений при появлении любых неприятных ощущений просьба незамедлительно сообщать преподавателю. Безопасность Вашего голоса является приоритетом номер 1 для нас</blockquote>
    </div>
    -->
    <?php
    if (!$is_slave) {
        ?>
        <label for="presets-var" class="preset-var">Доступные пресеты: </label>
        <select name="presets_var" id="presets_var" class="data-inputs data-select">
            <option value="" selected="selected">Выберите пресет</option>
            <?php
            foreach ($Presets as $preset) {
                echo '<option value="' . $preset->getPresetWebPath() . '">' . $preset->preset_name . '</option>';
            }
            ?>
        </select>
        <?php
    }
    ?>
</div>
<div class="present__footer slide-2 present__footer--h-auto <?= $is_slave ? 'is-slave' : ''?>">
    <div class="dashboard hw">
        <div id="player-control-height" style="border: 1px solid #ff0000; display: none;"></div>
        <div id="player-control" class="hw__player js-fixed-pw-player">

            <div class="hw-player js-hw-player calibrate-div-button hidden" id="calibrate-div-student">
                <button id="btn-calibrate" class="" type="button" title="Калибровка">
                    Калибровка
                </button>
            </div>

            <div class="hw-player js-hw-player player-buttons hidden" id="player-buttons-div">

                <?php if (!$is_slave) { ?>
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
                            <select id="count_notes_to_play" class="data-inputs hw-player__track-select round-select js-select">
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
                <?php } ?>

                <div class="hw-player__params js-hw-params">

                    <div class="hw-player__range-slider-wrap">
                        <div class="hw-player__range-slider-label">Громкость</div>
                        <input type="range" id="osmd-ap-volume" name="osmd-ap-volume" min="0" max="10" value="3" step="0.1" class="masterTooltip">
                        <!--<div id="volume-slider" class="hw-player__range-slider js-range-slider" data-min="0" data-max="4" data-start="2"></div>-->
                    </div>
                    <?php if (!$is_slave) { ?>
                    <div class="hw-player__range-slider-wrap">
                        <div class="hw-player__range-slider-label">Скорость</div>
                        <input type="range" id="osmd-ap-bpm" name="osmd-ap-bpm" min="10" max="600" value="100" step="1" class="masterTooltip">
                        <!--<div class="hw-player__range-slider js-range-slider" data-min="1" data-max="10" data-start="3"></div>-->
                    </div>
                    <?php } ?>
                    <!--
                    <div class="hw-player__range-slider-wrap">
                        <div class="hw-player__range-slider-label">Чувствительность</div>
                        <input type="range" id="osmd-ap-sensitivity" name="osmd-ap-sensitivity" min="0" max="4" value="" step="0.1" class="masterTooltip">
                        <!--<div class="hw-player__range-slider js-range-slider" data-min="1" data-max="10" data-start="3"></div>
                    </div>
                    -->

                </div>

                <button id="show-hide-volume"
                        class="btn open-params-btn js-open-params"
                        type="button"
                        data-title="Показать дополнительные настройки"
                        data-title-hidden="Скрыть дополнительные настройки">Показать дополнительные настройки</button>
            </div>
        </div>
        <div class="hw__desc">
            <div id="system-action" class="system-action"></div>
            <div id="user-action" class="user-action"></div>
            <div class="h3 text-center preset-name"> Preset name </div>
        </div>
        <div class="hw__workspace">
            <div id="loading-preset" style="display: none;">
                <h2>Дождитесь загрузки пресета...</h2>
            </div>
            <div id="osmd-canvas"></div>
        </div>
    </div>
</div>

<?php if (!$is_slave) { ?>
    <div style="display: block; width: 100%; text-align: center; padding-bottom: 20px;" id="iframe-div">
        <a href="<?= Url::to(['piano/'], CREATE_ABSOLUTE_URL) ?>" target="_blank">Открыть виртуальное пианино</a>
        <!--
        <input type="text" value="<?= Url::to(['piano/'], CREATE_ABSOLUTE_URL) ?>" id="url-for-iframe-src" style="width: 50%; height: 30px;" />
        <input type="button" value="Открыть" id="js-change-iframe-src">
        <iframe id="slide2-iframe-src" style="width: 100%; height: 400px;" src="<?= Url::to(['piano/'], CREATE_ABSOLUTE_URL) ?>"></iframe>
        -->
    </div>
<?php } ?>