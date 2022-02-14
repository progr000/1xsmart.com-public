<?php

/** @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Html::encode(Yii::t('static/support', 'title', ['APP_NAME' => Yii::$app->name]));

?>
<div class="content">
    <h1 class="page-title text-center"><?= Yii::t('static/support', 'Welcome_to_Support', ['APP_NAME' => Yii::$app->name]) ?></h1>
    <div class="support">
        <div class="support__main">
            <p><?= Yii::t('static/support', 'If_have_questions') ?></p>
            <p><?= Yii::t('static/support', 'There_is_probably') ?></p>
            <div class="btns btns--left">
                <button class="secondary-btn wide-mob-btn js-open-modal"
                        type="button"
                        data-modal-id="faq-popup"><?= Yii::t('static/support', 'Go_to_FAQ') ?></button>
            </div>
        </div>
        <div class="support__sidebar">
            <div class="support-card">
                <div class="support-card__caption">
                    <div class="support-card__title"><?= Yii::t('static/support', 'Customer_Support') ?></div>
                    <ul class="support-card__list">
                        <li>
                            <a class="js-open-modal void-0" href="#" data-modal-id="faq-popup">
                                <div class="icon-wrap">
                                    <svg class="svg-icon-conversation svg-icon" width="26" height="26">
                                        <use xlink:href="#conversation"></use>
                                    </svg>
                                </div><?= Yii::t('static/support', 'FAQ_long') ?>
                            </a>
                        </li>
                        <li>
                            <a class="js-open-modal void-0" href="#" data-modal-id="contact-popup">
                                <div class="icon-wrap">
                                    <svg class="svg-icon-letter svg-icon" width="24" height="23">
                                        <use xlink:href="#letter"></use>
                                    </svg>
                                </div><?= Yii::t('static/support', 'Contact_Us') ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <picture>
                    <source srcset="/assets/xsmart-min/images/operator-mob.png" media="(max-width: 480px)">
                    <img class="support-card__img lazy" src="/assets/xsmart-min/images/operator.png" alt="" role="presentation" />
                </picture>
            </div>
        </div>
    </div>
</div>