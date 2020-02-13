<?php


namespace Aminkt\Yii2\Oauth2\Interfaces;


interface UserCredentialInterface extends UserModelInterface
{
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