<?php

namespace app\src\actions\auth;

use app\models\forms\LoginForm;
use app\src\base\exceptions\UserException;
use app\src\interfaces\repositories\UserRepositoryInterface;
use Yii;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

class Login extends Action
{
    public UserRepositoryInterface $userRepository;

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws UserException
     */
    public function run(): array
    {
        $form = new LoginForm();

        $form->load(Yii::$app->request->post(), '');

        if (!$form->validate()) {
            throw new UserException($form->getErrorSummary(true));
        }

        $user = $this->userRepository->findOneByLogin($form->login);

        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }

        if (!Yii::$app->security->validatePassword($form->password, $user->password_hash)) {
            throw new UserException(['Invalid password.']);
        }

        return [
            'success' => true,
            'data' => [
                'token' => Yii::$app->tokenizer->generate(Yii::$app->user->id)
            ],
        ];
    }
}
