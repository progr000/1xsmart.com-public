<?php


namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\models\MailTemplate;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Users',
                'targetAttribute' => ['email' => 'user_email'],
                'filter' => ['user_is_confirmed' => Users::NO],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = Users::findOne([
            'user_email' => $this->email,
            'user_is_confirmed' => Users::NO,
            //'status' => Users::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        return MailTemplate::send([
            'language'        => Yii::$app->language,
            'to_email'        => $this->email,
            'to_name'         => $user->user_first_name,
            'composeTemplate' => 'emailVerify',
            'composeData'     => [
                'user_name' => $user->user_first_name,
                'APP_NAME'  => Yii::$app->name,
            ],
            'composeLinks' => [
                'verifyLink' => ['site/verify-email', 'token' => $user->verification_token],
            ],
            'User'            => $user,
        ]);
    }
}
