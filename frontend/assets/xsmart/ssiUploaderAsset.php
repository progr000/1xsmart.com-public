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
class ssiUploaderAsset extends MainExtendAsset
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

        $this->css = [
            $this->compressFile("themes/xsmart/js/ssi-uploader/ssi-uploader.css", self::TYPE_CSS, 'ssi-uploader'),
        ];

        $this->js = [
            $this->compressFile("themes/xsmart/js/ssi-uploader/ssi-uploader.js", self::TYPE_JS, 'ssi-uploader', true),
            $this->compressFile("themes/xsmart/js/ssi-uploader/ssi-uploader-init.js", self::TYPE_JS, 'ssi-uploader', true),
        ];
    }
}
