<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $Teachers \common\models\Users[] */
/** @var $Transactions array*/

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;
use common\models\Users;
use common\models\StudentsTimeline;
use common\models\TeachersRewards;
use frontend\models\search\FinanceSearch;
use frontend\assets\xsmart\WithdrawalAsset;

$this->render('/helpers/finance-js-paymentsData');

WithdrawalAsset::register($this);

$this->title = Html::encode(Yii::t('app/finance', 'title'));

//$CurrentUser->user_lessons_available + $CurrentUser->_user_lessons_assigned
?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['teacher/'], CREATE_ABSOLUTE_URL) ?>"><?= Yii::t('app/common', 'Main') ?></a>
    <div class="crumbs__title"><?= Yii::t('app/finance', 'title') ?></div>
</div>
<div class="bg-wrapper">
    <div class="container">
        <div class="dashboard">
            <div class="dashboard__section dashboard__section--wide">
                <div class="screen screen--sm screen screen--left">
                    <div class="screen__header"></div>
                    <div class="screen__body screen__body--sm-top-pad">
                        <div class="tutor-payments">
                            <div class="tutor-payments__main">
                                <div class="tutor-payments__header">
                                    <div class="tutor-payments__title"><?= Yii::t('app/finance', 'Payments_automatically') ?></div>
                                    <div class="tutor-payments__sub-title"><?= Yii::t('app/finance', 'For_example') ?></div>
                                </div>
                                <div class="tutor-payments__bottom">
                                    <div class="payment-features">
                                        <div class="payment-features__item">
                                            <div class="payment-features__icon-wrap">
                                                <svg class="svg-icon-money svg-icon" width="30" height="30">
                                                    <use xlink:href="#money"></use>
                                                </svg>
                                            </div>
                                            <div class="payment-features__body">
                                                <div class="payment-features__label"><?= Yii::t('app/finance', 'Minimum_amount_') ?></div>
                                                <div class="payment-features__value"><?= Functions::getInCurrency(FinanceSearch::MINIMAL_WITHDRAW_AMOUNT)['sum'] . ' ' . Functions::getInCurrency(1)['code'] ?></div>
                                                <div class="payment-features__note"><?= Yii::t('app/finance', 'Please_note_') ?></div>
                                            </div>
                                        </div>
                                        <div class="payment-features__item">
                                            <div class="payment-features__icon-wrap">
                                                <svg class="svg-icon-economy svg-icon" width="30" height="30">
                                                    <use xlink:href="#economy"></use>
                                                </svg>
                                            </div>
                                            <div class="payment-features__body">
                                                <div class="payment-features__label"><?= Yii::t('app/finance', 'Payment_methods_') ?></div>
                                                <div class="payment-features__value">
                                                    <span><img src="/assets/xsmart-min/images/payments/paypal.svg" alt=""><?= Yii::t('app/finance', 'PayPal') ?>,</span>
                                                    <span><img src="/assets/xsmart-min/images/payments/yandex.svg" alt=""><?= Yii::t('app/finance', 'UМоney') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tutor-payments__sidebar">
                                <div class="balance">
                                    <div class="balance__icon-wrap">
                                        <svg class="svg-icon-saving svg-icon" width="50" height="60">
                                            <use xlink:href="#saving"></use>
                                        </svg>
                                    </div>
                                    <div class="balance__body">
                                        <div class="balance__label"><?= Yii::t('app/finance', 'Your_balance') ?></div>
                                        <div class="balance__value"><?= Functions::getInCurrency(1)['code'] ?><?= Functions::getInCurrency($Transactions['balance'])['sum'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard__section dashboard__section--lg">
                <div class="screen screen--sm screen screen--left">
                    <div class="screen__header"></div>
                    <div class="screen__body screen__body--sm-top-pad">
                        <div class="screen__title"><?= Yii::t('app/finance', 'Requisites') ?></div>

                        <script>
                            let paymentsData = <?= json_encode(getPaymentsArray($CurrentUser)) ?>
                        </script>

                        <form class="payment-frm">
                            <select id="default-wallet-wor-withdraw" class="lg-select -js-payments-select payment-frm__select"></select>
                            <button class="primary-btn payment-frm__submit js-open-modal" type="button" data-modal-id="payments-popup"><?= Yii::t('app/finance', 'Set') ?></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="dashboard__section">
                <div class="info-block">
                    <p><?= Yii::t('app/finance', 'Be_polite') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <section class="page-section page-section page-section--no-top-margin">
        <h2 class="page-section__title"><?= Yii::t('app/finance', 'Transaction_history') ?></h2>

        <?php Pjax::begin([
            'id' => 'teacher-finance',
            'timeout' => PJAX_TIMEOUT,
            'options'=> ['tag' => 'div', 'class' => 'transactions own-pagination']
        ]); ?>

        <!--<div class="transactions">-->
            <div class="transactions-table">
                <div class="transactions-table__header">
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Date') ?></div>
                    <div class="transactions-table__th"><?= Yii::t('app/finance', 'Student') ?></div>
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
                            <div class="transactions-table__td" data-th="Student">
                                <div class="person">
                                    <?php if ($transaction['p_type'] == FinanceSearch::INCOMING) { ?>
                                        <img class="person__ava" src="<?= Users::staticGetProfilePhotoForWeb($transaction['student_photo'], '/assets/xsmart-min/images/no_photo.png') ?>" alt="" role="presentation" />
                                        <div class="person__name"><?= Users::getDisplayName($transaction['student_first_name'], $transaction['student_last_name']) ?></div>
                                    <?php } else { ?>
                                        <div class="person__name"></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="transactions-table__td" data-th="Status">
                                <div class="transaction-state">
                                    <?php
                                    if ($transaction['p_type'] == FinanceSearch::INCOMING) {
                                        if ($transaction['p_status'] == StudentsTimeline::STATUS_FAILED) {
                                            echo '<div>' . Yii::t('app/finance', 'Lesson_not_pass') . '</div>';
                                        } else {
                                            echo '<div>' . Yii::t('app/finance', 'Lesson_passed') . '</div>';
                                            if ($transaction['p_status'] == StudentsTimeline::STATUS_AWAIT) {
                                                echo '<div class="unconfirmed">' . Yii::t('app/finance', 'Unconfirmed') . '</div>';
                                            } else {
                                                echo '<div class="confirmed">' . Yii::t('app/finance', 'Confirmed') . '</div>';
                                            }
                                        }
                                    } else {
                                        echo '<div><b>Withdrawal</b></div>';
                                        if ($transaction['p_status'] == TeachersRewards::STATUS_AWAIT) {
                                            echo '<div class="unconfirmed">' . Yii::t('app/finance', 'Awaiting') . '</div>';
                                        } else {
                                            echo '<div class="confirmed">' . Yii::t('app/finance', 'Payed') . '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="transactions-table__td" data-th="Price">
                                <?php
                                if ($transaction['p_type'] == FinanceSearch::INCOMING) {
                                    echo Functions::getInCurrency($transaction['p_amount'] / abs($transaction['p_count']))['sum'] . ' ' . Functions::getInCurrency(1)['name_lover'] . ' per hour';
                                } else {
                                    echo "Sum for withdraw: " . Functions::getInCurrency(abs($transaction['p_amount']))['sum'] . ' ' . Functions::getInCurrency(1)['name_lover'];
                                }
                                ?>
                            </div>
                            <div class="transactions-table__td" data-th="Balance"><?= Functions::getInCurrency($transaction['teacher_balance'])['sum'] . ' ' . Functions::getInCurrency(1)['name_lover'] ?></div>
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


<!-- payments-popup -->
<div class="modal" id="payments-popup">
    <div class="modal__inner">
        <div class="modal__body">
            <div class="modal__title"><?= Yii::t('app/finance', 'Set_requisites_popup') ?></div>
            <div class="requisites-info" id="js-requisites-info">
                <?= Yii::t('app/finance', 'Specify_details') ?>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-wallet-requisites',
                'action'=>['teacher/save-wallet-for-withdraw'],
                'options' => [
                    'class'    => "modal__form",
                    'onsubmit' => "return false",
                ],
                'fieldConfig' => [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'input-wrap',
                    ],
                    'template' => '{label}{input}{error}{hint}',
                ]
            ]); ?>

                <?=
                $form->field($CurrentUser, 'wallet_paypal', [
                    'template' => '
                                <div class="input-wrap">
                                    {input}
                                    <label class="icon-label" for="wallet-paypal">
                                        <svg class="svg-icon-mail svg-icon" width="20" height="25">
                                            <use xlink:href="#mail"></use>
                                        </svg>
                                    </label>
                                    {error}{hint}
                                </div>
                            '
                ])->textInput([
                    'id'           => "wallet-paypal",
                    'type'         => "email",
                    'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                    'placeholder'  => Yii::t('modals/contact', "Mail_PayPal"),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/contact', "Mail_PayPal"),
                ])->label(false)
                ?>

                <?=
                $form->field($CurrentUser, 'wallet_yandex', [
                    'template' => '
                                    <div class="input-wrap">
                                        {input}
                                        <label class="icon-label" for="wallet-yandex">
                                            <svg class="svg-icon-mail svg-icon" width="20" height="25">
                                                <use xlink:href="#mail"></use>
                                            </svg>
                                        </label>
                                        {error}{hint}
                                    </div>
                                '
                ])->textInput([
                    'id'           => "wallet-yandex",
                    'type'         => "text",
                    'class'        => "icon-input js-request-inputs", //"icon-input _filled"
                    'placeholder'  => Yii::t('modals/contact', "Wallet_UMoney"),
                    'autocomplete' => "off",
                    'aria-label'   => Yii::t('modals/contact', "Wallet_UMoney"),
                ])->label(false)
                ?>

                <div class="modal__submit"><button class="accent-btn wide-mob-btn js-change-wallet-requisites"
                                                   data-save-in-progress="<?= Yii::t('app/finance', 'Saving') ?>"
                                                   data-ready-to-save="<?= Yii::t('app/finance', 'Set') ?>"
                                                   type="submit"><?= Yii::t('app/finance', 'Set') ?></button></div>

            <?php ActiveForm::end(); ?>

        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="30" height="30">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>
<!-- payments-popup -->