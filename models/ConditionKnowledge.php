<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "condition_knowledge".
 *
 * @property int $label
 * @property int $knowledge
 * @property string $created_at
 *
 * @property Label $label0
 * @property Paragraph $knowledge0
 */
class ConditionKnowledge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'condition_knowledge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'knowledge'], 'required'],
            [['label', 'knowledge'], 'integer'],
            [['created_at'], 'safe'],
            [['label'], 'exist', 'skipOnError' => true, 'targetClass' => Label::className(), 'targetAttribute' => ['label' => 'id']],
            [['knowledge'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::className(), 'targetAttribute' => ['knowledge' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'label' => 'Label',
            'knowledge' => 'Knowledge',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Label0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabel0()
    {
        return $this->hasOne(Label::className(), ['id' => 'label']);
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

    public static function fromDictionary($claim, $dictionary)
    {
        foreach (ArrayHelper::merge($dictionary["semantic_blocks"], $dictionary["ner_blocks"]) as $sample) {
            Article::fromSample($sample);
            (new ConditionKnowledge([
                "claim" => $claim->id,
                "knowledge" => Paragraph::findByCtkId($sample["id"])->id
            ]))->save();
        }
    }
}
