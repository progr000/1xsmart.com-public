<?php
namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\Users;

/**
 * Login form
 *
 * @property \common\models\Users $_user
 *
 */
class LoginForm extends Model
{
    public $user_email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // user_email and password are both required
            [['user_email', 'password'], 'required'],
            ['user_email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            //var_dump($this->password); var_dump($user->validatePassword($this->password)); exit;
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided user_email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
//        if ($this->validate()) {
//            return Yii::$app->user->login($this->getUser(), Users::LOGIN_COOKIE_TTL);
//            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
//        }
//
//        return false;

        /**/
        if (!$this->validate()) {
            return false;
        }

        /**/
        if (!$this->_user) {
            return false;
        }

        /* для админа разрешен логин только через специальный домен */
        if ($this->_user->user_type == Users::TYPE_ADMIN) {
            if (!Yii::getAlias('@adminWeb', false) || !Yii::getAlias('@adminDomain')) {
                Yii::$app->session->setFlash('error', 'There was an error on login. (ErrorCode::SecurityErrorConfig)');
                return false;
            }
            if (Yii::$app->request->hostName != Yii::getAlias('@adminDomain')) {
                Yii::$app->session->setFlash('error', 'There was an error on login. (ErrorCode::SecurityErrorDomain)');
                return false;
            }
        }

        Yii::$app->language = $this->_user->last_system_language;

        return Yii::$app->user->login($this->_user, Users::LOGIN_COOKIE_TTL);
        //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Finds user by [[user_email]]
     *
     * @return Users|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findByEmail($this->user_email);
        }

        return $this->_user;
    }
}
