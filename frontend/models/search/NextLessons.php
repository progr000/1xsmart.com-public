<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use common\models\StudentsTimeline;
use common\models\MethodistTimeline;
use common\models\Users;

/**
 * NextLessons represents the model behind the search form about common\models\News.
 */
class NextLessons extends Model
{
    const ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED = 60*60;//40*60;

    /**
     * @param \common\models\Users $User
     * @return array
     */
    public static function getStudentLesson($User)
    {
        if ($User->user_status == Users::STATUS_BEFORE_INTRODUCE && $User->methodist_user_id) {
            $query = "
                SELECT
                    t1.*,
                    t3.user_full_name as teacher_full_name,
                    t3.user_first_name as teacher_first_name,
                    t3.user_last_name as teacher_last_name,
                    t3.user_email as teacher_email,
                    t3.user_photo as teacher_photo,
                    t3.user_gender as teacher_gender,
                    t3.user_birthday as teacher_birthday,
                    t3.user_learning_objectives as teacher_learning_objectives,
                    t3.user_music_experience as teacher_music_experience,
                    t3.user_music_genres as teacher_music_genres,
                    t3.user_additional_info as teacher_additional_info,
                    :TYPE_METHODIST::int as teacher_type
                FROM {{%methodist_timeline}} as t1
                INNER JOIN {{%users}} as t3 ON t1.methodist_user_id = t3.user_id
                WHERE (t1.student_user_id = :student_user_id)
                AND (t1.methodist_user_id = :methodist_user_id)
                AND (t1.timeline_timestamp > :now)
                AND (t1.student_user_id IS NOT NULL) -- можно убрать т.к. четко задано ИД студента выше в условии
                ORDER BY timeline_timestamp ASC
                LIMIT 1";

            return Yii::$app->db->createCommand($query, [
                'TYPE_METHODIST'    => Users::TYPE_METHODIST,
                'student_user_id'   => $User->user_id,
                'methodist_user_id' => $User->methodist_user_id,
                'now'               => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия),
            ])->queryOne();
        }
        if (in_array($User->user_status, [
                Users::STATUS_ACTIVE,
                Users::STATUS_AFTER_PAYMENT,
                Users::STATUS_BEFORE_INTRODUCE,
                Users::STATUS_AFTER_INTRODUCE]) && $User->teacher_user_id) {

            if (in_array($User->user_status, [Users::STATUS_AFTER_PAYMENT, Users::STATUS_ACTIVE])) {
                $where_teacher = 'AND (t1.teacher_user_id = ' . intval($User->teacher_user_id) . ')';
                $where_teacher = '';
            } else {
                $where_teacher = '';
            }

            $query = "
                SELECT
                    t1.*,
                    t3.user_full_name as teacher_full_name,
                    t3.user_first_name as teacher_first_name,
                    t3.user_last_name as teacher_last_name,
                    t3.user_email as teacher_email,
                    t3.user_photo as teacher_photo,
                    t3.user_gender as teacher_gender,
                    t3.user_birthday as teacher_birthday,
                    t3.user_learning_objectives as teacher_learning_objectives,
                    t3.user_music_experience as teacher_music_experience,
                    t3.user_music_genres as teacher_music_genres,
                    t3.user_additional_info as teacher_additional_info,
                    :TYPE_TEACHER::int as teacher_type
                FROM {{%students_timeline}} as t1
                INNER JOIN {{%users}} as t3 ON t1.teacher_user_id = t3.user_id
                WHERE (t1.student_user_id = :student_user_id)
                {$where_teacher}
                AND (t1.timeline_timestamp > :now)
                AND (t1.student_user_id IS NOT NULL) -- можно убрать т.к. четко задано ИД студента выше в условии
                ORDER BY timeline_timestamp ASC
                LIMIT 1";


            return Yii::$app->db->createCommand($query, [
                'TYPE_TEACHER'    => Users::TYPE_TEACHER,
                'student_user_id' => $User->user_id,
                //'teacher_user_id' => $User->teacher_user_id,
                'now'             => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия)
            ])->queryOne();
        }

        return null;
    }

    /**
     * @param $teacher_user_id
     * @return array|false
     */
    public static function getTeacherLesson($teacher_user_id)
    {
        $query = "SELECT
                    t1.*,
                    t3.user_full_name as student_full_name,
                    t3.user_first_name as student_first_name,
                    t3.user_last_name as student_last_name,
                    t3.user_email as student_email,
                    t3.user_photo as student_photo,
                    t3.user_gender,
                    t3.user_birthday,
                    t3.user_learning_objectives,
                    t3.user_music_experience,
                    t3.user_music_genres,
                    t3.user_additional_info
                  FROM {{%students_timeline}} as t1
                  INNER JOIN {{%users}} as t3 ON t1.student_user_id = t3.user_id
                  WHERE (t1.teacher_user_id = :teacher_user_id)
                  AND (t1.student_user_id IS NOT NULL)
                  AND (t1.timeline_timestamp > :now)
                  ORDER BY timeline_timestamp ASC
                  LIMIT 1";

        return Yii::$app->db->createCommand($query, [
            'teacher_user_id' => $teacher_user_id,
            'now'             => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия),
        ])->queryOne();
    }

    /**
     * @param $methodist_user_id
     * @return array|false
     */
    public static function getMethodistLesson($methodist_user_id)
    {
        $query = "SELECT
                    t1.*,
                    --t2.user_full_name as methodist_full_name,
                    --t2.user_email as methodist_email,
                    t3.user_full_name as student_full_name,
                    t3.user_email as student_email,
                    t3.user_photo as student_photo,
                    t3.user_gender,
                    t3.user_birthday,
                    t3.user_learning_objectives,
                    t3.user_music_experience,
                    t3.user_music_genres,
                    t3.user_additional_info
                  FROM {{%methodist_timeline}} as t1
                  --INNER JOIN {{%users}} as t2 ON t1.methodist_user_id = t2.user_id
                  INNER JOIN {{%users}} as t3 ON t1.student_user_id = t3.user_id
                  WHERE (t1.methodist_user_id = :methodist_user_id)
                  AND (t1.timeline_timestamp > :now)
                  AND (t1.student_user_id IS NOT NULL)
                  ORDER BY timeline_timestamp ASC
                  LIMIT 1";

        return Yii::$app->db->createCommand($query, [
            'methodist_user_id' => $methodist_user_id,
            'now'               => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия)
        ])->queryOne();
    }

    /**
     * @param string $room
     * @param  \common\models\Users $User
     * @return StudentsTimeline
     */
    public static function checkEducationalLessonRoomHash($room, $User)
    {
        $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER;
        if ($User->user_type == Users::TYPE_STUDENT) {
            $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT;
        }
        $query =  StudentsTimeline::find()
            ->where('(room_hash = :room) AND (timeline_timestamp > :now) AND (timeline_timestamp < :now2)', [
                'room' => $room,
                'now'  => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия)
                'now2' => $now2,
            ]);
        if ($User->user_type == Users::TYPE_STUDENT) {
            $query->andWhere(['student_user_id' => $User->user_id]);
        } else {
            $query->andWhere(['teacher_user_id' => $User->user_id]);
        }
        return $query->one();
    }

    /**
     * @param string $room
     * @param  \common\models\Users $User
     * @return MethodistTimeline
     */
    public static function checkIntroductoryLessonRoomHash($room, $User)
    {
        $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST;
        if ($User->user_type == Users::TYPE_STUDENT) {
            $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT;
        }
        $query =  MethodistTimeline::find()
            ->where('(room_hash = :room) AND (timeline_timestamp > :now) AND (timeline_timestamp < :now2)', [
                'room' => $room,
                'now'  => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED, //(занятие длится 1 час и нужно дать возможность войти в класс на протяжении этого часа, даже после начала занятия)
                'now2' => $now2,
            ]);
        if ($User->user_type == Users::TYPE_STUDENT) {
            $query->andWhere(['student_user_id' => $User->user_id]);
        } else {
            $query->andWhere(['methodist_user_id' => $User->user_id]);
        }
        //var_dump($query->one());exit;
        return $query->one();
    }

    /**
     * @param  \common\models\Users $User
     * @return \common\models\MethodistTimeline | null
     */
    public static function getIntroductoryLessonRoomHash($User)
    {
        $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST;
        if ($User->user_type == Users::TYPE_STUDENT) {
            $now2 = time() + Users::ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT;
        }
        $query =  MethodistTimeline::find()
            ->where('(timeline_timestamp > :now) AND (timeline_timestamp < :now2)', [
                'now'  => time() - self::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED,
                'now2' => $now2,
            ]);
        if ($User->user_type == Users::TYPE_STUDENT) {
            $query->andWhere(['student_user_id' => $User->user_id]);
        } else {
            $query->andWhere(['methodist_user_id' => $User->user_id]);
        }
        //var_dump($query->one());exit;
        return $query->one();
    }
}
