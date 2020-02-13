<?php

namespace Aminkt\Yii2\Oauth2\Interfaces;

/**
 * Interface UserOTPInterface
 * You should implement this interface in your user model to send Opt code to user.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package aminkt\yii2\oauth2\Interfaces
 */
interface UserOTPInterface extends UserModelInterface
{
    /**
     * Send otp code to user.
     *
     * @param string   $code
     */
    public function sendOTPCode(string $code): void;

    /**
     * Validate provided otp code provided by user.
     *
     * @param string   $code
     *
     * @return bool
     */
    public function validateOTPCode(string $code): bool;

    /**
     * Generate OTP code.
     *
     * @return string
     */
    public function generateOTPCode(): string;
}
