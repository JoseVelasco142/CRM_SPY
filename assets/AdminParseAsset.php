<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 18:32
 */

namespace app\assets;


use yii\web\AssetBundle;

class AdminParseAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin-parse.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}