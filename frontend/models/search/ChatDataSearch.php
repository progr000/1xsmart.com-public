<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\models\Chat;
use common\helpers\Functions;
use common\models\MailTemplate;

/**
 *
 * @property Users $CurrentUser
 * @property int $sender_user_id
 * @property int $receiver_user_id
 * @property int $opponent_user_id
 * @property string $opponent_display_name
 * @property string $opponent_first_name
 * @property string $opponent_last_name
 * @property string $opponent_photo
 * @property int $opponent_type
 * @property string $msg_text
 *
 */
class ChatDataSearch extends Model
{

    protected $_required = [];

    public $CurrentUser;
    public $sender_user_id, $receiver_user_id;
    public $opponent_user_id, $opponent_display_name, $opponent_first_name, $opponent_last_name, $opponent_photo, $opponent_type;
    public $msg_text;

    /**
     * @param array $required
     * @param array $config
     */
    public function __construct(array $required=array(), array $config=array())
    {
        parent::__construct($config);
        if ($required && sizeof($required)) {
            $this->_required = [$required, 'required'];
        }
    }

    public function rules()
    {
        $rules = [
            ['CurrentUser', 'required'],
            [['sender_user_id', 'receiver_user_id'], 'integer'],
            ['opponent_user_id', 'integer'],
            [['opponent_display_name', 'opponent_first_name', 'opponent_last_name', 'opponent_photo'], 'string'],
            ['opponent_type', 'integer'],
            ['msg_text', 'string'],
            ['msg_text', 'safe'],
            ['msg_text', 'filter', 'filter' => function($value){return strip_tags($value);}],
        ];

        if (sizeof($this->_required)) {
            $rules[] = $this->_required;
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function getChatData()
    {
        /**/
        $ret['users'] = [];
        $ret['messages'] = [];

        /**/
        $query = "
            SELECT
              t2.user_id as opponent_user_id,
              t2.user_first_name,
              t2.user_last_name,
              t2.user_photo,
              t2.user_type,
              sum(t1.count_new) as count_new
            FROM
            (
              SELECT
                (CASE WHEN sender_user_id = :CurrentUser THEN receiver_user_id ELSE sender_user_id END) as opponent_user_id,
                (CASE WHEN sender_user_id = :CurrentUser THEN 0 ELSE sum(msg_unread) END) as count_new
              FROM sm_chat
              WHERE (sender_user_id = :CurrentUser)
              OR (receiver_user_id = :CurrentUser)
              GROUP BY sender_user_id, receiver_user_id
              ORDER BY count_new DESC
            ) as t1
            INNER JOIN sm_users as t2 ON t1.opponent_user_id = t2.user_id
            GROUP BY t2.user_id
            ORDER BY count_new DESC, t2.user_first_name ASC
        ";
        $ret['users'] = Yii::$app->db->createCommand($query, [
            'CurrentUser' => $this->CurrentUser->user_id,
        ])->queryAll();

        /**/
        $ret['total_count_new_messages'] = 0;
        $ret['total_count_new_opponents'] = 0;
        $start_chat_with = Yii::$app->session->get('start_chat_with', []);
        foreach ($ret['users'] as $test) {
            $ret['total_count_new_opponents'] += (intval($test['count_new']) > 0) ? 1 : 0;
            $ret['total_count_new_messages'] += intval($test['count_new']);
            if (isset($start_chat_with[$test['opponent_user_id']])) {
                unset($start_chat_with[$test['opponent_user_id']]);
            }
        }

        /**/
        if (sizeof($ret['users'])) {
            $query = "
                SELECT
                  sender_user_id,
                  receiver_user_id,
                  msg_unread,
                  msg_text,
                  msg_created
                FROM {{%chat}}
                WHERE ((sender_user_id = :CurrentUser) OR (receiver_user_id = :CurrentUser))
                AND (is_system_empty_message = :NO_SYSTEM)
                ORDER BY msg_created ASC
            ";
            $res = Yii::$app->db->createCommand($query, [
                'CurrentUser' => $this->CurrentUser->user_id,
                'NO_SYSTEM' => Chat::NO,
            ])->queryAll();

            foreach ($res as $v) {
                $v['msg_text'] = nl2br(Functions::formatLongString($v['msg_text']));
                if ($v['sender_user_id'] == $this->CurrentUser->user_id) {
                    $ret['messages'][$v['receiver_user_id']][] = $v;
                } else {
                    $ret['messages'][$v['sender_user_id']][] = $v;
                }
            }
        }

        /**/
        foreach ($start_chat_with as $v) {
            $ret['users'][] = $v;
            $ret['messages'][$v['opponent_user_id']] = [];
        }

        foreach ($ret['users'] as $v) {
            if (!isset($ret['messages'][$v['opponent_user_id']])) {
                $ret['messages'][$v['opponent_user_id']] = [];
            }
        }
        //var_dump($ret['users']); var_dump($ret['messages']); exit;

        //var_dump($ret['messages']);
        return $ret;
    }

    /**
     * @return array
     */
    public function setChatAsRead()
    {
        return [
            'sender_user_id' => $this->sender_user_id,
            'receiver_user_id' => $this->CurrentUser->user_id,
            'count_read' => Chat::updateAll(['msg_unread' => Chat::NO], [
                'receiver_user_id' => $this->CurrentUser->user_id,
                'sender_user_id' => $this->sender_user_id,
            ]),
        ];
    }

    /**
     *
     */
    public function startChatWith()
    {
        $start_chat_with = Yii::$app->session->get('start_chat_with', []);
        $start_chat_with[$this->opponent_user_id] = [
            'opponent_user_id' => $this->opponent_user_id,
            //'opponent_display_name' => $this->opponent_display_name,
            'user_first_name' => $this->opponent_first_name,
            'user_last_name' => $this->opponent_last_name,
            'user_photo' => $this->opponent_photo,
            'user_type' => $this->opponent_type,
            'count_new' => 0,
        ];
        Yii::$app->session->set('start_chat_with', $start_chat_with);
    }

    /**
     * @return array|bool
     */
    public function sendChatMessage()
    {
        $chat = new Chat();
        $chat->sender_user_id = $this->CurrentUser->user_id;
        $chat->receiver_user_id = $this->receiver_user_id;
        $chat->msg_text = $this->msg_text;
        $chat->msg_unread = Chat::YES;
        $chat->is_system_empty_message = Chat::NO;
        if ($chat->save()) {

            $ret['messages'] = [];

            $query = "
                SELECT
                  sender_user_id,
                  receiver_user_id,
                  msg_unread,
                  msg_text,
                  msg_created
                FROM {{%chat}}
                WHERE (
                  ((sender_user_id = :CurrentUser) AND (receiver_user_id = :receiver_user_id))
                  OR ((receiver_user_id = :CurrentUser) AND (sender_user_id = :receiver_user_id))
                )
                AND (is_system_empty_message = :NO_SYSTEM)
                ORDER BY msg_created ASC
            ";
            $res = Yii::$app->db->createCommand($query, [
                'CurrentUser' => $this->CurrentUser->user_id,
                'receiver_user_id' => $this->receiver_user_id,
                'NO_SYSTEM' => Chat::NO,
            ])->queryAll();

            foreach ($res as $v) {
                if ($v['sender_user_id'] == $this->CurrentUser->user_id) {
                    $ret['messages'][$v['receiver_user_id']][] = $v;
                } else {
                    $ret['messages'][$v['sender_user_id']][] = $v;
                }
            }

            /**/
            $Receiver = Users::findIdentity($chat->receiver_user_id);
            if ($Receiver) {
                $last_visit = strtotime($Receiver->user_last_visit);
                if (time() - $last_visit >= Users::ONLINE_TTL) {
                    MailTemplate::send([
                        'language' => $Receiver->last_system_language,
                        'to_email' => $Receiver->user_email,
                        'to_name' => $Receiver->user_first_name,
                        'composeTemplate' => 'newChatMessage',
                        'composeData' => [
                            'user_name' => $Receiver->user_first_name,
                            'APP_NAME' => Yii::$app->name,
                        ],
                        'composeLinks' => [
                            'memberLink' => ['user/'],
                        ],
                        'User' => $Receiver,
                    ]);
                }
            }

            return $ret['messages'];
        }

        return false;
    }
}
