<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "claim_knowledge".
 *
 * @property int $claim Tvrzení
 * @property int $knowledge Znalost (odstavec článku)
 * @property int $semantic Je ze sémantického vyhledávání?
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['claim', 'knowledge'], 'required'],
            [['claim', 'knowledge', 'semantic'], 'integer'],
            [['created_at'], 'safe'],
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
            'semantic' => 'Je ze sémantického vyhledávání?',
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
