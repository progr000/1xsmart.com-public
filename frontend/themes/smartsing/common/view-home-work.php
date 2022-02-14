<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\HomeWorks */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\Users;
use common\models\HomeWorks;

?>

<div class="home-work-view">

    <h1>View home work: <?= $model->work_name ?></h1>


    <?php
    $attributes = [
        'work_id',
        'work_created',
        'work_updated',
        'work_name',
        'work_status',
        'work_description',
        'methodist_user_id',
        'work_file',
        [
            'attribute' => 'work_content',
            'format' => 'raw',
            'value' => function($model) {
                return "<code>" .
                    nl2br(htmlentities(file_get_contents(Yii::$app->params['homeWorkUploadsDir'] . DIRECTORY_SEPARATOR . $model->work_file))) .
                    "</code>";
            }
        ]
    ];
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

    <?php
    if ($CurrentUser->user_type == Users::TYPE_OPERATOR && $model->work_status == HomeWorks::STATUS_UNCHECKED) {
        ?>
        <a class="btn btn-success"
           onclick="return confirm('Are you sure?')"
           href="<?= Url::to(['operator/home-work-checked', 'work_id' => $model->work_id], CREATE_ABSOLUTE_URL) ?>" >Отметить как проверенное</a>
        <?php
    }
    ?>
</div>