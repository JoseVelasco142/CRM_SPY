<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 29/05/2016
 * Time: 2:32
 */
use app\assets\ClientViewAsset;
use app\models\SpyClient;
use app\models\SpyContact;
use app\models\SpyTask;

if(isset($client) && $client instanceof SpyClient){
    ClientViewAsset::register($this);
    $this->title = "Spy | Cliente: ".$client->name;
    $app = Yii::$app;
    $session = $app->session;
    $routes = $session->get('routes');
    in_array($client->client_id, $routes) ? $added = "added" : $added = "";
    switch($client->DISC){
        case "rgb(255, 0, 0)":
            $discDesc = "disc red";
            break;
        case "rgb(0, 0, 255)":
            $discDesc = "disc blue";
            break;
        case "rgb(255, 255, 0)":
            $discDesc = "disc yellow";
            break;
        case "rgb(0, 255, 0)":
            $discDesc = "disc green";
            break;
        default:
            $discDesc = "Sin datos";
            break;
    }
?>

<div id="main-content">
    <div id="client-header" class="col-xs-12" key="<?=$client->client_id; ?>" coords="<?=$client->coordinates; ?>">
        <div id="client-name" class="col-md-10 col-xs-12">
            <div id="client-disc" style="background: <?=$client->DISC; ?>; padding: 0;" data-toggle="tooltip" data-placement="right" title="<?=$discDesc; ?>"></div>
            <div id="show-comment" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Comentario">
                <i class="fa fa-commenting" aria-hidden="true"></i>
            </div>
            <div id="comment-text" class="hidden"><?=$client->comment ?></div>
            <div id="show-equipment" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Equipamiento">
                <i class="fa fa-sitemap" aria-hidden="true"></i>
            </div>
            <div id="equipment-text" class="hidden"><?=$client->equipment ?></div>
            <?php
            if($client->photo != ""){ ?>
                <div id="show-photo" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Foto">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                </div>
                <img id="photo-img" class="hidden" src="<?=$client->photo; ?>" />
            <?php }  ?>

            <?=$client->name; ?>
        </div>
        <div id="client-actions" class="col-md-2 col-xs-12">
            <div id="client-update" class="col-xs-3 btn btn-default client-action" data-toggle="tooltip" data-placement="bottom" title="Modificar ficha">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </div>
            <div id="client-new-contact" class="col-xs-3 btn btn-default client-action" data-toggle="tooltip" data-placement="bottom" title="Añadir contacto">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
            </div>
            <div id="client-add-to-route" class="col-xs-3 btn btn-default client-action <?=$added; ?>" data-toggle="tooltip" data-placement="bottom" title="<?=$added ? "Eliminar de la ruta" : "Añadir a la ruta"   ?>">
                <img class="img-responsive mCS_img_loaded" src="img/streetview-icon.png" />
            </div>
            <div id="client-new-task" class="col-xs-3 btn btn-default client-action" data-toggle="tooltip" data-placement="bottom" title="Nueva tarea">
                <i class="fa fa-thumb-tack" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div id="client-body" class="col-xs-12">
        <!-- contacts -->
        <div id="contacts-list" class="col-md-3 col-xs-6">
            <?php
            foreach($client->contacts as $contact){
                if($contact instanceof SpyContact){
                    $contact->main ? $main = "contact_main" : $main = "";
                    empty($contact->mail) ? $contact->mail = "&nbsp;" : null;
                    $contact->position_id!=1 ? $positionName = $contact->position->name : $positionName = "Sin definir";
                    !$contact->main
                        ? $btn = '<div class=" btn btn-default set-as-contact-main" data-toggle="tooltip" data-placement="top" title="Marcar como contacto principal" key="'.$contact->contact_id.'"><i class="fa fa-flag" aria-hidden="true"></i></div>'
                        : $btn = "";
                    $content =  '<div class="contact-data-block">
                                        '.$btn.'
                                        <div class="col-xs-3" style="padding:0; margin-bottom: 2.5%; font-weight:600;">Tel&eacute;fono: </div><div class="col-xs-9" style="padding-left:5px; margin-bottom: 2.5%;">'.$contact->phone.'</div>
                                        <div class="col-xs-3" style="padding:0; margin-bottom: 2.5%; font-weight:600;">Email: </div><div class="col-xs-9 popover-client-mail" style="padding:0; margin-bottom: 2.5%;">'.$contact->mail.'</div>
                                        <div class="col-xs-3" style="padding:0; font-weight:600;">Cargo: </div><div class="col-xs-9" style="padding:0;">'.$positionName.'</div>
                                   </div>';
                    ?>
                    <button class="col-xs-3 contact-item <?=$main; ?>"
                            data-toggle="popover" data-container="body" data-placement="top" data-html="true"  title="<?=$contact->name ?>"
                            data-content='<?=$content ?>'
                        ></button>
                <?php }
            }?>
        </div>
        <!-- classification / state -->
        <div id="classification-state" class="col-md-4 col-xs-6">
            <!-- classification -->
            <div id="classification" class="col-xs-12">
                <?php
                if(empty($client->faculty_id)){
                    ?>
                    <div class="col-xs-12 classification-group">
                        <label for="client-category" class="col-xs-4">Categor&iacute;a:</label>
                        <div id="client-category" class="col-xs-8"><?=$client->category->name; ?></div>
                    </div>
                    <div class="col-xs-12 classification-group">
                        <label for="client-sector" class="col-xs-4">Sector:</label>
                        <div id="client-sector" class="col-xs-8"><?=$client->sector->name; ?></div>
                    </div>
                <?php } else {
                    ?>
                    <div class="col-xs-12 classification-group">
                        <label for="client-faculty" class="col-xs-4">Facultad:</label>
                        <div id="client-faculty" class="col-xs-8"><?=$client->faculty->name; ?></div>
                    </div>
                    <div class="col-xs-12 classification-group">
                        <label for="client-department" class="col-xs-4">Depart.:</label>
                        <div id="client-department" class="col-xs-8"><?=$client->department->name; ?></div>
                    </div>
                <?php } ?>
            </div>
            <!-- state -->
            <div id="state" class="col-xs-12">
                <?php switch($client->state) {
                    case 1:
                        $title = "Pendiente de llamada teléfonica";
                        break;
                    case 2:
                        $title = "Pendiente de visita";
                        break;
                    case 3:
                        $title = "Pendiente de enviar email";
                        break;
                    case 4:
                        $title = "Pendiente de enviar presupuesto";
                        break;
                    case 5:
                        $title = "Pendiente de asistencia técnica";
                        break;
                    case 6:
                        $title = "Pendiente de cierre de venta";
                        break;
                    case 7:
                        $title = "Pendiente de llamada de cortesía mensual";
                        break;
                    default:
                        $title = "Sin acciones pendientes";
                        break;
                } ?>
                <div class="col-xs-9">Estado actual: </div>
                <div class="col-xs-3" data-toggle="tooltip" data-placement="top" title="<?=$title; ?>" >
                    <img class="img-responsive action-icon" src="img/state-<?=$client->state; ?>.png" />
                </div>
            </div>
        </div>
        <!-- location -->
        <div id="location" class="col-md-5 col-xs-12">
            <?= !empty($client->coordinates)
                ? '<div id="client-map" coords="'.$client->coordinates.'" class="col-xs-12"></div>'
                : ' <img id="false-client-map" class="col-xs-12 img-responsive no-address" src="img/map-no-signal.jpg">';
            ?>
        </div>
        <!-- full address -->
        <?= !empty($client->coordinates)
             ? '<div id="full-address" class="col-md-7 col-xs-12">'.$client->address.', '.$client->city.', '.$client->province.', '.$client->postal_code.'</div>'
             : '<div id="full-address" class="col-md-7 col-xs-12">Localizaci&oacute;n no asignada </div>';
            ?>
        <!-- tasks -->
        <div id="tasks-list-title" class="col-xs-7">Tarea pendientes</div>
        <div id="tasks-list" class="col-xs-7 mCustomScrollbar" data-mcs-theme="dark">
            <?php
            $allTasks = $client->openedTasks;
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
                }
            } else { ?>
                <?='<div id="not-tasks">No hay tareas pendientes.</div>'; ?>
            <?php } ?>
        </div>
        <div id="statistics" class="col-xs-5">
        </div>
    </div>
</div>
<?php } ?>