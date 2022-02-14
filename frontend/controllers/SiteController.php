<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\Cookie;
use frontend\components\AuthHandler;
use frontend\components\SController;
use common\helpers\Functions;
use common\models\Users;
use common\models\Cities;
use common\models\Regions;
use common\helpers\FileSys;
use common\models\Reviews;
use frontend\models\StaticActionModel;
use frontend\models\forms\ContactForm;
use frontend\models\forms\SignupForm;
use frontend\models\forms\LoginForm;
use frontend\models\forms\PasswordResetRequestForm;
use frontend\models\forms\ResetPasswordForm;
use frontend\models\forms\VerifyEmailForm;
use frontend\models\search\TutorSearch;

/**
 * Site controller
 *
 * @property \frontend\models\forms\LoginForm $model_login
 * @property \frontend\models\forms\SignupForm $model_signup
 * @property \frontend\models\forms\PasswordResetRequestForm $model_password_reset_request
 */
class SiteController extends SController
{
    public $model_login;
    public $model_signup;
    public $model_password_reset_request;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup', 'login'],
                'rules' => [
                    [
                        'actions' => [

                            'auth',
                            'error',
                            'maintenance',

                            'login-by-token',
                            'verify-email',

                            'save-contact-us-form',

                            'set-user-timezone',
                            'set-currency',
                            'find-tutors-request',
                            'tutor-reviews',
                            'store-js-console-log',

                            'get-geo-regions-list-for-country',
                            'get-geo-cities-list-for-region',
                            'get-geo-cities-list-for-country',

                            'index',
                            'static',

                            'payment-success',

                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => [
                            'signup',
                            'login',
                            'request-password-reset',
                            'reset-password',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                /* функция которая обработает запрет на доступ к акшену (если не указать будет использована стандартная) */
                'denyCallback' => function($rule, $action) {
                    if ($this->CurrentUser) {
                        return $this->redirect(['user/']);
                    } else {
                        return $this->redirect(['/']);
                        //throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                    }
                },
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param $client
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layout = 'guest-main';
        $this->model_login = new LoginForm();
        $this->model_signup = new SignupForm();
        $this->model_password_reset_request = new PasswordResetRequestForm();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        /**/
        $test_social_redirect = Yii::$app->session->get('after_link_social_redirect', null);
        if ($test_social_redirect) {
            Yii::$app->session->remove('after_link_social_redirect');
            return $this->redirect($test_social_redirect);
        }

        /**/
        $static_action = Yii::$app->request->get('action');
        if (
            !$this->CurrentUser && !Yii::$app->request->isAjax &&
            isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] && !in_array($_SERVER['REQUEST_URI'], ['/']) &&
            in_array($this->action->id, ['static']) && in_array($static_action, ['find-tutors', 'disciplines', 'tutor'])
        ) {


                Yii::$app->session->set('return_url_after_signup_login', $_SERVER['REQUEST_URI']);

        }
        if (
            !in_array($this->action->id, ['static', 'login', 'signup', 'login-by-token', 'error', 'auth']) &&
            !in_array($static_action, ['find-tutors', 'disciplines', 'tutor']) &&
            !Yii::$app->request->isAjax
        ) {

            Yii::$app->session->remove('return_url_after_signup_login');

        }

        /**/
        return parent::beforeAction($action);
    }


    //----------------------------------------------------------//


    /**
     * @param string $token
     * @return \yii\web\Response
     */
    public function actionLoginByToken($token)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            Yii::$app->session->destroy();
            Yii::$app->response->cookies->remove('_identity-frontend');
            Yii::$app->response->cookies->remove('advanced-frontend');
            sleep(1);
        }

        $User = Users::findByToken($token);
        //if ($User && Yii::$app->user->login($User, 0)) {
        if ($User) {

            /* для админа разрешен логин только через специальный домен */
            if ($User->user_type == Users::TYPE_ADMIN) {
                if (!Yii::getAlias('@adminWeb', false) || !Yii::getAlias('@adminDomain')) {
                    Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'LoginByToken_error', ['ErrorCode' => 'SecurityErrorConfig']));
                    return $this->goHome();
                }
                if (Yii::$app->request->hostName != Yii::getAlias('@adminDomain')) {
                    Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'LoginByToken_error', ['ErrorCode' => 'SecurityErrorDomain']));
                    return $this->goHome();
                }
            }

            if (Yii::$app->user->login($User, Users::LOGIN_COOKIE_TTL)) {
                Yii::$app->language = $User->last_system_language;
                if (isset($_GET['is_from_admin_for_manager_users'])) {
                    Yii::$app->session->set('is_from_admin_for_manager_users', true);
                } else {
                    Yii::$app->session->remove('is_from_admin_for_manager_users');
                }
                return $this->redirect(['user/index']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'LoginByToken_error', ['ErrorCode' => 'login failed']));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'LoginByToken_error', ['ErrorCode' => 'User not found']));
        }

        return $this->goHome();
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return \yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            //throw new BadRequestHttpException($e->getMessage());
            Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'VerifyEmail_error', ['ErrorCode' => $e->getMessage()]));
            return $this->redirect(['/']);
        }
        if ($user = $model->verifyEmail()) {
            if ($this->CurrentUser && $this->CurrentUser->user_id != $user->user_id) {
                Yii::$app->user->logout();
                Yii::$app->user->login($user, Users::LOGIN_COOKIE_TTL);
            }
            if (!$this->CurrentUser) {
                Yii::$app->user->login($user, Users::LOGIN_COOKIE_TTL);
            }
            //if (Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', Yii::t('controllers/site', 'VerifyEmail_success'));
            //return $this->goHome();
            return $this->redirect(['user/']);
            //}
        }

        Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'VerifyEmail_error', ['ErrorCode' => 'unknown']));
        return $this->goHome();
    }


    //----------------------------------------------------------//


    /**
     * @return string
     */
    public function actionSaveContactUsForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ContactForm();
        $model->_validate_pattern = false;
        if (!$model->load(Yii::$app->request->post())) {
            return [
                'status' => false,
                'error'  => $model->getErrors(),
            ];
        }

        if (!$model->validate()) {
            return [
                'status' => false,
                'error'  => $model->getErrors(),
            ];
        }

        $model->saveRequest($this->CurrentUser);

        return [
            'status' => true,
            'info'   => Yii::t('controllers/site', 'ContactUsFormSent'),
        ];
    }


    //----------------------------------------------------------//


    /**
     * @param $utz
     * @return string
     */
    public function actionSetUserTimezone($utz)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->session->set('js_user_time_zone', intval($utz));

        return [
            'status' => true,
        ];
    }

    /**
     * @param $currency
     * @return Response
     */
    public function actionSetCurrency($currency)
    {
        if (isset(Yii::$app->params['exchange']['usd'][$currency])) {
            //Yii::$app->session->set('current----_currency', $currency);
            $cookie = new Cookie([
                //'httpOnly' => true,
                'name' => '_currency',
                'value' => $currency,
                'expire' => time() + (int) Yii::$app->urlManager->languageCookieDuration,
            ]);
            Yii::$app->response->cookies->add($cookie);
            //if ($this->CurrentUser) {
            //    $this->CurrentUser->last_system_currency = $currency;
            //}
        }

        //var_dump($this->goBack());exit;
        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['user/']);
        }
    }

    /**
     * @return string
     */
    public function actionFindTutorsRequest()
    {
        Yii::$app->getSession()->id;
        $model = new TutorSearch();
        if (isset($_POST['sort'])) { $_GET['sort'] = $_POST['sort']; }
        if (isset($_POST['page'])) { $_GET['page'] = $_POST['page']; }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $model->search(Yii::$app->request->queryParams);

            /**/
            $return = $this->renderPartial('find-tutors-request', [
                'result' => $result,
                'modelSearch' => $model,
                'CurrentUser' => $this->CurrentUser,
            ]);
            Yii::$app->cache->set('FindTutorsRequestCacheForeHistoryBack_for_session_id_' . Yii::$app->getSession()->id, $return);

            /**/
            return $return;
        }

        return $this->redirect(['/']);
    }

    /**
     * @param int $teacher_user_id
     * @return string
     */
    public function actionTutorReviews($teacher_user_id)
    {
        return $this->renderPartial('static/tutor-reviews', [
            'reviews' => Reviews::find()->where(['teacher_user_id' => $teacher_user_id])->orderBy(['review_created' => SORT_DESC])->all(),
            'CurrentUser' => $this->CurrentUser,
        ]);
    }

    /**
     * @return string
     */
    public function actionStoreJsConsoleLog()
    {
        //$this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset(Yii::$app->params['jsConsoleLogDir']) && isset($_POST['logs'])) {

            /**/
            $storeDir = Yii::$app->params['jsConsoleLogDir'];
            if (!file_exists($storeDir)) {
                FileSys::mkdir($storeDir, 0777);
                chmod($storeDir, 0777);
            }

            /**/
            if ($this->CurrentUser) {
                $storeDir .= DIRECTORY_SEPARATOR . $this->CurrentUser->user_email;
            } else {
                $storeDir .= DIRECTORY_SEPARATOR . '__GUEST__';
            }
            if (!file_exists($storeDir)) {
                FileSys::mkdir($storeDir, 0777);
                chmod($storeDir, 0777);
            }

            /**/
            $storeDir .= DIRECTORY_SEPARATOR . date('Y-m-d');
            if (!file_exists($storeDir)) {
                FileSys::mkdir($storeDir, 0777);
                chmod($storeDir, 0777);
            }

            /**/
            $browser = Functions::getBrowserByUserAgent(Yii::$app->request->userAgent);
            $os = Functions::getOsTypeByUserAgent(Yii::$app->request->userAgent);
            if (isset(Yii::$app->request) && method_exists(Yii::$app->request, 'getUserIP')) {
                $user_ip = Yii::$app->request->getUserIP();
            }
            $storeFile = $storeDir . DIRECTORY_SEPARATOR . $user_ip . "-{$os}-{$browser}.log";
            FileSys::fwrite($storeFile, $_POST['logs'], 0666, 'a');
        }

        return [
            'status' => true,
        ];
    }


    //----------------------------------------------------------//


    /**
     * @param int $country_id
     * @return array
     */
    public function actionGetGeoRegionsListForCountry($country_id)
    {
        /**/
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $country_id =intval($country_id);

        /**/
        $lang = Yii::$app->language;
        $region_name_field = "title_{$lang}";
        $Regions = new Regions();
        if (!$Regions->hasAttribute($region_name_field)) {
            $region_name_field = 'title_en';
        }

        /**/
        $regions = Yii::$app->cache->get("geo_regions_list_for_country_{$country_id}_{$lang}");
        if (!$regions) {
            $regions = Regions::find()
                ->select("region_id, {$region_name_field} as region_name")
                ->where(['country_id' => $country_id])
                ->orderBy(['region_name' => SORT_ASC])
                ->asArray()
                ->all();
            Yii::$app->cache->set("geo_regions_list_for_country_{$country_id}_{$lang}", $regions, CACHE_TTL);
        }

        /**/
        return [
            'status' => true,
            'data'   => $regions,
        ];
    }

    /**
     * @param int $region_id
     * @return array
     */
    public function actionGetGeoCitiesListForRegion($region_id)
    {
        /**/
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $region_id = intval($region_id);

        /**/
        $lang = Yii::$app->language;
        $city_name_field = "title_{$lang}";
        $Cities = new Regions();
        if (!$Cities->hasAttribute($city_name_field)) {
            $city_name_field = 'title_en';
        }

        /**/
        $cities = Yii::$app->cache->get("geo_cities_list_for_region_{$region_id}_{$lang}");
        if (!$cities) {
            $cities = Cities::find()
                ->select("city_id, {$city_name_field} as city_name")
                ->where(['region_id' => $region_id])
                ->orderBy(['city_name' => SORT_ASC])
                ->asArray()
                ->all();
            Yii::$app->cache->set("geo_cities_list_for_region_{$region_id}_{$lang}", $cities, CACHE_TTL);
        }

        /**/
        return [
            'status' => true,
            'data'   => $cities,
        ];
    }

    /**
     * @param int $country_id
     * @return array
     */
    public function ionGetGeoCitiesListForCountry($country_id)
    {
        /**/
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**/
        $country_id = intval($country_id);

        /**/
        $lang = Yii::$app->language;
        $city_name_field = "title_{$lang}";
        $Cities = new Regions();
        if (!$Cities->hasAttribute($city_name_field)) {
            $city_name_field = 'title_en';
        }

        /**/
        $cities = Yii::$app->cache->get("geo_cities_list_for_country_{$country_id}_{$lang}");
        if (!$cities) {
            $cities = Cities::find()
                ->select("city_id, {$city_name_field} as city_name")
                ->where(['country_id' => $country_id])
                ->orderBy(['city_name' => SORT_ASC])
                ->asArray()
                ->all();
            Yii::$app->cache->set("geo_cities_list_for_country_{$country_id}_{$lang}", $cities, CACHE_TTL);
        }

        /**/
        return [
            'status' => true,
            'data'   => $cities,
        ];
    }


    //----------------------------------------------------------//


    /**
     * Displays homepage.
     * @return mixed
     */
    public function actionIndex()
    {
        if ($this->CurrentUser) {
            return $this->redirect(['user/']);
        }

        return $this->render('static/index', [
            //'LoginFormModel' => $this->model_login,
            'CurrentUser' => $this->CurrentUser,
        ]);
    }

    /**
     * Этот акшен обработает страницы которые описаны в правилах
     * реврайта конфига frontend/config/main.php  urlManager/rules
     * в случае если имеется для них виевка
     * @return string
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionStatic()
    {
        //var_dump($_GET);
        $action = Yii::$app->request->get('action');
        $no_header = (bool) Yii::$app->request->get('header-free', 0);
        $empty_layout = (bool) Yii::$app->request->get('empty-layout', 0);
        //var_dump($action);exit;

        if (file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/site/static/' . $action . '.php')) {
            if ($no_header && file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/layouts/main-no-header-no-footer.php')) {
                $this->layout = 'main_no_header_no_footer';
            }
            if ($empty_layout && file_exists(Yii::getAlias('@frontend').'/themes/' . DESIGN_THEME . '/layouts/main-empty.php')) {
                $this->layout = 'main-empty';
            }

            /* поиск метода в модели StaticActionModel */
            $test = explode('-', $action);
            foreach ($test as $k=>$partAction) {
                if ($k > 0) {
                    $test[$k] = ucfirst($partAction);
                }
            }
            $method_action = implode('', $test);
            $model = new StaticActionModel();
            //var_dump($method_action);exit;
            if (method_exists($model, $method_action)) {
                try {
                    $additionalData = $model->{$method_action}($_GET, $this->CurrentUser);
                } catch (InvalidArgumentException $e) {
                    throw new BadRequestHttpException($e->getMessage());
                }
            } else {
                $additionalData = null;
            }

            /**/
            return $this->render('static/' . $action, [
                'CurrentUser' => $this->CurrentUser,
                'additionalData'     => $additionalData,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    //----------------------------------------------------------//


    /**
     * @return string|Response
     */
    public function actionPaymentSuccess()
    {
        /**/
        Yii::$app->session->set('payment_success', true);

        /**/
        //$this->layout = 'member-after';
        $this->layout = 'member-no-header-no-footer';

        return $this->render('/student/payment-success', [
            'CurrentUser' => $this->CurrentUser,
        ]);
    }


    //----------------------------------------------------------//


    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        //return $this->goHome();


        $model = new SignupForm();

        /**/
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        /**/
        if ($model->load(Yii::$app->request->post()) && /*$model->signup()*/ ($user = $model->signup())) {
            if (Yii::$app->user->login($user, Users::LOGIN_COOKIE_TTL)) {
                Yii::$app->session->setFlash('success', Yii::t('controllers/site', 'Signup_success'));
                return $this->redirect(['/user']);
            }
        } else {
            //var_dump($user); exit;
        }

        return $this->render('static/index', [
            'CurrentUser' => $this->CurrentUser,
        ]);

    }

    /**
     * Logs in a user.
     * @return string|Response
     * @throws HttpException
     */
    public function actionLogin()
    {

        if ($this->model_login->load(Yii::$app->request->post()) && $this->model_login->validate()) {

            if ($this->model_login->login()) {

                //return $this->goBack();
                return $this->redirect(['user/']);

            } else {

                throw new HttpException(400, 'Failed save data.');

            }

        } else {

            //var_dump($this->model_login->validate());
            //var_dump($this->model_login->getErrors());
            //var_dump($this->model_login->password);
            //exit;
            return $this->render('static/index', [
                //'LoginFormModel' => $this->model_login,
                'CurrentUser' => $this->CurrentUser,
            ]);

        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        if ($this->model_password_reset_request->load(Yii::$app->request->post()) && $this->model_password_reset_request->validate()) {
            if ($this->model_password_reset_request->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('controllers/site', 'RequestPasswordReset_success'));

                return $this->goHome();

            } else {
                Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'RequestPasswordReset_error'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'RequestPasswordReset_error'));
        }

        /**/
        return $this->render('static/index', [
            'CurrentUser' => $this->CurrentUser,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $ResetPasswordForm = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            //throw new BadRequestHttpException($e->getMessage());
            Yii::$app->session->setFlash('error', Yii::t('controllers/site', 'ResetPassword_error', ['ErrorCode' => $e->getMessage()]));
            return $this->redirect(['/']);
        }

        if ($ResetPasswordForm->load(Yii::$app->request->post()) && $ResetPasswordForm->validate() && $ResetPasswordForm->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('controllers/site', 'ResetPassword_success'));
            return $this->redirect(['/login']);
        }

        /**/
        return $this->render('static/reset-password', [
            'token' => $token,
            'ResetPasswordForm' => $ResetPasswordForm,
            'CurrentUser' => $this->CurrentUser,
        ]);
    }


    //----------------------------------------------------------//


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        Yii::$app->response->cookies->remove('_identity-frontend');
        Yii::$app->response->cookies->remove('advanced-frontend');
        sleep(1);

        return $this->redirect(['/']);
    }


    //----------------------------------------------------------//

}
