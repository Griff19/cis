<?php
/**
 * @var $this \yii\web\View
 * @var $deviceSearch \backend\models\DevicesSearch
 * @var $deviceProvider \yii\data\ActiveDataProvider;
 */

use yii\grid\GridView;
?>

<div class="search-device">
<div style="float:left; width: 15%; position: fixed; " >
    <h4>Поиск устройств</h4>
    <?= $this->render('_form_search', ['model' => $deviceSearch])?>
</div>
<div style="float: right; width: 79%" >
    <?= GridView::widget([
        'dataProvider' => $deviceProvider,
        'columns' => [
            'id',
            'dt_title',
            'dev_comp',
            'brand',
            'model',
            'sn',
            'specification',
            'imei1',
            'parent_device_id',
            'device_note',
            ['attribute' => 'workplace_id',
                'header' => '№Р.М.',
            ],
            //'workplace_id',
            ['attribute' => 'wp_title',
                'header' => 'Наименование Р.M.',
                'value' => 'wp_title'
            ],
            ['attribute' => 'snp',
                'header' => 'Ответственный',
                'value' => 'snp'
            ]
        ]
    ])?>
</div>
</div>
