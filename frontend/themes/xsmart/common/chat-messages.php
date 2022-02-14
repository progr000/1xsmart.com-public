<?php

/** @var $this yii\web\View */
/** @var $messages array */
/** @var $CurrentUser \common\models\Users */

//echo "<pre>";var_dump($messages);echo "</pre>";

foreach ($messages as $opponent_user_id => $data) {
    $current_message_user_id = null;
    ?>
    <div class="chat__messages-stack -js-messages-stack js-messages-stack-my messages-opponent-<?= $opponent_user_id ?>"
         data-opponent_user_id="<?= $opponent_user_id ?>">
        <?php
        if (sizeof($data)) {
            foreach ($data as $mes) {
                //var_dump($mes);
                if ($current_message_user_id != $mes['sender_user_id']) {
                    if ($mes['sender_user_id'] == $CurrentUser->user_id) {
                        echo '<div class="chat__messages-title chat__messages-title--own">You</div>';
                    } else {
                        echo '<div class="chat__messages-title">{opponent_name}</div>';
                    }
                }
                $current_message_user_id = $mes['sender_user_id'];
                $mes['msg_created'] = strtotime($mes['msg_created']) + $CurrentUser->user_timezone;
                ?>
                <div class="chat__message js-chat-message <?= $mes['sender_user_id'] == $CurrentUser->user_id ? 'chat__message--own' : 'chat__message--opponent' ?> <?= $mes['msg_unread'] ? 'unread' : '' ?>">
                    <div class="chat__message-text js-chat-message-text" data-original_msg_text="<?= $mes['msg_text'] ?>"><?= $mes['msg_text'] ?></div>
                    <div class="chat__message-meta"><?= date('H:i, d ', $mes['msg_created']) . Yii::t('app/common', 'month_' . date('n', $mes['msg_created'])) ?></div>
                </div>
                <?php
            }
        }
        ?>
        <div class="scroll-to-bottom"></div>
    </div>
    <?php
}
?>
