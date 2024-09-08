<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $name
 * @property string $ext
 * @property string $path
 * @property string $open_link
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ext', 'open_link'], 'required'],
            [['name', 'ext', 'path', 'open_link'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ext' => 'Ext',
            'path' => 'Path',
            'open_link' => 'Open Link',
        ];
    }

    public function getData()
    {
        // $ch = curl_init();
        // set URL and other appropriate options
        // curl_setopt($ch, $this->open_link, 'url');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // curl_setopt($ch, CURLOPT_HEADER, 0);

        // grab URL and pass it to the browser
        // $blob = curl_exec($ch);

        // close cURL resource, and free up system resources
        // curl_close($ch);

        return array_merge($this->toArray());
    }
}
