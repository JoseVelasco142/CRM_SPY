<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 07/05/2016
 * Time: 21:16
 */
use app\assets\SiteHomeAsset;
use app\models\SpyTask;
SiteHomeAsset::register($this);
$this->title = "Spy | HOME";
?>
<div id="main-content">
    <div id="todayTasks" class="col-xs-8">
        <div id="todayTasks-title" class="col-xs-12">Tarea activas</div>
        <div id="todayTasks-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">
            <?php
            if(count($allTasks)>0){
                foreach($allTasks as $task){
                    if($task instanceof SpyTask){
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
                                    <div class="col-xs-4 task-datetime-title">
                                        <i class="fa fa-clock-o" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="<?=$past ? "Pasada de hora/fecha" : "Dentro del horario";?>" style="color:<?=$past ? "#ff4545" : "#0d0"; ?>;"></i>
                                    </div>
                                    <div class="col-xs-8 task-datetime-time">
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
                }
            } else { ?>
                <div id="not-tasks">No hay tareas pendientes.</div>
            <?php } ?>
        </div>
    </div>
    <div id="home-datetime" class="col-xs-4">
        <div class="clock">
            <div id="Date"></div>
            <ul>
                <li id="hours"> </li>
                <li id="point">:</li>
                <li id="min"> </li>
                <li id="point">:</li>
                <li id="sec"> </li>
            </ul>
        </div>
    </div>
    <div id="clients-statistics" class="col-xs-12"></div>
</div>
