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
    const MUTATION_NAMES = [
        "rephrase" => "Parafráze",
        "substitute_similar" => "Nahrazení podobnou entitou nebo vztahem",
        "substitute_dissimilar" => "Nahrazení odlišnou entitou nebo vztahem",
        "specific" => "Zúžení",
        "general" => "Zobecnění",
        "negate" => "Negace",
    ];

    const MUTATION_DESCRIPTIONS = [
        "rephrase" => "Přeformulujte nebo parafrázujte tvrzení, aniž byste změnili jeho smysl nebo pravdivost. <strong>Parafrázované tvrzení musí vyplývat z původního a také naopak.</strong>",
        "substitute_similar" => "Nahraďte entitu, vztah nebo obojí podobnou entitou či vztahem. <strong>Vyhněte se parafrázování původního tvrzení. Z nového tvrzení by nemělo plynout původní tvrzení.</strong>",
        "substitute_dissimilar" => "Nahraďte entitu, vztah nebo obojí odlišnou entitou či vztahem. <strong>Vyhněte se parafrázování původního tvrzení. Z nového tvrzení by nemělo plynout původní tvrzení.</strong>",
        "specific" => "Změňte formulaci tak, aby bylo nové tvrzení specifičtější. Z nového tvrzení by mělo plynout původní tvrzení. Například entitu \"Velká Británie\" nahradíte entitou \"Londýn\".",
        "general" => "Změňte formulaci tak, aby bylo nové tvrzení obecnější. Z původního tvrzení by mělo plynout vaše nové tvrzení.",
        "negate" => "Vytvořte negaci původního tvrzení. <strong>Vyvarujte se negace pomocí jednoduchého přidání záporu.</strong>",
    ];

    const MUTATION_EXAMPLES = [
        "rephrase" => ["Barack Obama navštívil Velkou Británii", "Prezident Obama navštívil místa ve Spojeném království.", "Věty mají stejný význam."],
        "substitute_similar" => ["Barack Obama navštívil Velkou Británii", "Barack Obama navštívil Francii", "Velká Británie a Francie jsou země."],
        "substitute_dissimilar" => ["Barack Obama navštívil Velkou Británii", "Barack Obama se zůčastnil večeře korespondentů v Bílém domě.", "Návštěva země a oficiální večeře jsou dva různé typy událostí."],
        "specific" => ["Barack Obama navštívil Velkou Británii", "Barack Obama podnikl státní návštěvu Londýna.", "Londýn je ve Velké Británii. Jestli jej Obama navštívil, musel navštívit i Velkou Británii."],
        "general" => ["Barack Obama navštívil Velkou Británii", "Barack Obama navštívil evropskou zemi.", "Velká Británie je v Evropě. Jestli Obama navštívil Velkou Británii, musel navštívit evropskou zemi."],
        "negate" => ["Barack Obama navštívil Velkou Británii.", "Obama při svých cestách vynechal Velkou Británii.", "Negace: Velkou Británii nemohl navštívit, když ji vynechal."],
    ];
    const FROM = 0, TO = 1, BECAUSE = 2;

    private $_knowledge = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'claim';
    }

    public function afterFind()
    {
        if ($this->ners !== null) {
            $this->ners = explode(",", $this->ners);
        } else {
            $this->ners = [];
        }
        return parent::afterFind();
    }

    public function beforeValidate()
    {
        if (is_array($this->ners)) {
            $this->ners = implode(",", $this->ners);
        }
        return parent::beforeValidate();
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
            [['claim', 'ners'], 'string'],
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
