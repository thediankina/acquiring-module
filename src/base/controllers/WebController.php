<?php

namespace app\src\base\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

class WebController extends ActiveController
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = ActiveRecord::class;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'text/html' => Response::FORMAT_JSON,
            ],
        ];

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::class,
            'actions' => $this->verbs(),
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['POST'],
            'delete' => ['POST'],
        ];
    }
}
