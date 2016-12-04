<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Для подгрузки скрипта "Разворачивания-Сворачивания Таблицы"
 */
class CollapseTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/collapse_table.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}