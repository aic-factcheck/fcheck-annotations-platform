<?php


namespace app\helpers;


use yii\helpers\Html;

class Helper
{
    const SPACE_BEFORE = [",", ".", ":", ";", ")", "]"];
    const SPACE_AFTER = ["[", "("];
    const SWAP = ["`` " => "„", " ''" => "“"];
    private static $detokenizationMap = null;
    private static $entities = [];
    private static $entityMarks = [];
    private static $printedItems = [];

    public static function setEntities($entities)
    {
        foreach ($entities as $entity) {
            self::$entityMarks[$entity] = Html::tag('mark', $entity);
        }
        self::$entities = $entities;
    }

    public static function dictionaryItem($item = [])
    {
        if (array_key_exists($item['id'], self::$printedItems)) return '';
        self::$printedItems[$item['id']] = true;
        $content = "";
        foreach ($item['blocks'] as $key => $block) {
            $content .= Html::tag('p', self::presentText($block), ['class' => $key == $item['id'] ? 'p-active' : "p-$item[id]"]);
        }
        $title = self::presentText($item["title"]);
        return Html::tag("h6", self::expandLink("+ " . $title, ".$item[id]", " − " . $title)) .
            Html::tag("div", $content . self::expandLink("Více", ".p-$item[id]", "Méně"), ["class" => $item["id"]]);
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

    public static function expandLink($text, $target, $alt = "Skrýt")
    {
        return Html::a($text, "#", ['data' => ['show' => $target, 'alt' => $alt]]);
    }
}