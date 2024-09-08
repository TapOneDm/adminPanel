<?php

namespace app\services;

use Yii;
use app\services\base\BaseService;
use app\models\Tag;

class TagService extends BaseService
{
    public static function getListForCommonData()
    {
        $tags = array_map(fn($tag) => ['value' => $tag->id, 'label' => $tag->title], Tag::find()->all());
        return $tags;
    }
}
