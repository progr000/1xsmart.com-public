<?php

/** @var $this yii\web\View */
/** @var $result yii\data\ActiveDataProvider */
/** @var $modelSearch \frontend\models\search\TutorSearch */
/** @var $CurrentUser \common\models\Users */

use yii\helpers\Url;
use yii\widgets\ListView;
use common\helpers\Functions;
use common\models\Users;


function results_found_text($count)
{
    $count = intval($count);

    if ($count == 1) {
        return Yii::t('static/find-tutors', 'repetitor_nayden_one', ['totalCount' => $count]);
    }

    if ($count >= 10 && $count <= 20) {
        return Yii::t('static/find-tutors', 'repetitorov_naydeno', ['totalCount' => $count]);
    }

    $test = $count % 10;

    if ($test == 1) {
        return Yii::t('static/find-tutors', 'repetitor_nayden_many', ['totalCount' => $count]);
    }

    if ($test >= 2 && $test <= 4) {
        return Yii::t('static/find-tutors', 'repetitora_naydeno', ['totalCount' => $count]);
    }

    return Yii::t('static/find-tutors', 'repetitorov_naydeno', ['totalCount' => $count]);
}
?>


<?=
ListView::widget([
    'dataProvider' => $result,
    'pager' => [
        // https://github.com/yiisoft/yii2/blob/master/framework/widgets/LinkPager.php

        'firstPageLabel' => false,
        'lastPageLabel' => false,
        'prevPageLabel' => '',
        'nextPageLabel' => '',
        'maxButtonCount' => 9,

        // Customzing options for pager container tag
        'options' => [
            //'tag' => 'div',
            'class' => 'pages',
            'id' => 'pager-container',
        ],

        // Customzing CSS class for pager link
        'linkOptions' => [
            //'tag' => 'span',
            'class' => 'void-0 own-pager-a',
            'href' => '',
        ],
        'pageCssClass' => 'pages__item own-pager',
        'activePageCssClass' => 'pages__item--current',
        'disabledPageCssClass' => 'disabled',

        // Customzing CSS class for navigating link
        'prevPageCssClass' => 'pages__item own-pager pages__item--prev',
        'nextPageCssClass' => 'pages__item own-pager pages__item--next',
        'firstPageCssClass' => null,
        'lastPageCssClass' => null,
    ],
    //'itemOptions' => ['class' => 'item'],
    'itemOptions' => [
        'tag' => false,
        'class' => '',
    ],
    //'summary' => 'Страница <b>{page, number}</b>. Показаны записи с <b>{begin, number}</b> по <b>{end, number}</b> из <b>{totalCount, number}</b>.',
    //'summary' => '<div class="tutors__count">{totalCount, number} tutors found</div>',
    'summary' => '<div class="tutors__count">' . results_found_text($result->count) . '</div>',

    'layout' => '

        <div class="tutors">

            {summary}

            {items}

        </div>

        {pager}

    ',
    'emptyText' => '<div class="no-results-tutors">' . Yii::t('static/find-tutors', 'Empty_results') . '</div>',
    'emptyTextOptions' => ['tag' => false],
    'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser, $modelSearch) {
        /** @var $model \frontend\models\search\TutorSearch */

        /* text-about */
        $text_about = Functions::my_nl2br($model->user_additional_info);

        /*
        $tmp = explode("\n", $model->user_additional_info);
        $str_res = '<p>';
        if (sizeof($tmp) > 1) {
            foreach ($tmp as $k=>$v) {
                $tmp[$k] = Functions::formatLongString($v);
            }
            $str_res .= implode('</p><p>', $tmp);
        } else {
            $str_res .= Functions::formatLongString($tmp[0]);
        }
        $str_res .= '</p>';
        */

        /*
        $i = 0;
        $str_res = '';
        while (true) {
            if (!isset($tmp[$i])) { $stop = true; break; }
            if (mb_strlen($str_res) > 400) { $stop = true; break; }
            $str_res .= $tmp[$i] . "\n";
            $i++;
        }
        $str_res = trim($str_res);
        if (isset($tmp[$i])) {
            $str_res .= '<a href="#" class="toggle-text-btn js-toggle-hidden-text void-0">Show more&gt;&gt;</a>';
        }

        $str_res2 = '';
        while (true) {
            if (!isset($tmp[$i])) { $stop = true; break; }
            $str_res2 .= $tmp[$i] . "\n";
            $i++;
        }
        */

        /* price */
        foreach (Users::$_price_vars as $k=>$v) {
            if ($model->user_price_peer_hour >= $v['min'] && $model->user_price_peer_hour <= $v['max']) {
                $user_price_key = $k;
                break;
            }
        }

        /**/
        $model->initAdditionalDataForModel();

        /* link to tutor */
        $link = ["/tutor/{$model->user_id}"];

        /* discipline */
        $lang = Yii::$app->language;
        $TeachersDiscipline = $model->getMainDisciplineForThisTeacher();
        if ($TeachersDiscipline) {
            $field = "discipline_name_{$lang}";
            $discipline_name = $TeachersDiscipline->discipline_name_en;
            if ($TeachersDiscipline->hasAttribute($field)) {
                $discipline_name = $TeachersDiscipline->{$field};
            }
        } else {
            $discipline_name = '';
        }


        /* return html */
        return '
<div class="tutor-card">
    <div class="tutor-card__sidebar">
        <a class="tutor-card__ava append-for-history-back"
            name="tutor_' . $model->user_id . '"
            href="' . Url::to($link) . '"
            data-user_id="' . $model->user_id . '"
            title="' . $model->_user_display_name . '">
            <img class="tutor-card__img"
                 src="' . $model->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') . '"
                 alt="" role="presentation" />
            <span class="tutor-card__rating">' . $model->user_rating . '</span>
        </a>
        <a class="tutor-card__name append-for-history-back"
           href="' . Url::to($link) . '"
           data-user_id="' . $model->user_id . '">' . $model->_user_display_name . '</a>
        <div class="tutor-card__location location">
            <img src="' . Functions::getCountryImage($model->___country_code) . '" alt="">
            <span>' . Functions::concatCountryCityName($model->___country_name, $model->___city_name) . '</span>
        </div>
        <a class="tutor-card__reviews append-for-history-back"
           href="' . Url::to($link) . '"
           data-user_id="' . $model->user_id . '">' . Yii::t('static/find-tutors', 'reviews', ['count' => $model->user_reviews]) . '</a>
        <a class="tutor-card__lessons append-for-history-back"
           href="' . Url::to($link) . '"
           data-user_id="' . $model->user_id . '">' . Yii::t('static/find-tutors', 'Lessons', ['count' => $model->user_lessons_spent]) . '</a>
        <div class="tutor-card__rate rate">' . Functions::getInCurrency($model->user_price_peer_hour)['sum'] . ' ' . Functions::getInCurrency($model->user_price_peer_hour)['name_lover'] . '/' . Yii::t('static/find-tutors', 'hour') . '</div>
        <div class="tutor-card__rate-level rate-level"><img src="/assets/xsmart-min/images/price/' . $model->___user_price_key . '.svg" alt="" /></div>
    </div>
    <div class="tutor-card__main">
        <div class="tutor-card__top">
            <div class="tutor-card__header">
                <a class="tutor-card__name append-for-history-back"
                   href="' . Url::to($link) . '"
                   data-user_id="' . $model->user_id . '">' . $model->_user_display_name . '</a>
                <div class="tutor-card__location location">
                    <img src="' . Functions::getCountryImage($model->___country_code) . '" alt="">
                    <span>' . Functions::concatCountryCityName($model->___country_name, $model->___city_name) . '</span>
                </div>
                <div class="tutor-card__params">
                    <div class="param">
                        <div class="param__label">' . Yii::t('static/find-tutors', 'Discipline_') . '</div>
                        <div class="param__value">' . $discipline_name . '</div>
                    </div>
                    <div class="param">
                        <div class="param__label">' . Yii::t('static/find-tutors', 'Native_speaker_') . '</div>
                        <div class="param__value">' . implode(', ', $model->___native_vars) . '</div>
                    </div>
                    ' . ( $model->user_can_teach_children
                        ? '
                        <div class="param">
                            <div class="param__label">' . Yii::t('static/find-tutors', 'Can_teach_children_') . '</div>
                            <div class="param__value">
                                <svg class="svg-icon-baby-boy svg-icon" width="20" height="18">
                                    <use xlink:href="#baby-boy"></use>
                                </svg>
                            </div>
                        </div>
                        '
                        : ''
                    ) . '
                </div>
            </div>
            <div class="tutor-card__controls">
                <a class="tutor-card__video-btn secondary-btn sm-btn"
                   target="_blank"
                   href="' . $model->user_youtube_video . '"
                   data-gallery="gallery-1">
                    <svg class="svg-icon-play svg-icon" width="20" height="20">
                        <use xlink:href="#play"></use>
                    </svg>' . Yii::t('static/find-tutors', 'Video') . '
                </a>
                <a class="tutor-card__schedule-btn primary-btn sm-btn append-for-history-back"
                   href="' . Url::to($link) . '"
                   data-user_id="' . $model->user_id . '"><span class="mobile-text">' . Yii::t('static/find-tutors', 'Schedule') . '</span><span>' . Yii::t('static/find-tutors', 'Schedule_a_lesson') . '</span></a>
                <button class="tutor-card__chat-btn secondary-btn sm-btn ' . ($CurrentUser ? 'js-open-chat-with' : 'js-open-modal') .'"
                        type="button"
                        data-opponent_user_id="' . $model->user_id . '"
                        data-opponent_display_name="' . $model->_user_display_name . '"
                        data-opponent_first_name="' . $model->user_first_name . '"
                        data-opponent_last_name="' . $model->user_last_name . '"
                        data-opponent_photo="' . $model->user_photo . '"
                        data-opponent_type="' . $model->user_type . '"
                        data-modal-id="' . ($CurrentUser ? 'chat' : 'signup-popup') .'"><span class="mobile-text">' . Yii::t('static/find-tutors', 'Contact') . '</span><span>' . Yii::t('static/find-tutors', 'Contact_the_tutor') . '</span></button>
            </div>
        </div>
        <div class="tutor-card__description ww js-hidden-wrap" data-limit="3" data-mob-limit="2">
            ' . $text_about . '
        </div>
    </div>
</div>
        ';
    },
]);
?>
