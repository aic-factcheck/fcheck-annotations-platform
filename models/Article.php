<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property string $id ID článku (Dle ČTK)
 * @property string $title Titulek
 * @property string $date Datum vydání
 * @property int|null $created_at Datum vzniku
 * @property int|null $updated_at Datum poslední úpravy
 *
 * @property Paragraph[] $paragraphs
 */
class Article extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
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
            [['id', 'title', 'date'], 'required'],
            [['date'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 256],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID článku (Dle ČTK)',
            'title' => 'Titulek',
            'date' => 'Datum vydání',
            'created_at' => 'Datum vzniku',
            'updated_at' => 'Datum poslední úpravy',
        ];
    }

    /**
     * Gets query for [[Paragraphs]].
     *
     * @return ActiveQuery
     */
    public function getParagraphs()
    {
        return $this->hasMany(Paragraph::class, ['article' => 'id']);
    }
}
