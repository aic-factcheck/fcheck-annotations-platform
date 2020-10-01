<?php


namespace app\assets;


use Yii;
use yii\web\AssetBundle;

class TinyMCEAsset extends AssetBundle
{
    public $sourcePath = null;

    public function init()
    {
        $this->js = ['//cdn.tiny.cloud/1/' . Yii::$app->params['tinymce']['apiKey'] . '/tinymce/5/tinymce.min.js',];
        parent::init();
    }
}