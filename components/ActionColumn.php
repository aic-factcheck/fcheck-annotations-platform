<?php

namespace app\components;


use Yii;

/**
 * Class ActionColumn
 * @package app\components
 * @author Herbert Ullrich <ja@bertik.net>
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
    public $headerOptions = ['class'=>'action-column'];
}