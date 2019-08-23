<?php


namespace aminkt\yii2\oauth2\interfaces;

/**
 * Interface UserModelInterface
 * You should implement this interface in your user model.
 *
 * @package aminkt\yii2\oauth2\interfaces
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
    public function generateRefreshToken();
}