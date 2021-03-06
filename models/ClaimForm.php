<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Paragraph $paragraph
 *
 */
class ClaimForm extends Model
{
    public $claims;
    public $paragraph;
    public $sandbox;

    public function __construct($sandbox = false, $paragraph = false)
    {
        parent::__construct();
        $this->sandbox = $sandbox;
        $traversed = [];
        if (!$paragraph) {
            do {
                $this->paragraph = Paragraph::find()
                    ->where(['IS NOT', 'candidate_of', null])
                    ->andWhere(['not in', 'id', $traversed])
                    ->orderBy(new Expression('rand()+extractions'))
                    ->one();
                $traversed[] = $this->paragraph->id;
            } while (Claim::find()->where(['paragraph' => $this->paragraph->id, 'user' => Yii::$app->user->id])->exists());
        } else {
            $this->paragraph = Paragraph::findOne(['id' => $paragraph]);
        }
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['claims'], 'string'],
            [['claims'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return ["claims" => "tvrzení"]; // TODO: Change the autogenerated stub
    }

    public function save()
    {
        if ($this->validate()) {
            $result = [];
            foreach (array_reverse(explode("\n", $this->claims)) as $claim_) {
                if (strlen(trim($claim_))) {
                    $claim = new Claim([
                        'paragraph' => $this->paragraph->id,
                        'claim' => $claim_,
                        'sandbox' => $this->sandbox,
                        'labelled' => 0,
                        'user' => Yii::$app->user->id
                    ]);
                    if ($claim->save(false)) {
                        $result[] = $claim->id;
                        $this->paragraph->extractions++;
                    }
                }
            }
            return $this->paragraph->save();
        }
        return false;
    }
}
