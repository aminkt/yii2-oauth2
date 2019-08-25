<?php


namespace Aminkt\Yii2\Oauth2\Interfaces;

use Aminkt\Yii2\Oauth2\Lib\JwtToken;

/**
 * Interface UserModelInterface
 * You should implement this interface in your user model.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 *@package aminkt\yii2\oauth2\Interfaces
 *
 */
interface UserModelInterface
{
    /**
     * Return user id.
     *
     * @return int|string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId();

    /**
     * Generate a new refresh token and return it.
     *
     * @return \aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateRefreshToken(): RefreshTokenModelInterface;

    /**
     * Generate new access token.
     *
     * @return JwtToken
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateAccessToken(): JwtToken;

    /**
     * Get user by username. username can be username or mobile or ...
     *
     * @param string $username
     *
     * @return \Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function findUserByUsername(string $username): ?UserModelInterface;

    /**
     * Validate password.
     *
     * @param string $password
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function validatePassword(string $password): bool;
}