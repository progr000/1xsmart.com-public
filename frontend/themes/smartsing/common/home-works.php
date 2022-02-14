<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $homeWorksSearchModel \frontend\models\search\HomeWorksSearch */
/** @var $homeWorksDataProvider \yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Users;

?>

<?php
if ($CurrentUser->user_type == Users::TYPE_METHODIST) {
?>
    <a href="<?= Url::to(['/methodist/add-home-work'], CREATE_ABSOLUTE_URL) ?>"
       class="btn btn-default">Добавить домашнее задание</a>
<?php
}
?>

<?php Pjax::begin([
    'timeout' => PJAX_TIMEOUT,
]); ?>

<?= GridView::widget([
    'dataProvider' => $homeWorksDataProvider,
    'filterModel' => $homeWorksSearchModel,

    'pjax'=>true,
    'pjaxSettings' => [],
    'panel' => [
        'before' => false,
        'after' => '',
    ],
    //'summary' => 'Показаны записи с <b>{begin, number}</b> по <b>{end, number}</b> из <b>{totalCount, number}</b>.',
    'summary' => "Showing {begin, number}-{end, number} of {totalCount, number} users.",

    'columns' => [
        [
            'attribute' => 'work_id',
            'width' => '40px',
        ],
        //'user_created',
        [
            'attribute' => 'work_created',
            'hAlign'=>'center',
            'width' => '100px;',
            //'format' => ['date', 'php:d/m/Y H:i:s'],
            'encodeLabel' => false,
            'label' => 'Reg<br />date',
            /*
            'value' => function ($model) use ($searchModel) {
                return date(SQL_DATE_FORMAT, $model->user_created);
            },
            */

            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' =>([
                'model' => $homeWorksSearchModel,
                //'attribute' => 'created_at_range',
                'presetDropdown' => false,
                'defaultPresetValueOptions' => ['style'=>'display:none'],
                'convertFormat' => true,
                'initRangeExpr' => false,
                'pluginOptions' => [
                    'alwaysShowCalendars' => true,
                    'locale' => [
                        'format' => 'Y-m-d',
                        'cancelLabel' => 'Clear',
                    ],

                    'ranges' => [
                        //"Clear" => ["", ""],
                        "Today" => [date('Y-m-d'), date('Y-m-d')],
                        "Yesterday" => [date('Y-m-d', time()-86400), date('Y-m-d', time()-86400)],
                        "Last 7 Days" => [date('Y-m-d', time()-7*86400), date('Y-m-d', time())],
                        "Last 30 Days" => [date('Y-m-d', time()-30*86400), date('Y-m-d', time())],
                        "This Month" => [date('Y-m-01', time()), date('Y-m-d', time())],
                        //"Prev Month" => [date('Y-m-01', time()-27*86400), date('Y-m-d', time())],
                    ],

                ],
                'pluginEvents' => [
                    "cancel.daterangepicker" => "function(ev, picker) {
                                        //alert(1);
                                        //picker.element[0].children[1].textContent = '';
                                        //$(picker.element[0].nextElementSibling).val('').trigger('change');
                                        $('#homeworkssearch-work_created').val('').trigger('change');
                                    }",
                    /*
                    "hide.daterangepicker" => "function(ev, picker) {
                        if(picker.startDate._isValid==false){
                            $('#homeworkssearch-work_created').val('').trigger('change');
                            return;
                        }
                        if(picker.endDate._isValid==false){
                            $('#homeworkssearch-work_created').val('').trigger('change');
                            return;
                        }
                    }",
                    */

                    "apply.daterangepicker" => "function(ev, picker) {
                                console.log(picker.startDate._isValid);
                                if(picker.startDate._isValid==false){
                                    $('#homeworkssearch-work_created').val('').trigger('change');
                                    return false;
                                }
                                if(picker.endDate._isValid==false){
                                    $('#homeworkssearch-work_created').val('').trigger('change');
                                    return false;
                                }
                                var val = picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format);

                                //picker.element[0].children[1].textContent = val;
                                //$(picker.element[0].nextElementSibling).val(val);
                                console.log(val);
                                $('#homeworkssearch-work_created').val(val).trigger('change');
                                //return;
                            }",

                ],
            ]),
        ],
        'work_name',
        'methodist_user_id',
        [
            'attribute' => 'work_status',
            'filter' => \frontend\models\search\HomeWorksSearch::getStatuses(),
            'value' => function($data) {
                return \frontend\models\search\HomeWorksSearch::getStatus($data->work_status);
            }
        ],
        'work_file',
        [
            'class'=>'kartik\grid\ActionColumn',
            'width' => '60px',
            'vAlign' => 'top',
            'template' => '{view} {delete}',
            'buttons' => [

                'view' => function ($url, $model) use ($CurrentUser) {
                    if (in_array($CurrentUser->user_type, [Users::TYPE_STUDENT])) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::getAlias('@frontendWeb') . Url::to(['/student/execute-home-work', 'work_id' => $model->work_id, 'hws_hash' => $model->hws_hash], false),
                            [
                                //'title' => 'Login to User Account',
                                'data-pjax' => '0',
                                //'target' => '_blank',
                                //'class' => 'masterTooltip',
                            ]
                        );
                    } else {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::getAlias('@frontendWeb') . Url::to(['/user/view-home-work', 'work_id' => $model->work_id], false),
                            [
                                //'title' => 'Login to User Account',
                                'data-pjax' => '0',
                                //'target' => '_blank',
                                //'class' => 'masterTooltip',
                            ]
                        );
                    }
                },

                'delete' => function ($url, $model) use ($CurrentUser) {

                    if (in_array($CurrentUser->user_type, [Users::TYPE_METHODIST, Users::TYPE_OPERATOR])) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            Yii::getAlias('@frontendWeb') . Url::to(['/user/delete-home-work', 'work_id' => $model->work_id], false),
                            [
                                //'title' => 'Login to User Account',
                                'data-pjax' => '0',
                                //'target' => '_blank',
                                //'class' => 'masterTooltip',
                                'onclick' => 'return confirm("Are you sure?")',
                            ]
                        );
                    } else {
                        return null;
                    }
                },

            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

