<?php
/**
 * Страница глобальной карты
 */
use frontend\assets\MapAsset;
use yii\helpers\Html;
use backend\models\Workplaces;
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
</style>

<?= Html::a('1 Этаж', ['map', 'floor' => 1], ['class' => 'btn btn-default', 'style' => $floor == 1 ? 'font-weight: 600' : '']) ?>
<?= Html::a('2 Этаж', ['map', 'floor' => 2], ['class' => 'btn btn-default', 'style' => $floor == 2 ? 'font-weight: 600' : '']) ?>

<?php $form = ActiveForm::begin([
	'action' => ['site/map?floor='.$floor],
	'method' => 'get',
]); ?>
<?= $form->field($search, 'snp') ?>
<?php ActiveForm::end(); ?>

<div id = "map" class="map"></div>

<script type="text/javascript">
    var points = [];

    var floor = <?= $floor ?>;
    <?php foreach ( $dataProvider->models as $coordinate) {
        $workplace = Workplaces::findOne($coordinate->workplace_id);
        $title = '';
        if ($workplace->owner) {
            if ($workplace->owner[0]){
                $title = $workplace->owner[0]->snp . '<br>' . $workplace->owner[0]->job_title;
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