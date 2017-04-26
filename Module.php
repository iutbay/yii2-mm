<?php

namespace iutbay\yii2\mm;

use Yii;

use iutbay\yii2\mm\components\FileSystem;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'iutbay\yii2\mm\controllers';

    /**
     * @var components\FileSystem
     */
    public $fs;

    public $fsComponent = 'fs';

    public $apiOptions = [
        'cors' => false,
    ];

    public $thumbsPath = '@webroot/thumbs';
    public $thumbsUrl = '@web/thumbs';
    public $thumbsSize = [150, 150];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::setAlias('@mm', __DIR__);

        $this->fs = new FileSystem();
    }

    /**
     * @return array
     */
    public function getCorsOptions()
    {
        if (isset($this->apiOptions['cors'])) {
            if (is_array($this->apiOptions['cors'])) {
                return array_merge([
                    'class' => 'yii\filters\Cors',
                ], $this->apiOptions['cors']);
            } else {
                return $this->apiOptions['cors'] ? true : false;
            }
        }
    }

}
