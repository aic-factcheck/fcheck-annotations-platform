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
        $this->paragraph = $paragraph ? Paragraph::findOne(['id' => $paragraph]) : Paragraph::find()
            ->where(['IS NOT', 'candidate_of', null])
            ->orderBy(new Expression('rand()'))
            ->one();
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
            foreach (explode("\n", $this->claims) as $claim_) {
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
            Yii::$app->session->set('claims', $result);
            return true;
        }
        return false;
    }
}
