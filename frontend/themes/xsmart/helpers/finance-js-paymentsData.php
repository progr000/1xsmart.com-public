<?php

/** @var $this yii\web\View */

use common\models\Users;

function getPaymentsArray($CurrentUser)
{
    /** @var $CurrentUser \common\models\Users */

    if (!$CurrentUser->wallet_paypal && !$CurrentUser->wallet_yandex) {

        $paymentsArray = [
            [
                'innerHTML' => '<span>' . Yii::t('app/finance', 'Set_requisites') . '</span>',
                'text' => Yii::t('app/finance', 'no_requisites'),
                'value' => 0,
            ],
        ];

    } else {

        $paymentsArray = [
            [
                'innerHTML' => '<img src="/assets/xsmart-min/images/payments/paypal.svg" alt=""><span>PayPal' . ($CurrentUser->wallet_paypal ? " ({$CurrentUser->wallet_paypal})" : ' (requisites are not specified)') . '</span>',
                'text' => 'PayPal' . ($CurrentUser->wallet_paypal ? " ({$CurrentUser->wallet_paypal})" : ' (' . Yii::t('app/finance', 'no_requisites') . ')'),
                'value' => Users::PAY_TO_PAYPAL,
                'data_wallet' => $CurrentUser->wallet_paypal,
            ],
            [
                'innerHTML' => '<img src="/assets/xsmart-min/images/payments/yandex.svg" alt=""><span>ЮМоney' . ($CurrentUser->wallet_yandex ? " ({$CurrentUser->wallet_yandex})" : ' (requisites are not specified)') . '</span>',
                'text' => 'ЮМоney' . ($CurrentUser->wallet_yandex ? " ({$CurrentUser->wallet_yandex})" : ' (' . Yii::t('app/finance', 'no_requisites') . ')'),
                'value' => Users::PAY_TO_YANDEX,
                'data_wallet' => $CurrentUser->wallet_yandex,
            ]
        ];

        foreach ($paymentsArray as $k => $v) {
            if ($v['value'] == $CurrentUser->pay_to_wallet && $v['data_wallet']) {
                $paymentsArray[$k]['selected'] = 'selected';
            }
            if (!$v['data_wallet']) {
                unset($paymentsArray[$k]);
            }
        }

    }
    sort($paymentsArray);
    return $paymentsArray;
}
