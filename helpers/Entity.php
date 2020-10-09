<?php


namespace app\helpers;


class Dictionary
{
    public static function get($key){
        if(array_key_exists($key,\Yii::$app->params['dictionary'])){
            return \Yii::$app->params['dictionary'][$key];
        }
        return 'Todo';
    }
}