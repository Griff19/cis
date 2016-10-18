<?php
/**
 * @var $this \yii\web\View
 *
 */

//use yii\grid\GridView;
use backend\models\DeviceType;
//use common\widgets\TableCollapse;

$this->title = 'Акт инвентаризации';
$this->params['breadcrumbs'][] = ['label' => 'Рабочее место', 'url' => ['workplaces/view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<span style="float:left">Акт инвентаризации рабочего места <b>№<?= $id?></b> от <b><?= date('d.m.Y')?></b></span>
<span style="float:right">Инвентаризацию провел ____________________/ <?= Yii::$app->user->identity->username ?> /</span>
<br>
<?php

//echo GridView::widget([
//    'dataProvider' => $provider,
//
//    'columns' => [
//        'sort',
//        'id',
//        'type_id',
//        'device_note',
//        'workplace_id',
//        'brand',
//        'model',
//        'sn',
//        'specification',
//        'parent_device_id'
//    ]
//]);
?>

<table class="table table-bordered table-hover" style="padding: 0">
    <thead>
        <tr>
            <th>ИдОбщ.</th><th>Ид</th><th>Тип</th><th>Описание</th><th>Раб.место</th><th>Бренд</th><th>Модель</th><th>С№</th><th>Спец.</th><th>Род.ид</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $parentsId = [];
    foreach ($provider->models as $model){
        //var_dump($model['id']);
        if ($model['parent_device_id']) $parentsId[] = $model['parent_device_id'];
    }
    //var_dump($parentsId);

    $oldParent = 0; $parent = 0;
    $n = 0; $cn = 0; $t = false;
    foreach ($provider->models as $model){
        $parent = $model['parent_device_id'] ? $model['parent_device_id'] : 0;
        //var_dump($parent);
        if ($parent > 0) { //если существуют подчиненные устройства (комплектующие)
            if ($oldParent != $parent){ //и тут начинается их список - создаем скрытую таблицу
                if (!$t) { //если тег таблицы не открыт
                    echo '<tr><td colspan="10" class="hiddenRow" style="padding: 0px">';
                    echo '<div class="accordion-body collapse rows' . $cn . '" id="accordion' . $cn . '">';
                    echo '<table class="table table-condensed">';
                    $t = true; //указываем что тег таблицы открыт
                } else { //если тег таблицы открыт и подчиненные устройства закончились
                    echo '</table></div></td></tr>';
                    $t = false; //закрываем тег таблицы
                }
            }
            echo '<tr class="bg-info">'; //скрытые строки выделяем цветом
            foreach ($model as $key => $item) {
                if ($key == 'type_id') echo '<td>' . DeviceType::findOne($item)->title . '</td>';
                else echo '<td>' . $item . '</td>';
            }
            echo '</tr>';
        }
        else
        {
            if ($t) {
                echo '</table></div></td></tr>';
                $t = false;
            }
            $n++;
            if (in_array($model['id'], $parentsId)) { //если устройство в текущей строке относится к родительским то
                //указываем что эта строка управляет видимостью скрытой таблицы
                echo '<tr class = "accordion-toggle success" data-toggle="collapse" data-target=".rows' . $n . '">';
            } else {
                echo '<tr>';
            }

            foreach ($model as $key => $item) {
                if ($key == 'type_id') echo '<td>' . DeviceType::findOne($item)->title . '</td>';
                else echo '<td>' . $item . '</td>';
            }
            echo "</tr>";
            $cn = $n;
        }
        $oldParent = $parent;
    }
    ?>
    </tbody>
</table>




