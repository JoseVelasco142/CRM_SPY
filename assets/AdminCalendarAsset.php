<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 08/06/2016
 * Time: 14:00
 */

namespace app\assets;
use yii\web\AssetBundle;

class AdminCalendarAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/fullcalendar.min.css',
        'css/admin-calendar.css',
    ];
    public $js = [
        'js/plugins/moment.min.js',
        'js/plugins/fullcalendar.min.js',
        'js/plugins/fullcalendar-lang.es.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}