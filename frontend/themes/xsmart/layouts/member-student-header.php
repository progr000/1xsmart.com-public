<?php

/** @var $static_action string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use frontend\widgets\langSwitch\langSwitchWidget;
use frontend\widgets\currencySwitch\currencySwitchWidget;
use common\models\Users;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;

?>
<!-- begin .page-header-->
<header class="page-header page-header--member">
    <div class="page-header__top">
        <a class="page-header__logo" href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>">
            <img src="/assets/xsmart-min/images/logo.svg" alt="">
            <img src="/assets/xsmart-min/images/logo-white.svg" alt="">
        </a>
        <div class="page-header__inner">
            <a class="page-header__link <?= ($static_action == "find-tutors") ? 'void-0 _current' : '' ?>"  href="<?= Url::to(['/find-tutors'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Find_tutors') ?></a>
            <div class="page-header__lessons">
                <div class="page-header__lessons-count"><?= Yii::t('app/header', 'Available_lessons', ['count' => $CurrentUser->user_lessons_available + $CurrentUser->_user_lessons_assigned]) ?></div>
                <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                    <a class="page-header__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn void-0 js-alert"
                       data-alert-text="<?= Yii::t('app/header', 'Available_after_introductory_lesson') ?>"
                       href="#"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE || $CurrentUser->after_payment_action == Users::AFTER_INTRO_ACTION) { ?>
                    <a class="page-header__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn"
                       href="<?= Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT || $CurrentUser->after_payment_action == Users::AFTER_PACKAGE_ACTION) { ?>
                    <a class="page-header__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn -js-scroll-to"
                       href="<?= Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Setup_schedule') ?></span></a>
                <?php } elseif ($CurrentUser->user_status == Users::STATUS_ACTIVE) { ?>
                    <a class="page-header__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn -js-scroll-to"
                       href="<?= Url::to(['student/finance'/*, '#' => 'price'*/], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                <?php } ?>
            </div>
        </div>
        <div class="page-header__controls">
            <!-- begin CURRENCY -->
            <?= currencySwitchWidget::widget(['theme' => 'dark']) ?>
            <!-- begin CURRENCY -->
            <!-- begin LANG -->
            <?= langSwitchWidget::widget(['theme' => 'dark']) ?>
            <!-- end LANG -->
            <div class="current-state">
                <svg class="svg-icon-time svg-icon" width="20" height="20">
                    <use xlink:href="#time"></use>
                </svg>
                <div class="current-state__data"
                     data-time-zone="<?= $CurrentUser->user_timezone ?>"
                     data-timezone-short-name="<?= $CurrentUser->_user_timezone_short_name ?>"
                     id="real-clock">
                    <div class="current-state__date"><span id="real-clock-date"><?= date(Yii::$app->params['date_format'], $CurrentUser->_user_local_time)?></span>,</div>
                    <div class="current-state__time">
                        <span id="real-clock-time"><?= date(Yii::$app->params['time_format'], $CurrentUser->_user_local_time)?></span>
                        <span><?= $CurrentUser->_user_timezone_short_name ?></span>
                    </div>
                </div>
            </div>
            <div class="user-controls">
                <div class="user-controls__notify">
                    <button class="user-controls__btn user-controls__btn--notify js-open-panel js-open-notify -_has-new"
                            id="btn-open-notify"
                            data-total_count_new_notifications="0"
                            type="button">
                        <svg class="svg-icon-notification svg-icon" width="15" height="20">
                            <use xlink:href="#notification"></use>
                        </svg>
                    </button>
                    <div class="user-controls__notify-holder panel js-panel" id="notify-list-popup">
                        <div class="notify-list" id="notify-list-container">
                            <!--
                            <div class="notify">
                                <div class="notify__text">Payment received</div>
                                <div class="notify__meta">11:59 20 oct</div>
                            </div>
                            <div class="notify notify--warning">
                                <div class="notify__text">Payment canceled</div>
                                <div class="notify__meta">11:59 20 oct</div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
                <button class="user-controls__btn user-controls__btn--chat -_has-new js-open-modal js-open-chat"
                        type="button"
                        data-total_count_new_opponents="0"
                        data-total_count_new_messages="0"
                        data-modal-id="chat">
                    <svg class="svg-icon-chat-bubble svg-icon" width="18" height="18">
                        <use xlink:href="#chat-bubble"></use>
                    </svg>
                </button>
                <div class="user-controls__menu">
                    <img class="user-controls__menu-ava header-ava any-place-user-ava"
                         src="<?= $CurrentUser->getProfilePhotoForWeb('/assets/xsmart-min/images/upload_your_photo.png') ?>"
                         alt=""
                         role="presentation" />
                    <button class="user-controls__menu-btn js-open-panel" type="button"></button>
                    <div class="user-controls__menu-holder panel js-panel">
                        <div class="user-controls__menu-inner">
                            <div class="user-controls__section">
                                <a class="user-controls__link <?= ($static_action == "find-tutors") ? 'void-0 _current' : '' ?>"  href="<?= Url::to(['/find-tutors'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Find_tutors') ?></a>
                            </div>
                            <div class="user-controls__section">
                                <div class="user-controls__lessons-count"><span>Available:</span><span><?= $CurrentUser->user_lessons_available + $CurrentUser->_user_lessons_assigned ?> lessons</span></div>
                                <?php if ($CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) { ?>
                                    <a class="user-controls__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn void-0 js-alert"
                                       data-alert-text="<?= Yii::t('app/header', 'Available_after_introductory_lesson') ?>"
                                       href="#"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                                <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE || $CurrentUser->after_payment_action == Users::AFTER_INTRO_ACTION) { ?>
                                    <a class="user-controls__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn"
                                       href="<?= Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                                <?php } elseif ($CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT || $CurrentUser->after_payment_action == Users::AFTER_PACKAGE_ACTION) { ?>
                                    <a class="user-controls__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn -js-scroll-to"
                                       href="<?= Url::to(['student/set-schedule'], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Setup_schedule') ?></span></a>
                                <?php } else { ?>
                                    <a class="page-header__lesson-buy-btn primary-btn primary-btn primary-btn--accent sm-btn -js-scroll-to"
                                       href="<?= Url::to(['student/finance'/*, '#' => 'price'*/], CREATE_ABSOLUTE_URL) ?>"><span><?= Yii::t('app/header', 'Buy_lessons') ?></span></a>
                                <?php } ?>
                            </div>
                            <div class="user-controls__section user-controls__section--sm-pad">
                                <!-- begin CURRENCY -->
                                <?= currencySwitchWidget::widget() ?>
                                <!-- begin CURRENCY -->
                                <!-- begin LANG -->
                                <?= langSwitchWidget::widget() ?>
                                <!-- end LANG -->
                            </div>
                            <div class="user-controls__menu-list">
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "device-test") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['user/device-test'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Audio_Video_test') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "finance") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['student/finance'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Finance') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "settings-and-profile") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['user/settings-and-profile'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Settings') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link" href="<?= Url::to(['/support'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Support') ?></a></div>
                                <div class="user-controls__menu-item --user-controls__menu-item--logout"><a class="user-controls__menu-link" href="<?= Url::to(['/logout'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Log_out') ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end .page-header-->
