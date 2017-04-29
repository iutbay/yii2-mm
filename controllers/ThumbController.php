<?php

namespace iutbay\yii2\mm\controllers;

use Yii;

use iutbay\yii2\mm\models\Thumb;

/**
 */
class ThumbController extends \yii\web\Controller
{

    /**
     * @return mixed
     */
    public function actionThumb($path)
    {
        /* @var $thumb Thumb */
        $thumb = Yii::createObject([
            'class' => Thumb::className(),
            'path' => $path,
        ]);

        if ($thumb->validate() && $thumb->save()) {
            header('Content-type: image/' . $thumb->type);
            header('Content-Length: ' . filesize($thumb->realPath));
            readfile($thumb->realPath);
            return;
        } else {
            throw new \yii\web\NotFoundHttpException();
        }
    }

}
