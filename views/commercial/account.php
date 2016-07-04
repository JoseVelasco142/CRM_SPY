<?php

use app\assets\CommercialAccountAsset;
use app\models\SpyCommercial;

CommercialAccountAsset::register($this);
$this->title = "Spy | Perfíl";
if($commercial instanceof SpyCommercial) {
    ?>
    <div id="main-content">
        <div id="account-title" class="col-xs-12">Perf&iacute;l</div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="commercial-name" class="col-xs-3">Nombre:</label>
                <input id="commercial-name" class="form-control col-xs-9" readonly value="<?=$commercial->name; ?>"/>
            </div>
            <div class="form-group">
                <label for="commercial-lastname" class="col-xs-3">Apellidos:</label>
                <input id="commercial-lastname" class="form-control col-xs-9" readonly value="<?=$commercial->lastname; ?>"/>
            </div>
            <div class="form-group">
                <label for="commercial-email" class="col-xs-3">Email:</label>
                <input id="commercial-email" class="form-control col-xs-9" readonly value="<?=$commercial->email; ?>"/>
            </div>
            <div class="col-xs-12" style="padding: 0;">
                <div id="expand-passwords" class="col-xs-12 btn btn-default">Cambiar contrase&ntilde;a</div>
                <div id="passwords-form" class="col-xs-12">
                    <div class="form-group">
                        <label for="current-password" class="col-xs-4">Contrase&ntilde;a actual:</label>
                        <input id="current-password" class="form-control col-xs-8" type="password""/>
                    </div>
                    <div class="form-group">
                        <label for="new-password" class="col-xs-4">Nueva contrase&ntilde;a:</label>
                        <input id="new-password" class="form-control col-xs-8" type="password""/>
                    </div>
                    <div class="form-group">
                        <label for="new-password_v" class="col-xs-4">Confirma la contrase&ntilde;a:</label>
                        <input id="new-password_v" class="form-control col-xs-8" type="password""/>
                    </div>
                    <div id="update-password" class="col-xs-2 col-xs-offset-10 btn btn-default">ACTUALIZAR</div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>



