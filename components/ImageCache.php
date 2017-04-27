<?php

namespace iutbay\yii2\mm\components;

use Yii;
use yii\helpers\Url;

//use ManipulatorInterface

class ImageCache extends \yii\base\Component
{

    const SIZE_THUMB = 'thumb';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    const SIZE_FULL = 'full';

    public $thumbsPath = '@webroot/thumbs';
    public $thumbsUrl = '@web/thumbs';
    public $thumbsSize = self::SIZE_THUMB;
    public $resizeMode;
    public $sizes = [
        self::SIZE_THUMB => [150, 150],
        self::SIZE_MEDIUM => [300, 300],
        self::SIZE_LARGE => [600, 600],
    ];

    public $extensions = [
        'jpg' => 'jpeg',
        'jpeg' => 'jpeg',
        'png' => 'png',
        'gif' => 'gif',
        'bmp' => 'bmp',
    ];

    /**
     * @var FileSystem
     */
    public $fs;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->thumbsPath = Yii::getAlias($this->thumbsPath);
        $this->thumbsUrl = Yii::getAlias($this->thumbsUrl);

        // nomalize directory separator
        $this->thumbsPath = str_replace('\\', '/', $this->thumbsPath);
    }

    /**
     * @param string $path
     */
    public function checkPath($path)
    {
        // check extension
        $extension = preg_replace('#^.*\.([^\.]+)$#', '\1', $path);
        if (!array_key_exists($extension, $this->extensions))
            return false;

        // check file
        if (!$this->fs->has($path)) {
            return false;
        }

        return true;
    }

}
