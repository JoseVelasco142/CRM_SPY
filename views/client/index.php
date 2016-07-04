<?php


use app\assets\ClientIndexAsset;
use app\models\SpyClient;
use app\models\SpyContact;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use app\models\SpyFaculty;
use app\models\SpyCategory;
use app\models\SpySector;
use app\models\SpyDepartment;


$this->title = "Spy | Cartera de clientes";
ClientIndexAsset::register($this);
$session = Yii::$app->session;
$routes = $session->get('routes');
?>
<div id="main-content" class="col-xs-12">
    <div id="search-filter-bar" class="col-xs-12">
        <!-- search bar -->
        <div id="search-bar" class="col-md-4 col-xs-12">
            <?php
            $form = ActiveForm::begin([
                'id' => 'faculty-department-filter',
                'validateOnChange' => true,
                'method' => 'get',
                'options' => [
                    'class' => 'form-horizontal',
                ],
            ]) ?>
            <div id="search-button" class="col-xs-2">
                <?= Html::submitButton('<i class="col-xs-1 fa fa-search" aria-hidden="true"></i>', ['class' => 'btn']) ?>
            </div>
            <div id="search-input" class="col-xs-10">
                <?= $form->field($model, 'search')->textInput([
                    'id' => 'search',
                    'name' => 'search',
                    'class' => 'form-control',
                ])->label(false) ?>
            </div>
        </div>
        <!-- filter bar -->
        <div id="filter-bar" class="col-md-8 col-xs-12">
                <div class="col-xs-3">
                    <?= $form->field($model, 'category')->dropDownList(ArrayHelper::map(SpyCategory::find()->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'category_id', 'name'), [
                        'id' => 'category',
                        'name' => 'category',
                        'prompt' => 'Categoria',
                        'onchange' => 'this.form.submit()'
                    ])->label(false) ?>
                </div>
                <div class="col-xs-3">
                    <?= $form->field($model, 'sector')->dropDownList(ArrayHelper::map(SpySector::find()->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'sector_id', 'name') , [
                        'id' => 'sector',
                        'name' => 'sector',
                        'prompt' => 'Sector',
                        'onchange' => 'this.form.submit()'
                    ])->label(false) ?>
                </div>
                <div class="col-xs-2">
                <?= $form->field($model, 'location')->textInput([
                    'id' => 'location',
                    'name' => 'location',
                    'placeholder' => 'Localización',
                ])->label(false) ?>
            </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'faculty')->dropDownList(ArrayHelper::map(SpyFaculty::find()->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'faculty_id', 'name') , [
                        'id' => 'faculty',
                        'name' => 'faculty',
                        'prompt'=>'Facultad',
                        'onchange' => 'this.form.submit()'
                    ])->label(false) ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'department')->dropDownList(ArrayHelper::map(SpyDepartment::find()->where(['!=', 'name', 'No definido'])->orderBy('name ASC')->all(), 'department_id', 'name'), [
                        'id' => 'department',
                        'name' => 'department',
                        'prompt' => 'Departamento',
                        'onchange' => 'this.form.submit()'
                    ])->label(false) ?>
                </div>

            <?php ActiveForm::end() ?>
        </div>
        <?=Html::a('<i class="col-xs-12 fa fa-ban" aria-hidden="true"></i>',
            ['client/index'], [
                'class' => 'btn',
                'id' => 'clear-filter',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data-original-title' => 'Limpiar filtros'
            ]
        ) ?>
    </div>
    <div id="block-list" class="col-xs-12">
        <!-- header list -->
        <div id="header-list" class="col-xs-12">
            <div class="col-xs-6">Cliente</div>
            <div id="header-legend" class="col-xs-6">
                <div class="col-xs-2">
                    <i class="fa fa-question" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Acci&oacute;n inminente"></i>
                </div>
                <div class="col-xs-2">
                    <i class="fa fa-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="M&aacute;s info"></i>
                </div>
                <div class="col-xs-2">
                    <i class="fa fa-eye"  aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Ver ficha completa"></i>
                </div>
                <div class="col-xs-2">
                    <i class="fa fa-map-marker" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="A&ntilde;adir a ruta"></i>
                </div>
                <div class="col-xs-2">
                    <i class="fa fa-thumb-tack" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="A&ntilde;adir tarea"></i>
                </div>
            </div>
        </div>

        <!-- clients list -->
        <div id="client-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">
            <?php
            foreach($clients->models as $client){
                if($client instanceof SpyClient){
                $hour = date('H:i');
                in_array($client->client_id, $routes) ? $added = "added" : $added = "";
                switch($client->state) {
                    case 1:
                        $title = "llamada teléfonica";
                        break;
                    case 2:
                        $title = "visitar";
                        break;
                    case 3:
                        $title = "enviar email";
                        break;
                    case 4:
                        $title = "enviar presupuesto";
                        break;
                    case 5:
                        $title = "asistencia técnica";
                        break;
                    case 6:
                        $title = "cierre de venta";
                        break;
                    case 7:
                        $title = "llamada de cortesía mensual";
                        break;
                    default:
                        $title = "Sin acción pendiente";
                        break;
                } ?>
                <div key="<?=$client->client_id; ?>" class="col-xs-12 client-item">
                    <div class="col-xs-6" style="padding: 0">
                        <div class="col-xs-1 DISC" data-toggle="tooltip" data-placement="right" title="DISC" style="background: <?=$client->DISC; ?>;"></div>
                        <div class="col-xs-11 client-name"><?=$client->name; ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class="col-xs-2" data-toggle="tooltip" data-placement="left" title="<?=$title; ?>" >
                            <img class="img-responsive action-icon" src="img/state-<?=$client->state; ?>.png" />
                        </div>
                        <div class="col-xs-2">
                            <div class="btn btn-default client-expand" data-toggle="tooltip" data-placement="left" title="M&aacute;s info">
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <a href="?r=client/view&id=<?=$client->client_id ?>" target="_blank" class="btn btn-default client-full" data-toggle="tooltip" data-placement="left" title="Ver ficha completa">
                                <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="col-xs-2">
                            <div class="btn btn-default client-route <?=$added; ?>" data-toggle="tooltip" data-placement="left" title="A&ntilde;adir a la ruta">
                                <img class="img-responsive" src="img/streetview-icon.png" />
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="btn btn-default client-task" data-toggle="tooltip" data-placement="left" title="A&ntilde;adir nueva tarea">
                                <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 client-details detail-close">
                        <div class="col-xs-2 detail-block detail-first">
                            <div class="col-xs-12 contact-list-title">Contactos</div>
                            <div class="col-xs-12 contact-list">
                                <?php
                                $contacts = $client->contacts;
                                foreach($contacts as $contact){
                                    if($contact instanceof SpyContact){
                                        $contact->main ? $main = "contact_main" : $main = "";
                                        empty($contact->mail) ? $contact->mail = "&nbsp;" : null;
                                        $contact->position_id!=1 ? $positionName = $contact->position->name : $positionName = "Sin definir";
                                        $content = '<div class="contact-data-block">
                                                        <div class="col-xs-3" style="padding:0; margin-bottom: 2.5%; font-weight:600;">Tel&eacute;fono: </div><div class="col-xs-9" style="padding-left:5px; margin-bottom: 2.5%;">'.$contact->phone.'</div>
                                                        <div class="col-xs-3" style="padding:0; margin-bottom: 2.5%; font-weight:600;">Email: </div><div class="col-xs-9 popover-client-mail" style="padding:0; margin-bottom: 2.5%;">'.$contact->mail.'</div>
                                                        <div class="col-xs-3" style="padding:0; font-weight:600;">Cargo: </div><div class="col-xs-9" style="padding:0;">'.$positionName.'</div>
                                                   </div>';
                                        ?>
                                        <button class="col-xs-3 contact-item <?=$main; ?>"
                                                data-toggle="popover" data-container="body" data-placement="top" data-html="true" data-trigger="focus" title="<?=$contact->name; ?>"
                                                data-content='<?=$content ?>'
                                            ></button>
                                    <?php }
                                }?>
                            </div>
                        </div>
                        <div class="col-xs-6 detail-block detail-center">
                            <div class="task-list-title">Tareas para hoy</div>
                            <div class="col-xs-12 mCustomScrollbar task-list" data-mcs-theme="dark">
                            <?php
                                $limitedTasks = $client->limitedTasks;
                                if(count($limitedTasks)>0){
                                    foreach($limitedTasks as $task){
                                        $dateTimeA = explode(" ", $task->alert);
                                        $Adate = explode("-",$dateTimeA[0]);
                                        $formattedDate = $Adate[2]."-".$Adate[1];
                                        $formattedTime = substr($dateTimeA[1], 0, -3);
                                        $Atime = explode(":", $formattedTime);
                                        $past = strtotime($task->alert) < strtotime('now');
                                        $type = $task->taskType;
                                        $files =$task->files; ?>
                                        <div class="col-xs-12 task-item">
                                            <!-- task info ( type, state, alert )-->
                                            <div class="col-xs-2" style="padding: 0">
                                                <?php
                                                if($type->name == "servicio técnico"){ ?>
                                                    <div class="col-xs-12 task-type" style="background: <?=$type->color; ?>" data-toggle="tooltip" data-placement="right" title="Servicio técnico">S. Técnico</div>
                                                <?php } else if($type->name == "llamada de cortesía mensual"){ ?>
                                                    <div class="col-xs-12 task-type" style="background: <?=$type->color; ?>" data-toggle="tooltip" data-placement="right" title="Llamada de cortesía mensual">LL.C.M</div>
                                                <?php } else { ?>
                                                    <div class="col-xs-12 task-type" style="background: <?=$type->color; ?>"> <?=$type->name ?></div>
                                                <?php } ?>
                                                <div class="col-xs-12 task-state" data-toggle="tooltip" data-placement="right" title="Tarea activa">
                                                    <i class="fa fa-bullseye" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-xs-12 task-datetime">
                                                    <div class="col-xs-5 task-datetime-title">
                                                        <i class="fa fa-clock-o" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="<?=$past ? "Pasada de hora/fecha" : "Dentro del horario";?>" style="color:<?=$past ? "#ff4545" : "#0d0"; ?>;"></i>
                                                    </div>
                                                    <div class="col-xs-7 task-datetime-time">
                                                        <span class="col-xs-12"><?=$formattedDate; ?></span>
                                                        <span class="col-xs-12"><?=$formattedTime; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- task data ( subject, description )-->
                                            <div class="col-xs-9 task-data">
                                                <div class="col-xs-12 task-data-subject"><?=$task->subject ?></div>
                                                <textarea class="col-xs-12 task-data-desc"><?=$task->description; ?></textarea>
                                            </div>
                                            <!-- task actions  ( update description, show files, finalize task )-->
                                            <div class="col-xs-1 task-actions" key="<?=$task->task_id; ?>">
                                                <div class="col-xs-12 btn btn-default task-action task-action-update disabled" data-toggle="tooltip" data-placement="left" title="Actualizar descripci&oacute;n">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </div>
                                                <div key="files" class="col-xs-12 btn btn-default task-action animated task-action-files" data-toggle="tooltip" data-placement="left" title="<?=count($files)." Archivos";?>">
                                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                                </div>
                                                <div key="report" class="col-xs-12 btn btn-default task-action animated task-action-complete" data-toggle="tooltip" data-placement="left" title="Finalizar tarea">
                                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <!-- task report  ( report textarea, finalize button )-->
                                            <div class="col-xs-9 task-report closed hidden">
                                                <textarea class="task-report-text">Escribe un reporte si es necesario. (OPCIONAL)</textarea>
                                                <div class="btn btn-default task-report-save" data-toggle="tooltip" data-placement="left" title="Guardar reporte y finalizar">
                                                    <i class="glyphicon glyphicon-check" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <!-- task files  ( file viewer, btn show dropzone )-->
                                            <div class="col-xs-9 task-files closed hidden">
                                                <?php if(count($files) > 0){ ?>
                                                    <div class="file-list">
                                                        <?php foreach($files as $file){
                                                            $ext = explode(".",$file->short_description)[1];
                                                            switch($ext){
                                                                case "pdf":
                                                                    $icon = ["fa-file-pdf-o", "\f1c1"];
                                                                    break;
                                                                case "txt":
                                                                    $icon = ["fa-file-text-o", "\f0f6"];
                                                                    break;
                                                                case "jpeg":
                                                                case "jpg":
                                                                case "png":
                                                                case "ico":
                                                                    $icon = ["fa-file-image-o", "\f1c5"];
                                                                    break;
                                                                case "doc":
                                                                case "docx":
                                                                case "odt":
                                                                    $icon = ["fa-file-word-o", "\f1c2"];
                                                                    break;
                                                                default:
                                                                    $icon = ["fa-file-o", "\f016"];
                                                                    break;
                                                            }
                                                            ?>
                                                            <a href="?r=task/get-file&id=<?=$file->file_id ?>" target="_blank">
                                                                <div class="file-item" data-toggle="tooltip" data-placement="bottom" title="<?=$file->short_description; ?>">
                                                                    <i class="fa <?=$icon[0]; ?>" aria-hidden="true" style="content: <?=$icon[1] ;?>;!important;"></i>
                                                                </div>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="not-files">Sin archivos adjuntos</div>
                                                <?php } ?>
                                                <div class="btn btn-default file-add" data-toggle="tooltip" data-placement="left" title="Subir archivos">
                                                    <i class="glyphicon glyphicon-plus" aria-expanded="true"></i>
                                                </div>
                                                <form action="?r=task/file-upload&id=<?=$task->task_id ?>" class="task-dropzone dropzone hidden"></form>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="not-tasks"> No hay tareas para hoy </div>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="col-xs-4 detail-block detail-last">
                            <?php if(!empty($client->coordinates)){ ?>
                                <div id="map-<?=$client->client_id ;?>" class="expand-map" coords="<?=$client->coordinates ?>"
                                     address="<?=$client->address; ?>" city="<?=$client->city ;?>"
                                     CP="<?=$client->postal_code; ?>" province="<?=$client->province ;?>">
                                </div>
                            <?php } else { ?>
                                <img class="img-responsive not-located expand-map" src="img/map-no-signal.jpg" />
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            <?php }
            }?>
        </div>

        <!-- pagers -->
        <div id="pagers" class="col-xs-2">
            <?= LinkPager::widget(['pagination' => $clients->pagination]); ?>
        </div>
    </div>
</div>

