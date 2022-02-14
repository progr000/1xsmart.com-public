<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\xsmart\student;

use Yii;
use frontend\assets\xsmart\MainExtendAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AfterIntroduceAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\xsmart\AppAsset',
        'frontend\assets\xsmart\TinkoffAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->js = [
            $this->compressFile('themes/xsmart/js/student/after-introduce.js', self::TYPE_JS, 'student'),
        ];
    }
}
