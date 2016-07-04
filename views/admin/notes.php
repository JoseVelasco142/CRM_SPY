<?php


use app\assets\AdminNotesAsset;
use app\models\SpyNote;
AdminNotesAsset::register($this);
$this->title = "Spy | Notas";
?>
<div id="main-content" class="mCustomScrollbar" data-mcs-theme="dark">
    <div id="notes-list" class="col-xs-12">
        <?php
        foreach($notes as $note){
            if($note instanceof SpyNote){ ?>
                <div class="col-xs-3 note-item" key="<?=$note->note_id; ?>">
                    <div class="col-xs-9 note-subject"><?=$note->short_description?></div>
                    <div class="col-xs-1 btn btn-default note-update disabled" data-toggle="tooltip" data-placement="bottom" title="Actualizar">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-xs-1 btn btn-default note-finalize" data-toggle="tooltip" data-placement="bottom" title="Finalizar">
                        <i class="glyphicon glyphicon-check" aria-hidden="true"></i>
                    </div>
                    <div class="col-xs-1 btn btn-default note-delete" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </div>
                    <label class="col-xs-12">
                        <textarea class="form-control note-text"><?=$note->text ?></textarea>
                    </label>
                </div>
            <?php }
        } ?>
    </div>
</div>