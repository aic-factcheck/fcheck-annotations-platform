<?php

namespace app\models;

use app\helpers\Entity;
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
    public $claim;
    public $flag;
    public $load;
    public $label;
    public $sandbox;
    public $oracle;

    public function __construct($sandbox = false, $oracle = false, $claim = null)
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
            [['flag', 'load'], 'integer'],
        ];
    }


    public function save()
    {
        $this->claim->labelled = true;
        return $this->validate() && (new Label([
                'claim' => $this->claim->id,
                'label' => Yii::$app->request->post('label'),
                'user' => Yii::$app->user->id,
                'sandbox' => $this->sandbox,
                'flag' => $this->flag,
                'oracle' => $this->oracle,
                'evidence' => json_encode(Yii::$app->request->post('evidence'),JSON_UNESCAPED_UNICODE)
            ]))->save() && $this->claim->save(false,['labelled']);
    }

    public function getEntitySentences()
    {
        return Entity::get($this->claim->sentence["entity"]);
    }
}
