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
class MutateForm extends Model
{
    public $claim;
    public $mutations;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->claim = Yii::$app->user->identity->getLastUnmutatedClaim();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mutations'], 'each', 'rule' => ['string']],
            [['mutations'], 'safe'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $result = [];
            foreach (Claim::MUTATIONS as $mutation) {
                foreach (explode("\n", $this->mutations[$mutation]) as $claim_) {
                    $claim = new Claim([
                        'paragraph' => $this->claim->paragraph,
                        'claim' => $claim_,
                        'mutation_type' => $mutation,
                        'mutated_from' => $this->claim->id,
                        'sandbox' => $this->claim->sandbox,
                        'user' => Yii::$app->user->id
                    ]);
                    if ($claim->save()) {
                        $result[] = $claim->id;
                    }
                }
            }

            Yii::$app->session->set('mutations', $result);
            return true;
        }
        return false;
    }
}
