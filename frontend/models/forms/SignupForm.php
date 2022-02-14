<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\MailTemplate;
use common\helpers\Functions;
use common\models\Users;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $acceptRules;
    public $user_type;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique',
                'targetClass' => '\common\models\Users',
                'targetAttribute' => ['email' => 'user_email'],
                'message' => 'This email address has already been taken.'
            ],

            [['password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

            ['user_type', 'required'],
            ['user_type', 'in', 'range' => [Users::TYPE_STUDENT, Users::TYPE_TEACHER]],

            ['acceptRules', 'required', 'requiredValue' => 1, 'message' => Yii::t('modals/signup', "You_must_agree")],
            ['acceptRules', 'default', 'value' => 1],
        ];
    }

    /**
     * Signs user up.
     *
     * @return \common\models\Users|null
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Users();
        $user->user_first_name    = $this->username;
        $user->user_last_name     = '';
        $user->user_middle_name   = '';
        $user->user_full_name     = $user->user_first_name;
        $user->user_email         = $this->email;
        $user->user_last_ip       = '127.0.0.1';
        $user->user_status        = ($this->user_type == Users::TYPE_TEACHER)
            ? Users::STATUS_ACTIVE
            : Users::STATUS_BEFORE_INTRODUCE;
        $user->user_is_confirmed  = Users::NO;
        $user->user_type          = $this->user_type;
        $user->user_need_set_password = Users::NO;
        $user->receive_system_notif = Users::YES;
        $user->receive_lesson_notif = Users::YES;
        $user->last_system_language = Yii::$app->language;

        /* tz */
        $js_user_time_zone = Yii::$app->session->get('js_user_time_zone', null);
        $check_tz = Functions::get_list_of_timezones('offset_short_name');
        if (isset($check_tz[$js_user_time_zone])) {
            $js_user_time_zone = intval($js_user_time_zone);
        } else {
            $js_user_time_zone = 0;
        }
        $user->user_timezone = $js_user_time_zone;

        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        //$this->sendEmail($user);
        //$user->setPassword($this->password);

        if ($user->save()) {
            $this->sendEmail($user);
            $user->setPassword($this->password);
            $user->save();
            return $user;
        } else {
            //var_dump($user->getErrors());
            return null;
        }

    }

    /**
     * Sends confirmation email to user
     * @param Users $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return MailTemplate::send([
            'language'        => Yii::$app->language,
            'to_email'        => $this->email,
            'to_name'         => $this->username,
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
