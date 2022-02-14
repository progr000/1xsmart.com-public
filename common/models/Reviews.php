<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%reviews}}".
 *
 * @property int $review_id
 * @property string $review_created
 * @property string $review_updated
 * @property int $teacher_user_id
 * @property int $student_user_id
 * @property string $review_text
 * @property float $review_rating
 *
 * @property Users $teacherUser
 * @property Users $studentUser
 */
class Reviews extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reviews}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'review_created',
                'updatedAtAttribute' => 'review_updated',
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
            [['teacher_user_id', 'student_user_id', 'review_text'], 'required'],
            [['teacher_user_id', 'student_user_id'], 'default', 'value' => null],
            [['teacher_user_id', 'student_user_id'], 'integer'],
            [['review_text'], 'string'],
            [['review_rating'], 'number'],
            [['teacher_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['teacher_user_id' => 'user_id']],
            [['student_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['student_user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'review_id' => 'Review ID',
            'review_created' => 'Review Created',
            'review_updated' => 'Review Updated',
            'teacher_user_id' => 'Teacher User ID',
            'student_user_id' => 'Student User ID',
            'review_text' => 'Review Text',
            'review_rating' => 'Review Rating',
        ];
    }

    /**
     * Gets query for [[TeacherUser]].
     *
     * @return Users
     */
    public function getTeacherUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'teacher_user_id'])->one();
    }

    /**
     * Gets query for [[StudentUser]].
     *
     * @return Users
     */
    public function getStudentUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'student_user_id'])->one();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $query = "SELECT sum(review_rating) as s, count(*) as c
                  FROM {{%reviews}}
                  WHERE teacher_user_id = :this
                  GROUP BY teacher_user_id";
        $res = Yii::$app->db->createCommand($query, [
            'this' => $this->teacher_user_id,
        ])->queryOne();
        if (is_array($res)) {
            $rating = round($res['s'] / $res['c'], 2);
            if ($rating >= 0) {
                Users::updateAll(['user_rating' => $rating, 'user_reviews' => $res['c']], ['user_id' => $this->teacher_user_id]);
            }
        }
    }
}
