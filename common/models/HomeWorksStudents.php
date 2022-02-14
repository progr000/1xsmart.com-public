<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%homeworks_students}}".
 *
 * @property int $work_id
 * @property int $student_user_id
 * @property string $hws_appointed
 * @property string|null $hws_passed
 * @property int $hws_status
 * @property string $hws_hash
 * @property int $notes_played
 * @property int $notes_hit
 * @property int $notes_close
 * @property string|null $notes_lowest
 * @property string|null $notes_highest
 *
 * @property Homeworks $work
 * @property Users $studentUser
 */
class HomeWorksStudents extends ActiveRecord
{
    const STATUS_APPOINTED = 0;
    const STATUS_PASSED = 1;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PASSED    => 'passed',
            self::STATUS_APPOINTED => 'appointed',
        ];
    }

    /**
     * @param $hws_status
     * @return mixed
     */
    public static function getStatus($hws_status)
    {
        return self::getStatuses()[$hws_status];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%homeworks_students}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'hws_appointed',
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
            [['work_id', 'student_user_id', 'hws_hash'], 'required'],
            //[['work_id', 'student_user_id', 'hws_status', 'notes_played', 'notes_hit', 'notes_close', 'notes_lowest', 'notes_highest'], 'default', 'value' => null],
            [['work_id', 'student_user_id', 'hws_status'], 'integer'],
            [['hws_status'], 'in', 'range' => [self::STATUS_APPOINTED, self::STATUS_PASSED]],
            [['notes_played', 'notes_hit', 'notes_close'], 'integer', 'min' => 0],
            [['notes_lowest', 'notes_highest'], 'string', 'max' => 3],
            [['hws_appointed', 'hws_passed'], 'validateDateField', 'skipOnEmpty' => true],
            [['hws_appointed', 'hws_passed'], 'safe'],
            [['hws_hash'], 'string', 'max' => 32],
            [['work_id', 'student_user_id'], 'unique', 'targetAttribute' => ['work_id', 'student_user_id']],
            [['work_id'], 'exist', 'skipOnError' => true, 'targetClass' => Homeworks::className(), 'targetAttribute' => ['work_id' => 'work_id']],
            [['student_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['student_user_id' => 'user_id']],
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
            'work_id' => 'Work ID',
            'student_user_id' => 'Student User ID',
            'hws_appointed' => 'Hws Appointed',
            'hws_passed' => 'Hws Passed',
            'hws_status' => 'Hws Status',
            'hws_hash' => 'Hws Hash',
            'notes_played' => 'Notes Played',
            'notes_hit' => 'Notes Hit',
            'notes_close' => 'Notes Close',
            'notes_lowest' => 'Notes Lowest',
            'notes_highest' => 'Notes Highest',
        ];
    }

    /**
     * Gets query for [[Work]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWork()
    {
        return $this->hasOne(Homeworks::className(), ['work_id' => 'work_id']);
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
