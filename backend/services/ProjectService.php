<?php

namespace app\services;

use Yii;
use app\dto\ProjectDto;
use app\services\base\BaseService;
use app\models\Project;

class ProjectService extends BaseService {
    public static function getList(array $filter = [], int $limit, int $offset)
    {
        $result = Project::find()->limit($limit)->offset($offset)->all();
        return $result;
    }

    public static function getData(int $id)
    {
        $result = Project::findOne($id);
        return $result->toArray();
    }
}
