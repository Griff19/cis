<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 10.07.2016
 * Time: 15:01
 */

namespace common\models;

use Yii;

class FtpWork
{

    /**
     * Скачиваем файл с сервера
     * @param string $server_file
     * @param string $local_file
     * @return bool
     */
    public function download($server_file, $local_file){

        $conn_id = ftp_connect(Yii::$app->params['ftp']['server']);
        $login_result = ftp_login($conn_id, Yii::$app->params['ftp']['user'], Yii::$app->params['ftp']['pass']);
        //Logs::add('Результат аторизации на ftp: ' . $login_result);
        ftp_pasv($conn_id, true);

        $dir = dirname($server_file); //Получаем имя каталога
        $filename = basename($server_file); //Получаем имя файла
        if (!empty($dir)){
            ftp_chdir($conn_id, $dir); //если в имени файла указан каталог то выбираем его
        }

        if (ftp_get($conn_id, $local_file, $filename, FTP_BINARY)){
            ftp_close($conn_id);
            //Logs::add('Файл скачан ' . $server_file . ' как ' . $local_file);
            return true;
        } else {
            ftp_close($conn_id);
            return false;
        }
    }

    /**
     * @param $server_catalog
     * @param $local_catalog
     * @return bool
     */
    public function downloadAll($server_catalog, $local_catalog){
        $conn_id = ftp_connect(Yii::$app->params['ftp']['server']);
        $login_result = ftp_login($conn_id, Yii::$app->params['ftp']['user'], Yii::$app->params['ftp']['pass']);
        //Logs::add('Результат аторизации на ftp: ' . $login_result);
        ftp_pasv($conn_id, true);

        //echo $local_catalog . '<br>';
        if (!empty($dir)){
            ftp_chdir($conn_id, $dir); //выделяем каталог из полученного имени и выбераем его
        }

        $list_files = ftp_nlist($conn_id, $server_catalog);
        foreach ($list_files as $file_name) {
            if ($file_name == '.' || $file_name == '..') continue;
            //echo $file_name . '<br>'; continue;
            if (pathinfo($file_name, PATHINFO_EXTENSION) != 'xlsx') continue;

            if (ftp_get($conn_id, $local_catalog.'/'.$file_name, $server_catalog.'/'.$file_name, FTP_BINARY)){
                //Logs::add('Файл скачан ' . $server_catalog.'/'.$file_name . ' как ' . $local_catalog.'/'.$file_name);
            }
        }

        ftp_close($conn_id);
        //die;
        return true;
    }

    /**
     * Закачиваем файл на сервер
     * @param $local_file
     * @param $server_file
     * @return bool
     */
    public function upload($local_file, $server_file){
        $conn_id = ftp_connect(Yii::$app->params['ftp']['server']);
        $login_result = ftp_login($conn_id, Yii::$app->params['ftp']['user'], Yii::$app->params['ftp']['pass']);
        ftp_pasv($conn_id, true);

        $dir = dirname($server_file);
        $filename = basename($server_file);
        if (!empty($dir)){
            ftp_chdir($conn_id, $dir);
        }

        //Logs::add('Файл загружен на ftp-сервер: ' . $server_file . ' <- ' . $local_file);

        if (ftp_put($conn_id, $filename, $local_file, FTP_BINARY)){
            ftp_close($conn_id);
            return true;
        } else {
            ftp_close($conn_id);
            return false;
        }
    }
}