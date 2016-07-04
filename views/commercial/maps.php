<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 12/06/2016
 * Time: 4:41
 */
use app\assets\CommercialMapsAsset;

$this->title = "Spy | Mapas";
CommercialMapsAsset::register($this);

?>

<img id="load-icon" class="hidden" src="img/load-icon.gif" style="position: absolute;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />
<div id="main-content">
    <div class="col-xs-10" style="padding: 0">
        <div id="map" class="col-xs-12"></div>
        <div id="box" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark"></div>
    </div>
   <div class="col-xs-2" style="padding: 0">
       <div id="find-route" class="col-xs-12 btn btn-default">Calcular ruta</div>
       <div id="pointers" class="col-xs-12">
           <div class="col-xs-12 form-group">
               <div id="exit-pointers-title" class="col-xs-12">Punto de salida</div>
               <div id="exit-home" class="col-xs-2 btn btn-default" data-toogle="tooltip" data-placement="bottom" title="HOME" coordinate-x="37.15313133308989" coordinate-y="-3.5919255670584107">
                   <i class="fa fa-home" aria-hidden="true"></i>
               </div>
               <label style="margin-bottom: 0;" class="col-xs-10">
                   <input id="exit-point" class="form-control" type="text" />
               </label>
           </div>
           <div class="col-xs-12 form-group">
               <div id="exit-pointers-title" class="col-xs-12">Destino</div>
               <div id="destination-multiple" class="col-xs-2 btn btn-default" data-toogle="tooltip" data-placement="bottom" title="Múltiples ubicaciones">
                   <i class="fa fa-list" aria-hidden="true"></i>
               </div>
               <label style="margin-bottom: 0;" class="col-xs-10">
                   <input id="destination-point" class="form-control" type="text" />
               </label>
           </div>
       </div>
       <div id="clients-list" class="col-xs-12 mCustomScrollbar" data-mcs-theme="dark"></div>
   </div>
</div>
