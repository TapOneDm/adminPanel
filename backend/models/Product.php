<?php

namespace app\models;

use \app\models\base\Product as BaseProduct;

/**
 * This is the model class for table "product".
 */
class Product extends BaseProduct
{

    public function getTags()
    {
        return $this->hasMany(\app\models\Tag::class, ['id' => 'tag_id'])
            ->via('productTags');
    }
}
