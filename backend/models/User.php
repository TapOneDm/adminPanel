<?php

namespace app\models;

use Yii;
use app\components\HttpErrorBehavior;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int $is_activated
 * @property string|null $activation_link
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password', 'activation_link'], 'string'],
            [['is_activated'], 'integer'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'is_activated' => 'Is Activated',
            'activation_link' => 'Activation Link',
        ];
    }

    public function getTestBehaviorProps()
    {
        return $this->prop1;
    }

    public function create(string $email, string $password, string $activationLink): bool
    {
        $this->email = $email;
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $this->activation_link = $activationLink;
        $this->save();
        return true;
    }

    public static function getAllUsers()
    {
        return static::find()->asArray()->all();
    }
}
