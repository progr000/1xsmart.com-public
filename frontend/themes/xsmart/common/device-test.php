<?php

/** @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\assets\xsmart\deviceTestAsset;

deviceTestAsset::register($this);

$this->title = Html::encode(Yii::t('app/device-test', 'title'));

?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['user/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
    <div class="crumbs__title"><?= Yii::t('app/device-test', 'title') ?></div>
</div>
<div class="bg-wrapper">
    <div class="container">
        <div class="dashboard">
            <div class="dashboard__section">
                <div class="screen screen--sm screen screen--left">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="screen__title">
                            <svg class="svg-icon-webcam svg-icon" width="33" height="40">
                                <use xlink:href="#webcam"></use>
                            </svg><?= Yii::t('app/device-test', 'Video') ?>
                        </div>
                        <div class="test-tool">
                            <div class="test-tool__media">
                                <div class="test-tool__video video-wrapper" id="video-test-container">
                                    <!--<div class="video-play"></div>-->
                                    <video id="video-test" autoplay width="100%"></video>
                                </div>
                            </div>
                            <div class="test-tool__device">
                                <select class="lg-select js-select" id="videoSource">
                                    <option value="1">Camera 1</option>
                                </select>
                            </div>
                            <div class="test-tool__footer">
                                <div class="test-tool__note"><?= Yii::t('app/device-test', 'example_of_video') ?></div>
                                <!--<div class="test-tool__param">1745 kbit/s</div>-->
                                <button class="test-tool__play-btn primary-btn"
                                        type="button"
                                        id="btn-test-video"
                                        data-start-test="<?= Yii::t('app/device-test', 'Start') ?>"
                                        data-stop-test="<?= Yii::t('app/device-test', 'Finish') ?>"
                                        data-status="ready"><?= Yii::t('app/device-test', 'Start') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard__section">
                <div class="screen screen--sm screen screen--left">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="screen__title">
                            <svg class="svg-icon-karaoke svg-icon" width="40" height="40">
                                <use xlink:href="#karaoke"></use>
                            </svg><?= Yii::t('app/device-test', 'Mic') ?>
                        </div>
                        <div class="test-tool">
                            <div class="test-tool__media">
                                <div class="audio-progress" id="test-audio-output-level">
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled"></div>
                                    <div class="audio-progress__item _filled _active"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                    <div class="audio-progress__item"></div>
                                </div>
                            </div>
                            <div class="test-tool__device">
                                <select class="lg-select js-select" id="audioSource">
                                    <option value="1">Mic 1</option>
                                </select>
                            </div>
                            <div class="test-tool__footer">
                                <div class="test-tool__note"><?= Yii::t('app/device-test', 'Say_something') ?></div>
                                <button class="test-tool__play-btn primary-btn"
                                        type="button"
                                        id="btn-test-audio"
                                        data-start-test="<?= Yii::t('app/device-test', 'Start') ?>"
                                        data-stop-test="<?= Yii::t('app/device-test', 'Finish') ?>"
                                        data-status="ready"><?= Yii::t('app/device-test', 'Start') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard__section dashboard__section--wide">
                <div class="screen screen--sm screen screen--left">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="screen__title">
                            <svg class="svg-icon-speaker svg-icon" width="40" height="36">
                                <use xlink:href="#speaker"></use>
                            </svg><?= Yii::t('app/device-test', 'Speakers') ?>
                        </div>
                        <div class="test-tool test-tool--row">
                            <div class="test-tool__device">
                                <select class="lg-select js-select" id="audioOutput">
                                    <option value="1">Speaker 1</option>
                                </select>
                            </div>
                            <div class="test-tool__footer">
                                <div class="test-tool__note"><?= Yii::t('app/device-test', 'should_hear_sound') ?></div>
                                <audio src="/assets/xsmart-min/sounds/calibrating.mp3" id="audio-output-test"></audio>
                                <button class="test-tool__play-btn primary-btn"
                                        type="button"
                                        id="btn-test-audio-output">
                                    <svg class="svg-icon-triangle svg-icon" width="20" height="15">
                                        <use xlink:href="#triangle"></use>
                                    </svg><?= Yii::t('app/device-test', 'Play') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>