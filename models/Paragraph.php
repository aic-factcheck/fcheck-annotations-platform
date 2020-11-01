<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "paragraph".
 *
 * @property int $id Identifikátor
 * @property int $rank Pořadí věty ve článku
 * @property string $article ČTK článek
 * @property string $text Text odstavce
 * @property string $ners Pojmenované entity
 * @property int|null $candidate_of Uživatel, který zařadil větu do Ú1
 * @property int|null $created_at Datum vzniku
 * @property int|null $updated_at Datum poslední změny
 *
 * @property ClaimKnowledge[] $claimKnowledges
 * @property Evidence[] $evidences
 * @property Label[] $labels
 * @property Article $article0
 * @property User $candidateOf
 * @property ParagraphKnowledge[] $paragraphKnowledges
 */
class Paragraph extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paragraph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rank', 'article', 'text', 'ners'], 'required'],
            [['rank', 'candidate_of', 'created_at', 'updated_at'], 'integer'],
            [['text', 'ners'], 'string'],
            [['article'], 'string', 'max' => 64],
            [['article'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article' => 'id']],
            [['candidate_of'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['candidate_of' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Identifikátor',
            'rank' => 'Pořadí věty ve článku',
            'article' => 'ČTK článek',
            'text' => 'Text odstavce',
            'ners' => 'Pojmenované entity',
            'candidate_of' => 'Uživatel, který zařadil větu do Ú1',
            'created_at' => 'Datum vzniku',
            'updated_at' => 'Datum poslední změny',
        ];
    }

    /**
     * Gets query for [[ClaimKnowledges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClaimKnowledges()
    {
        return $this->hasMany(ClaimKnowledge::className(), ['knowledge' => 'id']);
    }

    /**
     * Gets query for [[Evidences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvidences()
    {
        return $this->hasMany(Evidence::className(), ['paragraph' => 'id']);
    }

    /**
     * Gets query for [[Labels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['id' => 'label'])->viaTable('evidence', ['paragraph' => 'id']);
    }

    /**
     * Gets query for [[Article0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticle0()
    {
        return $this->hasOne(Article::className(), ['id' => 'article']);
    }

    /**
     * Gets query for [[CandidateOf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCandidateOf()
    {
        return $this->hasOne(User::className(), ['id' => 'candidate_of']);
    }

    /**
     * Gets query for [[ParagraphKnowledges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParagraphKnowledges()
    {
        return $this->hasMany(ParagraphKnowledge::className(), ['paragraph' => 'id']);
    }

    public function getKnowledge() {
        return $this->hasMany(Paragraph::className(), ['id' => 'knowledge'])
            ->viaTable('paragraph_knowledge', ['paragraph' => 'id']);
    }
}
