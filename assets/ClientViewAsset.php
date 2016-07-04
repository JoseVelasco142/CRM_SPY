<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 17:47
 */

namespace app\assets;


use yii\web\AssetBundle;

class ClientViewAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/morris.css',
        'css/client-view.css',
    ];
    public $js = [
        'js/plugins/gmap3.js',
        'js/plugins/raphael-min.js',
        'js/plugins/morris.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
