<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 17:45
 */

namespace app\assets;


use yii\web\AssetBundle;

class ClientMassiveAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/client-massive.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}