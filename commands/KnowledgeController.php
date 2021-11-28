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
use app\models\ConditionKnowledge;
use app\models\Label;
use app\models\Paragraph;
use app\models\ParagraphKnowledge;
use app\models\Tweet;
use app\models\TweetKnowledge;
use Exception;
use PDOException;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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

    public function actionHideConflicts()
    {
        $labels = Label::find()->andWhere(['not', ['condition' => null]])->all();
        foreach ($labels as $label) {
            if ($label == null) continue;
            $conflicts = Label::find()->andWhere(['claim' => $label->claim])->andWhere(['condition' => null])->andWhere(['not', ['label' => 'NOT ENOUGH INFO']])->count();
            if ($conflicts > 0) {
                $label->note = '[COND][' . $label->deleted . '] Podmíněná anotace je redudantní nebo konfliktní' . (!empty($label->note) ? '; ' . $label->note : '');
                $label->deleted = 1;
                $label->save();
            }
        }
    }

    public function actionFetchConditional()
    {
        $ctkApi = new CtkApi();
        $labels = Label::find()->andWhere(['not', ['condition' => null]])->all();
        ConditionKnowledge::deleteAll();
        foreach ($labels as $label) {
            if ($label->claim0 != null) {
                $paragraph = $label->claim0->paragraph0;
                $dictionary = $ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ['q' => $label->condition, "nerlimit" => 2, "k" => 2, "npts" => 2, "older" => 1]);
                ConditionKnowledge::fromDictionary($label, $dictionary);
            }
        }
    }

    public function actionFetchByTweet()
    {
        $conf = [
            "k" => 3,
            "nerlimit" => 256,
            "prek" => 1024,
            "npts" => 128,
            'niter' => 12,
            "notitles" => 0,
            "randompts" => 0,
            "k_latest" => 5
        ];
        $ctkApi = new CtkApi();
        $tweets = Tweet::find()->orderBy(new Expression('rand()'))->limit(15000)->all();
        foreach ($tweets as $tweet) {
            try {
                //TweetKnowledge::deleteAll(['tweet' => $tweet->id]);
                $paragraph = Paragraph::nearest($tweet->created_at);
                $dictionary = $ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ArrayHelper::merge(['q' => $tweet->text],$conf));
                TweetKnowledge::fromDictionary($tweet, $dictionary, $conf["k_latest"]);
            } catch (Exception $e) {
                echo $e->getMessage().'\n';
                echo $e->getTraceAsString();
            }
        }
    }
}
