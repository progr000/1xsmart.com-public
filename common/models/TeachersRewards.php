<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%teachers_rewards}}".
 *
 * @property int $rw_id
 * @property string $rw_created
 * @property int|null $rw_status
 * @property float $rw_amount_usd
 * @property string|null $rw_description
 * @property int $teacher_user_id
 *
 * @property Users $teacherUser
 */
class TeachersRewards extends ActiveRecord
{
    const STATUS_PAYED = 1;
    const STATUS_AWAIT = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%teachers_rewards}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'rw_created',
                'updatedAtAttribute' => null,
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
            [['teacher_user_id', 'rw_amount_usd', 'rw_status'], 'required'],
            [['rw_created'], 'validateDateField', 'skipOnEmpty' => true],
            [['rw_created'], 'safe'],
            [['rw_status', 'teacher_user_id'], 'default', 'value' => null],
            [['rw_status', 'teacher_user_id'], 'integer'],
            [['rw_amount_usd'], 'number'],
            [['rw_description'], 'string'],
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
            'rw_id' => 'Rw ID',
            'rw_created' => 'Rw Created',
            'rw_status' => 'Rw Status',
            'rw_amount_usd' => 'Rw Amount Usd',
            'rw_description' => 'Rw Description',
            'teacher_user_id' => 'Teacher User ID',
        ];
    }

    /**
     * Gets query for [[TeacherUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'teacher_user_id']);
    }
}
