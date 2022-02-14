<?php

namespace frontend\models\schedule;

use common\models\StudentsTimeline;
use Yii;
use common\helpers\Functions;
use common\models\Users;
use common\models\TeachersSchedule;
use common\models\StudentsSchedule;
use yii\web\User;

/**
 *
 * @property integer $teacher_user_id
 * @property integer $synchronize_schedule_for_student_user_id
 *
 */
class TeachersScheduleForm extends CommonScheduleForm
{

    public $synchronize_schedule_for_student_user_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['synchronize_schedule_for_student_user_id', 'integer'],
        ]);
        //return parent::rules();
    }

    /**
     * @return array
     */
    public function getSchedule()
    {
        /*
        status:
            0: not selected you can change (select it)
            1: selected by you and you can change (un-select it)
            2: enable student for methodist and you can't change it (selected and enable student on this time)
        */

        /* если запрос для расписания на странице set-schedule для ученика то нужно проверить что есть совпадения расписаний иначе глюканет */
        /* нужно их синхронизировать */
        if ($this->synchronize_schedule_for_student_user_id) {

            $transaction = Yii::$app->db->beginTransaction();

            /* 1 */
            $tmpStudentsSchedule = StudentsSchedule::findAll([
                'student_user_id' => $this->synchronize_schedule_for_student_user_id,
                'teacher_user_id' => $this->user_id,
            ]);
            if ($tmpStudentsSchedule) {
                foreach ($tmpStudentsSchedule as $tmpSchedule) {
                    unset($tmpTeacherSchedule);
                    $tmpTeacherSchedule = TeachersSchedule::findOne([
                        'teacher_user_id' => $this->user_id,
                        'student_user_id' => $tmpSchedule->student_user_id,
                        'week_day' => $tmpSchedule->week_day,
                        'work_hour' => $tmpSchedule->work_hour
                    ]);
                    if (!$tmpTeacherSchedule) {
                        //var_dump(11111);exit;
                        $lessons_available = StudentsTimeline::deleteAll(['schedule_id' => $tmpSchedule->schedule_id]);
                        $tmpSchedule->delete();

                        if ($lessons_available > 0) {
                            $res = Yii::$app->db->createCommand("
                              UPDATE {{%users}}
                              SET user_lessons_available = user_lessons_available + :user_lessons_available
                              WHERE user_id = :user_id", [
                                'user_lessons_available' => $lessons_available,
                                'user_id' => $tmpSchedule->student_user_id,
                            ])->execute();
                        }
                    }
                }
            }

            /* 2 */
            $tmpTeacherSchedule = TeachersSchedule::findAll([
                'teacher_user_id' => $this->user_id,
                'student_user_id' => $this->synchronize_schedule_for_student_user_id,
            ]);
            if ($tmpTeacherSchedule) {
                foreach ($tmpTeacherSchedule as $tmpSchedule) {
                    unset($tmpStudentsSchedule);
                    $tmpStudentsSchedule = StudentsSchedule::findOne([
                        'student_user_id' => $tmpSchedule->student_user_id,
                        'teacher_user_id' => $tmpSchedule->teacher_user_id,
                        'week_day' => $tmpSchedule->week_day,
                        'work_hour' => $tmpSchedule->work_hour
                    ]);
                    if (!$tmpStudentsSchedule) {
                        $tmpSchedule->student_user_id = null;
                        $tmpSchedule->save();
                    }
                }
            }

            $transaction->commit();
        }

        /* общий отбор данных по расписанию */
        $userSchedule = parent::getSchedule();
        //var_dump($userSchedule);exit;

        /* добавочный отбор параметров для учителя (будет показано какие часы уже заняты учениками) */
        if ($this->user_type == Users::TYPE_TEACHER) {
            $userTimelineQuery = "
                SELECT
                    t1.week_day,
                    t1.work_hour,
                    string_agg(DISTINCT concat(t2.user_first_name::VARCHAR, ' (id: ', t1.student_user_id::VARCHAR, ')'), ', ') as student_users,
                    string_agg(DISTINCT concat('{\"user_name\":\"', t2.user_first_name::VARCHAR, '\", \"user_id\":', t1.student_user_id::VARCHAR, '}'), ',') as student_users_json
                    -- string_agg(t1.student_user_id::VARCHAR, ',' ORDER BY t1.student_user_id ASC) as student_user_ids,
                    -- string_agg(t2.user_first_name::VARCHAR, ',' ORDER BY t1.student_user_id ASC) as student_user_names
                FROM {{%students_timeline}} as t1
                INNER JOIN {{%users}} as t2 ON t1.student_user_id = t2.user_id
                WHERE (t1.teacher_user_id = :user_id)
                AND (t1.student_user_id IS NOT NULL)
                AND (t1.timeline_timestamp > :now)
                GROUP BY t1.week_day, t1.work_hour
                ORDER BY t1.week_day ASC, t1.work_hour ASC";

            $res = Yii::$app->db->createCommand($userTimelineQuery, [
                'user_id' => $this->user_id,
                'now' => time(),
            ])->queryAll();

            /**/
            foreach ($res as $item) {
                $item_week_day  = intval($item['week_day']);
                $item_work_hour = intval($item['work_hour']);

                /* учет таймзоны юзера */
                $tmp = self::dayAndHourFromGmtToTz($item_week_day, $item_work_hour, $this->user_timezone);
                $item_week_day  = intval($tmp['week_day']);
                $item_work_hour = intval($tmp['work_hour']);

                if (isset($userSchedule[$item_week_day][$item_work_hour])) {
                    $userSchedule[$item_week_day][$item_work_hour] = ['status' => 2, 'users' => $item['student_users'], 'users_json' => json_decode("[{$item['student_users_json']}]")];
                }
            }

            /* отсеиваем еще те даты где у юзера в расписании занятия с другими учителями */
            $studentsScheduleWithOtherTeacherQuery = "
            SELECT
                student_user_id,
                week_day,
                work_hour
            FROM {{%students_schedule}}
            WHERE (student_user_id = :student_user_id)
            AND (teacher_user_id != :teacher_user_id)
            GROUP BY week_day, work_hour, student_user_id
            ORDER BY week_day ASC, work_hour ASC";
            $res2 = Yii::$app->db->createCommand($studentsScheduleWithOtherTeacherQuery, [
                'student_user_id' => $this->synchronize_schedule_for_student_user_id,
                'teacher_user_id' => $this->user_id,
            ])->queryAll();
            foreach ($res2 as $item) {
                $item_week_day  = intval($item['week_day']);
                $item_work_hour = intval($item['work_hour']);

                /* учет таймзоны юзера */
                $tmp = self::dayAndHourFromGmtToTz($item_week_day, $item_work_hour, $this->user_timezone);
                $item_week_day  = intval($tmp['week_day']);
                $item_work_hour = intval($tmp['work_hour']);

                if (isset($userSchedule[$item_week_day][$item_work_hour])) {
                    $userSchedule[$item_week_day][$item_work_hour] = [
                        'status' => 0,
                        'users' => null,
                        'users_json' => [['user_name' => '', 'user_id' => '']],
                    ];
                }
            }
        }

        return $userSchedule;
    }

    /**
     * @return array
     */
    public function changeSchedule()
    {
        $transaction = Yii::$app->db->beginTransaction();

        if ($this->hour_status == self::OFF) {
            /* проверка что этот час в расписании никем из студентов еще не занят, тогда можно его снять */
            /** @var \common\models\TeachersSchedule $res */
            $res = TeachersSchedule::find()->where([
                'teacher_user_id' => $this->user_id,
                'week_day'        => $this->week_day,
                'work_hour'       => $this->work_hour,
            ])->with('student')->one();
            if ($res->student_user_id) {
                $transaction->rollBack();
                return [
                    'changed' => false,
                    'info'    => "На это время стоит занятие у ученика &lt;{$res->getStudent()->one()->user_email}&gt;. Нельзя отменить",
                ];
            }

            /* снимаем час с расписания */
            if (TeachersSchedule::deleteAll([
                'teacher_user_id' => $this->user_id,
                'week_day'        => $this->week_day,
                'work_hour'       => $this->work_hour,
            ])) {
                $transaction->commit();
                return [
                    'changed' => true,
                    'info' => "OK",
                ];
            }
        } else {
            /* устанавливаем час на расписание */
            $Schedule = new TeachersSchedule();
            $Schedule->teacher_user_id = $this->user_id;
            $Schedule->student_user_id = null;
            $Schedule->week_day = $this->week_day;
            $Schedule->work_hour = $this->work_hour;
            if ($Schedule->save()) {
                $transaction->commit();
                return [
                    'changed' => true,
                    'info' => "OK",
                ];
            }
        }

        /* оибка БД */
        $transaction->rollBack();
        return [
            'changed' => false,
            'info' => "DB error",
        ];
    }

    /**
     * @param $user_local_time
     * @param $tz
     * @param $is_for_teacher
     * @return array
     */
    public function getScheduleForTwoWeekByDate($user_local_time, $tz, $is_for_teacher=false)
    {
        //$data = $this->getSchedule();
        $data = parent::getSchedule();


        $userTimelineQuery = "
                SELECT
                    t1.week_day,
                    t1.work_hour,
                    t1.timeline,
                    t1.timeline_timestamp,
                    t1.student_user_id,
                    t2.user_first_name
                FROM {{%students_timeline}} as t1
                INNER JOIN {{%users}} as t2 ON t1.student_user_id = t2.user_id
                WHERE (t1.teacher_user_id = :user_id)
                AND (t1.student_user_id IS NOT NULL)
                AND (t1.timeline_timestamp > :now)
                ORDER BY timeline_timestamp ASC";

        $res = Yii::$app->db->createCommand($userTimelineQuery, [
            'user_id' => $this->user_id,
            'now' => time(),
        ])->queryAll();

        /**/
        foreach ($res as $item) {
            $used[$item['timeline_timestamp']] = $item['user_first_name'] . ' (id: '. $item['student_user_id'] .')';
        }



        //var_dump($data); exit;

        $today = intval(Functions::getDayOfWeek($user_local_time));
        $today = $today - 1;
        /* ??? */
        $user_local_time = Functions::getTimestampBeginOfDayByTimestamp($user_local_time);

        $date_start = $user_local_time - $today * 24 * 60 * 60;
        $date_end = $date_start + 13 * 24 * 60 * 60;

        //var_dump(date('Y-m-d, w, H:i', $date_start));
        //var_dump(date('Y-m-d, w, H:i', $date_end));
        //echo "<hr />";

        $ret = [];
        $now = time();
        $now_h = intval(date('G', time()));
        $now_w = Functions::getDayOfWeek(time());
        while ($date_start <= $date_end) {

            $week_day = Functions::getDayOfWeek($date_start);

            if (isset($data[$week_day])) { $data_local[$week_day] = $data[$week_day]; }

            //if (!$is_for_teacher) {
                if ($date_start <= $now && $week_day <= $now_w && isset($data_local[$week_day])) {
                    foreach ($data_local[$week_day] as $k_h => $k_V) {

                        if ($week_day < $now_w || $k_h <= $now_h + ($tz/3600)) {
                            if ($data_local[$week_day][$k_h]['status'] == 1) {
                                $data_local[$week_day][$k_h]['status'] = 2;
                                //$data_local[$week_day][$k_h]['users'] = 'Not available';
                                $data_local[$week_day][$k_h]['tooltip'] = 'Not available';
                                $data_local[$week_day][$k_h]['additional_class'] = '_disabled'; /// ????
                            }
                        }

                    }
                    //var_dump($data_local[$week_day]);
                }
            //}

            /**/
            if (isset($data_local[$week_day])) {
                foreach ($data_local[$week_day] as $kh => $kv) {
                    $data_local[$week_day][$kh]['date'] = $date_start + $kh * 60 * 60 - $tz;
                    if (isset($used[$data_local[$week_day][$kh]['date']])) {
                        $data_local[$week_day][$kh]['status'] = 2;
                        $data_local[$week_day][$kh]['users'] = $used[$data_local[$week_day][$kh]['date']];
                        $data_local[$week_day][$kh]['tooltip'] = 'Arranged lesson';
                        $data_local[$week_day][$kh]['additional_class'] = 'lesson_scheduled _active';
                        $data_local[$week_day][$kh]['is_arranged'] = true;
                    }
                }
            }

            /**/
            $ret[] = [
                'date' => $date_start,
                //'timestamp_gmt' => $date_start,
                'week_day' => $week_day,
                'hours' => isset($data_local[$week_day]) ? $data_local[$week_day] : null,
            ];
            //echo date('Y-m-d, w, H:i', $date_start) . '<br />';

            $date_start += 24 * 60 * 60;
        }

        //var_dump($ret);
        return $ret;
    }
}
