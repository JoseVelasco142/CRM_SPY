<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 14:00
 */

namespace app\assets;
use yii\web\AssetBundle;

class AdminNotesAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin-notes.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}