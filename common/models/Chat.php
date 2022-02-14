<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use WebSocket;
use common\helpers\Functions;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property int $msg_id
 * @property string $msg_created
 * @property string|null $msg_text
 * @property int $msg_unread
 * @property int $sender_user_id
 * @property int $receiver_user_id
 * @property int $is_system_empty_message
 *
 * @property Users $senderUser
 * @property Users $receiverUser
 */
class Chat extends ActiveRecord
{
    const YES = 1;
    Const NO = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'msg_created',
                'updatedAtAttribute' => null,
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
            //[['msg_created'], 'required'],
            [['msg_text', 'sender_user_id', 'receiver_user_id'], 'required'],
            [['msg_created'], 'validateDateField', 'skipOnEmpty' => true],
            [['msg_created'], 'safe'],
            [['msg_text'], 'string'],
            [['msg_unread', 'sender_user_id', 'receiver_user_id'], 'default', 'value' => null],
            [['msg_unread', 'sender_user_id', 'receiver_user_id'], 'integer'],
            [['msg_unread'], 'in', 'range' => [self::YES, self::NO]],
            [['is_system_empty_message'], 'in', 'range' => [self::YES, self::NO]],
            [['is_system_empty_message'], 'default', 'value' => self::NO],
            [['sender_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sender_user_id' => 'user_id']],
            [['receiver_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['receiver_user_id' => 'user_id']],
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
            'msg_id' => 'Msg ID',
            'msg_created' => 'Msg Created',
            'msg_text' => 'Msg Text',
            'msg_unread' => 'Msg UnRead',
            'sender_user_id' => 'Sender User ID',
            'receiver_user_id' => 'Receiver User ID',
        ];
    }

    /**
     * Gets query for [[SenderUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'sender_user_id']);
    }

    /**
     * Gets query for [[ReceiverUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'receiver_user_id']);
    }

    /**
     * @param int $sender_user_id
     * @param int $receiver_user_id
     * @param string $message_text
     * @param int $is_system_empty_message
     * @param int $unread
     * @param bool $send_to_websocket
     */
    public static function initChatBetweenUsers($sender_user_id,
                                                $receiver_user_id,
                                                $message_text = 'system',
                                                $is_system_empty_message = self::YES,
                                                $unread = self::NO,
                                                $send_to_websocket=true)
    {
        $test = Chat::find()
            ->where('
                ( (sender_user_id = :sender_user_id) AND (receiver_user_id = :receiver_user_id) )
                OR
                ( (sender_user_id = :receiver_user_id) AND (receiver_user_id = :sender_user_id) )
            ', [
                'sender_user_id'   => $sender_user_id,
                'receiver_user_id' => $receiver_user_id
            ])
            ->one();
        if (!$test) {
            $Chat = new Chat();
            $Chat->is_system_empty_message = $is_system_empty_message;
            $Chat->msg_unread = $unread;
            $Chat->receiver_user_id = $receiver_user_id;
            $Chat->sender_user_id = $sender_user_id;
            $Chat->msg_text = $message_text;
            $Chat->save();
        }

        /**/
        if ($send_to_websocket) {
            $client = new WebSocket\Client(Yii::$app->params['wss_host'] . "/chat");
            $client->text('{"chat": {"user_id": ' . $sender_user_id . '}}');
            $client->text('{"chat": {"user_id": ' . $receiver_user_id . '}}');
            //echo $client->receive();
            $client->close();
        }

    }
}
