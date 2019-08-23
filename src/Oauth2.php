<?php

namespace Aminkt\Yii2\Oauth2;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use RuntimeException;
use UnexpectedValueException;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class Module
 * This module can be used to handle oauth2.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2
 */
class Oauth2 extends Component
{
    const ENCRYPT_ALG_HS256 = 'HS256';
    const ENCRYPT_ALG_RS256 = 'RS256';

    /** @var string|null    User model class name. */
    public $userModelClass = null;
    /** @var string|null    User refresh token class name. */
    public $refreshTokenModelClass = null;
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


    public static function getInstance(): self
    {
        if (!Yii::$container->has(Oauth2::class)) {
            throw new RuntimeException('Oauth2 component not registered in DI');
        }

        return Yii::$container->get(Oauth2::class);
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
        try {
            $payload = (array) JWT::decode($token, $this->getJwtDecryptKey(), [$this->encryptAlgorithm]);
            return $payload;
        } catch (ExpiredException $exception) {
            throw new UnauthorizedHttpException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (SignatureInvalidException $exception) {
            throw new ForbiddenHttpException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (UnexpectedValueException $exception) {
            throw new ForbiddenHttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Return jwt decrypt key base on encrypt algorithm.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    private function getJwtDecryptKey(): string
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
    private function getJwtEncryptKey(): string
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
     * Generate jwt token.
     *
     * @param array $payload
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateJwtToken(array $payload)
    {
        if (!isset($payload['iat']) or
            !isset($payload['data']) or
            !isset($payload['data']['userId'])) {
            throw new InvalidArgumentException('Payload is not valid.');
        }

        return JWT::encode($payload, $this->getJwtEncryptKey(), $this->encryptAlgorithm);
    }
}
