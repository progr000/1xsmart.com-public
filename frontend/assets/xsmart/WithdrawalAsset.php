<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\xsmart;

use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class WithdrawalAsset extends MainExtendAsset
{
    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\xsmart\AppAsset',
    ];

    public $cssOptions = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->js = [
            $this->compressFile("themes/xsmart/js/teacher/withdrawal.js", self::TYPE_JS, 'teacher'),
        ];
    }
}
