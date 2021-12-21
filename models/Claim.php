<?php

namespace app\models;

use app\helpers\Helper;
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
 * @property int $deleted Byl smazán?
 * @property string $comment Důvod smazání
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
        "substitute_similar" => "Nahraďte entitu, vztah nebo obojí podobnou entitou či vztahem. <strong>Vyhněte se parafrázování původního tvrzení.</strong>",
        "substitute_dissimilar" => "Nahraďte entitu, vztah nebo obojí jakoukoli entitou či vztahem. <strong>Vyhněte se parafrázování původního tvrzení.</strong>",
        "specific" => "Změňte formulaci tak, aby bylo nové tvrzení specifičtější. Například entitu \"Velká Británie\" nahradíte entitou \"Londýn\".",
        "general" => "Změňte formulaci tak, aby bylo nové tvrzení obecnější. ",
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
    public $_majority_label = null;
    private $_knowledge = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'claim';
    }

    public static function find($deleted = 0)
    {
        return parent::find()->where(['deleted' => $deleted]);
    }

    public function afterFind()
    {
        if ($this->ners !== null) {
            $this->ners = explode(",", $this->ners);
        } else {
            $this->ners = [];
        }
        parent::afterFind();
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
            [['user', 'paragraph', 'mutated_from', 'sandbox', 'labelled', 'created_at', 'updated_at', 'deleted', 'tweet'], 'integer'],
            [['claim'], 'required'],
            [['claim', 'ners', 'comment'], 'string'],
            [['mutation_type'], 'string', 'max' => 32],
            [['mutated_from'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::class, 'targetAttribute' => ['mutated_from' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user' => 'id']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['paragraph' => 'id']],
            [['tweet'], 'exist', 'skipOnError' => true, 'targetClass' => Tweet::class, 'targetAttribute' => ['tweet' => 'id']],
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
            'mutation_type' => 'Typ obměny',
            'sandbox' => 'Je z testovní verze?',
            'labelled' => 'Má label?',
            'created_at' => 'Datum vzniku',
            'updated_at' => 'Datum poslední změny',
            'deleted' => 'Byl smazán?',
            'comment' => 'Důvod smazání',
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

    public function getEvidenceSets($param = 'ctkId', $simulate_nei_evidence = true, $condition = "double", $len_only = false)
    {
        $result = ["SUPPORTS" => [], "REFUTES" => [], "NOT ENOUGH INFO" => []];

        foreach ($this->labels as $label) {
            $e = $label->evidences;
            if ($simulate_nei_evidence && $label->label == "NOT ENOUGH INFO" && count($e) == 0) {
                $result[$label->label][] = [Helper::detokenize($label->claim0->paragraph0->{$param})];
                $k = $label->claim0->getKnowledge();
                $result[$label->label][] = [array_shift($k)->{$param}];
            } else {
                $dispatch_condition = $condition == "double" && !empty($label->condition) /*&& $param = 'text'*/
                ;
                foreach ($e as $evidence) {
                    if (!array_key_exists($label->id . '_' . $evidence->group, $result)) {
                        $result[$label->label][$label->id . '_' . $evidence->group] = [];
                        if ($dispatch_condition) {
                            $result[$label->label][$label->id . '_' . $evidence->group] = [$label->condition];
                            $result["NOT ENOUGH INFO"][$label->id . '_' . $evidence->group] = [];
                        }
                    }
                    $result[$label->label][$label->id . '_' . $evidence->group][] = Helper::detokenize($evidence->paragraph0->{$param});
                    if ($dispatch_condition) {
                        $result["NOT ENOUGH INFO"][$label->id . '_' . $evidence->group][] = Helper::detokenize($evidence->paragraph0->{$param});
                    }
                }
            }
        }
        foreach ($result as $key => $value) {
            $result[$key] = array_unique(array_values($value), SORT_REGULAR);
        }
        return $result;
    }

    public function getKnowledge()
    {
        if ($this->_knowledge == null) {
            $this->_knowledge = [];
            foreach (ArrayHelper::merge($this->claimKnowledge, ($this->paragraph0 == null ? $this->tweet0->knowledge : $this->paragraph0->knowledge)) as $paragraph) {
                $this->_knowledge[$paragraph->id] = $paragraph;
            }
        }
        $keys = array_keys($this->_knowledge);
        shuffle($keys);
        $shuffled = [];
        foreach ($keys as $key) {
            $shuffled[$key] = $this->_knowledge[$key];
        }
        usort($ordered_knowledge, function($a, $b) {return -strcmp($a->article0->date, $b->article0->date);});
        return $shuffled;
    }

    public function getEvidenceSets2($label = "SUPPORTS", $attr = "ctkId", $simulateFever = false, $simulateNeiEvidence = false)
    {
        $labels = Label::find()->andWhere(['label' => $label, 'claim' => $this->id])->all();
        $result = [];
        if ($simulateNeiEvidence && $label == "NOT ENOUGH INFO") {
            $result[] = [$this->paragraph0->{$attr}];
        }
        foreach ($labels as $label) {
            $evidence = Evidence::find()->where(['label' => $label->id])->orderBy('paragraph,label')->all();
            foreach ($evidence as $ev) {
                if (!array_key_exists($label->id . "_" . $ev->group, $result)) $result[$label->id . "_" . $ev->group] = [];
                $result[$label->id . "_" . $ev->group][] = Helper::detokenize($ev->paragraph0->{$attr});
            }
        }
        //die(json_encode( array_values($result)));
        $result = array_unique(array_values($result), SORT_REGULAR);
        if (!$simulateFever) return $result;
        return $this->feverize($result);
    }

    private function feverize($evidenceSets)
    {
        $result2 = [];
        $i = 1;
        foreach ($evidenceSets as $evset) {
            $evset2 = [];
            foreach ($evset as $ev) {
                $evset2[] = [10e2 * $this->id + $i, 10e2 * $this->id + ($i++), $ev, 0];
            }
            $result2[] = $evset2;
        }
        return $result2;
    }

    public function getMajorityLabel($skipConditional = true)
    {
        $max = 0;
        $label = null;
        foreach (Label::LABELS as $label_name) {
            $condition = ['claim' => $this->id, 'label' => $label_name];
            if ($skipConditional) $condition['condition'] = null;
            if (Label::find()->andWhere($condition)->count() > $max) {
                $label = $label_name;
            }
        }
        if ($label == null && Label::find()->andWhere(['claim' => $this->id])->andWhere(['not', ['condition' => null]])->exists()) {
            return "NOT ENOUGH INFO";
        }
        return $label;
    }

    public function getAnnotation()
    {
        if (count($this->labels) == 0) {
            return null;
        }
        return $this->labels[0]->label;
    }

    /**
     * Gets query for [[Tweet0]].
     *
     * @return ActiveQuery
     */
    public function getTweet0()
    {
        return $this->hasOne(Tweet::class, ['id' => 'tweet']);
    }
}
