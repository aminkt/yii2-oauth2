<?php
declare(strict_types=1);

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
     * @return int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId(): int;

    /**
     * Generate a new refresh token and return it.
     *
     * @param ClientModelInterface|null   $client
     *
     * @return RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateRefreshToken(?ClientModelInterface $client): RefreshTokenModelInterface;

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
     * @return UserModelInterface|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function findUserByUsername(string $username): ?UserModelInterface;

    /**
     * Return user entity by id.
     *
     * @param mixed $id
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function findUserEntityById($id);
}
