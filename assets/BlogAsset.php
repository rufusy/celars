<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 12:23 PM
 */

namespace app\assets;

use yii\web\AssetBundle;

class BlogAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,700,800|Poppins:300,400,700',
        'css/bootstrap.css',
        'css/fonts.css',
        'css/style.css',
        'css/celars.css'
    ];

    public $js = [
        'js/core.min.js',
        'js/script.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}