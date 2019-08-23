<?php

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
     * Generate new refresh token.
     * This method do not save generated token in db. user save method to save this.
     *
     * @param int      $userId User id who we want generate token for him.
     * @param string|null $ip     Client ip address. If not prepared get from request header.
     * @param array|null  $agent  Agent data. this data will hash and store in database.
     *
     * @return string Refresh token.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function generate(int $userId, ?string $ip = null, ?array $agent = null): string;

    /**
     * Generate and set token.
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateToken(): self;

    /**
     * Return related user model.
     *
     * @return \aminkt\yii2\oauth2\interfaces\UserModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUser(): UserModelInterface;

    /**
     * Return related user id.
     *
     * @return int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUserId(): int;

    /**
     * Return current token id if generated and saved into database.
     *
     * @return int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId(): int;

    /**
     * Set user id.
     *
     * @param int $id
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setUserId(int $id): self;

    /**
     * Return agent hash.
     *
     * @return string|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getAgent(): ?string;

    /**
     * Return expire time
     *
     * @return DateTime
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getExpireTime(): DateTime;

    /**
     * Return update time.
     *
     * @return DateTime
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUpdateTime(): DateTime;

    /**
     * Return create time.
     *
     * @return DateTime
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */

    public function getCreateTime(): DateTime;

    /**
     * Return saved refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getToken(): string;

    /**
     * Return user ip relaetd to this refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getIp(): string;

    /**
     * Set client ip.
     *
     * @param string $ip
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setIp(string $ip): self;

    /**
     * Compare that entered agent is valid or not.
     *
     * @param array $agent
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isAgentValid(array $agent): bool;

    /**
     * Set agent hash.
     *
     * @param string $agent
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setAgent(string $agent): self;

    /**
     * Check if current refresh token is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isTokenValid(): bool;
}