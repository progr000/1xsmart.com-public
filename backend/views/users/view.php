<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Users;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = $model->user_email;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $attributes = [
        'user_id',
        'user_created',
        'user_updated',
        //'password_hash',
        //'password_reset_token',
        //'verification_token',
        //'auth_key',
        'user_first_name',
        'user_middle_name',
        'user_last_name',
        'user_full_name',
        'user_email:email',
        'user_phone',
        'user_last_pay',
        [
            'attribute' => 'user_token',
            'format' => 'raw',
            'label' => 'Token login link',
            'value' => function($model) {
                if ($model->user_type == Users::TYPE_ADMIN) {
                    if (Yii::getAlias('@adminWeb', false) && Yii::getAlias('@adminDomain')) {
                        return Html::a(
                            Yii::getAlias('@adminWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token], false),
                            Yii::getAlias('@adminWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token], false),
                            ['target' => '_blank']
                        );
                    } else {
                        return '<a href="#"
                                   class="void-0"
                                   onclick="alert(\'Необходимо указать алиасы @adminWeb и @adminDomain в common/config/main-local.php\')">
                                    Необходимо указать алиасы @adminWeb и @adminDomain в common/config/main-local.php
                                </a>';
                    }
                } else {
                    return Html::a(
                        Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token, 'is_from_admin_for_manager_users' => 1], false),
                        Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token, 'is_from_admin_for_manager_users' => 1], false),
                        ['target' => '_blank']
                    );
                }
            }
        ],
        //'user_hash',
        'user_status',
        'user_type',
        'operator_user_id',
        'operator_notice',
        'methodist_user_id',
        'methodist_notice',
        'teacher_user_id',
        'teacher_notice',
        'user_balance',
        'user_last_ip',
    ];
    if ($model->user_type == \common\models\Users::TYPE_STUDENT) {
        $attributes = array_merge($attributes, [
            'notes_played',
            'notes_hit',
            'notes_close',
            'notes_lowest',
            'notes_highest',
            'user_lessons_available',
            'user_lessons_completed',
            'user_lessons_missed',
        ]);
    }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
