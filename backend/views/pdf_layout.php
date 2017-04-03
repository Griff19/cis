<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerAssetBundle('backend\assets\AssetPdf');
?>
<div>
    .<div style="float: right"><?= Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::previous())?></div>
    <iframe src="<?= Url::to($url)?>" width="100%"></iframe>

</div>

<?php
$this->registerJs("$(window).resize( function() { $('iframe').css({'height': $(window).height() - 40}) }); $(window).resize();");
?>
