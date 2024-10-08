<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "product".
 *
 * @property integer $id
 * @property string $record_status
 * @property boolean $show_on_website
 * @property integer $image_file_id
 * @property string $title
 * @property string $text
 * @property integer $price
 * @property integer $sort_order
 *
 * @property \app\models\File[] $files
 * @property \app\models\File $imageFile
 * @property \app\models\ProductFile[] $productFiles
 * @property \app\models\ProductTag[] $productTags
 * @property \app\models\Tag[] $tags
 */
abstract class Product extends \app\components\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $parentRules = parent::rules();
        return ArrayHelper::merge($parentRules, [
            [['id', 'record_status', 'show_on_website', 'sort_order'], 'required'],
            [['id', 'image_file_id', 'price', 'sort_order'], 'default', 'value' => null],
            [['id', 'image_file_id', 'price', 'sort_order'], 'integer'],
            [['record_status', 'title', 'text'], 'string'],
            [['show_on_website'], 'boolean'],
            [['id'], 'unique'],
            [['image_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\File::class, 'targetAttribute' => ['image_file_id' => 'id']]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => 'ID',
            'record_status' => 'Record Status',
            'show_on_website' => 'Show On Website',
            'image_file_id' => 'Image File ID',
            'title' => 'Title',
            'text' => 'Text',
            'price' => 'Price',
            'sort_order' => 'Sort Order',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(\app\models\File::class, ['id' => 'file_id'])->viaTable('product_file', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageFile()
    {
        return $this->hasOne(\app\models\File::class, ['id' => 'image_file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFiles()
    {
        return $this->hasMany(\app\models\ProductFile::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTags()
    {
        return $this->hasMany(\app\models\ProductTag::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(\app\models\Tag::class, ['id' => 'tag_id'])->viaTable('product_tag', ['product_id' => 'id']);
    }

}
