<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Для подгрузки скрипта Модальных окон
 */
class ValidDeviceAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';

	public $js = [
		'js/valid_device.js'
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}