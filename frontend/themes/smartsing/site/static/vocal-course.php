<?php

/** @var $this yii\web\View */
/** @var $additional_header_class string */
/** @var $RequestStudentForm \frontend\models\forms\IndexRequestForm */

use yii\helpers\Html;

$this->title = Html::encode('Курс вокала');

?>

    <div class="promo promo--cols">
        <div class="container">
            <div class="promo__col">
                <h1 class="promo__title">Развивайте голос и слух в онлайн- школе <span class="highlight-c1">Smart Sing</span></h1>
                <div class="promo__text">
                    <p>Занятия проходят с <strong>персональным преподавателем</strong> с использованием интерактивной платформы</p><a class="promo__btn btn primary-btn primary-btn primary-btn--c4 get-free-lesson-btn js-scroll-to" href="#order">Пройти бесплатный урок<svg class="svg-icon--melody svg-icon" width="20" height="20">
                            <use xlink:href="#melody"></use>
                        </svg></a>
                </div>
            </div>
            <div class="promo__col promo__col--media"><svg class="svg-icon--voice-red svg-icon" width="400" height="400">
                    <use xlink:href="#voice-red"></use>
                </svg><a class="btn primary-btn primary-btn--c4 get-free-lesson-btn js-scroll-to" href="#order">Пройти бесплатный урок<svg class="svg-icon--melody svg-icon" width="20" height="20">
                        <use xlink:href="#melody"></use>
                    </svg></a></div>
        </div>
    </div>
</div>
<section class="page-section page-section--cols">
    <div class="page-section__inner">
        <div class="page-section__col"><img src="/assets/smartsing-min/files/vocal-course/vc-1_860x620.jpg" alt=""></div>
        <div class="page-section__col page-section__col--text">
            <h2>Эффективный <span class="highlight-c1">рост</span></h2>
            <p><strong>Правильное развитие навыков пения</strong> происходит когда учитель слышит ошибки и корректирует их. Именно это преимущество дает наш персональный педагог</p>
        </div>
    </div>
</section>
<section class="page-section page-section--cols page-section--reverse">
    <div class="page-section__inner">
        <div class="page-section__col">
            <div class="win-grid places-slider js-places-slider">
                <div class="win win--logo places-slider__item">
                    <div class="win__top"></div>
                    <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/win-grid/wg-1_410x200.jpg" alt=""></div>
                    <div class="win__title win__title--lg">Дома</div>
                </div>
                <div class="win win--logo places-slider__item">
                    <div class="win__top"></div>
                    <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/win-grid/wg-2_410x200.jpg" alt=""></div>
                    <div class="win__title win__title--lg">На даче</div>
                </div>
                <div class="win win--logo places-slider__item">
                    <div class="win__top"></div>
                    <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/win-grid/wg-3_410x200.jpg" alt=""></div>
                    <div class="win__title win__title--lg">На отдыхе</div>
                </div>
                <div class="win win--logo places-slider__item">
                    <div class="win__top"></div>
                    <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/win-grid/wg-4_410x200.jpg" alt=""></div>
                    <div class="win__title win__title--lg">В студии</div>
                </div>
            </div>
        </div>
        <div class="page-section__col page-section__col--text">
            <h2>Занимайтесь где удобно. <br><span class="highlight-c1">В любое время</span></h2>
            <p><strong>Все занятия происходят онлайн.</strong> <br>Необходим только компьютер или смартфон</p>
        </div>
    </div>
</section>
<section class="page-section page-section--centered">
    <div class="page-section__inner">
        <h2 class="text-center">Навыки <span class="highlight-c1">правильного пения</span></h2>
        <p class="text-center">Полученные в нашей школе, пригодятся вам:</p>
        <div class="applying-slider-wrap slider-wrap">
            <div class="applying-slider slider slider--vw js-applying-slider">
                <div class="applying-slider__item">
                    <div class="win win--logo">
                        <div class="win__top"></div>
                        <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/carousel/c-1_547x315.jpg" alt=""></div>
                        <div class="win__title win__title--lg">В караоке</div>
                    </div>
                </div>
                <div class="applying-slider__item">
                    <div class="win win--logo">
                        <div class="win__top"></div>
                        <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/carousel/c-2_547x315.jpg" alt=""></div>
                        <div class="win__title win__title--lg">В компании</div>
                    </div>
                </div>
                <div class="applying-slider__item">
                    <div class="win win--logo">
                        <div class="win__top"></div>
                        <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/carousel/c-3_547x315.jpg" alt=""></div>
                        <div class="win__title win__title--lg">Для дыхательной гимнатистики и эмоциональной разгрузки</div>
                    </div>
                </div>
                <div class="applying-slider__item">
                    <div class="win win--logo">
                        <div class="win__top"></div>
                        <div class="win__img-wrap"><img src="/assets/smartsing-min/files/vocal-course/carousel/c-4_547x315.jpg" alt=""></div>
                        <div class="win__title win__title--lg">Для большой сцены</div>
                    </div>
                </div>
            </div>
            <div class="applying-slider-nav slider-nav slider-nav--couple">
                <div class="active-area active-area--right js-slide-next"></div>
                <div class="active-area active-area--left js-slide-prev"></div><button class="btn slider-nav__item slider-nav__item--accent slider-nav__item--prev nav-btn nav-btn--sm" type="button"><svg class="svg-icon--left svg-icon" width="6" height="12">
                        <use xlink:href="#left"></use>
                    </svg></button><button class="btn slider-nav__item slider-nav__item--accent slider-nav__item--next nav-btn nav-btn--sm" type="button"><svg class="svg-icon--right svg-icon" width="6" height="12">
                        <use xlink:href="#right"></use>
                    </svg></button>
            </div>
        </div>
    </div>
</section>
<section class="page-section page-section--cols">
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
<section class="lead lead--bottom-bg lead--c4" id="order">
    <div class="container">
        <div class="lead-action lead-action--row">
            <div class="lead-action__inner">
                <div class="lead-action__title">Заполнить <br>заявку</div>

                <?= $this->render('index-request-form', [
                    'model' => $RequestStudentForm,
                    'form_id' => 'student-request-form-1',
                    'form_class' => 'student-request-form lead-action__frm row-frm frm-c4',
                    'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c4',
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
