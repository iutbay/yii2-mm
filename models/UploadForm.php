<?php

namespace iutbay\yii2\mm\models;

use Yii;
use yii\web\UploadedFile;

class UploadForm extends \yii\base\Model
{

    /**
     * @var string
     */
    public $path;

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['file'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
                'mimeTypes' => ['image/*'],
                'maxSize' => 2000000, //2097152,
                'maxFiles' => 1,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * Upload files
     */
    public function upload()
    {
        if ($this->validate()) {
            $file = $this->file;
            $fs = Yii::$app->getModule('mm')->fs;
            $path = "{$this->path}/{$file->baseName}.{$file->extension}";
            if ($stream = fopen($file->tempName, 'r+')) {
                $fs->writeStream($path, $stream);
                fclose($stream);                
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
