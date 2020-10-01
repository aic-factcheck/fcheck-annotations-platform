<?php

namespace app\assets;

use yii\web\JqueryAsset;

class ParallaxAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@npm/jquery-parallax.js';

    public $js = [
        "parallax.min.js"
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}