<?php

namespace app\models;

use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

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
class Article extends CtkData
{
    public $_interest_id = null;
    public $_pars_tmp = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    public static function fromSample($sample, $save = true)
    {
        if (($result = self::findOne($sample["did"])) == null) {
            $result = new Article([
                "id" => $sample["did"],
                "title" => $sample["title"],
                "date" => (new DateTime($sample["date"]))->format("Y-m-d h:i:s")]);
            if ($save) {
                $result->save();
            }
            foreach ($sample["blocks"] as $id => $text) {
                if ($save) {
                    $p = new Paragraph(["article" => $result->id, "rank" => explode("_", $id)[1], "text" => $text]);
                    $p->save();
                } else {
                    $p = new Paragraph(["rank" => explode("_", $id)[1], "text" => $text]);
                    $result->_pars_tmp[] = $p;
                }
            }
        }
        return $result;
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
        return $this->hasMany(Paragraph::class, ['article' => 'id'])->orderBy(["paragraph.rank" => SORT_ASC]);
    }

}
