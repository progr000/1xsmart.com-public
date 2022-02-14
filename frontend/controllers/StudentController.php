<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use common\helpers\Functions;
use common\models\Users;
use common\models\Payments;
use common\models\StudentsTimeline;
use common\models\TeachersSchedule;
use frontend\models\schedule\StudentsScheduleForm;
use frontend\models\schedule\TeachersScheduleForm;
use frontend\models\search\NextLessons;
use frontend\models\schedule\CommonScheduleForm;
use frontend\modules\tinkoff\models\TinkoffApi;
use frontend\models\schedule\StudentMoveLessonForm;
use frontend\models\search\FinanceSearch;

/**
 * User controller
 */
class StudentController extends UserController
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
                        'allow' => $this->checkIsStudent(),
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
        /* проверим если интро урок пройден (user_status = STATUS_AFTER_INTRODUCE) и нет больше уроков будущих то редиректим на покупку пакета */
        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) {
            $testIntroNotPassed = StudentsTimeline::find()
                ->where([
                    'student_user_id' => $this->CurrentUser->user_id,
                    //'is_introduce_lesson' => StudentsTimeline::YES,
                ])
                ->andWhere('timeline_timestamp > :past', [
                    'past' => time() - NextLessons::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED,
                ])
                ->one();
            if (!$testIntroNotPassed) {
                return $this->redirect(['/student/after-introduce']);
            }
        }

        /**/
        $model = new CommonScheduleForm();
        $model->user_id = $this->CurrentUser->user_id;
        $model->user_type = $this->CurrentUser->user_type;
        $model->user_timezone = $this->CurrentUser->user_timezone;

        $scheduleModel = new StudentsScheduleForm();
        if ($scheduleModel->load([$scheduleModel->formName() => [
                'user_id'       => $this->CurrentUser->user_id,
                'user_type'     => Users::TYPE_STUDENT,
                'user_timezone' => $this->CurrentUser->user_timezone,
                'teacher_user_id' => $this->CurrentUser->teacher_user_id,
            ]]) && $scheduleModel->validate()) {
        }
        return $this->render('index', [
            'CurrentUser' => $this->CurrentUser,
            'NextLesson'  => NextLessons::getStudentLesson($this->CurrentUser),
            'DashboardSchedule' => $model->getScheduleForDashboard(),
            //'StudentsTimeline' => StudentsScheduleForm::getStudentsTimelineWhichCanChange($this->CurrentUser->user_id),
            'DashboardSchedule_v2' => $scheduleModel->getScheduleForTwoWeekByDate($this->CurrentUser->_user_local_time, $this->CurrentUser->user_timezone, true),
        ]);
    }

    /**
     * @return string
     */
    public function actionSetSchedule()
    {
        /**/
        if ($this->CurrentUser->after_payment_action != Users::AFTER_PACKAGE_ACTION) {
            return $this->redirect(['/student']);
        }

        /**/
        if (!in_array($this->CurrentUser->user_status, [Users::STATUS_ACTIVE, Users::STATUS_AFTER_PAYMENT])) {
            return $this->redirect(['/student']);
        }

        /**/
        if ($this->CurrentUser->user_lessons_available <=0) {
            Yii::$app->session->setFlash('error',  Yii::t('controllers/student', 'You_have_not_any_paid_lessons'));
            Yii::$app->session->set('set_schedule_step', 1);
            return $this->redirect(['/student/finance']);
        }

        /**/
        $step = Yii::$app->session->get('set_schedule_step', 1);
        if ($step == 5) {
            Yii::$app->session->set('set_schedule_step', 1);
        }

        //$this->layout = 'member-after';
        return $this->render('set-schedule', [
            'CurrentUser' => $this->CurrentUser,
            'step' => Yii::$app->session->get('set_schedule_step', 1),
            'date_start' => date('Y-m-d'), //Yii::$app->session->get('set_schedule_date_start', date('Y-m-d')),
        ]);
    }

    /**
     * @param $step
     * @return string
     */
    public function actionChangeStep($step)
    {
        // change-step
        Yii::$app->response->format = Response::FORMAT_JSON;

        $step = intval($step);
        Yii::$app->session->set('set_schedule_step', $step);

        if (isset($_GET['date_start'])) {
            $_GET['date_start'] = date('Y-m-d', strtotime($_GET['date_start']));
            Yii::$app->session->set('set_schedule_date_start', $_GET['date_start']);
        }

        return [
            'status' => true,
            'data'   => [
                'changed' => true,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionGenerateTimelinesAfterSetupSchedule()
    {
        // generate-timelines-after-setup-schedule
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        if ($this->CurrentUser->after_payment_action != Users::AFTER_PACKAGE_ACTION) {
            return [
                'status' => false,
            ];
        }

        /**/
        if (!in_array($this->CurrentUser->user_status, [Users::STATUS_ACTIVE, Users::STATUS_AFTER_PAYMENT])) {
            return [
                'status' => false,
            ];
        }

        /**/
        $model = new StudentsScheduleForm();
        if ($model->load(['StudentsScheduleForm' => [
                'user_id'         => $this->CurrentUser->user_id,
                'user_type'       => $this->CurrentUser->user_type,
                'user_timezone'   => $this->CurrentUser->user_timezone,
                'teacher_user_id' => $this->CurrentUser->teacher_user_id,
                'date_start'      => Yii::$app->request->get('date_start', date(SQL_DATE_FORMAT)),
            ]]) && $model->validate()) {

            return $model->generateTimelinesAfterSetupSchedule();

        } else {
            return [
                'status' => false,
                'data'   => $model->getErrors(),
            ];
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionScheduleMoveLesson()
    {
        // schedule-move-lesson
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new StudentMoveLessonForm();
        $model->Student = $this->CurrentUser;
        if ($model->load([$model->formName() => Yii::$app->request->post()]) && $model->validate()) {

            return $model->moveLesson();

        } else {
            //var_dump($_POST);
            return [
                'status' => false,
                'info' => $model->getErrors(),
            ];
        }

    }

    /**
     *
     */
    public function actionGetMyTeacherSchedule()
    {
        // get-my-teacher-schedule
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $model = new TeachersScheduleForm();

        /**/
        if ($model->load([$model->formName() => [
                'user_id'       => $this->CurrentUser->teacher_user_id,
                'user_type'     => Users::TYPE_TEACHER,
                'user_timezone' => $this->CurrentUser->user_timezone,
                'synchronize_schedule_for_student_user_id' => $this->CurrentUser->user_id,
            ]]) && $model->validate()) {

            return [
                'status' => true,
                'data'   => $model->getSchedule(), //$model->getSchedule(true),
            ];

        } else {
            return [
                'status' => false,
                'data'   => $model->getErrors(),
            ];
        }
    }


    // ----------------------------------------------------

    /**
     * @return string
     */
    public function actionAfterIntroduce()
    {
        /**/
        if ($this->CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
            Yii::$app->session->setFlash('error', [
                'message'   => Yii::t('controllers/student', 'Available_after_introductory_lesson'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'payment-danger-alert',
                'type' => 'danger',
                //'class' => 'alert-error',
            ]);
            return $this->redirect(['student/']);
        }

        /**/
        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
            return $this->redirect(['set-schedule']);
        }

        /**/
        $Teachers = FinanceSearch::getTeachersForByPackage($this->CurrentUser->user_id);
        if (!$Teachers) {
            Yii::$app->session->setFlash('error', [
                'message'   => Yii::t('controllers/student', 'not_completed_your_first_teacher_session'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'payment-error-alert',
                'type' => 'error',
                //'class' => 'alert-error',
            ]);
            return $this->redirect(['/find-tutors']);
        }

        /**/
        return $this->render('after-introduce', [
            'CurrentUser' => $this->CurrentUser,
            'Teachers' => $Teachers,
        ]);
    }

    /**
     * @return string
     */
    public function actionFinance()
    {
        /**/
        if ($this->CurrentUser->user_status == Users::STATUS_BEFORE_INTRODUCE) {
            Yii::$app->session->setFlash('error', [
                'message'   => Yii::t('controllers/student', 'Available_after_introductory_lesson'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'payment-danger-alert',
                'type' => 'danger',
                //'class' => 'alert-error',
            ]);
            return $this->redirect(['student/']);
        }

        /**/
        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) {
            return $this->redirect(['after-introduce']);
        }

        /**/
        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
            return $this->redirect(['set-schedule']);
        }

        /**/
        $Teachers = FinanceSearch::getTeachersForByPackage($this->CurrentUser->user_id);
        if (!$Teachers) {
            Yii::$app->session->setFlash('error', [
                'message'   => Yii::t('controllers/student', 'not_completed_your_first_teacher_session'),
                'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
                'showClose' => true,
                'alert_id' => 'payment-error-alert',
                'type' => 'error',
                //'class' => 'alert-error',
            ]);
            return $this->redirect(['/find-tutors']);
        }

        return $this->render('finance', [
            'CurrentUser' => $this->CurrentUser,
            'Teachers' => $Teachers,
            'Transactions' => FinanceSearch::getStudentTransactionHistory(
                $this->CurrentUser->user_id,
                Yii::$app->request->get('sort', SORT_ASC),
                intval(Yii::$app->request->get('page', 1)),
                intval(Yii::$app->request->get('per-page', 8))
            ),
        ]);
    }


    // ----------------------------------------------------

    /**
     * @param $teacher_user_id
     * @param $lessons_count
     * @param $amount
     * @param $description
     * @return array
     */
    public function actionStartTinkoffPaymentPackageLessons($teacher_user_id, $lessons_count, $amount, $description)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $lessons_count = intval($lessons_count);
        $teacher_user_id = intval($teacher_user_id);

        /**/
//        if (Yii::$app->session->get('payment_success', false)) {
//            return [
//                'status' => false,
//                'info'   => 'Оплата уже была произведена. Обновите страницу.',
//            ];
//        }

        /**/
        foreach (StudentsTimeline::$discount_vars as $vars) {
            if ($vars['lessons_count'] == $lessons_count) {
                $amount_data = $vars;
            }
        }
        if (!isset($amount_data)) {
            return [
                'status' => false,
                'info'   => 'Wrong lesson count',
            ];
        }

        /**/
        /** @var Users $teacher */
        $teacher = Users::find()
            ->alias('t1')->select('t1.*')
            ->innerJoin('{{%students_timeline}} as t2', 't1.user_id = t2.teacher_user_id')
            ->where([
                't1.user_id'         => $teacher_user_id,
                't1.user_type'       => Users::TYPE_TEACHER,
                't2.student_user_id' => $this->CurrentUser->user_id,
            ])->one();
        if (!$teacher) {
            return [
                'status' => false,
                'info'   => 'Teacher not found',
            ];
        }

        /* amount */
        $amount = doubleval($amount);
        $teacher->user_price_peer_hour;
        $cost = round($teacher->user_price_peer_hour*Yii::$app->params['exchange']['usd']['rur']['val'] - $teacher->user_price_peer_hour * Yii::$app->params['exchange']['usd']['rur']['val'] * $amount_data['discount_percent'] / 100, 2);
        $order_amount = round($cost * $amount_data['lessons_count'], 2);
//        if ($total_price !== $amount) {
//            return [
//                'status' => false,
//                'info'   => 'Wrong amount',
//            ];
//        }

        /**/
        Payments::deleteAll([
            'student_user_id' => $this->CurrentUser->user_id,
            'order_status'    => Payments::STATUS_UNPAYED,
            'order_type'      => Payments::TYPE_TINKOFF,
        ]);
        $order = new Payments();
        $order->order_type = Payments::TYPE_TINKOFF;
        $order->order_status = Payments::STATUS_UNPAYED;
        $order->order_count = $lessons_count;
        //$order->order_amount = round($order_amount * Yii::$app->params['exchange']['usd']['rur']['val'], 2);
        $order->order_amount = $order_amount;
        //$order->order_amount = 1;
        $order->order_description = Yii::t('models/Users', $description, [
            'teacher' => $teacher->_user_display_name,
            'teacher_user_id' => $teacher->user_id
        ]);
        $order->student_user_id = $this->CurrentUser->user_id;
        $order->teacher_user_id = $teacher->user_id;
        $order->order_additional_fields = json_encode([
            'student_user_id'     => $this->CurrentUser->user_id,
            'teacher_user_id'     => $teacher->user_id,
            'price_peer_hour'     => $teacher->user_price_peer_hour,
        ]);
        if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
            $order->order_ip = Yii::$app->request->getUserIP();
        }
        $order->is_read_by_admin = Payments::YES;
        $order->is_read_by_user = Payments::YES;
        if ($order->save()) {
            return [
                'status' => true,
                'data'   => [
                    'order_id' => $order->order_id,
                    'order_description' => $order->order_description,
                    'order_amount' => $order->order_amount,

                ],
            ];
        }

        /**/
        return [
            'status' => false,
            'info'   => $order->getErrors(),
        ];
    }

    /**
     * @param $teacher_id
     * @param $lesson_count
     * @param $timestamp_gmt
     * @return array|string
     */
    public function actionStartTinkoffPaymentFirstLessonXsmart($teacher_id, $lesson_count, $timestamp_gmt)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $lesson_count = intval($lesson_count);
        $teacher_id = intval($teacher_id);
        $timestamp_gmt = intval($timestamp_gmt);

        if ($lesson_count < 1) {
            return [
                'status' => false,
                'info'   => 'Wrong lesson count',
            ];
        }

        /** @var Users $Teacher */
        $Teacher = Users::find()
            ->where([
                'user_id' => $teacher_id,
                'user_type' => Users::TYPE_TEACHER,
            ])->one();
        if (!$Teacher) {
            return [
                'status' => false,
                'info'   => 'Teacher not found',
            ];
        }

        /**/
        $testSchedule = TeachersSchedule::find()->where([
            'teacher_user_id' => $Teacher->user_id,
            'week_day'        => Functions::getDayOfWeek($timestamp_gmt),
            'work_hour'       => date('H', $timestamp_gmt),
        ])->one();
        if (!$testSchedule) {
            return [
                'status' => false,
                'info'   => 'Teacher schedule mismatch',
            ];
        }

        /**/
        $testStudentAlreadyByLessonWithThisTeacher = StudentsTimeline::find()
            ->where('(student_user_id = :student_user_id) AND (teacher_user_id = :teacher_user_id)',[
                'student_user_id' => $this->CurrentUser->user_id,
                'teacher_user_id' => $teacher_id,
            ])
            ->one();
        if ($testStudentAlreadyByLessonWithThisTeacher) {
            return [
                'status' => false,
                'info'   => 'You are already buy first lesson with this teacher. Wait until lesson end. Also you could take lesson with another teacher too.',
            ];
        }


        /**/
//        $testStudentAlreadyByLesson = StudentsTimeline::find()
//            ->where('(student_user_id = :student_user_id) AND (timeline_timestamp > :now)',[
//                'student_user_id' => $this->CurrentUser->user_id,
//                'now' => time(),
//            ])
//            ->one();
        $testStudentAlreadyByLessonOnThisTime = StudentsTimeline::find()
            ->where('(student_user_id = :student_user_id) AND (timeline_timestamp = :timeline_timestamp)',[
                'student_user_id' => $this->CurrentUser->user_id,
                'timeline_timestamp' => $timestamp_gmt,
            ])
            ->one();
        if ($testStudentAlreadyByLessonOnThisTime) {
            return [
                'status' => false,
                'info'   => 'You are already buy first lesson on this time. Choose other time, please!',
            ];
        }

        /**/
        $testTeacherHaveOtherLessonOnThisTime = StudentsTimeline::find()->where([
            'teacher_user_id' => $Teacher->user_id,
            'timeline_timestamp' => $timestamp_gmt,
        ])->andWhere('student_user_id != :student_user_id', [
            'student_user_id' => $this->CurrentUser->user_id,
        ])->one();
        if ($testTeacherHaveOtherLessonOnThisTime) {
            return [
                'status' => false,
                'info'   => 'Teacher has lesson with other student at this time',
            ];
        }

        /**/
        Payments::deleteAll([
            'student_user_id' => $this->CurrentUser->user_id,
            'order_status'    => Payments::STATUS_UNPAYED,
            'order_type'      => Payments::TYPE_TINKOFF,
        ]);
        $order = new Payments();
        $order->order_type = Payments::TYPE_TINKOFF;
        $order->order_status = Payments::STATUS_UNPAYED;
        $order->order_count = 1;//$lesson_count;
        $order->order_amount = $Teacher->user_price_peer_hour * Yii::$app->params['exchange']['usd']['rur']['val'];
        $order->order_description = "For the fist lesson with teacher {$Teacher->user_first_name} (id= $Teacher->user_id) " . Yii::$app->request->get('description', '');
        $order->student_user_id = $this->CurrentUser->user_id;
        $order->teacher_user_id = $Teacher->user_id;
        $order->order_additional_fields = json_encode([
            'first_lesson_xsmart' => true,
            'timeline_timestamp'  => $timestamp_gmt,
            'student_user_id'     => $this->CurrentUser->user_id,
            'teacher_user_id'     => $Teacher->user_id,
            'price_peer_hour'     => $Teacher->user_price_peer_hour,
        ]);
        if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
            $order->order_ip = Yii::$app->request->getUserIP();
        }
        $order->is_read_by_admin = Payments::YES;
        $order->is_read_by_user = Payments::YES;
        if ($order->save()) {
            return [
                'status' => true,
                'data'   => [
                    'order_id' => $order->order_id,
                    'order_description' => $order->order_description,
                    'order_amount' => $order->order_amount,
                ],
            ];
        }

        /**/
        return [
            'status' => false,
            'info'   => $order->getErrors(),
        ];
    }


    // ----------------------------------------------------


















    /* =========================== Для тестирования тиньков =========================== */

    /**
     * @return mixed|string
     * @throws \yii\web\HttpException
     */
    public function actionTinkoffCancel()
    {
        return '';
        '{
            "Status":"CONFIRMED",
            "Token":"a10bebe710b4ae0d7e952903b09f096808490c72907cde03331087776041f88d",
            "OrderId":"547925",
            "TerminalKey":"1611832698743DEMO",
            "Success":true,
            "PaymentId":445787762,
            "ErrorCode":"0",
            "Amount":980600,
            "CardId":63490272,
            "Pan":"400000******0119",
            "ExpDate":"1122"
            }';
        $model = new TinkoffApi();
        $args = [
            'PaymentId' => '929085966',
            'Amount'    => 100,
            //'IP'        => '109.87.113.192',
        ];
        return $model->buildQuery('Cancel', $args);
    }

    /**
     * @return mixed|string
     * @throws \yii\web\HttpException
     */
    public function actionTinkoffInitWithReceipt()
    {
        return '';
        '
        {
        "Status":"CONFIRMED",
        "Token":"2810f2f062c992bc3389094bbd7bda3702c717f83c495f2fc8f26f4cc7992d60",
        "OrderId":"547844",
        "TerminalKey":"1611832698743DEMO",
        "Success":true,
        "PaymentId":445822312,
        "ErrorCode":"0",
        "Amount":520600,
        "CardId":63509222,
        "Pan":"400000******0101",
        "ExpDate":"1122"}
        ';
        $model = new TinkoffApi();
        $args = [
            'TerminalKey' => Yii::$app->params['tinkoff_terminal_key'],
            'Amount'      => 520600,
            'OrderId'     => '547848',
            //'PaymentId'   => 445822312,
            'Receipt'     => [
                'Email'    => 'student@smartsing.net',
                'Taxation' => 'osn',
                'Items' => [
                    [
                        'Name' => 'Name1',
                        'Quantity' => 1,
                        'Amount' => 520600,
                        'Price'  => 520600,
                        'PaymentMethod' => 'full_payment',
                        'Tax'  => 'none',
                    ],
                ],
            ],
            //'IP'        => '109.87.113.192',
        ];
        return $model->buildQuery('Init', $args);
    }



    /* =========================== OFF =========================== */

//    /**
//     * @param $current_timeline_id
//     * @param $teacher_schedule_id
//     * @param $is_replacing
//     * @param $new_timestamp_gmt_for_replacing
//     * @return string|array
//     */
//    public function actionMoveLesson($current_timeline_id, $teacher_schedule_id, $is_replacing, $new_timestamp_gmt_for_replacing)
//    {
//        // move-lesson
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $current_timeline_id = intval($current_timeline_id);
//        $teacher_schedule_id = intval($teacher_schedule_id);
//        $is_replacing = intval($is_replacing);
//        $new_timestamp_gmt_for_replacing = intval($new_timestamp_gmt_for_replacing);
//
//        /**/
//        if (!$this->CurrentUser->teacher_user_id) {
//            return [
//                'status' => false,
//                'info' => 'Teacher not set for this student',
//            ];
//        }
//
//        /**/
//        $StudentTimeline = StudentsTimeline::findOne([
//            'timeline_id' => $current_timeline_id,
//            'student_user_id' => $this->CurrentUser->user_id,
//            'teacher_user_id' => $this->CurrentUser->teacher_user_id,
//        ]);
//        if (!$StudentTimeline) {
//            return [
//                'status' => false,
//                'info' => "StudentsTimeline not found by current_timeline_id = {$current_timeline_id}",
//            ];
//        }
//
//        if ($StudentTimeline->is_replacing && !$is_replacing) {
//            return [
//                'status' => false,
//                'info' => "Something wrong with parameter is_replacing. It's must be 1 for this timeline_id",
//                'info_user' => "Это занятие является переносом, поэтому его нельзя вернуть в регулярные.",
//            ];
//        }
//
//        /**/
//        $TeacherSchedule = TeachersSchedule::find()
//            ->where([
//                'schedule_id' => $teacher_schedule_id,
//                'teacher_user_id' => $this->CurrentUser->teacher_user_id,
//            ])
//            ->andWhere('student_user_id IS NULL')
//            ->one();
//        /** @var \common\models\TeachersSchedule $TeacherSchedule */
//        if (!$TeacherSchedule) {
//            return [
//                'status' => false,
//                'info' => "TeachersSchedule not found by teacher_schedule_id = {$teacher_schedule_id}",
//            ];
//        }
//
//        return StudentsScheduleForm::moveLesson($this->CurrentUser, $StudentTimeline, $TeacherSchedule, $is_replacing, $new_timestamp_gmt_for_replacing);
//    }
//
//    /**
//     * @param $timeline_id
//     * @return string|array
//     */
//    public function actionGetAvailableDatesForChangingSchedule($timeline_id)
//    {
//        // get-available-dates-for-changing-schedule
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        /**/
//        if (!$this->CurrentUser->teacher_user_id) {
//            return [
//                'status' => false,
//                'info' => 'Teacher not set for this student',
//            ];
//        }
//
//        /**/
//        $CurrentTimeline = StudentsTimeline::findOne(['timeline_id' => $timeline_id]);
//        if (!$CurrentTimeline) {
//            return [
//                'status' => false,
//                'info' => 'StudentsTimeline not found',
//            ];
//        }
//
//        $ret = StudentsScheduleForm::getAvailableDatesForChangingSchedule($this->CurrentUser, $CurrentTimeline);
//
//        return [
//            'status' => true,
//            'data' => $ret,
//        ];
//    }
//
//    /**
//     * @return string
//     */
//    public function actionGetAvailableDatesForChangingLessonDate()
//    {
//        //get-available-dates-for-changing-lesson-date
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $model = new StudentsScheduleForm();
//        $model->teacher_user_id = $this->CurrentUser->teacher_user_id;
//        return $model->getAvailableDatesForChangingLessonDate();
//    }
//
//    /**
//     * @return string|Response
//     */
//    public function actionPaymentSuccess()
//    {
//        /**/
//        if ($this->CurrentUser->user_status == Users::STATUS_ACTIVE) {
//            //return $this->redirect(['schedule']);
//        }
//
//        /**/
//        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
//            //return $this->redirect(['set-schedule']);
//        }
//
//        /**/
//        Yii::$app->session->set('payment_success', true);
//
//        /**/
//        //$this->layout = 'member-after';
//        $this->layout = 'member-no-header-no-footer';
//
//        return $this->render('payment-success', [
//            'CurrentUser' => $this->CurrentUser,
//        ]);
//    }
//
//    /**
//     * @return string|Response
//     */
//    public function actionPaymentFail()
//    {
//        /**/
//        if ($this->CurrentUser->user_status == Users::STATUS_ACTIVE) {
//            return $this->redirect(['schedule']);
//        }
//
//        /**/
//        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
//            return $this->redirect(['set-schedule']);
//        }
//
//        /**/
//        Yii::$app->session->set('payment_success', false);
//
//        /**/
//        Yii::$app->session->setFlash('error', [
//            'message'   => Yii::t('controllers/student', 'payment_was_unsuccessful'),
//            'ttl'       => Yii::$app->params['FLASH_TIMEOUT'],
//            'showClose' => true,
//            'alert_id' => 'payment-error-alert',
//            'type' => 'error',
//            //'class' => 'alert-error',
//        ]);
//        return $this->redirect(['after-introduce']);
//    }
//
//    /**
//     * @param $lesson_count
//     * @return string|array
//     */
//    public function actionStartTinkoffPayment($lesson_count)
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        $lesson_count = intval($lesson_count);
//
//        if (!isset(Payments::$_AVAILABLE_AMOUNTS[$lesson_count])) {
//            return [
//                'status' => false,
//                'info'   => 'Ошибочное количество уроков, такой вариант недоступен.',
//            ];
//        }
//
//        $order = new Payments();
//        $order->order_type = Payments::TYPE_TINKOFF;
//        $order->order_status = Payments::STATUS_UNPAYED;
//        $order->order_count = $lesson_count;
//        $order->order_amount = Payments::$_AVAILABLE_AMOUNTS[$lesson_count]['rub'];
//        $order->order_description =
//            "{$lesson_count} урока SmartSing за " .
//            number_format(Payments::$_AVAILABLE_AMOUNTS[$lesson_count]['rub'], 0, '', ' ') .
//            " руб. для ученика {$this->CurrentUser->user_id}::{$this->CurrentUser->user_email}";
//        $order->student_user_id = $this->CurrentUser->user_id;
//        if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
//            $order->order_ip = Yii::$app->request->getUserIP();
//        }
//
//        if ($order->save()) {
//            return [
//                'status' => true,
//                'data'   => [
//                    'order_id' => $order->order_id,
//                    'order_description' => $order->order_description,
//                    'order_amount' => $order->order_amount,
//                ],
//            ];
//        }
//
//        return [
//            'status' => false,
//            'info'   => $order->getErrors(),
//        ];
//    }
//
//    /**
//     * @return string
//     */
//    public function actionSchedule()
//    {
//        /**/
//        if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
//            return $this->redirect(['student/set-schedule']);
//        }
//
//        /**/
//        if (!$this->CurrentUser->teacher_user_id && $this->CurrentUser->user_status == Users::STATUS_ACTIVE) {
//            $this->CurrentUser->user_status = Users::STATUS_AFTER_PAYMENT;
//            $this->CurrentUser->save();
//            Yii::$app->session->set('set_schedule_step', 2);
//            return $this->redirect(['student/set-schedule']);
//        }
//
//        /**/
//        $StudentsTimeline = StudentsScheduleForm::getStudentsTimelineWhichCanChange($this->CurrentUser->user_id);
//
//        /**/
//        if (!$StudentsTimeline && $this->CurrentUser->user_status == Users::STATUS_ACTIVE) {
//            $this->CurrentUser->user_status = Users::STATUS_AFTER_PAYMENT;
//            $this->CurrentUser->teacher_user_id = null;
//            $this->CurrentUser->save();
//            Yii::$app->session->set('set_schedule_step', 2);
//            return $this->redirect(['student/set-schedule']);
//        }
//
//        /**/
//        $model = new CommonScheduleForm();
//        $model->user_id = $this->CurrentUser->user_id;
//        $model->user_type = $this->CurrentUser->user_type;
//        $model->user_timezone = $this->CurrentUser->user_timezone;
//
//        return $this->render('schedule', [
//            'CurrentUser' => $this->CurrentUser,
//            'NextLesson'  => NextLessons::getStudentLesson($this->CurrentUser),
//            'DashboardSchedule' => $model->getScheduleForDashboard(),
//            'StudentsTimeline' => $StudentsTimeline,
//        ]);
//    }
//
//    /**
//     * @param string $action
//     * @return Response
//     */
//    public function actionUrlDispatcher($action)
//    {
//        switch ($action) {
//            case 'payment-success':
//                if ($this->CurrentUser->user_status == Users::STATUS_AFTER_PAYMENT) {
//                    return $this->redirect(['student/set-schedule']);
//                }
//                if ($this->CurrentUser->user_status == Users::STATUS_AFTER_INTRODUCE) {
//                    return $this->redirect(['student/after-introduce']);
//                }
//                return $this->redirect(['student/index']);
//                break;
//            default:
//                return $this->redirect(['student/index']);
//        }
//    }
//
//    /**
//     *  @return string
//     */
//    public function actionSetTeacherForStudent()
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $model = new StudentsScheduleForm();
//        if ($model->load(['StudentsScheduleForm' => [
//                'user_id'         => $this->CurrentUser->user_id,
//                'user_type'       => $this->CurrentUser->user_type,
//                'user_timezone'   => $this->CurrentUser->user_timezone,
//                'teacher_user_id' => Yii::$app->request->get('teacher_user_id'),
//                'date_start'      => Yii::$app->request->get('date_start', date(SQL_DATE_FORMAT)),
//            ]]) && $model->validate()) {
//
//            return $model->setTeacherForStudent();
//
//        } else {
//            return [
//                'status' => false,
//                'data'   => $model->getErrors(),
//            ];
//        }
//    }
//
//    /**
//     *  @return string
//     */
//    public function actionUnsetTeacherForStudent()
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $model = new StudentsScheduleForm();
//        if ($model->load(['StudentsScheduleForm' => [
//                'user_id'         => $this->CurrentUser->user_id,
//                'user_type'       => $this->CurrentUser->user_type,
//                'user_timezone'   => $this->CurrentUser->user_timezone,
//            ]]) && $model->validate()) {
//
//            return $model->unsetTeacherForStudent();
//
//        } else {
//            return [
//                'status' => false,
//                'data'   => $model->getErrors(),
//            ];
//        }
//    }
//
//    /**
//     * @return string
//     */
//    public function actionFindAvailableTeachers()
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $model = new StudentsScheduleForm();
//        if ($model->load(['StudentsScheduleForm' => [
//                'user_id'       => $this->CurrentUser->user_id,
//                'user_type'     => $this->CurrentUser->user_type,
//                'user_timezone' => $this->CurrentUser->user_timezone,
//                'date_start'    => Yii::$app->request->get('date_start', date(SQL_DATE_FORMAT, time() + $this->CurrentUser->user_timezone)),
//            ]]) && $model->validate()) {
//
//
//            return $model->findAvailableTeachers();
//
//        } else {
//            return [
//                'status' => false,
//                'data'   => $model->getErrors(),
//            ];
//        }
//    }
}
