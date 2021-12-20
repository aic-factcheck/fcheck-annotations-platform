<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "paragraph_knowledge".
 *
 * @property int $paragraph
 * @property int $knowledge
 * @property string $search_term Klíčová slova (NULL při sem.search)
 * @property string $created_at
 *
 * @property Paragraph $paragraph0
 * @property Paragraph $knowledge0
 */
class ParagraphKnowledge extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paragraph_knowledge';
    }

    public static function fromDictionary($paragraph, $dictionary, $limit = null)
    {
        if ($limit == null) {
            self::fromDictionary2($paragraph, $dictionary);
            return;
        }
        $parTimestamp = strtotime($paragraph->article0->date);

        $timeProxComparator = function ($a, $b) use ($parTimestamp) {
            return ($parTimestamp - strtotime($a->date)) - ($parTimestamp - strtotime($b->date));
        };

        $articles = [];
        $used_dids = [];
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            if (in_array($sample["did"], $used_dids)) continue;
            $a = Article::fromSample($sample, false);
            $a->_interest_id = $sample["id"];
            $articles[] = $a;
            $used_dids[] = $sample["did"];
        }
        usort($articles, $timeProxComparator);
        $articles = array_slice($articles, 0, $limit);

        foreach ($articles as $article) {
            $article->save();
            foreach ($article->_pars_tmp as $par) {
                $par->article = $article->id;
                $par->save();
            }
            (new ParagraphKnowledge([
                "paragraph" => $paragraph->id,
                "knowledge" => Paragraph::findByCtkId($article->_interest_id)->id,
                "search_term" => ''
            ]))->save();
        }
    }

    public static function fromDictionary2($paragraph, $dictionary)
    {
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            Article::fromSample($sample);
            (new ParagraphKnowledge([
                "paragraph" => $paragraph->id,
                "knowledge" => Paragraph::findByCtkId($sample["id"])->id,
                "search_term" => array_key_exists("search_term", $sample) ? $sample["search_term"] : ''
            ]))->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paragraph', 'knowledge'], 'required'],
            [['paragraph', 'knowledge'], 'integer'],
            [['created_at'], 'safe'],
            [['search_term'], 'string', 'max' => 512],
            [['paragraph', 'knowledge', 'search_term'], 'unique', 'targetAttribute' => ['paragraph', 'knowledge', 'search_term']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['paragraph' => 'id']],
            [['knowledge'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['knowledge' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paragraph' => 'Paragraph',
            'knowledge' => 'Knowledge',
            'search_term' => 'Klíčová slova (NULL při sem.search)',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Paragraph0]].
     *
     * @return ActiveQuery
     */
    public function getParagraph0()
    {
        return $this->hasOne(Paragraph::class, ['id' => 'paragraph']);
    }

    /**
     * Gets query for [[Knowledge0]].
     *
     * @return ActiveQuery
     */
    public function getKnowledge0()
    {
        return $this->hasOne(Paragraph::class, ['id' => 'knowledge']);
    }
}
