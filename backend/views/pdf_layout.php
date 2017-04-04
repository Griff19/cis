<?php
/**
 * Представление для вывода pdf документа во фрейм, с возможностью добавить элементы вверху страницы
 * Для реализации нужно в контроллере создать функцию с содержанием:
 * ```php
 *     Url::remember([ {Url для возврата на предыдущую страницу} ]);
 *     return $this->renderAjax('../pdf_layout', ['url' => {Ссылка на экшн генерации pdf-документа} ]);
 * ```
 * На страницах, где нужно открыть pdf-документ, вызвывать эту функцию.
 *
 * @var $this \yii\web\View
 * @var $url string Ссылка на экшн генерации pdf-документа
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerAssetBundle('backend\assets\AppAsset');
$this->registerAssetBundle('backend\assets\AssetPdf');

?>
<div>
    <div style="float: right"><?= Html::a('<span class="glyphicon glyphicon-remove"></span> Закрыть', Url::previous())?></div>
    <iframe src="<?= Url::to($url)?>" width="100%" style="height: calc(100% - 20px)"></iframe>
</div>

<?php
//Если будет проблема с поддержкой calc() то раскомментировать это:
//$this->registerJs("$(window).resize( function() { $('iframe').css({ 'height': $(window).height() - 20 }) }); $(window).resize();");
?>
