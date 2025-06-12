<?php

namespace app\src\base\controllers;

use app\src\base\exceptions\UserException;
use app\src\base\helpers\LogHelper;
use sizeg\jwt\JwtHttpBearerAuth;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\HttpException;
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
    public function runAction($id, $params = [])
    {
        try {
            return parent::runAction($id, $params);
        } catch (UserException $e) {
            Yii::$app->response->setStatusCode(422);

            return [
                'success' => false,
                'errors' => $e->errors,
            ];
        } catch (HttpException $e) {
            Yii::$app->response->setStatusCodeByException($e);

            return [
                'success' => false,
                'errors' => [
                    $e->getMessage(),
                ],
            ];
        } catch (Throwable $e) {
            Yii::$app->response->setStatusCode(500);
            LogHelper::exception($e);

            return [
                'success' => false,
                'errors' => [
                    'Something went wrong.',
                ]
            ];
        }
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
