<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 06/06/2016
 * Time: 17:47
 */

namespace app\assets;


use yii\web\AssetBundle;

class CommercialCalendarAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/fullcalendar.min.css',
        'css/commercial-calendar.css',
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
