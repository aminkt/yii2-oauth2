<?php

namespace Aminkt\Yii2\Oauth2\Traits;

use Aminkt\Yii2\Oauth2\Oauth2;
use Exception;
use Yii;
use yii\web\IdentityInterface;

/**
 * Trait UserTrait
 *
 * @method int getId()
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2\Traits
 */
trait UserTrait
{
    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     *
     * @param mixed $type  the type of the token. The value of this parameter depends on the implementation.
     *                     For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be
     *                     `yii\filters\auth\HttpBearerAuth`.
     *
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     * @throws \Exception
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($type == 'yii\filters\auth\HttpBearerAuth') {
            $payload = Oauth2::getInstance()->decryptJwtToken($token);
            $id = $payload['data']->userId;
            return self::findOne($id);
        }

        throw new Exception('Invalid auth type. Just HttpBearerAuth is available.');
    }

    public function generateJwtToken(): string
    {
        return Oauth2::getInstance()->generateJwtToken(
            $this->payloadCreator()
        );
    }

    /**
     * Create a payload for JWT token.
     *
     * @return array    Payload array.
     * @throws \yii\base\InvalidConfigException
     */
    private function payloadCreator(): array
    {
        $payload = [
            'iat' => time(), // Issued at: time when the token was generated
            'jti' => $this->getId() . '_' . time(), // Json Token Id: an unique identifier for the token
            'iss' => Yii::$app->getUrlManager()->getHostInfo(),   // Issuer
            'nbf' => time(),  // Not before
            'exp' => time() + Oauth2::getInstance()->jwtTokenExpireTimeDuration, // Expire
            'data' => [ // Data related to the signer user
                'userId' => $this->getId()
            ]
        ];

        return $payload;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     *
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return '';
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     *
     * @return bool whether the given auth key is valid.
     *
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
}
