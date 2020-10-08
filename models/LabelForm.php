<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LabelForm extends Model
{
    public $claims;
    public $claim;
    public $flag;
    public $sandbox;
    public $oracle;
    public $sentence_json = null;

    public function __construct($sandbox = false,$oracle = false, $claim = null)
    {
        parent::__construct();
        $this->sandbox = $sandbox;
        $this->oracle = $oracle;
        $this->claim = $claim;
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['evidence'], 'string'],
            [['flag'], 'integer'],
        ];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        if ($this->sentence_json != null) {
            $this->claim = json_decode($this->sentence_json,true);
        }
        return $result;
    }

    public function attributeLabels()
    {
        return ['flag'=>'<i class="fas fa-flag"></i> Nahlásit'];
    }

    public function save()
    {
        if ($this->validate()) {
            $result = [];
            foreach (explode("\n", $this->claims) as $claim_) {
                $claim = new Claim([
                    'sentence_id' => $this->claim['sentence_id'],
                    'sentence' => $this->sentence_json,
                    'entity' => $this->claim['entity'],
                    'claim' => $claim_,
                    'sandbox'=>$this->sandbox,
                    'user' => Yii::$app->user->id
                ]);
                if($claim->save()){
                    $result[]=$claim->id;
                }
            }
            Yii::$app->session->set('claims', $result);
            return true;
        }
        return false;
    }

    public function getEntitySentences(){
        return Yii::$app->params['entities'][$this->claim->sentence["entity"]];
    }
}
