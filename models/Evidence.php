<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "evidence".
 *
 * @property int $label
 * @property int $paragraph
 * @property string $created_at
 *
 * @property Label $label0
 * @property Paragraph $paragraph0
 */
class Evidence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evidence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'paragraph'], 'required'],
            [['label', 'paragraph'], 'integer'],
            [['created_at'], 'safe'],
            [['label', 'paragraph'], 'unique', 'targetAttribute' => ['label', 'paragraph']],
            [['label'], 'exist', 'skipOnError' => true, 'targetClass' => Label::class, 'targetAttribute' => ['label' => 'id']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::class, 'targetAttribute' => ['paragraph' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'label' => 'Label',
            'paragraph' => 'Paragraph',
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
        return $this->hasOne(Label::class, ['id' => 'label']);
    }

    /**
     * Gets query for [[Paragraph0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParagraph0()
    {
        return $this->hasOne(Paragraph::class, ['id' => 'paragraph']);
    }
}
