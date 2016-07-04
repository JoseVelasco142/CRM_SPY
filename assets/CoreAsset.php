<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 07/05/2016
 * Time: 19:58
 */
namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CoreAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/bootstrap.min.css',
        'css/plugins/default.min.css',
        'css/plugins/semantic.min.css',
        'css/plugins/alertify.min.css',
        'css/plugins/jquery.mCustomScrollbar.min.css',
        'css/plugins/dropzone.css',
        'css/plugins/font-awesome.min.css',
        'css/plugins/bootstrap.datepicker.min.css',
        'css/plugins/jquery.timepicker.css',
        'css/modal-views.css',
        'css/core.css',
    ];
    public $js = [
        'assets/7274620f/js/bootstrap.min.js',
        'js/plugins/alertify.min.js',
        'js/plugins/jquery.mCustomScrollbar.min.js',
        'js/plugins/jquery.mCustomScrollbar.concat.min.js',
        'js/plugins/dropzone.js',
        'js/plugins/bootstrap-datepicker.min.js',
        'js/plugins/jquery.timepicker.min.js',
        'js/core.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}