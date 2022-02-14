<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\caching\TagDependency;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%students_timeline}}".
 *
 * @property int $timeline_id
 * @property int $schedule_id
 * @property int $student_user_id
 * @property int $week_day
 * @property int $work_hour
 * @property string $timeline
 * @property int $is_replacing
 * @property int $teacher_user_id
 * @property int $timeline_timestamp
 * @property int $replacing_for_timeline_timestamp
 * @property string $room_hash
 *
 * @property string $notes_lowest
 * @property string $notes_highest
 * @property int $notes_played
 * @property int $notes_hit
 * @property int $notes_close
 *
 * @property int $lesson_status
 * @property string|null $lesson_notice
 * @property int $is_introduce_lesson
 * @property float $lesson_amount_usd
 *
 * @property StudentsSchedule $schedule
 * @property Users $user
 */
class StudentsTimeline extends ActiveRecord
{
    const YES = 1;
    const NO  = 0;

    const STATUS_AWAIT = 0;
    const STATUS_PASSED = 1;
    const STATUS_FAILED = 2;

    const CONFIRM_LESSON_AFTER = 72 * 60 * 60; // 72 hours

    public static $discount_vars = [
        ['lessons_count' => 5,  'discount_percent' => 0.0, 'name' => 'Smarty'],
        ['lessons_count' => 11, 'discount_percent' => 1.5, 'name' => 'Smart', 'additional_header' => 'Most_popular'],
        ['lessons_count' => 21, 'discount_percent' => 3.0, 'name' => '1xSMART'],
        //['lessons_count' => 31, 'discount_percent' => 6.0, 'name' => '2xSMART'],
    ];

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
        return '{{%students_timeline}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[/*'schedule_id',*/ 'student_user_id', 'teacher_user_id', 'week_day', 'work_hour', 'timeline', 'timeline_timestamp'], 'required'],
            //[['schedule_id', 'student_user_id', 'week_day', 'work_hour'], 'default', 'value' => null],
            [[
                'schedule_id',
                'student_user_id',
                'teacher_user_id',
                'week_day',
                'work_hour',
                'timeline_timestamp',
                'replacing_for_timeline_timestamp',
            ], 'integer'],
            ['timeline', 'validateDateField', 'skipOnEmpty' => true],
            ['timeline', 'safe'],
            ['is_replacing', 'integer', 'min' => self::NO, 'max' => self::YES],
            ['is_replacing', 'default', 'value' => self::NO],
            ['is_introduce_lesson', 'integer', 'min' => self::NO, 'max' => self::YES],
            ['is_introduce_lesson', 'default', 'value' => self::NO],
            ['room_hash', 'string', 'length' => 32],

            ['lesson_status', 'in', 'range' => [self::STATUS_AWAIT, self::STATUS_PASSED, self::STATUS_FAILED]],
            ['lesson_notice', 'safe'],

            [['notes_played', 'notes_hit', 'notes_close'], 'integer', 'min' => 0],
            [['notes_lowest', 'notes_highest'], 'string', 'max' => 3],

            ['lesson_amount_usd', 'number'],

            [['student_user_id', 'timeline'], 'unique', 'targetAttribute' => ['student_user_id', 'timeline']],
            [['student_user_id', 'timeline_timestamp'], 'unique', 'targetAttribute' => ['student_user_id', 'timeline_timestamp']],
            [['student_user_id', 'replacing_for_timeline_timestamp'], 'unique', 'targetAttribute' => ['student_user_id', 'replacing_for_timeline_timestamp']],
            [['room_hash'], 'unique', 'targetAttribute' => ['room_hash']],
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentsSchedule::className(), 'targetAttribute' => ['schedule_id' => 'schedule_id']],
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
            'timeline_id' => 'Timeline ID',
            'schedule_id' => 'Schedule ID',
            'student_user_id' => 'User ID',
            'week_day' => 'Week Day',
            'work_hour' => 'Work Hour',
            'timeline' => 'Timeline',
        ];
    }

    /**
     * Gets query for [[Schedule]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(StudentsSchedule::className(), ['schedule_id' => 'schedule_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'student_user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'teacher_user_id']);
    }

    /**
     * Invalidate Cache
     */
    protected function invalidateCache()
    {
        TagDependency::invalidate(Yii::$app->cache, [
            'StudentsTimeline.student_user_id.' . $this->student_user_id,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->invalidateCache();
    }
}
