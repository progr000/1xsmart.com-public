<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets\xsmart\admin;

use Yii;
use frontend\assets\xsmart\MainExtendAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserManageAsset extends MainExtendAsset
{

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
        'frontend\assets\xsmart\AppAsset',
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
            $this->compressFile('themes/xsmart/js/admin/users-manage.js', self::TYPE_JS, 'admin', true),
        ];
    }
}
