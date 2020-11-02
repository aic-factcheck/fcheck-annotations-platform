<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "claim".
 *
 * @property int $id
 * @property int|null $user Anotátor
 * @property string $claim Výrok
 * @property int|null $paragraph Původní odstavec ČTK článku
 * @property int|null $mutated_from Výrok před mutací
 * @property string|null $mutation_type Typ mutace
 * @property int $sandbox Je z testovní verze?
 * @property int $labelled Má label?
 * @property int|null $created_at Datum vzniku
 * @property int|null $updated_at Datum poslední změny
 *
 * @property Claim $mutatedFrom
 * @property Claim[] $claims
 * @property User $user0
 * @property Paragraph $paragraph0
 * @property ClaimKnowledge[] $claimKnowledges
 * @property Label[] $labels
 */
class Claim extends ActiveRecord
{
    const MUTATIONS = ["rephrase", "substitute_similar", "substitute_dissimilar", "specific", "general", "negate"];
    const MUTATION_COLORS = ["rephrase" => "success", "substitute_similar" => "info", "substitute_dissimilar" => "secondary", "specific" => "warning", "general" => "primary", "negate" => "danger"];
    private $_knowledge = null;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'paragraph', 'mutated_from', 'sandbox', 'labelled', 'created_at', 'updated_at'], 'integer'],
            [['claim'], 'required'],
            [['claim'], 'string'],
            [['mutation_type'], 'string', 'max' => 32],
            [['mutated_from'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::class, 'targetAttribute' => ['mutated_from' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user' => 'id']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['paragraph' => 'id']],
        ];
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
            'paragraph' => 'Původní odstavec ČTK článku',
            'mutated_from' => 'Výrok před mutací',
            'mutation_type' => 'Typ mutace',
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
        return $this->hasOne(Claim::class, ['id' => 'mutated_from']);
    }

    /**
     * Gets query for [[Claims]].
     *
     * @return ActiveQuery
     */
    public function getClaims()
    {
        return $this->hasMany(Claim::class, ['mutated_from' => 'id']);
    }

    /**
     * Gets query for [[User0]].
     *
     * @return ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::class, ['id' => 'user']);
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
     * Gets query for [[ClaimKnowledges]].
     *
     * @return ActiveQuery
     */
    public function getClaimKnowledges()
    {
        return $this->hasMany(ClaimKnowledge::class, ['claim' => 'id']);
    }

    /**
     * Gets query for [[Labels]].
     *
     * @return ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::class, ['claim' => 'id']);
    }

    public function getClaimKnowledge()
    {
        return $this->hasMany(Paragraph::class, ['id' => 'knowledge'])
            ->viaTable('claim_knowledge', ['claim' => 'id']);
    }

    public function getKnowledge()
    {
        if ($this->_knowledge == null) {
            $this->_knowledge = [];
            foreach (ArrayHelper::merge($this->claimKnowledge, $this->paragraph0->knowledge) as $paragraph) {
                $this->_knowledge[$paragraph->id] = $paragraph;
            }
        }
        return $this->_knowledge;
    }
}
