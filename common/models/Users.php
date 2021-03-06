<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\imagine\Image;
use yii\web\IdentityInterface;
use yii\caching\TagDependency;
use common\helpers\Functions;
use common\helpers\FileSys;

/**
 * Users model
 *
 * @property int $user_id
 * @property string $user_created
 * @property string $user_updated
 *
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property string $auth_key
 *
 * @property string $user_first_name
 * @property string $user_middle_name
 * @property string $user_last_name
 * @property string $user_full_name
 *
 * @property string $user_email
 * @property string|null $user_phone
 * @property string $user_last_pay
 *
 * @property string $user_token
 * @property string|null $user_hash
 * @property int $user_status
 * @property int $user_type
 *
 * @property int|null $admin_user_id
 * @property string|null $admin_notice
 * @property int|null $operator_user_id
 * @property string|null $operator_notice
 * @property int|null $methodist_user_id
 * @property string|null $methodist_notice
 * @property int|null $teacher_user_id
 * @property string|null $teacher_notice
 *
 * @property float $user_balance
 * @property int $user_last_ip
 *
 * @property int $user_level_general
 * @property int $user_level_range
 * @property int $user_level_coordination
 * @property int $user_level_timbre
 *
 * @property string $user_level_general_notice
 * @property string $user_level_range_notice
 * @property string $user_level_coordination_notice
 * @property string $user_level_timbre_notice
 *
 * @property int $user_need_set_password
 *
 * @property string $user_photo
 * @property int $user_gender
 * @property int $user_timezone
 * @property string $user_birthday
 * @property string $user_learning_objectives
 * @property string $user_music_experience
 * @property string $user_music_genres
 * @property string $user_additional_info
 *
 * @property int $receive_system_notif
 * @property int $receive_lesson_notif
 *
 * @property string $notes_lowest
 * @property string $notes_highest
 * @property int $notes_played
 * @property int $notes_hit
 * @property int $notes_close
 *
 * @property string|null $additional_service_info
 * @property string|null $additional_service_notice
 *
 * @property int $user_lessons_available
 * @property int $user_lessons_completed
 * @property int $user_lessons_missed
 * @property string $user_last_lesson
 * @property int $_user_lessons_assigned
 * @property string $user_last_visit
 * @property string $user_youtube_video
 * @property string $user_local_video
 *
 * @property int $user_confirm_lesson
 * @property string $user_are_native
 * @property string $user_speak_also
 * @property string $user_goals_of_education
 *
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 *
 * @property int $user_is_confirmed
 * @property int $user_can_teach_children
 * @property float $user_price_peer_hour
 *
 * @property int $user_lessons_spent
 * @property int $user_reviews
 * @property float $user_rating
 * @property int $teacher_profile_completed
 *
 * @property int $after_payment_action
 *
 * @property string $pay_to_wallet
 * @property string $wallet_paypal
 * @property string $wallet_yandex
 *
 * @property string $last_system_language
 *
 * @property Users $operatorUser
 * @property Users[] $users
 * @property Users $methodistUser
 * @property Users[] $users0
 * @property Users $teacherUser
 * @property Users[] $users1
 *
 * @property $_old_user_type
 * @property $_user_timezone_name
 * @property $_user_timezone_short_name
 * @property $_user_local_time
 * @property $_user_age
 *
 * @property string $user_custom_messengers
 * @property array $_messengers
 * @property string $_user_skype
 * @property string $_user_telegram
 * @property string $_user_viber
 * @property string $_user_jabber
 * @property string $_user_icq
 *
 * @property boolean $_user_online;
 * @property int|string $_user_last_visit
 *
 * @property int|null $_old_operator_user_id
 * @property int|null $_old_methodist_user_id
 * @property int|null $_old_teacher_user_id
 *
 * @property string|null $_old_user_photo
 * @property string|null $_old_user_youtube_video
 * @property string|null $_old_user_local_video
 *
 * @property string $_user_conference_room_hash
 * @property string $_user_display_name
 *
 */
class Users extends ActiveRecord implements IdentityInterface
{
    private static $CACHE_TTL = 3600;
    const LOGIN_COOKIE_TTL = 3600 * 24 * 7;
    const ONLINE_TTL = 60 * 5;

    const CONFERENCE_ROOM_HASH_SALT = 'dref3FVFDasff4sdcds';

    const STATUS_DELETED   = 0;
    const STATUS_INACTIVE  = 1;
    const STATUS_BEFORE_INTRODUCE = 2; // ???? ???????? ?????? ???????????? ?????????????? ?????????????? ?? ????????????????????
    const STATUS_AFTER_INTRODUCE = 3;  // ?????????? ???????????????? ?????????????? ?? ????????????????????????????
    const STATUS_AFTER_PAYMENT = 4;    // ?????????? ???????? ?????? ???????? ???????????? ???????????? ???????????? ???????????? ???? ?????? ???? ?????????????????? ???????? ????????????????????
    const STATUS_ACTIVE    = 10;

    const NO_ACTION = 0;
    const AFTER_INTRO_ACTION = 1;
    const AFTER_PACKAGE_ACTION= 2;

    const TYPE_ADMIN     = 0;
    const TYPE_OPERATOR  = 1;
    const TYPE_METHODIST = 2;
    const TYPE_TEACHER   = 3;
    const TYPE_STUDENT   = 4;

    const TEACHER_PROFILE_NEW = 0;
    const TEACHER_PROFILE_WAIT_APPROVE = 1;
    const TEACHER_PROFILE_APPROVED = 2;

    const ENTER_TO_CLASS_ROOM_NOT_EARLIER_METHODIST = 60*10;
    const ENTER_TO_CLASS_ROOM_NOT_EARLIER_TEACHER = 60*10;
    const ENTER_TO_CLASS_ROOM_NOT_EARLIER_STUDENT = 60*3*10;

    const GENDER_MALE   = 1;
    const GENDER_FEMALE = 0;

    const level_general_max = 11;
    const level_range_max = 9;
    const level_coordination_max = 9;
    const level_timbre_max = 9;

    const YES = 1;
    const NO  = 0;

    const CONFIRM_LESSON_1 = 1;
    const CONFIRM_LESSON_2 = 2;

    const PAY_TO_PAYPAL = 'paypal';
    const PAY_TO_YANDEX = 'yandex';

    const PASSWORD_PATTERN = '/^[a-zA-Z0-9!@#$%^&*()_\-+=[\]{};:"\'\\\|\?\/\.\,]+$/';
    const WALLET_YANDEX_PATTERN = "/^[0-9]{14,16}$/";

    public $_user_timezone_name;
    public $_user_timezone_short_name;
    public $_user_local_time;
    private $_old_user_type;

    public $_user_age;

    public $teacherUser;
    public $methodistUser;
    public $operatorUser;

    public $_old_operator_user_id;
    public $_old_methodist_user_id;
    public $_old_teacher_user_id;

    public $_old_user_photo;
    public $_old_user_youtube_video;
    public $_old_user_local_video;

    public $_user_conference_room_hash;

    public $_user_display_name;

    public static $_music_experience = [
        'studying_music_school' => "???????????????? ?? ??????. ??????????",
        'studying_with_teacher' => "???????????????? ?? ????????????????????",
        'training_courses'      => "??????????",
        'self_courses'          => "????????????????????????",
    ];

    public static $_learning_objectives = [
        'for_me' => "?????? ????????",
        'for_karaoke' => "?????? ??????????????",
        'for_scene' => "?????????????????????? ???? ?????????????? ??????????",
        'for_present' => "???????????????????? ??????????????",
    ];

    public static $_music_genres = [
        'domestic_pop_music' => "?????????????????????????? ?????? ????????????",
        'foreign_pop_music' => "???????????????????? ?????? ????????????",
        'rnb_rap' => "R'nB, Rap",
        'jazz' => "????????",
        'authors_music' => "?????????????????? ????????????",
        'folk' => "????????",
        'club_music' => "?????????????? ????????????",
    ];

    public static $_general_levels = [
        0  => '??????????????',
        1  => '??????????????????',
        2  => '??????????????????+',
        3  => '?????????????????? Extra',
        4  => '????????????????',
        5  => '????????????????+',
        6  => '???????????????? Extra',
        7  => '??????????????????????',
        8  => '??????????????????????+',
        9  => '?????????????????????? Extra',
        10 => '????????????????????????',
        11 => '????????????????????????-??????????????????',
    ];

    public static $_languages = [
        'english',
        'spanish',
        'russian',
        'ukrainian',
        'french',
        'arabic',
        'portuguese',
        'german',
        'chinese',
    ];

    public static $_speak_levels = [
        'A1',
        'A2',
        'B1',
        'B2',
        'C1',
        'C2',
    ];

    public static $_goals_of_education = [
        'business_language',
        'conversational_language',
        'language_for_traveling',
        'language_for_beginners',
        'relocation_to_other_country',
        //'for_children',
        'ielts',
        'toefl',
        'delf',
        'dalf',
        'other',
    ];

    public static $_price_vars = [
        'econom'   => ['name' => "Econom",   'min' => 0.00,  'max' => 10.00],
        'standard' => ['name' => "Standard", 'min' => 10.01, 'max' => 15.00],
        'premium'  => ['name' => "Premium",  'min' => 15.01, 'max' => 20.00],
        'vip'      => ['name' => "VIP",      'min' => 20.01, 'max' => 1000.00],
    ];

    public $_messengers = [];
    public $_user_skype;
    public $_user_telegram;
    public $_user_viber;
    public $_user_jabber;
    public $_user_icq;

    public $_user_online;
    public $_user_last_visit;

    public $_user_lessons_assigned;

    /* ???????? ???? ???????????????????? ?? ???? ??????????, ?????????? ?????? ?????????? ?????????????? ?????????????????? ?????????????? ??????????, ?????? ???? ???? ?????????????????? ?????????????????? ???????????? ?????????? */
    public $_lesson_status;
    public $_lesson_notice;

    /**
     * @param string $user_birthday
     * @return int
     */
    public static function staticGetAge($user_birthday)
    {
        return Functions::calculate_age($user_birthday);
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return self::staticGetAge($this->user_birthday);
    }

    /**
     * @return array
     */
    public static function getGenders()
    {
        return [
            self::GENDER_MALE  => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    /**
     * @param $user_gender
     * @return string
     */
    public static function getGender($user_gender)
    {
        $params = self::getGenders();
        //if ($user_gender === null) { return $user_gender; }
        return isset($params[$user_gender]) ? $params[$user_gender] : $user_gender;
    }

    /**
     * returns list of statuses in array
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DELETED  => '????????????',
            self::STATUS_INACTIVE => '??????????????????????????',
            self::STATUS_BEFORE_INTRODUCE => '???? ????????????????',
            self::STATUS_AFTER_INTRODUCE => '?????????? ???????????????? (?????? ????????????)',
            self::STATUS_AFTER_PAYMENT => '?????????? ???????????? (?????? ????????????????????)',
            self::STATUS_ACTIVE   => '????????????????'
        ];
    }

    /**
     * return status name by user_status value
     * @param integer $user_status
     * @return string | null
     */
    public static function getStatus($user_status)
    {
        $params = self::getStatuses();
        return isset($params[$user_status]) ? $params[$user_status] : $user_status;
    }

    /**
     * returns list of statuses in array
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_ADMIN     => 'Admin',
            self::TYPE_OPERATOR  => 'Operator',
            self::TYPE_METHODIST => 'Methodist',
            self::TYPE_TEACHER   => 'Teacher',
            self::TYPE_STUDENT   => 'Student'
        ];
    }

    /**
     * return status name by user_status value
     * @param integer $user_type
     * @return string | null
     */
    public static function getType($user_type)
    {
        $params = self::getTypes();
        return isset($params[$user_type]) ? $params[$user_type] : $user_type;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'user_created',
                'updatedAtAttribute' => 'user_updated',
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
            [[
                'user_first_name',
                'user_full_name',
                'user_email',
            ], 'required'],

            /* ???????????????????????????? ???????? */
            [['_lesson_status', '_lesson_notice'],'safe'],

            ['user_email', 'trim'],
            ['user_email', 'email'],
            ['user_email', 'string', 'max' => 255],

            [[
                'user_created',
                'user_updated',
                'user_last_pay',
                'user_birthday',
                'user_last_lesson',
                'user_last_visit',
            ], 'validateDateField', 'skipOnEmpty' => true],
            [[
                'user_created',
                'user_updated',
                'user_last_pay',
                'user_birthday',
                'user_last_lesson',
                'user_last_visit',
            ], 'safe'],

            ['user_status', 'default', 'value' => self::STATUS_INACTIVE],
            ['user_status', 'in', 'range' => [
                self::STATUS_DELETED,
                self::STATUS_INACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
                self::STATUS_ACTIVE,
            ]],

            ['user_type', 'default', 'value' => self::TYPE_STUDENT],
            ['user_type', 'in', 'range' => [
                self::TYPE_STUDENT,
                self::TYPE_TEACHER,
                self::TYPE_METHODIST,
                self::TYPE_OPERATOR,
                self::TYPE_ADMIN,
            ]],

            [[
                'user_first_name',
                'user_middle_name',
                'user_last_name',
                'user_full_name',
                'user_email',
                'admin_notice',
                'operator_notice',
                'methodist_notice',
                'teacher_notice',
                'user_photo',
                'user_learning_objectives',
                'user_music_experience',
                'user_music_genres',
                'user_additional_info',
                'user_level_general_notice',
                'user_level_range_notice',
                'user_level_coordination_notice',
                'user_level_timbre_notice',
                'user_local_video',
                'user_are_native',
                'user_speak_also',
                'user_goals_of_education',
            ], 'string'],

            ['user_youtube_video', 'url', 'defaultScheme' => 'https'],

            [[
                'admin_notice',
                'operator_notice',
                'methodist_notice',
                'teacher_notice',
                'user_additional_info',
                'user_level_general_notice',
                'user_level_range_notice',
                'user_level_coordination_notice',
                'user_level_timbre_notice',
            ], 'safe'],

            [[
                'user_status',
                'user_type',
                'admin_user_id',
                'operator_user_id',
                'methodist_user_id',
                'teacher_user_id',
                'user_last_ip',
                'user_gender',
                'user_timezone',
                'user_lessons_spent',
                'user_reviews',
                'user_can_teach_children',
            ], 'integer'],

            ['user_timezone', 'default', 'value' => 10800],

            ['user_gender', 'in', 'range' => [self::GENDER_FEMALE, self::GENDER_MALE]],

            [['user_balance', 'user_price_peer_hour', 'user_rating'], 'number'],
            [['user_balance', 'user_rating'], 'default', 'value' => '0.00'],
            ['user_price_peer_hour', 'default', 'value' => '1.00'],
            //['user_price_peer_hour', 'number', 'min' => 1.00, 'max' => 200.00],
            ['user_rating', 'number', 'min' => 0.00, 'max' => 5.00],


            [['password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['auth_key', 'user_token'], 'string', 'max' => 32],

            [['user_phone'], 'string', 'max' => 50],

            ['user_custom_messengers', 'string'],
            ['_user_skype', 'string', 'min' => 3, 'max' => 50],
            ['_user_telegram', 'string', 'min' => 3, 'max' => 50],
            ['_user_viber', 'string', 'min' => 3, 'max' => 50],
            ['_user_jabber', 'string', 'min' => 3, 'max' => 50],
            ['_user_icq', 'string', 'min' => 3, 'max' => 50],

            ['user_hash', 'string', 'max' => 128],

            /* student levels */
            ['user_level_general', 'integer', 'min' => 0, 'max' => self::level_general_max],
            ['user_level_range', 'integer', 'min' => 0, 'max' => self::level_range_max],
            ['user_level_coordination', 'integer', 'min' => 0, 'max' => self::level_coordination_max],
            ['user_level_timbre', 'integer', 'min' => 0, 'max' => self::level_timbre_max],

            [[
                'user_need_set_password',
                'receive_system_notif',
                'receive_lesson_notif',
                'user_is_confirmed',
                'user_can_teach_children',
            ], 'integer', 'min' => self::NO, 'max' => self::YES],
            ['user_is_confirmed', 'default', 'value' => self::NO],
            [['receive_system_notif', 'receive_lesson_notif'], 'default', 'value' => self::YES],

            ['teacher_profile_completed', 'integer'],
            ['teacher_profile_completed', 'in', 'range' => [
                self::TEACHER_PROFILE_NEW,
                self::TEACHER_PROFILE_WAIT_APPROVE,
                self::TEACHER_PROFILE_APPROVED,
            ]],

            ['after_payment_action', 'integer'],
            ['after_payment_action', 'in', 'range' => [
                self::NO_ACTION,
                self::AFTER_INTRO_ACTION,
                self::AFTER_PACKAGE_ACTION,
            ]],
            ['after_payment_action', 'default', 'value' => self::NO_ACTION],

            [['notes_played', 'notes_hit', 'notes_close'], 'integer', 'min' => 0],
            [['notes_lowest', 'notes_highest'], 'string', 'max' => 3],

            [['additional_service_info', 'additional_service_notice'], 'string'],

            [[
                'user_lessons_available',
                'user_lessons_completed',
                'user_lessons_missed'
            ], 'integer', 'min' => 0],

            ['user_confirm_lesson', 'in', 'range' => [self::CONFIRM_LESSON_1, self::CONFIRM_LESSON_2]],

            /* idx */
            ['password_reset_token', 'unique'],
            ['user_email', 'unique'],
            ['user_hash', 'unique'],
            ['user_token', 'unique'],
            ['verification_token', 'unique'],

            [['city_id', 'country_id', 'region_id'], 'integer'],

            ['pay_to_wallet', 'in', 'range' => [self::PAY_TO_PAYPAL, self::PAY_TO_YANDEX]],
            ['pay_to_wallet', 'default', 'value' => self::PAY_TO_PAYPAL],
            [['wallet_paypal', 'wallet_yandex'], 'string', 'max' => 255],
            ['wallet_paypal', 'email'],
            ['wallet_yandex', 'match', 'pattern' => self::WALLET_YANDEX_PATTERN],

            ['last_system_language', 'string', 'max' => 5],
            ['last_system_language', 'default', 'value' => 'en'],

            /* foreign */
            ['admin_user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['admin_user_id' => 'user_id']],
            ['operator_user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['operator_user_id' => 'user_id']],
            ['methodist_user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['methodist_user_id' => 'user_id']],
            ['teacher_user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['teacher_user_id' => 'user_id']],

            //[['city_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'city_id']],
            //[['country_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            //[['region_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'region_id']],
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
     * @return static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @param integer $user_id
     * @param bool $active
     * @return static
     */
    public static function findById($user_id, $active=true)
    {
        if ($active) {
            return static::findOne(['user_id' => $user_id, 'user_status' => [
                self::STATUS_ACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
            ]]);
        } else {
            return static::findOne(['user_id' => $user_id]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by user_email
     *
     * @param string $user_email
     * @param boolean $active
     * @return static
     */
    public static function findByEmail($user_email, $active=true)
    {
        if ($active) {
            return static::findOne(['user_email' => $user_email, 'user_status' => [
                self::STATUS_ACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
            ]]);
        } else {
            return static::findOne(['user_email' => $user_email]);
        }
    }

    /**
     * Finds user by user_token
     *
     * @param string $user_token
     * @param boolean $active
     * @return static
     */
    public static function findByToken($user_token, $active=true)
    {
        if ($active) {
            return static::findOne(['user_token' => $user_token, 'user_status' => [
                self::STATUS_ACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
            ]]);
        } else {
            return static::findOne(['user_token' => $user_token]);
        }
    }

    /**
     * Finds user by user_hash
     *
     * @param string $user_hash
     * @param boolean $active
     * @return static
     */
    public static function findByHash($user_hash, $active=true)
    {
        if ($active) {
            return static::findOne(['user_hash' => $user_hash, 'user_status' => [
                self::STATUS_ACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
            ]]);
        } else {
            return static::findOne(['user_hash' => $user_hash]);
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'user_status' => [
                self::STATUS_ACTIVE,
                self::STATUS_BEFORE_INTRODUCE,
                self::STATUS_AFTER_INTRODUCE,
                self::STATUS_AFTER_PAYMENT,
            ],
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            //'user_status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new user_token for login link by token
     */
    public function generateUserToken()
    {
        $this->user_token = md5(uniqid());
    }

    /**
     * Generates new user_token for login link by token
     */
    public function deleteUserToken()
    {
        $this->user_token = null;
    }

    public function generateUserHash()
    {
        $this->user_hash = hash('sha512', $this->user_email . $this->password_hash);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_created' => 'User Created',
            'user_updated' => 'User Updated',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'auth_key' => 'Auth Key',
            'user_first_name' => 'Name',
            'user_middle_name' => 'Middle name',
            'user_last_name' => 'Last name',
            'user_full_name' => 'Full name',
            'user_email' => 'Email',
            'user_phone' => 'Phone',
            'user_last_pay' => 'User Last Pay',
            'user_token' => 'User Token',
            'user_hash' => 'User Hash',
            'user_status' => 'User Status',
            'user_type' => 'User Type',
            'admin_user_id' => 'Admin User ID',
            'operator_user_id' => 'Operator User ID',
            'operator_notice' => 'Operator Notice',
            'methodist_user_id' => 'Methodist User ID',
            'methodist_notice' => 'Methodist Notice',
            'teacher_user_id' => 'Teacher User ID',
            'teacher_notice' => 'Teacher Notice',
            'user_balance' => 'User Balance',
            'user_last_ip' => 'User Last Ip',

            '_user_skype' => 'Skype',
            '_user_telegram' => 'Telegram',
            '_user_viber' => 'Viber',
            '_user_jabber'   => 'Jabber',
            '_user_icq' => 'ICQ',
        ];
    }

    /**
     * @return Countries
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['country_id' => 'country_id'])->one();
    }

    /**
     * @return Cities
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['city_id' => 'city_id'])->one();
    }

    /**
     * Gets query for [[OperatorUser]].
     *
     * @return Users
     */
    public function getOperatorForThisUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'operator_user_id'])->one();
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersForThisOperator()
    {
        return $this->hasMany(Users::className(), ['operator_user_id' => 'user_id'])->all();
    }

    /**
     * Gets query for [[MethodistUser]].
     *
     * @return Users
     */
    public function getMethodistForThisUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'methodist_user_id'])->one();
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getIntroduceLessonForThisUser()
    {
        return $this->hasOne(MethodistTimeline::className(), ['student_user_id' => 'user_id'])->one();
    }

    /**
     * Gets query for [[Users0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersForThisMethodist()
    {
        return $this->hasMany(Users::className(), ['methodist_user_id' => 'user_id'])->all();
    }

    /**
     * Gets query for [[TeacherUser]].
     *
     * @return Users
     */
    public function getTeacherForThisUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'teacher_user_id'])->one();
    }

    /**
     * Gets query for [[Users1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersForThisTeacher()
    {
        return $this->hasMany(Users::className(), ['teacher_user_id' => 'user_id'])->all();
    }

    /**
     * @return Disciplines
     */
    public function getMainDisciplineForThisTeacher()
    {
        /** @var \common\models\Disciplines $TeachersDiscipline */
        return Disciplines::find()
            ->select('t1.*')
            ->alias('t1')
            ->innerJoin('{{%teachers_disciplines}} as t2', 't1.discipline_id = t2.discipline_id')
            ->where([
                'teacher_user_id' => $this->user_id
            ])
            ->orderBy(['t1.discipline_id' => SORT_ASC])
            ->one();
    }

    public function getAllDisciplinesForThisTeacher()
    {
        /** @var \common\models\Disciplines $TeachersDiscipline */
        return Disciplines::find()
            ->select('t1.*')
            ->alias('t1')
            ->innerJoin('{{%teachers_disciplines}} as t2', 't1.discipline_id = t2.discipline_id')
            ->where([
                'teacher_user_id' => $this->user_id
            ])
            ->orderBy(['t1.discipline_id' => SORT_ASC])
            ->all();
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            //$this->user_timezone = intval(round($this->user_timezone/3600)) * 3600;

            // +++ token and hash
            if ($this->isNewRecord) {
                $this->setPassword(uniqid());
                $this->generateAuthKey();
                $this->generatePasswordResetToken();

                $this->user_need_set_password = self::YES;
                $this->generateUserHash();
                $this->generateUserToken();
            }

            // +++ IP to long
            if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
                $this->user_last_ip = Yii::$app->request->getUserIP();
            }
            if (is_string($this->user_last_ip)) {
                $this->user_last_ip = intval(ip2long($this->user_last_ip));
            }

            // +++ full_name
            if (!$this->user_full_name) {
                $this->user_full_name = $this->user_last_name . ' ' . $this->user_first_name . ' ' . $this->user_middle_name;
            }

            return true;
        }

        return false;
    }

    /**
     *
     */
    protected static function createProfileDirSymlink()
    {
        $targetLink = Yii::getAlias('@frontend') . "/web" . Yii::$app->params['profileDirWeb'];
        if (!file_exists($targetLink) && file_exists(Yii::$app->params['profileDir'])) {
            @symlink(Yii::$app->params['profileDir'], $targetLink);
        }
    }

    /**
     * Invalidate Cache
     */
    protected function invalidateCache()
    {
        TagDependency::invalidate(Yii::$app->cache, [
            'Users.user_id.' . $this->user_id,
            'Users.user_name.' . $this->user_full_name,
            'Users.user_email.' . $this->user_email,
            'Users.user_hash.' . $this->user_hash,
            'Users.password_reset_token.' . $this->password_reset_token,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->invalidateCache();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAssignedLessons()
    {
        $ret = self::getDb()->cache(
            function($db) {
                return StudentsTimeline::find()
                    ->where('(student_user_id = :student_user_id) AND (teacher_user_id IS NOT NULL) AND (timeline_timestamp > :now)', [
                        'student_user_id' => $this->user_id,
                        'now' => time(),
                    ])
                    ->orderBy(['timeline_timestamp' => SORT_ASC])
                    ->all();
            },
            self::$CACHE_TTL,
            new TagDependency(['tags' => 'StudentsTimeline.student_user_id.' . $this->user_id])
        );

        $this->_user_lessons_assigned = sizeof($ret);

        return $ret;
    }

    /**
     * @param $event
     */
    public static function afterLogin($event)
    {
        /** @var \common\models\Users $User */
        $User = $event->identity;
        if ($User) {
            if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
                $User->user_last_ip = Yii::$app->request->getUserIP();
            }
            $User->user_last_visit = date(SQL_DATE_FORMAT);
            $User->additional_service_info = serialize($_SERVER);
            $User->save();
        }
    }

    /**
     * @param $user_custom_messengers
     * @return array
     */
    public static function staticGetCustomMessengers($user_custom_messengers)
    {
        $_messengers = unserialize($user_custom_messengers);
        if (!is_array($_messengers)) { $_messengers = []; }
        if (!isset($_messengers['_user_skype'])) { $_messengers['_user_skype'] = ''; }
        if (!isset($_messengers['_user_telegram'])) { $_messengers['_user_telegram'] = ''; }
        if (!isset($_messengers['_user_viber'])) { $_messengers['_user_viber'] = ''; }
        if (!isset($_messengers['_user_jabber'])) { $_messengers['_user_jabber'] = ''; }
        if (!isset($_messengers['_user_icq'])) { $_messengers['_user_icq'] = ''; }
        return $_messengers;
    }

    /**
     *
     */
    public function getCustomMessengers()
    {
        $this->_messengers = self::staticGetCustomMessengers($this->user_custom_messengers);
        foreach ($this->_messengers as $m_k => $m_v) {
            $this->$m_k = $m_v;
        }
    }

    /**
     * @param int $user_id
     * @param string $user_email
     * @return string
     */
    public static function generateConferenceRoomHash($user_id, $user_email)
    {
        return md5($user_id . $user_email . self::CONFERENCE_ROOM_HASH_SALT);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        /**/
        if ($this->user_type == self::TYPE_STUDENT) {
            //$this->getAssignedLessons();
        }

        /**/
        $this->_user_conference_room_hash = self::generateConferenceRoomHash($this->user_id, $this->user_email);

        /**/
        $this->getCustomMessengers();

        /**/
        //$tz = Functions::get_list_of_timezones('short_name');
        $tz = Functions::get_list_of_timezones('offset_short_name');
        if (isset($tz[$this->user_timezone])) {
            $this->_user_timezone_short_name = $tz[$this->user_timezone];
        }
        unset($tz);
        $tz = Functions::get_list_of_timezones('name');
        if (isset($tz[$this->user_timezone])) {
            $this->_user_timezone_name = $tz[$this->user_timezone];
        }
        $this->_user_local_time = time() + $this->user_timezone;

        /**/
        $this->_old_user_type = $this->user_type;
        $this->_old_operator_user_id = $this->operator_user_id;
        $this->_old_methodist_user_id = $this->methodist_user_id;
        $this->_old_teacher_user_id = $this->teacher_user_id;

        $this->_old_user_photo = $this->user_photo;
        $this->_old_user_local_video = $this->user_local_video;
        $this->_old_user_youtube_video = $this->user_youtube_video;

        /**/
        $this->user_last_ip = long2ip($this->user_last_ip);

        /**/
        $this->_user_age = $this->getAge();

        /**/
        $this->_user_display_name = self::getDisplayName($this->user_first_name, $this->user_last_name);

        /**/
        self::createProfileDirSymlink();

        /**/
        $this->_user_last_visit = time() - strtotime($this->user_last_visit);
        $this->_user_online = ($this->_user_last_visit <= self::ONLINE_TTL);

        $minutes_ago = $this->_user_last_visit / 60;
        if ($minutes_ago < 60) {
            $this->_user_last_visit = Functions::left_minutes_ru_text($minutes_ago)[1] . ' ??????????';
        } elseif ($minutes_ago < 60*24) {
            $this->_user_last_visit = Functions::in_hours_ru_text($minutes_ago/60)[1] . ' ??????????';
        } else {
            $this->_user_last_visit = $this->user_last_visit;
        }


    }

    /**
     * @param string $user_first_name
     * @param string $user_last_name
     * @return string
     */
    public static function getDisplayName($user_first_name, $user_last_name)
    {
        return ucfirst($user_first_name) . ' ' . ($user_last_name ? ucfirst(mb_substr($user_last_name, 0, 1)) . '.' : '');
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            /* profile completed */
            if (!$this->isNewRecord /*&& $this->teacher_profile_completed == self::TEACHER_PROFILE_APPROVED*/) {


                if ($this->teacher_profile_completed == self::TEACHER_PROFILE_WAIT_APPROVE) {

                    if (!$this->user_photo) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_local_video && !$this->user_youtube_video) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_are_native) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_price_peer_hour) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_additional_info) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }

                } elseif ($this->teacher_profile_completed == self::TEACHER_PROFILE_APPROVED) {
                    if ($this->user_photo != $this->_old_user_photo) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    /*
                    if ($this->user_local_video != $this->_old_user_local_video) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    */
                    if ($this->user_youtube_video != $this->_old_user_youtube_video) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_are_native) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_price_peer_hour) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                    if (!$this->user_additional_info) {
                        $this->teacher_profile_completed = self::TEACHER_PROFILE_NEW;
                    }
                }
            }

            /**/
            $_messengers = self::staticGetCustomMessengers($this->user_custom_messengers);
            foreach ($_messengers as $m_k => $m_v) {
                $this->_messengers[$m_k] = $this->$m_k;
            }
            $this->user_custom_messengers = serialize($this->_messengers);

            /* ???????????????? ?????????????? ???????????????????? ?? ???????????????????? ?????????? ??????????, ???????? ?????? ?????? ?????????????????? */
            if (!$this->isNewRecord && $this->_old_user_type && $this->_old_user_type != $this->user_type) {

                MethodistSchedule::deleteAll(['methodist_user_id' => $this->user_id]);
                MethodistSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
                MethodistTimeline::deleteAll(['methodist_user_id' => $this->user_id]);
                MethodistTimeline::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);

                TeachersSchedule::deleteAll(['teacher_user_id' => $this->user_id]);
                TeachersSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
                StudentsTimeline::deleteAll(['teacher_user_id' => $this->user_id]);

                StudentsSchedule::deleteAll(['student_user_id' => $this->user_id]);
                StudentsTimeline::deleteAll(['student_user_id' => $this->user_id]);

            }

            /**/
            if (!$this->isNewRecord && $this->user_type == self::TYPE_STUDENT) {

                /* ???????? ?????????? ?????????????? */
                // ???????? (_old_teacher_user_id !== null) ?? (_old_teacher_user_id != teacher_user_id)
                // ?????? ???????? teacher_user_id == null
//                if ((($this->_old_teacher_user_id !== null) && ($this->_old_teacher_user_id != $this->teacher_user_id)) || !$this->teacher_user_id) {
//
//                    /**/
//                    if ($this->_old_teacher_user_id) {
//                        $lessons_available = StudentsTimeline::deleteAll('
//                        (student_user_id = :student_user_id) AND
//                        (teacher_user_id = :teacher_user_id) AND
//                        (is_introduce_lesson != :YES)
//                        ', [
//                            'student_user_id' => $this->user_id,
//                            'teacher_user_id' => $this->_old_teacher_user_id,
//                            'YES' => StudentsTimeline::YES
//                        ]);
////                        $lessons_available = StudentsTimeline::deleteAll([
////                            'student_user_id' => $this->user_id,
////                            'teacher_user_id' => $this->_old_teacher_user_id,
////                        ]);
//                        TeachersSchedule::updateAll(['student_user_id' => null], [
//                            'student_user_id' => $this->user_id,
//                            'teacher_user_id' => $this->_old_teacher_user_id,
//                        ]);
//                    } else {
//                        $lessons_available = StudentsTimeline::deleteAll([
//                            'student_user_id' => $this->user_id,
//                        ]);
//                        TeachersSchedule::updateAll(['student_user_id' => null], [
//                            'student_user_id' => $this->user_id,
//                        ]);
//                    }
//
//                    /* ?????????????? ???????????????????? ?????????????? ?? ?????????????????? ?????? ???????????? STATUS_AFTER_PAYMENT */
//                    StudentsSchedule::deleteAll(['student_user_id' => $this->user_id]);
//                    if ($this->user_status == self::STATUS_ACTIVE) {
//                        $this->user_status = self::STATUS_AFTER_PAYMENT;
//                    }
//
//                    /* ???????????? ???? ?????????? ?????????????? ?????? ???? ???????????????????????? */
//                    $this->user_lessons_available += $lessons_available;
//
//                    $this->user_last_lesson = null;
//
//                }

            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        /**/
        parent::afterSave($insert, $changedAttributes);

        /* ?????????????? ?????????????????? ???????????? ?? ?????????????? */
        $this->invalidateCache();

        /**/
        if ($this->user_type == self::TYPE_TEACHER) {
            TagDependency::invalidate(Yii::$app->cache, ['Users.Teachers']);
        }

        /* ???????????????? ?????????????? ???????????????????? ?? ???????????????????? ?????????? ??????????, ???????? ?????? ???????????? ???????????? ?????? ?????????????????? */
        if (!$this->isNewRecord && in_array($this->user_status, [self::STATUS_DELETED, self::STATUS_INACTIVE])) {

            MethodistSchedule::deleteAll(['methodist_user_id' => $this->user_id]);
            MethodistSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
            MethodistTimeline::deleteAll(['methodist_user_id' => $this->user_id]);
            MethodistTimeline::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);

            TeachersSchedule::deleteAll(['teacher_user_id' => $this->user_id]);
            TeachersSchedule::updateAll(['student_user_id' => null], ['student_user_id' => $this->user_id]);
            StudentsTimeline::deleteAll(['teacher_user_id' => $this->user_id]);

            StudentsSchedule::deleteAll(['student_user_id' => $this->user_id]);
            StudentsTimeline::deleteAll(['student_user_id' => $this->user_id]);

        }
    }

    /**
     * @param $user_photo
     * @param $default_path_if_no_photo
     * @return string
     */
    public static function staticGetProfilePhotoForWeb($user_photo, $default_path_if_no_photo)
    {
        if ($user_photo) {
            return Yii::$app->params['profileDirWeb'] . "/" . $user_photo;// . "?rnd=" . mt_rand(1, 10000000);
        } else {
            return $default_path_if_no_photo;
        }
    }

    /**
     * @param string $default_path_if_no_photo
     * @return string
     */
    public function getProfilePhotoForWeb($default_path_if_no_photo)
    {
        return self::staticGetProfilePhotoForWeb($this->user_photo, $default_path_if_no_photo);
//        if ($this->user_photo) {
//            return Yii::$app->params['profileDirWeb'] . "/" . $this->user_photo;// . "?rnd=" . mt_rand(1, 10000000);
//        } else {
//            return $default_path_if_no_photo;
//        }
    }

    /**
     * @return bool
     */
    public function deleteProfilePhoto()
    {
        if (isset($this->user_photo)) {
            FileSys::remove(Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $this->user_photo);
            $this->user_photo = null;
            $this->save();
        }
        return true;
    }

    /**
     * @return array
     */
    public function addProfilePhoto()
    {
        $fi = FileSys::pathinfo($_FILES['user_profile_photo']['name']);
        $tmp = intval(floor($this->user_id / 100)) * 100;
        $web_dst = $tmp . DIRECTORY_SEPARATOR .
            'UserID-' . $this->user_id . DIRECTORY_SEPARATOR .
            md5(mt_rand(0,1000) . time() . $this->user_id) . "." . $fi['extension'];
        $dst = Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $web_dst;
        FileSys::mkdir(dirname($dst));

        self::createProfileDirSymlink();

        if ($this->user_photo) {
            $old_user_photo = $this->user_photo;
        }
        FileSys::remove($dst);
        if (move_uploaded_file($_FILES['user_profile_photo']['tmp_name'], $dst)) {
            $this->user_photo = $web_dst;
            $image = Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $this->user_photo;
            Image::thumbnail($image, 140, 140)
                ->save($image, ['quality' => 100]);
            if (isset($old_user_photo)) {
                FileSys::remove(Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $old_user_photo);
            }
            if ($this->save()) {
                return [
                    'type' => 'success',
                    'msg' => 'success msg',
                    'imgSrc' => Yii::$app->params['profileDirWeb'] . "/{$web_dst}?rnd=" . mt_rand(1, 10000000),
                ];
            } else {
                return [
                    'type' => 'error',
                    'msg' => 'DB error',
                ];
            }
        } else {
            return [
                'type' => 'error',
                'msg' => 'Error during upload process',
            ];
        }
    }

    /**
     * @return bool
     */
    public function deleteProfileVideo()
    {
        if (isset($this->user_local_video)) {
            FileSys::remove(Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $this->user_local_video);
            $this->user_local_video = null;
            $this->save();
        }
        return true;
    }

    /**
     * @return array
     */
    public function addProfileVideo()
    {
        $fi = FileSys::pathinfo($_FILES['user_profile_video']['name']);
        $tmp = intval(floor($this->user_id / 100)) * 100;
        $web_dst = $tmp . DIRECTORY_SEPARATOR .
            'UserID-' . $this->user_id . DIRECTORY_SEPARATOR .
            md5(mt_rand(0,1000) . time() . $this->user_id) . "." . $fi['extension'];
        $dst = Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $web_dst;
        FileSys::mkdir(dirname($dst));

        self::createProfileDirSymlink();

        if ($this->user_photo) {
            $old_user_video = $this->user_local_video;
        }
        FileSys::remove($dst);
        if (move_uploaded_file($_FILES['user_profile_video']['tmp_name'], $dst)) {
            $this->user_local_video = $web_dst;
            $video = Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $this->user_local_video;

            if (isset($old_user_video)) {
                FileSys::remove(Yii::$app->params['profileDir'] . DIRECTORY_SEPARATOR . $old_user_video);
            }
            if ($this->save()) {
                return [
                    'type' => 'success',
                    'msg' => 'success msg',
                    'imgSrc' => Yii::$app->params['profileDirWeb'] . "/{$web_dst}?rnd=" . mt_rand(1, 10000000),
                ];
            } else {
                return [
                    'type' => 'error',
                    'msg' => 'DB error',
                ];
            }
        } else {
            return [
                'type' => 'error',
                'msg' => 'Error during upload process',
            ];
        }
    }

    /**
     * @param int $timestamp_gmt
     * @param string|null $format
     * @param bool $display_timezone
     * @return string
     */
    public function getDateInUserTimezoneByTimestamp($timestamp_gmt, $format=null, $display_timezone=true)
    {
        $timestamp_gmt = intval($timestamp_gmt);
        if (!$format) {
            $format = Yii::$app->params['datetime_short_format'];
        }
        return date(
            $format,
            $timestamp_gmt + $this->user_timezone
        ) . ($display_timezone ?  ' ' . $this->_user_timezone_short_name : '');
    }

    /**
     * @param string $date_string_gmt
     * @param string|null $format
     * @param bool $display_timezone
     * @return string
     */
    public function getDateInUserTimezoneByDateString($date_string_gmt, $format=null, $display_timezone=true)
    {
        return $this->getDateInUserTimezoneByTimestamp(strtotime($date_string_gmt), $format, $display_timezone);
    }

    /**
     * @param $_user_last_visit
     * @return string
     */
    public function getUserOnlineStatus($_user_last_visit)
    {
        return mb_strrpos($_user_last_visit, '??????????') > 0
            ? $_user_last_visit
            : $this->getDateInUserTimezoneByDateString($_user_last_visit);
    }

    /**
     *
     */
    public $___user_price_key,
        $___country_name,
        $___city_name,
        $___country_code,
        $___native_vars,
        $___speak_also_vars,
        $___user_goals_of_education;
    public function initAdditionalDataForModel()
    {
        /* price */
        $user_price_key = 'econom';
        foreach (Users::$_price_vars as $k=>$v) {
            if ($this->user_price_peer_hour >= $v['min'] && $this->user_price_peer_hour <= $v['max']) {
                $user_price_key = $k;
                break;
            }
        }
        $this->___user_price_key = $user_price_key;

        /**/
        $lang = Yii::$app->language;

        /* country */
        $country_name_field = "title_{$lang}";
        if ($this->country_id) {
            $Country = $this->getCountry();
            if (!$Country->hasAttribute($country_name_field)) {
                $country_name_field = "title_en";
            }
            $country_name = $Country->{$country_name_field};
            $this->___country_code = $Country->country_code;
        } else {
            $country_name = '';
            $this->___country_code = 'undefined';
        }
        $this->___country_name = $country_name;

        /* city */
        $city_name_field = "title_{$lang}";
        if ($this->city_id) {
            $City = $this->getCity();
            if (!$City->hasAttribute($city_name_field)) {
                $city_name_field = "title_en";
            }
            $city_name = $City->{$city_name_field};
        } else {
            $city_name = '';
        }
        $this->___city_name = $city_name;

        /* native */
        $native_vars = [];
        $_user_are_native = unserialize($this->user_are_native);
        foreach (self::$_languages as $key => $item) {
            if (!empty($_user_are_native[$item])) {
                $native_vars[] = Yii::t('models/Users', $item);
            }
        }
        $this->___native_vars = $native_vars;

        /* speak also */
        $speak_also_vars = [];
        $_user_speak_also = unserialize($this->user_speak_also);
        //var_dump($_user_speak_also);
        foreach (self::$_languages as $key => $item) {
            if (!empty($_user_speak_also[$item])) {
                $speak_also_vars[] = Yii::t('models/Users', $item) . " ({$_user_speak_also[$item]})";
            }
        }
        $this->___speak_also_vars = $speak_also_vars;

        /* user_goals_of_education (specialization) */
        $user_goals_of_education = [];
        $_user_goals_of_education = unserialize($this->user_goals_of_education);
        foreach (self::$_goals_of_education as $key => $item) {
            if (!empty($_user_goals_of_education[$item])) {
                $user_goals_of_education[] = Yii::t('models/Users', $item);
            }
        }
        $this->___user_goals_of_education = $user_goals_of_education;
    }
}
