<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\CtkApi;
use app\models\Claim;
use app\models\ClaimKnowledge;
use app\models\Paragraph;
use app\models\ParagraphKnowledge;
use app\models\User;
use Exception;
use yii\console\Controller;
use yii\console\ExitCode;

class KnowledgeController extends Controller
{

    /**
     * Updates knowledge base of all claims & candidate paragraphs in db (up to some max. timestamp)
     * @param int $max_timestamp entries newer than this timestamp wont be affected
     * @return int Exit code
     */
    public function actionImport($max_timestamp = 2147483647)
    {
        $maxDate = date("Y-m-d H:m:s", $max_timestamp);
        $ctkApi = new CtkApi();
        $C = Paragraph::find()->where(['<=', 'updated_at', $max_timestamp])->andWhere(['IS NOT', 'candidate_of', null])->all();
        ParagraphKnowledge::deleteAll(['<=', 'created_at', $maxDate]);
        $problematic = [];
        foreach ($C as $paragraph) {
            try {
                echo "\nprocessing paragraph {$paragraph->id}: " . $paragraph->article . '_' . $paragraph->rank;
                $response = $ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank);
                ParagraphKnowledge::fromDictionary($paragraph, $response);
                $paragraph->ners = $response['ners'];
                $paragraph->save();
            } catch (Exception $e) {
                $problematic[] = $paragraph->id;
                echo "\nEXCEPTION ARISED!:\n" . $e->getTraceAsString();
            }
        }
        echo "\n PROBLEMATIC PARAGRAPH IDs: (" . implode(",", $problematic) . ")";
        $problematic = [];
        $C = Claim::find()->where(['<=', 'updated_at', $max_timestamp])->andWhere(['IS NOT', 'mutation_type', null])->all();
        ClaimKnowledge::deleteAll(['<=', 'created_at', $maxDate]);
        foreach ($C as $claim) {
            try {
                echo "\nprocessing claim {$claim->id}: {$claim->claim}";
                $par = $claim->paragraph0;
                $response = $ctkApi->getDictionary($par->article . '_' . $par->rank, ['q' => $claim->claim]);
                ClaimKnowledge::fromDictionary($claim, $response);
                $claim->ners = $response['ners'];
                $claim->save();
            } catch (Exception $e) {
                $problematic[] = $claim->id;
                echo "\nEXCEPTION ARISED!:\n" . $e->getTraceAsString();
            }
        }
        echo "\n PROBLEMATIC CLAIM IDs: (" . implode(",", $problematic) . ")";
        return ExitCode::OK;
    }

}
