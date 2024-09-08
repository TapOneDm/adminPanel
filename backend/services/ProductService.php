<?php

namespace app\services;

use Yii;
use app\dto\ProductDto;
use app\services\TagService;
use app\services\base\BaseService;
use app\models\Product;
use app\models\Tag;

class ProductService extends BaseService
{
    public static function getList(array $filter = [], int $limit, int $offset)
    {
        $result = Product::find()->select(['id', 'title', 'show_on_website', 'price'])->limit($limit)->offset($offset)->all();
        return $result;
    }

    public static function getData(int $id)
    {
        $product = Product::findOne($id);
        $tags = $product->tags;

        $data = $product->toArray();
        $data['image'] = $product->imageFile?->getData();
        $data['tags'] = array_map(fn($tag) => ['value' => $tag->id, 'label' => $tag->title], $tags);

        return $data;
    }
}
