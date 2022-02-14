<?php
namespace frontend\components;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use common\models\Auth;
use common\models\Users;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = trim(ArrayHelper::getValue($attributes, 'login'));
        if (!$nickname) { $nickname = $email; }

        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var Users $user */
                $user = $auth->user;
                //$this->updateUserInfo($user);
                Yii::$app->user->login($user, Users::LOGIN_COOKIE_TTL);
                Yii::$app->getSession()->set('after_link_social_redirect', '/user');
                Yii::$app->getSession()->set('after_link_social_flash', Yii::t('controllers/auth-handler', 'You_are_logged', [
                    'client' => $this->client->getTitle(),
                    'errors' => json_encode($auth->getErrors()),
                ]));
                Yii::$app->getSession()->setFlash(
                    'success',
                    [
                        'message'   => Yii::t('app', 'You are logged successfully', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                        'ttl'       => 0,
                        'showClose' => true,
                        'alert_id' => 'access-control-alert',
                        'type' => 'success',
                        //'class' => 'alert-error',
                    ]
                );
            } else { // signup
                if ($email !== null && Users::find()->where(['user_email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash(
                        'error',
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()])
                    );
                } else {
                    $password = Yii::$app->security->generateRandomString(6);

                    $user = new Users();
                    $user->user_first_name    = $nickname;
                    $user->user_last_name     = $nickname;
                    $user->user_full_name     = $nickname;
                    $user->user_email         = $email;
                    $user->user_last_ip       = '127.0.0.1';
                    $user->user_status        = Users::STATUS_BEFORE_INTRODUCE;
                    $user->user_is_confirmed  = Users::NO;
                    $user->user_type          = Users::TYPE_STUDENT;
                    $user->user_need_set_password = Users::YES;
                    $user->generateAuthKey();
                    $user->generateEmailVerificationToken();
                    $user->generatePasswordResetToken();

                    $transaction = Users::getDb()->beginTransaction();

                    if ($user->save()) {
                        $user->setPassword($password);
                        $user->save();
                        $auth = new Auth([
                            'user_id' => $user->user_id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user, Users::LOGIN_COOKIE_TTL);
                            Yii::$app->getSession()->set('after_link_social_redirect', '/user');
                            Yii::$app->getSession()->set('after_link_social_flash', Yii::t('controllers/auth-handler', 'You_are_registered_and_logged', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]));
                            Yii::$app->getSession()->setFlash(
                                'success',
                                [
                                    'message'   => Yii::t('app', 'You are registered and logged successfully', [
                                        'client' => $this->client->getTitle(),
                                        'errors' => json_encode($auth->getErrors()),
                                    ]),
                                    'ttl'       => 0,
                                    'showClose' => true,
                                    'alert_id' => 'access-control-alert',
                                    'type' => 'success',
                                    //'class' => 'alert-error',
                                ]
                            );
                        } else {
                            Yii::$app->getSession()->setFlash(
                                'error',
                                [
                                    'message'   => Yii::t('app', 'Unable to save {client} account: {errors}', [
                                        'client' => $this->client->getTitle(),
                                        'errors' => json_encode($auth->getErrors()),
                                    ]),
                                    'ttl'       => 0,
                                    'showClose' => true,
                                    'alert_id' => 'access-control-alert',
                                    'type' => 'error',
                                    //'class' => 'alert-error',
                                ]
                            );
                        }
                    } else {
                        Yii::$app->getSession()->setFlash(
                            'error',
                            [
                                'message'   => Yii::t('app', 'Unable to save user: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($user->getErrors()),
                                ]),
                                'ttl'       => 0,
                                'showClose' => true,
                                'alert_id' => 'access-control-alert',
                                'type' => 'error',
                                //'class' => 'alert-error',
                            ]
                        );
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->identity->getId(),
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var Users $user */
                    $user = $auth->user;
                    //$this->updateUserInfo($user);
                    Yii::$app->getSession()->set('after_link_social_redirect', '/user/settings-and-profile?tab=profile');
                    Yii::$app->getSession()->set('after_link_social_flash',Yii::t('controllers/auth-handler', 'Linked_account', [
                        'client' => $this->client->getTitle()
                    ]));
                    Yii::$app->getSession()->setFlash(
                        'success',
                        [
                            'message'   => Yii::t('app', 'Linked {client} account.', [
                                'client' => $this->client->getTitle()
                            ]),
                            'ttl'       => 0,
                            'showClose' => true,
                            'alert_id' => 'access-control-alert',
                            'type' => 'success',
                            //'class' => 'alert-error',
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        'error',
                        [
                            'message' => Yii::t('app', 'Unable to link {client} account: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]),
                            'ttl'       => 0,
                            'showClose' => true,
                            'alert_id' => 'access-control-alert',
                            'type' => 'error',
                            //'class' => 'alert-error',
                        ]
                    );
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash(
                    'error',
                    [
                        'message' => Yii::t('app',
                            'Unable to link {client} account. There is another user using it.', [
                                'client' => $this->client->getTitle()
                        ]),
                        'ttl'       => 0,
                        'showClose' => true,
                        'alert_id' => 'access-control-alert',
                        'type' => 'error',
                        //'class' => 'alert-error',
                    ]
                );
            }
        }
    }

    /**
     * @param Users $user
     */
    private function updateUserInfo(Users $user)
    {
        $attributes = $this->client->getUserAttributes();
        $github = ArrayHelper::getValue($attributes, 'login');
        if ($user->user_middle_name === null && $github) {
            $user->user_middle_name = $github;
            $user->save();
        }
    }
}
