<?php

namespace frontend\models\schedule;

use Yii;
use common\helpers\Functions;
use common\models\Users;
use common\models\Payments;
use common\models\StudentsTimeline;
use common\models\StudentsSchedule;
use common\models\TeachersSchedule;

/**
 *
 * @property string $date_start
 * @property integer $teacher_user_id
 * @property \common\models\Users $studentUser
 *
 */
class StudentsScheduleForm extends CommonScheduleForm
{
    const MAX_LESSONS_FOR_DAY  = 3;
    const MAX_LESSONS_FOR_WEEK = 10;

    const CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS = 10;

    /* the number of seconds before lesson that can still be changed */
    const SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED = 60 * 60 * 2;

    public $date_start;
    public $teacher_user_id;

    public $studentUser;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['date_start', 'safe'],
            ['date_start', 'validateDateField'],
            ['teacher_user_id', 'integer'],
            ['teacher_user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['teacher_user_id' => 'user_id']],
        ]);
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
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();
        if (empty($this->date_start)) { $this->date_start = date(SQL_DATE_FORMAT); }
        $this->date_start_timestamp = Functions::getTimestampBeginOfDayByTimestamp(strtotime($this->date_start));
        $this->date_start = date(SQL_DATE_FORMAT, $this->date_start_timestamp);

        $this->studentUser = Users::findIdentity($this->user_id);
    }

    /**
     * @return int
     */
    protected function checkIsStudentScheduleSet()
    {
        return StudentsSchedule::find()->where([
            'student_user_id' => $this->user_id,
        ])->count();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function changeSchedule()
    {
        /*
         * у ученика есть пока непонятная ситуация в случае если он тут добавит новый час занятия
         * но при этом таймлайны уже сгененрированы и все доступные оплаченные уроки ушли туда
         * значит он по сути не может добавить новый час таким образом
         * Решением может быть удаление всех таймлайнов которые еще не использованы
         * с подсчетом количества этих снятых таймлайнов
         * затем добавление этого самого нового часа
         * и генерация таймлайнов заново
         * делать это нужно после всех проверок которые делает этот метод
         * в точке где написано TODO - перед добавлением нового часа
         */


        /* проверки на максимальное количество часов в день и в неделю */
        if ($this->hour_status == self::ON) {
            $Check1 = StudentsSchedule::find()->where([
                'student_user_id' => $this->user_id,
            ])->count();
            if ($Check1 >= self::MAX_LESSONS_FOR_WEEK) {
                return [
                    'changed' => false,
                    'info' => "Достигнут максимум разрешенных занятий в неделю",
                ];
            }
            $Check2 = StudentsSchedule::find()->where([
                'student_user_id' => $this->user_id,
                'week_day' => $this->week_day,
            ])->count();
            if ($Check2 >= self::MAX_LESSONS_FOR_DAY) {
                return [
                    'changed' => false,
                    'info' => "Достигнут максимум разрешенных занятий в день",
                ];
            }
        }

        /* Начинаем основное тело настройки расписания */
        $transaction = Yii::$app->db->beginTransaction();

        /* удалим все таймлайны студента которые меньше даты старта расписания ?????? */
//        StudentsTimeline::deleteAll([
//            'timeline_timestamp' => $this->date_start_timestamp,
//            'student_user_id' => $this->user_id,
//        ]);

        /**/
        if ($this->teacher_user_id) {
            /* если уже назначен учитель */
            if ($this->hour_status == self::OFF) {
                /* и это отмена занятия, то просто снимаем час с расписания
                и у учителя снимаем отметку с расписания о студенте */

                $findStudentSchedule = StudentsSchedule::findOne([
                    'student_user_id' => $this->user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]);
                if ($findStudentSchedule) {
                    $findStudentTimelinesCount = StudentsTimeline::find()->where(['schedule_id' => $findStudentSchedule->schedule_id])->count();
                }
                if (StudentsSchedule::deleteAll([
                    'student_user_id' => $this->user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]) !== false && TeachersSchedule::updateAll([
                        'student_user_id' => null,
                    ], [
                        'teacher_user_id' => $this->teacher_user_id,
                        'student_user_id' => $this->user_id,
                        'week_day'        => $this->week_day,
                        'work_hour'       => $this->work_hour,
                    ]) !== false)
                {
                    if (isset($findStudentTimelinesCount)) {
                        $this->studentUser->user_lessons_available += $findStudentTimelinesCount;
                        $this->studentUser->save();
                    }
                    $transaction->commit();
                    return [
                        'changed' => true,
                        'info' => "OK",
                    ];
                }
            } else {
                /* и это добавление нового часа, то сначала нужно
                проверить что этот час свободен у учителя как в его расписании
                так и в таймлайне у других студентов и только если свободно,
                то можно установить это новый час в расписание студента,
                отметить студента в расписании учителя
                и сгенерировать таймланы */

                /* проверки */
                $CheckFreeSchedule = TeachersSchedule::findOne([
                    'teacher_user_id' => $this->teacher_user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]);
                if (!$CheckFreeSchedule) {
                    return [
                        'changed' => false,
                        'info' => "У учителя который с вами работает нет возможности в это время",
                    ];
                }
                if ($CheckFreeSchedule->student_user_id && $CheckFreeSchedule->student_user_id != $this->user_id) {
                    return [
                        'changed' => false,
                        'info' => "У учителя который с вами работает в это время уже назначен урок с другим учеником (TeachersSchedule)",
                    ];
                }
                $CheckFreeTimeline = StudentsTimeline::find()->where('
                    (teacher_user_id = :teacher_user_id) AND
                    (week_day = :week_day) AND
                    (work_hour = :work_hour) AND
                    (timeline_timestamp >= :now) AND
                    (student_user_id != :student_user_id)
                    ', [
                    'teacher_user_id' => $this->teacher_user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                    'now'             => time(),
                    'student_user_id' => $this->user_id,
                ])->one();
                if ($CheckFreeTimeline) {
                    return [
                        'changed' => false,
                        'info' => "У учителя который с вами работает в это время уже назначен урок с другим учеником (StudentsTimeline)",
                    ];
                }

                //TODO - перед добавлением нового часа

                /* создание расписания */
                /* на всякий случай, сначала попробуем удалить из расписания этот час */
                StudentsSchedule::deleteAll([
                    'student_user_id' => $this->user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]);
                TeachersSchedule::updateAll([
                    'student_user_id' => null,
                ], [
                    'teacher_user_id' => $this->teacher_user_id,
                    'student_user_id' => $this->user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]);

                /* устанавливаем час в расписание */
                $Schedule = new StudentsSchedule();
                $Schedule->student_user_id = $this->user_id;
                $Schedule->week_day = $this->week_day;
                $Schedule->work_hour = $this->work_hour;
                $Schedule->teacher_user_id = $this->teacher_user_id;
                if ($Schedule->save()) {

                    /* отметка студента в расписании учителя*/
                    if (TeachersSchedule::updateAll(['student_user_id' => $this->user_id], [
                        'teacher_user_id' => $this->teacher_user_id,
                        'week_day'        => $this->week_day,
                        'work_hour'       => $this->work_hour,
                    ])) {

                        /* генерация таймлайна */
                        if ($this->studentUser->user_status == Users::STATUS_ACTIVE && $this->studentUser->after_payment_action == 0) {

                            $schedule_for_generate_timeline[$Schedule->week_day][$Schedule->work_hour] = $Schedule->schedule_id;
                            if ($this->generateTimeline($this->date_start_timestamp, $schedule_for_generate_timeline)) {
                                $transaction->commit();
                                return [
                                    'changed' => true,
                                    'info' => "OK",
                                ];
                            }

                        } else {
                            $transaction->commit();
                            return [
                                'changed' => true,
                                'info' => "OK",
                            ];
                        }
                    }
                }
            }
        } else {
            /* если учитель еще не назначен */
            if ($this->hour_status == self::OFF) {
                /* и это отмена занятия, то просто снимаем час с расписания */
                if (StudentsSchedule::deleteAll([
                    'student_user_id' => $this->user_id,
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
                /* и это добавление нового часа, то устанавливаем час в расписание */

                /* на всякий случай, сначала попробуем удалить из расписания этот час */
                StudentsSchedule::deleteAll([
                    'student_user_id' => $this->user_id,
                    'week_day'        => $this->week_day,
                    'work_hour'       => $this->work_hour,
                ]);

                $Schedule = new StudentsSchedule();
                $Schedule->student_user_id = $this->user_id;
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
        }

        /* оибка БД */
        $transaction->rollBack();
        return [
            'changed' => false,
            'info' => "DB error",
        ];
    }

    /**
     * @param integer $start_timestamp
     * @param array $schedule_for_generate_timeline
     * @return int
     */
    public function generateTimeline($start_timestamp, &$schedule_for_generate_timeline)
    {
        if (!$this->teacher_user_id) {
            return [
                'count_lessons_created' => 0,
                'last_lesson_timestamp' => null,
                'last_lesson_date' => null,
                'status' => false,
            ];
        }

        /* попробуем найти последний платеж за уроки для этого учителя что бы получить цену за урок */
        /** @var Payments $payment */
        $payment = Payments::find()
            ->where([
                'teacher_user_id' => $this->teacher_user_id,
                'student_user_id' => $this->studentUser->user_id,
                'order_status' => Payments::STATUS_PAYED,
            ])
            ->orderBy(['order_created' => SORT_DESC])
            ->one();
        if ($payment) {
            $per_hour = $payment->order_amount_usd / $payment->order_count;
        } else {
            $per_hour = 0.00;
        }

        /**/
        $status = true;

        /**/
        $current_timestamp = $start_timestamp;  // UTC(GMT)
        //$finish_timestamp = $start_timestamp + self::generateTimelinePeriod;

        /**/
        $count_errors = 0;
        $count_lessons_created = 0;
        $count_lessons_available = $this->studentUser->user_lessons_available;
        //var_dump($count_lessons_available);exit;
        //var_dump(1);
        //while (($current_timestamp < $finish_timestamp) && ($count_lessons_available > $count_lessons_created)) {
        while ($count_lessons_available > $count_lessons_created) {

            //var_dump(2);
            /**/
            $week_day_gmt = Functions::getDayOfWeek($current_timestamp);
            $work_hour_gmt = intval(date('H', $current_timestamp));

            /* учет таймзоны юзера */
            $tmp = self::dayAndHourFromGmtToTz($week_day_gmt, $work_hour_gmt, $this->user_timezone);
            $week_day_utz = intval($tmp['week_day']);
            $work_hour_utz = intval($tmp['work_hour']);

            /* аварийная остановка цикла в случае зависания  */
            if ($count_errors > 1000) {
                break;
            }

            /* не нужно генерировать таймлайны которые будут в прошедшем времени, или за SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED до начала занятия */
            if ($current_timestamp <= time() + self::SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED) {
                $current_timestamp = $current_timestamp + 3600;
                continue;
            }

            /**/
            //var_dump($week_day . ' -- ' . $work_hour );
            if (isset($schedule_for_generate_timeline[$week_day_gmt][$work_hour_gmt])) {

                //student_user_id, timeline_timestamp
                $test = StudentsTimeline::findOne([
                    'student_user_id' => $this->user_id,
                    'timeline_timestamp' => $current_timestamp,
                ]);


                //var_dump(3);
                if (!$test) {

                    //var_dump(4);
                    /**/
                    //var_dump($week_day . ' - ' . $work_hour );
                    $StudentsTimeline = new StudentsTimeline();
                    $StudentsTimeline->schedule_id = $schedule_for_generate_timeline[$week_day_gmt][$work_hour_gmt];
                    $StudentsTimeline->student_user_id = $this->user_id;
                    $StudentsTimeline->teacher_user_id = $this->teacher_user_id;
                    $StudentsTimeline->week_day = $week_day_gmt;
                    $StudentsTimeline->work_hour = $work_hour_gmt;
                    $StudentsTimeline->timeline = date(SQL_DATE_FORMAT, $current_timestamp);
                    $StudentsTimeline->timeline_timestamp = $current_timestamp;
                    $StudentsTimeline->replacing_for_timeline_timestamp = $StudentsTimeline->timeline_timestamp;
                    $StudentsTimeline->room_hash = md5(uniqid());
                    $StudentsTimeline->is_replacing = StudentsTimeline::NO;
                    $StudentsTimeline->lesson_amount_usd = $per_hour;
                    if (!$StudentsTimeline->save()) {

                        //var_dump($StudentsTimeline->getErrors()); exit;
                        //$count_added_lessons = 0;
                        //return $count_added_lessons;
                        //echo "{$StudentsTimeline->timeline} - fail\n";
                        $count_errors++;
                        $status = false;

                    } else {

                        $count_lessons_created++;
                        //var_dump($StudentsTimeline->getErrors()); exit;
                        TeachersSchedule::updateAll([
                            'student_user_id' => $this->user_id
                        ], [
                            'teacher_user_id' => $this->teacher_user_id,
                            'week_day' => $week_day_gmt,
                            'work_hour' => $work_hour_gmt,
                        ]);

                    }

                }
            }
            $current_timestamp = $current_timestamp + 3600;

        }

        return [
            'count_lessons_created' => $count_lessons_created,
            'last_lesson_timestamp' => isset($StudentsTimeline) ? $StudentsTimeline->timeline_timestamp : null,
            'last_lesson_date' => isset($StudentsTimeline) ? $StudentsTimeline->timeline : null,
            'status' => $status,
        ];
    }

    /**
     * @return array
     */
    public function getScheduleForTimeline()
    {
        /**/
        //$ret = $this->getTableAndWhereField();

        /**/
//        $scheduleQuery = "
//            SELECT
//                schedule_id,
//                week_day,
//                work_hour
//            FROM {$ret['table']}
//            WHERE {$ret['field_user_id']} = :user_id
//            ORDER BY week_day ASC, work_hour ASC";
        $scheduleQuery = "
            SELECT
                schedule_id,
                week_day,
                work_hour,
                teacher_user_id
            FROM {{%students_schedule}}
            WHERE (student_user_id = :user_id)
            AND (teacher_user_id = :teacher_user_id)
            ORDER BY week_day ASC, work_hour ASC";
        $res = Yii::$app->db->createCommand($scheduleQuery, [
            'user_id' => $this->user_id,
            'teacher_user_id' => $this->teacher_user_id
        ])->queryAll();

        $userSchedule = [];
        foreach ($res as $item) {
            $item_week_day  = intval($item['week_day']);
            $item_work_hour = intval($item['work_hour']);
            $userSchedule[$item_week_day][$item_work_hour] = $item['schedule_id'];
        }

        /**/
        return $userSchedule;
    }

    /**
     * @return array
     */
    public function generateTimelinesAfterSetupSchedule()
    {
        /**/
        if (!$this->teacher_user_id) {
            return [
                'status' => false,
                'data'   => 'teacher_user_id is required',
            ];
        }

        /* firstly, check that the student has set a schedule */
        $countTotalHoursInStudentSchedule = $this->checkIsStudentScheduleSet();
        if (!$countTotalHoursInStudentSchedule) {
            return [
                'status' => false,
                'data'   => 'schedule not set',
            ];
        }

        /**/
        $transaction = Yii::$app->db->beginTransaction();

        /* так же удалим все таймлайны студента которые позже date_start c этим учителем, если вдруг они есть */
        $lessons_available = StudentsTimeline::deleteAll('
            (student_user_id = :student_user_id) AND
            (timeline_timestamp > :timeline_timestamp) AND
            (is_introduce_lesson = :NO) AND
            (teacher_user_id = :teacher_user_id)
        ', [
            'student_user_id' => $this->user_id,
            'teacher_user_id' => $this->teacher_user_id,
            'timeline_timestamp' => $this->date_start_timestamp,
            'NO' => StudentsTimeline::NO,
        ]);

        /**/
        $this->studentUser->user_lessons_available += $lessons_available;

        /* а тут нужно запустить модуль генерации таймланв по расписаню. */
        $schedule_for_generate_timeline = $this->getScheduleForTimeline();
        $ret_generate = $this->generateTimeline(
            $this->date_start_timestamp,
            $schedule_for_generate_timeline
        );
        if (!$ret_generate['status']) {
            $transaction->rollBack();
            /* восстановим состояние юзера в случае ошибки, как было до начала этого метода */
            //$this->studentUser->teacher_user_id = null;
            //$this->studentUser->user_status = Users::STATUS_AFTER_PAYMENT;
            //$this->studentUser->save();
            return [
                'status' => false,
                'data'   => 'Some errors during generate timelines',
            ];
        }

        /* сменим статус у юзера */
        //Users::updateAll(['user_status' => Users::STATUS_ACTIVE], ['user_id' => $this->user_id]);
        $this->studentUser->user_last_lesson = $ret_generate['last_lesson_date'];
        $this->studentUser->user_lessons_available -= $ret_generate['count_lessons_created'];
        if ($this->studentUser->user_lessons_available < 0) { $this->studentUser->user_lessons_available = 0; }
        //$this->studentUser->teacher_user_id = $this->teacher_user_id;
        $this->studentUser->user_status = Users::STATUS_ACTIVE;
        $this->studentUser->after_payment_action = Users::NO_ACTION;
        $this->studentUser->save();

        /**/
        $transaction->commit();

        /**/
        $teacher = Users::findIdentity($this->teacher_user_id);
        /** @var \common\models\StudentsTimeline $when_first */
        $when_first = StudentsTimeline::find()
            ->where('(timeline_timestamp >= :now) AND (student_user_id = :student_user_id)', [
                'student_user_id' => $this->user_id,
                'now' => time(),
            ])
            ->orderBy(['timeline_timestamp' => SORT_ASC])
            ->one();

        /**/
        if ($when_first) {
            return [
                'status' => true,
                'data' => [
                    'week_day' => Functions::getTextWeekDay(date('N', $when_first->timeline_timestamp + $this->user_timezone), 'Up_'),
                    'short_date' => date(Yii::$app->params['date_short_format'], $when_first->timeline_timestamp + $this->user_timezone),
                    'long_date' => date(Yii::$app->params['date_format'], $when_first->timeline_timestamp + $this->user_timezone),
                    'short_time' => date(Yii::$app->params['time_short_format'], $when_first->timeline_timestamp + $this->user_timezone),
                    'user_display_name' => $teacher->_user_display_name,
                    'user_photo' => $teacher->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png'),
                ],
            ];
        } else {
            return [
                'status' => true,
                'data' => [
                    'week_day' => 'NULL',
                    'short_date' => 'NULL',
                    'long_date' => 'NULL',
                    'short_time' => 'NULL',
                    'user_display_name' => $teacher->_user_display_name,
                    'user_photo' => $teacher->getProfilePhotoForWeb('/assets/xsmart-min/images/no_photo.png'),
                ],
            ];
        }

    }

    /**
     * @param \common\models\Users $student
     * @param int $override_date_start_timestamp
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function updateStudentsTimelineAfterPayOrByCron($student, $override_date_start_timestamp=null)
    {
        $model = new StudentsScheduleForm();

        if ($model->load([$model->formName() => [
                'user_id'         => $student->user_id,
                'user_type'       => $student->user_type,
                'user_timezone'   => $student->user_timezone,
                'teacher_user_id' => $student->teacher_user_id,
            ]]) && $model->validate()) {

            /**/
            $transaction = Yii::$app->db->beginTransaction();


            /**/
            if ($override_date_start_timestamp && $override_date_start_timestamp >= time()) {
                $date_start_timestamp = Functions::getTimestampBeginOfDayByTimestamp($override_date_start_timestamp);
            } else {

                /** @var \common\models\StudentsTimeline $startFrom */
                $startFrom = StudentsTimeline::find()
                    ->where(['student_user_id' => $student->user_id])
                    ->orderBy(['timeline_timestamp' => SORT_DESC])
                    ->one();

                if ($startFrom) {
                    $date_start_timestamp = Functions::getTimestampBeginOfDayByTimestamp($startFrom->timeline_timestamp);
                } else {
                    $date_start_timestamp = Functions::getTimestampBeginOfDayByTimestamp(time());
                }

            }

            /**/
            $schedule = $model->getScheduleForTimeline();
            $ret_generate = $model->generateTimeline(
                $date_start_timestamp,
                $schedule
            );

            $student->user_lessons_available -= $ret_generate['count_lessons_created'];
            $student->user_last_lesson = $ret_generate['last_lesson_date'];
            //echo "{$ret_generate['count_lessons_created']}\n";
            if ($student->user_lessons_available < 0) { $student->user_lessons_available = 0; }
            if (!$student->save()) {
                $transaction->rollBack();
            }
            $transaction->commit();
        }
    }

    /**
     * @param \common\models\Users $Student
     * @param \common\models\StudentsTimeline $StudentTimeline
     * @param \common\models\TeachersSchedule $TeacherSchedule
     * @param int $is_replacing
     * @param int $new_timestamp_gmt_for_replacing
     * @return array
     */
    public static function moveLesson($Student, $StudentTimeline, $TeacherSchedule, $is_replacing, $new_timestamp_gmt_for_replacing)
    {
        $transaction = Yii::$app->db->beginTransaction();

        if ($is_replacing) {
            /* если это нерегулярный перенос занятия */

            $week_day_gmt = Functions::getDayOfWeek($new_timestamp_gmt_for_replacing);
            $work_hour_gmt = intval(date('H', $new_timestamp_gmt_for_replacing));

            //var_dump($week_day_gmt);var_dump($work_hour_gmt);
            /**/
            if (($TeacherSchedule->week_day != $week_day_gmt) || ($TeacherSchedule->work_hour != $work_hour_gmt)) {
                return [
                    'status' => false,
                    'info'   => 'Something wrong with param new_timestamp_gmt_for_replacing',
                ];
            }

            /**/
            $test = StudentsTimeline::findOne([
                'timeline_timestamp' => $new_timestamp_gmt_for_replacing,
                'student_user_id' => $Student->user_id,
            ]);
            if ($test) {
                return [
                    'status' => false,
                    'info'   => 'У вас уже есть занятие в это время',
                    'info_user'   => 'У вас уже есть занятие в это время',
                ];
            }

            /**/
            $StudentTimeline->timeline_timestamp = $new_timestamp_gmt_for_replacing;
            $StudentTimeline->timeline = date(SQL_DATE_FORMAT, $new_timestamp_gmt_for_replacing);
            $StudentTimeline->is_replacing = StudentsTimeline::YES;

            if (!$StudentTimeline->save()) {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info'   => 'DB error on save StudentTimeline',
                ];
            }

            /**/
            $transaction->commit();
            return [
                'status' => true,
                'data' => 'Успешно перенесено',
            ];

        } else {
            /* если регулярный перенос */
            $oldStudentSchedule = StudentsSchedule::findOne([
                'schedule_id' => $StudentTimeline->schedule_id,
                'student_user_id' => $Student->user_id,
            ]);
            if (!$oldStudentSchedule) {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info'   => "oldStudentSchedule not found by schedule_id {$StudentTimeline->schedule_id}",
                ];
            }

            $newStudentSchedule = new StudentsSchedule();
            $newStudentSchedule->student_user_id = $Student->user_id;
            $newStudentSchedule->week_day = $TeacherSchedule->week_day;
            $newStudentSchedule->work_hour = $TeacherSchedule->work_hour;
            if ($newStudentSchedule->save()) {

                $count_lesson_available = StudentsTimeline::deleteAll([
                    'schedule_id' => $oldStudentSchedule->schedule_id,
                    'student_user_id' => $Student->user_id,
                ]);
                $upd2 = TeachersSchedule::updateAll(['student_user_id' => null], [
                    'student_user_id' => $Student->user_id,
                    'week_day' => $oldStudentSchedule->week_day,
                    'work_hour' => $oldStudentSchedule->work_hour,
                ]);

                if ($oldStudentSchedule->delete() && $count_lesson_available) {

                    $Student->user_lessons_available = $count_lesson_available;

                    if ($Student->save()) {
                        $transaction->commit();
                    }

                    StudentsScheduleForm::updateStudentsTimelineAfterPayOrByCron($Student, time());

                    return [
                        'status' => true,
                        'data'   => 'Успешно перенесено',
                    ];

                }
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info'   => 'DB error on delete oldStudentSchedule or StudentsTimeline::updateAll',
                ];

            } else {
                $transaction->rollBack();
                return [
                    'status' => false,
                    'info'   => 'DB error on save newStudentSchedule',
                ];
            }
        }

        $transaction->rollBack();
        return [
            'status' => false,
            'info'   => 'Something wrong.',
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
        /* все возможные дни за две недели */
        $data = [0 => []];
        for ($week_day=1; $week_day<=7; $week_day++) {
            for ($work_hour=0; $work_hour<=23; $work_hour++) {
                $data[$week_day][$work_hour] = ['status' => 2, 'users' => null];
            }
        }

        /* выберем дни куда можно перенести уроки (там где у учителей есть время) */
        $teacherScheduleQuery = "
            SELECT
                string_agg(teacher_user_id::VARCHAR , ',') as available_for_teachers_ids,
                student_user_id,
                week_day,
                work_hour
            FROM {{%teachers_schedule}}
            --WHERE teacher_user_id = :teacher_user_id
            --WHERE teacher_user_id IN (" . implode(', ', [$this->teacher_user_id, 8]) . ")
            WHERE teacher_user_id IN (SELECT DISTINCT teacher_user_id FROM {{%students_schedule}} WHERE student_user_id = :student_user_id)
            AND ((student_user_id IS NULL) OR (student_user_id = :student_user_id))
            GROUP BY week_day, work_hour, student_user_id
            ORDER BY week_day ASC, work_hour ASC";
        $res = Yii::$app->db->createCommand($teacherScheduleQuery, [
            //'teacher_user_id' => $teacher,//$this->teacher_user_id,
            'student_user_id' => $this->user_id,
        ])->queryAll();

        /**/
        foreach ($res as $item) {
            $item_week_day = intval($item['week_day']);
            $item_work_hour = intval($item['work_hour']);

            /* учет таймзоны юзера */
            $tmp = self::dayAndHourFromGmtToTz($item_week_day, $item_work_hour, $this->user_timezone);
            $item_week_day = intval($tmp['week_day']);
            $item_work_hour = intval($tmp['work_hour']);

            if (isset($data[$item_week_day][$item_work_hour])) {
                $data[$item_week_day][$item_work_hour] = [
                    'status' => 0,
                    'users' => null,
                    'available_for_teachers_ids' => $item['available_for_teachers_ids'],
                    //'users_json' => [['user_name' => '', 'user_id' => $item['student_user_id']]],
                ];
            }
        }
        //var_dump($data);

        /* дни где уже студент имеет назначенные уроки */
        $userTimelineQuery = "
                SELECT
                    t1.timeline_id,
                    t1.schedule_id,
                    t1.week_day,
                    t1.work_hour,
                    t1.timeline,
                    t1.timeline_timestamp,
                    t1.student_user_id,
                    t1.teacher_user_id,
                    t3.user_first_name,
                    t3.user_last_name
                    --, t2.user_first_name
                FROM {{%students_timeline}} as t1
                INNER JOIN {{%users}} as t3 ON t1.teacher_user_id = t3.user_id
                WHERE (t1.student_user_id = :user_id)
                AND (t1.teacher_user_id IS NOT NULL)
                AND (t1.timeline_timestamp > :now)
                ORDER BY timeline_timestamp ASC";

        $res = Yii::$app->db->createCommand($userTimelineQuery, [
            'user_id' => $this->user_id,
            'now' => time(),
        ])->queryAll();

        /**/
        foreach ($res as $item) {
            $used[$item['timeline_timestamp']]['timeline_id'] = $item['timeline_id'];
            $used[$item['timeline_timestamp']]['schedule_id'] = $item['schedule_id'];
            $used[$item['timeline_timestamp']]['teacher_user_id'] = $item['teacher_user_id'];
            $used[$item['timeline_timestamp']]['teacher_display_name'] = Users::getDisplayName($item['user_first_name'], $item['user_last_name']);
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

                    if ($week_day < $now_w || $k_h <= $now_h) {
                        if ($data_local[$week_day][$k_h]['status'] < 2) {
                            $data_local[$week_day][$k_h]['status'] = 2;
                            unset($data_local[$week_day][$k_h]['available_for_teachers_ids']);
                            //$data_local[$week_day][$k_h]['users'] = 'Not available';
                        }
                    }
                    //if ($now > $date_start )

                }
                //var_dump($data_local);
            }
            //}
            for ($hh=0; $hh<=23; $hh++) {
                if ($now + self::CAN_MOVE_LESSONS_NOT_LATER_THAN_HOURS * 60 * 60 > $date_start + $hh * 60 * 60) {
                    //var_dump($hh);
                    if (!isset($data_local[$week_day][$hh]['status']) || $data_local[$week_day][$hh]['status'] == 0) {
                        $data_local[$week_day][$hh]['status'] = 2;
                        unset($data_local[$week_day][$hh]['available_for_teachers_ids']);
                    }
                }
            }

            /**/
            if (isset($data_local[$week_day])) {
                foreach ($data_local[$week_day] as $kh => $kv) {
                    $data_local[$week_day][$kh]['date'] = $date_start + $kh * 60 * 60 - $tz;
                    if (isset($used[$data_local[$week_day][$kh]['date']])) {
                        $data_local[$week_day][$kh]['status'] = 1;
                        unset($data_local[$week_day][$kh]['available_for_teachers_ids']);
                        //$data_local[$week_day][$kh]['users'] = $used[$data_local[$week_day][$kh]['date']];
                        $data_local[$week_day][$kh]['timeline_id'] = $used[$data_local[$week_day][$kh]['date']]['timeline_id'];
                        $data_local[$week_day][$kh]['schedule_id'] = $used[$data_local[$week_day][$kh]['date']]['schedule_id'];
                        $data_local[$week_day][$kh]['teacher_user_id'] = $used[$data_local[$week_day][$kh]['date']]['teacher_user_id'];
                        $data_local[$week_day][$kh]['teacher_display_name'] = $used[$data_local[$week_day][$kh]['date']]['teacher_display_name'];
                        $data_local[$week_day][$kh]['additional_class'] = 'lesson_scheduled';
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







    /* =========================== OFF =========================== */

//    /**
//     * @return array
//     */
//    public function findAvailableTeachers()
//    {
//        /* firstly, check that the student has set a schedule */
//        $countTotalHoursInStudentSchedule = $this->checkIsStudentScheduleSet();
//        if (!$countTotalHoursInStudentSchedule) {
//            return [
//                'status' => false,
//                'data'   => 'schedule not set',
//            ];
//        }
//
//        /* secondly, try find any teachers for student schedule */
//        $query = "
//            SELECT
//              founded_teachers.teacher_user_id,
//              founded_teachers.number_of_matching_hours,
//              t4.user_full_name,
//              t4.user_email,
//              t4.user_photo,
//              t4.user_birthday,
//              t4.user_first_name,
//              t4.user_gender,
//              t4.user_additional_info,
//              t4.admin_notice,
//              t4.user_music_genres,
//              t4.user_youtube_video,
//              t4.user_local_video
//            FROM sm_users as t4 INNER JOIN
//            (SELECT
//                --t1.week_day,
//                --t1.work_hour,
//                --t3.timeline_id,
//                --t3.student_user_id,
//                --t2.*
//                t2.teacher_user_id,
//                count(t2.teacher_user_id) as number_of_matching_hours
//              FROM sm_students_schedule AS t1
//                LEFT JOIN sm_teachers_schedule AS t2
//                  ON (t1.week_day = t2.week_day)
//                     AND (t1.work_hour = t2.work_hour)
//                     AND ((t2.student_user_id IS NULL) OR (t2.student_user_id = :student_user_id))
//                LEFT JOIN sm_students_timeline AS t3
//                  ON (t1.week_day = t3.week_day)
//                     AND (t1.work_hour = t3.work_hour)
//                     AND (t3.teacher_user_id = t2.teacher_user_id)
//                     AND (t3.student_user_id != :student_user_id)
//                     AND (t3.timeline >= :date_start)
//                     AND (t3.is_replacing > 0)
//              WHERE t1.student_user_id = :student_user_id
//              AND t3.timeline_id IS NULL  -- если это поле не нулл, значит у кого то (t3.student_user_id) есть перенос на этот час у этого учителя (t3.teacher_user_id)
//            GROUP BY t2.teacher_user_id) as founded_teachers
//            ON founded_teachers.teacher_user_id = t4.user_id
//            ORDER BY founded_teachers.number_of_matching_hours DESC, t4.user_full_name ASC
//            --WHERE founded_teachers.number_of_matching_hours = :countTotalHoursInStudentSchedule
//            --LIMIT 10
//        ";
//        /* этот запрос выберет всех учителей */
//
//        $res = Yii::$app->db->createCommand($query, [
//            'student_user_id' => $this->user_id,
//            'date_start'      => $this->date_start,
//        ])->queryAll();
//
//        $data = [];
//        foreach ($res as $item) {
//            if ($item['user_birthday']) {
//                $item['user_age'] = Functions::ru_string_age(Users::staticGetAge($item['user_birthday']));
//            } else {
//                $item['user_age'] = 'Неизвестен';
//            }
//            $item['user_additional_info'] = nl2br($item['user_additional_info']);
//            $item['admin_notice'] = nl2br($item['admin_notice']);
//
//            /* youtube */
//            if ($item['user_youtube_video']) {
//                if (!$item['user_youtube_video']) {
//                    $item['user_youtube_video'] = Yii::$app->params['default_teacher_youtube_video'];
//                }
//                $youtube_id = ($item['user_youtube_video'])
//                    ? Functions::getYoutubeVideoID($item['user_youtube_video'])
//                    : null;
//                $item['youtube_image_webp'] = "https://i.ytimg.com/vi_webp/{$youtube_id}/maxresdefault.webp";
//                $item['youtube_image_jpg'] = "https://i.ytimg.com/vi/{$youtube_id}/maxresdefault.jpg";
//                $item['youtube_video_id'] = $youtube_id;
//            } else {
//                $item['user_youtube_video'] = null;
//                $item['youtube_image_webp'] = null;
//                $item['youtube_image_jpg'] = null;
//                $item['youtube_video_id'] = null;
//            }
//
//            /**/
//            $item['user_photo'] = ($item['user_photo'])
//                ? Yii::$app->params['profileDirWeb'] . "/" . $item['user_photo'] . "?rnd=" . mt_rand(1, 10000000)
//                : '/assets/smartsing-min/images/no_photo.png';
//
//            /**/
//            $item['user_local_video'] = ($item['user_local_video'])
//                ? Yii::$app->params['profileDirWeb'] . "/" . $item['user_local_video'] . "?rnd=" . mt_rand(1, 10000000)
//                : null;
//
//            /**/
//            $tmp = unserialize($item['user_music_genres']);
//            $tmp2 = [];
//            if (is_array($tmp)) {
//                foreach ($tmp as $k => $v) {
//                    $tmp2[] = Users::$_music_genres[$k];
//                }
//            }
//            $item['user_music_genres'] = implode(', ', $tmp2);
//            if ($item['number_of_matching_hours'] == $countTotalHoursInStudentSchedule) {
//                $data['full_match'][] = $item;
//            } else {
//                $data['partial_match'][] = $item;
//            }
//        }
//
//        return [
//            'status' => true,
//            'data'   => $data,
//        ];
//    }
//
//    /**
//     * @return array
//     */
//    public function getAvailableDatesForChangingLessonDate()
//    {
//        $query = "
//            SELECT
//              t1.week_day,
//              t1.work_hour,
//              t1.teacher_user_id
//            FROM sm_teachers_schedule as t1
//            LEFT JOIN sm_students_timeline as t2
//              ON t1.teacher_user_id = t2.teacher_user_id
//              AND t1.work_hour = t2.work_hour
//              AND t1.week_day = t2.week_day
//              AND t2.timeline_timestamp > :now
//            WHERE t1.teacher_user_id = :teacher_user_id
//            AND t2.timeline_id IS NULL
//            AND t1.student_user_id IS NULL
//            GROUP BY t1.week_day, t1.work_hour, t1.teacher_user_id
//            ORDER BY t1.week_day ASC, t1.work_hour ASC
//        ";
//
//        $res = Yii::$app->db->createCommand($query, [
//            'teacher_user_id' => $this->teacher_user_id,
//            'now'             => time(),
//        ])->queryAll();
//
//        //$today_week_day = 3;
//        $today_week_day = date('N');
//        $ret = [];
//        if (is_array($res)) {
//
//            foreach ($res as $v) {
//                $ind = intval($v['week_day'] - $today_week_day);
//                if ($ind < 0) { $ind = $ind + 7; }
//                $ind += 1;
//                //var_dump($ind);
//                $ret[$ind]['week_day_text'] = Functions::getTextWeekDay($v['week_day']);
//                $ret[$ind]['available'] = true;
//                $ret[$ind]['week_day'] = $v['week_day'];
//                $ret[$ind]['work_hour'] = $v['work_hour'];
//                $ret[$ind]['teacher_user_id'] = $v['teacher_user_id'];
//                if (!isset($ret[$ind]['available_time'])) {
//                    $ret[$ind]['available_time'] = [];
//                }
//                array_push($ret[$ind]['available_time'], $v['work_hour']);
//            }
//        }
//
//        for ($i=1; $i<=7; $i++) {
//            if (!isset($ret[$i])) {
//                //$ind += 1;
//                $ind = $i + $today_week_day - 1;
//                if ($ind > 7) { $ind = $ind - 7; }
//                $ret[$i]['week_day'] = $ind;
//                $ret[$i]['week_day_text'] = Functions::getTextWeekDay($ind);
//                $ret[$i]['available'] = false;
//            }
//        }
//
//        ksort($ret);
//
//        for ($i=1; $i<=7; $i++) {
//            array_push($ret, $ret[$i]);
//        }
//
//        $timestamp = strtotime("-1 day", time());
//        foreach ($ret as $k=>$v) {
//            $timestamp = strtotime("+1 day", $timestamp);
//            $ret[$k]['date'] = date(Yii::$app->params['date_format'], $timestamp);
//            $ret[$k]['display_text'] = $ret[$k]['week_day_text'] . " " . date(Yii::$app->params['date_short_format'], $timestamp);
//        }
//
//
//        //var_dump($ret); exit;
//        return $ret;
//    }
//
//    /**
//     * @param int $student_user_id
//     * @return array|\yii\db\ActiveRecord[]
//     */
//    public static function getStudentsTimelineWhichCanChange($student_user_id)
//    {
//        return StudentsTimeline::find()
//            ->where([
//                'student_user_id' => $student_user_id,
//            ])
//            ->andWhere('timeline_timestamp > :now', [
//                'now' => time() + self::SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED,
//            ])
//            ->orderBy(['timeline_timestamp' => SORT_ASC])
//            ->all();
//    }
//
//    /**
//     * @return array
//     */
//    public function setTeacherForStudent()
//    {
//        /**/
////        if ($this->studentUser->user_lessons_available <= 0) {
////            return [
////                'status' => false,
////                'data'   => 'У вас нет больше оплаченных уроков.',
////            ];
////        }
//
//        /**/
//        if (!$this->teacher_user_id) {
//            return [
//                'status' => false,
//                'data'   => 'teacher_user_id is required',
//            ];
//        }
//
//        $transaction = Yii::$app->db->beginTransaction();
//
//        /* firstly, check that the student has set a schedule */
//        $countTotalHoursInStudentSchedule = $this->checkIsStudentScheduleSet();
//        if (!$countTotalHoursInStudentSchedule) {
//            $transaction->rollBack();
//            return [
//                'status' => false,
//                'data'   => 'schedule not set',
//            ];
//        }
//
//
//        /* secondly, check that teacher schedule match with student schedule */
//        $query = "
//        SELECT
//            t2.teacher_user_id,
//            count(t2.teacher_user_id) as number_of_matching_hours
//          FROM sm_students_schedule AS t1
//            LEFT JOIN sm_teachers_schedule AS t2
//              ON (t1.week_day = t2.week_day)
//                 AND (t1.work_hour = t2.work_hour)
//                 AND ((t2.student_user_id IS NULL) OR (t2.student_user_id = :student_user_id))
//            LEFT JOIN sm_students_timeline AS t3
//              ON (t1.week_day = t3.week_day)
//                 AND (t1.work_hour = t3.work_hour)
//                 AND (t3.teacher_user_id = t2.teacher_user_id)
//                 AND (t3.student_user_id != :student_user_id)
//                 AND (t3.timeline >= :date_start)
//                 AND (t3.is_replacing > 0)
//          WHERE t1.student_user_id = :student_user_id
//          AND t2.teacher_user_id = :teacher_user_id
//          AND t3.timeline_id IS NULL  -- если это поле не нулл, значит у кого то (t3.user_id) есть перенос на этот час у этого учителя (t3.teacher_user_id)
//        GROUP BY t2.teacher_user_id
//        HAVING count(t2.teacher_user_id) = :countTotalHoursInStudentSchedule
//        ";
//
//        $res = Yii::$app->db->createCommand($query, [
//            'student_user_id' => $this->user_id,
//            'teacher_user_id' => $this->teacher_user_id,
//            'date_start'      => $this->date_start,
//            'countTotalHoursInStudentSchedule' => $countTotalHoursInStudentSchedule,
//        ])->queryAll();
//
//        if (!is_array($res) || !sizeof($res)) {
//            $transaction->rollBack();
//            return [
//                'status' => false,
//                'data'   => 'Something going wrong. Teacher schedule not match with you schedule',
//            ];
//        }
//
//
//        /* third, set that teacher for student, and mark teacher schedule with this student  */
//        /* сначала сбросим студента со всех учительских часов */
//        TeachersSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
//        /* так же удалим все таймлайны студента */
//        $lessons_available = StudentsTimeline::deleteAll(['student_user_id' => $this->user_id]);
//        /* если студент уже был с назначенным учителем, то нужно у этого старого учителя сбросить методиста в null */
//        if ($this->studentUser->teacher_user_id) {
//            $prevTeacher = $this->studentUser->getTeacherForThisUser();
//            if ($prevTeacher) {
//                $prevTeacher->methodist_user_id = null;
//                $prevTeacher->save();
//            }
//        }
//        /* теперь установим студента на часы выбранного учителя согласно расписанию */
//        $res_student = StudentsSchedule::findAll([
//            'student_user_id' => $this->user_id,
//        ]);
//        foreach ($res_student as $item) {
//            TeachersSchedule::updateAll(['student_user_id' => $this->user_id], [
//                'teacher_user_id' => $this->teacher_user_id,
//                'week_day'        => $item->week_day,
//                'work_hour'       => $item->work_hour,
//            ]);
//        }
//        /* и теперь зададим учителя для юзера */
//        Users::updateAll(['teacher_user_id' => $this->teacher_user_id], ['user_id' => $this->user_id]);
//        /* а тут нужно запустить модуль генерации таймланв по расписаню. */
//        /* в таблицу таймлайнов добавить поле тичер-ид тоже */
//        $schedule_for_generate_timeline = $this->getScheduleForTimeline();
//
//
//        /**/
//        $this->studentUser->user_lessons_available += $lessons_available;
//
//        $ret_generate = $this->generateTimeline(
//            $this->date_start_timestamp,
//            $schedule_for_generate_timeline
//        );
//
//        if (!$ret_generate['status']) {
//            $transaction->rollBack();
//
//            /* восстановим состояние юзера в случае ошибки, как было до начала этого метода */
//            //$this->studentUser->teacher_user_id = null;
//            //$this->studentUser->user_status = Users::STATUS_AFTER_PAYMENT;
//            //$this->studentUser->save();
//
//            return [
//                'status' => false,
//                'data'   => 'Some errors during generate timelines',
//            ];
//        }
//
//        /* сменим статус у юзера */
//        //Users::updateAll(['user_status' => Users::STATUS_ACTIVE], ['user_id' => $this->user_id]);
//        $this->studentUser->user_last_lesson = $ret_generate['last_lesson_date'];
//        $this->studentUser->user_lessons_available -= $ret_generate['count_lessons_created'];
//        //if ($this->studentUser->user_lessons_available < 0) { $this->studentUser->user_lessons_available = 0; }
//        $this->studentUser->teacher_user_id = $this->teacher_user_id;
//        $this->studentUser->user_status = Users::STATUS_ACTIVE;
//        $this->studentUser->save();
//        /* новому учителю этого студента назначаем того же мотодиста что и у студента */
//        $newTeacher = $this->studentUser->getTeacherForThisUser();
//        if ($newTeacher) {
//            $newTeacher->methodist_user_id = $this->studentUser->methodist_user_id;
//            $newTeacher->save();
//        }
//
//
//        /**/
//        $transaction->commit();
//
//        $teacher = Users::findIdentity($this->teacher_user_id);
//        /** @var \common\models\StudentsTimeline $when_first */
//        $when_first = StudentsTimeline::find()
//            ->where('(timeline_timestamp >= :now) AND (student_user_id = :student_user_id)', [
//                'student_user_id' => $this->user_id,
//                'now' => time(),
//            ])
//            ->orderBy(['timeline_timestamp' => SORT_ASC])
//            ->one();
//
//        /**/
//        if ($when_first) {
//            return [
//                'status' => true,
//                'data' => [
//                    'week_day' => Functions::getTextWeekDay(date('N', $when_first->timeline_timestamp + $this->user_timezone), 'UpIn_'),
//                    'short_date' => date(Yii::$app->params['date_short_format'], $when_first->timeline_timestamp + $this->user_timezone),
//                    'short_time' => date(Yii::$app->params['time_short_format'], $when_first->timeline_timestamp + $this->user_timezone),
//                    'user_first_name' => $teacher->user_first_name,
//                    'user_full_name' => $teacher->user_full_name,
//                ],
//            ];
//        } else {
//            return [
//                'status' => true,
//                'data' => [
//                    'week_day' => 'NULL',
//                    'short_date' => 'NULL',
//                    'short_time' => 'NULL',
//                    'user_first_name' => $teacher->user_first_name,
//                    'user_full_name' => $teacher->user_full_name,
//                ],
//            ];
//        }
//    }
//
//    /**
//     * @return array
//     */
//    public function unsetTeacherForStudent()
//    {
//        $transaction = Yii::$app->db->beginTransaction();
//
//        /* сначала сбросим студента со всех учительских часов */
//        TeachersSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
//
//        /* так же удалим все таймлайны студента */
//        StudentsTimeline::deleteAll(['student_user_id' => $this->user_id]);
//
//        /* и теперь отменим учителя для юзера */
//        Users::updateAll(['teacher_user_id' => null], ['user_id' => $this->user_id]);
//
//        /**/
//        $transaction->commit();
//
//        /**/
//        return [
//            'status' => true,
//            'data'   => "OK",
//        ];
//    }
//
//    /**
//     * @param \common\models\Users $Student
//     * @param \common\models\StudentsTimeline $CurrentTimeline
//     * @return array
//     */
//    public static function getAvailableDatesForChangingSchedule($Student, $CurrentTimeline)
//    {
//        /**/
//        $TeacherSchedule = TeachersSchedule::find()
//            ->where(['teacher_user_id' => $Student->teacher_user_id])
//            ->andWhere('student_user_id IS NULL')
//            ->orderBy(['week_day' => SORT_ASC, 'work_hour' => SORT_ASC])
//            ->asArray()
//            ->all();
//        $availableSchedule = [];
//        foreach ($TeacherSchedule as $item) {
//            $item_week_day  = intval($item['week_day']);
//            $item_work_hour = intval($item['work_hour']);
//            $availableSchedule[$item_week_day][$item_work_hour] = $item['schedule_id'];
//        }
//        $TeacherTimelines = StudentsTimeline::find()
//            ->select(['week_day', 'work_hour'])
//            ->where([
//                'teacher_user_id' => $Student->teacher_user_id,
//                'is_replacing' => StudentsTimeline::NO, //??? потом отдельно выбрать таймлайны где is_replacing = YES и не показывать именно эти даты
//            ])
//            ->andWhere('timeline_timestamp > :now', [
//                'now' => time(),
//            ])
//            ->groupBy(['week_day', 'work_hour'])
//            ->all();
//        /** @var \common\models\StudentsTimeline $timeline */
//        foreach ($TeacherTimelines as $timeline) {
//            unset($availableSchedule[$timeline->week_day][$timeline->work_hour]);
//        }
//
//        /* отдельно выбрать таймлайны где is_replacing = YES и не показывать именно эти даты (в цикле while (($current_timestamp < $finish_timestamp)) )*/
//        $unset_available_because_replacing = [];
//        $TeacherTimelines2 = StudentsTimeline::find()
//            //->select()
//            ->where([
//                'teacher_user_id' => $Student->teacher_user_id,
//                'is_replacing' => StudentsTimeline::YES, //??? потом
//            ])
//            ->andWhere('timeline_timestamp > :now', [
//                'now' => time(),
//            ])
//            ->all();
//        /** @var \common\models\StudentsTimeline $timeline2 */
//        foreach ($TeacherTimelines2 as $timeline2) {
//            $unset_available_because_replacing[$timeline2->timeline_timestamp] = $timeline2->timeline_timestamp;
//        }
//
//        //var_dump($availableSchedule);
//        //echo "<hr />";
//
//        /* convert available_schedule to user timezone */
//        $converted_availableSchedule = [];
//        foreach ($availableSchedule as $day => $value_day) {
//            foreach ($value_day as $hour => $value_hour) {
//                $tmp2 = self::dayAndHourFromGmtToTz($day, $hour, $Student->user_timezone);
//                $conv_week_day = intval($tmp2['week_day']);
//                $conv_work_hour = intval($tmp2['work_hour']);
//                $converted_availableSchedule[$conv_week_day][$conv_work_hour] = $value_hour;
//            }
//        }
//
//        //var_dump($converted_availableSchedule);
//        //exit;
//
//        /**/
//        $ret['unselected'] = [
//            'print_day' => 'Выберите дату',
//            'full_date_gmt' => 'unselected',
//            'full_date_utz' => 'unselected',
//            'hours' => ['unselected' => ['schedule_id' => 'unselected', 'print_hour' => 'Сначала выберите время']],
//        ];
//
//
//        $current_timestamp = Functions::getTimestampBeginOfDayByTimestamp(time()); // UTC(GMT)
//        $finish_timestamp = $current_timestamp + self::generateTimelinePeriod;
//
//        while (($current_timestamp < $finish_timestamp)) {
//
//            $week_day_gmt = Functions::getDayOfWeek($current_timestamp);
//            $work_hour_gmt = intval(date('H', $current_timestamp));
//
//            /* учет таймзоны юзера */
//            $tmp = self::dayAndHourFromGmtToTz($week_day_gmt, $work_hour_gmt, $Student->user_timezone);
//            $week_day_utz = intval($tmp['week_day']);
//            $work_hour_utz = intval($tmp['work_hour']);
//
//            /* не нужно показывать таймлайны которые будут в прошедшем времени, или за SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED до начала занятия */
//            if ($current_timestamp <= time() + self::SECONDS_BEFORE_LESSON_THAT_CAN_CHANGED) {
//                $current_timestamp = $current_timestamp + 3600;
//                continue;
//            }
//
//            /* не нужно показывать юзеру ту дату которую он как раз и хочет перенести */
//            if (($CurrentTimeline->week_day == $week_day_gmt) && ($CurrentTimeline->work_hour == $work_hour_gmt)) {
//                $current_timestamp = $current_timestamp + 3600;
//                continue;
//            }
//
//            /* не нужно показывать таймлайны которые у преподавателя отмечены как реплейинг */
//
//            /**/
//            $hours = [];
//            if (isset($converted_availableSchedule[$week_day_utz][$work_hour_utz])) {
//                $current_timestamp_utz = $current_timestamp + $Student->user_timezone;
//                //$tmstmp = $current_timestamp;
//                $npp_key = date('d_m', $current_timestamp_utz);
//                if (!isset($ret[$npp_key])) {
//                    /**/
//                    $hours['unselected'] = ['schedule_id' => 'unselected', 'print_hour' => 'Выберите время'];
//                    foreach ($converted_availableSchedule[$week_day_utz] as $k=>$v) {
//                        $h_key = $k . "_00";
//                        if ($k < 10) { $k_prn = "0{$k}"; } else { $k_prn = $k; }
//                        $timestamp_gmt = (Functions::getTimestampBeginOfDayByTimestamp($current_timestamp_utz) + $k*3600) - $Student->user_timezone;
//                        if (!isset($unset_available_because_replacing[$timestamp_gmt])) {
//                            $hours[$h_key] = [
//                                'schedule_id' => $v,
//                                'print_hour' => $k_prn . ':00',
//                                'timestamp_utz' => (Functions::getTimestampBeginOfDayByTimestamp($current_timestamp_utz) + $k * 3600),
//                                'timestamp_gmt' => $timestamp_gmt,
//                                'date_gmt' => date('Y-m-d H:i:s', $timestamp_gmt),
//                            ];
//                        }
//
//                    }
//                    /**/
//                    if (sizeof($hours) > 1) {
//                        $ret[$npp_key] = [
//                            'print_day' => Functions::getTextWeekDay($week_day_utz, 'Up_') . ' ' . date('d.m', $current_timestamp_utz),
//                            'full_date_gmt' => date('Y-m-d 00:00:00', $current_timestamp),
//                            'full_date_utz' => date('Y-m-d 00:00:00', $current_timestamp_utz),
//                            'hours' => $hours
//                        ];
//                    }
//                }
//            }
//
//            /**/
//            $current_timestamp = $current_timestamp + 3600;
//        }
//
//        //var_dump($ret); exit;
//        return $ret;
//    }
}
