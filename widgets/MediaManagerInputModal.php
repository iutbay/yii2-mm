<?php

namespace iutbay\yii2\mm\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

use iutbay\yii2\mm\widgets\MediaManagerAsset;

class MediaManagerInputModal extends \yii\widgets\InputWidget
{

    /**
     * @var string
     */
    public $modalTitle = 'Media Manager';

    /**
     * @var string
     */
    public $inputId;

    /**
     * @var array
     */
    public $inputOptions = ['class' => 'form-control'];

    /**
     * @var string
     */
    public $buttonLabel = 'Browse';

    /**
     * @var array
     */
    public $buttonOptions = ['class' => 'btn btn-primary'];

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
        echo $this->renderInputGroup();
        echo $this->renderModal();
        $this->registerClientScript();
    }

    /**
     * @return string
     */
    public function renderInput()
    {
        $input = '';
        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
        } else {
            $input = Html::textInput($this->name, $this->value, $this->inputOptions);
        }
        return $input;
    }

    /**
     * @return string
     */
    public function renderInputGroup()
    {
        $buttonIcon = '<i class="fa fa-fw fa-folder-open" aria-hidden="true"></i>';
        $buttonLabel = $buttonIcon . ' ' . $this->buttonLabel;
        $button = Html::button($buttonLabel, array_merge($this->buttonOptions, [
            'data-toggle' => 'modal',
            'data-target' => '#' . $this->getModalId(),
        ]));
        $button = Html::tag('span', $button, ['class' => 'input-group-btn']);

        $input = $this->renderInput();
        $group = Html::tag('div', $input . $button, ['class' => 'input-group']);
        return $group;
    }

    /**
     * @return string
     */
    public function renderModal()
    {
        return <<<HTML
            <div class="modal fade" id="{$this->getModalId()}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">{$this->modalTitle}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="{$this->getId()}"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
HTML;
    }

    /**
     * @return string
     */
    public function getModalId()
    {
        return $this->getId() . '-modal';
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
                'multiple' => false,
            ],
            'onSelect' => new JsExpression("function(e) { $('#{$this->getModalId()}').modal('hide'); }"),
        ]);

        $varName = str_replace('-', '_', $this->getId());
        $options = Json::encode($options);
        $js = <<<JS
            var {$varName};
            $('#{$this->getModalId()}')
                .on('show.bs.modal', function (e) {
                    {$varName} = new MM({$options});
                }).on('hide.bs.modal', function (e) {
                    {$varName}.destroy();
                });
JS;
        $view->registerJs($js, \yii\web\View::POS_END);
    }

}
