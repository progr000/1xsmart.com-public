<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */

use common\models\Users;

?>

<div class="present__text">
    <div class="present__logo">
        <picture>
            <source srcset="/themes/smartsing/images/logo@2x.png 2x, /themes/smartsing/images/logo.png 1x"><img src="/themes/smartsing/images/logo.png" alt=""></picture>
    </div>
    <?php if ($CurrentUser->user_type == Users::TYPE_METHODIST) { ?>
        <h1 class="present__title">Добро пожаловать на <span class="highlight-c1">вводный урок</span> в школе вокала <span class="highlight-c1">Smart Sing</span></h1>
        <div class="present__desc">Наш методист уточнит Ваши цели, расскажет о содержании занятий и проверит Ваш уровень</div>
    <?php } else { ?>
        <h1 class="present__title">Добро пожаловать на <span class="highlight-c1">регулярный урок</span> в школе вокала <span class="highlight-c1">Smart Sing</span></h1>
        <div class="present__desc">Наш преподаватель проведет с вами занятие.</div>
    <?php } ?>
    <input type="hidden" name="var1" value="val1" class="data-inputs" />
    <input type="hidden" name="var2" value="val2" class="data-inputs" />
    <input type="hidden" name="var3" value="val3" class="data-inputs" />
</div>