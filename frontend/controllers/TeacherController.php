<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\Users;
use common\models\TeachersDisciplines;
use common\models\TeachersSchedule;
use frontend\models\schedule\TeachersScheduleForm;
use frontend\models\search\NextLessons;
use frontend\models\schedule\CommonScheduleForm;
use frontend\models\forms\ProfileForm;
use frontend\models\search\FinanceSearch;

/**
 * User controller
 */
class TeacherController extends UserController
{
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
                        'allow' => $this->checkIsTeacher(),
                        'roles' => ['@'],
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
        $model = new CommonScheduleForm();
        $model->user_id = $this->CurrentUser->user_id;
        $model->user_type = $this->CurrentUser->user_type;
        $model->user_timezone = $this->CurrentUser->user_timezone;

        $scheduleModel = new TeachersScheduleForm();
        if ($scheduleModel->load([$scheduleModel->formName() => [
                'user_id'       => $this->CurrentUser->user_id,
                'user_type'     => Users::TYPE_TEACHER,
                'user_timezone' => $this->CurrentUser->user_timezone,
            ]]) && $scheduleModel->validate()) {

        }
        return $this->render('index', [
            'CurrentUser' => $this->CurrentUser,
            'ProfileForm' => ProfileForm::findIdentity($this->CurrentUser->user_id),
            'TeachersDisciplines' => TeachersDisciplines::find()
                ->where([
                    'teacher_user_id' => $this->CurrentUser->user_id
                ])
                ->orderBy(['discipline_id'=> SORT_ASC])
                ->one(),
            'NextLesson'  => NextLessons::getTeacherLesson($this->CurrentUser->user_id),
            'DashboardSchedule' => $model->getScheduleForDashboard(),
            'DashboardSchedule_v2' => $scheduleModel->getScheduleForTwoWeekByDate($this->CurrentUser->_user_local_time, $this->CurrentUser->user_timezone, true),
        ]);
    }

    /**
     * @return string
     */
    public function actionFinance()
    {
        return $this->render('finance', [
            'CurrentUser' => $this->CurrentUser,
            'Transactions' => FinanceSearch::getTeacherTransactionHistory(
                $this->CurrentUser->user_id,
                Yii::$app->request->get('sort', SORT_ASC),
                intval(Yii::$app->request->get('page', 1)),
                intval(Yii::$app->request->get('per-page', 8))
            ),
        ]);
    }

    /**
     *
     */
    public function actionSendProfileForApprove()
    {
        $TeachersDisciplines = TeachersDisciplines::find()
            ->where([
                'teacher_user_id' => $this->CurrentUser->user_id
            ])
            ->orderBy(['discipline_id'=> SORT_ASC])
            ->one();

        $TeacherSchedule = TeachersSchedule::find()
            ->where(['teacher_user_id' => $this->CurrentUser->user_id])
            ->one();

        if ($TeachersDisciplines &&
            $TeacherSchedule &&
            $this->CurrentUser->user_email &&
            $this->CurrentUser->user_first_name &&
            $this->CurrentUser->user_last_name &&
            $this->CurrentUser->user_are_native &&
            $this->CurrentUser->user_price_peer_hour > 0 &&
            $this->CurrentUser->user_additional_info &&
            $this->CurrentUser->user_photo &&
            $this->CurrentUser->user_local_video || $this->CurrentUser->user_youtube_video) {

            $this->CurrentUser->teacher_profile_completed = Users::TEACHER_PROFILE_WAIT_APPROVE;
            $this->CurrentUser->save();

            Yii::$app->session->setFlash('send-profile-for-approve', [
                'message'   => Yii::t('controllers/teacher', 'Profile_was_sent_for_approve'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'send-profile-for-approve',
                'type' => 'success',
            ]);
        } else {
            Yii::$app->session->setFlash('send-profile-for-approve', [
                'message'   => Yii::t('controllers/teacher', 'Error_on_sent_profile_for_approve'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id'  => 'send-profile-for-approve',
                'type'      => 'error',
            ]);
        }

        $this->goBack(['teacher/']);

    }

    /**
     * @param string $wallet
     * @return string
     */
    public function actionChangeWalletForWithdraw($wallet)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $this->CurrentUser->pay_to_wallet = $wallet;
        if ($this->CurrentUser->save()) {
            return [
                'status' => true,
                'info' => Yii::t('controllers/teacher', 'Wallet_type_changed_successfully'),
            ];
        } else {
            return [
                'status' => false,
                'info' => Yii::t('controllers/teacher', 'Some_error_on_change_wallet_type'),
            ];
        }
    }

    /**
     * @return string
     */
    public function actionSaveDataForWallets()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $old_wallet_yandex = $this->CurrentUser->wallet_yandex;
        $old_wallet_paypal = $this->CurrentUser->wallet_paypal;

        if ($this->CurrentUser->load(['Form' => Yii::$app->request->post()], 'Form') &&
            $this->CurrentUser->validate()) {

            if (trim($this->CurrentUser->wallet_yandex) == '') { $this->CurrentUser->wallet_yandex = null; }
            if (trim($this->CurrentUser->wallet_paypal) == '') { $this->CurrentUser->wallet_paypal = null; }

            if ($this->CurrentUser->wallet_paypal != $old_wallet_paypal) {
                $this->CurrentUser->pay_to_wallet = Users::PAY_TO_PAYPAL;
            }
            if ($this->CurrentUser->wallet_yandex != $old_wallet_yandex) {
                $this->CurrentUser->pay_to_wallet = Users::PAY_TO_YANDEX;
            }

            if ($this->CurrentUser->save()) {

                //var_dump($this->getViewPath());exit;
                if (file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/helpers/finance-js-paymentsData.php')) {
                    require_once(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/helpers/finance-js-paymentsData.php');
                }

                return [
                    'status' => true,
                    'paymentsData' => getPaymentsArray($this->CurrentUser),
                    'info' => Yii::t('controllers/teacher', 'Wallet_requisites_saved_successfully'),
                ];
            }

        }

        return [
                'status' => false,
                'info' => Yii::t('controllers/teacher', 'Some_error_on_save_wallet_requisites'),
            ];

    }








    /* =========================== OFF =========================== */

//    /**
//     * @return string
//     */
//    public function actionScheduleOld()
//    {
//        return $this->render('schedule-old', [
//            'CurrentUser' => $this->CurrentUser,
//        ]);
//    }
//
//    /**
//     * @return string
//     */
//    public function actionSchedule()
//    {
//        $model = new CommonScheduleForm();
//        $model->user_id = $this->CurrentUser->user_id;
//        $model->user_type = $this->CurrentUser->user_type;
//        $model->user_timezone = $this->CurrentUser->user_timezone;
//
//        return $this->render('schedule', [
//            'CurrentUser' => $this->CurrentUser,
//            'NextLesson'  => NextLessons::getTeacherLesson($this->CurrentUser->user_id),
//            'DashboardSchedule' => $model->getScheduleForDashboard(),
//        ]);
//    }
//
//    /**
//     * @return string
//     */
//    public function actionSaveStudentResult()
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $room_hash = Yii::$app->request->post('room_hash', null);
//        $student_user_id = intval(Yii::$app->request->post('student_user_id', null));
//
//        if (!$room_hash || !$student_user_id) {
//            return [
//                'status' => false,
//                'error'  => 'Wrong POST data',
//            ];
//        }
//
//        $NextLessons = NextLessons::checkEducationalLessonRoomHash($room_hash, $this->CurrentUser);
//        if (!$NextLessons) {
//            return [
//                'status' => false,
//                'error'  => 'Wrong room_hash',
//            ];
//        }
//
//        if ($NextLessons->student_user_id !== $student_user_id) {
//            return [
//                'status' => false,
//                'error'  => 'Wrong student_user_id',
//                'data'   => Yii::$app->request->post(),
//            ];
//        }
//
//        $User = Users::findById($student_user_id);
//        if (!$User) {
//            return [
//                'status' => false,
//                'error'  => 'Student not found for this room',
//            ];
//        }
//
//        //var_dump(Yii::$app->request->post());exit;
//        //var_dump($User->_lesson_notice);exit;
//        /*
//        if (!$NextLessons->load(['UserData' => Yii::$app->request->post()], 'UserData')) {
//            return [
//                'status' => false,
//                'error'  => $User->getErrors(),
//            ];
//        }
//        */
//
//        $NextLessons->notes_played = Yii::$app->request->post('notes_played', null);
//        $NextLessons->notes_hit = Yii::$app->request->post('notes_hit', 0);
//        $NextLessons->notes_close = Yii::$app->request->post('notes_close', 0);
//        $NextLessons->notes_lowest = Yii::$app->request->post('notes_lowest', 0);
//        $NextLessons->notes_highest = Yii::$app->request->post('notes_highest', null);
//        $NextLessons->lesson_status = Yii::$app->request->post('_lesson_status', null);
//        $NextLessons->lesson_notice = Yii::$app->request->post('_lesson_notice', null);
//        if (!$NextLessons->validate() || !$NextLessons->save()) {
//            return [
//                'status' => false,
//                'error'  => $NextLessons->getErrors(),
//            ];
//        }
//
//        return [
//            'status' => true,
//            'data'   => [
//                //'url' => Url::to(['student/after-introduce'], CREATE_ABSOLUTE_URL),
//                'slide_num' => 13,
//            ],
//        ];
//    }
}
