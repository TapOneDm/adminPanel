<?php

namespace app\services;

use Yii;
use app\dto\UserDto;
use app\services\base\BaseService;
use app\services\MailService;
use app\services\TokenService;
use app\models\User;
use Ramsey\Uuid\Uuid;


class UserService extends BaseService
{
    public static function registration(string $email, string $password)
    {
        $candidate = User::find()->where(['email' => $email])->one();
        if ($candidate) {
            throw new \Exception("Пользователь с почтовым адресом $email уже существует");
        }

        $userModel = new User();
        $activationLink = Uuid::uuid4();
        $userModel->create($email, $password, $activationLink);
        MailService::sendActivationMail($email, $activationLink);

        $userDto = new UserDto($userModel);
        $tokens = TokenService::generateTokens(['id' => $userDto->id, 'email' => $userDto->email, 'isActive' => $userDto->isActive]);
        TokenService::saveToken($userDto->id, $tokens['refreshToken']);

        return [...$tokens, 'user' => $userDto];
    }

    public static function activation($activationLink)
    {
        $user = User::find()->where(['activation_link' => $activationLink])->one();

        if (!$user) {
            throw new \Exception('Некорректная ссылка активации');
        }

        $user->is_activated = true;
        $user->save();
    }

    public static function login($email, $password)
    {
        $userModel = User::find()->where(['email' => $email])->one();

        if (!$userModel) {
            throw new \Exception("Пользователь не найден");
        }

        $passwordHash = Yii::$app->getSecurity()->generatePasswordHash($password);
        $isPasswordEquals = Yii::$app->getSecurity()->validatePassword($password, $passwordHash);

        if (!$isPasswordEquals) {
            throw new \Exception('Неверный пароль');
        }

        $userDto = new UserDto($userModel);
        $tokens = TokenService::generateTokens(['id' => $userDto->id, 'email' => $userDto->email, 'isActive' => $userDto->isActive]);

        TokenService::saveToken($userDto->id, $tokens['refreshToken']);

        return [...$tokens, 'user' => $userDto];
    }

    public static function logout($refreshToken)
    {
        $token = TokenService::removeToken($refreshToken);
        return $token;
    }

    public static function refresh($refreshToken)
    {
        if (!isset($refreshToken)) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        $userData = TokenService::validateRefreshToken($refreshToken);
        if (!isset($userData)) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        $tokenFromDb = TokenService::findToken($refreshToken);
        if (!$tokenFromDb) {
            throw new \yii\web\UnauthorizedHttpException('Не авторизован');
        }

        $userModel = User::findOne($userData['id']);
        $userDto = new UserDto($userModel);
        $tokens = TokenService::generateTokens(['id' => $userDto->id, 'email' => $userDto->email, 'isActive' => $userDto->isActive]);

        TokenService::saveToken($userDto->id, $tokens['refreshToken']);

        return [...$tokens, 'user' => $userDto];
    }
}
