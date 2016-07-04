<?php

use app\assets\CommercialCalendarAsset;

header('Content-type: text/plain; charset=utf-8');
CommercialCalendarAsset::register($this);
$this->title = "Calendario";
?>
<div id="main-content">
    <div id="calendar-main"></div>
    <div id="colors-legend"></div>
</div>



