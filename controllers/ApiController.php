<?php

namespace iutbay\yii2\mm\controllers;

use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

use iutbay\yii2\mm\models\UploadForm;

/**
 */
class ApiController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'cn' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];

        $corsOptions = $this->module->getCorsOptions();
        if ($corsOptions) {
            $behaviors['cors'] = $corsOptions;
        }

        return $behaviors;
    }

    /**
     * @return mixed
     */
    public function actionList($path = '', $recursive = false)
    {
        if (Yii::$app->request->method === 'OPTIONS') {
            //Yii::$app->response->headers->set('Allow', 'GET');
            return;
        }

        $fs = $this->module->fs;
        $path = $fs->normalizePath($path);
        if (!empty($path) && !$fs->has($path)) {
            throw new \yii\web\NotFoundHttpException();
        }

        return $fs->listContents($path, $recursive);
    }

    /**
     * @return mixed
     */
    public function actionUpload()
    {
        $request = Yii::$app->getRequest();
        if ($request->method === 'OPTIONS') {
            //Yii::$app->response->headers->set('Allow', 'POST');
            return;
        }

        $model = new UploadForm();
        if ($request->isPost) {
            $model->path = $request->post('path');
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                return true;
            }
            return $this->serializeModelErrors($model);
        }
        throw new \yii\web\BadRequestHttpException();
    }

    /**
     * @return mixed
     */
    public function actionDownload($path)
    {
        $fs = $this->module->fs;

        if ($fs->has($path)) {
            $metas = $fs->getMetaData($path);
            if (is_array($metas) && isset($metas['type'])) {
                if ($metas['type'] === 'file' && $stream = $fs->readStream($path)) {
                    $response = Yii::$app->getResponse();
                    $attachmentName = preg_replace('#^.*/#', '', $path);
                    return $response->sendStreamAsFile($stream, $attachmentName);
                }
                throw new \yii\web\BadRequestHttpException('Invalid path.');
            }
        }

        throw new \yii\web\NotFoundHttpException('The file does not exists.');
    }

    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    protected function serializeModelErrors($model)
    {
        Yii::$app->getResponse()->setStatusCode(422, 'Data Validation Failed.');

        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'message' => $message,
            ];
        }

        return $result;
    }

}
