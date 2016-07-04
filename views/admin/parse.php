<?php


use app\assets\AdminParseAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\SpyCommercial;

AdminParseAsset::register($this);
$this->title = "Importar clientes";
?>
<div class="main-content">
    <div id="form-parse" class="col-xs-12">
        <?php $form = ActiveForm::begin([
               'id' => 'parse-form',
                'options' => [
                    'class' => 'form-horizonal from-group',
                    'enctype' => 'multipart/form-data'
                ],
                'fieldConfig' => [
                    'template' => " <div class=\"col-lg-12\">{input}</div>
                        <div class=\"col-lg-9 col-xs-offset-3\">{error}</div>",
                ],
                'enableClientValidation' => true,
            ]); ?>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <div class="col-xs-12">
                    <div class="col-xs-3">Comercial: </div>
                    <div class="col-xs-9">
                        <?= $form->field($model, 'commercial')
                            ->dropDownList(ArrayHelper::map(SpyCommercial::find()->orderBy('name ASC')->all(), 'commercial_id', 'email'), [
                            'class' => 'form-control',
                            'prompt'=>'--Selecciona un comercial--'
                        ]); ?>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="col-xs-3">Fichero excel: </div>
                    <div class="col-xs-9">
                        <?= $form->field($model, 'file')->fileInput(['class' => 'input-md']); ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-6"><?= Html::submitButton('Importar', [ 'class' => 'btn btn-default']) ?></div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php if(isset($data)) { ?>
    <div id="table-header" class="col-xs-12">
        <div class="col-xs-3">Nombre</div>
        <div class="col-xs-3">Contacto</div>
        <div class="col-xs-2">Tel&eacute;fono</div>
        <div class="col-xs-4">Email</div>
    </div>
    <div id="client-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">
        <?php
        foreach($data as $client){ ?>
            <div class="col-xs-12 client-item" style="border:1px solid red;">
                <div class="col-xs-3"><?=$client['client_name'] ;?></div>
                <div class="col-xs-3"><?=$client['contact_name'] ;?></div>
                <div class="col-xs-2"><?=$client['contact_phone'] ;?></div>
                <div class="col-xs-4"><?=$client['contact_mail'] ;?></div>
            </div>
        <?php } ?>
        </div>
    <?php } ?>
</div>