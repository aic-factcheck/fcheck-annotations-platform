<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_spent".
 *
 * @property int $id ID
 * @property string|null $route Podstránka
 * @property int $time Strávený čas
 * @property int $user Uživatel
 * @property string $created_at
 *
 * @property User $user0
 */
class TimeSpent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time_spent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'user'], 'required'],
            [['time', 'user'], 'integer'],
            [['created_at'], 'safe'],
            [['route'], 'string', 'max' => 128],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => 'Podstránka',
            'time' => 'Strávený čas',
            'user' => 'Uživatel',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
