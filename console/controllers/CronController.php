<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use common\helpers\Functions;
use common\models\Users;
use common\models\MethodistTimeline;
use common\models\StudentsTimeline;
use common\models\TeachersSchedule;
use frontend\models\schedule\MethodistScheduleForm;
use frontend\models\schedule\StudentsScheduleForm;

/**
 * Site controller
 */
class CronController extends Controller
{

    public $task_start;
    public $task_finish;
    public $task_log;

    /**
     * При разработке консольного приложения принято использовать код возврата.
     * Принято, код 0 (ExitCode::OK) означает, что команда выполнилась удачно.
     * Если команда вернула код больше нуля, то это говорит об ошибке.
     */

    /**
     * ExitCode::NOINPUT;
     * ExitCode::DATAERR;
     * ExitCode::OK;
     */

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->task_start = date(SQL_DATE_FORMAT);
        $this->task_log = "In progress...";
        echo "\n";
        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function setTaskFinish()
    {
        $this->task_finish = date(SQL_DATE_FORMAT);
        return $this->task_finish;
    }

    /**
     * Очистка (установка в null) поля schedule_id в таблицах таймлайнов для строк у которых дата уже прошла
     * Запускать в 00:00 каждый день
     * пример строки в крон файле: "0 0 * * * /var/www/smartsing-member/yii cron/delete-schedule-id-for-old-timelines"
     * @return int
     */
    public function actionDeleteScheduleIdForOldTimelines()
    {
        $now = time();

        StudentsTimeline::updateAll(['schedule_id' => null], 'timeline_timestamp < :now', [
            'now' => $now,
        ]);

        echo "OK\n";

        return ExitCode::OK;
    }















    /* =========================== OFF =========================== */

//    /**
//     * Генерация таймлайнов для StudentsTimeline на основе расписания
//     * Запускать в 00:00 каждый день
//     * пример строки в крон файле: "0 0 * * * /var/www/smartsing-member/yii cron/generate-students-timeline"
//     * @return int
//     */
//    public function actionGenerateStudentsTimeline()
//    {
//        $Students =  Users::find()->where('(user_type = :user_type) AND (user_status = :user_status) AND (teacher_user_id IS NOT NULL)', [
//            'user_type'   => Users::TYPE_STUDENT,
//            'user_status' => Users::STATUS_ACTIVE,
//        ])->all();
//
//        /** @var \common\models\Users $student */
//        foreach ($Students as $student) {
//
//            StudentsScheduleForm::updateStudentsTimelineAfterPayOrByCron($student);
//
//        }
//
//        echo "OK\n";
//        return ExitCode::OK;
//    }
//
//    /**
//     * Если у пользователя закончились оплаченные уроки то
//     * нужно сбросить его статус и освободить учителя от этого пользователя
//     * Запускать в 00:00 каждый день
//     * пример строки в крон файле: "0 0 * * * /var/www/smartsing-member/yii cron/reset-no-payed-users"
//     * @return int
//     */
//    public function actionResetNoPayedUsers()
//    {
//        $NoPayedUsers = Users::find()
//            ->where([
//                'user_status' => Users::STATUS_ACTIVE,
//                'user_type'   => Users::TYPE_STUDENT,
//            ])
//            ->andWhere('
//                (teacher_user_id IS NOT NULL) AND
//                (user_lessons_available = 0) AND
//                ((user_last_lesson IS NULL) OR (user_last_lesson < :now))
//                ', [
//                'now' => date(SQL_DATE_FORMAT, time() - 86400)
//            ])
//            ->all();
//
//        foreach ($NoPayedUsers as $User) {
//
//            /** @var \common\models\Users $User*/
//            $transaction = Yii::$app->db->beginTransaction();
//
//            TeachersSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $User->user_id]);
//            $User->teacher_user_id = null;
//            $User->user_status = Users::STATUS_AFTER_INTRODUCE;
//            if (!$User->save()) {
//                $transaction->rollBack();
//            }
//
//            $transaction->commit();
//        }
//
//        echo "OK\n";
//        return ExitCode::OK;
//    }
//
//    /**
//     * Генерация таймлайнов для MethodistTimeline на основе расписания
//     * Запускать в 00:00 каждый день
//     * пример строки в крон файле: "0 0 * * * /var/www/smartsing-member/yii cron/generate-methodist-timeline"
//     * @return int
//     */
//    public function actionGenerateMethodistTimeline()
//    {
//        $Methodists = Users::findAll([
//            'user_type'   => Users::TYPE_METHODIST,
//            'user_status' => Users::STATUS_ACTIVE,
//        ]);
//
//        foreach ($Methodists as $methodist) {
//            $model = new MethodistScheduleForm();
//
//            if ($model->load([$model->formName() => [
//                    'user_id'       => $methodist->user_id,
//                    'user_type'     => $methodist->user_type,
//                    'user_timezone' => $methodist->user_timezone,
//                ]]) && $model->validate()) {
//
//                $schedule = $model->getScheduleForTimeline();
//                $date_start_timestamp = Functions::getTimestampBeginOfDayByTimestamp(time());
//                $model->generateTimeline(
//                    $date_start_timestamp,
//                    $schedule
//                );
//
//            }
//        }
//
//        echo "OK\n";
//        return ExitCode::OK;
//    }

}
