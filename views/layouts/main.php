<?php

use app\models\SpyNote;
use yii\helpers\Html;
use app\assets\CoreAsset;

CoreAsset::register($this);
$app = Yii::$app;
$user = $app->user->identity;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
        <div id="layout-content" class="container-fluid">
            <!-- top navbar-->
            <div id="top-bar" class="col-xs-12 navbar">
                <div id="logo" class="col-xs-2">
                    <div class="link"><a href="?r=site/index"><img class="img-responsive" src="img/logo-spy.png" /></a></div>
                </div>
                <div id="shortcuts" class="col-xs-1 col-xs-offset-6 dropdown">
                    <?php
                        $notes = SpyNote::find()->where(['shared' => 1])->andWhere(['state' => 1 ])->count();
                        if(count($notes) > 0){ ?>
                            <a id="notes-shared" class="btn btn-default" href="?r=commercial/calendar" data-toggle="tooltip" data-placement="bottom" title="<?=$notes; ?> notas del administrador">
                                <i class="fa fa-exclamation" aria-hidden="true"></i>
                            </a>
                        <?php }
                    ?>
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="?r=client/create">Nuevo cliente</a></li>
                        <li><a id="shortcut-task">Nueva tarea</a></li>
                        <li><a id="shortcut-note">Nueva nota</a></li>
                    </ul>
                </div>
                <div id="account" class="col-xs-3 dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                        <?=$user->mail; ?>
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="?r=commercial/guides">Guiones</a></li>
                        <li><a href="?r=commercial/account">Perf&iacute;l</a></li>
                        <li><a id="logout">Salir</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <!-- vertical menu -->
                <ul id="accordion" class="col-xs-2 mCustomScrollbar"  data-mcs-theme="dark">
                    <li>
                        <div class="link"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs">Clientes</span><i class="fa fa-chevron-down"></i></div>
                        <ul class="submenu">
                            <li><a href="?r=client/index"><i class="fa fa-list" aria-hidden="true"></i>Cartera</a></li>
                            <li><a href="?r=client/create"><i class="fa fa-plus" aria-hidden="true"></i>Nuevo cliente</a></li>
                            <li><a href="?r=client/massive"><i class="fa fa-square" aria-hidden="true"></i>Creaci&oacute;n masiva</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="link"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hidden-xs">Tareas</span><i class="fa fa-chevron-down"></i></div>
                        <ul class="submenu">
                            <li><a href="?r=task/index"><i class="fa fa-bars" aria-hidden="true"></i>Agenda</a></li>
                            <li><a id="menu-create-note"><i class="fa fa-plus" aria-hidden="true"></i>Nueva nota</a></li>
                            <li><a id="menu-create-task"><i class="fa fa-thumb-tack" aria-hidden="true"></i>Nueva tarea</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="link" href="?r=commercial/calendar"><i class="fa fa-calendar" aria-hidden="true"></i><span class="hidden-xs">Calendario</span></a>
                    </li>
                    <li>
                        <a class="link" href="?r=commercial/files"><i class="fa fa-files-o" aria-hidden="true"></i><span class="hidden-xs">Ficheros</span></a>
                    </li>
                    <li>
                        <a class="link"  href="?r=commercial/maps"><i class="fa fa-globe" aria-hidden="true"></i><span class="hidden-xs">Mapas</span></a>
                    </li>
                </ul>
                <!-- content viewer -->
                <div id="main-viewer" class="col-xs-10"><?= $content ?></div>
            </div>
            <!-- bottom navbar -->
            <div class="navbar navbar-fixed-bottom">
                <div id="ftr" class="col-xs-2">
                        <a href="#">&copy; Enterprise <?= date('Y') ?></a>
                </div>
            </div>
        </div>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
