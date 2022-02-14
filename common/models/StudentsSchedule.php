<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%students_schedule}}".
 *
 * @property int $schedule_id
 * @property int $student_user_id
 * @property int $week_day
 * @property int $work_hour
 * @property int $teacher_user_id
 *
 * @property Users $user
 * @property StudentsTimeline[] $studentsTimelines
 */
class StudentsSchedule extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%students_schedule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_user_id', 'week_day', 'work_hour'], 'required'],
            //[['student_user_id', 'week_day', 'work_hour'], 'default', 'value' => null],
            [['student_user_id', 'teacher_user_id', 'week_day', 'work_hour'], 'integer'],
            [['student_user_id', 'week_day', 'work_hour'], 'unique', 'targetAttribute' => ['student_user_id', 'week_day', 'work_hour']],
            [['student_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['student_user_id' => 'user_id']],
            [['teacher_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['teacher_user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'schedule_id' => 'Schedule ID',
            'student_user_id' => 'Student User ID',
            'week_day' => 'Week Day',
            'work_hour' => 'Work Hour',
        ];
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
     * Gets query for [[StudentsTimelines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentsTimelines()
    {
        return $this->hasMany(StudentsTimeline::className(), ['schedule_id' => 'schedule_id']);
    }
}
