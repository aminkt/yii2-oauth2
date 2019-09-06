<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Interfaces;

use DateTime;

/**
 * Interface RefreshTokenModelInterface
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2\Interfaces
 */
interface RefreshTokenModelInterface
{
    /**
     * Generate and set token.
     *
     * @param \Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface $userModel
     * @param \DateTime|null                                    $expireAt
     * @param string|null                                       $agent
     * @param string|null                                       $ip
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function generateToken(UserModelInterface $userModel, ?DateTime $expireAt = null, ?string $agent = null, ?string $ip = null): RefreshTokenModelInterface;

    /**
     * Return related user model.
     *
     * @return \aminkt\yii2\oauth2\interfaces\UserModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUser(): UserModelInterface;

    /**
     * Return current token id if generated and saved into database.
     *
     * @return int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId(): int;

    /**
     * Return expire time
     *
     * @return DateTime
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getExpireTime(): DateTime;

    /**
     * Return saved refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getToken(): string;

    /**
     * Check if current refresh token is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isTokenValid(): bool;

    /**
     * Revoke refresh token.
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function revokeToken(): void;

    /**
     * Find refresh token.
     *
     * @param int    $userId
     * @param string $refreshToken
     *
     * @return \Aminkt\Yii2\Oauth2\Interfaces\RefreshTokenModelInterface|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function findRefreshToken(int $userId, string $refreshToken): ?RefreshTokenModelInterface;
}
