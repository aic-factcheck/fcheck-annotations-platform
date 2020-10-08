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
class CandidateForm extends Model
{
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
    public function save(){

    }
}
