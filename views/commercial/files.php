<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 12/06/2016
 * Time: 4:41
 */
use app\assets\CommercialFilesAsset;

$this->title = "Spy | Ficheros";
CommercialFilesAsset::register($this);
?>
<div id="main-content">
    <div id="file-list-title" class="col-xs-12">Spy Cloud</div>
    <?php if(count($files) > 0){ ?>
    <div id="file-list-commercials" class="col-xs-10 file-list">
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
            <div class="col-xs-1 file-item">
                <div class="col-xs-8 file-icon" data-toggle="tooltip" data-placement="bottom" title="<?=$file->short_description; ?>">
                    <i class="fa <?=$icon[0]; ?>" aria-hidden="true" style="content: <?=$icon[1] ;?>;!important;"></i>
                </div>
                <div class="col-xs-4" style="padding: 0">
                    <a href="?r=commercial/get-file&id=<?=$file->file_id ?>" target="_blank">
                        <div class="col-xs-12 btn btn-default file-download"  data-toggle="tooltip" data-placement="right" title="Descargar">
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </div>
                    </a>
                    <div class="col-xs-12 btn btn-default file-remove"  key="<?=$file->file_id ?>" data-toggle="tooltip" data-placement="right" title="Eliminar">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } else { ?>
        <div id="not-files-commercial" class="col-xs-10">Sin archivos adjuntos</div>
    <?php } ?>
    <form id="file-dropzone" class="col-xs-2 dropzone" action="?r=commercial/file-upload"></form>
</div>
