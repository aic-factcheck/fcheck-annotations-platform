<?php


namespace app\helpers;


use app\models\Paragraph;
use Yii;
use yii\helpers\Html;

class Helper
{
    const SPACE_BEFORE = [",", ".", ":", ";", ")", "]", "!", "?"];
    const SPACE_AFTER = ["[", "("];
    const SWAP = ["`` " => "„", " ''" => "“"];
    public static $entities = [];
    private static $detokenizationMap = null;
    private static $entityMarks = [];
    private static $printedItems = [];

    public static function setEntities($entities)
    {
        foreach ($entities as $entity) {
            if(strlen($entity))
            self::$entityMarks[$entity] = Html::tag('mark', $entity);
        }
        self::$entities = $entities;
    }

    /**
     * @param $knowledge Paragraph
     * @return string
     */
    public static function dictionaryItem($knowledge)
    {
        $content = "";
        $article = $knowledge->article0;
        foreach ($article->paragraphs as $paragraph) {
            if ($paragraph->rank == 0) continue;
            $content .= Html::tag('p', $paragraph->get('text'), ['class' => $paragraph->id == $knowledge->id ? 'p-active' : "p-$knowledge->id"]);
        }
        $h6Text = $article->get('title') . ' ' .
            Html::tag('small', Yii::$app->formatter->asDatetime($article->date),['class'=>'badge badge-secondary ']);
        return Html::tag("h6", self::expandLink("+ " . $h6Text, ".$knowledge->id", " − " . $h6Text)) .
            Html::tag("div", $content . self::expandLink("Více", ".p-$knowledge->id", "Méně"), ["class" => $knowledge->id]);
    }

    public static function expandLink($text, $target, $alt = "Skrýt")
    {
        return Html::a($text, "#", ['data' => ['show' => $target, 'alt' => $alt]]);
    }

    public static function presentText($text)
    {
        return self::detokenize(self::highlightEntities($text));
    }

    public static function detokenize($string)
    {
        if (self::$detokenizationMap == null) {
            self::$detokenizationMap = [];
            foreach (self::SPACE_BEFORE as $item) {
                self::$detokenizationMap[" " . $item] = $item;
            }
            foreach (self::SPACE_AFTER as $item) {
                self::$detokenizationMap[$item . " "] = $item;
            }
            foreach (self::SWAP as $key => $value) {
                self::$detokenizationMap[$key] = $value;
            }
        }
        return strtr($string, self::$detokenizationMap);
    }

    public static function highlightEntities($text)
    {
        return strtr($text, self::$entityMarks);
    }
}