<?php

use app\assets\TaskIndexAsset;
use app\models\SpyContact;
use app\models\SpyTaskType;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SpyCategory;
use app\models\SpyClient;
use app\models\SpyDepartment;
use app\models\SpyFaculty;
use app\models\SpySector;
use app\models\SpyTask;
use yii\widgets\LinkPager;

TaskIndexAsset::register($this);
$this->title = "Spy | Agenda";
?>
<div id="main-content">
    <?php if(isset($taskList) && $taskList instanceof ActiveDataProvider){ ?>
    <div id="task-main" class="col-xs-10">
        <div id="task-main-title" class="col-xs-12">Listado de tareas<span id="task-counter"><span><?=$taskList->getTotalCount(); ?></span> tareas activas</span></div>
        <!-- task list -->
        <div id="task-list-view" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">

        <?php
        foreach($taskList->models  as $task){
            if($task instanceof SpyTask){
                $client = $task->client;
                $contactMain = $client->contactMain;
                $type = $task->taskType;
                $files = $task->files;
                if($client instanceof SpyClient && $type instanceof SpyTaskType && $contactMain instanceof SpyContact){
                    $dateTime = $task->alert;
                    $dateTimeA = explode(" ", $dateTime);
                    $dateA = explode("-",$dateTimeA[0]);
                    $timeA = explode(":",$dateTimeA[1]);
                    $formattedDateTime = $timeA[0].":".$timeA[1]." ".$dateA[2]."-".$dateA[1]."-".$dateA[0];
                    ?>
                    <div class="col-xs-12 task-list-item" key="<?=$task->task_id; ?>">
                        <!-- default visible -->
                        <?php if($type->name == "llamada de cortesía mensual"){ ?>
                            <div class="col-xs-2 task-list-type" style="background: <?=$type->color; ?>" data-toggle="tooltip" data-placement="right" title="Llamada de cortesía mensual">LL.C.M</div>
                        <?php } else { ?>
                            <div class="col-xs-2 task-list-type" style="background: <?=$type->color; ?>"> <?=$type->name ?></div>
                        <?php } ?>
                        <div class="col-xs-6 task-list-subject"><?=$task->subject; ?></div>
                        <div class="col-xs-4 task-list-actions">
                            <div class="col-xs-3 task-list-expand">
                                <div class="btn btn-default">
                                    <i class="fa fa-caret-down" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Expandir tarea"></i>
                                </div>
                            </div>
                            <div class="col-xs-9 task-list-time"><?=$formattedDateTime; ?></div>
                        </div>
                        <!-- task expanded -->
                        <div class="col-xs-12 task-list-expanded task-detail-close">
                            <!-- task client info -->
                            <div class="col-xs-4 task-client-data">
                                <div class="col-xs-3 client-name-title">Cliente: </div>
                                <div class="col-xs-9 client-name"><?=$client->name; ?></div>

                                <div class="col-xs-3 contact-name-title">Contacto: </div>
                                <div class="col-xs-9 contact-name"><?=$contactMain->name; ?></div>

                                <div class="col-xs-3 contact-phone-title">Tel&eacute;fono: </div>
                                <div class="col-xs-9 contact-phone"><?=$contactMain->phone; ?></div>

                                <div class="col-xs-3 contact-mail-title">Email: </div>
                                <div class="col-xs-9 contact-mail"><?=$contactMain->mail; ?></div>

                            </div>
                            <!-- task actions -->
                            <div class="col-xs-1 task-list-expanded-actions">
                                <div class="btn btn-default action-home" key="home">
                                    <i class="fa fa-home" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Vista por defecto"></i>
                                </div>
                                <div class="btn btn-default action-files" key="files">
                                    <i class="fa fa-files-o" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="<?=count($files); ?> archivos"></i>
                                </div>
                                <div class="btn btn-default action-upload" key="upload">
                                    <i class="glyphicon glyphicon-plus" aria-expanded="true" data-toggle="tooltip" data-placement="top" title="Añadir archivos"></i>
                                </div>
                                <div class="btn btn-default action-report" key="report">
                                    <i class="fa fa-arrow-circle-o-right" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Finalizar tarea"></i>
                                </div>
                            </div>
                            <!-- task description -->
                            <div class="col-xs-5 task-list-description">
                                <textarea class="form-control"><?=$task->description ; ?></textarea>
                                <div class="btn btn-default task-update-text disabled" key="<?=$task->task_id;?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </div>
                            </div>
                            <!-- task report -->
                            <div class="col-xs-5 task-list-report optional-task-view hidden">
                                <textarea class="form-control">Escribe un reporte si es necesario. (OPCIONAL)</textarea>
                                <div class="btn btn-default task-list-finalize" key="<?=$task->task_id ;?>" title="Guardar reporte y finalizar">
                                    <i class="glyphicon glyphicon-check" aria-hidden="true"></i>
                                </div>
                            </div>
                            <!-- task files -->
                            <div class="col-xs-6 task-list-files optional-task-view hidden">
                                <?php if(count($files) > 0){ ?>
                                    <div class="tasl-file-list">
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
                                                <div class="task-file-item" data-toggle="tooltip" data-placement="bottom" title="<?=$file->short_description; ?>">
                                                    <i class="fa <?=$icon[0]; ?>" aria-hidden="true" style="content: <?=$icon[1] ;?>;!important;"></i>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="task-not-files">Sin archivos adjuntos</div>
                                <?php } ?>
                            </div>
                            <!-- task upload -->
                            <div class="col-xs-6 task-list-upload optional-task-view hidden">
                                <div id="dropzone-title" class="col-xs-12">Archivos adjuntos</div>
                                <form id="task-dropzone" action="?r=task/file-upload&id=<?=$task->task_id; ?>" class="dropzone"></form>
                            </div>
                        </div>
                    </div>
                <?php }
                }
            }
        }?>
        </div>
    </div>
    <!-- filters bar -->
    <div id="task-filters-col" class="col-xs-2">
        <div id="task-filters-title" class="col-xs-12">Filtrado de tareas</div>
        <div id="clear-filters" class="btn btn-default">Limpiar filtros</div>
        <?php $form = ActiveForm::begin([
            'id' => 'task-filters',
            'method' => "get",
            'validateOnChange' => true,
            'options' => [
                'class' => 'form-horizontal'
            ],
        ]) ?>
            <div class="col-xs-12" >
                <?= $form->field($model, 'client')->dropDownList(ArrayHelper::map(SpyClient::find()->orderBy('name ASC')->all(), 'client_id', 'name'), [
                    'prompt' => 'Cliente',
                    'name' => 'client',
                    'onchange' => 'this.form.submit()'
                ])->label(false) ?>
            </div>
            <div class="col-xs-12" >
                <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(SpyTaskType::find()->orderBy('name ASC')->all(), 'taskT_id', 'name'), [
                    'prompt' => 'Tipo de tarea',
                    'name' => 'type',
                    'onchange' => 'this.form.submit()',
                ])->label(false) ?>
            </div>
            <div class="col-xs-9">
                <?= $form->field($model, 'date')->textInput([
                    'id' => 'task-date-search',
                    'name' => 'date',
                    'placeholder' => 'Buscar por fecha'
                ])->label(false) ?>
            </div>
            <?= Html::submitButton('<i class="fa fa-search" aria-hidden="true"></i>',[
                'id' => 'search-for-date',
                'class' => 'col-xs-1 btn btn-default',
            ]); ?>
            <div class="col-xs-12">
                <?= $form->field($model, 'category')->dropDownList(ArrayHelper::map(SpyCategory::find()->orderBy('name ASC')->all(), 'category_id', 'name'), [
                    'prompt' => 'Categoría',
                    'name' => 'category',
                    'onchange' => 'this.form.submit()',
                ])->label(false) ?>
            </div>
            <div class="col-xs-12">
                <?= $form->field($model, 'sector')->dropDownList(ArrayHelper::map(SpySector::find()->orderBy('name ASC')->all(), 'sector_id', 'name'), [
                    'prompt' => 'Sector',
                    'name' => 'sector',
                    'onchange' => 'this.form.submit()'
                ])->label(false) ?>
            </div>
            <div class="col-xs-12">
                <?= $form->field($model, 'faculty')->dropDownList(ArrayHelper::map(SpyFaculty::find()->orderBy('name ASC')->all(), 'faculty_id', 'name'), [
                    'prompt' => 'Facultad',
                    'name' => 'faculty',
                    'onchange' => 'this.form.submit()'
                ])->label(false) ?>
            </div>
            <div class="col-xs-12">
            <?= $form->field($model, 'department')->dropDownList(ArrayHelper::map(SpyDepartment::find()->orderBy('name ASC')->all(), 'department_id', 'name'), [
                'prompt' => 'Departamento',
                'name' => 'department',
                'onchange' => 'this.form.submit()'
            ])->label(false) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
    <!-- pagers -->
    <div id="task-pagers" class="col-xs-2">
        <?= LinkPager::widget(['pagination' => $taskList->pagination]); ?>
    </div>
</div>