<?php

namespace app\models;

use Yii;
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
class TweetKnowledge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tweet_knowledge';
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
     * @return \yii\db\ActiveQuery
     */
    public function getTweet0()
    {
        return $this->hasOne(Tweet::className(), ['id' => 'tweet']);
    }

    /**
     * Gets query for [[Knowledge0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKnowledge0()
    {
        return $this->hasOne(Paragraph::className(), ['id' => 'knowledge']);
    }

    public static function fromDictionary($tweet, $dictionary)
    {
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            Article::fromSample($sample);
            (new TweetKnowledge([
                "tweet" => $tweet->id,
                "knowledge" => Paragraph::findByCtkId($sample["id"])->id,
                "search_term" => array_key_exists("search_term", $sample) ? $sample["search_term"] : ''
            ]))->save();
        }
    }
}
