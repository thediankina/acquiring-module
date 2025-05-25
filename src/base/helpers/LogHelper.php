<?php

namespace app\src\base\helpers;

use Throwable;
use Yii;

class LogHelper
{
    public static function handleException(Throwable $e): void
    {
        Yii::error($e->getMessage() . "\n" . $e->getTraceAsString());
    }
}
