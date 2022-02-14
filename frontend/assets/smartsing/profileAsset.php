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
class profileAsset extends MainExtendAsset
{
    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\smartsing\AppAsset',
    ];

    public $cssOptions = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->css = [
            $this->compressFile("themes/smartsing/js/ssi-uploader/ssi-uploader.css", self::TYPE_CSS, 'ssi-uploader'),
        ];

        $this->js = [
            $this->compressFile("themes/smartsing/js/ssi-uploader/ssi-uploader.js", self::TYPE_JS, 'ssi-uploader', true),
            $this->compressFile("themes/smartsing/js/ssi-uploader/ssi-uploader-init.js", self::TYPE_JS, 'ssi-uploader', true),
        ];
    }
}
