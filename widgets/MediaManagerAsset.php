<?php

namespace iutbay\yii2\mm\widgets;

use yii\web\AssetBundle;

class MediaManagerAsset extends AssetBundle
{

    public $sourcePath = '@vendor/iutbay/yii2-mm/assets/mm';
    public $css = [
        'mm.min.css',
    ];
    public $js = [
        'mm.min.js',
    ];
    public $depends = [
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

}
