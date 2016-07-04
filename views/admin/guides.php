<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 21:17
 */
use app\assets\AdminGuidesAsset;
use app\models\SpyGuide;

$this->title = "Spy | Guías";
AdminGuidesAsset::register($this);

?>
<div id="main-content">
    <div id="guide-list-title" class="col-xs-11">Listado de guiones</div>
    <div id="add-guide" class="col-xs-1 btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </div>
    <div id="guide-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">
        <?php
        if(count($guides)>0){
            foreach ($guides as $guide) {
            if ($guide instanceof SpyGuide) { ?>
                <div class="guide-item col-xs-12">
                    <div class="col-xs-11 btn btn-default guide-line">
                        <div class="col-xs-11 guide-title"><?= $guide->short_description; ?></div>
                    </div>
                    <div class="col-xs-1 btn btn-default delete-guide"  key="<?=$guide->guide_id ?>">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </div>
                    <div class="guide-info col-xs-12">
                        <div class=" btn btn-default guide-save disabled" key="<?=$guide->guide_id; ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </div>
                        <label class="col-xs-12">
                            <textarea class="form-control guide-text"><?=$guide->text ?></textarea>
                        </label>
                        <?php
                        $files = $guide->files;
                        if(count($files) > 0){ ?>
                            <div class="col-xs-8 file-list">
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
                                             <a href="?r=admin/get-file&id=<?=$file->file_id ?>" target="_blank">
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
                            <div class="col-xs-8 not-files">Sin archivos adjuntos</div>
                        <?php } ?>
                        <form action="?r=admin/file-upload&id=<?=$guide->guide_id ?>" class="col-xs-4 guide-dropzone dropzone"></form>
                    </div>
                </div>
            <?php }
        }
        } else { ?>
            <div id="not-guides">A&uacute;n no se ha a&ntilde;adido ning&uacute;n gui&oacute;n</div>
        <?php }?>
    </div>
</div>

