<?php
namespace backend\components;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Users;

/**
 * Site controller
 *
 * @property \common\models\Users $CurrentUser
 *
 */
class SController extends Controller
{
    protected $CurrentUser;

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
     * @return bool
     */
    protected function checkAccess()
    {
        return ($this->CurrentUser && $this->CurrentUser->user_type == Users::TYPE_ADMIN);
    }

    /**
     * @return \yii\web\Response
     */
    protected function accessErrorRedirect()
    {
        return $this->redirect('/users');
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
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
            if (in_array($User->user_status, [Users::STATUS_ACTIVE])) {
                return $User;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

