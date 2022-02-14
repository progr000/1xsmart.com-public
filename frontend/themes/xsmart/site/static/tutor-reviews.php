<?php

/** @var $this yii\web\View */
/** @var $reviews array */
/** @var $CurrentUser \common\models\Users */
/** @var $tutor \common\models\Users */

use common\helpers\Functions;


if (is_array($reviews) && sizeof($reviews)) {
    ?>
    <div class="testimonials-slider js-tutor-data-rating-reviews js-carousel"
         data-rating="<?= $tutor->user_rating ?>"
         data-reviews-count="<?= $tutor->user_reviews ?>"
         id="js-reviews-carousel">
        <?php
        foreach ($reviews as $review) {
            /** @var \common\models\Reviews $review */
            /** @var \common\models\Users $student */
            $student = $review->getStudentUser();
            ?>

            <div class="testimonial testimonials-slider__item">
                <div class="testimonial__top">
                    <div class="testimonial__ava testimonial__ava_50x50"><img
                            src="<?= $student->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') ?>"
                            alt=""></div>
                    <div class="testimonial__name"><?= $student->user_full_name ?></div>
                    <div class="testimonial__rating mark js-mark"
                         title="<?= round($review->review_rating) ?>"
                         data-mark="<?= round($review->review_rating) ?>"></div>
                </div>
                <div class="testimonial__body">
                    <?= nl2br(Functions::formatLongString($review->review_text)) ?>
                </div>
                <div class="testimonial__footer">
                    <div class="testimonial__date">
                        <?= $CurrentUser
                            ? $CurrentUser->getDateInUserTimezoneByDateString($review->review_created, Yii::$app->params['date_format'], false)
                            : date(Yii::$app->params['date_format'], strtotime($review->review_created)) ?>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
    <div class="slider-nav slider-nav--aside">
        <button class="slider-nav-btn slider-nav-btn--prev js-prev" type="button"></button>
        <button class="slider-nav-btn slider-nav-btn--next js-next" type="button"></button>
    </div>
    <?php
} else {
    ?>
    <div class="testimonials-empty js-tutor-data-rating-reviews"
         data-rating="<?= $tutor->user_rating ?>"
         data-reviews-count="<?= $tutor->user_reviews ?>">
        <?= Yii::t('static/tutor', 'No_reviews') ?>
    </div>
    <?php
}
?>