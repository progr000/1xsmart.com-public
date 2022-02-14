<?php
namespace frontend\models\forms;

use Yii;
use common\helpers\Functions;
use common\models\Users;
use common\models\Disciplines;
use common\models\TeachersDisciplines;

/**
 * Signup form
 *
 * @property string $_old_user_email
 * @property string $password
 *
 * @property integer $user_birthday_day
 * @property integer $user_birthday_month
 * @property integer $user_birthday_year
 *
 * @property array $_user_music_experience
 * @property array $_user_learning_objectives
 * @property array $_user_music_genres
 *
 * @property array $_user_are_native
 * @property array $_user_speak_also
 * @property array $_user_speak_also_select
 * @property array $_user_goals_of_education
 *
 * @property int $discipline_id
 *
 */
class ProfileForm extends Users
{
    public $_old_user_email;
    public $password;

    public $user_birthday_day;
    public $user_birthday_month;
    public $user_birthday_year;

    public $_user_music_experience;
    public $_user_learning_objectives;
    public $_user_music_genres;
    public $_user_are_native;
    public $_user_speak_also;
    public $_user_speak_also_select;
    public $_user_goals_of_education;

    public $discipline_id;
    public static $additional_rules = null;


    protected $_formName;
    public function formName()
    {
        return $this->_formName ? : parent::formName();
    }
    public function setFormName($name)
    {
        $this->_formName = $name;
    }

    /**
     * @param $id
     * @param $additional_rules
     * @return static
     */
    public static function initUser($id, $additional_rules)
    {
        self::$additional_rules = $additional_rules;
        return self::findIdentity($id);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $ret = array_merge(parent::rules(), [
            //['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['user_birthday_day', 'user_birthday_month', 'user_birthday_year'], 'required'],
            ['user_birthday_day', 'integer', 'min' => 1, 'max' => 31],
            ['user_birthday_month', 'integer', 'min' => 1, 'max' => 12],
            ['user_birthday_year', 'integer', 'min' => 1950, 'max' => intval(date('Y'))],
            ['user_price_peer_hour', 'default', 'value' => '1.00'],
            //['user_price_peer_hour', 'number', 'min' => 1.00, 'max' => 200.00],
            ['user_price_peer_hour', 'number', 'min' => 1.00],

            [[
                '_user_learning_objectives',
                '_user_music_experience',
                '_user_music_genres',
                '_user_are_native',
                '_user_speak_also',
                '_user_speak_also_select',
                '_user_goals_of_education',
            ], 'checkIsArray'],

            ['user_additional_info', 'string', 'min' => 100],

            [['discipline_id'], 'exist', 'skipOnError' => true, 'targetClass' => Disciplines::className(), 'targetAttribute' => ['discipline_id' => 'discipline_id']],
        ]);

        if (self::$additional_rules) {
            $ret = array_merge(self::$additional_rules, $ret);
        }

        return $ret;
    }

    public function attributeLabels()
    {
        return [
            'discipline_id' => Yii::t('app/settings-and-profile', 'I_would_like_teach'),
            'user_first_name' => Yii::t('app/settings-and-profile', 'First_name'),
            'user_middle_name' => Yii::t('app/settings-and-profile', 'Middle_name'),
            'user_last_name' => Yii::t('app/settings-and-profile', 'Last_name'),
            'user_email' => Yii::t('app/settings-and-profile', 'Email'),
            'user_phone' => Yii::t('app/settings-and-profile', 'Phone'),
            '_user_skype' => Yii::t('app/settings-and-profile', 'Skype_Telegram'),
            'password' => Yii::t('app/settings-and-profile', 'Password'),
            'user_additional_info' => Yii::t('app/settings-and-profile', 'Detailed_information'),
        ];
    }

    /**
     * @param $attribute
     */
    public function checkIsArray($attribute)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'Must be an array');
            return;
        }
    }

    /**
     * @return bool
     */
    public function saveProfile()
    {
        //var_dump(Yii::$app->request->post());exit;
        //var_dump($this->country_id);exit;
        /**/
        $this->user_birthday = date(
            SQL_DATE_FORMAT,
            Functions::getTimestampBeginOfDayByTimestamp(
                strtotime(
                    $this->user_birthday_year . '-' .
                    ($this->user_birthday_month < 10 ? '0'.$this->user_birthday_month : $this->user_birthday_month) . '-' .
                    ($this->user_birthday_day < 10 ? '0'.$this->user_birthday_day : $this->user_birthday_day) . ' 12:00:00'
                )
            )
        );
        /**/
        if (is_array($this->_user_music_experience)) {
            foreach ($this->_user_music_experience as $key => $item) {
                if (!isset(Users::$_music_experience[$key])) {
                    unset($this->_user_music_experience[$key]);
                }
            }
        }
        $this->user_music_experience = serialize($this->_user_music_experience);
        /**/
        if (is_array($this->_user_music_genres)) {
            foreach ($this->_user_music_genres as $key => $item) {
                if (!isset(Users::$_music_genres[$key])) {
                    unset($this->_user_music_genres[$key]);
                }
            }
        }
        $this->user_music_genres = serialize($this->_user_music_genres);
        /**/
        if (is_array($this->_user_learning_objectives)) {
            foreach ($this->_user_learning_objectives as $key => $item) {
                if (!isset(Users::$_learning_objectives[$key])) {
                    unset($this->_user_learning_objectives[$key]);
                }
            }
        }
        $this->user_learning_objectives = serialize($this->_user_learning_objectives);


        /**/
        if (is_array($this->_user_are_native)) {
            foreach ($this->_user_are_native as $key => $item) {
                if (!in_array($key, Users::$_languages)) {
                    unset($this->_user_are_native[$key]);
                }
                unset($this->_user_speak_also[$key]); //если ты натив по этому языку, то спеак-элсо не нужен на этом языке
            }
            $this->user_are_native = serialize($this->_user_are_native);
        } else {
            $this->user_are_native = null;
        }
        /**/
        if (is_array($this->_user_speak_also)) {
            foreach ($this->_user_speak_also as $key => $item) {
                if (!in_array($key, Users::$_languages)) {
                    unset($this->_user_speak_also[$key]);
                }
                if (!in_array($item, Users::$_speak_levels)) {
                    unset($this->_user_speak_also[$key]);
                }
            }
            $this->user_speak_also = serialize($this->_user_speak_also);
        } else {
            $this->user_speak_also = null;
        }
        /**/
        if (is_array($this->_user_goals_of_education)) {
            foreach ($this->_user_goals_of_education as $key => $item) {
                if (!in_array($key, Users::$_goals_of_education)) {
                    unset($this->_user_goals_of_education[$key]);
                }
            }
            $this->user_goals_of_education = serialize($this->_user_goals_of_education);
        } else {
            $this->user_goals_of_education = null;
        }

        TeachersDisciplines::deleteAll(['teacher_user_id' => $this->user_id]);
        $TD = new TeachersDisciplines();
        $TD->teacher_user_id = $this->user_id;
        $TD->discipline_id = $this->discipline_id;
        $TD->save();

        if (empty($this->country_id)) { $this->country_id = null; $this->region_id = null; $this->city_id = null; }
        if (empty($this->region_id)) { $this->region_id = null; /*$this->city_id = null;*/ }
        if (empty($this->city_id)) { $this->city_id = null; }

        //var_dump($this->country_id, $this->region_id, $this->city_id); exit;
        /*
        var_dump($this->_user_are_native);
        echo "<br />";
        var_dump($this->user_are_native);
        echo "<hr />";
        var_dump($this->_user_speak_also);
        echo "<br />";
        var_dump($this->user_speak_also);
        exit;
        */

        /**/
        if ($this->password) {
            $this->setPassword($this->password);
            //$this->generateAuthKey();
            $this->user_need_set_password = Users::NO;
        }
        /**/
        if ($this->_old_user_email !== $this->user_email) {
            $this->generateEmailVerificationToken();
        }
        /**/
        return $this->save();
    }

    public function beforeSave($insert)
    {
        $this->user_price_peer_hour = Functions::getInUsd($this->user_price_peer_hour);
        //var_dump($this->user_price_peer_hour);exit;
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_old_user_email = $this->user_email;

        $this->user_price_peer_hour = Functions::getInCurrency($this->user_price_peer_hour)['sum'];

        $this->user_birthday_day = 1;
        $this->user_birthday_month = 1;
        $this->user_birthday_year = 2015;
        if ($this->user_birthday) {
            $this->user_birthday_day = intval(date('d', strtotime($this->user_birthday)));
            $this->user_birthday_month = intval(date('m', strtotime($this->user_birthday)));
            $this->user_birthday_year = intval(date('Y', strtotime($this->user_birthday)));
        }
    }
}
