<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paragraph_knowledge".
 *
 * @property int $paragraph
 * @property int $knowledge
 * @property int $semantic
 * @property string $created_at
 *
 * @property Paragraph $paragraph0
 * @property Paragraph $knowledge0
 */
class ParagraphKnowledge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paragraph_knowledge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paragraph', 'knowledge', 'semantic'], 'required'],
            [['paragraph', 'knowledge', 'semantic'], 'integer'],
            [['created_at'], 'safe'],
            [['paragraph', 'knowledge', 'semantic'], 'unique', 'targetAttribute' => ['paragraph', 'knowledge', 'semantic']],
            [['paragraph'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::className(), 'targetAttribute' => ['paragraph' => 'id']],
            [['knowledge'], 'exist', 'skipOnError' => true, 'targetClass' => Paragraph::className(), 'targetAttribute' => ['knowledge' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paragraph' => 'Paragraph',
            'knowledge' => 'Knowledge',
            'semantic' => 'Semantic',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Paragraph0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParagraph0()
    {
        return $this->hasOne(Paragraph::className(), ['id' => 'paragraph']);
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
}
