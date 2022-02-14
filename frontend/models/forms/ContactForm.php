<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use common\models\Leads;
use common\models\Users;
use common\models\Chat;
use common\helpers\Functions;
use common\models\MailTemplate;

/**
 * ContactForm is the model behind the contact form.
 *
 * @property string $request_text
 *
 */
class ContactForm extends Model
{
    /**/
    public $_required = [];
    public $_validate_pattern = true;

    /**/
    public $request_type;
    public $request_name;
    public $request_fio;
    public $request_phone;
    public $request_email;
    public $request_text;

    /**/
    const PHONE_PATTERN = "/^(([0-9]{2}|\+[0-9]{2})[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/";
    const FIO_PATTERN = "/^[А-ЯA-Z][а-яa-z\-]+\s[А-ЯA-Z][а-яa-z\-]+(\s[А-ЯA-Z][а-яa-z\-]+)?$/";
    const NAME_PATTERN = "/^[А-ЯA-Z][а-яa-z\-]{2,100}$/";

    /**
     * PurchaseForm constructor.
     * @param array $required
     * @param array $config
     */
    public function __construct(array $required=array(), array $config=array())
    {
        //setlocale(LC_ALL, "ru_RU.UTF-8");
        parent::__construct($config);
        if ($required && sizeof($required)) {
            $this->_required = [$required, 'required'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            //[['request_name', 'request_phone', 'request_text'], 'required'],
            //[['request_name', 'request_email', 'request_text'], 'required'],
            [['request_name', 'request_phone', 'request_email'], 'filter', 'filter' => 'trim'],
            ['request_email', 'email'],
            ['request_text', 'string'],
            ['request_text', 'safe'],
            ['request_text', 'filter', 'filter' => function($value){return strip_tags($value);}],
        ];

        if ($this->_validate_pattern) {
            $rules[] = ['request_name', 'match','pattern' => self::NAME_PATTERN, /*'message' => 'Wrong name format'*/];
            $rules[] = ['request_phone', 'match','pattern' => self::PHONE_PATTERN,/* 'message' => 'Неверный формат номера'*/];
        }

        if (sizeof($this->_required)) {
            $rules[] = $this->_required;
        }

        return $rules;
    }

    /**
     * @param Users|null $CurrentUser
     * @return bool
     */
    public function saveRequest($CurrentUser = null)
    {
        /* запись в чат с админом если юзер залогинен */
        if ($CurrentUser) {
            $supportUser = Users::find()
                ->alias('t1')
                ->innerJoin('{{%chat}} as t2', 't1.user_id = t2.receiver_user_id')
                ->where([
                    't1.user_type' => Users::TYPE_ADMIN,
                    't2.sender_user_id' => $CurrentUser->user_id,
                ])
                ->one();
            if (!$supportUser) {
                $supportUser = Users::find()
                    ->where(['user_type' => Users::TYPE_ADMIN])
                    ->orderBy(new Expression('random()'))
                    ->one();
            }
            //var_dump($supportUser);exit;
            /** @var $supportUser \common\models\Users */
            if ($supportUser) {
                $Chat = new Chat();
                $Chat->msg_unread = Chat::YES;
                $Chat->receiver_user_id = $supportUser->user_id;
                $Chat->sender_user_id = $CurrentUser->user_id;
                $Chat->msg_text = $this->request_text;
                $Chat->save();
                //var_dump($Chat->getErrors());exit;
            }
        }

        /* запись в бд Leads если это форма с главной страницы (определяем по наличию поля request_phone)
         * изменение - пишем в бд с любой формы, но если есть поле request_phone то статус Leads::STATUS_NEW
         * иначе Leads::STATUS_CONTACT_US. При выборке в админку можно выбирать только со статусом STATUS_NEW
         * и после прочтения ставить статус STATUS_FORM_FILL.
         * Так не потеряем ни одного запроса юзера но сможем разделить для отображения
         * */
        //if ($this->request_phone) {
            $lead = new Leads();
            $lead->lead_name  = $this->request_name;
            $lead->lead_email = ($this->request_email ? $this->request_email : 'empty');
            $lead->lead_phone = ($this->request_phone ? $this->request_phone : 'empty');
            $lead->lead_info  = $this->request_text;
            $lead->user_type  = ($CurrentUser && $CurrentUser->user_type == Users::TYPE_TEACHER)
                ? Users::TYPE_TEACHER
                : Users::TYPE_STUDENT;
            $lead->additional_service_info = Functions::getAdditionalServiceInfo();
            $lead->lead_status = ($this->request_phone)
                ? Leads::STATUS_NEW
                : Leads::STATUS_CONTACT_US;
            $lead->save();
        //}

        /* Отправка емейла */
        $message =
            "Date: " . date('d-m-Y H:i:s') . " <br />\n" .
            "An request from a " . ($this->request_email ? "-=Contact Us form=-" : "-=Fill the form=-") . ". <br />\n" .
            ($this->request_email ? "Customer email: {$this->request_email} <br />\n" : "") .
            ($this->request_phone ? "Customer phone: {$this->request_phone} <br />\n" : "") .
            "Customer name: {$this->request_name} <br />\n" .
            "Request text: <br />\n" .
            "============================================ <br />\n" .
            $this->request_text . "<br />\n" .
            "============================================ <br />\n";

        $additional_service_info = Functions::getAdditionalServiceInfo();
        $message .= $additional_service_info;

        /**/
        return MailTemplate::send([
            'language' => 'en',
            'to_email' => Yii::$app->params['supportEmail'],
            'to_name'  => '1xsmart',
            'subject'  => 'Request from Contact Us form',
            'body'     => $message,
            'User'     => $CurrentUser,
        ]);
    }

}
