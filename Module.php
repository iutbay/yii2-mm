<?php

namespace iutbay\yii2\mm;

use Yii;

use iutbay\yii2\mm\components\FileSystem;
use iutbay\yii2\mm\components\ImageCache;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'iutbay\yii2\mm\controllers';

    /**
     * @var components\FileSystem
     */
    public $fs;

    /**
     * Filesystem component name
     * @var string
     */
    public $fsComponent = 'fs';

    /**
     * Directory separator
     * @var string
     */
    public $directorySeparator = '/';

    /**
     * api controller options
     * @var array
     */
    public $apiOptions = [
        'cors' => false,
    ];

    /**
     * @var components\ImageCache
     */
    public $ic;
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

        $this->fs = new FileSystem([
            'fs' => Yii::$app->{$this->fsComponent},
            'directorySeparator' => $this->directorySeparator,
        ]);

        $this->ic = new ImageCache([
            'fs' => $this->fs,
            'thumbsPath' => $this->thumbsPath,
            'thumbsUrl' => $this->thumbsUrl,
            'thumbsSize' => $this->thumbsSize,
        ]);
    }

    /**
     * @return array
     */
    public function getCorsOptions()
    {
        if (isset($this->apiOptions['cors'])) {
            if (is_array($this->apiOptions['cors'])) {
                return [
                    'class' => 'yii\filters\Cors',
                    'cors' => $this->apiOptions['cors'],
                ];
            } else {
                return $this->apiOptions['cors'] ? true : false;
            }
        }
    }

}
