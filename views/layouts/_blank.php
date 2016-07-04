<?php

use yii\helpers\Html;
use app\assets\CoreAsset;

CoreAsset::register($this);
$app = Yii::$app;
$_Id = $app->getUser()->identity;
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
        <style>
            #logout{
                height: 44px;
                margin-top: 0.75%;
                padding: 0.75%;
                font-weight: bolder;
                text-align: center;
            }
            .fa.fa-user-plus {
                top: 25%;
            }
            #show-commercials {
                position: absolute;
                top: 22.5%;
                left: 85%;
                background: yellow;
                font-size: 150%;
                padding: 0.25% 0.5%;
            }
            @media (min-width: 1360px) and (max-width: 1399px) {
                #accordion .link {
                    line-height: 38.5px!important;
                }
                #logout {
                    height: 40px;
                    margin-top: 0.60%;
                    padding: 0.75%;
                }
                #show-commercials {
                    top: 15.5%;
                }
            }
            @media (min-width: 1280px) and (max-width: 1359px) {
                #accordion .link {
                    line-height: 41.5px!important;
                }
                #logout {
                    height: 41px;
                    margin-top: 0.35%;
                }
            }
        </style>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div id="layout-content" class="container-fluid">
        <!-- top navbar-->
        <div id="top-bar" class="col-xs-12 navbar">
            <div id="logo" class="col-xs-2">
                <div class="link"><a href="?r=site/index"><img class="img-responsive" src="img/logo-spy.png" /></a></div>
            </div>
            <div id="show-commercials" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Mostar contraseñas actuales">
                <i class="fa fa-key" aria-hidden="true"></i>
            </div>
            <div id="logout" class="col-xs-1 col-xs-offset-9 btn btn-default">Salir</div>
        </div>
        <div class="row">
            <!-- vertical menu -->
            <ul id="accordion" class="col-xs-2 mCustomScrollbar"  data-mcs-theme="dark">
                <li>
                    <a class="link" href="?r=admin/index"><i class="fa fa-users" aria-hidden="true"></i>Comerciales</i></a>
                </li>
                <li>
                    <a href="?r=admin/calendar" class="link"><i class="fa fa-calendar" aria-hidden="true"></i>Calendario</a>
                </li>
                <li>
                    <div id="add-commercial" class="link"><i class="fa fa-user-plus" aria-hidden="true"></i>A&ntilde;adir comercial</i></div>
                </li>
                <li>
                    <a class="link" id="parse-clients" href="?r=admin/parse"><i class="fa fa-upload" aria-hidden="true"></i>Importar clientes</i></a>
                </li>
                <li>
                    <div class="link" id="new-shared-note"><i class="fa fa-calendar" aria-hidden="true"></i>Nueva nota</div>
                </li>
                <li>
                    <a href="?r=admin/guides" class="link" id="guides"><i class="fa fa-files-o" aria-hidden="true"></i>Gu&iacute;as</a>
                </li>
                <li>
                    <a href="?r=admin/notes" class="link" id="notes"><i class="fa fa-sticky-note-o" aria-hidden="true"></i>Notas</a>
                </li>
            </ul>
            <!-- content viewer -->
            <div id="main-viewer" class="col-xs-10 container-fluid"><?= $content ?></div>
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
