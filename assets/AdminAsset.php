<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        'admin/plugins/fontawesome-free/css/all.min.css',
        'admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'admin/plugins/summernote/summernote-bs4.min.css',
        'admin/css/adminlte.min.css',
        'admin/css/celars-admin.css'
    ];

    public $js = [
        'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js',
        'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js',
        'admin/plugins/summernote/summernote-bs4.min.js',
        'admin/js/adminlte.min.js',
        'admin/js/jquery_validation.js',
        'admin/js/celars-admin.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
