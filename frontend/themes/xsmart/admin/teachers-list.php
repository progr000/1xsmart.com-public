<?php

/** @var $this \yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $teachersListSearch \frontend\models\admin\TeachersListSearch */
/** @var $dataProviderTeachersSearch \yii\data\ActiveDataProvider */
/** @var $newTeacherForm \common\models\Users */
/** @var $searchStatus integer */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use common\models\Users;

$this->title = Html::encode('Teachers list | Admin area');

?>

<div class="crumbs container">
    <a class="crumbs__link" href="<?= Url::to(['admin/'], CREATE_ABSOLUTE_URL) ?>">Main</a>
    <div class="crumbs__title">Teachers list</div>
</div>
<div class="bg-wrapper">

    <?php Pjax::begin([
        'id' => 'teachers-list-content',
        'timeout' => PJAX_TIMEOUT,
        'options'=> ['tag' => 'div', 'class' => 'container']
    ]); ?>


    <?php
    $sort = '';
    $sort_direction = SORT_ASC;
    $test = $dataProviderTeachersSearch->getSort()->attributeOrders;
    foreach ($test as $k=>$v) {
        $sort_direction = $v;
        $sort_key = $k;
    }
    $sort = ($sort_direction == SORT_DESC ? '-' : '') . $sort_key;
    $lnk_sort = Url::to([
        'admin/teachers-list',
        'TeachersListSearch[filter]' => ($teachersListSearch->filter ? $teachersListSearch->filter : ''),
        'status' => $searchStatus,
        'sort' => '',
    ], CREATE_ABSOLUTE_URL);
    ?>

        <div class="tabs-wrap">
            <div class="tabs tabs--btns tabs tabs--nowrap -js-tabs">
                <a class="tabs__item -js-tabs-item <?= $searchStatus == Users::TEACHER_PROFILE_WAIT_APPROVE ? '_current' : ''?>"
                   data-pjax="1"
                   href="<?= Url::to(['admin/teachers-list', 'status' => Users::TEACHER_PROFILE_WAIT_APPROVE], CREATE_ABSOLUTE_URL) ?>">For approve</a>
                <a class="tabs__item -js-tabs-item <?= $searchStatus == Users::TEACHER_PROFILE_NEW ? '_current' : ''?>"
                   data-pjax="1"
                   href="<?= Url::to(['admin/teachers-list', 'status' => Users::TEACHER_PROFILE_NEW], CREATE_ABSOLUTE_URL) ?>">Registered teachers</a>
                <a class="tabs__item -js-tabs-item <?= $searchStatus == Users::TEACHER_PROFILE_APPROVED ? '_current' : ''?>"
                   data-pjax="1"
                   href="<?= Url::to(['admin/teachers-list', 'status' => Users::TEACHER_PROFILE_APPROVED], CREATE_ABSOLUTE_URL) ?>">Current Teachers</a>
            </div>
            <div class="tabs-content">
                <div class="box _visible">


                    <?php
                    $search_form =
                    '
                    <div class="catalog-controls">
                        <form id="filter-search-teachers-list" class="search-frm" action="' . Url::to(['admin/teachers-list'], CREATE_ABSOLUTE_URL) . '" method="get" data-pjax>
                            <input type="hidden" name="sort" value="' . $sort .'" />
                            <input type="hidden" name="status" value="' . $searchStatus . '" />
                            <input type="text" id="teachers-list-search-filter"
                                   class="search-frm__input"
                                   name="TeachersListSearch[filter]"
                                   value="' . ($teachersListSearch->filter ? $teachersListSearch->filter : '') . '"
                                   placeholder="ID, Email, Name etc."
                                   autocomplete="off"
                                   aria-label="ID, Email, Name etc." />

                            <button class="search-frm__submit" type="submit">
                                <svg class="svg-icon-search svg-icon" width="12" height="12">
                                    <use xlink:href="#search"></use>
                                </svg>
                            </button>
                        </form>
                        {summary}
                    </div>
                    ';
                    ?>
                    <?=
                    ListView::widget([
                        'pager' => [
                            // https://github.com/yiisoft/yii2/blob/master/framework/widgets/LinkPager.php

                            // Customzing options for pager container tag
                            'options' => [
                                //'tag' => 'div',
                                'class' => 'pages',
                                //'id' => 'pager-container',
                            ],

                            // Customzing CSS class for pager link
                            'linkOptions' => [
                                //'tag' => 'span',
                                'class' => 'pages__item',
                                'href' => '',
                            ],
                            'activePageCssClass' => 'pages__item--current_',

                            // Customzing CSS class for navigating link
                            'prevPageCssClass' => 'pages__item--prev_',
                            'nextPageCssClass' => 'pages__item--next_',
                            'firstPageCssClass' => null,
                            'lastPageCssClass' => null,
                        ],

                        'dataProvider' => $dataProviderTeachersSearch,
                        'itemOptions' => [
                            'tag' => false,
                            'class' => '',
                        ],
                        'summary' => '<div class="showed">Showed <span>{begin, number}-{end, number}</span> of <span>{totalCount, number}</span></div>',
                        //'Страница <b>{page, number}</b>. Показаны записи с <b>{begin, number}</b> по <b>{end, number}</b> из <b>{totalCount, number}</b>.',
                        'layout' => $search_form . '
                            <table class="stripe-tbl">
                                <thead>
                                <th class="' . ($sort_key == 'id' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">ID<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'id' ? '-id' : 'id') . '"></a></th>
                                <th class="' . ($sort_key == 'created' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Reg. Date<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'created' ? '-created' : 'created') . '"></a></th>
                                <th>Country</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th class="' . ($sort_key == 'last-login' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Last login<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'last-login' ? '-last-login' : 'last-login') . '"></a></th>
                                <th>Approved</th>
                                <th class="' . ($sort_key == 'paid' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Get paid<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'paid' ? '-paid' : 'paid') . '"></a></th>
                                <th class="' . ($sort_key == 'balance' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Balance<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'balance' ? '-balance' : 'balance') . '"></a></th>
                                <th></th>
                                </thead>
                                <tbody>
                                {items}
                                </tbody>
                            </table>
                            {pager}
                        ',

                        'emptyText' => (
                            $teachersListSearch->filter
                                ? str_replace('{summary}', '', $search_form) . '<div class="results-empty">По заданному поиску нет результатов</div>'
                                : '<div class="results-empty">Нет записей</div>'
                        ),
                        //'emptyTextOptions' => ['tag' => false],
                        'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
                            /** @var $model \frontend\models\admin\TeachersListSearch */

                            $model->initAdditionalDataForModel();
                            /* return html */
                            $teacher = $model->getTeacherForThisUser();
                            return '
                                <!-- tr -->
                                <tr>
                                    <td>' . $model->user_id . '</td>
                                    <td>' . $CurrentUser->getDateInUserTimezoneByDateString($model->user_created, Yii::$app->params['datetime_short_format'], false) . '</td>
                                    <td>' . $model->___country_name . '</td>
                                    <td>' . $model->user_email . '</td>
                                    <td>
                                        <div class="person -js-open-modal show-teacher-info"
                                             data-modal-id="user-info--popup"
                                             data-user_id="' . $model->user_id . '">
                                            ' . (
                                        $model->user_photo
                                            ? '
                                                    <div class="person__ava-wrap">
                                                        <img class="person__ava"
                                                             src="' . $model->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png') . '"
                                                             alt="" />
                                                    </div>
                                                  '
                                            : '<div class="person__ava-wrap person__ava-wrap--empty"></div>'
                                        ) . '
                                            <div class="person__name">' . $model->_user_display_name . '</div>
                                        </div>
                                    </td>
                                    <td>' . $CurrentUser->getDateInUserTimezoneByDateString($model->user_last_visit, Yii::$app->params['datetime_short_format'], false) /*$model->getUserOnlineStatus($model->_user_last_visit)*/ . '</td>
                                    <td>
                                        <div>
                                            ' . (
                                        $model->teacher_profile_completed == Users::TEACHER_PROFILE_APPROVED
                                            ? '<div class="confirmed">Yes</div>'
                                            : '<div class="unconfirmed">No</div>'
                                        ) . '
                                        </div>
                                    </td>
                                    <td>
                                        ' . (
                                        $model->user_balance > 0
                                            ? '<div class="confirmed">Yes</div>'
                                            : '<div class="unconfirmed">No</div>'
                                        ) . '
                                    </td>
                                    <td>' . $model->user_balance . ' usd</td>
                                    <td>
                                        <div class="table-controls">
                                            <a class="lock-btn adm-control-btn"
                                               data-type="button"
                                               data-pjax="0"
                                               target="_blank"
                                               href="' . Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $model->user_token, 'is_from_admin_for_manager_users' => 1], false) . '">
                                                <svg class="svg-icon-key2 svg-icon" width="16" height="16">
                                                    <use xlink:href="#key2"></use>
                                                </svg>
                                            </a>
                                            <button class="edit-btn -js-open-modal js-open-edit-teacher-popup"
                                                    type="button"
                                                    data-teacher_user_id="' . $model->user_id . '"
                                                    data-modal-id="add-edit-teacher-popup">
                                                <svg class="svg-icon-edit svg-icon" width="16" height="16">
                                                    <use xlink:href="#edit"></use>
                                                </svg>
                                            </button>
                                            <a class="remove-btn confirm-delete-dialog"
                                               data-type="button"
                                               data-pjax="0"
                                               data-user_id="' . $model->user_id . '"
                                               data-confirm-text="Are you sure to delete this user?"
                                               href="' . Url::to(['admin/delete-user', 'user_id' => $model->user_id], CREATE_ABSOLUTE_URL) . '">
                                                <svg class="svg-icon-delete2 svg-icon" width="15" height="15">
                                                    <use xlink:href="#delete2"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- end tr -->
                            ';
                        },
                    ]);
                    ?>

                </div>
            </div>
        </div>

    <?php Pjax::end(); ?>

</div>


<?= $this->render('../modals/admin-users-manage-modal', ['CurrentUser' => $CurrentUser]) ?>
