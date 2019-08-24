<?php

namespace Aminkt\Yii2\Oauth2;

use Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Lib\JwtToken;
use RuntimeException;
use Yii;
use yii\base\Component;

/**
 * Class Module
 * This module can be used to handle oauth2.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2
 *
 * @property string $jwtDecryptKey
 * @property string $jwtEncryptKey
 */
class Oauth2 extends Component
{
    const ENCRYPT_ALG_HS256 = 'HS256';
    const ENCRYPT_ALG_RS256 = 'RS256';

    /** @var string|null    User model class name. */
    public $userModelClass = null;
    /** @var string|null    User refresh token class name. */
    public $refreshTokenModelClass = null;
    /** @var string|null    Client class name. */
    public $clientModelClass = null;
    /** @var string Algorithm that to use encrypt and decrypt token. */
    public $encryptAlgorithm = self::ENCRYPT_ALG_HS256;
    /** @var int    Second to expire refresh token. */
    public $refreshTokenExpireTimeDuration = 2592000;
    /** @var int Second to expire jwt token. */
    public $jwtTokenExpireTimeDuration = 3600;
    /** @var string|null */
    public $hsEncryptKey;
    /** @var string|null */
    public $rsPublicKeyPath;
    /** @var string|null */
    public $rsPrivateKeyPath;

    private static $_instance;


    public static function getInstance(): self
    {
        if (static::$_instance) {
            return static::$_instance;
        }

        if (!Yii::$container->has(Oauth2::class)) {
            throw new RuntimeException('Oauth2 component not registered in DI');
        }

        return static::$_instance = Yii::$container->get(Oauth2::class);
    }

    /**
     * Return jwt decrypt key base on encrypt algorithm.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getJwtDecryptKey(): string
    {
        switch ($this->encryptAlgorithm) {
            case self::ENCRYPT_ALG_HS256:
                return $this->hsEncryptKey;
                break;
            case self::ENCRYPT_ALG_RS256:
                if (!file_exists($this->rsPublicKeyPath)) {
                    throw new RuntimeException('Public key file not exist.');
                }
                $key = file_get_contents($this->rsPublicKeyPath);
                return $key;
        }

        throw new RuntimeException('Invalid algorithm.');
    }

    /**
     * Return JWT encrypt key base on encrypt algorithm.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getJwtEncryptKey(): string
    {
        switch ($this->encryptAlgorithm) {
            case self::ENCRYPT_ALG_HS256:
                return $this->hsEncryptKey;
                break;
            case self::ENCRYPT_ALG_RS256:
                if (!file_exists($this->rsPrivateKeyPath)) {
                    throw new RuntimeException('Public key file not exist.');
                }
                $key = file_get_contents($this->rsPublicKeyPath);
                return $key;
        }

        throw new RuntimeException('Invalid algorithm.');
    }

    /**
     * Generate Jwt token.
     *
     * @param \Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface $userModel
     * @param array                                             $payload
     *
     * @return \Aminkt\Yii2\Oauth2\Lib\JwtToken
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\base\InvalidConfigException
     */
    public function generateJwtToken(UserModelInterface $userModel, array $payload = []): JwtToken
    {
        return new JwtToken($userModel, $payload);
    }

    /**
     * Decrypt jwt token.
     *
     * @param string $token
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function decryptJwtToken(string $token): array
    {
        return JwtToken::decryptJwtToken($token);
    }
}
