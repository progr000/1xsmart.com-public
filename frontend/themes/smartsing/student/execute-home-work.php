<?php

/** @var $model \common\models\Users */
/** @var $role string */
/** @var $dataArray array */

?>


<iframe
    id="home-work-iframe"
    src="https://edu2.smartsing.net/jitsi/?role=homework&musicxml=<?= $dataArray['work_file'] ?>&name=<?= $dataArray['work_name'] ?>&hws=<?= $dataArray['hws_hash'] ?>"
    allow="camera; microphone; display-capture"
    allowfullscreen="true"
    height="100%"
    width="100%"
    style="height: 100%; width: 100%; border: 2px solid #ccc;"></iframe>