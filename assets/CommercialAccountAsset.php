<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 17:47
 */

namespace app\assets;


use yii\web\AssetBundle;

class CommercialAccountAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/commercial-account.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
