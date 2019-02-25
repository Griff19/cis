<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>
<div class="site-index">
    <div align="center">
        <p class="lead"> Система учета и контроля орг-техники на предприятии "Алтайская Буренка"</p>
    </div>
    <div class="body-content">
        <div class="row"> <!-- 1 -->
            <div class="col-sm-3">
                <?= Html::a('Сотрудники',['employees/index'],['class'=>'btn btn-default'])?>
                <p>Список сотрудников.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Подразделения',['branches/index'],['class'=>'btn btn-default'])?>
                <p>Список подразделений.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Акты инвентаризации',['inventory-acts/index'],['class'=>'btn btn-default'])?>
                <p>Список актов инвентаризации</p>
            </div>
            <div class="col-sm-3">
                <?=  Html::a('Рабочие места', ['workplaces/index'], ['class'=>'btn btn-primary'])?>
                <p>Список рабочих мест.</p>
            </div>
        </div>
        <div class="row"> <!-- 2 -->
            <div class="col-sm-3">
                <?= Html::a('Пользователи',['user/index'],['class'=>'btn btn-default'])?>
                <p>Список пользователей.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Отделы/Кабинеты', ['rooms/index'], ['class'=>'btn btn-default'])?>
                <p>Список отделов.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Типы устройств',['devicetypes/index'],['class'=>'btn btn-default'])?>
                <p>Типы устройств.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Устройства',['devices/index'],['class'=>'btn btn-primary'])?>
                <p>Список устройств.</p>
            </div>
        </div>
        <div class="row"> <!-- 3 -->
            <div class="col-sm-3">
                <?= Html::a('Emails',['emails/index'],['class'=>'btn btn-primary'])?>
                <p>Электронные адреса.</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Отчет',['reports/aindex'],['class'=>'btn btn-default'])?>
                <p>Основной отчет по устройствам</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Основная страница поиска',['site/admin_workplace'],['class'=>'btn btn-default'])?>
                <p>Страница поиска данных по РМ и Сотрудникам</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Приходовать', ['devices/create?id_wp=1'], ['class' => 'btn btn-success'])?>
                <p>Приходовать на Склад ОИ</p>
            </div>
        </div>
        <div class="row"> <!-- 4 -->
            <div class="col-sm-3">
                <?= Html::a('Мобильные номера', ['cellnumbers/index'], ['class' => 'btn btn-default'])?>
                <p>Список мобильных номеров</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Контрагенты',['d-partners/index'],['class'=>'btn btn-default'])?>
                <p>Список Контрагентов</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Счета',['dt-invoices/index'],['class'=>'btn btn-default'])?>
                <p>Документы счета</p>
            </div>
			<div class="col-sm-3">
				<?= Html::a('Протоколы встреч',['meeting-minutes/index'],['class'=>'btn btn-default'])?>
				<p>Протоколы встреч</p>
			</div>
        </div>
        <div class="row"> <!-- 5 -->
            <div class="col-sm-3">
                <?= Html::a('Внутренние номера',['voipnumbers/index'],['class'=>'btn btn-default'])?>
                <p>Список внутренних номеров...</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Поиск устройств',['devices/find-device'],['class'=>'btn btn-default'])?>
                <p>Страница поиска устройств</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Акты списания',['dt-defsheets/index'],['class'=>'btn btn-default'])?>
                <p>Акты списания</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Заявки на оборудование',['dt-enquiries/index'],['class'=>'btn btn-warning'])?>
                <p>Заявки на оборудование</p>
            </div>
        </div>
        <div class="row"> <!-- 6 -->
            <div class="col-sm-3">
                <?= Html::a('Сеть',['netints/index'],['class'=>'btn btn-default'])?>
                <p>Сетевые интерфейсы</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Страница сотрудника IT',['site/employee-it'],['class'=>'btn btn-default'])?>
                <p>Основная рабочая страница сотрудника IT</p>
            </div>
            <div class="col-sm-3">
                <?= Html::a('Перемещения',['/tmp-moving'],['class'=>'btn btn-default'])?>
                <p>Виртуальные перемещения</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= Html::a('Счета за услуги связи', ['/phone-bill'], ['class'=>'btn btn-primary'])?>
                <p>Данные по затратам на услуги связи</p>
            </div>
        </div>
        <div>
            <?= "PHP " . phpversion() ?>
        </div>
    </div>
</div>
