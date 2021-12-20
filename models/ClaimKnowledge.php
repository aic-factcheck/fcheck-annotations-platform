<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "claim_knowledge".
 *
 * @property int $claim Tvrzení
 * @property int $knowledge Znalost (odstavec článku)
 * @property string|null $search_term Klíčová slova (NULL při sem.search)
 * @property string $created_at Datum vzniku
 *
 * @property Claim $claim0
 * @property Paragraph $knowledge0
 */
class ClaimKnowledge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'claim_knowledge';
    }


    public static function fromDictionary($claim, $dictionary)
    {
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            Article::fromSample($sample);
            (new ClaimKnowledge([
                "claim" => $claim->id,
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
            [['claim', 'knowledge'], 'required'],
            [['claim', 'knowledge'], 'integer'],
            [['created_at'], 'safe'],
            [['search_term'], 'string', 'max' => 512],
            [['claim'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::class, 'targetAttribute' => ['claim' => 'id']],
            [['knowledge'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['knowledge' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'claim' => 'Tvrzení',
            'knowledge' => 'Znalost (odstavec článku)',
            'search_term' => 'Klíčová slova (NULL při sem.search)',
            'created_at' => 'Datum vzniku',
        ];
    }

    /**
     * Gets query for [[Claim0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClaim0()
    {
        return $this->hasOne(Claim::class, ['id' => 'claim']);
    }

    /**
     * Gets query for [[Knowledge0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKnowledge0()
    {
        return $this->hasOne(Paragraph::class, ['id' => 'knowledge']);
    }
}
