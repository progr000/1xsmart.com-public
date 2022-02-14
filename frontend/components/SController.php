<?php
namespace frontend\components;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;
use common\models\Users;

/**
 * Site controller
 *
 * @property \common\models\Users $CurrentUser
 *
 */
class SController extends Controller
{
    public $CurrentUser;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /* CurrentUser */
        if (!Yii::$app->user->isGuest) {
            $this->CurrentUser = $this->findUserModel(Yii::$app->user->identity->getId());
            //$this->CurrentUser = Yii::$app->user->identity;
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \common\models\Users $User
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUserModel($id)
    {
        if (($User = Users::findIdentity($id)) !== null) {
            if (in_array($User->user_status, [
                Users::STATUS_ACTIVE,
                Users::STATUS_AFTER_INTRODUCE,
                Users::STATUS_BEFORE_INTRODUCE,
                Users::STATUS_AFTER_PAYMENT,
            ])) {
                return $User;
            } else {
                Yii::$app->user->logout();
            }
        }

        //throw new ForbiddenHttpException('Forbidden');
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed|\yii\web\Response
     */
    public function afterAction($action, $result)
    {
        /**/
        if ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_STUDENT) {
            $return_url_after_signup_login = Yii::$app->session->get('return_url_after_signup_login', false);
            if ($return_url_after_signup_login) {
                Yii::$app->session->remove('return_url_after_signup_login');
                return $this->redirect($return_url_after_signup_login);
            }
        }

        /**/
        return parent::afterAction($action, $result);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        //var_dump(Yii::$app->request);exit;
        //var_dump(Yii::$app->urlManager->languages);exit;
        //var_dump(Yii::$app->session->get(Yii::$app->urlManager->languageSessionKey, 'undefined-sess'));exit;
        /* попробуем получить куки языка и валюты */
        $_language = Yii::$app->request->cookies->getValue('_language', 'undefined');
        $_currency = Yii::$app->request->cookies->getValue('_currency', 'undefined');
        //var_dump($_language);

        /* если куки языка нет, то найдем тут по ГЕОИП язык юзера и установим его как начальный */
        //$_default_user_lang = 'en';
        $_default_user_lang = Yii::$app->session->get(Yii::$app->urlManager->languageSessionKey, 'en');
        if ($_language == 'undefined') {
            //var_dump(Yii::$app->geoIp->getInfoDb());
            //var_dump(Yii::$app->geoIp->getInfo());exit;
            $geo = Yii::$app->geoIp->getInfoDb();
            //$geo['countryCode'] = 'us';
            //var_dump($geo['countryCode']); exit;
            if (isset($geo['countryCode'])) {
                $geo['countryCode'] = mb_strtolower($geo['countryCode']);
                //var_dump($geo['countryCode']);exit;
                if (in_array($geo['countryCode'], Yii::$app->urlManager->languages)) {
                    $_default_user_lang = $geo['countryCode']; //заменить на геоИП
                }
            }
        }

        /* если нет куки валюты, то установим ее в зависимости от языка */
        if ($_currency == 'undefined') {
            $_default_user_currency = Yii::$app->params['exchange']['default'];
            $exchange = Yii::$app->params['exchange']['usd'];
            foreach ($exchange as $k => $v) {
                if (in_array($_default_user_lang, $v['for_lang'])) {
                    $_default_user_currency = $k;
                    break;
                }
            }
            Yii::$app->response->cookies->add(new Cookie([
                //'httpOnly' => true,
                'name' => '_currency',
                'value' => $_default_user_currency,
                'expire' => time() + (int)Yii::$app->urlManager->languageCookieDuration,
            ]));
        }

        /* если куки языка нет, то редиректим на главную страницу с нужным языком */
        if ($_language == 'undefined' && in_array($_default_user_lang, Yii::$app->urlManager->languages)) {
            Yii::$app->language = $_default_user_lang;
            Yii::$app->session->set(Yii::$app->urlManager->languageSessionKey, $_default_user_lang);
            Yii::$app->response->cookies->add(new Cookie([
                //'httpOnly' => true,
                'name' => Yii::$app->urlManager->languageCookieName,
                'value' => $_default_user_lang,
                'expire' => time() + (int)Yii::$app->urlManager->languageCookieDuration,
            ]));
            return $this->redirect(['/', 'language' => $_default_user_lang]);
            //return $this->redirect(['/', 'test' => $_default_user_lang ]);
        }

        /**/
        return parent::beforeAction($action);
    }
}

