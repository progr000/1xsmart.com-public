<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%leads}}".
 *
 * @property int $lead_id
 * @property string $lead_created
 * @property string $lead_updated
 * @property string $lead_name
 * @property string $lead_email
 * @property string $lead_phone
 * @property string|null $lead_photo
 * @property string|null $lead_info
 * @property int|null $operator_user_id
 * @property string|null $operator_notice
 * @property string|null $lead_in_work
 * @property int|null $admin_user_id
 * @property string|null $admin_notice
 * @property int $user_type
 * @property int $lead_status
 * @property string|null $additional_service_info
 * @property string|null $additional_service_notice
 *
 * @property Users $operatorUser
 */
class Leads extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_IN_PROCESSING = 3;
    const STATUS_REJECTED = 2;

    const STATUS_FORM_FILL = 3;
    const STATUS_CONTACT_US = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%leads}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'lead_created',
                'updatedAtAttribute' => 'lead_updated',
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
            [['lead_name', 'lead_email', 'lead_phone'], 'required'],
            [['lead_created', 'lead_updated', 'lead_in_work'], 'validateDateField', 'skipOnEmpty' => true],
            [['lead_created', 'lead_updated', 'lead_in_work'], 'safe'],
            [[
                'lead_name',
                'lead_email',
                'lead_info',
                'operator_notice',
                'admin_notice',
                'additional_service_info',
                'additional_service_notice'
            ], 'string'],
            [['operator_user_id', 'admin_user_id'], 'default', 'value' => null],
            [['operator_user_id', 'admin_user_id'], 'integer'],
            ['lead_status', 'integer'],
            ['lead_status', 'in', 'range' => [
                self::STATUS_NEW,
                self::STATUS_CONFIRMED,
                self::STATUS_REJECTED,
                self::STATUS_IN_PROCESSING,
                self::STATUS_FORM_FILL,
                self::STATUS_CONTACT_US,
            ]],
            ['lead_status', 'default', 'value' => self::STATUS_NEW],
            ['user_type', 'integer'],
            ['user_type', 'in', 'range' => [Users::TYPE_STUDENT, Users::TYPE_TEACHER]],
            ['user_type', 'default', 'value' => Users::TYPE_STUDENT],
            //[['lead_phone'], 'string', 'max' => 50],
            //[['lead_email'], 'email'],
            [['lead_photo'], 'string', 'max' => 255],
            [['operator_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['operator_user_id' => 'user_id']],
            [['admin_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['admin_user_id' => 'user_id']],
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
            'lead_id' => 'Lead ID',
            'lead_created' => 'Lead Created',
            'lead_updated' => 'Lead Updated',
            'lead_name' => 'Lead Name',
            'lead_email' => 'Lead Email',
            'lead_phone' => 'Lead Phone',
            'lead_photo' => 'Lead Photo',
            'lead_info' => 'Lead Info',
            'operator_user_id' => 'Operator User ID',
            'operator_notice' => 'Operator Notice',
            'admin_user_id' => 'Admin User ID',
            'admin_notice' => 'Admin Notice',
            'lead_in_work' => 'Lead In Work',
        ];
    }

    /**
     * Gets query for [[OperatorUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperatorUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'operator_user_id'])->one();
    }

    /**
     * Gets query for [[OperatorUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'admin_user_id']);
    }
}
