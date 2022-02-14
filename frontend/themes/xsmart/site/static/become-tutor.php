<?php

/** @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Html::encode(Yii::t('static/become-tutor', 'title', ['APP_NAME' => Yii::$app->name]));

?>

<div class="appeal">
    <div class="appeal__inner">
        <div class="appeal__caption">
            <h1 class="appeal__title"><?= Yii::t('static/become-tutor', 'Teach_online') ?></h1>
            <div class="appeal__intro"><?= Yii::t('static/become-tutor', 'Site_is_looking', ['APP_NAME' => Yii::$app->name]) ?></div>
            <button class="appeal__action-btn primary-btn wide-mob-btn js-open-modal signup-as-tutor-button"
                    type="button"
                    data-modal-id="signup-popup"><?= Yii::t('static/become-tutor', 'Sign_up_as_tutor') ?></button>
        </div>
        <div class="appeal__media"><img src="/assets/xsmart-min/files/money-girl.png" alt="" role="presentation" /></div>
    </div>
</div>

<section class="page-section page-section--waves">
    <div class="page-section__inner">
        <h2 class="page-section__title"><?= Yii::t('static/become-tutor', 'Who_we_looking') ?></h2>
        <div class="features">
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/cap.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'Tutors_of_any_discipline') ?></div>
                </div>
            </div>
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/book.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'You_like') ?></div>
                </div>
            </div>
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/smiley-face.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'You_welcoming') ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section">
    <h2 class="page-section__title"><?= Yii::t('static/become-tutor', 'What_we_expecting') ?></h2>
    <div class="page-section__inner">
        <div class="features">
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/gears.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'Good_understanding') ?></div>
                </div>
            </div>
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/responsive.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'PC_or_smartphone') ?></div>
                </div>
            </div>
            <div class="features__item features__item--wide">
                <div class="feature">
                    <div class="feature__icon-wrap"><img src="/assets/xsmart-min/images/features/dollar.svg" alt=""></div>
                    <div class="feature__title"><?= Yii::t('static/become-tutor', 'Desire_to_work') ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section page-section--bg">
    <div class="page-section__inner">
        <h2 class="page-section__title"><?= Yii::t('static/become-tutor', 'Warranties') ?></h2>
        <div class="warranties mob-scrolling js-mob-scrolling">
            <div class="warranties__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/students.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Pupil_stream') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'No_more_searching') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="warranties__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/sofa.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Convenience') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'You_need') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="warranties__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/dollars.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Payment') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'Set_up_own') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="warranties__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/lifebuoy.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Help') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'If_you_have_questions') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section page-section--accent-bg page-section page-section--no-top-margin">
    <div class="page-section__inner">
        <h2 class="page-section__title"><?= Yii::t('static/become-tutor', 'Join_our_team') ?></h2>
        <div class="join mob-scrolling js-mob-scrolling">
            <div class="join__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/click.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Sign_up_as_tutor_free') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="join__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/video-call.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Make_photo') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'Upload_photo') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="join__item">
                <div class="screen screen--sm">
                    <div class="screen__header"></div>
                    <div class="screen__body">
                        <div class="feature">
                            <div class="feature__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/user.svg" alt="" /></div>
                            <div class="feature__title"><?= Yii::t('static/become-tutor', 'Await_approval') ?></div>
                            <div class="feature__desc"><?= Yii::t('static/become-tutor', 'Approval_process') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-section__btns"><button class="light-btn js-open-modal signup-as-tutor-button" type="button" data-modal-id="signup-popup"><?= Yii::t('static/become-tutor', 'Become_a_tutor') ?></button></div>
    </div>
</section>