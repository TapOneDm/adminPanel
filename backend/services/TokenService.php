<?php

namespace app\services;

use app\services\base\BaseService;
use Ahc\Jwt\JWT;
use app\models\Token;
use Exception;

class TokenService extends BaseService
{

    public static function generateTokens($payload)
    {
        $accessToken = (new JWT(env('JWT_SECRET_KEY'), 'HS256', 1800 ))->encode($payload);
        $refreshToken = (new JWT(env('JWT_REFRESH_KEY'), 'HS256', 86400))->encode($payload);
        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }

    public static function saveToken($userId, $refreshToken)
    {
        $tokenData = Token::findOne($userId);

        if ($tokenData) {
            $tokenData->refresh_token = $refreshToken;
            return $tokenData->save();
        }

        $token = new Token();
        $token->user_id = $userId;
        $token->refresh_token = $refreshToken;
        return $token->save();
    }

    public static function removeToken($refreshToken)
    {
        $tokenModel = Token::find()->where(['refresh_token' => $refreshToken])->one();
        $tokenModel->delete();
        return $tokenModel;
    }

    public static function validateAccessToken($token)
    {
        try {
            $userData = (new JWT(env('JWT_SECRET_KEY')))->decode($token);
            return $userData;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function validateRefreshToken($token)
    {
        try {
            $userData = (new JWT(env('JWT_REFRESH_KEY'), 'HS256', 3600))->decode($token);
            return $userData;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function findToken($token)
    {
        $tokenModel = Token::find()->where(['refresh_token' => $token])->one();
        if (isset($tokenModel)) {
            return $tokenModel->toArray();
        }
        return null;
    }
}
