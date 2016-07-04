<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 05/06/2016
 * Time: 19:48
 */
use app\assets\AdminIndexAsset;

$this->title = "Spy | Administración";
AdminIndexAsset::register($this);
?>
<img id="load-icon" class="hidden" src="img/load-icon.gif"
     style="position: absolute; z-index: 9999999; margin: 23.5% 45.5%; height: 75px;"/>
<div id="main-content">
    <!-- top bar selector / total counters -->
    <div id="global-info" class="col-xs-12">
        <!-- commercial selector -->
        <div id="commercial-list" class="col-md-3 col-xs-12">
            <label for="commercial-selector" class="hidden"></label>
            <select id="commercial-selector" class="form-control">
                <option selected="selected" disabled="disabled">Selecciona un comercial</option>
                <?php
                foreach ($commercials as $commercial) {
                    if ($commercial instanceof SpyCommercial)
                        ?>
                        <option value="<?=$commercial->commercial_id ?>"><?=$commercial->name." ".$commercial->lastname ?></option>
                <?php }
                ?>
            </select>
        </div>

        <div class="col-md-9">
            <!-- commercials number -->
            <div class="col-xs-4 counter-info">
                <div class="col-xs-9 counter-title">Nº comerciales:</div>
                <div class="col-xs-3 counter-number"><?= $totalCommercials; ?></div>
            </div>
            <!-- total tasks -->
            <div class="col-xs-4 counter-info">
                <div class="col-xs-9 counter-title">Nº tareas:</div>
                <div class="col-xs-3 counter-number"><?= $totalTasks; ?></div>
            </div>
            <!-- total tasks today -->
            <div class="col-xs-4 counter-info">
                <div class="col-xs-9 counter-title">Nº tareas creadas hoy:</div>
                <div class="col-xs-3 counter-number"><?= $totalTaskToday; ?></div>
            </div>
        </div>
    </div>
    <!-- commercial info -->
    <div id="global-commercial" class="col-xs-12">
        <!-- statistics block commercial tasks -->
        <div class="col-xs-12" style="padding: 0">
            <div class="col-xs-4 donut-block">
                <div class="donut-opt-block hidden">
                    <div class="dropdown donut-options col-xs-1 " key="today">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                        <ul key="today" class="dropdown-menu">
                            <li key="created"><a>Creadas</a></li>
                            <li key="opened"><a>Pendientes</a></li>
                            <li key="finalized"><a>Finalizadas</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-11 donut-showing"></div>
                </div>
                <div id="donut-today" class="donut hidden"></div>
            </div>
            <div class="col-xs-4 donut-block">
                <div class="donut-opt-block hidden">
                    <div class="dropdown donut-options col-xs-1 " key="week">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                        <ul key="week" class="dropdown-menu">
                            <li key="created"><a>Creadas</a></li>
                            <li key="opened"><a>Pendientes</a></li>
                            <li key="finalized"><a>Finalizadas</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-11 donut-showing"></div>
                </div>
                <div id="donut-week" class="donut hidden"></div>
            </div>
            <div class="col-xs-4 donut-block">
                <div class="donut-opt-block hidden">
                    <div class="dropdown donut-options col-xs-1 " key="month">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                        <ul key="month" class="dropdown-menu">
                            <li key="created"><a>Creadas</a></li>
                            <li key="opened"><a>Pendientes</a></li>
                            <li key="finalized"><a>Finalizadas</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-11 donut-showing"></div>
                </div>
                <div id="donut-month" class="donut hidden"></div>
            </div>
        </div>

        <!-- tasks lists -->
        <div class="col-xs-6 list-title">Tareas pendientes</div>
        <div class="col-xs-6 list-title">Tareas finalizadas</div>
        <div id="opened-list" class="col-xs-6 mCustomScrollbar" data-mcs-theme="dark"></div>
        <div id="closed-list" class="col-xs-6 mCustomScrollbar" data-mcs-theme="dark"></div>
    </div>
</div>