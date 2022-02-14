<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\models\MailTemplate;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
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
                'filter' => [
                    'user_type' => [Users::TYPE_STUDENT, Users::TYPE_TEACHER],
                    'user_status' => [
                        Users::STATUS_ACTIVE,
                        Users::STATUS_AFTER_PAYMENT,
                        Users::STATUS_AFTER_INTRODUCE,
                        Users::STATUS_BEFORE_INTRODUCE
                    ],
                ],
                'message' => false //'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Users */
        $user = Users::findOne([
            'user_status' => [
                Users::STATUS_ACTIVE,
                Users::STATUS_AFTER_PAYMENT,
                Users::STATUS_AFTER_INTRODUCE,
                Users::STATUS_BEFORE_INTRODUCE
            ],
            'user_type' => [Users::TYPE_STUDENT, Users::TYPE_TEACHER],
            'user_email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return MailTemplate::send([
            'language'        => Yii::$app->language,
            'to_email'        => $this->email,
            'to_name'         => $user->user_first_name,
            'composeTemplate' => 'passwordResetToken',
            'composeData'     => [
                'user_name' => $user->user_first_name,
                'APP_NAME'  => Yii::$app->name,
            ],
            'composeLinks' => [
                'resetLink' => ['site/reset-password', 'token' => $user->password_reset_token],
            ],
            'User'            => $user,
        ]);
    }
}
