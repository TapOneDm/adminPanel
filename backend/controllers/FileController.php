<?php

namespace app\controllers;

use app\components\ApiController;
use yii\helpers\FileHelper;
use app\models\File;
use Yii;
use Imagick;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;

class FileController extends ApiController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;
    

    public function getFileExtension(UploadedFile $file) {
        if (empty($file)) {
            throw new BadRequestHttpException('Not a file');
        }
        return array_slice(explode(".", $file->name), -1)[0];
    }

    public function actionUpload()
    {
        // $im = new Imagick();
        // var_dump($im);exit;
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isPost) {
            // $post = Yii::$app->request->post();
            $file = UploadedFile::getInstanceByName('file');
            $ext = $this->getFileExtension($file);
            $filename = md5($file->name) . ".$ext";
            $path = Yii::getAlias('@webroot') . '/' .'thumbs/' . $filename;
            $file->saveAs($path);

            $fileModel = new File();
            $fileModel->name = $filename;
            $fileModel->ext = $ext;
            $fileModel->path = $path;
            $fileModel->open_link = Yii::$app->params['domainThumbs'] . $filename;

            if (!$fileModel->save()) {
                return $fileModel->getFirstErrors();
            }
            $fileModel->save();

            return [
                'file' => $fileModel->toArray(),
            ];
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
}