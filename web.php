<?php

$db = require __DIR__ . '/database.php';

return [
    'id' => 'app',
    'basePath' => __DIR__,
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'db' => $db,
        'snowflake' => [
            'class' => 'xutl\snowflake\Snowflake',
            'workerId' => 0,
            'dataCenterId' => 0,
        ],
        'user' => [
            'identityClass' => 'app\models\db\User',
        ],
        'jwt' => [
            'class' => 'sizeg\jwt\Jwt',
            'signer' => \sizeg\jwt\JwtSigner::HS256,
            'signerKey' => \sizeg\jwt\JwtKey::PLAIN_TEXT,
            'signerKeyPassphrase' => $_ENV['JWT_SIGNER_KEY_PASSPHRASE'],
            'signerKeyContents' => $_ENV['JWT_SIGNER_KEY_CONTENTS'],
            'constraints' => [],
        ],
        'tokenizer' => [
            'class' => 'app\src\components\tokenizer\Component',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => $_ENV['COOKIE_VALIDATION_KEY'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];
