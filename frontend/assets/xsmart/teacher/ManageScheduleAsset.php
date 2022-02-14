<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\xsmart\teacher;

use Yii;
use frontend\assets\xsmart\MainExtendAsset;

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
        'frontend\assets\xsmart\AppAsset',
        'frontend\assets\xsmart\common\ScheduleAsset',
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
            $this->compressFile('themes/xsmart/js/teacher/manage-schedule.js', self::TYPE_JS, 'teacher', true),
        ];
    }
}
