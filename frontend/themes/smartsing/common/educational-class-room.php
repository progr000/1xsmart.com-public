<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\Users */
/** @var $room string */
/** @var $role string */
/** @var $CurrentUser \common\models\Users */
/** @var $is_test_student boolean */
/** @var $NextLesson \common\models\MethodistTimeline */
/** @var $is_class_room boolean */

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Users;
use frontend\assets\smartsing\RoomAsset;
use frontend\assets\smartsing\student\PaymentAsset;
use frontend\assets\smartsing\WebsocketAsset;

RoomAsset::register($this);
PaymentAsset::register($this);
WebsocketAsset::register($this);

$this->title = Html::encode('Классная комната');

?>

<div class="present <?= ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) ? 'present--control' : '' ?>">
    <div class="present__video" id="main-container-video-slide">
        <div class="present__main-screen">
            <div class="video-str" id="jitsi"></div>
        </div>
        <div class="present__top-screen">
            <!-- img(src="files/video/video-top.jpg")-->
        </div>
        <div class="present__bottom-screen" style="display: none;">
            <!-- img(src="files/video/video-bottom.jpg")-->
            <button id="panel-control">Show panel</button>

        </div>
        <div class="online-label" style="display: none;">Online</div>
    </div>
    <div class="present__info present-info-content-slides present__info--centered <?= ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) ? 'send-page' : '' ?>"
         id="present-info-content"
         data-wss-url="<?= Yii::$app->params['wss_host'] ?>/<?= $room ?>"
         data-document-domain="<?= Yii::$app->params['js_document_domain'] ?>"
         data-jitsi-domain="<?= Yii::$app->params['jitsi_domain'] ?>"
         data-jitsi-recording="<?= Yii::$app->params['jitsi_recording'] ? 1 : 0 ?>"
         data-is-student="<?= ($CurrentUser->user_type == Users::TYPE_STUDENT || $is_test_student) ? 1 : 0 ?>"
         data-user-name="<?= $is_test_student ? 'Test-Student' : $CurrentUser->user_first_name ?>"
         data-student-user-id="<?= $NextLesson ? $NextLesson->student_user_id : '' ?>"
         data-room-uuid="<?= $room ?>"
         data-is-class-room="<?= (int) $is_class_room ?>"
         data-main-class="present__info present-info-content-slides"
         data-additional-class-1="present__info--centered"
         data-additional-class-8="bg">

        <?= $this->render('slides/slide_1', ['CurrentUser' => $CurrentUser]) ?>

    </div>


    <!--
    <?php
    if (!$is_class_room && $CurrentUser->user_type != Users::TYPE_STUDENT) {
        $controller_id = Yii::$app->controller->id;
        $action_id = Yii::$app->controller->action->id;
        ?>
        <div class="room-mode">
            <?php if ($is_test_student) { ?>
                <a href="<?= Url::to(["{$controller_id}/{$action_id}", 'room' => $room]) ?>">Перейти в режим преподавателя</a>
            <?php } else { ?>
                <a href="<?= Url::to(["{$controller_id}/{$action_id}", 'room' => $room, 'is_test_student' => 1]) ?>">Перейти в режим ученика</a>
            <?php } ?>
        </div>
    <?php } ?>
    -->

    <?php
    if ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) {
        ?>
        <div class="context-menu-holder">
            <button id="send-slides-menu" class="btn open-context-menu-btn js-open-context-menu" type="button"><span></span></button>
            <div class="context-menu js-context-menu">
                <div class="context-menu__item only-for-editable">
                    <a class="edit-slide-data context-menu__link void-0" href="#">
                        <svg class="svg-icon--edit svg-icon" width="16" height="17">
                            <use xlink:href="#edit"></use>
                        </svg>Редактировать
                    </a>
                    <a class="save-slide-data context-menu__link void-0" href="#">
                        <svg class="svg-icon--edit svg-icon" width="16" height="17">
                            <use xlink:href="#edit"></use>
                        </svg>Зафиксировать
                    </a>
                </div>
                <div class="context-menu__item">
                    <a class="send-slide-to-student context-menu__link void-0" href="#">
                        <svg class="svg-icon--send svg-icon" width="16" height="16">
                            <use xlink:href="#send"></use>
                        </svg>Отправить
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

</div>

<?php
if ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) {
    ?>
    <div class="control-menu-holder">
        <button id="slides-menu-btn" class="open-control-menu-btn btn js-open-control-menu" type="button">
            <div class="hamburger hamburger--transform"><span></span><span></span><span></span><span></span></div>
        </button>
        <div class="control-menu js-control-menu">
            <div class="control-menu__item control-menu__item_1 _passed"><a class="control-menu__link show-slide void-0" data-slide-num="1" href="#">Добро пожаловать</a></div>
            <div class="control-menu__item control-menu__item_2"><a class="control-menu__link show-slide void-0" data-slide-num="2" href="#">Пресеты</a></div>
            <div class="control-menu__item control-menu__item_12" data-send-disabled="1"><a class="control-menu__link show-slide void-0" data-slide-num="12" href="#">Завершение урока и выставление результатов</a></div>
        </div>
    </div>
    <?php
}
?>



