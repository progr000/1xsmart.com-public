<?php
namespace frontend\modules\tinkoff\models;

use Yii;
use yii\base\Model;
use yii\web\HttpException;
use WebSocket;
use common\helpers\Functions;
use common\helpers\FileSys;
use common\models\Payments;
use common\models\Users;
use common\models\Chat;
use common\models\MailTemplate;
use common\models\StudentsTimeline;

/**
 *
 * @property string $Status
 * @property string $TerminalKey
 * @property string $OrderId
 * @property boolean $Success
 * @property int $PaymentId
 * @property string $ErrorCode
 * @property int $Amount
 * @property int $CardId
 * @property string $Pan
 * @property string $ExpDate
 * @property string $Token
 *
 * @property array $rawRequest
 *
 * @property string $secretKey
 *
 */
class TinkoffApi extends Model
{
    const LOG_DIR          = __DIR__ . '/../logs/';
    const LOG_FILE         = __DIR__ . '/../logs/tinkoff-log.log';
    const LOG_ERROR_FILE   = __DIR__ . '/../logs/tinkoff-error.log';
    const LOG_REQUEST_FILE = __DIR__ . '/../logs/tinkoff-requests.log';

    const STATUS_NEW        = 'NEW';
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_CONFIRMED  = 'CONFIRMED';
    const STATUS_REJECTED   = 'REJECTED';
    const STATUS_CANCELED   = 'CANCELED';
    const STATUS_REVERSED   = 'REVERSED';
    const STATUS_REFUNDED   = 'REFUNDED';
    const STATUS_PARTIAL_REVERSED = 'PARTIAL_REVERSED';
    const STATUS_PARTIAL_REFUNDED = 'PARTIAL_REFUNDED';

    private $api_url;
    private $secretKey;

    public $Status;
    public $TerminalKey;
    public $OrderId;
    public $Success;
    public $PaymentId;
    public $ErrorCode;
    public $Amount;
    public $CardId;
    public $Pan;
    public $ExpDate;
    public $Token;

    public $rawRequest;

    public function __construct($config=[])
    {
        parent::__construct($config);
        $this->api_url = 'https://securepay.tinkoff.ru/v2/';
        $this->TerminalKey = Yii::$app->params['tinkoff_terminal_key'];
        $this->secretKey = Yii::$app->params['tinkoff_terminal_pass'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Status', 'Token', 'OrderId'], 'required'],
            ['Status', 'string', 'max' => 32],
            ['TerminalKey', 'string', 'max' => 32],
            ['OrderId', 'string', 'max' => 50],
            ['Success', 'boolean'],
            ['PaymentId', 'integer'],
            ['ErrorCode', 'string', 'max' => 20],
            ['Amount', 'integer'],
            ['CardId', 'integer'],
            ['Pan', 'string', 'max' => 32],
            ['ExpDate', 'string', 'max' => 10],
            ['Token', 'string', 'max' => 128],
        ];
    }

    /**
     * Generates Token
     *
     * @param $args
     * @return string
     */
    private function _genToken($args)
    {
        $token = '';
        $args['Password'] = $this->secretKey;
        ksort($args);

        foreach ($args as $key=>$arg) {
            if (in_array($key, ['Receipt', 'DATA'])) { continue; }
            if (!is_array($arg)) {
                $token .= $arg;
            }
        }
        $token = hash('sha256', $token);

        return $token;
    }

    /**
     * Combines parts of URL. Simply gets all parameters and puts '/' between
     *
     * @return string
     */
    private function _combineUrl()
    {
        $args = func_get_args();
        $url = '';
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if ($arg[strlen($arg) - 1] !== '/') $arg .= '/';
                $url .= $arg;
            } else {
                continue;
            }
        }

        return $url;
    }

    /**
     * @param $api_url
     * @param $args
     * @return bool|string
     * @throws HttpException
     */
    private function _sendRequest($api_url, $args)
    {
        if (is_array($args)) {
            $args = json_encode($args);
        }

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));

            $out = curl_exec($curl);
            curl_close($curl);

            return $out;

        } else {
            throw new HttpException('Can not create connection to ' . $api_url . ' with args ' . $args, 404);
        }
    }

    /**
     * Builds a query string and call sendRequest method.
     * Could be used to custom API call method.
     *
     * @param string $path API method name
     * @param mixed $args query params
     *
     * @return mixed
     * @throws HttpException
     */
    public function buildQuery($path, $args)
    {
        $url = $this->api_url;
        if (is_array($args)) {
            if (!array_key_exists('TerminalKey', $args)) {
                $args['TerminalKey'] = $this->TerminalKey;
            }
            if (!array_key_exists('Token', $args)) {
                $args['Token'] = $this->_genToken($args);
            }
        }
        $url = $this->_combineUrl($url, $path);


        return $this->_sendRequest($url, $args);
    }

    /**
     *
     */
    public function orderProcessing()
    {
        /** @var Payments $order */
        $order = Payments::findOne(['order_id' => $this->OrderId]);

        /**/
        $token = $this->_genToken($this->rawRequest);

        /**/
        if (!$order) {
            return [
                'status' => false,
                'info'   => 'Error::Order not found',
            ];
        }

        /**/
        $order->order_amount = $this->Amount/100;
        $order->order_amount_usd = round($order->order_amount / Yii::$app->params['exchange']['usd']['rur']['val'], 2);

        if ($this->Status == self::STATUS_CONFIRMED) {
            $order->order_status = Payments::STATUS_PAYED;
        }
        if ($this->Status == self::STATUS_CANCELED) {
            $order->order_status = Payments::STATUS_CANCELED;
        }

        $order->is_read_by_user = Payments::NO;
        $order->is_read_by_admin = Payments::NO;

        $order->order_type = Payments::TYPE_TINKOFF;
        $order_additional_fields = json_decode($order->order_additional_fields, true);

        //var_dump($order_additional_fields); exit;
        FileSys::fwrite(self::LOG_DIR . 'test.log', "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($order_additional_fields, true) . "\n\n\n", 0666, 'a');

        $order_additional_fields_received = [
            'Status' => $this->Status,
            'Token' => $this->Token,
            'OrderId' => $this->OrderId,
            'TerminalKey' => $this->TerminalKey,
            'Success' => $this->Success,
            'PaymentId' => $this->PaymentId,
            'ErrorCode' => $this->ErrorCode,
            'Amount' => $this->Amount,
            'CardId' => $this->CardId,
            'Pan' => $this->Pan,
            'ExpDate' => $this->ExpDate,
        ];
        if (is_array($order_additional_fields)) {
            $order_additional_fields = $order_additional_fields + $order_additional_fields_received;
        } else {
            $order_additional_fields = $order_additional_fields_received;
        }
        $order->order_additional_fields = json_encode($order_additional_fields);

        /**/
        if ($order->order_status == Payments::STATUS_PAYED) {

            /**/
            $User = Users::findById($order->student_user_id);
            if ($User) {
                if (!isset($order_additional_fields['first_lesson_xsmart'])) {
                    $User->user_status == Users::STATUS_ACTIVE
                        ? $User->user_status = Users::STATUS_ACTIVE
                        : $User->user_status = Users::STATUS_AFTER_PAYMENT;
                }
                $User->user_balance += $order->order_amount_usd;
                $User->user_lessons_available += $order->order_count;
                $User->user_last_pay = date(SQL_DATE_FORMAT);
                //if (isset($order_additional_fields['teacher_user_id'])) {
                if (isset($order->teacher_user_id) && $order->teacher_user_id) {
                    //$User->teacher_user_id = $order_additional_fields['teacher_user_id'];
                    $User->teacher_user_id = $order->teacher_user_id;
                }
                $User->after_payment_action = Users::AFTER_PACKAGE_ACTION;
                $User->save();

                //$User->getAssignedLessons();
                //$order->lessons_remaining = $User->user_lessons_available + $User->_user_lessons_assigned;
            }

            /**/
            if (isset($order_additional_fields['first_lesson_xsmart'],
                $order_additional_fields['timeline_timestamp'],
                $order_additional_fields['student_user_id'],
                $order_additional_fields['teacher_user_id'])) {

                if (!in_array($User->user_status, [Users::STATUS_ACTIVE, Users::STATUS_AFTER_PAYMENT, Users::STATUS_AFTER_INTRODUCE])) {
                    $User->user_status = Users::STATUS_BEFORE_INTRODUCE;
                }
                $User->after_payment_action = Users::AFTER_INTRO_ACTION;

                //$User->teacher_user_id = $order_additional_fields['teacher_user_id'];
                $User->teacher_user_id = $order->teacher_user_id;
                if ($User->user_lessons_available > 0) {
                    $User->user_lessons_available -= 1; //сразу снимаем один урок, т.к. он переходит в $StudentTimeline прямо сейчас
                }
                $User->save();

                $StudentTimeline = new StudentsTimeline();
                $StudentTimeline->schedule_id = null;
                $StudentTimeline->student_user_id = $order->student_user_id; //$order_additional_fields['student_user_id'];
                $StudentTimeline->week_day = Functions::getDayOfWeek($order_additional_fields['timeline_timestamp']);
                $StudentTimeline->work_hour = date('H', $order_additional_fields['timeline_timestamp']);
                $StudentTimeline->timeline = date(SQL_DATE_FORMAT, $order_additional_fields['timeline_timestamp']);
                $StudentTimeline->is_replacing = StudentsTimeline::NO;
                $StudentTimeline->is_introduce_lesson = StudentsTimeline::YES;
                //$StudentTimeline->teacher_user_id = $order_additional_fields['teacher_user_id'];
                $StudentTimeline->teacher_user_id = $order->teacher_user_id;
                $StudentTimeline->timeline_timestamp = $order_additional_fields['timeline_timestamp'];
                $StudentTimeline->room_hash = md5(uniqid());
                $StudentTimeline->replacing_for_timeline_timestamp = $order_additional_fields['timeline_timestamp'];
                $StudentTimeline->lesson_amount_usd = $order->order_amount_usd;
                if (!$StudentTimeline->save()) {
                    FileSys::fwrite(self::LOG_DIR . 'test.log', "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($StudentTimeline->getErrors(), true) . "\n\n\n", 0666, 'a');
                }
            }

            /** Отправка емейлов и создание чата */
            if ($User) {

                /* чат */
                Chat::initChatBetweenUsers(
                    $order->teacher_user_id,
                    $order->student_user_id,
                    "Payment for lesson(s) with this teacher was successful. It's a system message, no need answer."
                );

                /* нотификация о платеже */
                $client = new WebSocket\Client(Yii::$app->params['wss_host'] . "/chat");
                $client->text('{"notification": {"user_id": ' . $order->student_user_id . '}}');
                //echo $client->receive();
                $client->close();

                /* емейлы */
                $Teacher = $User->getTeacherForThisUser();
                if (isset($StudentTimeline) && $Teacher) {
                    /* Отправка емейлов о первом уроке */

                    /* --- студенту */
                    if ($User->receive_system_notif) {
                        MailTemplate::send([
                            'language' => $User->last_system_language,
                            'to_email' => $User->user_email,
                            'to_name' => $User->user_first_name,
                            'composeTemplate' => 'payFirstLessonStudent',
                            'composeData' => [
                                'student_name' => $User->user_first_name,
                                'teacher_display_name' => $Teacher->_user_display_name,
                                'order_amount_usd' => $order->order_amount_usd,
                                'lesson_date' => $User->getDateInUserTimezoneByTimestamp($StudentTimeline->timeline_timestamp, Yii::$app->params['datetime_short_format'], true),
                                '_user_timezone_short_name' => $User->_user_timezone_short_name,
                            ],
                            'composeLinks' => [
                                'link_to_main_member' => ['student/'],
                                'link_to_the_lesson'  => ['user/educational-class-room', 'room' => $StudentTimeline->room_hash],
                            ],
                            'User' => $User,
                        ]);
                    }

                    /* --- учителю */
                    if ($Teacher->receive_system_notif) {
                        MailTemplate::send([
                            'language' => $Teacher->last_system_language,
                            'to_email' => $Teacher->user_email,
                            'to_name' => $Teacher->user_first_name,
                            'composeTemplate' => 'payFirstLessonTeacher',
                            'composeData' => [
                                'student_name' => $User->user_first_name,
                                'teacher_display_name' => $Teacher->_user_display_name,
                                'lesson_date' => $Teacher->getDateInUserTimezoneByTimestamp($StudentTimeline->timeline_timestamp, Yii::$app->params['datetime_short_format'], true),
                                '_user_timezone_short_name' => $Teacher->_user_timezone_short_name,
                            ],
                            'composeLinks' => [
                                'link_to_main_member' => ['teacher/'],
                                'link_to_the_lesson'  => ['user/educational-class-room', 'room' => $StudentTimeline->room_hash],
                            ],
                            'User' => $Teacher,
                        ]);
                    }

                } elseif ($Teacher) {
                    /* Отправка емейлов о пакете уроков */

                    /* --- студенту */
                    if ($User->receive_system_notif) {
                        MailTemplate::send([
                            'language' => $User->last_system_language,
                            'to_email' => $User->user_email,
                            'to_name' => $User->user_first_name,
                            'composeTemplate' => 'payPackageLessonsStudent',
                            'composeData' => [
                                'student_name' => $User->user_first_name,
                                'teacher_display_name' => $Teacher->_user_display_name,
                                'order_amount_usd' => $order->order_amount_usd,
                                'lesson_count' => $order->order_count,
                            ],
                            'composeLinks' => [
                                'link_to_main_member'  => ['student/'],
                                'link_to_set_schedule' => ['student/set-schedule'],
                            ],
                            'User' => $User,
                        ]);
                    }

                }
            }

        }

        /**/
        //StudentsScheduleForm::updateStudentsTimelineAfterPayOrByCron($User);

        /**/
        if (!$order->save()) {
            FileSys::fwrite(TinkoffApi::LOG_ERROR_FILE, "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($order->getErrors(), true) . "\n\n\n", 0666, 'a');
            return [
                'status' => false,
                'info'   => $order->getErrors(),
            ];
        }

        return [
            'status' => true,
        ];
    }
}
