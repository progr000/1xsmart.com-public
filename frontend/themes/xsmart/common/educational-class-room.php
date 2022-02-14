<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\Users */
/** @var $room string */
/** @var $role string */
/** @var $CurrentUser \common\models\Users */
/** @var $is_test_student boolean */
/** @var $NextLesson \common\models\StudentsTimeline */
/** @var $is_class_room boolean */

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Users;
use frontend\assets\xsmart\RoomAsset;
//use frontend\assets\xsmart\student\PaymentAsset;
//use frontend\assets\xsmart\WebsocketAsset;

RoomAsset::register($this);
//PaymentAsset::register($this);
//WebsocketAsset::register($this);

$this->title = Html::encode('Student conference');

?>
<div class="conf">
    <div class="conf__inner">
        <div class="conf__main <?= ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) ? 'present--control' : '' ?>"
             id="jitsi" >
            <!--
            <form class="conf__search-frm" action="/">
                <input class="conf__search-input" type="text" placeholder="Enter URL" />
                <button class="conf__search-submit primary-btn" type="submit">Go</button>
            </form>
            -->
        </div>
        <!--
        <div class="conf__sidebar">
            <div class="conf__users">
                <div class="conf__user"><img src="files/video/user-screen-1_230x170.jpg" alt=""></div>
                <div class="conf__user"><img src="files/video/user-screen-2_230x170.jpg" alt=""></div>
            </div>
        </div>
        <button class="conf__miniplayer-btn miniplayer-btn" type="button"></button>
        -->
    </div>
</div>


    <div style="display: none;"
         class="<?= ($CurrentUser->user_type == Users::TYPE_TEACHER && !$is_test_student) ? 'send-page' : '' ?>"
         id="present-info-content"
         data-wss-url="<?= Yii::$app->params['wss_host'] ?>/<?= $room ?>"
         data-chat-wss-url="<?= Yii::$app->params['wss_host'] ?>/chat"
         data-document-domain="<?= Yii::$app->params['js_document_domain'] ?>"
         data-jitsi-domain="<?= Yii::$app->params['jitsi_domain'] ?>"
         data-jitsi-recording="<?= Yii::$app->params['jitsi_recording'] && $NextLesson->is_introduce_lesson ? 1 : 0 ?>"
         data-is-student="<?= ($CurrentUser->user_type == Users::TYPE_STUDENT || $is_test_student) ? 1 : 0 ?>"
         data-user-name="<?= $is_test_student ? 'Test-Student' : $CurrentUser->user_first_name ?>"
         data-student-user-id="<?= $NextLesson ? $NextLesson->student_user_id : '' ?>"
         data-teacher-user-id="<?= $NextLesson ? $NextLesson->teacher_user_id : '' ?>"
         data-room-uuid="<?= $room ?>"
         data-is-class-room="<?= (int) $is_class_room ?>"
         data-main-class="present__info present-info-content-slides"
         data-additional-class-1="present__info--centered"
         data-additional-class-8="bg">

        <?= 'slides' ?>

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
    <!--
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
    -->
    <?php
}
?>



