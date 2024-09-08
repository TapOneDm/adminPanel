<?php

namespace app\controllers;

use app\components\ApiController;
use app\models\Project;
use app\services\ProjectService;
use Yii;

class ProjectController extends ApiController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;

    public function actionList()
    {
        $data = Yii::$app->request->getBodyParams();
        $result = ProjectService::getList($data['filter'], $data['limit'], $data['offset']);
        return ['result' => $result];
    }

    public function actionGet($id)
    {
        $result = ProjectService::getData($id);
        return ['result' => $result];
    }

    public function actionSave()
    {

    }

    public function actionDelete()
    {
        $id = Yii::$app->request->getBodyParams()['id'];
        $trx = Yii::$app->db->beginTransaction();
        $model = Project::findOne($id);
        $model->delete();
        $trx->commit();
        return ['result' => true];
    }
}