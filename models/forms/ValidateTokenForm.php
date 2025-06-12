<?php

namespace app\models\forms;

use yii\base\Model;

class ValidateTokenForm extends Model
{
    public $token;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['token'], 'required'],
            [['token'], 'string'],
        ];
    }
}
