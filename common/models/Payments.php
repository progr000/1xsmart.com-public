<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property int $order_id
 * @property string $order_created
 * @property string $order_updated
 * @property float $order_amount
 * @property float $order_amount_usd
 * @property int $order_count
 * @property string|null $order_description
 * @property int $order_status
 * @property string|null $order_type
 * @property string|null $order_additional_fields
 * @property int|null $student_user_id
 * @property int|null $teacher_user_id
 * @property string $order_ip
 * @property int $is_read_by_user
 * @property int $is_read_by_admin
 *
 * @property Users $studentUser
 */
class Payments extends ActiveRecord
{
    const TYPE_TINKOFF = 'tinkoff';

    const STATUS_UNPAYED  = 'unpayed'; // new || AUTHORIZED || REJECTED
    const STATUS_PAYED    = 'payed'; // CONFIRMED
    const STATUS_CANCELED = 'canceled';

    const YES = 1;
    const NO = 0;

    /*
    public static $_AVAILABLE_AMOUNTS = [
        4  => ['rub' => 5206,  'rub_for_one' => 1370, 'discount' => '5%',  'rub_no_discount' => 5480],
        8  => ['rub' => 9806,  'rub_for_one' => 1225, 'discount' => '15%', 'rub_no_discount' => 11536],
        16 => ['rub' => 17292, 'rub_for_one' => 1080, 'discount' => '25%', 'rub_no_discount' => 23040],
        32 => ['rub' => 31328, 'rub_for_one' => 979,  'discount' => '32%', 'rub_no_discount' => 46080],
        64 => ['rub' => 56192, 'rub_for_one' => 878,  'discount' => '39%', 'rub_no_discount' => 92060],
    ];
    */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'order_created',
                'updatedAtAttribute' => 'order_updated',
                'value' => function() { return date(SQL_DATE_FORMAT); }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_type', 'order_status'], 'required'],

            [['order_created', 'order_updated'], 'validateDateField', 'skipOnEmpty' => true],
            [['order_created', 'order_updated'], 'safe'],

            [['order_amount', 'order_amount_usd'], 'number'],
            [['order_count', 'student_user_id'], 'default', 'value' => null],
            [['order_count', 'student_user_id'], 'integer'],
            [['order_description', 'order_additional_fields'], 'string'],

            [['order_status'], 'string', 'max' => 30],
            [['order_status'], 'in', 'range' => [self::STATUS_CANCELED, self::STATUS_PAYED, self::STATUS_UNPAYED]],
            //[['order_status'], 'default', 'value' => self::STATUS_UNPAYED],

            [['is_read_by_user', 'is_read_by_admin'], 'in', 'range' => [self::YES, self::NO]],
            [['is_read_by_user', 'is_read_by_admin'], 'default', 'value' => self::NO],

            [['order_type'], 'string', 'max' => 30],
            [['order_type'], 'in', 'range' => [self::TYPE_TINKOFF]],
            //[['order_type'], 'default', 'value' => self::TYPE_TINKOFF],

            [['order_ip'], 'string', 'max' => 30],

            [['student_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['student_user_id' => 'user_id']],
            [['teacher_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['teacher_user_id' => 'user_id']],
        ];
    }

    /**
     * @param $attribute
     */
    public function validateDateField($attribute/*, $params*/)
    {
        $check = Functions::checkDateIsValidForDB($this->$attribute);
        if (!$check) {
            $this->addError($attribute, 'Invalid date format');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_created' => 'Order Created',
            'order_updated' => 'Order Updated',
            'order_amount' => 'Order Amount',
            'order_count' => 'Order Count',
            'order_description' => 'Order Description',
            'order_status' => 'Order Status',
            'order_type' => 'Order Type',
            'order_additional_fields' => 'Order Additional Fields',
            'student_user_id' => 'Student User ID',
        ];
    }

    /**
     * Gets query for [[StudentUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'student_user_id']);
    }
}
