<?php

/** @var $this yii\web\View */
/** @var string $additional_header_class */
/** @var $RequestTeacherForm \frontend\models\forms\IndexRequestForm */

use yii\helpers\Html;

$this->title = Html::encode('Для преподавателей');

?>

    <div class="promo promo--coaches">
        <div class="container">
            <h1 class="promo__title"><span class="highlight-c1">Обучайте</span> <br>вокалу студентов <br>и <span class="highlight-c1">зарабатывайте!</span></h1>
            <div class="promo__text">
                <p><strong>Smart Sing</strong> ищет преподавателей вокала</p>
            </div>
            <div class="lead-action lead-action--row">
                <div class="lead-action__inner">
                    <div class="lead-action__title">Заполнить <br>заявку</div>

                    <?= $this->render('index-request-form', [
                        'model' => $RequestTeacherForm,
                        'form_id' => 'teacher-request-form-1',
                        'form_class' => 'student-request-form lead-action__frm row-frm frm-c5',
                        'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c5',
                        'type' => 'coaches'
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
    </div>
</div>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title">Кого мы ищем</h2>
        <ul class="icon-list">
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--edu-2 svg-icon" width="94" height="80">
                        <use xlink:href="#edu-2"></use>
                    </svg></div>
                <div class="icon-list__title">Преподавателей вокала с опытом работы от 1 года</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--music svg-icon" width="74" height="80">
                        <use xlink:href="#music"></use>
                    </svg></div>
                <div class="icon-list__title">Любите и понимаете музыку</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--smile svg-icon" width="70" height="70">
                        <use xlink:href="#smile"></use>
                    </svg></div>
                <div class="icon-list__title">Вы приветливы и доброжелательны</div>
            </li>
        </ul>
    </div>
</section>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title">Что ожидаем от Вас</h2>
        <ul class="icon-list">
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--voice-green svg-icon" width="80" height="80">
                        <use xlink:href="#voice-green"></use>
                    </svg></div>
                <div class="icon-list__title">Хорошее владение голосом/музыкальным инструментом</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--video-chat svg-icon" width="79" height="79">
                        <use xlink:href="#video-chat"></use>
                    </svg></div>
                <div class="icon-list__title">Доступ в интернет и компьютер</div>
                <div class="icon-list__desc">Необходимо хорошее качество связи, ноутбук, гарнитура/микрофон и веб-камера</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--orchestra svg-icon" width="130" height="80">
                        <use xlink:href="#orchestra"></use>
                    </svg></div>
                <div class="icon-list__title">Наличие музыкального инструмента будет плюсом</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--gears svg-icon" width="77" height="80">
                        <use xlink:href="#gears"></use>
                    </svg></div>
                <div class="icon-list__title">Желание работать и зарабатывать</div>
            </li>
            <li class="icon-list__item">
                <div class="icon-list__icon-wrap"><svg class="svg-icon--clock-green svg-icon" width="80" height="80">
                        <use xlink:href="#clock-green"></use>
                    </svg></div>
                <div class="icon-list__title">От 10 часов в неделю время на проведение уроков</div>
            </li>
        </ul>
    </div>
</section>
<section class="page-section">
    <div class="container">
        <h2 class="page-section__title">Гарантии и удобство</h2>
        <div class="features-list features-list--4col">
            <div class="feature-card win win win--grey">
                <div class="feature-card__top win__top"></div>
                <div class="feature-card__inner win__inner">
                    <div class="feature-card__icon-wrap"><svg class="svg-icon--peoples svg-icon" width="95" height="80">
                            <use xlink:href="#peoples"></use>
                        </svg></div>
                    <div class="feature-card__title">Поток студентов</div>
                    <div class="feature-card__desc">Больше не нужно заниматься поиском</div>
                </div>
            </div>
            <div class="feature-card win win win--grey">
                <div class="feature-card__top win__top"></div>
                <div class="feature-card__inner win__inner">
                    <div class="feature-card__icon-wrap"><svg class="svg-icon--sofa svg-icon" width="80" height="67">
                            <use xlink:href="#sofa"></use>
                        </svg></div>
                    <div class="feature-card__title">Удобство</div>
                    <div class="feature-card__desc">Необходим только компьютер и стабильный интернет</div>
                </div>
            </div>
            <div class="feature-card win win win--grey">
                <div class="feature-card__top win__top"></div>
                <div class="feature-card__inner win__inner">
                    <div class="feature-card__icon-wrap"><svg class="svg-icon--round-money svg-icon" width="80" height="71">
                            <use xlink:href="#round-money"></use>
                        </svg></div>
                    <div class="feature-card__title">Оплата</div>
                    <div class="feature-card__desc">Своевременная достойная оплата труда 2 раза в месяц</div>
                </div>
            </div>
            <div class="feature-card win win win--grey">
                <div class="feature-card__top win__top"></div>
                <div class="feature-card__inner win__inner">
                    <div class="feature-card__icon-wrap"><svg class="svg-icon--rescue svg-icon" width="80" height="80">
                            <use xlink:href="#rescue"></use>
                        </svg></div>
                    <div class="feature-card__title">Помощь</div>
                    <div class="feature-card__desc">Помощь методиста и тех. специалистов по любым вопросам</div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="page-section page-section--last">
    <div class="container">
        <h2 class="page-section__title"><span class="highlight-c1">Присоединиться</span> к нашей команде</h2>
        <div class="row-stages-scheme">
            <div class="row-stages-scheme__item">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--click svg-icon" width="80" height="61">
                                <use xlink:href="#click"></use>
                            </svg></div>
                        <div class="stage-card__title">Заполните форму ниже</div>
                    </div>
                </div>
                <div class="row-stages-scheme__num num">01</div>
                <div class="path-1"></div>
            </div>
            <div class="row-stages-scheme__item">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--karaoke svg-icon" width="70" height="80">
                                <use xlink:href="#karaoke"></use>
                            </svg></div>
                        <div class="stage-card__title">Расскажите о себе в видеорезюме и исполните 1 песню на любом языке</div>
                        <div class="stage-card__desc">Продолжительность песни 2-5 минут</div>
                    </div>
                </div>
                <div class="row-stages-scheme__num num">02</div>
                <div class="path-1"></div>
            </div>
            <div class="row-stages-scheme__item">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--online-learning svg-icon" width="80" height="80">
                                <use xlink:href="#online-learning"></use>
                            </svg></div>
                        <div class="stage-card__title">Пройдите обучение</div>
                        <div class="stage-card__desc">Продолжительность обучения 3 дня</div>
                    </div>
                </div>
                <div class="row-stages-scheme__num num">03</div>
                <div class="path-1"></div>
            </div>
        </div>
    </div>
</section>
<section class="lead lead--bottom-bg lead--c5">
    <div class="container">
        <div class="lead-action lead-action--row">
            <div class="lead-action__inner">
                <div class="lead-action__title">Заполнить <br>заявку</div>

                <?= $this->render('index-request-form', [
                    'model' => $RequestTeacherForm,
                    'form_id' => 'teacher-request-form-2',
                    'form_class' => 'student-request-form lead-action__frm row-frm frm-c5',
                    'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c5',
                    'type' => 'coaches'
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