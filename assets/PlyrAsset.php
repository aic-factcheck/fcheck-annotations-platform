<?php

namespace app\assets;

use yii\web\JqueryAsset;

class PlyrAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@npm/plyr/dist';

    public $js = [
        "plyr.min.js"
    ];
    public $css = [
        "plyr.css"
    ];
    public $depends = [
    ];
}