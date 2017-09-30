<?php
/**
 * Страница глобальной карты
 */
use backend\models\Workplaces;
use backend\models\Coordinate;
use frontend\assets\MapAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

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
    .field-coordinatesearch-snp, #w0 {
        display: inline-flex;
    }
    #w0 {
        display: inline-flex;
        height: 33px;
    }
    #coordinatesearch-snp {
        margin-top: -5px;
    }
</style>
<script type="text/javascript">
    var points = [];
    var floor = <?= $floor ?>;
    <?php
    $owners = [];
    foreach ( $dataProvider->models as $coordinate) {
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
    ?>
        points.push({y: <?= $coordinate->y ?>, x: <?= $coordinate->x ?>, balloonContent: '<?= $balloon ?>', preset: 'islands#blueDotIcon'})
    <?php } ?>
</script>

<?= Html::a('1 Этаж', ['map', 'floor' => 1], ['class' => 'btn btn-default', 'style' => $floor == 1 ? 'font-weight: 600' : '']) ?>
<?= Html::a('2 Этаж', ['map', 'floor' => 2], ['class' => 'btn btn-default', 'style' => $floor == 2 ? 'font-weight: 600' : '']) ?>

<?php $form = ActiveForm::begin([
	'action' => ['site/map?floor='.$floor],
	'method' => 'get',

]); ?>
<?= $form->field($search, 'snp')->dropDownList(ArrayHelper::map(Coordinate::getOwners($floor), 'snp', 'snp'), [
        'prompt' => 'Пустой фильтр...'
]) ?>
<?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary', 'style' => 'margin:-5px 4px 4px 3px;']) ?>
<?php ActiveForm::end(); ?>

<div id = "map" class="map"></div>