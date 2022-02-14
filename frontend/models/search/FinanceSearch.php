<?php

namespace frontend\models\search;

use common\models\TeachersRewards;
use Yii;
use yii\data\Pagination;
use common\models\Users;
use common\models\Payments;
use common\models\StudentsTimeline;

/**
 * NextLessons represents the model behind the search form about common\models\News.
 */
class FinanceSearch extends StudentsTimeline
{
    const MINIMAL_WITHDRAW_AMOUNT = 30; //usd

    const INCOMING = 'IN';
    const OUTGOING = 'OUT';

    /**
     * @param int $student_user_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTeachersForByPackage($student_user_id)
    {
        return Users::find()
            ->alias('t1')->select('t1.*')
            ->innerJoin('{{%students_timeline}} as t2', 't1.user_id = t2.teacher_user_id')
            ->where([
                't1.user_type'       => Users::TYPE_TEACHER,
                't2.student_user_id' => $student_user_id,
                't2.is_introduce_lesson' => StudentsTimeline::YES,
            ])
            ->andWhere('t2.timeline_timestamp < :now', [
                'now' => time() - NextLessons::ENTER_INTO_CLASS_AFTER_BEGINING_TIME_ALLOWED
            ])
            ->orderBy(['t1.user_price_peer_hour' => SORT_DESC])
            ->all();
    }

    /**
     * @param int $student_user_id
     * @param int $sort
     * @param int $page
     * @param int $count_per_page
     * @return array
     */
    public static function getStudentTransactionHistory($student_user_id, $sort = SORT_ASC, $page = 1, $count_per_page = 8)
    {
        $transactions_res = [];
        $now = time();
        $total_cnt = 0;

        $query = "
            SELECT
              t1.order_created as p_date,
              t1.order_count as p_count,
              t1.order_amount_usd as p_amount,
              t1.order_additional_fields as p_additional_data,
              :INCOMING as p_type,
              :NO_INTRO as p_is_intro,
              :STATUS_PASSED as lesson_status,
              t2.user_first_name as teacher_first_name,
              t2.user_last_name as teacher_last_name,
              t2.user_photo as teacher_photo
            FROM {{%payments}} as t1
            INNER JOIN {{%users}} as t2 ON t1.teacher_user_id = t2.user_id
            WHERE (t1.student_user_id = :student_user_id)
            AND (t1.order_status = :STATUS_PAYED)

            UNION

            SELECT
              t1.timeline as p_date,
              -1 as p_count,
              lesson_amount_usd as p_amount,
              '' as p_additional_data,
              :OUTGOING as p_type,
              t1.is_introduce_lesson as p_is_intro,
              t1.lesson_status as lesson_status,
              t2.user_first_name as teacher_first_name,
              t2.user_last_name as teacher_last_name,
              t2.user_photo as teacher_photo
            FROM {{%students_timeline}} as t1
            INNER JOIN {{%users}} as t2 ON t1.teacher_user_id = t2.user_id
            WHERE (t1.student_user_id = :student_user_id)
            AND (t1.timeline_timestamp < :now)

            ORDER BY p_date " . (($sort == SORT_ASC) ? "ASC" : "DESC") . "
        ";
        //" . (($sort == SORT_ASC) ? "ASC" : "DESC") . "
        /**/
        if ($page < 1) { $page = 1; }
        $count_total = Yii::$app->db->createCommand("SELECT count(*) as cnt FROM ( $query ) as t", [
            'INCOMING' => self::INCOMING,
            'OUTGOING' => self::OUTGOING,
            'NO_INTRO' => StudentsTimeline::NO,
            'STATUS_PASSED' => StudentsTimeline::STATUS_PASSED,
            'student_user_id' => $student_user_id,
            'STATUS_PAYED' => Payments::STATUS_PAYED,
            'now' => $now,
        ])->queryOne();
        if (isset($count_total['cnt'])) {
            $total_cnt = intval($count_total['cnt']);
        }
        if ($total_cnt > 0) {
            $max_page = intval(ceil($total_cnt / $count_per_page));
            if ($page > $max_page) {
                $page = $max_page;
            }
            $offset = $count_per_page * ($page - 1);

            /**/
            $query .= "LIMIT $count_per_page OFFSET $offset";
            $transactions_res = Yii::$app->db->createCommand($query, [
                'INCOMING' => self::INCOMING,
                'OUTGOING' => self::OUTGOING,
                'NO_INTRO' => StudentsTimeline::NO,
                'STATUS_PASSED' => StudentsTimeline::STATUS_PASSED,
                'student_user_id' => $student_user_id,
                'STATUS_PAYED' => Payments::STATUS_PAYED,
                'now' => $now,
            ])->queryAll();


            $l = sizeof($transactions_res);
            if ($l) {

                if ($sort == SORT_ASC) {
                    $last_date = $transactions_res[0]['p_date'];
                } else {
                    $last_date = $transactions_res[$l-1]['p_date'];
                }

                $query_plus = "
                    SELECT
                      sum(order_count) as plus
                    FROM {{%payments}}
                    WHERE (student_user_id = :student_user_id)
                    AND (order_status = :STATUS_PAYED)
                    AND (order_created < :last_date)
                ";
                $res_plus = Yii::$app->db->createCommand($query_plus, [
                    'student_user_id' => $student_user_id,
                    'STATUS_PAYED' => Payments::STATUS_PAYED,
                    'last_date' => $last_date,
                ])->queryOne();

                $query_minus = "
                    SELECT
                      count(*) as minus
                    FROM {{%students_timeline}}
                    WHERE (student_user_id = :student_user_id)
                    AND (timeline < :last_date)
                ";
                $res_minus = Yii::$app->db->createCommand($query_minus, [
                    'student_user_id' => $student_user_id,
                    'last_date' => $last_date,
                ])->queryOne();

                if (isset($res_plus['plus'])) {
                    $res_plus['plus'] = intval($res_plus['plus']);
                } else {
                    $res_plus['plus'] = 0;
                }
                if (isset($res_minus['minus'])) {
                    $res_minus['minus'] = intval($res_minus['minus']);
                } else {
                    $res_minus['minus'] = 0;
                }
                $lessons_remaining_before_this_page = $res_plus['plus'] - $res_minus['minus'];


                if ($sort == SORT_ASC) {
                    for ($i = 0; $i < $l; $i++) {
                        $lessons_remaining_before_this_page = $lessons_remaining_before_this_page + intval($transactions_res[$i]['p_count']);
                        $transactions_res[$i]['hours_remaining'] = $lessons_remaining_before_this_page;
                    }
                } else {
                    for ($i = $l-1; $i >= 0; $i--) {
                        $lessons_remaining_before_this_page = $lessons_remaining_before_this_page + intval($transactions_res[$i]['p_count']);
                        $transactions_res[$i]['hours_remaining'] = $lessons_remaining_before_this_page;
                    }
                }

            }
        }
        $pagination = new Pagination([
            'totalCount' => $total_cnt,
            'pageSize' => $count_per_page,
        ]);

        return [
            'list' => $transactions_res,
            'pagination' => $pagination,
            'sort' => $sort,
        ];
    }

    /**
     * @param int $teacher_user_id
     * @param int $sort
     * @param int $page
     * @param int $count_per_page
     * @return array
     */
    public static function getTeacherTransactionHistory($teacher_user_id, $sort = SORT_ASC, $page = 1, $count_per_page = 8)
    {
        $transactions_res = [];
        $now = time();
        $total_cnt = 0;
        $teacher_balance = 0.00;
        $withdraw_percent = Yii::$app->params['default_teacher_percent'];

        $query = "
            SELECT
              rw_created as p_date,
              0 as p_count,
              -1 * rw_amount_usd as p_amount,
              rw_description as p_additional_data,
              :OUTGOING as p_type,
              :NO_INTRO as p_is_intro,
              rw_status as p_status,
              '' as student_first_name,
              '' as student_last_name,
              '' as student_photo
            FROM {{%teachers_rewards}} as t1
            --INNER JOIN {{%users}} as t2 ON t1.teacher_user_id = t2.user_id
            WHERE (teacher_user_id = :teacher_user_id)
            AND (rw_status = :STATUS_PAYED)
            AND (rw_created < :now_date)

            UNION

            SELECT
              t1.timeline as p_date,
              1 as p_count,
              round(lesson_amount_usd * :withdraw_percent, 2) as p_amount,
              '' as p_additional_data,
              :INCOMING as p_type,
              t1.is_introduce_lesson as p_is_intro,
              t1.lesson_status as p_status,
              t2.user_first_name as student_first_name,
              t2.user_last_name as student_last_name,
              t2.user_photo as student_photo
            FROM {{%students_timeline}} as t1
            INNER JOIN {{%users}} as t2 ON t1.student_user_id = t2.user_id
            WHERE (t1.teacher_user_id = :teacher_user_id)
            AND (t1.timeline_timestamp < :now)

            ORDER BY p_date " . (($sort == SORT_ASC) ? "ASC" : "DESC") . "
        ";
        //" . (($sort == SORT_ASC) ? "ASC" : "DESC") . "
        /**/
        if ($page < 1) { $page = 1; }
        $count_total = Yii::$app->db->createCommand("SELECT count(*) as cnt FROM ( $query ) as t", [
            'INCOMING' => self::INCOMING,
            'OUTGOING' => self::OUTGOING,
            'NO_INTRO' => StudentsTimeline::NO,
            //'STATUS_PASSED' => StudentsTimeline::STATUS_PASSED,
            'teacher_user_id' => $teacher_user_id,
            'STATUS_PAYED' => TeachersRewards::STATUS_PAYED,
            'now' => $now,
            'now_date' => date(SQL_DATE_FORMAT, $now),
            'withdraw_percent' => $withdraw_percent,
        ])->queryOne();
        if (isset($count_total['cnt'])) {
            $total_cnt = intval($count_total['cnt']);
        }
        if ($total_cnt > 0) {
            $max_page = intval(ceil($total_cnt / $count_per_page));
            if ($page > $max_page) {
                $page = $max_page;
            }
            $offset = $count_per_page * ($page - 1);

            /**/
            $query .= "LIMIT $count_per_page OFFSET $offset";
            $transactions_res = Yii::$app->db->createCommand($query, [
                'INCOMING' => self::INCOMING,
                'OUTGOING' => self::OUTGOING,
                'NO_INTRO' => StudentsTimeline::NO,
                //'STATUS_PASSED' => StudentsTimeline::STATUS_PASSED,
                'teacher_user_id' => $teacher_user_id,
                'STATUS_PAYED' => TeachersRewards::STATUS_PAYED,
                'now' => $now,
                'now_date' => date(SQL_DATE_FORMAT, $now),
                'withdraw_percent' => $withdraw_percent,
            ])->queryAll();


            $l = sizeof($transactions_res);
            if ($l) {

                if ($sort == SORT_ASC) {
                    $last_date = $transactions_res[0]['p_date'];
                } else {
                    $last_date = $transactions_res[$l-1]['p_date'];
                }

                $query_minus = "
                    SELECT
                      sum(rw_amount_usd) as minus
                    FROM {{%teachers_rewards}}
                    WHERE (teacher_user_id = :teacher_user_id)
                    AND (rw_status = :STATUS_PAYED)
                    AND (rw_created < :last_date)
                ";
                $res_minus = Yii::$app->db->createCommand($query_minus, [
                    'teacher_user_id' => $teacher_user_id,
                    'STATUS_PAYED' => TeachersRewards::STATUS_PAYED,
                    'last_date' => $last_date,
                ])->queryOne();

                $query_plus = "
                    SELECT
                      sum(round(lesson_amount_usd * :withdraw_percent, 2)) as plus
                    FROM {{%students_timeline}}
                    WHERE (teacher_user_id = :teacher_user_id)
                    AND (timeline < :last_date)
                ";
                $res_plus = Yii::$app->db->createCommand($query_plus, [
                    'teacher_user_id' => $teacher_user_id,
                    'last_date' => $last_date,
                    'withdraw_percent' => $withdraw_percent
                ])->queryOne();


                if (isset($res_plus['plus'])) {
                    $res_plus['plus'] = doubleval($res_plus['plus']);
                } else {
                    $res_plus['plus'] = 0;
                }
                if (isset($res_minus['minus'])) {
                    $res_minus['minus'] = doubleval($res_minus['minus']);
                } else {
                    $res_minus['minus'] = 0;
                }

                $teacher_balance_before_this_page = round(doubleval($res_plus['plus']), 2) - round(doubleval($res_minus['minus']), 2);
                $teacher_balance_before_this_page = round($teacher_balance_before_this_page, 2);


                if ($sort == SORT_ASC) {
                    for ($i = 0; $i < $l; $i++) {
                        $teacher_balance_before_this_page = $teacher_balance_before_this_page + round(doubleval($transactions_res[$i]['p_amount']), 2);
                        $transactions_res[$i]['teacher_balance'] = $teacher_balance_before_this_page;
                    }
                } else {
                    for ($i = $l-1; $i >= 0; $i--) {
                        $teacher_balance_before_this_page = $teacher_balance_before_this_page + round(doubleval($transactions_res[$i]['p_amount']), 2);
                        $transactions_res[$i]['teacher_balance'] = $teacher_balance_before_this_page;
                    }
                }

            }


            /* balance */
            $query_minus = "
                    SELECT
                      sum(rw_amount_usd) as minus
                    FROM {{%teachers_rewards}}
                    WHERE (teacher_user_id = :teacher_user_id)
                    AND (rw_status = :STATUS_PAYED)
                    AND (rw_created < :now_date)
                ";
            $balance_minus = Yii::$app->db->createCommand($query_minus, [
                'teacher_user_id' => $teacher_user_id,
                'STATUS_PAYED' => TeachersRewards::STATUS_PAYED,
                'now_date' => date(SQL_DATE_FORMAT, $now),
            ])->queryOne();

            $query_plus = "
                    SELECT
                      sum(round(lesson_amount_usd * :withdraw_percent, 2)) as plus
                    FROM {{%students_timeline}}
                    WHERE (teacher_user_id = :teacher_user_id)
                    AND (timeline < :now_date)
                ";
            $balance_plus = Yii::$app->db->createCommand($query_plus, [
                'teacher_user_id' => $teacher_user_id,
                'now_date' => date(SQL_DATE_FORMAT, $now),
                'withdraw_percent' => $withdraw_percent
            ])->queryOne();

            if (isset($balance_plus['plus'])) {
                $balance_plus['plus'] = doubleval($balance_plus['plus']);
            } else {
                $balance_plus['plus'] = 0;
            }
            if (isset($balance_minus['minus'])) {
                $balance_minus['minus'] = doubleval($balance_minus['minus']);
            } else {
                $balance_minus['minus'] = 0;
            }

            $teacher_balance = round(doubleval($balance_plus['plus']), 2) - round(doubleval($balance_minus['minus']), 2);
            $teacher_balance = round($teacher_balance, 2);

        }
        $pagination = new Pagination([
            'totalCount' => $total_cnt,
            'pageSize' => $count_per_page,
        ]);

        return [
            'list' => $transactions_res,
            'balance' => $teacher_balance,
            'pagination' => $pagination,
            'sort' => $sort,
        ];
    }
}