<?php

/** @var $this yii\web\View */
/** @var $CurrentUser \common\models\Users */
/** @var $studentsListSearch \frontend\models\admin\StudentsListSearch */
/** @var $dataProviderStudentSearch \yii\data\ActiveDataProvider */
/** @var $modelFormFillsListSearch \frontend\models\admin\FormFillsListSearch */
/** @var $dataProviderFormFillsListSearch \yii\data\ActiveDataProvider */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = Html::encode('Students list | Admin area');

$FormFillsCount = ($dataProviderFormFillsListSearch->getTotalCount());
?>

<div class="crumbs container">
    <a class="crumbs__link void-0" href="<?= Url::to(['admin/'], CREATE_ABSOLUTE_URL) ?>">Main</a>
    <div class="crumbs__title">Students list</div>
</div>
<div class="bg-wrapper">
    <div class="container">

        <?php Pjax::begin([
            'id' => 'students-list-content',
            'timeout' => PJAX_TIMEOUT,
            'options'=> ['tag' => 'section', 'class' => 'section']
        ]); ?>

        <?php
        $sort = '';
        $sort_direction = SORT_ASC;
        $test = $dataProviderStudentSearch->getSort()->attributeOrders;
        foreach ($test as $k=>$v) {
            $sort_direction = $v;
            $sort_key = $k;
        }
        $sort = ($sort_direction == SORT_DESC ? '-' : '') . $sort_key;
        $lnk_sort = Url::to([
            'admin/index',
            'StudentsListSearch[filter]' => ($studentsListSearch->filter ? $studentsListSearch->filter : ''),
            'sort' => '',
        ], CREATE_ABSOLUTE_URL);
        ?>

        <?php
        $search_form =
        '
        <div class="catalog-controls">

            <button id="ff-button" class="data-count js-open-modal ' . ($FormFillsCount > 0 ? '' : 'hidden') . '" type="button" data-modal-id="form-fills-popup">
                <div class="data-count__label">Form fills</div>
                <div class="data-count__value" id="ff_count" data-count="' . $FormFillsCount . '">' . $FormFillsCount . '</div>
            </button>

            <form id="filter-search-students-list" class="search-frm" action="' . Url::to(['admin/index'], CREATE_ABSOLUTE_URL) . '" method="get" data-pjax>
                <!--<input class="search-frm__input" name="" type="text" placeholder="ID, Email, Name etc." />-->
                <input type="hidden" name="sort" value="' . $sort . '" />
                <input type="text" id="students-list-search-filter"
                       class="search-frm__input"
                       name="StudentsListSearch[filter]"
                       value="' . ($studentsListSearch->filter ? $studentsListSearch->filter : '') . '"
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
        '
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

            'dataProvider' => $dataProviderStudentSearch,
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
                    <th class="' . ($sort_key == 'created' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Date<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'created' ? '-created' : 'created') . '"></a></th>
                    <th>Country</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th class="' . ($sort_key == 'last-login' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Last login<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'last-login' ? '-last-login' : 'last-login') . '"></a></th>
                    <th>Booked trial</th>
                    <th class="' . ($sort_key == 'paid' ? 'bold' . ($sort_direction == SORT_DESC ? ' desc' : ' asc') : '') . '">Paid<a class="table-sort-btn" type="button" href="' . $lnk_sort . ($sort == 'paid' ? '-paid' : 'paid') . '"></a></th>
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
            $studentsListSearch->filter
                ? str_replace('{summary}', '', $search_form) . '<div class="results-empty">По заданному поиску нет результатов</div>'
                : '<div class="results-empty">Нет записей</div>'
            ),
            //'emptyTextOptions' => ['tag' => false],
            'itemView' => function ($model, $key, $index, $widget) use ($CurrentUser) {
                /** @var $model \frontend\models\admin\StudentsListSearch */

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
                            <div class="person -js-open-modal show-student-info"
                                 data-modal-id="user-info--popup"
                                 data-user_id="' . $model->user_id . '">
                                ' . (
                                $model->user_photo
                                    ? '
                                        <div class="person__ava-wrap">
                                            <img class="person__ava managed-ava-user_photo"
                                                 data-user-id="' . $model->user_id . '"
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
                                $model->user_last_pay && $teacher
                                    ? '
                                        <div class="confirmed">Yes</div>
                                        <div>' . $teacher->_user_display_name . ', <!--' . $CurrentUser->getDateInUserTimezoneByDateString($model->user_last_pay, Yii::$app->params['datetime_short_format'], false) . ' --></div>
                                      '
                                    : '<div class="unconfirmed">No</div>'
                                ) . '
                            </div>
                        </td>
                        <td>
                            ' . (
                            $model->user_last_pay
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
                                <button class="edit-btn -js-open-modal js-open-edit-student-popup"
                                        type="button"
                                        data-student_user_id="' . $model->user_id . '"
                                        data-modal-id="add-edit-student-popup">
                                    <svg class="svg-icon-edit svg-icon" width="16" height="16">
                                        <use xlink:href="#edit"></use>
                                    </svg>
                                </button>
                                <a class="remove-btn adm-control-btn confirm-delete-dialog"
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


        <?php Pjax::end(); ?>

    </div>
</div>


<?= $this->render('../modals/admin-users-manage-modal', [
    'CurrentUser' => $CurrentUser,
    'modelFormFillsListSearch'        => $modelFormFillsListSearch,
    'dataProviderFormFillsListSearch' => $dataProviderFormFillsListSearch,
]) ?>