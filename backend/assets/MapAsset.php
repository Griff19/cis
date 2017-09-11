<?php
/**
 * Скрипт для работы карты
 */
namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_END];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
        'js/get_map.js',
    ];
}