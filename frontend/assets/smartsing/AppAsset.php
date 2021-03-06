<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\smartsing;

use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'frontend\assets\smartsing\MainCssAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->js = [
            $this->compressFile('themes/smartsing/js/bundle-repair.js', self::TYPE_JS),
            $this->compressFile('themes/smartsing/js/jquery.cookie.js', self::TYPE_JS),
            $this->compressFile('themes/smartsing/js/common/logger.js', self::TYPE_JS, 'common'),
            $this->compressFile('themes/smartsing/js/common/main.js', self::TYPE_JS, 'common'),
        ];
    }
}
