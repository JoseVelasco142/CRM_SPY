<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 29/04/2016
 * Time: 17:12
 */
use app\assets\ClientMassiveAsset;
use app\models\SpySector;

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$this->title = "Masivo clientes";
ClientMassiveAsset::register($this);
?>

<div id="main-content">
        <div id="massive-header" class="col-xs-12">
            <div class="col-xs-4">
                <label for="sector-massive-selector" class="hidden"></label>
                <?= Html::dropDownList('sector', null,
                    ArrayHelper::map(SpySector::find()->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'sector_id', 'name'),[
                        'prompt' => 'Sector',
                        'id' => 'sector-massive-selector',
                        'class' => 'form-control col-xs-4'
                    ]) ?>
            </div>
            <div class="col-xs-8">
                <div class="col-xs-3 col-md-offset-2">
                    <button id="validate-clients" class="col-xs-12 btn btn-default">Validar clientes</button>
                </div>
                <div class="col-xs-3 col-md-offset-2">
                    <button id="create-sector" class="col-xs-12 btn btn-default">Nuevo sector</button>
                </div>
            </div>
        </div>
        <div id="legend-column" class="col-xs-12">
            <div id="lc-1" class="l-colum">Nombre</div>
            <div id="lc-2" class="l-colum">Contacto</div>
            <div id="lc-3" class="l-colum">Tel&eacute;fono</div>
            <div id="lc-4" class="l-colum">Email</div>
        </div>
        <div id="massive-form" class="col-xs-12"></div>
</div>
