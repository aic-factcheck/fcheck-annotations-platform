<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "evidence".
 *
 * @property int $label
 * @property int $paragraph
 * @property int $group
 * @property string $created_at
 *
 * @property Label $label0
 * @property Paragraph $paragraph0
 */
class Evidence extends ActiveRecord
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
            [['label', 'paragraph', 'group'], 'required'],
            [['label', 'paragraph', 'group'], 'integer'],
            [['created_at'], 'safe'],
            [['label', 'paragraph'], 'unique', 'targetAttribute' => ['label', 'paragraph']],
            [['label'], 'exist', 'skipOnError' => true, 'targetClass' => Label::className(), 'targetAttribute' => ['label' => 'id']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::className(), 'targetAttribute' => ['paragraph' => 'id']],
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
            'group' => 'Group',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Label0]].
     *
     * @return ActiveQuery
     */
    public function getLabel0()
    {
        return $this->hasOne(Label::className(), ['id' => 'label']);
    }

    /**
     * Gets query for [[Paragraph0]].
     *
     * @return ActiveQuery
     */
    public function getParagraph0()
    {
        return $this->hasOne(Paragraph::className(), ['id' => 'paragraph']);
    }
}
