<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%methodist_timeline}}".
 *
 * @property int $timeline_id
 * @property int $schedule_id
 * @property int $methodist_user_id
 * @property int $week_day
 * @property int $work_hour
 * @property string $timeline
 * @property int $timeline_timestamp
 * @property int|null $student_user_id
 * @property string|null $room_hash
 * @property int $lesson_status
 * @property string|null $lesson_notice
 *
 * @property MethodistSchedule $schedule
 * @property Users $methodistUser
 * @property Users $studentUser
 */
class MethodistTimeline extends ActiveRecord
{
    const STATUS_AWAIT = 0;
    const STATUS_PASSED = 1;
    const STATUS_FAILED = 2;

    public static $_lesson_statuses = [
        self::STATUS_AWAIT   => 'Ожидание',
        self::STATUS_PASSED  => 'Состоялся',
        self::STATUS_FAILED  => 'Не состоялся',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%methodist_timeline}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['schedule_id', 'methodist_user_id', 'week_day', 'work_hour', 'timeline', 'timeline_timestamp'], 'required'],
            //[['schedule_id', 'methodist_user_id', 'week_day', 'work_hour', 'timeline_timestamp', 'student_user_id'], 'default', 'value' => null],
            [['schedule_id', 'methodist_user_id', 'week_day', 'work_hour', 'timeline_timestamp', 'student_user_id'], 'integer'],
            ['timeline', 'validateDateField', 'skipOnEmpty' => true],
            ['timeline', 'safe'],
            ['room_hash', 'string', 'max' => 32],
            ['lesson_status', 'in', 'range' => [self::STATUS_AWAIT, self::STATUS_PASSED, self::STATUS_FAILED]],
            ['lesson_notice', 'safe'],

            [['methodist_user_id', 'timeline'], 'unique', 'targetAttribute' => ['methodist_user_id', 'timeline']],
            [['methodist_user_id', 'timeline_timestamp'], 'unique', 'targetAttribute' => ['methodist_user_id', 'timeline_timestamp']],
            [['room_hash'], 'unique'],
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => MethodistSchedule::className(), 'targetAttribute' => ['schedule_id' => 'schedule_id']],
            [['methodist_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['methodist_user_id' => 'user_id']],
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
            'timeline_id' => 'Timeline ID',
            'schedule_id' => 'Schedule ID',
            'methodist_user_id' => 'Methodist User ID',
            'week_day' => 'Week Day',
            'work_hour' => 'Work Hour',
            'timeline' => 'Timeline',
            'timeline_timestamp' => 'Timeline Timestamp',
            'student_user_id' => 'Student User ID',
            'room_hash' => 'Room Hash',
        ];
    }

    /**
     * Gets query for [[Schedule]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(MethodistSchedule::className(), ['schedule_id' => 'schedule_id']);
    }

    /**
     * Gets query for [[MethodistUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethodist()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'methodist_user_id']);
    }

    /**
     * Gets query for [[StudentUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'student_user_id']);
    }
}
