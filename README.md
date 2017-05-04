# Yii2 Media Manager Module

This module is still in its early stages, but feel free to use it and report bugs.

## Installation

The preferred way to install this module is through [composer](http://getcomposer.org/download/).

Either run

```
composer require "iutbay/yii2-mm" "*"
```

or add

```json
"iutbay/yii2-mm" : "*"
```

to the require section of your application's `composer.json` file.

## Configuration

Add the following lines in your application configuration :

```php
'components' => [
    // ...
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [    
            'thumbs/<path:.*>' => 'mm/thumb/thumb',
            // ...
        ],
    ],
    // ...
    'fs' => [
        'class' => 'creocoder\flysystem\LocalFilesystem',
        'path' => '@webroot/upload',
    ],
],
'modules' => [
    // ...
    'mm' => [
        'class' => 'iutbay\yii2\mm\Module',
    ],
],
```


### About Flysystem

This module use [Flysystem](https://github.com/thephpleague/flysystem) (via [creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem)), a *filesystem abstraction which allows you to easily swap out a local filesystem for a remote one*.

You can use a local filesystem as described previously, you should then create an `upload` folder in the web folder of your Yii2 application. You can also use any *adapter* provided by Flysystem, take a look at [Flysystem](http://flysystem.thephpleague.com) and [creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem).

WARNING : Actually, this module has only been tested with *local*, *ftp* and *sftp* adapters.

### About image thumbnails

This module use [Imagine](https://github.com/avalanche123/Imagine) (via [yii2-imagine](https://github.com/yiisoft/yii2-imagine)) to generate image thumbnails *on demand*, you should create a `thumbs` folder in the web folder of your application.

## Usage

### MediaManagerInput

### MediaManagerModal
