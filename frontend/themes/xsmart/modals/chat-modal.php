<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */

use frontend\assets\xsmart\ChatAsset;

ChatAsset::register($this);

?>
<a id="trigger-open-chat"
   class="js-open-modal void-0"
   href="#"
   data-modal-id="chat"
   style="display: none">open chat</a>
<div class="modal modal--chat -_opened" id="chat" data-wss-url="<?= Yii::$app->params['wss_host'] ?>/chat" data-my_user_id="<?= $CurrentUser->user_id ?>">
    <div class="modal__inner">
        <div class="chat">
            <div class="chat__sidebar">
                <form class="chat__search-form" data-onsubmit="return false;">
                    <input class="chat__search-input" id="chat-search" type="text" placeholder="<?= Yii::t('app/chat', 'Search') ?>" />
                </form>
                <div class="chat__users" id="chat-users">

                    <!--
                    <div class="chat__user js-user-chat-link user-{user_id} _current" data-chat-id="user-1">
                        <div class="chat__user-ava"><img src="/assets/xsmart-min/files/tutors/tutor-1_40x40.jpg" alt=""></div>
                        <div class="chat__user-info">
                            <div class="chat__user-label">Teacher name</div>
                            <div class="chat__user-name">Tom S.</div>
                        </div>
                        <div class="chat__user-last-time"></div>
                    </div>
                    <div class="chat__user js-user-chat-link user-{user_id}" data-chat-id="user-2">
                        <div class="chat__user-ava _has-new"><img src="/assets/xsmart-min/files/tutors/tutor-5_40x40.jpg" alt=""></div>
                        <div class="chat__user-info">
                            <div class="chat__user-label">Teacher name</div>
                            <div class="chat__user-name">Grade A.</div>
                        </div>
                        <div class="chat__user-last-time"></div>
                    </div>
                    <div class="chat__user js-user-chat-link user-{user_id}" data-chat-id="user-3">
                        <div class="chat__user-ava"></div>
                        <div class="chat__user-info">
                            <div class="chat__user-label">Support</div>
                            <div class="chat__user-name">Illa P.</div>
                        </div>
                        <div class="chat__user-last-time"></div>
                    </div>
                    -->

                </div>
            </div>
            <div class="chat__main">
                <div class="chat-messages-container" id="chat-messages">

                    <!--
                    <div class="chat__messages-stack js-messages-stack messages-user-{user_id} _opened" id="user-1">
                        <div class="chat__messages-title">Tom S.</div>
                        <div class="chat__message">
                            <div class="chat__message-text">Hi, can we get started?</div>
                            <div class="chat__message-meta">11:59 20 oct</div>
                        </div>
                        <div class="chat__message">
                            <div class="chat__message-text">I have already freed myself</div>
                            <div class="chat__message-meta">12:00 20 oct</div>
                        </div>
                        <div class="chat__messages-title chat__messages-title--own">You</div>
                        <div class="chat__message chat__message--own">
                            <div class="chat__message-text">Yes, I'm ready to start</div>
                            <div class="chat__message-meta">12:02 20 oct</div>
                        </div>
                        <div class="chat__messages-title">Tom S.</div>
                        <div class="chat__message">
                            <div class="chat__message-text">After 5 minutes I dial</div>
                            <div class="chat__message-meta">12:32 20 oct</div>
                        </div>
                    </div>
                    <div class="chat__messages-stack js-messages-stack messages-user-{user_id}" id="user-2">
                        <div class="chat__messages-title">Tom S.</div>
                        <div class="chat__message">
                            <div class="chat__message-text">Hi, can we get started?</div>
                            <div class="chat__message-meta">11:59 20 oct</div>
                        </div>
                        <div class="chat__message">
                            <div class="chat__message-text">I have already freed myself</div>
                            <div class="chat__message-meta">12:00 20 oct</div>
                        </div>
                    </div>
                    <div class="chat__messages-stack js-messages-stack messages-user-{user_id}" id="user-3">
                        <div class="chat__messages-title chat__messages-title--own">You</div>
                        <div class="chat__message chat__message--own">
                            <div class="chat__message-text">Yes, I'm ready to start</div>
                            <div class="chat__message-meta">12:02 20 oct</div>
                        </div>
                        <div class="chat__messages-title">Tom S.</div>
                        <div class="chat__message">
                            <div class="chat__message-text">After 5 minutes I dial</div>
                            <div class="chat__message-meta">12:32 20 oct</div>
                        </div>
                    </div>
                    -->

                </div>
                <form class="chat__message-form js-no-enter-submit" onsubmit="return sendMessageForOpponent();">
                    <input class="chat__message-input" type="text" placeholder="<?= Yii::t('app/chat', 'Message') ?>" id="message-for-opponent" />
                    <div class="chat__message-files files-input js-files" data-count-id="files-count" style="display: none;">
                        <input class="files-input__input js-files-input" id="file" name="file" type="file" multiple="">
                        <label class="files-input__label" for="file">
                            <svg class="svg-icon-clip svg-icon" width="10" height="16">
                                <use xlink:href="#clip"></use>
                            </svg>
                            <span class="files-input__count js-files-count"></span>
                        </label>
                    </div>
                    <button class="chat__message-submit-btn primary-btn" id="button-send-message" type="submit"><?= Yii::t('app/chat', 'Send') ?></button>
                </form>
            </div>
        </div>
        <button class="modal__close-btn js-close-modal" type="button">
            <svg class="svg-icon-close svg-icon" width="22" height="22">
                <use xlink:href="#close"></use>
            </svg>
        </button>
    </div>
</div>