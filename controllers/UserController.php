<?php

namespace app\controllers;

use app\models\db\User;
use app\src\actions\auth\Login;
use app\src\actions\auth\ValidateToken;
use app\src\base\controllers\WebController;
use app\src\interfaces\repositories\UserRepositoryInterface;

class UserController extends WebController
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = User::class;

    /**
     * {@inheritdoc}
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        $id,
        $module,
        public UserRepositoryInterface $userRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'login' => [
                'class' => Login::class,
                'modelClass' => $this->modelClass,
                'userRepository' => $this->userRepository,
            ],
            'validate-token' => [
                'class' => ValidateToken::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs(): array
    {
        return [
            'login' => ['POST'],
            'validate-token' => ['POST'],
        ];
    }
}
