<?php

namespace iutbay\yii2\mm\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use iutbay\yii2\mm\widgets\MediaManagerAsset;

class MediaManagerInput extends \yii\widgets\InputWidget
{

    /**
     * @var boolean
     */
    public $multiple = false;

    /**
     * @var string
     */
    public $inputId;

    /**
     * @var string
     */
    public $inputTag = 'input';

    /**
     * @var array
     */
    public $inputOptions = ['class' => 'form-control'];

    /**
     * MM options
     * @var array
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->multiple) {
            $this->inputTag = 'textarea';
        }

        if ($this->hasModel()) {
            $this->inputId = Html::getInputId($model, $attribute);
        } else {
            $this->inputId = $this->getId() . '-input';
            $this->inputOptions = array_merge($this->inputOptions, [
                'id' => $this->inputId,
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $div = Html::tag('div', '', ['id' => $this->getId()]);
        $div = Html::tag('div', $div, ['class' => 'form-group']);
        $input = $this->renderInput();
        echo $div . $input;

        $this->registerClientScript();
    }

    /**
     * @return string
     */
    public function renderInput()
    {
        $input = '';
        if ($this->hasModel()) {
            switch ($this->inputTag) {
                case 'textarea' :
                    $input = Html::activeTextarea($this->model, $this->attribute, $this->inputOptions);
                    break;
                default :
                    $input = Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
                    break;
            }
        } else {
            switch ($this->inputTag) {
                case 'textarea' :
                    $input = Html::textarea($this->name, $this->value, $this->inputOptions);
                    break;
                default :
                    $input = Html::textInput($this->name, $this->value, $this->inputOptions);
                    break;
            }
        }
        return $input;
    }

    /**
     * Register js
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        MediaManagerAsset::register($view);

        $options = array_merge($this->clientOptions, [
            'el' => '#' . $this->getId(),
            'input' => [
                'el' => '#' . $this->inputId,
                'multiple' => $this->multiple,
            ],
        ]);

        $options = Json::encode($options);
        $view->registerJs("new MM($options);", \yii\web\View::POS_END);
    }

}
