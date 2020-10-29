<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "claim".
 *
 * @property int $id
 * @property int|null $user Anotátor
 * @property string $claim Výrok
 * @property int|null $sentence_id ID ČTK věty
 * @property string|null $mutation_type Typ mutace
 * @property int|null $mutated_from Výrok před mutací
 * @property string|null $entity Entita (ČTK článek)
 * @property int $sandbox Je z testovní verze?
 * @property int $labelled Má label?
 * @property int|null $created_at Datum vzniku
 * @property int $updated_at Datum poslední změny
 * @property Candidate $candidate0
 * @property Claim $mutatedFrom
 * @property Claim[] $claims
 * @property User $user0
 * @property Label[] $labels
 */
class Claim extends ActiveRecord
{
    const MUTATIONS = ["rephrase", "substitute_similar", "substitute_dissimilar", "specific", "general", "negate"];
    const MUTATION_COLORS = ["rephrase" => "success", "substitute_similar" => "info", "substitute_dissimilar" => "secondary", "specific" => "warning", "general" => "primary", "negate" => "danger"];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'claim';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'mutated_from', 'sandbox', 'labelled', 'candidate'], 'integer'],
            [['claim'], 'required'],
            [['claim', 'sentence_id', 'sentence'], 'string'],
            [['mutation_type'], 'string', 'max' => 32],
            [['entity'], 'string', 'max' => 512],
            [['mutated_from'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['mutated_from' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
            [['candidate'], 'exist', 'skipOnError' => true, 'targetClass' => Candidate::className(), 'targetAttribute' => ['candidate' => 'id']],
        ];
    }

    /**
     * Gets query for [[Candidate0]].
     *
     * @return ActiveQuery
     */
    public function getCandidate0()
    {
        return $this->hasOne(Candidate::className(), ['id' => 'candidate']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'Anotátor',
            'claim' => 'Výrok',
            'sentence_id' => 'ID ČTK věty',
            'mutation_type' => 'Typ mutace',
            'mutated_from' => 'Výrok před mutací',
            'entity' => 'Entita (ČTK článek)',
            'sandbox' => 'Je z testovní verze?',
            'labelled' => 'Má label?',
            'created_at' => 'Datum vzniku',
            'updated_at' => 'Datum poslední změny',
        ];
    }

    /**
     * Gets query for [[MutatedFrom]].
     *
     * @return ActiveQuery
     */
    public function getMutatedFrom()
    {
        return $this->hasOne(Claim::className(), ['id' => 'mutated_from']);
    }

    /**
     * Gets query for [[Claims]].
     *
     * @return ActiveQuery
     */
    public function getClaims()
    {
        return $this->hasMany(Claim::className(), ['mutated_from' => 'id']);
    }

    /**
     * Gets query for [[User0]].
     *
     * @return ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

    /**
     * Gets query for [[Labels]].
     *
     * @return ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['claim' => 'id']);
    }
}
