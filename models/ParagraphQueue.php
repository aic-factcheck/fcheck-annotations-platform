<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "paragraph_queue".
 *
 * @property string $id
 * @property string $created_at
 */
class ParagraphQueue extends ActiveRecord
{
    public static function dequeue()
    {
        $object = self::find()->orderBy(new Expression('rand()'))->one();
        $id = $object->id;
        $object->delete();
        return $id;
    }

    public static function enqueue($id)
    {
        return (new ParagraphQueue(['id' => $id]))->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paragraph_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['created_at'], 'safe'],
            [['id'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
        ];
    }
}
