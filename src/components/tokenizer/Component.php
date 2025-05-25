<?php

namespace app\src\components\tokenizer;

use DateTimeImmutable;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Yii;

class Component extends \yii\base\Component
{
    private Signer $signer;
    private Key $signerKey;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->signer = Yii::$app->jwt->getSigner();
        $this->signerKey = Yii::$app->jwt->getSignerKey();
    }

    public function generate(int $uid): string
    {
        $issuedAt = new DateTimeImmutable();
        $expiresAt = $issuedAt->modify('+ 1 hour');

        return Yii::$app->jwt->getBuilder()
            ->issuedBy('acquiring')
            ->permittedFor('acquiring')
            ->identifiedBy('tokenId')
            ->issuedAt($issuedAt)
            ->expiresAt($expiresAt)
            ->withClaim('uid', $uid)
            ->getToken($this->signer, $this->signerKey)
            ->toString();
    }

    public function verify(string $token): bool
    {
        return Yii::$app->jwt->loadToken(jwt: $token, throwException: false) !== null;
    }
}
