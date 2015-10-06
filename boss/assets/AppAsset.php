<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace boss\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'adminlte/css/font-awesome.min.css',
        'adminlte/css/ionicons.min.css',
        'adminlte/css/AdminLTE.css',
        'css/openWin.css',
        'adminlte/css/font-awesome.min.css',
        'css/courseware.css',
        'css/custom.css',
    ];
    public $js = [
        'adminlte/js/AdminLTE/app.js',
//        'adminlte/js/jquery.min.js',
        'js/interview.js',
        'js/openWin.js',
        'js/cascade.js',
        'js/searchbox.js',
        'js/bootpage.js',
        'js/custom.js',
        'js/advert.js',
        'js/spec.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //���尴�����JS������ע�����˳�������
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'boss\assets\AppAsset']);
    }

    //���尴�����css������ע�����˳�������
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'boss\assets\AppAsset']);
    }
}
