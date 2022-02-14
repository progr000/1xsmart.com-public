<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%homeworks}}".
 *
 * @property int $work_id
 * @property string $work_created
 * @property string $work_updated
 * @property string $work_name
 * @property string|null $work_file
 * @property int $work_status
 * @property string|null $work_description
 * @property int|null $operator_user_id
 * @property int $methodist_user_id
 *
 * @property Users $operatorUser
 * @property Users $methodistUser
 * @property HomeworksStudents[] $homeworksStudents
 * @property Users[] $studentUsers
 */
class HomeWorks extends ActiveRecord
{
    const STATUS_CHECKED = 1;
    const STATUS_UNCHECKED = 0;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_CHECKED   => 'Проверено',
            self::STATUS_UNCHECKED => 'Непроверено',
        ];
    }

    /**
     * @param $work_status
     * @return mixed
     */
    public static function getStatus($work_status)
    {
        return self::getStatuses()[$work_status];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%homeworks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'work_created',
                'updatedAtAttribute' => 'work_updated',
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
            [['work_name', 'methodist_user_id', 'work_file'], 'required'],
            [['work_created', 'work_updated'], 'validateDateField', 'skipOnEmpty' => true],
            [['work_created', 'work_updated'], 'safe'],
            [['work_name', 'work_description'], 'string'],
            //[['work_status', 'operator_user_id', 'methodist_user_id'], 'default', 'value' => null],
            [['work_status', 'operator_user_id', 'methodist_user_id'], 'integer'],
            [['work_status'], 'in', 'range' => [self::STATUS_CHECKED, self::STATUS_UNCHECKED]],
            [['work_file'], 'string', 'max' => 255],
            [['operator_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['operator_user_id' => 'user_id']],
            [['methodist_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['methodist_user_id' => 'user_id']],
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
            'work_created' => 'Work Created',
            'work_updated' => 'Work Updated',
            'work_name' => 'Work Name',
            'work_file' => 'Work File',
            'work_status' => 'Work Status',
            'work_description' => 'Work Description',
            'operator_user_id' => 'Operator User ID',
            'methodist_user_id' => 'Methodist User ID',
        ];
    }

    /**
     * Gets query for [[OperatorUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperatorUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'operator_user_id']);
    }

    /**
     * Gets query for [[MethodistUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethodistUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'methodist_user_id']);
    }

    /**
     * Gets query for [[HomeworksStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworksStudents()
    {
        return $this->hasMany(HomeworksStudents::className(), ['work_id' => 'work_id']);
    }

    /**
     * Gets query for [[StudentUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentUsers()
    {
        return $this->hasMany(Users::className(), ['user_id' => 'student_user_id'])->viaTable('{{%homeworks_students}}', ['work_id' => 'work_id']);
    }
}
