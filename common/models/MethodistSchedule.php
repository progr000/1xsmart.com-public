<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%methodist_schedule}}".
 *
 * @property int $schedule_id
 * @property int $methodist_user_id
 * @property int $week_day
 * @property int $work_hour
 * @property int $student_user_id
 *
 * @property Users $user
 */
class MethodistSchedule extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%methodist_schedule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['methodist_user_id', 'week_day', 'work_hour'], 'required'],
            [['methodist_user_id', 'student_user_id'], 'integer'],
            ['week_day', 'integer', 'min' => 1, 'max' => 7],
            ['work_hour', 'integer', 'min' => 0, 'max' => 23],

            [['methodist_user_id', 'week_day', 'work_hour'], 'unique', 'targetAttribute' => ['methodist_user_id', 'week_day', 'work_hour']],
            [['methodist_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['methodist_user_id' => 'user_id']],
            [['student_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['student_user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'schedule_id' => 'Schedule ID',
            'methodist_user_id' => 'Methodist User ID',
            'week_day' => 'Week Day',
            'work_hour' => 'Work Hour',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethodist()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'methodist_user_id']);
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
}
