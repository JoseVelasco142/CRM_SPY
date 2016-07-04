<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 20/05/2016
 * Time: 18:56
 */
use app\models\SpyCategory;
use app\models\SpyDepartment;
use app\models\SpyFaculty;
use app\models\spyPosition;
use app\models\SpySector;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\assets\ClientFormAsset;

$this->title = "Spy | Nuevo cliente";
ClientFormAsset::register($this);
$session = Yii::$app->session;
?>
<img id="load-icon" class="hidden" src="img/load-icon.gif" style="position: absolute;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />

<div id="main-content">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form-new-client',
            'class' => 'form-vertical mCustomScrollbar',
            'enctype' => 'multipart/form-data',
            'data-mcs-theme' =>'dark',
        ],
        'enableClientValidation' => true,
    ]); ?>
    <!-- left form / required* -->
    <div class="row">
        <div class="col-md-6  fields-block">
            <div id="client-title">Datos de cliente</div>
            <div id="client" class="mCustomScrollbar" data-mcs-theme="dark">
                <!-- commercial id-->
                <?= $form->field($client, 'commercial_id')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false) ?>
                <!-- name (required)-->
                <?= $form->field($client, 'name', [
                    'template' => '<div>
                                <div class="col-xs-3 field-title"><span>{label}<i class="fa fa-asterisk" aria-hidden="true"></i></span></div>
                                <div class="col-xs-9 field-input">{input}</div>
                                <div class="col-xs-9 col-xs-offset-3 field-error">{error}</div>
                            </div>'
                ])->textInput([
                    'placeholder' => 'Nombre',
                    'maxlength' => true
                ]); ?>
                <!-- CIF (optional)-->
                <?= $form->field($client, 'CIF', [
                    'template' => '<div>
                                <div class="col-xs-3 field-title">{label}</div>
                                <div class="col-xs-9 field-input">{input}</div>
                                <div class="col-xs-9 col-xs-offset-3 field-error">{error}</div>
                            </div>'
                ])->textInput([
                    'placeholder' => 'CIF',
                    'maxlength' => true
                ]); ?>
                <!-- DISC (optional)-->
                <div class="col-md-6 ">
                    <?= $form->field($client, 'DISC', [
                        'template' => '<div class="hidden">{input}</div>'
                    ])->hiddenInput()->label(false) ?>
                    <div class="col-xs-9 col-xs-offset-3 field-horizontal-title">DISC</div>
                    <div id="disc-selector" current="<?= $client->DISC; ?>" class="col-xs-9 col-xs-offset-3">
                        <div class="disc-red"></div>
                        <div class="disc-blue"></div>
                        <div class="disc-yellow"></div>
                        <div class="disc-green"></div>
                    </div>
                </div>
                <!-- Photo (optional)-->
                <div class="col-md-6 ">
                    <?php
                    $photo = !(empty($client->photo));
                    $photo ? $text = "Actualizar" : $text = "Subir imagen"; ?>
                    <?= $form->field($client, 'photo', [
                        'template' => '<div class="hidden">{input}</div>'
                    ])->fileInput(['accept' => 'image/*']) ?>
                    <?= '<span id="photo-btn" class="btn btn-default col-md-10 col-md-offset-2 col-xs-6"><i class="fa fa-camera" aria-hidden="true"></i>' . $text . '</span>' ?>
                    <img id="current-photo" src="<?= $client->photo ?>"
                         class="col-md-8 col-md-offset-3 col-xs-6 img-responsive <?= $photo ? null : 'hidden'; ?>"/>
                </div>
            </div>
            <!-- contact main -->
            <div id="contact-title">Datos de contacto</div>
            <div id="contact" class=" mCustomScrollbar" data-mcs-theme="dark">
                <!-- contact name (required)-->
                <?= $form->field($contact, 'name', [
                    'template' => '<div>
                                    <div class="col-xs-3 field-title"><span>{label}<i class="fa fa-asterisk" aria-hidden="true"></i></span></div>
                                    <div class="col-xs-9 field-input">{input}</div>
                                    <div class="col-xs-9 col-xs-offset-3 field-error">{error}</div>
                                </div>'
                ])->textInput([
                    'placeholder' => 'Persona de contacto',
                    'maxlength' => true
                ]); ?>
                <!-- contact phone (required)-->
                <?= $form->field($contact, 'phone', [
                    'template' => '<div>
                                     <div class="col-xs-3 field-title"><span>{label}<i class="fa fa-asterisk long" aria-hidden="true"></i></span></div>
                                     <div class="col-xs-9 field-input">{input}</div>
                                     <div class="col-xs-9 col-xs-offset-3 field-error">{error}</div>
                                  </div>'
                ])->textInput([
                    'placeholder' => 'Telefono de contacto',
                    'maxlength' => 12
                ]); ?>
                <!-- contact email (required)-->
                <?= $form->field($contact, 'mail', [
                    'template' => '<div>
                                      <div class="col-xs-3 field-title">{label}</div>
                                      <div class="col-xs-9 field-input">{input}</div>
                                      <div class="col-xs-9 col-xs-offset-3 field-error">{error}</div>
                                 </div>'
                ])->textInput([
                    'placeholder' => '@',
                    'maxlength' => true
                ]); ?>
                <!-- contact position (optional)-->
                <?= $form->field($contact, 'position_id', [
                    'template' =>
                        ' <div>
                        <div class="col-xs-3 field-title">{label}</div>
                        <div class="col-xs-8 field-input">{input}</div>
                        <div key="position" class="col-xs-1 btn btn-default add-type">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>
                    </div>'
                ])->dropDownList(
                    ArrayHelper::map(SpyPosition::find()
                        ->orderBy('name ASC')
                        ->where(['!=', 'name', 'No definido'])
                        ->all(), 'position_id', 'name'),
                    ['prompt' => 'Cargo']
                ) ?>
            </div>
        </div>
        <!-- actions -->
        <div id="action-bar" class="col-md-6  ">
            <div id="faculty-switch" class="col-md-9 col-xs-7">
                <div id="faculty-mode-title" class="col-xs-6">Modo facultades</div>
                <label id="faculty-switch-button" class="col-xs-6">
                    <input id="faculty-switch-input" class="switch-input" type="checkbox"/>
                    <span class="switch-label" data-on="ON" data-off="OFF"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
            <div class="col-md-3 col-xs-5">
                <?= Html::submitButton( $update ? 'Actualizar' : 'Dar de alta', [
                    'id' => 'client-create-submit',
                    'class' => 'btn btn-default',
                    'action' => $update ? '?r=client/update&id='.$client->client_id : '?r=client/create',
                ]); ?>
            </div>
        </div>

        <!-- right form / optional -->
        <div id="extend-fields" class="col-md-6 panel-group fields-block" role="tablist" aria-multiselectable="true">
            <!-- clasification -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="categorization">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#extend-fields" href="#extend-categorization" aria-expanded="true"
                           aria-controls="extend-categorization">
                            Clasificaci&oacute;n
                        </a>
                    </h4>
                </div>
                <div id="extend-categorization" role="tabpanel" aria-labelledby="categorization" class="panel-collapse collapse in mCustomScrollbar" data-mcs-theme="dark">
                    <div class="panel-body">
                        <?= $form->field($client, 'category_id', [
                            'template' => '<div>
                                            <div class="col-xs-3 field-title">{label}</div>
                                            <div class="col-xs-8 field-input">{input}</div>
                                            <div key="category" class="col-xs-1 btn btn-default add-type">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </div>
                                        </div>'
                        ])->dropDownList(ArrayHelper::map(SpyCategory::find() ->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'category_id', 'name'), [
                            'prompt' => 'Categoria'
                        ]); ?>
                        <?= $form->field($client, 'sector_id', [
                            'template' => '<div>
                                <div class="col-xs-3 field-title">{label}</div>
                                <div class="col-xs-8 field-input">{input}</div>
                                <div key="sector" class="col-xs-1 btn btn-default add-type">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                 </div>
                            </div>'
                        ])->dropDownList(ArrayHelper::map(SpySector::find() ->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'sector_id', 'name'), [
                            'prompt' => 'Sector'
                        ]); ?>
                        <div id="faculty-mode" class="hidden">
                            <?= $form->field($client, 'faculty_id', [
                                'template' => '<div>
                                                <div class="col-xs-3 field-title">{label}</div>
                                                <div class="col-xs-8 field-input">{input}</div>
                                                <div key="faculty" class="col-xs-1 btn btn-default add-type">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </div>
                                            </div>'
                            ])->dropDownList(ArrayHelper::map(SpyFaculty::find() ->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'faculty_id', 'name'), [
                                'prompt' => 'Facultad',
                            ]); ?>
                            <?= $form->field($client, 'department_id', [
                                'template' =>
                                    '<div>
                                        <div class="col-xs-3 field-title">{label}</div>
                                        <div class="col-xs-8 field-input">{input}</div>
                                        <div key="department" class="col-xs-1 btn btn-default add-type">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                         </div>
                                    </div>'
                            ])->dropDownList(ArrayHelper::map(SpyDepartment::find() ->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'department_id', 'name'), [
                                'prompt' => 'Departamento',
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- location -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="location">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#extend-fields" href="#extend-location"
                           aria-expanded="false" aria-controls="collapseTwo">
                            Localizaci&oacute;n
                        </a>
                    </h4>
                </div>
                <div id="extend-location" role="tabpanel" aria-labelledby="location" class="panel-collapse collapse mCustomScrollbar" data-mcs-theme="dark">
                    <div class="panel-body">
                        <?= $form->field($client, 'postal_code', [
                            'template' => '<div>
                                            <div class="col-xs-3 field-title">{label}</div>
                                            <div class="col-xs-8 field-input">{input}</div>
                                            <div id="find-postal-code" class="btn btn-default col-xs-1" valid="false" data-toggle="tooltip" data-placement="bottom" data-title="Buscar c&oacute;digo postal">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </div>
                                        </div>'
                        ])->textInput([
                            'placeholder' => 'Código Postal',
                            'maxlength' => '5',
                            'data-toggle' => 'tooltip',
                            'data-html' => 'true',
                            'data-placement' => 'bottom',
                            'data-trigger' => 'manual',
                            'data-original-title' => '<div class=" postal-code-tooltip">Míximo <b>5 dígitos</b>. <br />Incluye ceros delante si es necesario</div>',
                        ]); ?>
                        <?= $form->field($client, 'province', [
                            'template' => '<div>
                                            <div class="col-xs-3 field-title">{label}</div>
                                            <div class="col-xs-9 field-input">{input}</div>
                                        </div>'
                        ])->textInput([
                            'placeholder' => 'provincia',
                            'maxlength' => "true",
                        ]); ?>
                        <?= $form->field($client, 'city', [
                            'template' => '<div>
                                            <div class="col-xs-3 field-title">{label}</div>
                                            <div class="col-xs-9 field-input">{input}</div>
                                        </div>'
                        ])->textInput([
                            'placeholder' => 'municipio',
                            'maxlength' => true,
                        ]); ?>
                        <?= $form->field($client, 'address', [
                            'template' => '<div>
                                            <div class="col-xs-3 field-title">{label}</div>
                                            <div class="col-xs-8 field-input">{input}</div>
                                            <div id="find-address" class="btn btn-default col-xs-1" valid="false" data-toggle="tooltip" data-placement="top" data-title="Validar direcci&oacute;n">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </div>
                                        </div>'
                        ])->textInput([
                            'placeholder' => 'avd, calle, vía,... ',
                            'maxlength' => true,
                        ]); ?>
                        <?= $form->field($client, 'coordinates')->hiddenInput([
                            'valid' => 'false'
                        ])->label(false) ?>
                        <div>
                            <div class="col-md-9 col-md-offset-3" style="padding: 0">
                                <img id="false-client-map" class="img-responsive no-address" src="img/map-no-signal.jpg">
                                <div id="client-map" class="hidden"></div>
                            </div>
                        </div>
                    </div>
                </div class="panel-collapse collapse in mCustomScrollbar" data-mcs-theme="dark">
            </div>
            <!-- extra data -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="extra-data">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#extend-fields" href="#extra-data-block"
                           aria-expanded="false" aria-controls="collapseThree">
                            Datos adicionales
                        </a>
                    </h4>
                </div>
                <div id="extra-data-block" role="tabpanel" aria-labelledby="extra-data" class="panel-collapse collapse mCustomScrollbar" data-mcs-theme="dark">
                    <div class="panel-body">
                        <?= $form->field($client, 'comment', [
                            'template' => '  <div>
                                            <div class=" field-horizontal-title">{label}</div>
                                            <div class=" field-input">{input}</div>
                                        </div>'
                        ])->textarea([
                            'placeholder' => '¿Alguna información a destacar?',
                            'style' => 'resize:none',
                            'rows' => 6
                        ]) ?>
                        <?= $form->field($client, 'equipment', [
                            'template' => '  <div>
                                            <div class=" field-horizontal-title">{label}</div>
                                            <div class=" field-input">{input}</div>
                                        </div>'
                        ])->textarea([
                            'placeholder' => '¿Conoces el equipamiento de este cliente?',
                            'style' => 'resize:none',
                            'rows' => 6
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
