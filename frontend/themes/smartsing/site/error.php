<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;

$CurrentUser = Yii::$app->user->identity;
?>

    <div class="promo promo--home js-promo">
        <div class="container">
            <div class="promo__inner">

                <h1 class="promo__title"><span class="highlight-c1"><?= Html::encode($this->title) ?></span></h1>
                <div class="promo__text">

                    <?= ''/*nl2br(Html::encode($message))*/ ?>

                </div>
                <div class="promo__sidebar">
                    <?php if (!$CurrentUser) { ?>
                        <a class="login-btn btn secondary-btn js-open-modal void-0" href="#" data-modal-id="auth-modal">
                            <span>Войти</span>
                            <svg class="svg-icon--key svg-icon" width="20" height="19">
                                <use xlink:href="#key"></use>
                            </svg>
                        </a>
                    <?php } else { ?>
                        <a class="login-btn btn secondary-btn" href="<?= Url::to('user/', CREATE_ABSOLUTE_URL) ?>">
                            <span>В кабинет</span>
                            <svg class="svg-icon--key svg-icon" width="20" height="19">
                                <use xlink:href="#key"></use>
                            </svg>
                        </a>
                    <?php } ?>
                    <img class="promo__sidebar-img" src="/assets/smartsing-min/images/micro-alert.png">
                    <div class="promo__sidebar-title">Пройдите бесплатный урок</div>
                    <ul class="dots-list dots-list--white">
                        <li>Определим ваш уровень</li>
                        <li>Составим план обучения</li>
                        <li>Продемонстрируем как проходит урок</li>
                    </ul>
                    <a class="get-free-lesson-btn btn primary-btn primary-btn--c2 js-scroll-to"
                       href="<?= Url::to(['/', '#' => 'order'], CREATE_ABSOLUTE_URL) ?>">Отправить заявку
                        <svg class="svg-icon--melody svg-icon" width="20" height="20">
                            <use xlink:href="#melody"></use>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
