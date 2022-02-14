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
class RoomAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\smartsing\AppAsset',
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
            $this->compressFile("themes/smartsing/css/slides.css", self::TYPE_CSS),
            $this->compressFile('themes/smartsing/css/osmd.css', self::TYPE_CSS),
        ];

        $this->js = [
            //'https://js.bugfender.com/bugfender.js',
            $this->compressFile('themes/smartsing/js/external_api.js', self::TYPE_JS),
            $this->compressFile('themes/smartsing/js/osmd-audio-player/opensheetmusicdisplay.min.js', self::TYPE_JS, 'osmd-audio-player', true),
            $this->compressFile('themes/smartsing/js/osmd-audio-player/OsmdAudioPlayer.min.js', self::TYPE_JS, 'osmd-audio-player', true),
            $this->compressFile('themes/smartsing/js/osmd-audio-player/osmd-init.js', self::TYPE_JS, 'osmd-audio-player', true),
            $this->compressFile('themes/smartsing/js/slides.js', self::TYPE_JS),
        ];
    }
}
