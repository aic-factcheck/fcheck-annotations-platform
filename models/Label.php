<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "label".
 *
 * @property int $id ID
 * @property int|null $user Anotátor
 * @property int $claim Výrok
 * @property string $label Label
 * @property string $evidence Důkaz
 * @property int $sandbox Je label ze zkušební v.?
 * @property int $oracle Je label oracle anotací?
 * @property int|null $created_at Datum vytvoření
 * @property int|null $updated_at Datum poslední změny
 *
 * @property User $user0
 * @property Claim $claim0
 */
class Label extends ActiveRecord
{
    const LABELS = ["SUPPORTS", "REFUTES", "NOT ENOUGH INFO"];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'label';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'claim', 'sandbox', 'oracle', 'flag', 'created_at', 'updated_at'], 'integer'],
            [['claim'], 'required'],
            [['label', 'evidence'], 'string'],
            ['label', 'in', 'range' => self::LABELS],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
            [['claim'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['claim' => 'id']],
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
            'label' => 'Label',
            'evidence' => 'Důkaz',
            'sandbox' => 'Je label ze zkušební v.?',
            'oracle' => 'Je label oracle anotací?',
            'created_at' => 'Datum vytvoření',
            'updated_at' => 'Datum poslední změny',
        ];
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
     * Gets query for [[Claim0]].
     *
     * @return ActiveQuery
     */
    public function getClaim0()
    {
        return $this->hasOne(Claim::className(), ['id' => 'claim']);
    }
}
