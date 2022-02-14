<?php

namespace frontend\models\search;

use Yii;
use common\models\Payments;
use common\models\Users;
use common\models\Chat;

/**
 *
 * @property Users $CurrentUser
 *
 */
class NotificationsDataSearch extends Payments
{

    public $CurrentUser;
    public $admin_user_id, $receiver_user_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['CurrentUser', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function getNotifData()
    {
        /**/
        $ret['messages'] = [];

        /**/
        if ($this->CurrentUser->user_type == Users::TYPE_STUDENT) {

            $query = "
                SELECT
                  order_updated as p_date,
                  order_amount_usd as p_sum,
                  order_count as p_count,
                  order_description as p_info,
                  order_status as p_status,
                  teacher_user_id as opponent_user_id
                FROM {{%payments}}
                WHERE (student_user_id = :CurrentUser)
                AND (is_read_by_user = :NO_SYSTEM)
                AND (order_status IN (:STATUS_PAYED, :STATUS_CANCELED))
                ORDER BY order_created DESC
            ";
            $ret['messages'] = Yii::$app->db->createCommand($query, [
                'CurrentUser' => $this->CurrentUser->user_id,
                'NO_SYSTEM' => Payments::NO,
                'STATUS_PAYED' => Payments::STATUS_PAYED,
                'STATUS_CANCELED' => Payments::STATUS_CANCELED,
            ])->queryAll();

        }

        /**/
        $ret['total_count_new_notifications'] = sizeof($ret['messages']);

        /**/
        return $ret;
    }

    /**
     * @return array
     */
    public function setNotifAsRead()
    {
        if ($this->CurrentUser->user_type == Users::TYPE_ADMIN) {
            $is_read_field = 'is_read_by_admin';
        } else {
            $is_read_field = 'is_read_by_user';
        }

        return [
            'count_read' => Payments::updateAll([$is_read_field => Payments::YES], [
                'student_user_id' => $this->CurrentUser->user_id,
                $is_read_field => Payments::NO,
                'order_status' => [Payments::STATUS_PAYED, Payments::STATUS_CANCELED],
            ]),
        ];
    }


}
