<?php

/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;
use frontend\assets\smartsing\WebsocketAsset;

$this->title = Html::encode('тестовая страница');

WebsocketAsset::register($this);
?>

<div id="wss-data"
     data-wss-url="wss://smartsing.net.my:9443/broadcast-all"
     data-wss-user="<?= $CurrentUser->user_email ?>"
     data-wss-url-off="wss://smartsing.net.my:9443/broadcast?mode=broadcast"
     data-wss-url-echo-test-server="ws://echo.websocket.org">


    <input type="text" id="chat-message" value="" title=""/>
    <button type="submit" id="send-to-chat">Отправить</button>

</div>
