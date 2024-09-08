<?php

namespace app\controllers;

use app\components\ApiController;
use app\models\Product;
use app\models\Tag;
use app\services\ProductService;
use Yii;

class ProductController extends ApiController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;

    public function actionList()
    {
        $data = Yii::$app->request->getBodyParams();
        $result = ProductService::getList($data['filter'], $data['limit'], $data['offset']);
        return ['result' => $result];
    }

    public function actionGet($id)
    {
        $result = ProductService::getData($id);
        return ['result' => $result];
    }

    public function actionSave()
    {
        $data = Yii::$app->request->getBodyParams()['model'];
        $trx = Yii::$app->db->beginTransaction();


        $model = !empty($data['id']) ? Product::findOne($data['id']) : new Product();

        $model->load($data, '');

        $model->image_file_id = !empty($data['image']) ? $data['image']['id'] : null;
        
        if ($model->validate()) {
            $model->save();

            if (!empty($data['tags'])) {
                $model->relinkByIds($data['tags'], Tag::class, 'tags');
            }

            $trx->commit();
            return ProductService::getData($model->id);
        }

        $trx->rollBack();
        return $model->getFirstErrors();
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->getBodyParams()['id'];
        $trx = Yii::$app->db->beginTransaction();
        $model = Product::findOne($id);
        $model->delete();
        $trx->commit();
        return ['result' => true];
    }
}