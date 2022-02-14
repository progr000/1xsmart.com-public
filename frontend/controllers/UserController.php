<?php
namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use frontend\components\SController;
use common\helpers\Functions;
use common\models\Auth;
use common\models\Users;
use common\models\Chat;
use common\models\Reviews;
use common\models\MethodistTimeline;
use common\models\TeachersDisciplines;
use common\models\StudentsTimeline;
use frontend\models\StaticActionModel;
use frontend\models\forms\ContactForm;
use frontend\models\search\NextLessons;
use frontend\models\forms\ProfileForm;
use frontend\models\schedule\CommonScheduleForm;
use frontend\models\schedule\TeachersScheduleForm;
use frontend\models\schedule\StudentsScheduleForm;
use frontend\models\search\ChatDataSearch;
use frontend\models\search\NotificationsDataSearch;
use frontend\models\forms\ResendVerificationEmailForm;

/**
 * User controller
 *
 * @property \frontend\models\forms\ContactForm $model_contact
 *
 */
class UserController extends SController
{

    public $model_contact;

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

                    /* for all actions */
                    [
                        'actions' => [
                            'index',
                            'upload-profile-photo',
                            'delete-profile-photo',
                            'upload-profile-video',
                            'delete-profile-video',
                            'profile',
                            'settings',
                            'settings-and-profile',
                            'resend-verification-email',

                            'device-test',
                            'conference-room',

                            'get-notification-messages',
                            'set-notifications-as-read',
                            'get-chat-messages',
                            'set-chat-as-read',
                            'start-chat-with',
                            'send-chat-message',

                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                    /* review actions */
                    [
                        'actions' => [
                            'check-open-review',
                            'send-review',
                        ],
                        'allow' => ($this->checkIsAdmin() || $this->checkIsStudent()),
                        'roles' => ['@'],
                    ],

                    /* schedule actions */
                    [
                        'actions' => [
                            'get-schedule',
                            'change-schedule',
                        ],
                        'allow' => ($this->checkIsTeacher() || $this->checkIsStudent()),
                        'roles' => ['@'],
                    ],

                    /* class-room actions */
                    [
                        'actions' => [
                            'educational-class-room',
                        ],
                        'allow' => ($this->checkIsTeacher() || $this->checkIsStudent()),
                        'roles' => ['@'],
                    ],

                    /* admin actions */
                    [
                        'actions' => [
                            'get-user-info',
                        ],
                        'allow' => ($this->checkIsTeacher() || $this->checkIsAdmin()),
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function checkIsTeacher()
    {
        return ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_TEACHER);
    }

    /**
     * @return bool
     */
    public function checkIsStudent()
    {
        return ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_STUDENT);
    }

    /**
     * @return bool
     */
    public function checkIsAdmin()
    {
        return ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_ADMIN);
    }

    /**
     * @return Response
     */
    public function denyCallbackFunct()
    {
        Yii::$app->session->setFlash('access-control-alert-error', [
            'message'   => Yii::t('controllers/user', 'denyCallbackFunct_error'),
            'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
            'showClose' => true,
            'alert_id' => 'access-control-alert',
            'type' => 'error',
            //'class' => 'alert-error',
        ]);
        return $this->redirect(['user/']);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layout = 'member-main';
        $this->model_contact = new ContactForm();

        $test_social_flash = Yii::$app->session->get('after_link_social_flash', null);
        if ($test_social_flash) {
            Yii::$app->session->remove('after_link_social_flash');
            //var_dump($test_social_flash);exit;
            Yii::$app->getSession()->setFlash(
                'success',
                [
                    'message' => $test_social_flash,
                    'ttl' => 0,
                    'showClose' => true,
                    'alert_id' => 'access-control-alert',
                    'type' => 'success',
                    //'class' => 'alert-error',
                ]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        /**/
        if (!parent::beforeAction($action)) {
            return false;
        }

        /* ласт ланг */
        if ($this->CurrentUser->last_system_language != Yii::$app->language) {
            $this->CurrentUser->last_system_language = Yii::$app->language;
            $this->CurrentUser->save();
        }

        /* онлайн статус */
        if ($this->CurrentUser->user_type != Users::TYPE_ADMIN) {
            $this->CurrentUser->user_last_visit = date(SQL_DATE_FORMAT);
            $lvs_test = Yii::$app->session->get('last_visit_saved', 0);
            if (time() - $lvs_test >= Users::ONLINE_TTL) {
                Yii::$app->session->set('last_visit_saved', time());
                $this->CurrentUser->save();

                /* для всех прошедших таймлайнов нужно сбросить schedule_id (желательно это делать как можно чаще) */
                StudentsTimeline::updateAll(['schedule_id' => null], 'timeline_timestamp < :now', [
                    'now' => time(),
                ]);

                /* для всех прошедших таймлайнов со статусом STATUS_AWAIT через 72 часа нужно установить статус STATUS_PASSED */
                $past = time() - StudentsTimeline::CONFIRM_LESSON_AFTER;
                $query = "SELECT
                            teacher_user_id,
                            count(*) as cnt
                          FROM {{%students_timeline}}
                          WHERE (timeline_timestamp < :past)
                          AND (lesson_status = :STATUS_AWAIT)
                          GROUP BY teacher_user_id";
                $res_upd = Yii::$app->db->createCommand($query, [
                    'past' => $past,
                    'STATUS_AWAIT' => StudentsTimeline::STATUS_AWAIT,
                ])->queryAll();
                if (is_array($res_upd) && sizeof($res_upd)) {
                    foreach ($res_upd as $v) {
                        $query = "UPDATE {{%users}}
                                  SET user_lessons_spent = user_lessons_spent + :cnt
                                  WHERE (user_id = :teacher_user_id)";
                        Yii::$app->db->createCommand($query, [
                            'cnt'     => $v['cnt'],
                            'teacher_user_id' => $v['teacher_user_id'],
                        ])->execute();
                        //Users::updateAll(['user_lessons_spent' => $v['cnt']], ['user_id' => $v['teacher_user_id']]);
                    }
                    StudentsTimeline::updateAll(['lesson_status' => StudentsTimeline::STATUS_PASSED], '(timeline_timestamp < :past) AND (lesson_status = :STATUS_AWAIT)', [
                        'past' => $past,
                        'STATUS_AWAIT' => StudentsTimeline::STATUS_AWAIT,
                    ]);
                }
            }
        }

        /* если это студент */
        if ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_STUDENT) {

            /* получаем данные по основному учителю юзера (наверное не пригодится далее) */
            if ($this->CurrentUser->teacher_user_id) {
                $this->CurrentUser->teacherUser = $this->CurrentUser->getTeacherForThisUser();
            }

            /**/
            $this->CurrentUser->getAssignedLessons();

            /* если уже пройден хоть один из уроков с is_introduce_lesson, то юзер меняет статус */
            if ($this->CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE && $this->CurrentUser->teacher_user_id) {
                $testIntroPassed = StudentsTimeline::find()
                    ->where([
                        'student_user_id' => $this->CurrentUser->user_id,
                        'is_introduce_lesson' => StudentsTimeline::YES,
                        'lesson_status' => [StudentsTimeline::STATUS_AWAIT, StudentsTimeline::STATUS_PASSED]
                    ])
                    ->andWhere('timeline_timestamp < :past', [
                        'past' => time() - NextLessons::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED,
                    ])
                    ->all();
                if ($testIntroPassed) {
                    /** @var StudentsTimeline $stl */
                    /*
                    foreach ($testIntroPassed as $stl) {
                        if ($stl->lesson_status != StudentsTimeline::STATUS_PASSED) {
                            $stl->lesson_status = StudentsTimeline::STATUS_PASSED;
                            $stl->save();
                        }
                    }
                    */
                    $this->CurrentUser->user_status = Users::STATUS_AFTER_INTRODUCE;
                    $this->CurrentUser->save();
                }
            }

        }



        return true;
    }



    /** ================================ COMMON ACTIONS ================================= **/


    //-------------------------- вообще все авторизованные могут вызывать эти методы --------------------------------//

    /**
     * Displays homepage.
     * @return mixed
     */
    public function actionIndex()
    {
        switch ($this->CurrentUser->user_type) {
            case Users::TYPE_ADMIN:
                return $this->redirect(['/admin']);
                break;
            case Users::TYPE_OPERATOR:
                return $this->redirect(['/operator']);
                break;
            case Users::TYPE_METHODIST:
                return $this->redirect(['/methodist']);
                break;
            case Users::TYPE_TEACHER:
                return $this->redirect(['/teacher']);
                break;
            case Users::TYPE_STUDENT:
                return $this->redirect(['/student']);
                break;
            default:
                return $this->redirect(['/student']);
        }
    }

    /**
     * @return string
     */
    public function actionUploadProfilePhoto()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_FILES['user_profile_photo']) && is_uploaded_file($_FILES['user_profile_photo']['tmp_name'])) {
            if (isset($_POST['user_id']) && intval($_POST['user_id']) > 0 && $this->CurrentUser->user_type == Users::TYPE_ADMIN) {
                $User = Users::findIdentity(intval($_POST['user_id']));
                if ($User) {
                    return $User->addProfilePhoto();
                }
            } else {
                return $this->CurrentUser->addProfilePhoto();
            }
        } else {
            return [
                'type' => 'error',
                'msg' => 'wrong data',
            ];
        }
    }

    /**
     * @return array
     */
    public function actionDeleteProfilePhoto()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_GET['user_id']) && intval($_GET['user_id']) > 0 && $this->CurrentUser->user_type == Users::TYPE_ADMIN) {
            $User = Users::findIdentity(intval($_GET['user_id']));
            if ($User) {
                if ($User->deleteProfilePhoto()) {
                    return [
                        'type' => 'success',
                        'msg' => 'success msg',
                        'imgSrc' => $User->getProfilePhotoForWeb((isset(Yii::$app->params['profileNoPhoto']) ? Yii::$app->params['profileNoPhoto'] : '')),
                    ];
                }
            }
        } else {
            if ($this->CurrentUser->deleteProfilePhoto()) {
                return [
                    'type' => 'success',
                    'msg' => 'success msg',
                    'imgSrc' => $this->CurrentUser->getProfilePhotoForWeb((isset(Yii::$app->params['profileNoPhoto']) ? Yii::$app->params['profileNoPhoto'] : '')),
                ];
            }
        }

    }

    /**
     * @return string
     */
    public function actionUploadProfileVideo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_FILES['user_profile_video']) && is_uploaded_file($_FILES['user_profile_video']['tmp_name'])) {
            if (isset($_POST['user_id']) && intval($_POST['user_id']) > 0 && $this->CurrentUser->user_type == Users::TYPE_ADMIN) {
                $User = Users::findIdentity(intval($_POST['user_id']));
                if ($User) {
                    return $User->addProfileVideo();
                }
            } else {
                return $this->CurrentUser->addProfileVideo();
            }
        } else {
            return [
                'type' => 'error',
                'msg' => 'wrong data',
            ];
        }
    }

    /**
     * @return array
     */
    public function actionDeleteProfileVideo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($this->CurrentUser->deleteProfileVideo()) {
            return [
                'type' => 'success',
                'msg' => 'success msg',
                'imgSrc' => null,
            ];
        }
    }

    /**
     * @return string
     */
    public function actionProfile()
    {
        $model = ProfileForm::findIdentity($this->CurrentUser->user_id);

        /**/
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        /**/
        $tab = (isset($_POST['tab']) && in_array($_POST['tab'], ['settings', 'profile']))
            ? $_POST['tab']
            : 'profile';

        /**/
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->saveProfile()) {
                Yii::$app->session->setFlash('success', Yii::t('controllers/user', 'Profile_success'));
                if ($this->CurrentUser->user_type == Users::TYPE_TEACHER) {
                    if (!$this->CurrentUser->user_photo) {
                        Yii::$app->session->setFlash('warning', Yii::t('controllers/user', 'Profile_need_photo'));
                    }
                    if (!($this->CurrentUser->user_youtube_video || $this->CurrentUser->user_local_video)) {
                        Yii::$app->session->setFlash('danger', Yii::t('controllers/user', 'Profile_need_video'));
                    }
                    return $this->redirect(['teacher/']);
                }
                return $this->redirect(['settings-and-profile', 'tab' => $tab]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('controllers/user', 'Profile_danger'));
                return $this->redirect(['settings-and-profile', 'tab' => $tab]);
            }


        } else {

            Yii::$app->session->setFlash('danger', Yii::t('controllers/user', 'Profile_danger'));

        }

        return $this->redirect(['settings-and-profile', 'tab' => 'profile']);
    }

    /**
     * @return string
     */
    public function actionSettings()
    {

        $tab = (isset($_POST['tab']) && in_array($_POST['tab'], ['settings', 'profile']))
            ? $_POST['tab']
            : 'settings';

        if ($this->CurrentUser->load($_POST) && $this->CurrentUser->validate()) {

            if (!isset($_POST['Users']['receive_system_notif'])) { $this->CurrentUser->receive_system_notif = Users::NO; }
            if (!isset($_POST['Users']['receive_lesson_notif'])) { $this->CurrentUser->receive_lesson_notif = Users::NO; }
            if ($this->CurrentUser->save()) {
                Yii::$app->session->setFlash('success', Yii::t('controllers/user', 'Settings_success'));
                if ($this->CurrentUser->user_type == Users::TYPE_TEACHER) {
                    return $this->redirect(['teacher/']);
                }
                return $this->redirect(['settings-and-profile', 'tab' => $tab]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('controllers/user', 'Settings_danger'));
                return $this->redirect(['settings-and-profile', 'tab' => $tab]);
            }

        }

        return $this->redirect(['settings-and-profile', 'tab' => 'settings']);
    }

    /**
     * @return string
     */
    public function actionSettingsAndProfile()
    {
        if ($this->CurrentUser->user_type == Users::TYPE_TEACHER && file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/teacher/settings-and-profile.php')) {
            $model = new StaticActionModel();
            $additionalData = $model->findTutors();
            return $this->render('/teacher/settings-and-profile', [
                'ProfileForm' => ProfileForm::initUser($this->CurrentUser->user_id, [[
                    ['user_last_name', 'discipline_id', 'user_price_peer_hour', 'user_additional_info', '_user_skype', /*, 'user_youtube_video',/*'user_are_native'*/ ], 'required']
                ]),//ProfileForm::findIdentity($this->CurrentUser->user_id),
                'Auth' => Auth::find()->where([
                    'user_id' => $this->CurrentUser->user_id,
                ])->all(),
                'CurrentUser' => $this->CurrentUser,
                'Disciplines' => $additionalData['disciplines'],
                'TeachersDisciplines' => TeachersDisciplines::find()
                    ->where([
                        'teacher_user_id' => $this->CurrentUser->user_id
                    ])
                    ->orderBy(['discipline_id'=> SORT_ASC])
                    ->one(),
                'countries'   => $additionalData['countries'],
            ]);
        } elseif ($this->CurrentUser->user_type == Users::TYPE_STUDENT && file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/student/settings-and-profile.php')) {
            return $this->render('/student/settings-and-profile', [
                'ProfileForm' => ProfileForm::findIdentity($this->CurrentUser->user_id),
                'Auth' => Auth::find()->where([
                    'user_id' => $this->CurrentUser->user_id,
                ])->all(),
                'CurrentUser' => $this->CurrentUser,
            ]);
        } elseif (file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/common/settings-and-profile.php')) {
            return $this->render('/common/settings-and-profile', [
                'ProfileForm' => ProfileForm::findIdentity($this->CurrentUser->user_id),
                'Auth' => Auth::find()->where([
                    'user_id' => $this->CurrentUser->user_id,
                ])->all(),
                'CurrentUser' => $this->CurrentUser,
            ]);
        } else {
            return '';
        }
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        $model->email = $this->CurrentUser->user_email;
        if ($model->validate()) {
            $send = $model->sendEmail();
        } else {
            $send = false;
        }

        if ($send) {
            Yii::$app->session->setFlash('success', Yii::t('controllers/user', 'ResendVerificationEmail_success'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('controllers/user', 'ResendVerificationEmail_danger'));
        }

        return $this->redirect(['user/']);
    }



    //--------------------------  --------------------------------//

    /**
     * @param string $room
     * @return string
     */
    public function actionConferenceRoom($room)
    {
        $this->layout = 'member-class-room';

        return $this->render('/common/educational-class-room', [
            'room' => $room,
            'CurrentUser' => $this->CurrentUser,
            'NextLesson' => null,
            'is_test_student' => isset($_GET['is_test_student']),
            'is_class_room' => false,
        ]);

    }

    /**
     * @return string
     */
    public function actionDeviceTest()
    {
        return $this->render('/common/device-test', [
            'CurrentUser' => $this->CurrentUser,
        ]);
    }



    //--------------------------  --------------------------------//

    /**
     * @return string
     */
    public function actionGetNotificationMessages()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new NotificationsDataSearch();
        $model->CurrentUser = $this->CurrentUser;
        if (!$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        $ret = $model->getNotifData();

        return [
            'status' => true,
            'data' => [
                'total_count_new_notifications' => $ret['total_count_new_notifications'],
                'notifications' => $this->renderPartial('/common/notif-messages', [
                    'messages' => $ret['messages'],
                    'CurrentUser' => $this->CurrentUser,
                ]),
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionSetNotificationsAsRead()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new NotificationsDataSearch();
        $model->CurrentUser = $this->CurrentUser;
        if (!$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        /**/
        return [
            'status' => true,
            'data' => $model->setNotifAsRead(),
        ];
    }

    /**
     * @return string
     */
    public function actionGetChatMessages()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ChatDataSearch();
        $model->CurrentUser = $this->CurrentUser;
        if (!$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        /**/
        $ret = $model->getChatData();

        return [
            'status' => true,
            'data' => [
                'total_count_new_opponents' => $ret['total_count_new_opponents'],
                'total_count_new_messages' => $ret['total_count_new_messages'],
                'chat_users' => $this->renderPartial('/common/chat-users', [
                    'users' => $ret['users'],
                    'CurrentUser' => $this->CurrentUser,
                ]),
                'chat_messages' => $this->renderPartial('/common/chat-messages', [
                    'messages' => $ret['messages'],
                    'CurrentUser' => $this->CurrentUser,
                ]),
            ],
        ];
    }

    /**
     * @param int $sender_user_id
     * @return array
     */
    public function actionSetChatAsRead($sender_user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ChatDataSearch(['sender_user_id']);
        $model->CurrentUser = $this->CurrentUser;
        $model->sender_user_id = intval($sender_user_id);

        /**/
        if (!$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        /**/
        return [
            'status' => true,
            'data' => $model->setChatAsRead(),
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStartChatWith()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ChatDataSearch();
        $model->CurrentUser = $this->CurrentUser;

        $model = new ChatDataSearch([
            'opponent_user_id',
            'opponent_display_name',
            'opponent_first_name',
            'opponent_last_name',
            //'opponent_photo',
            'opponent_type',
        ]);
        $model->CurrentUser = $this->CurrentUser;
        if (!$model->load([$model->formName() => Yii::$app->request->post()]) || !$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        /**/
        $model->startChatWith();

        /**/
        $ret = $model->getChatData();

        return [
            'status' => true,
            'data' => [
                'chat_users' => $this->renderPartial('/common/chat-users', [
                    'users' => $ret['users'],
                    'CurrentUser' => $this->CurrentUser,
                ]),
                'chat_messages' => $this->renderPartial('/common/chat-messages', [
                    'messages' => $ret['messages'],
                    'CurrentUser' => $this->CurrentUser,
                ]),
            ],
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSendChatMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ChatDataSearch();
        $model->CurrentUser = $this->CurrentUser;

        $model = new ChatDataSearch([
            'msg_text',
            'receiver_user_id',
        ]);
        $model->CurrentUser = $this->CurrentUser;
        if (!$model->load([$model->formName() => Yii::$app->request->post()]) || !$model->validate()) {
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

        /**/
        $res = $model->sendChatMessage();
        if (!is_array($res)) {
            return [
                'status' => false,
                'info' => 'DB error.',
            ];
        }

        /**/
        return [
            'status' => true,
            'data' => [
                'opponent_messages' => $this->renderPartial('/common/chat-messages', [
                    'messages' => $res,
                    'CurrentUser' => $this->CurrentUser,
                ]),
            ],
        ];
    }



    //------------------ отзывы об учителе только студенты или админ могут делать --------------------//

    /**
     * @param int $teacher_user_id
     * @return string
     */
    public function actionCheckOpenReview($teacher_user_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $teacher_user_id =intval($teacher_user_id);

        $is_admin = Yii::$app->session->get('is_from_admin_for_manager_users', false);

        /**/
        if ($is_admin) {
            $test = true;
        } else {
            $test = StudentsTimeline::find()->where([
                'teacher_user_id' => $teacher_user_id,
                'student_user_id' => $this->CurrentUser->user_id,
            ])->andWhere('timeline_timestamp < :now', [
                'now' => time(),
            ])->one();
        }
        if (!$test) {
            return [
                'status' => false,
                'data' => 'have_no_this_teacher',
            ];
        }

        /**/
        if ($is_admin) {
            $test2 = false;
        } else {
            $test2 = Reviews::findOne([
                'teacher_user_id' => $teacher_user_id,
                'student_user_id' => $this->CurrentUser->user_id,
            ]);
        }
        if ($test2) {
            return [
                'status' => false,
                'data' => 'already_leave_review',
            ];
        }

        /**/
        return [
            'status' => true,
        ];
    }

    public function actionSendReview()
    {
        /**/
        $data = Yii::$app->request->post();

        /**/
        if (!isset($data['teacher_user_id'])) {
            return '';
        }

        $ret = $this->actionCheckOpenReview($data['teacher_user_id']);
        Yii::$app->response->format = Response::FORMAT_HTML;
        if (!$ret['status']) {
            return '';
        }

        /**/
        $model = new Reviews();
        $data['student_user_id'] = $this->CurrentUser->user_id;
        if ($model->load([$model->formName() => $data]) && $model->validate() && $model->save()) {

            $tutor = Users::findIdentity($model->teacher_user_id);
            return $this->renderPartial('/site/static/tutor-reviews', [
                'reviews' => Reviews::find()->where(['teacher_user_id' => $model->teacher_user_id])->orderBy(['review_created' => SORT_DESC])->all(),
                'CurrentUser' => $this->CurrentUser,
                'tutor' => $tutor,
            ]);

        }

        /**/
        return '';
    }


    //------------------ все авторизованные (кроме опреатора и админа) могут вызывать эти методы --------------------//

    /**
     * @return string
     */
    public function actionGetSchedule()
    {
        // get-schedule
        Yii::$app->response->format = Response::FORMAT_JSON;

        /* выбор модели в зависимости от типа юзера */
        switch ($this->CurrentUser->user_type) {
            case Users::TYPE_TEACHER:
                $model = new TeachersScheduleForm();
                break;
            case Users::TYPE_STUDENT:
                $model = new StudentsScheduleForm();
                break;
            default:
                $model = new CommonScheduleForm();
        }

        /**/
        if ($model->load([$model->formName() => [
                'user_id'       => $this->CurrentUser->user_id,
                'user_type'     => $this->CurrentUser->user_type,
                'user_timezone' => $this->CurrentUser->user_timezone,
            ]]) && $model->validate()) {

            return [
                'status' => true,
                'data'   => $model->getSchedule(),
            ];

        } else {
            return [
                'status' => false,
                'data'   => $model->getErrors(),
            ];
        }
    }

    /**
     * @param integer $week_day
     * @param integer $work_hour
     * @param integer $hour_status
     * @return array
     */
    public function actionChangeSchedule($week_day, $work_hour, $hour_status)
    {
        // change-schedule
        Yii::$app->response->format = Response::FORMAT_JSON;

        /* выбор модели в зависимости от типа юзера */
        switch ($this->CurrentUser->user_type) {
            case Users::TYPE_TEACHER:
                $model = new TeachersScheduleForm();
                break;
            case Users::TYPE_STUDENT:
                $model = new StudentsScheduleForm();
                break;
            default:
                $model = new StudentsScheduleForm();
        }

        /* учет таймзоны юзера */
        $tmp = CommonScheduleForm::dayAndHourFromTzToGmt($week_day ,$work_hour, $this->CurrentUser->user_timezone);
        $week_day  = $tmp['week_day'];
        $work_hour = $tmp['work_hour'];
        //var_dump($work_hour); var_dump($week_day); exit;

        /* подготовка массива данных */
        $data = [
            'user_id'       => $this->CurrentUser->user_id,
            'user_type'     => $this->CurrentUser->user_type,
            'user_timezone' => $this->CurrentUser->user_timezone,
            'week_day'      => $week_day,
            'work_hour'     => $work_hour,
            'hour_status'   => $hour_status,
        ];
        if (isset($_GET['date_start'])) {
            $data['date_start'] = $_GET['date_start'];
        }
        if ($this->CurrentUser->user_type == Users::TYPE_STUDENT) {
            $data['teacher_user_id'] = $this->CurrentUser->teacher_user_id;
        }

        /* запуск модели с массивом данных */
        if ($model->load([$model->formName() => $data]) && $model->validate()) {

            return [
                'status' => true,
                'data'   => $model->changeSchedule(),
            ];

        } else {
            return [
                'status' => false,
                'data'   => $model->getErrors(),
            ];
        }
    }



    //------------------------- учитель, студент могут вызывать эти методами ----------------------------//

    /**
     * @param string $room
     * @return string
     */
    public function actionEducationalClassRoom($room)
    {
        $NextLesson = NextLessons::checkEducationalLessonRoomHash($room, $this->CurrentUser);
        if (!$NextLesson) {
            $left_minutes = $this->CurrentUser->user_type == Users::TYPE_STUDENT
                ? intval(Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT/60)
                : intval(Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER/60);
            Yii::$app->session->setFlash('access-control-alert-error', [
                'message'   => Yii::t('controllers/user', 'EducationalClassRoom_error', [
                    'count_minutes' => $left_minutes,
                    'word_minutes_ru' => Functions::left_minutes_ru_text($left_minutes)[0],
                ]),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'access-control-alert',
                'type' => 'error',
                //'class' => 'alert-error',
            ]);
            return $this->redirect(['user/']);
        }

        /* чат */
        Chat::initChatBetweenUsers(
            $this->CurrentUser->user_id,
            $this->CurrentUser->user_id == $NextLesson->teacher_user_id
                ? $NextLesson->student_user_id
                : $NextLesson->teacher_user_id,
            "Class-Room with opponent are opened. It's a system message, no need answer.",
            Chat::YES,
            Chat::NO,
            false
        );

        /**/
        $this->layout = 'member-class-room';

        return $this->render('/common/educational-class-room', [
            'room' => $room,
            'CurrentUser' => $this->CurrentUser,
            'NextLesson' => $NextLesson,
            'is_test_student' => isset($_GET['is_test_student']),
            'is_class_room' => true,
        ]);
    }



    //------------------------ только admin оператор методист и учитель ------------------------------//

    /**
     * @param $user_id
     * @return string
     */
    public function actionGetUserInfo($user_id)
    {
        // get-user-info
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $user_id = intval($user_id);

        /**/
        if ($this->checkIsTeacher()) {
            $user_types_allowed = [Users::TYPE_STUDENT];
            $owner_field = ['teacher_user_id' => $this->CurrentUser->user_id];
        }

        /**/
        $query = Users::find()
            ->where(['user_id' => $user_id]);

        if (isset($user_types_allowed)) {
            $query->andWhere(['user_type' => $user_types_allowed]);
        }

        if (isset($owner_field)) {
            $query->andWhere($owner_field);
        }

        $User = $query
            ->asArray()
            ->one();

        /**/
        if (!$User) {
            return [
                'status' => false,
                'info'   => 'User not found',
            ];
        }

        /**/
        $messengers = Users::staticGetCustomMessengers($User['user_custom_messengers']);
        $User = array_merge($User, $messengers);


        /**/
        $User['user_gender'] = Users::getGender($User['user_gender']);
        $User['user_created'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User['user_created']);
        $User['user_updated'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User['user_updated']);
        $User['user_age'] = Users::staticGetAge($User['user_birthday']);
        $User['user_age'] = Functions::ru_string_age($User['user_age']);
        $User['user_photo'] = Users::staticGetProfilePhotoForWeb($User['user_photo'], '/assets/smartsing-min/images/no_photo.png');
        $User['owner_user_notice'] = (
            $this->checkIsAdmin()
                ? $User['admin_notice']
                : $User['teacher_notice']
        );
        $Operator = Users::findById($User['operator_user_id']);
        $User['user_operator'] = $Operator ? $Operator->user_full_name : 'Не назначен';
        $Methodist = Users::findById($User['methodist_user_id']);
        $User['user_methodist'] = $Methodist ? $Methodist->user_full_name : 'Не назначен';
        $User['introduce_lesson_date'] = 'Не назначен';
        /** @var \common\models\MethodistTimeline $intro */
        $intro = MethodistTimeline::find()->where(['student_user_id' => $User['user_id']])->orderBy(['timeline_timestamp' => SORT_ASC])->one();
        if ($intro) {
            $User['introduce_lesson_date'] = $this->CurrentUser->getDateInUserTimezoneByTimestamp($intro->timeline_timestamp);
        }
        if ($User['user_last_pay']) {
            $User['user_last_pay'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User['user_last_pay']);
        } else {
            $User['user_last_pay'] = 'Оплат еще не было';
        }

        $User['user_last_lesson'] = $this->CurrentUser->getDateInUserTimezoneByDateString($User['user_last_lesson']);
        /*
         {"status":true,"data":{"user_id":11,"user_created":"2020-08-07 10:30:18","user_updated":"2021-02-10 10:17:58","user_first_name":"operator2","user_middle_name":"operator2","user_last_name":"operator2","user_full_name":"operator2","user_email":"operator2@gmail.com","user_phone":"44444","user_last_pay":null,"user_status":10,"user_type":1,"operator_user_id":null,"operator_notice":"","methodist_user_id":null,"methodist_notice":"","teacher_user_id":null,"teacher_notice":"","user_balance":"0.00","user_last_ip":2130706433,"user_level_general":0,"user_level_range":0,"user_level_coordination":0,"user_level_timbre":0,"user_need_set_password":1,"user_photo":null,"user_gender":null,"user_timezone":0,"user_birthday":null,"user_learning_objectives":null,"user_music_experience":null,"user_music_genres":null,"user_additional_info":null,"receive_system_notif":0,"receive_lesson_notif":0,"notes_played":0,"notes_hit":0,"notes_close":0,"notes_lowest":null,"notes_highest":null,"user_level_general_notice":null,"user_level_range_notice":null,"user_level_coordination_notice":null,"user_level_timbre_notice":null,"additional_service_info":"HTTP_USER_AGENT: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36 &lt;br /&gt;\nREMOTE_ADDR: 127.0.0.1 &lt;br /&gt;\n","additional_service_notice":"","admin_user_id":1,"admin_notice":"","user_lessons_available":0,"user_lessons_completed":0,"user_lessons_missed":0,"_user_skype":"BBBB","_user_telegram":"AAAA","_user_viber":"","_user_jabber":"","_user_icq":""}}
         */

        /**/
        unset(
            $User['auth_key'],
            $User['password_hash'],
            $User['password_reset_token'],
            $User['verification_token'],
            $User['user_token'],
            $User['user_hash'],
            $User['user_custom_messengers']
        );
        if (!$this->checkIsAdmin()) {
            unset(
                $User['user_balance'],
                $User['user_last_pay']
            );
        }
        if ($User['user_type'] !== Users::TYPE_STUDENT) {
            /*
            unset(
                $User[''],
                $User['']
            );
            */
        }

        /**/
        return [
            'status' => true,
            'data' => $User,
        ];
    }


}
