<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Для подгрузки скрипта Модальных окон
 */
class ModalAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';

	public $js = [
		'js/modal.js'
	];

	public $jsOptions = [
		'position' => View::POS_END
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}