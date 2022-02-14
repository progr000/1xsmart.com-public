<?php
namespace frontend\modules\tinkoff\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\helpers\FileSys;
use frontend\modules\tinkoff\models\TinkoffApi;

class DefaultController extends Controller
{
    const STATUS_SUCCESS = "success";
    const STATUS_ERROR   = "error";

    private $error = "";
    public static $ALLOWED_METHODS = [

    ];

    /**
     * Позволяет приходить запросам на этот скрипт и акшены перечисленные в массиве
     * с других доменов а не только с домена где стоит этот скрипт
     *
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        //set_time_limit(0);
        //ini_set('memory_limit', '1G');
        $this->enableCsrfValidation = false;
        if (in_array($action->id, ['index', 'upload-video-lessons'])) {
            $this->enableCsrfValidation = false;
        }

        $log_dir = dirname(TinkoffApi::LOG_FILE);
        if (!file_exists($log_dir)) {
            FileSys::mkdir($log_dir, 0777);
            chmod($log_dir, 0777);
        }

        return parent::beforeAction($action);
    }

    /**
     * Проверяет валидность массива $request полученного методом POST из JSON строки
     *
     * @param array $request
     * @return bool
     */
    private function validate($request)
    {
        if (($request === null) || empty($request['action']) || empty($request['data'])) {
            $this->error = "Invalid JSON";
            return false;
        }

        if (!in_array($request['action'], self::$ALLOWED_METHODS)) {
            $this->error = "Not allowed method in JSON";
            return false;
        }

        if (!method_exists($this, $request['action'])) {
            $this->error = "Method not allowed for this api url";
            return false;
        }

        return true;
    }

    /**
     * Основная ф-ия обработки запросов (роутер методов)
     * Возвращает массив для ответа в формате JSON
     *
     * @return array
     */
    public function actionIndex()
    {
        //var_dump(Yii::getAlias('@frontend'));exit;
        Yii::$app->response->format = Response::FORMAT_RAW;
        //Yii::$app->language = "en";

        // получаем боди запроса
        $request = json_decode(Yii::$app->request->getRawBody(), true);
        $params['_POST'] = Yii::$app->request->post();
        $params['_GET'] = Yii::$app->request->get();
        $params['RAW'] = Yii::$app->request->getRawBody();
        $params['json_decode'] = $request;
        FileSys::fwrite(TinkoffApi::LOG_REQUEST_FILE, "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($params, true) . "\n\n\n", 0666, 'a');

        $model = new TinkoffApi();
        if ($model->load(['TinkoffApi' => $request]) && $model->validate()) {

            $model->rawRequest = $request;

            $res = $model->orderProcessing();
            if ($res['status']) {
                return "OK";
            } else {
                $error = $res['info'];
                FileSys::fwrite(TinkoffApi::LOG_ERROR_FILE, "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($error, true) . "\n" . var_export($params, true) . "\n\n\n", 0666, 'a');
            }

        } else {
            $error = $model->getErrors();
            //var_dump($request);
            //var_dump($error);
            FileSys::fwrite(TinkoffApi::LOG_ERROR_FILE, "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($error, true) . "\n" . var_export($params, true) . "\n\n\n", 0666, 'a');
        }

        //$params['error'] = $error;
        $params['_POST'] = Yii::$app->request->post();
        $params['_GET'] = Yii::$app->request->get();
        $params['RAW'] = Yii::$app->request->getRawBody();
        FileSys::fwrite(TinkoffApi::LOG_REQUEST_FILE, "  ===== " . date('Y-m-d, H:i:s') . " ====\n" . var_export($params, true) . "\n\n\n", 0666, 'a');

        //return "Something wrong in params";
        return "OK";
    }
}
