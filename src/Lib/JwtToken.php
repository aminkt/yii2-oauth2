<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Lib;

use Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class JwtToken will handle jwt token.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2
 *
 */
class JwtToken
{
    private $_payload;
    private $_token;
    protected $userModel;
    protected $payloadData;

    /**
     * JwtToken constructor.
     *
     * @param \Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface $userModel
     * @param array
     */
    public function __construct(UserModelInterface $userModel, array $payloadData = [])
    {
        $this->payloadData = $payloadData;
        $this->userModel = $userModel;
        $this->generateJwtToken();
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
    public static function decryptJwtToken(string $token): array
    {
        $oauth2= Oauth2::getInstance();
        try {
            $payload = (array) JWT::decode($token, $oauth2->getJwtDecryptKey(), [$oauth2->encryptAlgorithm]);
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
     * Generate jwt token.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function generateJwtToken(): void
    {
        $payload = $this->getJwtPayload();

        if (!isset($payload['iat']) or
            !isset($payload['data']) or
            !isset($payload['data']['userId'])) {
            throw new InvalidArgumentException('Payload is not valid.');
        }

        $oauth2= Oauth2::getInstance();
        $this->_token = JWT::encode($payload, $oauth2->getJwtEncryptKey(), $oauth2->encryptAlgorithm);
    }

    /**
     * Return jwtToken payload.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getJwtPayload(): array
    {
        if (!$this->_payload) {
            $this->_payload = [
                'iat' => time(), // Issued at: time when the token was generated
                'jti' => $this->userModel->getId() . '_' . time(), // Json Token Id: an unique identifier for the token
                'iss' => Yii::$app->getUrlManager()->getHostInfo(),   // Issuer
                'nbf' => time(),  // Not before
                'exp' => time() + Oauth2::getInstance()->jwtTokenExpireTimeDuration, // Expire
                'data' => $this->payloadData
            ];
        }

        return $this->_payload;
    }

    /**
     * Return generated jwt token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getJwtToken(): string
    {
        return $this->_token;
    }
}