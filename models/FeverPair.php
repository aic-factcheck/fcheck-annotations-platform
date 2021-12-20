<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "fever_pair".
 *
 * @property int $id ID (auto increment)
 * @property int $fever_id FEVER claim ID
 * @property string $claim Tvrzení
 * @property string $claim_cs Tvrzení v CS
 * @property string $evidence Důkaz
 * @property string $evidence_cs Důkaz v CS
 * @property string $label Pravdivost tvrzení
 * @property string|null $label_cs Pravdivost CS tvrzení
 * @property int|null $checked_by Uživatel, který zkontroloval tvrzení
 * @property int|null $created_at Datum vytvoření
 * @property int|null $updated_at Datum poslední změny
 *
 * @property User $checkedBy
 */
class FeverPair extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fever_pair';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fever_id', 'claim', 'claim_cs', 'evidence', 'evidence_cs', 'label'], 'required'],
            [['fever_id', 'checked_by', 'created_at', 'updated_at'], 'integer'],
            [['evidence', 'evidence_cs'], 'safe'],
            [['claim', 'claim_cs'], 'string', 'max' => 256],
            [['label', 'label_cs'], 'string', 'max' => 16],
            [['checked_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['checked_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fever_id' => 'Fever ID',
            'claim' => 'Claim',
            'claim_cs' => 'Claim Cs',
            'evidence' => 'Evidence',
            'evidence_cs' => 'Evidence Cs',
            'label' => 'Label',
            'label_cs' => 'Label Cs',
            'checked_by' => 'Checked By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CheckedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedBy()
    {
        return $this->hasOne(User::class, ['id' => 'checked_by']);
    }
}
