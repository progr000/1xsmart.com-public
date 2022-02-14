<?php

/** @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode(Yii::t('static/index', 'title', ['APP_NAME' => Yii::$app->name]));

$this->params['additional_header_class'] = 'page-header--bordered';
$this->params['additional_header_promo'] = '
<div class="promo promo--top">
    <div class="promo__inner">
        <div class="promo__text">
            <div class="promo__title">' . Yii::t('static/index', 'Best_online_tutors') . '</div>
            <a class="primary-btn wide-mob-btn" href="' . Url::to(['/find-tutors'], CREATE_ABSOLUTE_URL) . '">' . Yii::t('static/index', 'Find_a_tutor') . '</a>
        </div>
    </div>
    <div class="promo__img-wrap">
        <picture>
            <source srcset="/assets/xsmart-min/files/promo/promo-1_600x396.jpg" media="(max-width: 600px)">
            <img class="promo__img" src="/assets/xsmart-min/files/promo/promo-1_960x600.jpg" alt="" role="presentation" />
        </picture>
    </div>
</div>
';
?>

<div class="container">
    <section class="page-section">
        <h2 class="page-section__title"><?= Yii::t('static/index', 'Site_pros', ['APP_NAME' => Yii::$app->name]) ?></h2>
        <div class="features">
            <div class="features__item">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/expert.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/index', 'Expert_tutors') ?></div>
                    <div class="feature__desc"><?= Yii::t('static/index', 'Every_tutor_passed') ?></div>
                </div>
            </div>
            <div class="features__item">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/sofa.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/index', 'Comfort_learning') ?></div>
                    <div class="feature__desc"><?= Yii::t('static/index', 'Take_lessons_at') ?></div>
                </div>
            </div>
            <div class="features__item">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/best-price.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/index', 'Reasonable_prices') ?></div>
                    <div class="feature__desc"><?= Yii::t('static/index', 'You_can_choose') ?></div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="page-section page-section--bg">
    <div class="page-section__inner">
        <h2 class="page-section__title"><?= Yii::t('static/index', 'Site_connects', ['APP_NAME' => Yii::$app->name]) ?></h2>
        <div class="steps">
            <div class="steps__item">
                <div class="screen">
                    <div class="screen__header">
                        <div class="screen__num">1</div>
                    </div><img class="screen__img lazy" src="/assets/xsmart-min/files/steps/step-1_394x267.jpg" alt="" role="presentation" />
                </div>
                <div class="steps__tick"></div>
                <div class="steps__title"><?= Yii::t('static/index', 'Find_the_tutor') ?></div>
            </div>
            <div class="steps__item">
                <div class="screen">
                    <div class="screen__header">
                        <div class="screen__num">2</div>
                    </div><img class="screen__img lazy" src="/assets/xsmart-min/files/steps/step-2_394x267.jpg" alt="" role="presentation" />
                </div>
                <div class="steps__tick"></div>
                <div class="steps__title"><?= Yii::t('static/index', 'Book_the_lesson') ?></div>
            </div>
            <div class="steps__item">
                <div class="screen">
                    <div class="screen__header">
                        <div class="screen__num">3</div>
                    </div><img class="screen__img lazy" src="/assets/xsmart-min/files/steps/step-3_394x267.jpg" alt="" role="presentation" />
                </div>
                <div class="steps__tick"></div>
                <div class="steps__title"><?= Yii::t('static/index', 'Learn_regularly') ?></div>
            </div>
        </div>
    </div>
</section>

<div class="promo promo--right">
    <div class="promo__inner">
        <div class="promo__text">
            <div class="promo__title"><?= Yii::t('static/index', 'Get_the_new') ?></div>
            <ul class="linked-list">
                <li><img src="/assets/xsmart-min/images/features/tie.svg">
                    <div><span>Business</span><span><?= Yii::t('static/index', 'Be_more') ?></span></div>
                </li>
                <li><img src="/assets/xsmart-min/images/features/earth.svg">
                    <div><span>Traveling</span><span><?= Yii::t('static/index', 'Feel_confident') ?></span></div>
                </li>
                <li><img src="/assets/xsmart-min/images/features/meditation.svg">
                    <div><span>Feeling of oneself</span><span><?= Yii::t('static/index', 'Success_skills') ?></span></div>
                </li>
            </ul>
        </div>
    </div>
    <div class="promo__img-wrap"><img class="promo__img lazy" src="/assets/xsmart-min/files/promo/promo-2_960x600.jpg" alt="" role="presentation" /></div>
</div>

<section class="page-section">
    <div class="page-section__inner">
        <h2 class="page-section__title page-section__title--sm-margin"><?= Yii::t('static/index', 'Need_help') ?></h2>
        <div class="text-center">
            <p class="lg-size"><?= Yii::t('static/index', 'Fill_the_form') ?></p>
        </div>
    </div>
</section>

<section class="page-section page-section--dark">
    <div class="page-section__inner">
        <div class="find-tutor">
            <div class="find-tutor__title"><?= Yii::t('static/index', 'Ready') ?></div>
            <a class="primary-btn wide-mob-btn" href="<?= Url::to(['/find-tutors'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('static/index', 'Find_a_tutor') ?></a>
            <div class="find-tutor__guarantee guarantee-block">
                <div><?= Yii::t('static/index', 'And_be_sure') ?></div>
                <div class="guarantee guarantee--lg">
                    <picture>
                        <source srcset="/assets/xsmart-min/images/guarantee_99x80.png" media="(max-width: 480px)">
                        <img src="/assets/xsmart-min/images/guarantee.png">
                    </picture>
                    <div><span><?= Yii::t('app/header', 'Guarantee2') ?></span><span><?= Yii::t('static/index', 'We_guarantee_100') ?></span></div>
                </div>
            </div>
        </div>
    </div>
</section>