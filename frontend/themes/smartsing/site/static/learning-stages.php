<?php

/** @var $this yii\web\View */
/** @var $additional_header_class string */
/** @var $RequestStudentForm \frontend\models\forms\IndexRequestForm */

use yii\helpers\Html;

$this->title = Html::encode('Этапы обучения');

?>

    <div class="promo promo--stages">
        <div class="container">
            <h1 class="promo__title">Обучение вокалу в <span class="highlight-c1">Smart Sing</span></h1>
            <div class="promo__text">
                <ul class="learning-stages-list">
                    <li class="learning-stages-list__item exc-item">
                        <div class="exc-item__header"><svg class="svg-icon--graduate svg-icon" width="44" height="50">
                                <use xlink:href="#graduate"></use>
                            </svg>
                            <div class="exc-item__title">Персональные <br>занятия</div>
                        </div>
                        <div class="exc-item__desc">Преподаватель все время урока уделяет вам</div>
                    </li>
                    <li class="learning-stages-list__item exc-item">
                        <div class="exc-item__header"><svg class="svg-icon--location svg-icon" width="50" height="50">
                                <use xlink:href="#location"></use>
                            </svg>
                            <div class="exc-item__title">Занятия проходят <br>из любого места</div>
                        </div>
                        <div class="exc-item__desc">Дома, на даче, в поездке за границу или в студии. Онлайн</div>
                    </li>
                    <li class="learning-stages-list__item exc-item">
                        <div class="exc-item__header"><svg class="svg-icon--clock-yellow svg-icon" width="50" height="50">
                                <use xlink:href="#clock-yellow"></use>
                            </svg>
                            <div class="exc-item__title">Вы выбираете время <br>для обучения</div>
                        </div>
                        <div class="exc-item__desc">Это может быть утро буднего дня или вечер в выходной. Как удобнее Вам. Продолжительность урока — 50 минут</div>
                    </li>
                    <li class="learning-stages-list__item exc-item">
                        <div class="exc-item__header"><svg class="svg-icon--target svg-icon" width="50" height="50">
                                <use xlink:href="#target"></use>
                            </svg>
                            <div class="exc-item__title">Структура и <br>методики обучения</div>
                        </div>
                        <div class="exc-item__desc">Структура урока и современные методики обучения позволяют Вам достигать результатов настолько быстро, насколько это возможно.  Базовый курс - 60 занятий</div>
                    </li>
                    <li class="learning-stages-list__item exc-item">
                        <div class="exc-item__header"><svg class="svg-icon--leaf svg-icon" width="50" height="50">
                                <use xlink:href="#leaf"></use>
                            </svg>
                            <div class="exc-item__title">Безопасность <br>в обучении</div>
                        </div>
                        <div class="exc-item__desc">Голос - сложный инструмент, наши преподаватели постоянно контролируют отсутствие у вас перенапряжения в связках</div>
                    </li>
                </ul>
            </div>
            <div class="lead-action lead-action--row">
                <div class="lead-action__inner">
                    <div class="lead-action__title">Заполнить <br>заявку</div>

                    <?= $this->render('index-request-form', [
                        'model' => $RequestStudentForm,
                        'form_id' => 'student-request-form-1',
                        'form_class' => 'student-request-form lead-action__frm row-frm frm-c5',
                        'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c5',
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
        <div class="stages-desc">
            <h2 class="text-center">Как научиться <span class="highlight-c1">петь хорошо</span></h2>
            <p>Занятия вокалом у нас в школе начинаются с <strong>бесплатного вводного урока</strong>. На этом уроке мы оценим ваш уровень, замерим диапазон, чистоту и плотность голоса. На основе этой информации подберем для Вас подходящего проподавателя.</p>
            <p>Во время обучения помимо основных занятий с преподавателем у Вас будут домашние задания, упражнения на дикцию, дыхательную гимнастику и все что улучшит ваш уровень.</p>
        </div>
        <div class="stages-scheme stages-scheme--2">
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--flag svg-icon" width="70" height="80">
                                <use xlink:href="#flag"></use>
                            </svg></div>
                        <div class="stage-card__title">01. Начало</div>
                        <div class="stage-card__desc">
                            <p>Оставьте заявку на дистанционное обучение вокалу.  Наш оператор перезвонит и согласует с вами время пробного занятия.</p><a class="btn primary-btn primary-btn--c6 js-scroll-to" href="learning-stages.html#order">Оставить заявку<svg class="svg-icon--melody svg-icon" width="20" height="20">
                                    <use xlink:href="#melody"></use>
                                </svg></a>
                        </div>
                    </div>
                </div>
                <div class="stages-scheme__num num">1</div>
                <div class="path-1"></div>
                <!--+e.dot-->
                <!--+e.arrow-->
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--melody-color svg-icon" width="68" height="80">
                                <use xlink:href="#melody-color"></use>
                            </svg></div>
                        <div class="stage-card__title">02. Пробное занятие</div>
                        <div class="stage-card__desc">Бесплатное вводное занятие является отличной возможностью для вас оценить преимущества онлайн-формата обучения. Методист школы оценит ваши данные и уровень владения голосом, а также составит для вас персональные рекомендации по вокальному развитию.</div>
                    </div>
                </div>
                <div class="stages-scheme__num num">2</div>
                <div class="path-2"></div>
                <!--+e.dot-->
                <!--+e.dot--bottom-->
                <!--+e.arrow-->
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--man-check svg-icon" width="97" height="80">
                                <use xlink:href="#man-check"></use>
                            </svg></div>
                        <div class="stage-card__title">03. Подброр педагога</div>
                        <div class="stage-card__desc">После вводного занятия, на основе собранных данных и ваших предпочтений, методист подбирает вам педагога. Цель методиста на этом этапе:  Максимально точно подобрать для Вас преподавателя, для того чтобы процесс обучения был наиболее эффективным.</div>
                    </div>
                </div>
                <div class="stages-scheme__num num">3</div>
                <div class="path-4"></div>
                <!--+e.dot-->
                <!--+e.dot--bottom-->
                <!--+e.arrow-->
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--sing svg-icon" width="80" height="80">
                                <use xlink:href="#sing"></use>
                            </svg></div>
                        <div class="stage-card__title">04. Регулярные занятия</div>
                        <div class="stage-card__desc">
                            <p>Все уроки проходят по современным методикам обучения. Уроки структурированы, проходят на собственной платформе и после каждого занятия вы можете оценить его качество.  Вы почувствуете что умеете управлять своим голосом как никогда раньше. Дополнительно качество работы преподавателя контролирует ваш персональный методист.</p>
                        </div>
                        <h3 class="text-center">Также Вы получаете:</h3>
                        <ul class="stage-card__list">
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--calendar-beige svg-icon" width="57" height="50">
                                        <use xlink:href="#calendar-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Бесплатный перенос и отмена занятий</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--operator-beige svg-icon" width="50" height="50">
                                        <use xlink:href="#operator-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Решение любых возникших вопросов по телефону, почте и скайпу</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--refresh-beige svg-icon" width="50" height="50">
                                        <use xlink:href="#refresh-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Бесплатная замена преподавателя, в случае необходимости</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--computer-money-beige svg-icon" width="47" height="50">
                                        <use xlink:href="#computer-money-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Удобная пакетная система оплаты</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--pig-beige svg-icon" width="54" height="50">
                                        <use xlink:href="#pig-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">При оплате бОльшего количества занятий, цена за урок становится ниже</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--binoculars-beige svg-icon" width="53" height="50">
                                        <use xlink:href="#binoculars-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Контроль качества уроков методистом</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--rescue-beige svg-icon" width="50" height="50">
                                        <use xlink:href="#rescue-beige"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Помощь наших специалистов в решении любых технических вопросов. От связи до оплаты</div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="stages-scheme__num num">4</div>
                <div class="path-5"></div>
                <!--+e.dot-->
                <!--+e.dot--bottom-->
                <!--+e.arrow-->
            </div>
            <div class="stages-scheme__item js-animate">
                <div class="stage-card stage-card stage-card--sm">
                    <div class="stage-card__top"></div>
                    <div class="stage-card__body">
                        <div class="stage-card__img-wrap"><svg class="svg-icon--bullit-check svg-icon" width="80" height="80">
                                <use xlink:href="#bullit-check"></use>
                            </svg></div>
                        <div class="stage-card__title">05. Результат</div>
                        <div class="stage-card__desc">После прохождения всего курса в вашем арсенале будет несколько отработанных песен, а также бесценные навыки работы со своим голосом и слухом. Разумеется мы расширим Ваш диапазон, сделаем голос плотнее, динамичнее и интереснее. <strong>Но самое главное - Вы научитесь хорошо петь.</strong></div>
                        <h3 class="text-center">Также, в зависимости от целей вы сможете:</h3>
                        <ul class="stage-card__list">
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--team-light-green svg-icon" width="50" height="48">
                                        <use xlink:href="#team-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Спеть в любой компании при удобном случае</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--hand-light-green svg-icon" width="50" height="50">
                                        <use xlink:href="#hand-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Сделать подарок в виде песни любимому человеку</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--target-light-green svg-icon" width="50" height="50">
                                        <use xlink:href="#target-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Развить голос, стать увереннее и убедительнее</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--meditation-light-green svg-icon" width="50" height="50">
                                        <use xlink:href="#meditation-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Получать моральное удовлетворение от  правильного пения</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--team-light-green svg-icon" width="50" height="48">
                                        <use xlink:href="#team-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Бесплатный перенос и отмена занятий</div>
                            </li>
                            <li class="stage-card__list-item">
                                <div class="stage-card__list-icon-wrap"><svg class="svg-icon--key-light-green svg-icon" width="50" height="48">
                                        <use xlink:href="#key-light-green"></use>
                                    </svg></div>
                                <div class="stage-card__list-text">Открыть в себе способности, о которых раньше могли только мечтать</div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="stages-scheme__num num">5</div>
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
<section class="lead lead--bottom-bg lead--c1" id="order">
    <div class="container">
        <div class="lead-action lead-action--row">
            <div class="lead-action__inner">
                <div class="lead-action__title">Заполнить <br>заявку</div>

                <?= $this->render('index-request-form', [
                    'model' => $RequestStudentForm,
                    'form_id' => 'student-request-form-2',
                    'form_class' => 'student-request-form lead-action__frm row-frm frm-c2',
                    'button_class' => 'lead-action__submit row-frm__submit btn primary-btn primary-btn primary-btn--c6',
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