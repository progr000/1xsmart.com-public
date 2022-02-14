<?php

/** @var $this yii\web\View */
/** @var $additional_header_class string */
/** @var $RequestStudentForm \frontend\models\forms\IndexRequestForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode('Персональные занятия вокалом с преподавателем');

$this->params['additional_header_class'] = 'page-header page-header--over';
?>

    <div class="promo promo--home js-promo">
        <div class="container">
            <div class="promo__inner">
                <h1 class="promo__title">Уроки вокала <br>с преподавателем <br>в онлайн-школе <span class="highlight-c1">Smart Sing</span></h1>
                <div class="promo__text">
                    <ul class="reasons-list">
                        <li class="reasons-list__item exc-item">
                            <div class="exc-item__header"><svg class="svg-icon--microphone-red svg-icon" width="50" height="50">
                                    <use xlink:href="#microphone-red"></use>
                                </svg></div>
                            <div class="exc-item__desc">Уроки вокала для <br>детей и взрослых</div>
                        </li>
                        <li class="reasons-list__item exc-item">
                            <div class="exc-item__header"><svg class="svg-icon--voice-red svg-icon" width="50" height="50">
                                    <use xlink:href="#voice-red"></use>
                                </svg></div>
                            <div class="exc-item__desc">Любой уровень. <br>Ставим голос с 0</div>
                        </li>
                        <li class="reasons-list__item exc-item">
                            <div class="exc-item__header"><svg class="svg-icon--training svg-icon" width="50" height="48">
                                    <use xlink:href="#training"></use>
                                </svg></div>
                            <div class="exc-item__desc">Онлайн формат обучения, нужен только компьютер или смартфон</div>
                        </li>
                    </ul>
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
                    </ul><a class="get-free-lesson-btn btn primary-btn primary-btn--c2 js-scroll-to" href="#order">Отправить заявку<svg class="svg-icon--melody svg-icon" width="20" height="20">
                            <use xlink:href="#melody"></use>
                        </svg></a>
                </div>
                <div class="get-more"><svg class="svg-icon--mouse svg-icon" width="24" height="34">
                        <use xlink:href="#mouse"></use>
                    </svg>Узнайте больше</div>
            </div>
        </div>
    </div>
</div>
<section class="page-section page-section--cols">
    <div class="page-section__inner">
        <div class="page-section__col page-section__col--lg"><img src="/assets/smartsing-min/files/home/platform.png" alt=""></div>
        <div class="page-section__col page-section__col--text">
            <h2>Где <span class="highlight-c1">удобно</span> <br>и когда <span class="highlight-c1">удобно</span></h2>
            <p>Благодаря собственной платформе <strong>AudioSmart HD</strong> погружение в занятие происходит максимально результативно</p>
        </div>
    </div>
</section>
<section class="page-section page-section--cols page-section--reverse">
    <div class="page-section__inner">
        <div class="page-section__col"><img src="/assets/smartsing-min/files/home/grow.png" alt=""></div>
        <div class="page-section__col page-section__col--text">
            <h2>Ставим <span class="highlight-c1">голос с 0</span></h2>
            <p><strong>Эффективно.</strong> При любых природных данных. Известная истина: 95% успеха зависят от трудолюбия и лишь 5% от врожденных данных</p>
        </div>
    </div>
</section>
<section class="page-section page-section--cols">
    <div class="page-section__inner">
        <div class="page-section__col page-section__col--lg"><img src="/assets/smartsing-min/files/home/windows.png" alt=""></div>
        <div class="page-section__col page-section__col--text">
            <h2><span class="highlight-c1">Эстрадный</span> вокал</h2>
            <p><strong>Подбираем преподавателя</strong> под ваши цели. <strong>Разучиваем песни</strong> которые нравятся вам</p>
            <div class="icon-item"><svg class="svg-icon--status svg-icon" width="50" height="50">
                    <use xlink:href="#status"></use>
                </svg>
                <div class="icon-item__text">У нас работают только мастера своего дела, профессиональные педагоги которые умеют донести до ученика свои знания и опыт</div>
            </div>
        </div>
    </div>
</section>
<section class="page-section page-section--cols page-section--last">
    <div class="page-section__inner">
        <div class="page-section__col">
            <h2>Оставьте заявку <br>на <span class="highlight-c1">бесплатный урок</span></h2>
            <ul class="reasons-list vocal-course-reasons">
                <li class="reasons-list__item exc-item">
                    <div class="exc-item__header"><svg class="svg-icon--family svg-icon" width="43" height="50">
                            <use xlink:href="#family"></use>
                        </svg></div>
                    <div class="exc-item__desc">Простое и легкое обучение для всех возрастов</div>
                </li>
                <li class="reasons-list__item exc-item">
                    <div class="exc-item__header"><svg class="svg-icon--reload svg-icon" width="50" height="45">
                            <use xlink:href="#reload"></use>
                        </svg></div>
                    <div class="exc-item__desc">Бесплатная замена преподавателя при необходимости</div>
                </li>
                <li class="reasons-list__item exc-item">
                    <div class="exc-item__header"><svg class="svg-icon--break-dance svg-icon" width="50" height="50">
                            <use xlink:href="#break-dance"></use>
                        </svg></div>
                    <div class="exc-item__desc">Обучение по эффективным современным методикам</div>
                </li>
            </ul>
        </div>
        <div class="page-section__col page-section__col--text">
            <h3>На бесплатном уроке</h3>
            <ul class="dots-list">
                <li>Определим ваш уровень</li>
                <li>Составим план обучения</li>
                <li>Продемонстрируем как проходит урок</li>
            </ul>
        </div>
    </div>
</section>
<section class="lead lead--bottom-bg lead--c2" id="order">
    <div class="container">
        <div class="lead-action lead-action--row">
            <div class="lead-action__inner">
                <div class="lead-action__title">Заполнить <br>заявку</div>

                <?= $this->render('index-request-form', [
                    'model' => $RequestStudentForm,
                    'form_id' => 'student-request-form-1',
                    'form_class' => 'student-request-form lead-action__frm row-frm frm-c1',
                    'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c1',
                ]) ?>

            </div>
            <p class="private-info text-center">
                Заполняя форму я
                <a href="#"
                   class="void-0 private-link js-open-modal js-open-pdf-modal"
                   data-title="Соглашение"
                   data-content="/assets/smartsing-min/files/private.pdf"
                   data-modal-id="modal-private">соглашаюсь на обработку персональных данных</a>.
            </p>
        </div>
    </div>
</section>
