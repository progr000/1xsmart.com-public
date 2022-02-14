<?php

/** @var $this yii\web\View */

use common\helpers\Functions;

$youtube_default_video_id = Functions::getYoutubeVideoID(Yii::$app->params['default_teacher_youtube_video']);
?>
<div class="modal modal--sm-pad" id="choose-teacher-modal">
    <div class="modal__content scroll-wrapper js-scroll">
        <div class="modal__inner scroll-content">
            <div class="modal__title">Карточка преподавателя</div>
            <div class="user-v-card">
                <div class="user-v-card__top">
                    <div class="user-info">
                        <div class="user-info__user">
                            <img class="user_photo user-info__ava"
                                 src="/assets/smartsing-min/images/no_photo.png"
                                 alt=""
                                 role="presentation" />
                            <div class="user_first_name user-info__name">{user_first_name}</div>
                        </div>
                        <div class="user-info__data">
                            <div class="user-info__data-item">
                                <div class="user-info__data-item-label">Возраст</div>
                                <div class="user-info__data-item-value"><span class="user_age">{user_age}</span></div>
                            </div>
                            <!--
                            <div class="user-info__data-item">
                                <div class="user-info__data-item-label">Город</div>
                                <div class="user-info__data-item-value">Сочи, Россия</div>
                            </div>
                            -->
                            <div class="user-info__data-item">
                                <div class="user-info__data-item-label">Любымые жанры музыки:</div>
                                <div class="user_music_genres user-info__data-item-value">{user_music_genres}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-v-card__bottom">
                    <div class="user-desc">
                        <p class="admin_notice">{admin_notice}</p>
                    </div>
                    <div class="video- video-about-teacher" id="video-about-teacher" style="display: none;">
                        <a class="youtube_video video__link youtube_video_id"
                           data-off-href="{user_youtube_video}"
                           data-youtube_video_id="<?= $youtube_default_video_id ?>"
                           href="<?= Yii::$app->params['default_teacher_youtube_video'] ?>">
                            <picture>
                                <source class="youtube_image_webp"
                                        data-off-srcset="{youtube_image_webp}"
                                        srcset="https://i.ytimg.com/vi_webp/<?= $youtube_default_video_id ?>/maxresdefault.webp"
                                        type="image/webp">
                                <img class="youtube_image_jpg video__media"
                                     data-off-src="{youtube_image_jpg}"
                                     src="https://i.ytimg.com/vi/<?= $youtube_default_video_id ?>/maxresdefault.jpg"
                                     alt="">
                            </picture>
                        </a>
                        <div class="video__header">
                            <div class="video__btn-wrap">
                                <button class="video__btn btn" aria-label="Запустить видео">
                                    <svg class="svg-icon--play svg-icon" width="12" height="16">
                                        <use xlink:href="#play"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="video__title">Видеоприветствие</div>
                        </div>
                    </div>

                    <div class="video- video-about-teacher-" id="video-about-teacher-local" style="display: none;">
                        <video id="user-local-video" width="100%" height="auto" src="" controls>
                            Your browser does not support the video tag.
                        </video>
                    </div>

                </div>
            </div>
            <div class="modal__footer">
                <button class="teacher_user_id coach-v-card__submit btn primary-btn primary-btn--c6 md-btn js-select-teacher"
                        type="button"
                        data-teacher_user_id="{teacher_user_id}">Выбрать преподавателя</button>
            </div>
        </div>
        <button class="btn modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon--close-2 svg-icon" width="30" height="30">
                <use xlink:href="#close-2"></use>
            </svg>
        </button>
    </div>
</div>
