<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 14:00
 */

namespace app\assets;
use yii\web\AssetBundle;

class AdminIndexAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/morris.css',
        'css/admin-index.css',
    ];
    public $js = [
        'js/plugins/raphael-min.js',
        'js/plugins/morris.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}