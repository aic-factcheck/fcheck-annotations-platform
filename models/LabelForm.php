<?php

namespace app\models;

use app\helpers\Entity;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 * @property Claim $claim
 */
class LabelForm extends Model
{
    public $claim;
    public $flag;
    public $flag_reason;
    public $load;
    public $label;
    public $condition;
    public $sandbox;
    public $oracle;

    public function __construct($sandbox = false, $oracle = false, $claim = null)
    {
        parent::__construct();
        $this->sandbox = $sandbox;
        $this->oracle = $oracle;
        $this->claim = $claim;
    }

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['flag', 'load'], 'integer'],
            [['condition', 'flag_reason'], 'string'],
        ];
    }


    public function save()
    {
        $this->claim->labelled = true;

        if ($this->validate()) {
            if (($label = new Label([
                'claim' => $this->claim->id,
                'label' => Yii::$app->request->post('label'),
                'user' => Yii::$app->user->id,
                'condition' => $this->condition,
                'sandbox' => $this->sandbox,
                'flag' => $this->flag,
                'oracle' => $this->oracle,
            ]))->save()) {
                if ($this->flag) {
                    $this->claim->deleted = 1;
                    $this->claim->comment = "Flag (" . Yii::$app->user->id . "): " . $this->flag_reason;
                }
                if (Yii::$app->request->post("evidence") != null)
                    foreach (Yii::$app->request->post("evidence") as $group => $evidenceList) {
                        foreach ($evidenceList as $paragraph) {
                            (new Evidence(['label' => $label->id, 'paragraph' => $paragraph, 'group' => $group]))->save();
                        }
                    }
                return $this->claim->save(false, ['labelled', 'deleted', 'comment']);
            }
        }
        return false;
    }
}
