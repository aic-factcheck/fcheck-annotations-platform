<?php


namespace app\helpers;


class Entity
{
    public static function get($key){
        if(array_key_exists($key,\Yii::$app->params['entities'])){
            return \Yii::$app->params['entities'][$key];
        }
        return ['Todo'];
    }
}