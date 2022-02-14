<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\smartsing\student;

use Yii;
use frontend\assets\smartsing\MainExtendAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PaymentAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\smartsing\AppAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->js = [
            'https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js',
            $this->compressFile('themes/smartsing/js/student/payment.js', self::TYPE_JS, 'student', true),
        ];
    }
}
