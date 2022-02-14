<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\smartsing\teacher;

use Yii;
use frontend\assets\smartsing\MainExtendAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ManageScheduleAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\smartsing\AppAsset',
        'frontend\assets\smartsing\common\ScheduleAsset',
    ];

//    public $jsOptions = [
//        'defer' => false,
//    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->js = [
            $this->compressFile('themes/smartsing/js/teacher/manage-schedule.js', self::TYPE_JS, 'teacher', true),
        ];
    }
}
