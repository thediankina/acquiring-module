<?php

namespace app\src\actions\auth;

use app\models\forms\ValidateTokenForm;
use app\src\base\exceptions\UserException;
use Yii;
use yii\rest\Action;

class ValidateToken extends Action
{
    /**
     * @return array
     * @throws UserException
     */
    public function run(): array
    {
        $form = new ValidateTokenForm();

        $form->load(Yii::$app->request->post(), '');

        if (!$form->validate()) {
            throw new UserException($form->getErrorSummary(true));
        }

        return [
            'success' => Yii::$app->tokenizer->verify($form->token),
        ];
    }
}
