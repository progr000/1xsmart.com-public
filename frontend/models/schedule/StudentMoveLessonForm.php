<?php

namespace frontend\models\schedule;

use Yii;
use yii\base\Model;
use common\helpers\Functions;
use common\models\MailTemplate;
use common\models\Users;
use common\models\StudentsTimeline;
use common\models\StudentsSchedule;
use common\models\TeachersSchedule;

/**
 *
 * @property string $move_type
 *
 * @property integer $week_day_to
 * @property integer $work_hour_to
 * @property integer $timestamp_gmt_to
 *
 * @property integer $week_day_from
 * @property integer $work_hour_from
 * @property integer $timestamp_gmt_from
 * @property integer $timeline_id_from
 * @property integer $teacher_user_id
 *
 * @property Users $Student
 *
 */
class StudentMoveLessonForm extends Model
{
    const MOVE_TYPE_ONCE      = 'once';
    const MOVE_TYPE_PERMANENT = 'permanent';

    public $Student;

    public $move_type;

    public $week_day_to;
    public $work_hour_to;
    public $timestamp_gmt_to;

    public $week_day_from;
    public $work_hour_from;
    public $timestamp_gmt_from;
    public $timeline_id_from;
    public $teacher_user_id;


    /**
     * inheritdoc
     */
    public function rules()
    {
        return [
            [[

                'move_type',

                'week_day_to',
                'work_hour_to',
                'timestamp_gmt_to',

                'week_day_from',
                'work_hour_from',
                'timestamp_gmt_from',
                'timeline_id_from',
                'teacher_user_id',

                //'Student',

            ], 'required'],

            ['move_type', 'in', 'range' => [self::MOVE_TYPE_ONCE, self::MOVE_TYPE_PERMANENT]],
            [[

                'week_day_to',
                'work_hour_to',
                'timestamp_gmt_to',

                'week_day_from',
                'work_hour_from',
                'timestamp_gmt_from',
                'timeline_id_from',

            ], 'integer'],

            ['timeline_id_from', 'exist', 'skipOnError' => true, 'targetClass' => StudentsTimeline::className(), 'targetAttribute' => ['timeline_id_from' => 'timeline_id']],
        ];
    }

    /**
     * @return array
     */
    public function moveLesson()
    {
        $hours = [];
        $now = time();

        /**/
        if ($this->timestamp_gmt_from < $now + StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS * 60 * 60) {
            return [
                'status' => false,
                'info' => "This lesson can't be rescheduled. You can change the lesson time not later than " . StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS . " hours before the planned time.",
            ];
        }

        /**/
        if ($this->timestamp_gmt_to < $now + StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS * 60 * 60) {
            return [
                'status' => false,
                'info' => "This lesson can't be rescheduled. You can change the lesson time not later than " . StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS . " hours before the planned time.",
            ];
        }

        /**/
        $user_local_time = $this->Student->_user_local_time;
        $today = intval(Functions::getDayOfWeek($user_local_time));
        $today = $today - 1;
        $user_local_time = Functions::getTimestampBeginOfDayByTimestamp($this->Student->_user_local_time);
        $date_start = $user_local_time - $today * 24 * 60 * 60;
        $date_end = $date_start + 14 * 24 * 60 * 60 - $this->Student->user_timezone - 3600;
        if ($this->timestamp_gmt_to > $date_end) {
            return [
                'status' => false,
                'info' => "This lesson cannot be rescheduled over to this time. You can change the time of the lesson to a date no later than the end of the next week.",
            ];
        }
        if ($this->timestamp_gmt_from > $date_end) {
            return [
                'status' => false,
                'info' => "This lesson can't be rescheduled. You can change the lesson time not later than " . StudentsScheduleForm::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS . " hours before the planned time.",
            ];
        }

        /**/
        $this->week_day_to = Functions::getDayOfWeek($this->timestamp_gmt_to);
        $this->work_hour_to = intval(date('G', $this->timestamp_gmt_to));
        $date_test = date('Y-m-d', $this->timestamp_gmt_to) . " {$this->work_hour_to}:00:00";
        $test_timestamp_gmt_to = strtotime($date_test);
        if ($test_timestamp_gmt_to != $this->timestamp_gmt_to) {
            return [
                'status' => false,
                'info' => "Wrong timestamp for move lesson",
            ];
        }

        /**/
        $this->week_day_from = Functions::getDayOfWeek($this->timestamp_gmt_from);
        $this->work_hour_from = intval(date('G', $this->timestamp_gmt_from));

        /**/
        $Timeline = StudentsTimeline::findOne([
            'timeline_id' => $this->timeline_id_from,
            'student_user_id' => $this->Student->user_id,
            'week_day' => $this->week_day_from,
            'work_hour' => $this->work_hour_from,
            'timeline_timestamp' => $this->timestamp_gmt_from,
        ]);
        if (!$Timeline) {
            return [
                'status' => false,
                'info' => 'Lesson not found',
            ];
        }

        /**/
        if ($this->move_type == self::MOVE_TYPE_ONCE) {

            /**/
            $testTeacherScheduleOnce = StudentsTimeline::find()
                ->where([
                    'week_day' => $this->week_day_to,
                    'work_hour' => $this->work_hour_to,
                    'timeline_timestamp' => $this->timestamp_gmt_to,
                    'teacher_user_id' => $this->teacher_user_id, //$this->Student->teacher_user_id,
                ])->one();
            if ($testTeacherScheduleOnce) {
                return [
                    'status' => false,
                    'info' => "The teacher does not have free time-slots for lessons at the time of your choice. (Timeline)",
                ];
            }

            $StudentSchedule = StudentsSchedule::findOne([
                'student_user_id' => $this->Student->user_id,
                'week_day' => $this->week_day_to,
                'work_hour' => $this->work_hour_to,
            ]);

            $Timeline->schedule_id = $StudentSchedule ? $StudentSchedule->schedule_id : null;
            $Timeline->week_day = $this->week_day_to;
            $Timeline->work_hour = $this->work_hour_to;
            $Timeline->timeline = date(SQL_DATE_FORMAT, $this->timestamp_gmt_to);
            $Timeline->timeline_timestamp = $this->timestamp_gmt_to;
            $Timeline->replacing_for_timeline_timestamp = $this->timestamp_gmt_to;
            if (!$Timeline->save()) {
                return [
                    'status' => false,
                    'info' => "Some error during move lesson.",
                    //'debug' => $Timeline->getErrors(),
                ];
            }


            $hours[] = [
                'id' => "hour-{$this->timestamp_gmt_from}",
                'status' => 0,
                'timeline-id' => '',
                'schedule-id' => '',
                'teacher-id'  => '',
            ];
            $hours[] = [
                'id' => "hour-{$this->timestamp_gmt_to}",
                'status' => 1,
                'timeline-id' => $Timeline->timeline_id,
                'schedule-id' => $Timeline->schedule_id ? $Timeline->schedule_id : '',
                'teacher-id'  => $Timeline->teacher_user_id,
            ];

            /*
            $timestamp_local_to = $this->timestamp_gmt_to + $this->Student->user_timezone;
            $prn_hour = date('H', $timestamp_local_to) . ":00";
            'data' => [
                'hour-status' => 1,
                'timeline-id' => $Timeline->timeline_id,

                'day'  => Functions::getDayOfWeek($timestamp_local_to),
                'hour' => date('G', $timestamp_local_to),
                'timestamp-gmt' => $Timeline->timeline_timestamp,
                'date-gmt' => date('Y-m-d, H:i:s', $Timeline->timeline_timestamp),
                'test' => $Timeline->timeline_timestamp,
                'print-date' => Functions::getTextWeekDay(Functions::getDayOfWeek($timestamp_local_to), 'Up_') .
                    ', ' .
                    Yii::t('app/common', "month_" . date('n', $timestamp_local_to)) .
                    ' ' .
                    date('d, Y', $timestamp_local_to) . " at {$prn_hour}",
            ]
            */

            /* отправка письма учителю о том что ученик перенес урок в режиме единичного переноса */
            $MailTeacher = Users::findOne(['user_id' => $Timeline->teacher_user_id]);
            if ($MailTeacher && $MailTeacher->receive_system_notif) {

                $tmp_old = StudentsScheduleForm::dayAndHourFromGmtToTz($this->week_day_from, $this->work_hour_from, $MailTeacher->user_timezone);
                $week_day_old = intval($tmp_old['week_day']);
                $work_hour_old = intval($tmp_old['work_hour']);
                $tmp_new = StudentsScheduleForm::dayAndHourFromGmtToTz($this->week_day_to, $this->work_hour_to, $MailTeacher->user_timezone);
                $week_day_new = intval($tmp_new['week_day']);
                $work_hour_new = intval($tmp_new['work_hour']);

                MailTemplate::send([
                    'language' => $MailTeacher->last_system_language,
                    'to_email' => $MailTeacher->user_email,
                    'to_name' => $MailTeacher->user_first_name,
                    'composeTemplate' => 'moveLessonOnce', // 'moveLessonPermanent'
                    'composeData' => [
                        'student_name' => $this->Student->user_first_name,
                        'teacher_display_name' => $MailTeacher->_user_display_name,
                        'lesson_date_old' => $MailTeacher->getDateInUserTimezoneByTimestamp($this->timestamp_gmt_from, Yii::$app->params['datetime_short_format'], true),
                        'lesson_date_new' => $MailTeacher->getDateInUserTimezoneByTimestamp($this->timestamp_gmt_to, Yii::$app->params['datetime_short_format'], true),
                        'week_day_old' => Functions::getTextWeekDay($week_day_old, 'Up_'),
                        'hour_old'     => ($work_hour_old < 10) ? "0{$work_hour_old}:00" : "{$work_hour_old}:00",
                        'week_day_new' => Functions::getTextWeekDay($week_day_new, 'Up_'),
                        'hour_new'     => ($work_hour_new < 10) ? "0{$work_hour_new}:00" : "{$work_hour_new}:00",
                        'short_timezone' => $MailTeacher->_user_timezone_short_name,
                    ],
                    'User' => $MailTeacher,
                ]);
            }

        } else {

            /**/
            if (!$Timeline->schedule_id) {
                return [
                    'status' => false,
                    'info' => "Schedule not found for this lesson. Can't move it permanently.",
                ];
            }

            /* начнем транзакцию */
            $transaction = Yii::$app->db->beginTransaction();

            /**/
            $now = time();

            /* тут предварительные проверки на синхронность расписания с учителем и доступность учительского расписания на выбранные даты */
            $StudentScheduleFrom = StudentsSchedule::findOne([
                'schedule_id' => $Timeline->schedule_id,
                'student_user_id' => $this->Student->user_id,
                'week_day' => $this->week_day_from,
                'work_hour' => $this->work_hour_from,
            ]);

            $TeacherScheduleFrom = TeachersSchedule::findOne([
                'teacher_user_id' => $this->teacher_user_id, //$this->Student->teacher_user_id,
                'student_user_id' => $this->Student->user_id,
                'week_day' => $this->week_day_from,
                'work_hour' => $this->work_hour_from,
            ]);

            if (!$StudentScheduleFrom || !$TeacherScheduleFrom) {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info' => "Something wrong with schedule synchronization. Contact us.",
                ];
            }

            $TeacherScheduleTo = TeachersSchedule::findOne([
                'teacher_user_id' => $this->teacher_user_id, //$this->Student->teacher_user_id,
                'student_user_id' => null,
                'week_day' => $this->week_day_to,
                'work_hour' => $this->work_hour_to,
            ]);

            if (!$TeacherScheduleTo) {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info' => "The teacher does not have free time-slots for lessons at the time of your choice. (Schedule)",
                ];
            }

            $testTeacherSchedule = StudentsTimeline::find()
                ->where([
                    'week_day' => $this->week_day_to,
                    'work_hour' => $this->work_hour_to,
                    'teacher_user_id' => $this->teacher_user_id, //$this->Student->teacher_user_id,
                ])->andWhere('timeline_timestamp > :now', [
                    'now' => $now,
                ])->all();
            if ($testTeacherSchedule) {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info' => "The teacher does not have free time-slots for lessons at the time of your choice. (Timeline)",
                ];
            }

            /* дальше добавляем в массив $hours старые StudentsTimeline принадлежащие $StudentScheduleFrom */
            $count_add_lessons = 0;
            $StudentsTimelineFrom = StudentsTimeline::findAll([
                'student_user_id' => $this->Student->user_id,
                'schedule_id' => $StudentScheduleFrom->schedule_id,
            ]);
            foreach ($StudentsTimelineFrom as $stf) {
                /** @var $stf \common\models\StudentsTimeline */
                if ($stf->timeline_timestamp <= $now) {
                    $hours[] = [
                        'id' => "hour-{$stf->timeline_timestamp}",
                        'status' => 2,
                        'timeline-id' => '',
                        'schedule-id' => '',
                        'teacher-id'  => '',
                    ];
                    $stf->schedule_id = null;
                    $stf->save();
                } else {
                    $hours[] = [
                        'id' => "hour-{$stf->timeline_timestamp}",
                        'status' => 0,
                        'timeline-id' => '',
                        'schedule-id' => '',
                        'teacher-id'  => '',
                    ];
                    $count_add_lessons++;
                }
            }

            /* дальше удаляем StudentsTimeline c (schedule_id = $StudentScheduleFrom->schedule_id) и возвращаем
            студенту количество уроков равное количеству удаленных таймлайнов с датой больше текущего времени */
            StudentsTimeline::deleteAll([
                'student_user_id' => $this->Student->user_id,
                'schedule_id' => $StudentScheduleFrom->schedule_id,
            ]);
            $this->Student->user_lessons_available += $count_add_lessons;
            $this->Student->save();

            /* дальше удаляем $StudentScheduleFrom*/
            $StudentScheduleFrom->delete();

            /* дальше ставим student_user_id=null в $TeacherScheduleFrom */
            $TeacherScheduleFrom->student_user_id = null;
            $TeacherScheduleFrom->save();

            /* дальше создаем новый $StudentScheduleTo */
            $StudentScheduleTo = new StudentsSchedule();
            $StudentScheduleTo->student_user_id = $this->Student->user_id;
            $StudentScheduleTo->week_day = $this->week_day_to;
            $StudentScheduleTo->work_hour = $this->work_hour_to;
            $StudentScheduleTo->teacher_user_id = $StudentScheduleFrom->teacher_user_id;
            $StudentScheduleTo->save();

            /* дальше ставим student_user_id=$this->Student->user_id в $TeacherScheduleTo */
            $TeacherScheduleTo->student_user_id = $this->Student->user_id;
            $TeacherScheduleTo->save();

            /* дальше генерируем новые таймлайны для студента по этому шедуле ($StudentScheduleTo) начиная с той даты которая пришла в $this->timestamp_gmt_to */
            $modelSSF = new StudentsScheduleForm();
            if ($modelSSF->load(['StudentsScheduleForm' => [
                    'user_id'         => $this->Student->user_id,
                    'user_type'       => $this->Student->user_type,
                    'user_timezone'   => $this->Student->user_timezone,
                    'teacher_user_id' => $this->teacher_user_id, //$this->Student->teacher_user_id,
                    'date_start'      => date(SQL_DATE_FORMAT, $this->timestamp_gmt_to),
                ]]) && $modelSSF->validate())
            {

                $schedule_for_generate_timeline[$StudentScheduleTo->week_day][$StudentScheduleTo->work_hour] = $StudentScheduleTo->schedule_id;
                $ret_generate = $modelSSF->generateTimeline(
                    $this->timestamp_gmt_to,
                    $schedule_for_generate_timeline
                );
                if ($ret_generate['status']) {
                    $this->Student->user_last_lesson = $ret_generate['last_lesson_date'];
                    $this->Student->user_lessons_available -= $ret_generate['count_lessons_created'];
                    if ($this->Student->user_lessons_available < 0) { $this->Student->user_lessons_available = 0; }
                    $this->Student->save();
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => false,
                        'info' => "Failed generate timeline for schedule (2). Contact us.",
                    ];
                }

            } else {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info' => "Failed generate timeline for schedule(1). Contact us.",
                ];
            }


            /* дальше выбираем сгенерированные новые таймлайны и добавляем их в массив $hours*/
            $StudentsTimelineTo = StudentsTimeline::findAll([
                'student_user_id' => $this->Student->user_id,
                'schedule_id' => $StudentScheduleTo->schedule_id,
            ]);
            foreach ($StudentsTimelineTo as $stt) {
                /** @var $stf \common\models\StudentsTimeline */
                $hours[] = [
                    'id' => "hour-{$stt->timeline_timestamp}",
                    'status' => 1,
                    'timeline-id' => $stt->timeline_id,
                    'schedule-id' => $stt->schedule_id,
                    'teacher-id'  => $stt->teacher_user_id,
                ];
            }

            /* фиксация транзакции */
            $transaction->commit();

            /* отправка письма учителю о том что ученик перенес урок в режиме постоянного переноса */
            $MailTeacher = Users::findOne(['user_id' => $this->teacher_user_id]);
            if ($MailTeacher && $MailTeacher->receive_system_notif) {

                $tmp_old = StudentsScheduleForm::dayAndHourFromGmtToTz($this->week_day_from, $this->work_hour_from, $MailTeacher->user_timezone);
                $week_day_old = intval($tmp_old['week_day']);
                $work_hour_old = intval($tmp_old['work_hour']);
                $tmp_new = StudentsScheduleForm::dayAndHourFromGmtToTz($this->week_day_to, $this->work_hour_to, $MailTeacher->user_timezone);
                $week_day_new = intval($tmp_new['week_day']);
                $work_hour_new = intval($tmp_new['work_hour']);

                MailTemplate::send([
                    'language' => $MailTeacher->last_system_language,
                    'to_email' => $MailTeacher->user_email,
                    'to_name' => $MailTeacher->user_first_name,
                    'composeTemplate' => 'moveLessonPermanent',
                    'composeData' => [
                        'student_name' => $this->Student->user_first_name,
                        'teacher_display_name' => $MailTeacher->_user_display_name,
                        'week_day_old' => Functions::getTextWeekDay($week_day_old, 'Up_'),
                        'hour_old'     => ($work_hour_old < 10) ? "0{$work_hour_old}:00" : "{$work_hour_old}:00",
                        'week_day_new' => Functions::getTextWeekDay($week_day_new, 'Up_'),
                        'hour_new'     => ($work_hour_new < 10) ? "0{$work_hour_new}:00" : "{$work_hour_new}:00",
                        'short_timezone' => $MailTeacher->_user_timezone_short_name,
                    ],
                    'User' => $MailTeacher,
                ]);
            }

        }

        return [
            'status' => true,
            'data' => [
                'hours' => $hours,
            ],
            'info' => 'OK',
        ];
    }
}
