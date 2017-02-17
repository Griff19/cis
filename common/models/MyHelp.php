<?php
namespace common\models;

/**
 * Class MyHelp
 * @property string strArray
 * @package common\models
 */
class MyHelp
{
    public static function getItem($array, $index = 0)
    {
        if (gettype($array) == 'array') {
            $a = 0;
            $res = null;
            foreach ($array as $item) {
                $a++;
                if ($a == $index)
                    $res = $item;
            }

            return $res;
        }
        else {
            return $array;
        }
    }
}
