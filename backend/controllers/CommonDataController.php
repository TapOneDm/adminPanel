<?php

namespace app\controllers;

use app\components\ApiController;
use app\models\Product;
use app\services\ProductService;
use Yii;
use app\services\TagService;

class CommonDataController extends ApiController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;

    public function actionData()
    {
        $data = [];
        $data['tags'] = TagService::getListForCommonData();
        return $data;
    }
}
