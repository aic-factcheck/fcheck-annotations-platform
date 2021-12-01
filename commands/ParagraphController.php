<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\ParagraphQueue;
use yii\console\Controller;

class ParagraphController extends Controller
{

    /**
     * Updates Paragraph base of all claims & candidate paragraphs in db (up to some max. timestamp)
     * @param int $max_timestamp entries newer than this timestamp wont be affected
     * @return int Exit code
     */
    public function actionImport($max_timestamp = 2147483647)
    {
        $handle = fopen("samples_labelled.tsv", "r");
        fgets($handle);
        while (($line = fgets($handle)) !== false) {
            $l = explode("\t", $line);
            if (boolval($l[1])) ParagraphQueue::enqueue($l[0]);
        }
    }
}
