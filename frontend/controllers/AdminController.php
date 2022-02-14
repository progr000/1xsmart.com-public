<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use common\helpers\Functions;
use common\models\Users;
use common\models\Disciplines;
use common\models\Leads;
use common\models\MailTemplate;
use frontend\models\admin\TeachersListSearch;
use frontend\models\admin\StudentsListSearch;
use frontend\models\schedule\TeachersScheduleForm;
use frontend\models\forms\ProfileForm;
use frontend\models\admin\FormFillsListSearch;

/**
 * User controller
 * @property array $target_logs
 */
class AdminController extends UserController
{
    public $target_logs = [
        'yii'     => "/runtime/logs/app.log",
        'php'     => "/runtime/logs/php-error.log",
        'tinkoff' => "/runtime/tinkoff-logs/tinkoff-error.log",
    ];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    /* default police for non authorized */
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],

                    /**/
                    [
                        'allow' => $this->checkIsAdmin(),
                        'roles' => ['@'],
//                        'matchCallback' => function(/*$rule, $action*/) {
//                            if (!$this->checkIsAdmin()) {
//                                return $this->redirect(['user/']);
//                                //return false;
//                            }
//                            return true;
//                        },
                        'denyCallback' => function ($rule, $action) {
                            return $this->denyCallbackFunct();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelStudentSearch = new StudentsListSearch();
        $dataProviderStudentSearch = $modelStudentSearch->search(Yii::$app->request->queryParams);

        $modelFormFillsListSearch = new FormFillsListSearch();
        $dataProviderFormFillsListSearch = $modelFormFillsListSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'CurrentUser'               => $this->CurrentUser,
            'newStudentForm'            => new Users(),
            'studentsListSearch'        => $modelStudentSearch,
            'dataProviderStudentSearch' => $dataProviderStudentSearch,
            'modelFormFillsListSearch'        => $modelFormFillsListSearch,
            'dataProviderFormFillsListSearch' => $dataProviderFormFillsListSearch,
        ]);
    }

    /**
     * @return string
     */
    public function actionTeachersList()
    {
        $status = Yii::$app->request->get('status', Users::TEACHER_PROFILE_WAIT_APPROVE);
        if (!in_array($status, [Users::TEACHER_PROFILE_NEW, Users::TEACHER_PROFILE_WAIT_APPROVE, Users::TEACHER_PROFILE_APPROVED])) {
            $status = Users::TEACHER_PROFILE_WAIT_APPROVE;
        }

        $teachersListSearch = new TeachersListSearch();
        $dataProviderTeachersSearch = $teachersListSearch->search(Yii::$app->request->queryParams, $status);


        return $this->render('/admin/teachers-list', [
            'CurrentUser'  => $this->CurrentUser,
            'teachersListSearch'  => $teachersListSearch,
            'dataProviderTeachersSearch' => $dataProviderTeachersSearch,
            'newTeacherForm' => new Users(),
            'searchStatus' => $status,
        ]);
    }

    /**
     * @return string
     */
    public function actionFinance()
    {
        return $this->render('finance', [
            'CurrentUser' => $this->CurrentUser,
        ]);
    }

    /**
     * @return string
     */
    public function actionMailq()
    {
        return $this->render('mailq', [
            'CurrentUser' => $this->CurrentUser,
        ]);
    }

    public function actionFileLog($target)
    {
        /**/
        if (!isset($this->target_logs[$target])) {
            return $this->redirect(['/']);
        }

        /**/
        $file = Yii::getAlias('@frontend') . $this->target_logs[$target];
        //var_dump($file);exit;

        /**/
        if (isset($_GET['clear'])) {
            if (file_exists($file) && is_writable($file)) {
                @unlink($file);
            }
            return $this->redirect(['file-log', 'target' => $target]);
        }

        /**/
        return $this->render('file-log', [
            'CurrentUser' => $this->CurrentUser,
            'log' => (file_exists($file) && is_readable($file)) ? file_get_contents($file) : '',
            'target' => $target
        ]);
    }

    /**
     * @param $user_id
     * @return string
     */
    public function actionGetUserData($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $lang = Yii::$app->language;

        //var_dump(Yii::$app->request->get()); exit;
        $user_id = intval($user_id);
        $schedule = boolval(Yii::$app->request->get('schedule', 1));

        /**/
        /** @var \common\models\Users $User */
        $User = Users::find()->where([
            'user_id'   => $user_id,
            //'user_type' => Users::TYPE_TEACHER,
        ])->one();
        if (!$User) {
            return [
                'result' => 'User not found',
            ];
        }

        $User->initAdditionalDataForModel();
        $User->password_hash = null;
        $User->password_reset_token = null;
        $User->verification_token = null;
        $User->auth_key = null;
        $User->user_token = null;
        $User->user_hash = null;
        $User->user_created = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_created, Yii::$app->params['datetime_short_format'], false);
        $User->user_updated = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_updated, Yii::$app->params['datetime_short_format'], false);
        $tmp_info = [];
        if ($User->additional_service_info) {
            try {
                $tmp_info = unserialize($User->additional_service_info);
            } catch (\Exception $e) { }
        }
        $User->additional_service_info = [
                'user_id'      => $User->user_id,
                'user_created' => $User->user_created,
                'user_updated' => $User->user_updated,
                'user_last_ip' => $User->user_last_ip,
            ] + $tmp_info;

        $u = $User->toArray();
        /**/
        if ($User->user_birthday) {
            $u['user_birthday'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_birthday, Yii::$app->params['date_format'], false);
        } else {
            $u['user_birthday'] = 'not set';
        }
        $u['user_birthday_day'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_birthday, 'j', false);
        $u['user_birthday_month'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_birthday, 'n', false);
        $u['user_birthday_year'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User->user_birthday, 'Y', false);
        $u['_user_skype'] = $User->_user_skype;
        $u['___country_code'] = $User->___country_code;
        $u['___city_name'] = $User->___city_name;
        $u['___country_name'] = $User->___country_name;
        $u['_user_display_name'] = $User->_user_display_name;
        $u['_user_location'] = ($u['___country_name']
            ? $u['___country_name'] . ($u['___city_name'] ? ', ' . $u['___city_name'] : '')
            : 'undefined'
        );
        $u['user_gender_code'] = $User->user_gender;
        $u['user_gender'] = Users::getGender($User->user_gender);
        $u['user_are_native'] = implode(', ', $User->___native_vars);
        $u['user_are_native_codes'] = unserialize($User->user_are_native);
        $u['user_speak_also'] = implode(', ', $User->___speak_also_vars);
        $u['user_speak_also_codes'] = unserialize($User->user_speak_also);
        $u['user_goals_of_education'] = implode(', ', $User->___user_goals_of_education);
        $u['user_goals_of_education_codes'] = unserialize($User->user_goals_of_education);

        /**/
        $u['images'] = [
            'user_photo' => $User->getProfilePhotoForWeb('/assets/smartsing-min/images/no_photo.png'),
            'user_country_flag' => Functions::getCountryImage($User->___country_code),
        ];

        /**/
        $u['show_hide_elements']['user_additional_info_part2'] = 'hide';
        $u['show_hide_elements']['button-approve-user'] = $User->teacher_profile_completed == Users::TEACHER_PROFILE_WAIT_APPROVE ? 'show' : 'hide';

        /**/
        /** @var \common\models\Disciplines $TeachersDiscipline */
        $TeachersDiscipline = Disciplines::find()
            ->alias('t1')
            ->innerJoin('{{%teachers_disciplines}} as t2', 't1.discipline_id = t2.discipline_id')
            ->where([
                'teacher_user_id' => $user_id
            ])
            ->orderBy(['t1.discipline_id' => SORT_ASC])
            ->one();
        if ($TeachersDiscipline) {
            $field = "discipline_name_{$lang}";
            $u['user_discipline'] = $TeachersDiscipline->discipline_name_en;
            if ($TeachersDiscipline->hasAttribute($field)){
                $u['user_discipline'] = $TeachersDiscipline->{$field};
            }
        } else {
            $u['user_discipline'] = '';
        }

        /**/
        $u['user_additional_info'] = Functions::my_nl2br(Functions::formatLongString($User->user_additional_info)); //$User->user_additional_info;

        /**/
        if ($schedule) {
            $scheduleModel = new TeachersScheduleForm();
            $scheduleModel->load([$scheduleModel->formName() => [
                'user_id' => $user_id,
                'user_type' => Users::TYPE_TEACHER,
                'user_timezone' => $this->CurrentUser->user_timezone,
            ]]);
            $sch = $this->renderPartial('teacher-schedule-part', [
                'teacher_user_id' => $user_id,
                'CurrentUser' => $this->CurrentUser,
                'DashboardSchedule_v2' => $scheduleModel->getScheduleForTwoWeekByDate($this->CurrentUser->_user_local_time, $this->CurrentUser->user_timezone, true)
            ]);
        } else {
            $sch = null;
        }

        return [
            'User' => $u,
            'Schedule' => $sch,
        ];
    }

    /**
     * @param $user_id
     * @return string
     */
    public function actionApproveUser($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user_id = intval($user_id);

        /**/
        /** @var \common\models\Users $User */
        $User = Users::find()->where([
            'user_id'   => $user_id,
            'user_type' => Users::TYPE_TEACHER,
        ])->one();
        if (!$User) {
            return [
                'status' => false,
                'result' => 'User not found',
            ];
        }

        /**/
        if ($User->teacher_profile_completed != Users::TEACHER_PROFILE_WAIT_APPROVE) {
            return [
                'status' => false,
                'result' => 'User is not for approve',
            ];
        }

        /**/
        $User->teacher_profile_completed = Users::TEACHER_PROFILE_APPROVED;
        if ($User->save()) {

            //var_dump(Yii::getAlias('@frontendWeb') . '/' . $User->last_system_language . '/teacher/');exit;
            MailTemplate::send([
                'language'        => $User->last_system_language,
                'to_email'        => $User->user_email,
                'to_name'         => $User->user_first_name,
                'composeTemplate' => 'approveTeacher',
                'composeData'     => [
                    'user_name' => $User->user_first_name,
                    'APP_NAME'  => Yii::$app->name,
                    //'memberLink' => Yii::getAlias('@frontendWeb') .'/' . $User->last_system_language . '/teacher',
                    'memberLink' => Yii::getAlias('@frontendWeb') . Url::to(['/site/login-by-token', 'token' => $User->user_token], false)
                ],
                'User'            => $User,
            ]);

            return [
                'status' => true,
                'result' => 'User successfully approved',
            ];
        }

        /**/
        return [
            'status' => false,
            'result' => 'DB error',
        ];
    }

    /**
     * @return string
     */
    public function actionSaveUserData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        /* определим студент или учитель */
        if (isset($_POST['ProfileStudent'])) {
            $form_name = 'ProfileStudent';
            $user_type = Users::TYPE_STUDENT;
            $user_status = Users::STATUS_BEFORE_INTRODUCE;
        } elseif (isset($_POST['ProfileTeacher'])) {
            $form_name = 'ProfileTeacher';
            $user_type = Users::TYPE_TEACHER;
            $user_status = Users::STATUS_ACTIVE;
        }
        if (!isset($form_name, $user_type, $user_status)) {
            return [
                'status' => false,
                'info'   => "Wrong request form",
            ];
        }

        /**/
        if (!isset($_POST[$form_name]['user_id'])) {
            return [
                'status' => false,
                'info'   => "Wrong request data",
            ];
        }

        /**/
        $user_id = intval($_POST[$form_name]['user_id']);
        if ($user_id) {
            $model = ProfileForm::findIdentity($user_id);
        } else {
            $model = new ProfileForm();
            $model->user_status = $user_status;
            $model->user_type = $user_type;
        }
        $model->setFormName($form_name);

        /**/
        if (!intval(Yii::$app->request->post('is_after_validate', 0))) {
            if ($model->load(Yii::$app->request->post(), $form_name)) {
                return ActiveForm::validate($model);
            } else {
                return ['error'];
            }
        }

        /**/
        if ($model->load(Yii::$app->request->post(), $form_name) && $model->validate()) {

            if ($model->saveProfile()) {

                return [
                    'status' => true,
                    'data' => [
                        'user_id' => $model->user_id,
                    ]
                ];

            } else {
                return [
                    'status' => false,
                    'info'   => "DB error",
                ];
            }

        } else {
            //var_dump($model->getErrors());
            return [
                'status' => false,
                'info'   => "Wrong model data",
            ];
        }

    }

    /**
     * @param int $user_id
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDeleteUser($user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user_id = intval($user_id);

        $User = Users::findIdentity($user_id);
        if ($User) {
            $User->delete();
        }

        return [
            'status' => true,
            'user_id' => $User->user_id,
        ];
    }

    /**
     * @param int $lead_id
     * @return string
     */
    public function actionSetFfAsRead($lead_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $lead_id = intval($lead_id);
        $lead = Leads::findOne($lead_id);
        if (!$lead) {
            return [
                'status' => false,
                'info' => 'Lead not found',
            ];
        }

        $lead->lead_status = Leads::STATUS_FORM_FILL;
        if (!$lead->save()) {
            return [
                'status' => false,
                'info' => 'DB error',
            ];
        }

        return [
            'status' => true,
            'ff_count' => Leads::find()->where(['lead_status' => Leads::STATUS_NEW])->count()
        ];
    }

    /**
     * @return string
     */
    public function actionGetFfCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'status' => true,
            'ff_count' => Leads::find()->where(['lead_status' => Leads::STATUS_NEW])->count()
        ];
    }
}
