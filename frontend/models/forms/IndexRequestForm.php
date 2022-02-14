<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\helpers\FileSys;
use common\helpers\Functions;
use common\models\MailTemplate;
use common\models\Leads;
use common\models\Users;

/**
 * Signup form
 *
 * @property string $request_type
 * @property string $request_name
 * @property string $request_fio
 * @property string $request_phone
 * @property string $request_email
 *
 */
class IndexRequestForm extends Model
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

    const TYPE_STUDENT = 'student';
    const TYPE_TEACHER = 'teacher';

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
        setlocale(LC_ALL, "ru_RU.UTF-8");
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
            [['request_phone', 'request_email', 'request_type'], 'required'],
            ['request_type', 'in', 'range' => [self::TYPE_STUDENT, self::TYPE_TEACHER]],
            [['request_name', 'request_fio', 'request_phone', 'request_email'], 'filter', 'filter' => 'trim'],
            //['request_name', 'string', 'min' => 2, 'max' => 100],
            ['request_fio', 'string', 'max' => 255],
            ['request_email', 'email'],
            ['request_phone', 'match','pattern' => self::PHONE_PATTERN, 'message' => 'Неверный формат номера'],
            //['request_email', 'unique', 'targetClass' => '\common\models\Users', 'message' => 'email already taken'],
        ];

        if ($this->_validate_pattern) {
            $rules[] = ['request_name', 'match','pattern' => self::NAME_PATTERN, 'message' => 'Неверный формат имени'];
            $rules[] = ['request_fio', 'match','pattern' => self::FIO_PATTERN, 'message' => 'Неверный формат ФИО'];
        }

        if (sizeof($this->_required)) {
            $rules[] = $this->_required;
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'request_phone' => 'Phone',
            'request_email' => 'Email',
            'request_name'  => 'Name',
            'request_fio'   => 'FIO',
        ];
    }

}
