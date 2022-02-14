<?php

namespace backend\controllers;

use Yii;
use backend\components\SController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Users;
use backend\models\search\UsersSearch;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends SController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function(/*$rule, $action*/) {
                            if (!$this->checkAccess()) {
                                return false;
                            }
                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();

        $model->setPassword(uniqid());
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        $model->admin_user_id = $this->CurrentUser->user_id;
        //$model->generateUserHash();
        //$model->generateUserToken();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            /* если это текущий пользователь */
            if ($this->CurrentUser->user_id == $id) {
                if ($this->CurrentUser->user_type != $model->user_type) {
                    Yii::$app->session->setFlash('danger', "You can't change your role.");
                    return $this->redirect(['update', 'id' => $id]);
                }
                if ($this->CurrentUser->user_status != $model->user_status) {
                    Yii::$app->session->setFlash('danger', "You can't change your status.");
                    return $this->redirect(['update', 'id' => $id]);
                }
                if ($this->CurrentUser->user_email != $model->user_email) {
                    Yii::$app->session->setFlash('danger', "You can't change your email.");
                    return $this->redirect(['update', 'id' => $id]);
                }
            /* если это другой юзер а не текущий */
            } else {

            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->user_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionGenerateToken($id)
    {
        $model = $this->findModel($id);

        $model->generateUserToken();
        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteToken($id)
    {
        $model = $this->findModel($id);

        $model->deleteUserToken();
        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->CurrentUser->user_id == $id) {
            Yii::$app->session->setFlash('danger', "You can't delete yourself");
        } else {
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
