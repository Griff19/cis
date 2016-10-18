<?php
namespace common\models;

use Yii;

/**
 * Class MyHelp
 * @property string strArray
 * @package common\models
 */
class MyHelp
{
    public $strArray = '';

    /**
     * @param $arr
     * @return string
     */
    public function arrayToString($arr){
        var_dump($arr);
        echo 'тип1: ' . gettype($arr);
        if (gettype($arr) == "array")
            foreach ($arr as $item){
                echo 'тип2: ' . gettype($arr);
                if (gettype($item) == "array")
                    $this->arrayToString($item);
                else $this->strArray .= ' ' . $item;
            }
        else {
            $this->strArray .= ' ' . $arr;
            echo 'Знач: ' . $this->strArray;
            return $this->strArray;
        }
    }

}
