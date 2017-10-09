<?php
/**
 * Тестовый контроллер консольного приложения
 */
namespace console\controllers;

use yii\base\Controller;

class HelloController extends Controller
{
    function actionHello(){
        echo 'Hello world!';
    }
}