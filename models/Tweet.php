<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tweet".
 *
 * @property int $id
 * @property int|null $conversation_id
 * @property string|null $created_at
 * @property string|null $date
 * @property string|null $time
 * @property string|null $timezone
 * @property int|null $user_id
 * @property string|null $username
 * @property string|null $name
 * @property string|null $place
 * @property string|null $tweet
 * @property string|null $language
 * @property string|null $mentions
 * @property string|null $urls
 * @property string|null $photos
 * @property int|null $replies_count
 * @property int|null $retweets_count
 * @property int|null $likes_count
 * @property string|null $hashtags
 * @property string|null $cashtags
 * @property string|null $link
 * @property string|null $retweet
 * @property string|null $quote_url
 * @property string|null $video
 * @property string|null $thumbnail
 * @property string|null $near
 * @property string|null $geo
 * @property string|null $source
 * @property string|null $user_rt_id
 * @property string|null $user_rt
 * @property string|null $retweet_id
 * @property string|null $reply_to
 * @property string|null $retweet_date
 * @property string|null $translate
 * @property string|null $trans_src
 * @property string|null $trans_dest
 * @property string|null $url_titles
 * @property string|null $text
 * @property int $deleted
 * @property string|null $comment
 */
class Tweet extends \yii\db\ActiveRecord
{

    public static function find()
    {
        return parent::find()->where(['deleted' => 0])->andWhere(['<=','date', '2019-03-03 05:00:00']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tweet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'conversation_id', 'user_id', 'replies_count', 'retweets_count', 'likes_count', 'deleted'], 'integer'],
            [['created_at', 'date', 'time'], 'safe'],
            [['tweet', 'mentions', 'urls', 'photos', 'hashtags', 'cashtags', 'link', 'retweet', 'quote_url', 'video', 'thumbnail', 'near', 'geo', 'source', 'user_rt_id', 'user_rt', 'retweet_id', 'reply_to', 'retweet_date', 'translate', 'trans_src', 'trans_dest', 'url_titles', 'text'], 'string'],
            [['timezone'], 'string', 'max' => 64],
            [['username', 'name'], 'string', 'max' => 128],
            [['place'], 'string', 'max' => 512],
            [['language'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 256],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conversation_id' => 'Conversation ID',
            'created_at' => 'Created At',
            'date' => 'Date',
            'time' => 'Time',
            'timezone' => 'Timezone',
            'user_id' => 'User ID',
            'username' => 'Username',
            'name' => 'Name',
            'place' => 'Place',
            'tweet' => 'Tweet',
            'language' => 'Language',
            'mentions' => 'Mentions',
            'urls' => 'Urls',
            'photos' => 'Photos',
            'replies_count' => 'Replies Count',
            'retweets_count' => 'Retweets Count',
            'likes_count' => 'Likes Count',
            'hashtags' => 'Hashtags',
            'cashtags' => 'Cashtags',
            'link' => 'Link',
            'retweet' => 'Retweet',
            'quote_url' => 'Quote Url',
            'video' => 'Video',
            'thumbnail' => 'Thumbnail',
            'near' => 'Near',
            'geo' => 'Geo',
            'source' => 'Source',
            'user_rt_id' => 'User Rt ID',
            'user_rt' => 'User Rt',
            'retweet_id' => 'Retweet ID',
            'reply_to' => 'Reply To',
            'retweet_date' => 'Retweet Date',
            'translate' => 'Translate',
            'trans_src' => 'Trans Src',
            'trans_dest' => 'Trans Dest',
            'url_titles' => 'Url Titles',
            'text' => 'Text',
            'deleted' => 'Deleted',
            'comment' => 'Comment',
        ];
    }

    public function getKnowledge()
    {
        return $this->hasMany(Paragraph::class, ['id' => 'knowledge'])
            ->viaTable('tweet_knowledge', ['tweet' => 'id']);
    }

    public function getOrderedKnowledge(){
        $ordered_knowledge = $this->knowledge;
        usort($ordered_knowledge, function($a, $b) {return -strcmp($a->article0->date, $b->article0->date);});
        return $ordered_knowledge;
    }
}
