<?php

namespace app\components;

use app\services\TokenService;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'corsFilter'  => [
                'class' => \yii\filters\Cors::class,
                'cors'  => [
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Max-Age' => 86400,
                ],
            ],
        ]);
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: http://localhost:3000");
        $this->enableCsrfValidation = false;
        $this->enableCookieValidation = false;
        return parent::beforeAction($action);
    }

    public function checkAuth()
    {
        $headers = Yii::$app->request->getHeaders()->toArray();
        if (!isset($headers['authorization'])) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        $authData = explode(' ', $headers['authorization'][0]);
        if (!isset($authData[1])) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        $token = $authData[1];
        $userData = TokenService::validateAccessToken($token);

        if (!isset($userData)) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        return true;
    }
}
