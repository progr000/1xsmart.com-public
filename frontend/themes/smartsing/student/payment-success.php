<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $step integer */
/** @var $date_start string */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Html::encode('Оплата произведена');

?>
<div class="after-top gradient-5 -after-top--has-margin">
    <header class="page-header js-page-header">
        <div class="page-header__inner">
            <!--
            <button class="user-menu-btn btn square-btn square-btn--lg hamburger-btn js-open-user-menu" type="button">
                <div class="hamburger"><span></span><span></span><span></span><span></span></div>
            </button>
            -->
            <br /><br />
        </div>
    </header>
    <div class="trial trial trial--after-payment container"><img class="trial__img accept-img" src="/assets/smartsing-min/images/accept.svg" alt="" role="presentation" />
        <div class="trial__title trial__title--sm">Спасибо. <span class="highlight-c2">Оплата произведена</span></div>
        <!--<div class="trial__desc">Через несколько минут вам зачислятся уроки <br />и вы будете направлены на страницу управления расписанием.</div>-->
    </div>
    <div class="step__nav" style="margin-top: 20px;">
        <a class="step__nav-btn step__nav-btn btn primary-btn primary-btn primary-btn--c6 lg-btn round-btn"
           href="<?= Url::to(['student/url-dispatcher', 'action' => 'payment-success'], CREATE_ABSOLUTE_URL) ?>"
           target="_blank">Продолжить</a>
    </div>
</div>
<script>
    /*
    setTimeout(function() {
        window.location.reload();
    }, 30*1000);
    */
</script>