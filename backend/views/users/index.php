<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Users;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'pjax'=>true,
        'pjaxSettings' => [],
        'panel' => [
            'before' => false,
            'after' => '',
        ],
        //'summary' => 'Показаны записи с <b>{begin, number}</b> по <b>{end, number}</b> из <b>{totalCount, number}</b>.',
        'summary' => "Showing {begin, number}-{end, number} of {totalCount, number} users.",

        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'width' => '40px',
            ],
            //'user_created',
            [
                'attribute' => 'user_created',
                'hAlign'=>'center',
                //'width' => '100px;',
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
                    'model' => $searchModel,
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
                                        $('#userssearch-user_created').val('').trigger('change');
                                    }",
                        /*
                        "hide.daterangepicker" => "function(ev, picker) {
                            if(picker.startDate._isValid==false){
                                $('#userssearch-user_created').val('').trigger('change');
                                return;
                            }
                            if(picker.endDate._isValid==false){
                                $('#userssearch-user_created').val('').trigger('change');
                                return;
                            }
                        }",
                        */

                        "apply.daterangepicker" => "function(ev, picker) {
                                console.log(picker.startDate._isValid);
                                if(picker.startDate._isValid==false){
                                    $('#userssearch-user_created').val('').trigger('change');
                                    return false;
                                }
                                if(picker.endDate._isValid==false){
                                    $('#userssearch-user_created').val('').trigger('change');
                                    return false;
                                }
                                var val = picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format);

                                //picker.element[0].children[1].textContent = val;
                                //$(picker.element[0].nextElementSibling).val(val);
                                console.log(val);
                                $('#userssearch-user_created').val(val).trigger('change');
                                //return;
                            }",

                    ],
                ]),
            ],
            //'user_updated',
            //'user_first_name',
            //'user_middle_name',
            //'user_last_name',
            //'user_full_name',
            'user_email:email',
            //'user_phone',
            //'user_last_pay',
            //'user_token',
            //'user_hash',
            [
                'attribute' => 'user_status',
                'format' => 'raw',
                'label' => 'Status',
                'hAlign'=>'center',
                'filter' => Users::getStatuses(),
                'value' => function ($model) {
                    return Users::getStatus($model->user_status);
                },
                //'width' => '80px',
            ],
            [
                'attribute' => 'user_type',
                'format' => 'raw',
                'label' => 'Type',
                'hAlign'=>'center',
                'filter' => Users::getTypes(),
                'value' => function ($model) {
                    return Users::getType($model->user_type);
                },
                'width' => '80px',
            ],
            //'operator_user_id',
            //'operator_notice',
            //'methodist_user_id',
            //'methodist_notice',
            [
                'attribute' => 'teacher_user_id',
                'width' => '50px',
            ],
            //'teacher_notice',
            //'user_balance',
            //'user_last_ip',

            //['class' => 'yii\grid\ActionColumn'],
            [
                //'class' => 'yii\grid\ActionColumn',
                'class'=>'kartik\grid\ActionColumn',
                'width' => '60px',
                'vAlign' => 'top',
                'template' => '{profile} {view} {update} {delete}',
                //'template' => '{change-status} {view} {update} {delete}',
                //'template' => '{change-status} {profile} {view} {update}',
                //'template' => '{change-status} {view} {update}',
                'buttons' => [

                    'profile' => function ($url, $model) {
                        /** @var $model \common\models\Users */
                        if ($model->user_type == Users::TYPE_ADMIN) {
                            if (Yii::getAlias('@adminWeb', false) && Yii::getAlias('@adminDomain')) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-user"></span>',
                                    Yii::getAlias('@adminWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token], false),
                                    [
                                        'title' => 'Login to User Account',
                                        'data-pjax' => '0',
                                        'target' => '_blank',
                                        //'class' => 'masterTooltip',
                                    ]
                                );
                            } else {
                                return '<a href="#" class="void-0" onclick="alert(\'Необходимо указать алиасы @adminWeb и @adminDomain в common/config/main-local.php\')"><span class="glyphicon glyphicon-user"></span></a>';
                            }
                        } else {
                            return Html::a(
                                '<span class="glyphicon glyphicon-user"></span>',
                                Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token, 'is_from_admin_for_manager_users' => 1], false),
                                [
                                    'title' => 'Login to User Account',
                                    'data-pjax' => '0',
                                    'target' => '_blank',
                                    //'class' => 'masterTooltip',
                                ]
                            );
                        }
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
