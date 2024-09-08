<?php

namespace app\services;

use Yii;
use app\services\base\BaseService;

class MailService extends BaseService
{

    public static function sendActivationMail(string $to, string $sendLink)
    {
        $activationLink = '<a href=' . $sendLink . '>' . env('API_URL') . '/user/activate?link=' . $sendLink . '</a>';
        Yii::$app->mailer->compose()
            ->setFrom(env('SMTP_USER'))
            ->setTo($to)
            ->setSubject('Активация')
            ->setHtmlBody("<b>Ты начал регаться пацан, дойди до конца.</b> <br/> Cсылка для активации - $activationLink")
            ->send();
    }
}
