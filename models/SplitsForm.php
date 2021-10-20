<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\web\UploadedFile;

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
    public $_ready = false;
    public $_splits = [];
    public $_names = [];
    public $_all = [];
    public $_claims = [];
    public $_label_count = ["SUPPORTS" => 0, "REFUTES" => 0, "NOT ENOUGH INFO" => 0];
    public $_label_count_db = ["SUPPORTS" => 0, "REFUTES" => 0, "NOT ENOUGH INFO" => 0];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['files'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 3, 'minFiles' => 3],
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
        $this->files = UploadedFile::getInstances($this, 'files');
        if ($this->validate()) {
            $i = -1;
            foreach ($this->files as $file) {
                $this->_names[] = $file->baseName;
                $this->_splits[++$i] = [];
                $lines = explode("\n", file_get_contents($file->tempName));
                foreach ($lines as $line) {
                    $datapoint = json_decode($line, true);
                    if ($datapoint == null) continue;
                    $this->_splits[$i][] = $datapoint;
                    $this->_all[$datapoint["id"]] = $datapoint;
                    $this->_label_count[$datapoint["label"]]++;
                }
            }

            foreach (Claim::find()->andWhere(['not', ['mutated_from' => null]])->all() as $claim) {
                $claim->_majority_label = $claim->getMajorityLabel();
                if($claim->_majority_label == null) continue;
                $this->_label_count_db[$claim->_majority_label]++;
                $this->_claims[$claim->id] = $claim;
            }
            $this->_ready = true;
            return true;
        }
        return false;
    }
}
