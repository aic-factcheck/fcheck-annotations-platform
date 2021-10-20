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
class SplitsForm extends Model
{
    public $files;
    public $datetime;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, jsonl', 'maxFiles' => 3],
            [['datetime'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            "files" => "Splity [jsonl]",
            "datetime" => "Čas exportu"
        ];
    }

    public function submit()
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
                    }
                }
            }
            return true;
        }
        return false;
    }
}
