<?php

namespace app\controllers;

use app\components\ApiController;
use app\models\User;
use app\services\UserService;
use Yii;
use \yii\web\Cookie;


class UserController extends ApiController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;

    public function actionUsers()
    {
        $users = User::getAllUsers();
        return $users;
    }

    public function actionRegistration()
    {
        $data = Yii::$app->request->getBodyParams();
        // $data = array_filter($data);exit;
        // var_dump($data);exit;
        $userData = UserService::registration($data['email'], $data['password']);
        $refreshToken = $userData['refreshToken'];
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'token',
            'value' => $refreshToken,
            'expire' => 30 * 24 * 60 * 60 * 1000,
            'httpOnly' => true,
        ]));
        return $userData;
    }

    public function actionLogin()
    {
        $data = Yii::$app->request->getBodyParams();
        $userData = UserService::login($data['email'], $data['password']);
        $refreshToken = $userData['refreshToken'];
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'token',
            'value' => $refreshToken,
            'expire' => 30 * 24 * 60 * 60 * 1000,
            'httpOnly' => true,
        ]));
        return $userData;
    }

    public function actionLogout()
    {
        if ($this->checkAuth()) {
            $refreshToken = Yii::$app->request->cookies->get('token')->value;
            $token = UserService::logout($refreshToken);
            return ['token' => $token];
        }
    }
    
    public function actionActivate($link)
    {
        UserService::activation($link);
        $this->redirect(env('CLIENT_URL'));
    }

    public function actionRefresh()
    {
        $refreshToken = Yii::$app->request->cookies->get('token')->value;
        $userData = UserService::refresh($refreshToken);
        $refreshToken = $userData['refreshToken'];
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'token',
            'value' => $refreshToken,
            'expire' => 30 * 24 * 60 * 60 * 1000,
            'httpOnly' => true,
            
        ]));       
        return $userData;
    }

    public function actionGetUsers()
    {
        if ($this->checkAuth()) {
            $users = User::find()->select(['id', 'email', 'is_activated'])->asArray()->all();
            foreach ($users as &$user) {
                $user['isActive'] = $user['is_activated'];
                unset($user['is_activated']);
            }
            return $users;
        }
    }
}