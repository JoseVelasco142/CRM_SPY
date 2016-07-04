<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 12/06/2016
 * Time: 19:37
 */

namespace app\assets;


use yii\web\AssetBundle;

class CommercialMapsAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/commercial-maps.css',
    ];
    public $js = [
        'js/plugins/gmap3.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}