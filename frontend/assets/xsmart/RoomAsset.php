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
class RoomAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\xsmart\AppAsset',
        'frontend\assets\xsmart\WebsocketAsset',
        'yii\widgets\ActiveFormAsset',
        'yii\validators\ValidationAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->css = [
            $this->compressFile("themes/xsmart/css/class-room.css", self::TYPE_CSS),
            //$this->compressFile('themes/xsmart/css/osmd.css', self::TYPE_CSS),
        ];

        $this->js = [
            $this->compressFile('themes/xsmart/js/class-room/external_api.js', self::TYPE_JS, 'class-room'),
            //$this->compressFile('themes/xsmart/js/osmd-audio-player/opensheetmusicdisplay.min.js', self::TYPE_JS, 'osmd-audio-player', true),
            //$this->compressFile('themes/xsmart/js/osmd-audio-player/OsmdAudioPlayer.min.js', self::TYPE_JS, 'osmd-audio-player', true),
            //$this->compressFile('themes/xsmart/js/osmd-audio-player/osmd-init.js', self::TYPE_JS, 'osmd-audio-player', true),
            //$this->compressFile('themes/xsmart/js/slides.js', self::TYPE_JS),
            $this->compressFile('themes/xsmart/js/class-room/jitsi-init.js', self::TYPE_JS, 'class-room'),
        ];
    }
}
