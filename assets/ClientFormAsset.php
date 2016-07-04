<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 17:44
 */

namespace app\assets;


use yii\web\AssetBundle;

class ClientFormAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/client-form.css',
    ];
    public $js = [
        'js/plugins/locationpicker.jquery.js',
        'js/plugins/gmap3.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}