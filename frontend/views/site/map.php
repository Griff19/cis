<?php
/**
 * Страница глобальной карты
 */
use backend\models\Workplaces;
use backend\models\Coordinate;
use backend\models\Branches;
use frontend\assets\MapAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $floor integer - номер этажа
 * @var $branch integer - идентификатор филиала
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
MapAsset::register($this);

$this->title = 'Карта сайта';
?>
<style>
    .map {
        width: 100%;
        height: 500px;
        padding: 0;
        margin: 0 0 10px;
        outline: auto;
    }
    .field-coordinatesearch-snp, .field-coordinatesearch-branch_id, #w0 {
        display: inline-flex;
        margin-left: 5px;
    }
    #w0 {
        display: inline-flex;
        height: 33px;
    }
    #coordinatesearch-snp, #coordinatesearch-branch_id {
        margin-top: -5px;
    }
</style>

<?= Html::a('1 Этаж', ['map', 'floor' => 1, 'branch' => $branch], ['class' => 'btn btn-default', 'style' => $floor == 1 ? 'font-weight: 600' : '']) ?>
<?= Html::a('2 Этаж', ['map', 'floor' => 2, 'branch' => $branch], ['class' => 'btn btn-default', 'style' => $floor == 2 ? 'font-weight: 600' : '']) ?>

<?php $form = ActiveForm::begin([
	'action' => ['site/map?floor='.$floor],
	'method' => 'get',
]); ?>
<?= $form->field($search, 'snp')->dropDownList(
	ArrayHelper::map(
		Coordinate::getOwners($floor, $branch), 'snp', 'snp'
	), ['prompt' => 'Пустой фильтр...'])
?>
<?= $form->field($search, 'branch_id')->dropDownList(
	ArrayHelper::map(
        Coordinate::getBranches(), 'id', 'value'
	        //Branches::arrayBranches(), 'id', 'value'
	)
)?>
<?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary', 'style' => 'margin:-5px 4px 4px 3px;']) ?>
<?php ActiveForm::end(); ?>

<div id = "map" class="map"></div>

<script type="text/javascript">
    var points = [];
    var floor = <?= $floor ?>;
    var branch = <?= $branch ?>;
    var max_zoom = <?= Coordinate::$mapParams[$branch]['max_zoom'] ?>;
    var pic_width = <?= Coordinate::$mapParams[$branch]['pic_width'] ?>;
    var pic_height = <?= Coordinate::$mapParams[$branch]['pic_height']?>;
    <?php
    $owners = [];
    foreach ( $dataProvider->models as $coordinate ) {
        $workplace = Workplaces::findOne($coordinate->workplace_id);
        $title = '';
        if ($workplace->owner) {
            if ($workplace->owner[0]){
                $title = $workplace->owner[0]->snp . '<br>' . $workplace->owner[0]->job_title;
                $owners[$workplace->owner[0]->snp] = $workplace->owner[0]->snp;
            }
        }
        if (Yii::$app->user->can('it')) {
            $balloon = Html::a('<b>№' . $workplace->id . '</b> ' . $workplace->workplaces_title, ['/admin/workplaces/view', 'id' => $workplace->id]) . '<br>' . $title;
        } else {
            $balloon = '<b>№' . $workplace->id . '</b> ' . $workplace->workplaces_title . '<br>' . $title;
        }
        if ( ctype_space($coordinate->preset) || empty($coordinate->preset) )
            $preset = 'islands#blueDotIcon';
        else
            $preset = trim($coordinate->preset);
        if (ctype_space($coordinate->content) || empty($coordinate->content))
            $content = '';
        else
            $content = trim($coordinate->content);
    ?>
        points.push({y: <?= $coordinate->y ?>, x: <?= $coordinate->x ?>, balloonContent: '<?= $balloon ?>', preset: '<?= $preset ?>', content: '<?= $content ?>'});
    <?php } ?>

</script>
