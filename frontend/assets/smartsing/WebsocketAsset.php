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
class WebsocketAsset extends MainExtendAsset
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

        $this->js = [
            $this->compressFile('themes/smartsing/js/common/websocket.js', self::TYPE_JS, 'common'),
            //$this->compressFile('themes/smartsing/js/common/test-wss.js', self::TYPE_JS, 'common'),
        ];
    }
}
