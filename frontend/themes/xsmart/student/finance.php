<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $Teachers \common\models\Users[] */
/** @var $Transactions array*/

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use common\helpers\Functions;
use common\models\Users;
use common\models\StudentsTimeline;
use frontend\assets\xsmart\student\AfterIntroduceAsset;
use frontend\models\search\FinanceSearch;

AfterIntroduceAsset::register($this);

$this->title = Html::encode(Yii::t('app/finance', 'title'));

//$CurrentUser->user_lessons_available + $CurrentUser->_user_lessons_assigned
?>

<!-- PACKAGE PRICE -->
<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['student/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
    <div class="crumbs__title"><?= Yii::t('app/finance', 'title') ?></div>
</div>
<div data-off-style="display: none;" class="tutor-choice container">
    <div class="check-row check-row--centered">
        <?php
        $checked = ' checked="checked"';
        foreach ($Teachers as $teacher) {
            /** @var $teacher \common\models\Users */
            $teacher->initAdditionalDataForModel();

            $res = [];
            foreach (StudentsTimeline::$discount_vars as $vars) {
                $res["usd_cost_hour_if_by_{$vars['lessons_count']}_hours"] = number_format(round($teacher->user_price_peer_hour - $teacher->user_price_peer_hour * $vars['discount_percent'] / 100, 2), 2, '.', '');
                $res["usd_total_price_for_{$vars['lessons_count']}_hours"] = number_format(round($res["usd_cost_hour_if_by_{$vars['lessons_count']}_hours"] * $vars['lessons_count'], 2), 2, '.', '');
                $res["usd_save_if_by_{$vars['lessons_count']}_hours"] = number_format(round($teacher->user_price_peer_hour * $vars['lessons_count'] - $res["usd_total_price_for_{$vars['lessons_count']}_hours"], 2), 2, '.', '');

                $res["cost_hour_if_by_{$vars['lessons_count']}_hours"] = number_format(round(Functions::getInCurrency($teacher->user_price_peer_hour)['sum'] - Functions::getInCurrency($teacher->user_price_peer_hour)['sum'] * $vars['discount_percent'] / 100, 2), 2, '.', '');
                $res["total_price_for_{$vars['lessons_count']}_hours"] = number_format(round($res["cost_hour_if_by_{$vars['lessons_count']}_hours"] * $vars['lessons_count'], 2), 2, '.', '');
                $res["save_if_by_{$vars['lessons_count']}_hours"] = number_format(round(Functions::getInCurrency($teacher->user_price_peer_hour)['sum'] * $vars['lessons_count'] - $res["total_price_for_{$vars['lessons_count']}_hours"], 2), 2, '.', '');

            }
            //var_dump($res);
            ?>
            <div class="check-wrap">
                <input class="accent-radio teachers-variant"
                       type="radio"
                       name="coach"
                       <?= $checked ?>
                       data-teacher_user_id="<?= $teacher->user_id ?>"
                       data-user_price_peer_hour="<?= $teacher->user_price_peer_hour ?>"
                       data-teacher_display_name="<?= $teacher->_user_display_name ?>"
                       data-teacher_rating="<?= $teacher->user_rating ?>"
                       data-teacher_reviews="<?= $teacher->user_reviews ?>"
                       data-teacher_ava="<?= $teacher->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') ?>"
                       data-teacher_location="<?= Functions::concatCountryCityName($teacher->___country_name, $teacher->___city_name) ?>"
                       data-teacher_country_img="<?= Functions::getCountryImage($teacher->___country_code) ?>"
                       <?php
                       foreach ($res as $k=>$v) {
                           echo "data-{$k}=\"{$v}\"\n";
                       }
                       ?>
                       id="coach-<?= $teacher->user_id ?>" />
                <label class="tutor-check-label" for="coach-<?= $teacher->user_id ?>">
                    <span></span>
                    <div class="tutor-brief">
                        <div class="tutor-brief__ava">
                            <img class="tutor-brief__img after-introduce-ava"
                                 src="<?= $teacher->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') ?>"
                                 alt=""
                                 role="presentation" />
                            <div class="tutor-brief__rating rating"><?= $teacher->user_rating ?></div>
                        </div>
                        <div class="tutor-brief__info">
                            <div class="tutor-brief__name"><?= $teacher->_user_display_name ?></div>
                            <div class="tutor-brief__reviews"><?= Yii::t('app/finance', 'reviews', ['count' => $teacher->user_reviews]) ?></div>
                            <div class="tutor-brief__location location">
                                <img src="<?= Functions::getCountryImage($teacher->___country_code) ?>" alt="">
                                <span><?= Functions::concatCountryCityName($teacher->___country_name, $teacher->___city_name) ?></span>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <?php
            $checked = '';
        }
        ?>
    </div>
</div>
<section data-off-style="display: none;" class="page-section page-section--bg page-section page-section--sm-pad page-section page-section--no-top-margin">
    <div class="page-section__inner">
        <h2 class="page-section__title"><?= Yii::t('app/finance', 'Obtain_packages_') ?></h2>
        <div class="tariffs mob-scrolling mob-scrolling mob-scrolling--mob-stack js-mob-scrolling">

            <?php
            foreach (StudentsTimeline::$discount_vars as $vars) {
                ?>
                <div class="tariffs__item">
                    <div class="screen screen--sm">
                        <?=
                        isset($vars['additional_header'])
                            ? '<div class="screen__header screen__header--active"><div class="screen__header-title">' . Yii::t('app/common', $vars['additional_header']) . '</div></div>'
                            : '<div class="screen__header"></div>'
                        ?>
                        <div class="screen__body">
                            <div class="tariff">
                                <div class="tariff__icon-wrap"><img class="lazy" src="/assets/xsmart-min/images/features/price-image-<?= $vars['lessons_count'] ?>.svg" alt="" /></div>
                                <div class="tariff__term"><?= $vars['lessons_count'] ?> <?= Functions::in_hours_ru_text($vars['lessons_count'])[0] ?></div>
                                <div class="tariff__title"><?= $vars['name'] ?></div>
                                <div class="tariff__rate rate"><span id="cost_hour_if_by_<?= $vars['lessons_count'] ?>_hours">{num}</span>&nbsp;<?= Functions::getInCurrency(1)['name_lover'] ?> <?= Yii::t('app/finance', 'per_hour') ?></div>
                                <?=
                                isset($res["save_if_by_{$vars['lessons_count']}_hours"]) && doubleval($res["save_if_by_{$vars['lessons_count']}_hours"] > 0.0)
                                    ? '<div class="tariff__free">' . Yii::t('app/finance', 'You_save') .' <span id="save_if_by_' . $vars['lessons_count'] . '_hours">{num}</span> ' .  Functions::getInCurrency(1)['name_lover'] . '</div>'
                                    : ''
                                ?>
                                <button class="tariff__btn primary-btn primary-btn primary-btn--accent js-prepare-tinkoff-payment -js-open-modal"
                                        type="button"
                                        id="button-total_price_for_<?= $vars['lessons_count'] ?>_hours"
                                        data-currency="<?= Functions::getInCurrency(1)['name_lover'] ?>"
                                        data-lesson_cost="{num}"
                                        data-total_price="{num}"
                                        data-teacher_user_id="{teacher_user_id}"
                                        data-lessons_count="<?= $vars['lessons_count'] ?>"
                                        data-description="<?= Yii::t('app/finance', 'For_lessons_package', ['package_name' => $vars['name'], 'count' => $vars['lessons_count']]) ?>"
                                        data-description-en="For lessons package #<?= $vars['name'] ?># with {teacher} (teacher_user_id: {teacher_user_id}, lessons count: <?= $vars['lessons_count'] ?>)"
                                        data-modal-id="booking-popup"><?= Yii::t('app/finance', 'Buy_now') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="guarantee-block">
            <div><?= Yii::t('app/finance', 'And_be_sure') ?></div>
            <div class="guarantee guarantee--lg">
                <picture>
                    <source srcset="/assets/xsmart-min/images/guarantee_99x80.png" media="(max-width: 480px)"><img src="/assets/xsmart-min/images/guarantee.png">
                </picture>
                <div>
                    <span><?= Yii::t('app/finance', 'Guarantee') ?></span>
                    <span><?= Yii::t('app/finance', 'We_guarantee_100') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRANSACTION HISTORY -->
<div class="content">
    <section class="page-section page-section page-section--no-top-margin">
        <h2 class="page-section__title"><?= Yii::t('app/finance', 'Transaction_history') ?></h2>

        <?php Pjax::begin([
            'id' => 'student-finance',
            'timeout' => PJAX_TIMEOUT,
            'options'=> ['tag' => 'div', 'class' => 'transactions own-pagination']
        ]); ?>

        <!--<div class="transactions own-pagination">-->
            <div class="transactions-table">
                <div class="transactions-table__header">
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Date') ?></div>
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Tutor') ?></div>
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Status') ?></div>
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Price') ?></div>
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Balance') ?></div>
                </div>
                <div class="transactions-table__body">

                    <?php
                    foreach ($Transactions['list'] as $transaction) {
                        ?>
                        <div class="transactions-table__tr <?= $transaction['p_type'] == FinanceSearch::INCOMING ? 'incoming' : 'outgoing' ?>">
                            <div class="transactions-table__td" data-th="Date"><?= $CurrentUser->getDateInUserTimezoneByDateString($transaction['p_date'], Yii::$app->params['datetime_short_format'], false) ?></div>
                            <div class="transactions-table__td" data-th="Tutor">
                                <div class="person">
                                    <img class="person__ava" src="<?= Users::staticGetProfilePhotoForWeb($transaction['teacher_photo'], '/assets/xsmart-min/images/no_photo.png') ?>" alt="" role="presentation" />
                                    <div class="person__name"><? Users::getDisplayName($transaction['teacher_first_name'], $transaction['teacher_last_name']) ?></div>
                                </div>
                            </div>
                            <div class="transactions-table__td" data-th="Status">
                                <div class="transaction-state">
                                    <?php
                                    if ($transaction['p_type'] == FinanceSearch::INCOMING) {
                                        echo '<div class="confirmed"><b>+' . $transaction['p_count'] . '</b>&nbsp; ' . Yii::t('app/finance', 'lessons_bought') . '</div>';
                                    } else {
                                        if ($transaction['lesson_status'] == StudentsTimeline::STATUS_FAILED) {
                                            echo '<div>' . Yii::t('app/finance', 'Lesson_not_pass') . '</div>';
                                        } else {
                                            echo '<div>' . Yii::t('app/finance', 'Lesson_passed') . ' (<b>-1</b>)</div>';
                                            if ($transaction['lesson_status'] == StudentsTimeline::STATUS_AWAIT) {
                                                echo '<div class="unconfirmed">' . Yii::t('app/finance', 'Unconfirmed') . '</div>';
                                            } else {
                                                echo '<div class="confirmed">' . Yii::t('app/finance', 'Confirmed') . '</div>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="transactions-table__td" data-th="Price">
                                <?php
                                if ($transaction['p_type'] == FinanceSearch::INCOMING) {
                                    ?>
                                    <div>Total: <?= Functions::getInCurrency($transaction['p_amount'])['sum'] . ' ' . Functions::getInCurrency(1)['name_lover'] ?></div>
                                    <?php
                                } ?>
                                <div><?= Functions::getInCurrency($transaction['p_amount'] / $transaction['p_count'])['sum'] . ' ' . Functions::getInCurrency(1)['name_lover'] . ' ' . Yii::t('app/finance', 'per_hour') ?></div>
                            </div>
                            <div class="transactions-table__td" data-th="Balance">
                                <div><span class="text-light"><?= Yii::t('app/finance', 'Hours_remaining_') ?>&nbsp;</span><span><?= $transaction['hours_remaining'] . '<!-- (' . $transaction['p_count'] .' ) -->' ?></span></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
            <?= LinkPager::widget([

                'pagination' => $Transactions['pagination'],
                // https://github.com/yiisoft/yii2/blob/master/framework/widgets/LinkPager.php

                'options' => [
                    //'tag' => 'div',
                    'class' => 'pages',
                    //'id' => 'pager-container',
                ],

                // Customzing CSS class for pager link
                'linkOptions' => [
                    //'tag' => 'span',
                    'class' => 'pages__item',
                    'href' => '',
                ],
                'activePageCssClass' => 'pages__item--current_',

                // Customzing CSS class for navigating link
                'prevPageCssClass' => 'pages__item--prev_',
                'nextPageCssClass' => 'pages__item--next_',
                'firstPageCssClass' => null,
                'lastPageCssClass' => null,
            ]) ?>

        <!--</div>-->
        <?php Pjax::end(); ?>
    </section>
</div>



<!-- begin booking-popup -->
<div class="modal modal--light" id="booking-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('app/finance', 'Credit_Card') ?></div>
            <div class="modal__desc">
                <p><?= Yii::t('app/finance', 'You_are_going') ?> <b class="teacher_display_name">{teacher_display_name}</b></p>
            </div>
            <div class="tutor-teaser">
                <div class="tutor-teaser__top">
                    <img class="tutor-teaser__ava its-img teacher_ava" src="" alt="" role="presentation" />
                    <div class="tutor-teaser__header">
                        <div class="tutor-teaser__name teacher_display_name">{teacher_display_name}</div>
                        <div class="tutor-teaser__location location">
                            <img class="its-img teacher_country_img" src="" alt="">
                            <span class="teacher_location">{teacher_location}</span>
                        </div>
                    </div>
                </div>
                <div class="tutor-teaser__level">
                    <div class="tutor-teaser__rating rating teacher_rating">{teacher_rating}</div>
                    <div class="tutor-teaser__reviews"><span class="teacher_reviews">{teacher_reviews}</span> <?= Yii::t('app/finance', 'reviews_popup') ?></div>
                </div>
            </div>
            <div class="modal__section-title"><?= Yii::t('app/finance', 'Lesson_details_') ?></div>
            <div class="params-tbl">
                <div class="params-tbl__row"><span><?= Yii::t('app/finance', 'Lessons_count_') ?></span><span class="lessons_count">{lessons_count}</span></div>
                <div class="params-tbl__row"><span><?= Yii::t('app/finance', 'Lesson_duration_') ?></span><span>1 <?= Functions::in_hours_ru_text(1)[0] ?> - <b class="no-bold lesson_cost">{lesson_cost}</b> <?= Functions::getInCurrency(1)['name_lover'] ?></span></div>
                <div class="params-tbl__row"><span><?= Yii::t('app/finance', 'Total_') ?></span><span><b class="no-bold total_price">{total_price}</b> <?= Functions::getInCurrency(1)['name_lover'] ?></span></div>
            </div>
            <form class="modal__form" action="/">
                <div class="modal__submit">
                    <button class="primary-btn wide-mob-btn void-0 js-start-tinkoff-payment"
                            id="payment-params-button"
                            data-currency="<?= Functions::getInCurrency(1)['name_lover'] ?>"
                            data-lesson_cost="{num}"
                            data-total_price="{num}"
                            data-you_save="{num}"
                            data-usd_lesson_cost="{num}"
                            data-usd_total_price="{num}"
                            data-usd_you_save="{num}"
                            data-teacher_user_id="{teacher_user_id}"
                            data-lessons_count="{num}"
                            data-description="{description}"
                            type="button"><?= Yii::t('app/finance', 'Proceed_checkout') ?></button>
                    <div class="guarantee"><img src="/assets/xsmart-min/images/guarantee-sm.png"><span><?= Yii::t('app/finance', 'Guarantee') ?></span></div>
                </div>
            </form>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- end booking-popup -->

<!-- begin tinkoff form -->
<div style="display: none;">
    <form name="TinkoffPayForm" id="tinkoff-payment-form" onsubmit="pay(this); return false;">
        <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="<?= Yii::$app->params['tinkoff_terminal_key'] ?>" />
        <input class="tinkoffPayRow" type="hidden" name="frame" value="true" />
        <input class="tinkoffPayRow" type="hidden" name="language" value="<?= Yii::$app->language ?>" />
        <input class="tinkoffPayRow"
               id="tinkoff-order_amount"
               type="text"
               placeholder="order_amount"
               name="amount"
               value="{amount}"
               required />
        <input class="tinkoffPayRow"
               type="text"
               id="tinkoff-order_id"
               placeholder="order_id"
               name="order"
               value="{order-id}" />
        <input class="tinkoffPayRow"
               type="text"
               id="tinkoff-order_description"
               placeholder="order_description"
               name="description"
               value="{description}" />
        <input class="tinkoffPayRow"
               type="text"
               placeholder="user_full_name"
               name="name"
               value="<?= $CurrentUser->user_full_name ?>" />
        <input class="tinkoffPayRow"
               type="text"
               placeholder="E-mail"
               name="email"
               value="<?= $CurrentUser->user_email ?>" />
        <input class="tinkoffPayRow"
               type="text"
               placeholder="Phone"
               name="phone"
               value="<?= $CurrentUser->user_phone ?>" />
        <input class="tinkoffPayRow" type="submit" value="Start pay" />
    </form>
</div>
<!-- end tinkoff form -->


