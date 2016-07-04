<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 21:17
 */
use app\assets\CommercialGuidesAsset;
use app\models\SpyGuide;

$this->title = "Spy | Guías";
CommercialGuidesAsset::register($this);

?>
<div id="main-content">
    <div id="guide-list-title" class="col-xs-12">Listado de guiones</div>
    <div id="guide-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark">
        <?php
        if(count($guides)>0){
            foreach ($guides as $guide) {
            if ($guide instanceof SpyGuide) { ?>
                <div class="guide-item col-xs-12">
                    <div class="col-xs-12 btn btn-default guide-line">
                        <div class="col-xs-11 guide-title"><?= $guide->short_description; ?></div>
                    </div>
                    <div class="guide-info col-xs-12">
                        <label class="col-xs-12">
                            <textarea class="form-control guide-text" readonly><?=$guide->text ?></textarea>
                        </label>
                        <?php
                        $files = $guide->files;
                        if(count($files) > 0){ ?>
                            <div class="col-xs-12 file-list">
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
                                    <a href="?r=admin/get-file&id=<?=$file->file_id ?>" target="_blank">
                                        <div class="col-xs-1 file-item" data-toggle="tooltip" data-placement="right" title="<?=$file->short_description; ?>">
                                            <i class="fa <?=$icon[0]; ?>" aria-hidden="true" style="content: <?=$icon[1] ;?>;!important;"></i>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="col-xs-12 not-files">Sin archivos adjuntos</div>
                        <?php } ?>
                    </div>
                </div>
            <?php }
        }
        } else { ?>
            <div id="not-guides">A&uacute;n no se ha a&ntilde;adido ning&uacute;n gui&oacute;n</div>
        <?php }?>
    </div>
</div>

