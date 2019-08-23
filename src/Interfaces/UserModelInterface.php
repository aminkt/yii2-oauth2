<?php


namespace Aminkt\Yii2\Oauth2\Interfaces;

/**
 * Interface UserModelInterface
 * You should implement this interface in your user model.
 *
 * @package aminkt\yii2\oauth2\Interfaces
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
interface UserModelInterface
{
    /**
     * Generate a new refresh token and return it.
     *
     * @return \aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateRefreshToken(): RefreshTokenModelInterface;

    /**
     * Generate new jwt token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateJwtToken(): string;
}