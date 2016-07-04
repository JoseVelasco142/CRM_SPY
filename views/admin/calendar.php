<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 10:19
 */

use app\assets\AdminCalendarAsset;
use app\models\SpyCommercial;
use app\models\SpyTaskType;

AdminCalendarAsset::register($this);
$this->title = "Calendario";
?>
<div id="main-content">
    <div id="title-selector" class="col-md-3 col-xs-12">
        <label for="commercial-selector" class="hidden"></label>
        <select id="commercial-selector" class="form-control col-md-6  col-xs-12">
            <option selected="selected" disabled="disabled">Selecciona un comercial</option>
            <?php
            foreach($commercials as $commercial){
                if($commercial instanceof SpyCommercial)
                ?>
                <option value="<?=$commercial->commercial_id ?>"><?=$commercial->name." ".$commercial->lastname ?></option>
            <?php }
            ?>
        </select>
    </div>
    <div id="legend-color" class="col-md-9 col-xs-12">
        <?php
            foreach($types as $type){
                if($type instanceof SpyTaskType) {
                    switch ($type->name){
                        case "llamada de cortesía mensual":
                            $class = "task-type courtesy-call ";
                            break;
                        case "servicio técnico":
                            $class = "task-type service-tec";
                            break;
                        default:
                            $class =  "task-type";
                            break;
                    }
                    ?>
                <div class="<?=$class; ?>" style="background: <?=$type->color; ?>"><?=$type->name; ?></div>
        <?php }
            } ?>
    </div>
    <div id="admin-calendar" class="col-xs-12"></div>
</div>

