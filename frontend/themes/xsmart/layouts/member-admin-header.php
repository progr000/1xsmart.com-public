<?php

/** @var $static_action string */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use frontend\widgets\langSwitch\langSwitchWidget;
use frontend\widgets\currencySwitch\currencySwitchWidget;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;

if (isset($_GET['target']) && isset($this->context->target_logs[$_GET['target']])) {
    $target_log = $_GET['target'];
} else {
    $target_log = '';
}

?>
<!-- begin .page-header-->
<header class="page-header page-header--member page-header page-header--admin">
    <div class="page-header__top">
        <a class="page-header__logo" href="<?= Url::to(['/'], CREATE_ABSOLUTE_URL) ?>">
            <img src="/assets/xsmart-min/images/logo.svg" alt="">
            <img src="/assets/xsmart-min/images/logo-white.svg" alt="">
        </a>
        <div class="page-header__inner">
            <div class="page-header__title"><?= Yii::t('app/header', 'Admin_area') ?></div>
            <?php
            if (in_array($action_id, ['index', 'teachers-list'])) {
                ?>
                <button class="page-header__add-student-btn primary-btn primary-btn primary-btn--accent sm-btn -js-open-modal js-open-edit-student-popup"
                        type="button"
                        data-student_user_id="0"
                        data-modal-id="add-edit-student-popup"><?= Yii::t('app/header', 'Add_student') ?></button>
                <button class="page-header__add-teacher-btn primary-btn primary-btn primary-btn--accent sm-btn js-open-modal"
                        type="button"
                        data-modal-id="add-edit-teacher-popup"><?= Yii::t('app/header', 'Add_teacher') ?></button>
                <div class="page-header__info-data-wrap">
                    <!--
                    <div class="page-header__info-data info-data"><span><?= Yii::t('app/header', 'Users_online') ?></span><span>7</span></div>
                    <div class="page-header__info-data info-data"><span><?= Yii::t('app/header', 'Users_logged_in') ?></span><span>12</span></div>
                    -->
                </div>
                <?php
            }
            ?>
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
                    <button class="user-controls__btn user-controls__btn--notify js-open-panel hidden" type="button">
                        <svg class="svg-icon-notification svg-icon" width="15" height="20">
                            <use xlink:href="#notification"></use>
                        </svg>
                    </button>
                    <div class="user-controls__notify-holder panel js-panel">
                        <div class="notify-list">
                            <div class="notify">
                                <div class="notify__text">Payment received</div>
                                <div class="notify__meta">11:59 20 oct</div>
                            </div>
                            <div class="notify">
                                <div class="notify__text">Payment received</div>
                                <div class="notify__meta">11:59 20 oct</div>
                            </div>
                            <div class="notify notify--warning">
                                <div class="notify__text">Payment canceled</div>
                                <div class="notify__meta">11:59 20 oct</div>
                            </div>
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
                            <div class="user-controls__section user-controls__section--sm-pad">
                                <!-- begin CURRENCY -->
                                <?= currencySwitchWidget::widget() ?>
                                <!-- begin CURRENCY -->
                                <!-- begin LANG -->
                                <?= langSwitchWidget::widget() ?>
                                <!-- end LANG -->
                            </div>
                            <div class="user-controls__menu-list">
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "index") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Students_list') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "teachers-list") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin/teachers-list'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Teachers_list') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "finance") ? 'void-0 _current' : '' ?>"" href="<?= Url::to(['/admin/finance'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Finance') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "mailq") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin/mailq'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Mail Log') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "file-log" && $target_log == 'yii') ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin/file-log', 'target' => 'yii'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Yii Log') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "file-log" && $target_log == 'php') ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin/file-log', 'target' => 'php'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'PHP Log') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "file-log" && $target_log == 'tinkoff') ? 'void-0 _current' : '' ?>" href="<?= Url::to(['/admin/file-log', 'target' => 'tinkoff'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Tinkoff Log') ?></a></div>
                                <div class="user-controls__menu-item"><a class="user-controls__menu-link <?= ($action_id == "settings-and-profile") ? 'void-0 _current' : '' ?>" href="<?= Url::to(['user/settings-and-profile'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/header', 'Settings') ?></a></div>
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
