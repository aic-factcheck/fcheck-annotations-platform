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
class ClaimForm extends Model
{
    public $claims;
    public $sentence;
    public $sandbox;
    public $sentence_json = null;

    public function __construct($sandbox = false)
    {
        parent::__construct();
        $this->sandbox = $sandbox;
        $pool = $sandbox ? Yii::$app->params['sandbox'] : Yii::$app->params['live'];
        $this->sentence = $pool[array_rand($pool)];
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['claims', 'sentence_json'], 'string'],
            [['claims'], 'required'],
        ];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        if ($this->sentence_json != null) {
            $this->sentence = json_decode($this->sentence_json,true);
        }
        return $result;
    }

    public function save()
    {
        if ($this->validate()) {
            $result = [];
            foreach (explode("\n", $this->claims) as $claim_) {
                $claim = new Claim([
                    'sentence_id' => $this->sentence['sentence_id'],
                    'sentence' => $this->sentence_json,
                    'entity' => $this->sentence['entity'],
                    'claim' => $claim_,
                    'sandbox'=>$this->sandbox,
                    'labelled'=>0,
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
}