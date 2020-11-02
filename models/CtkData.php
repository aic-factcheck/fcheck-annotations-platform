<?php


namespace app\models;


use app\helpers\Helper;
use yii\db\ActiveRecord;

abstract class CtkData extends ActiveRecord
{
    public function get($attr){
        return Helper::presentText($this->$attr);
    }
}