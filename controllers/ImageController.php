<?php

namespace iutbay\yii2\mm\controllers;

use Yii;

/**
 */
class ImageController extends \yii\web\Controller
{

    public function actionThumb($path)
    {
        $ic = $this->module->ic;

        if (!$ic->checkPath($path)) {
            throw new \yii\web\NotFoundHttpException();
        }
    }

}