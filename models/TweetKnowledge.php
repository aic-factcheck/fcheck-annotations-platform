<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tweet_knowledge".
 *
 * @property int $tweet
 * @property int $knowledge
 * @property string $created_at
 *
 * @property Tweet $tweet0
 * @property Paragraph $knowledge0
 */
class TweetKnowledge extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tweet_knowledge';
    }

    public static function fromDictionary($tweet, $dictionary, $limit = null)
    {
        $tweetTimestamp = strtotime($tweet->created_at);

        $timeProxComparator = function ($a, $b) use ($tweetTimestamp) {
            return ($tweetTimestamp - strtotime($a->date)) - ($tweetTimestamp - strtotime($b->date));
        };

        $articles = [];
        $used_dids = [];
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            if(in_array($sample["did"],$used_dids)) continue;
            $a = Article::fromSample($sample, false);
            $a->_interest_id = $sample["id"];
            $articles[] = $a;
            $used_dids[] = $sample["did"];
        }
        usort($articles, $timeProxComparator);
        $articles = array_slice($articles, 0, $limit);

        foreach ($articles as $article) {
            $article->save();
            foreach($article->_pars_tmp as $par){
                $par->article = $article->id;
                $par->save();
            }
            (new TweetKnowledge([
                "tweet" => $tweet->id,
                "knowledge" => Paragraph::findByCtkId($article->_interest_id)->id,
                "search_term" => ''
            ]))->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tweet', 'knowledge'], 'required'],
            [['search_term'], 'string', 'max' => 512],
            [['tweet', 'knowledge'], 'integer'],
            [['created_at'], 'safe'],
            [['tweet'], 'exist', 'skipOnError' => true, 'targetClass' => Tweet::className(), 'targetAttribute' => ['tweet' => 'id']],
            [['knowledge'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::className(), 'targetAttribute' => ['knowledge' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeTweets()
    {
        return [
            'tweet' => 'Tweet',
            'knowledge' => 'Knowledge',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Tweet0]].
     *
     * @return ActiveQuery
     */
    public function getTweet0()
    {
        return $this->hasOne(Tweet::className(), ['id' => 'tweet']);
    }

    /**
     * Gets query for [[Knowledge0]].
     *
     * @return ActiveQuery
     */
    public function getKnowledge0()
    {
        return $this->hasOne(Paragraph::className(), ['id' => 'knowledge']);
    }
}
