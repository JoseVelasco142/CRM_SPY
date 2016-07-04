<?php

/* @var $this yii\web\View */

use app\assets\SiteIndexAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
SiteIndexAsset::register($this);
$this->title = 'Spy2.0 | Index';
?>
<div class="site-index">
    <div id="index-info" class="col-xs-6">
        <img id="logo-spy" class="img-responsive" src="img/logo-spy.png" alt="Logo Spy" />
    </div>
    <div id="login-block" class="col-xs-6">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [
                'class' => 'col-md-10 col-md-offset-1 form-horizontal'
            ],
            'action' => '?r=site/login',
            'fieldConfig' => [
                'template' => " <div class=\"col-lg-12\">{input}</div>
                    <div class=\"col-lg-9 col-xs-offset-3\">{error}</div>",
            ],
        ]);
        ?>
        <div class="col-xs-12  shadow-field">
            <div id="user-icon" class="col-xs-1 glyphicon glyphicon-user"></div>
            <div class="col-xs-11">
                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'placeholder' =>  'Nombre de usuario' ,
                    'class' => 'col-xs-12 form-control',
                    //##################
                    'value' => 'user@test.com'
                ])->label(false) ?>
            </div>
        </div>
        <div class="col-xs-12  shadow-field">
            <div id="pwd-icon" class="col-xs-1 glyphicon glyphicon-asterisk"></div>
            <div class="col-xs-11">
                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => 'clave de acceso',
                    'class' => 'col-xs-12 form-control',
                    //##################
                    'value' => 'spySecretKey'
                ]) ?>
            </div>
        </div>
        <div class="col-xs-12" style="padding: 0;">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' =>   '<div class="col-xs-12">
                                    <div class="col-xs-offset-2 col-xs-10">{input} {label}</div>
                                </div>',
            ])->label("Recordarme en este equipo") ?>
        </div>
        <div class="col-xs-12">
            <div class="col-lg-offset-2">
                <?= Html::submitButton('Entrar', [
                    'class' => 'btn btn-default',
                    'name' => 'login-button'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-1 slide-checkbox">
        <label for="loginform-rememberme"></label>
        <input id="loginform-rememberme" type="checkbox" value="0" />
    </div>
    <div id="checkbox-text" class="col-xs-10">qweqwe}</div>
</div>
