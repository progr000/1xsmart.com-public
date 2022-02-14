<?php

/** @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\smartsing\deviceTestAsset;

deviceTestAsset::register($this);

$this->title = Html::encode('Тест видео и аудио');
?>

<div class="dashboard">
    <h1 class="page-title">Тест видео и аудио</h1>
    <p>Для теста видеосвязи дайте разрешение своему браузеру на использование видеокамеры и микрофона.</p>
    <p>Вы можете посмотреть подробную инструкцию для браузера <strong>Chrome</strong> (<a href="https://support.google.com/chrome/answer 2693767 co=GENIE.Platform=Desktop&amp;hl=ru" class="accent-link">https://support.google.com/chrome/answer 2693767 co=GENIE.Platform=Desktop&amp;hl=ru</a>), а также для браузера <strong>Safari</strong> (<a href="https://support.apple.com/ru-ru/guide/safari/ibrw7f78f7fe/mac" class="accent-link">https://support.apple.com/ru-ru/guide/safari/ibrw7f78f7fe/mac</a>).</p>
    <p>Рекомендуется использование наушников и микрофона.</p>
    <div class="device-test-grid">
        <div class="device-test-grid__item">
            <h2 class="icon-title">
                <svg class="svg-icon--camera svg-icon" width="40" height="48">
                    <use xlink:href="#camera"></use>
                </svg>Видеосвязь
            </h2>
            <div class="test-card win win win--grey">
                <div class="test-card__top win__top"></div>
                <div class="test-card__inner win__inner">
                    <div class="test-card__media" id="video-test-container">
                        <!--<img src="/assets/smartsing-min/files/test/test-video.jpg" alt="">-->
                        <video id="video-test" autoplay width="100%"></video>
                    </div>

                    <div class="test-card__device">
                        <div class="select-wrap">
                            <select class="js-select" id="videoSource">
                                <option value="1">Камера 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="test-card__footer">
                        <button type="button"
                                id="btn-test-video"
                                class="btn primary-btn primary-btn--c6 md-btn round-btn video-test-btn"
                                data-start-test="Начать тест"
                                data-stop-test="Завершить"
                                data-status="ready">
                            Начать тест
                        </button>
                        <div class="test-card__note test-card-video__note"></div>

                        <div class="test-card__test-params param" style="display: none;">
                            <svg class="svg-icon--speed svg-icon" width="30" height="18">
                                <use xlink:href="#speed"></use>
                            </svg>1745 kbit/s
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="device-test-grid__item">
            <h2 class="icon-title"><svg class="svg-icon--microphone-2 svg-icon" width="38" height="59">
                    <use xlink:href="#microphone-2"></use>
                </svg>Микрофон</h2>
            <div class="test-card win win win--grey">
                <div class="test-card__top win__top"></div>
                <div class="test-card__inner win__inner">
                    <div class="test-card__media test-card__media--bg">
                        <div class="audio-progress" id="test-audio-output-level">
                            <div class="audio-progress__item"></div> <!-- _filled -->
                            <div class="audio-progress__item"></div> <!-- _filled _active -->
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
                            <div class="audio-progress__item"></div>
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
                    <div class="test-card__device">
                        <div class="select-wrap">
                            <select class="js-select" id="audioSource">
                                <option value="1">Микрофон 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="test-card__footer">
                        <button type="button"
                                id="btn-test-audio"
                                class="btn primary-btn primary-btn--c6 md-btn round-btn video-test-btn"
                                data-start-test="Начать тест"
                                data-stop-test="Завершить"
                                data-status="ready">
                            Начать тест
                        </button>
                        <div class="test-card__note test-card-audio__note">Скажите что-нибудь в микрофон и шкала должна изменится</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="device-test-grid__item device-test-grid__item--wide">
            <h2 class="icon-title"><svg class="svg-icon--volume svg-icon" width="48" height="48">
                    <use xlink:href="#volume"></use>
                </svg>Динамики</h2>
            <div class="test-card test-card--wide win win win--grey">
                <div class="test-card__top win__top"></div>
                <div class="test-card__inner win__inner">
                    <div class="test-card__device">
                        <div class="select-wrap">
                            <select class="js-select" id="audioOutput">
                                <option value="1">Динамик 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="test-card__note">Нажмите кнопку Воспроизвести и вы должны услышать звук из динамиков</div>
                    <audio src="/assets/smartsing-min/sounds/calibrating.mp3" id="audio-output-test"></audio>
                    <button class="btn primary-btn primary-btn--c6 md-btn round-btn" type="button" id="btn-test-audio-output">
                        <svg class="svg-icon--play svg-icon" width="15" height="20">
                            <use xlink:href="#play"></use>
                        </svg>Воспроизвести
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>