<?php

namespace Aminkt\Yii2\Oauth2\Traits;

use Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Lib\JwtToken;
use Aminkt\Yii2\Oauth2\Oauth2;
use Exception;
use RuntimeException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

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
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($type == 'yii\filters\auth\HttpBearerAuth') {
            try {
                $payload = Oauth2::getInstance()->decryptJwtToken($token);
            } catch (ForbiddenHttpException|UnauthorizedHttpException $e) {
                return null;
            }
            $id = $payload['data']->userId;
            return self::findOne($id);
        }

        throw new Exception('Invalid auth type. Just HttpBearerAuth is available.');
    }

    /**
     * Generate Jwt token.
     *
     *
     * @return JwtToken
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\base\InvalidConfigException
     */
    public function generateAccessToken(): JwtToken
    {
        $payloadData = [
            'userId' => $this->getId()
        ];

        if ($this instanceof UserModelInterface) {
            return Oauth2::getInstance()->generateJwtToken(
                $this,
                $payloadData
            );
        }

        throw new RuntimeException('User trait should use in UserModelInterface object');
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->generateAccessToken()->getJwtToken();
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        try {
            $payload = Oauth2::getInstance()->decryptJwtToken($authKey);
        } catch (ForbiddenHttpException|UnauthorizedHttpException $e) {
            return false;
        }

        if (empty($payload)) {
            return true;
        }

        return false;
    }
}
