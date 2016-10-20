<?php
/**
 * @var $workplaceSearch \backend\models\AdminWorkplacesSearch
 * @var $employeeSearch \backend\models\AdminEmployeesSearch
 * @var $workplaceProvider \yii\data\ActiveDataProvider
 * @var $employeeProvider \yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use backend\models\AdminWorkplaces;
use backend\models\AdminEmployees;
use backend\models\Netints;
use yii\bootstrap\Tabs;


?>

<h1> Данные по рабочему месту </h1>

<div>
<div class="row">
    <div class="col-xs-12 col-md-5 col-md-push-7">
        <!--<h4> Поиск по рабочему месту </h4>-->
        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Поиск по рабочему месту',
                    'content' => $this->render('../workplaces/_search', ['model' => $workplaceSearch]),
                    'active' =>  $tab == 1 ? true : false

                ],
                [
                    'label' => 'Поиск по сотруднику',
                    'content' => $this->render('../employees/_search', ['model' => $employeeSearch]),
                    'active' =>  $tab == 2 ? true : false

                    //'headerOptions' => [...],
                    // 'options' => ['id' => 'myveryownID'],
                ],
            ],
        ]);
        ?>

        <?php //echo $this->render('../workplaces/_search', ['model' => $workplaceSearch]); ?>
        <?php

        ?>
    </div>
    <?php if ($tab == 1) {?>
        <div id = "find-workplace" class="col-xs-12 col-md-7 col-md-pull-5">
        <?php
        //var_dump($workplaceProvider->models);
        //var_dump(Yii::$app->request->queryParams);
        if ($workplaceProvider->models)
            $model = $workplaceProvider->models[0];
        else {
            echo '<h3> По вашему запросу нет данных...</h3>';
            echo '</div></div>';
            return;
        }
        /* @var $model \backend\models\AdminWorkplaces */

        ?>

        <h3> <?= $model->branch_title ?>, <?= $model->id ?> <?= $model->status == 1 ? '(основное)' : '' ?> </h3>
        <table>
            <tr>
                <td> Помещение:</td>
                <td> &emsp;<?= $model->room_title ?> </td>
            </tr>
            <tr>
                <td> Описание:</td>
                <td> &emsp;<?= $model->workplaces_title ?> </td>
            </tr>
            <tr>
                <td> VoIP-номер:</td>
                <td> &emsp;<?= $model->voip_number ?> </td>
            </tr>
            <tr>
                <td> ФИО:</td>
                <td> &emsp;<?= $model->snp ?> </td>
            </tr>
            <tr>
                <td> Моб.номер:</td>
                <td> &emsp;<?= Yii::$app->formatter->asPhone($model->cellnumber) ?> </td>
            </tr>
            <tr>
                <td> Адрес эл.почты:</td>
                <td> &emsp;<?= $model->email ?> </td>
            </tr>
            <tr>
                <td> Должность:</td>
                <td> &emsp;<?= $model->jobtitle ?> </td>
            </tr>
        </table>

        <h3> Данные сети </h3>

        <?php
        $netintsProvider = AdminWorkplaces::netintsProvider($model->id);
        //var_dump($netintsProvider->models);
        $old_parent_type_title = '';
        $old_netdevice = '';
        $table = false;
        foreach ($netintsProvider->models as $netModel) {
            $parent_type_title = $netModel['parent_type_title'] . ' ' . $netModel['devices_parent_device_id'];
            if ($parent_type_title != $old_parent_type_title) {
                if ($table) {echo  '</table>'; $table = false;}
                echo '<h4>' . $parent_type_title . '</h4>';
                $old_parent_type_title = $parent_type_title;
                if (!$table) {
                    echo '<table class="table table-bordered table-condensed">';
                    $table = true;
                }
            }
            $netdevice = $netModel['device_type_title'] . ' ' . $netModel['devices_id'];
            if ($old_netdevice != $netdevice) {
                if (empty($netModel['parent_type_title'])) {
                    if ($table) {echo  '</table>'; $table = false;}
                    echo '<h4>' . $netdevice . '</h4>';
                    if (!$table) {
                        echo '<table class="table table-bordered table-condensed">';
                        $table = true;
                    }
                }
                else {
                    echo '<tr><td colspan="7"><b>' . $netdevice . '</b></td></tr>';
                }
                $old_netdevice = $netdevice;

            }

            echo '<tr><td>' . Netints::arrTypes()[$netModel['netints_type']] . '</td><td>'
                . $netModel['netints_id'] . '</td><td> ['
                . $netModel['netints_mac'] . '] </td><td>'
                . $netModel['netints_domain_name'] . '</td><td>'
                . $netModel['netints_ipaddr'] . '</td><td>'
                . $netModel['netints_port_count'] . '</td></tr>';
        }
        if ($table) {echo  '</table>'; $table = false;}
        ?>
    </div>
    <?php } elseif ($tab == 2) {?>
        <div id = "find-employee" class="col-xs-12 col-md-7 col-md-pull-5">
            <?php
            //var_dump($employeeProvider->models[0]);

            if ($employeeProvider->models)
                $model = $employeeProvider->models[0];
            else {
                echo '<h3> По вашему запросу нет данных...</h3>';
                echo '</div></div></div>';
                return;
            }

            echo '<h3>'.$model['fio'].'</h3>';
            echo 'Мобильный: ' . Yii::$app->formatter->asPhone($model['cell_number']) . '<br>'
                . 'Email: ' . $model['email'] . '<br>'
                . 'Должность: ' . $model->job_title . '<br>';

            $wpProvider = AdminEmployees::workplacesProvider($model->id);
            $old_branch = '';
            $old_device = '';
            $table = false;
            foreach ($wpProvider->models as $wpModel){
                $branch = $wpModel['branch_title'] . ' ' . $wpModel['id'];
                if ($branch != $old_branch) {
                    if ($table) {echo  '</table>'; $table = false;}
                    echo '<h3><a href="admin_workplace?tab=1&AdminWorkplacesSearch[id]='. $wpModel['id'] .'">' . $branch . ($wpModel['status'] == 1 ? ' (основное)' : '') . '</a></h3>';

                    echo 'Помещение: ' . $wpModel['room_title'] . '<br>';
                    echo 'Описание: '. $wpModel['workplaces_title'] . '<br>';
                    echo 'Вн.номер: ' . $wpModel['voip_number'] . '<br>';

                    $old_branch = $branch;
                }
                $device = $wpModel['device_type_title'] . ' ' . $wpModel['devices_id'];
                if ($old_device != $device) {
                    if ($table) {echo  '</table>'; $table = false;}
                    echo '<b>' . $device . '</b>';

                    $old_device = $device;
                    if (!$table)
                        echo '<table class="table table-bordered table-condensed">';
                    $table = true;
                }

                echo empty($wpModel['sub_type_title']) ? '' : '<tr><td colspan="7"><b>' . $wpModel['sub_type_title'] . '</b></td></tr>';

                echo '<tr><td>' . Netints::arrTypes()[$wpModel['netints_type']] . '</td><td>'
                    . $wpModel['netints_id'] . '</td><td>'
                    . $wpModel['mac'] . '</td><td>'
                    . $wpModel['domain_name'] . '</td><td>'
                    . $wpModel['ip'] . '</td><td>'
                    . $wpModel['ports'] . '</td></tr>';

            }
            if ($table) {echo  '</table>'; $table = false;}

            ?>

        </div>
    <?php }?>
</div>
<?php if ($tab == 1) {?>
    <div id = "list-devices" class="row">
    <h3> Список устройств </h3>

    <?php
    $deviceProvider = AdminWorkplaces::devicesProvider($model->id);
    $s = 0;
    if ($deviceProvider)
        echo GridView::widget([
            'dataProvider' => $deviceProvider,

            'columns' => [
                ['class' => \yii\grid\SerialColumn::className()],
                ['attribute' => 'device_type_title',
                    'header' => 'Тип',
                    'value' => function ($device) {
                        if ($device['parent_devices_id'])
                            return '[' . $device['parent_devices_id'] . '] ' . $device['device_type_title'];
                        else
                            return $device['device_type_title'];
                    }
                ],
                ['attribute' => 'devices_brand',
                    'header' => 'Бренд'
                ],
                ['attribute' => 'devices_model',
                    'header' => 'Модель'
                ],
                ['attribute' => 'devices_sn',
                    'header' => 'SN'
                ],
                ['attribute' => 'devices_id',
                    'header' => 'ID'
                ],
                ['attribute' => 'devices_specification',
                    'header' => 'Спецификация'
                ],
                ['attribute' => 'devices_device_note',
                    'header' => 'Заметка'
                ]
            ]
        ]) ?>
</div>
<?php } elseif ($tab == 2) {?>
    <div>
    </div>
    <?php } ?>
</div>

