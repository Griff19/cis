<?php
use backend\models\DeviceType;
//use backend\models\Devices;
use backend\models\Workplaces;
use backend\models\Branches;
use backend\models\Rooms;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Devices */
/* @var $form yii\widgets\ActiveForm */

$this->registerAssetBundle('backend\assets\ValidDeviceAsset');
//$this->registerJs('Valid();');
?>

<div class="devices-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        //'validateOnBlur' => true,
        'validationUrl' => Url::toRoute('devices/validation?scenario=' . $model->scenario)
    ]);
    ?>
    <?php
    if ($mode !== 'update') {
        if ($id_wp == 0) { ?>
            <?= $form->field($model, 'branch_id')->dropDownList(
                ArrayHelper::map(Branches::find()->all(), 'id', 'branch_title'),
                ['tabindex' => 1,
                    'prompt' => 'Выберите подраздление...',
                    'onchange' => '$.post("/admin/rooms/list?id=' . '"+$(this).val(), function(data) {
                        $("select#devices-room_id").html(data);
                        $("select#devices-workplace_id").html(\'<option value="0"> - </option>\');
                        $(".field-devices-room_id").show("fast");
                    });'
                ])
            ?>
            <?= $form->field($model, 'room_id')->dropDownList(
                ArrayHelper::map(Rooms::find()->orderBy('room_title')->all(), 'id', 'room_title'),
                ['tabindex' => 2,
                    'prompt' => 'Выберите отдел/кабинет...',
                    'onchange' => '$.post("/admin/workplaces/list?id=' . '"+$(this).val(), function(data) {
                        $("select#devices-workplace_id").html(data);
                        $(".field-devices-workplace_id").show("fast");
                    });'
                ])
            ?>

            <?= $form->field($model, 'workplace_id')->dropDownList(
                ArrayHelper::map(Workplaces::find()->all(), 'id', function ($model_wp) {
                    $snp = '';
                    if ($model_wp->owner) $snp = $model_wp->owner[0]['snp'];
                    return '"' . $model_wp->workplaces_title . '" ' . $snp;
                }),
                ['tabindex' => 3,
                    'prompt' => 'Выберите рабочее место...'])
            ?>
        <?php } else {
            $model_wp = Workplaces::findOne($id_wp);
            echo '<h4>на Рабочее место №' . $id_wp .' ('. $model_wp->workplaces_title.')</h4>';
        }
        ?>
        <?= $form->field($model, 'type_id')->dropDownList(
            ArrayHelper::map(DeviceType::arrDevType(), 'type_id', 'title'),
            ['tabindex' => 4,
                'prompt' => 'Выберите тип устройства...',
                'onchange' => '$.post("/admin/devices/set-type-id?type_id="+$(this).val(), function(data) {
                    if (data) {
                        $("#devices-brand").val("");
                        $("#devices-model").val("");
                        $("#devices-specification").val("");
                    }
                });'
            ])
        ?>
    <?php } ?>

    <?= $form->field($model, 'chekMode')->widget('\kartik\checkbox\CheckboxX', [
        'autoLabel' => true,
        'pluginOptions' => ['threeState' => false],
        'pluginEvents' => [
            'change' => 'function() {
                if ($(this).val() == 1) {
                    $(".field-devices-sn").hide("fast")
                } else {
                    $(".field-devices-sn").show("fast")
                }
            }',
        ],
        'options' => ['tabindex' => 5,]
    ])->label(false) ?>

    <?= $form->field($model, 'sn')->textInput()->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => $model::arraySns(),
            'minLength' => 3,
            'select' => 'Valid();'
        ],
        'options' => ['class' => 'form-control', 'tabindex' => 1001,]
    ]) ?>

    <?= $form->field($model, 'device_mac')->textInput(['tabindex' => 1002]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true])->widget(
        AutoComplete::className(), [
        'clientOptions' => ['source' => Url::to(['devices/get-brands']),
            'autoFill' => true,
            'create' => new JsExpression('function(event, ui) {
                $("#devices-brand").autocomplete("instance")._renderItem = function(ul, item) {
                    if (item.sort == 0){
                        return $("<li></li>").data("item.autocomplete", item).append("<b>"+item.label+"</b>").appendTo(ul);
                    } else {
                        return $("<li></li>").data("item.autocomplete", item).append(item.label).appendTo(ul);
                    }
                }
            }'),
        ],
        'options' => ['class' => 'form-control', 'tabindex' => 1003]
        ]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true])->widget(
        AutoComplete::className(), [
        'clientOptions' => ['source' => Url::to(['devices/get-models']),
            'autoFill' => true,
            'select' => new JsExpression('function (event, ui){
                $.post("/admin/devices/set-specification-auto?model="+encodeURIComponent(ui.item.value), function(data){
                    $("#devices-specification").val(data);
                })
            }')
        ],
        'options' => ['class' => 'form-control', 'tabindex' => 1004]
    ]) ?>

    <?= $form->field($model, 'imei1')->textInput(['maxlength' => true])->widget(
        AutoComplete::className(), [
        'clientOptions' => ['source' => $model::arrayImei1()],
        'options' => ['class' => 'form-control', 'tabindex' => 1005]
    ]) ?>

    <?= $form->field($model, 'imei2')->textInput(['maxlength' => true])->widget(
        AutoComplete::className(), [
        'clientOptions' => ['source' => $model::arrayImei2()],
        'options' => ['class' => 'form-control', 'tabindex' => 1006]
    ]) ?>

    <?= $form->field($model, 'specification')->textInput(['maxlength' => true])->widget(
        AutoComplete::className(), [
        'clientOptions' => ['source' => Url::to(['devices/get-specifications'])],
        'options' => ['class' => 'form-control', 'tabindex' => 1007]
    ]) ?>
    <?= $form->field($model, 'device_note')->textInput(['maxlength' => true, 'tabindex' => 1008]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'tabindex' => 1009]) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>

<?php
    $this->registerJs('
        $(".field-devices-room_id").hide();
        $(".field-devices-workplace_id").hide();
    ');
?>
