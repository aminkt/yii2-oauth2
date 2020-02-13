<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Forms;

use Aminkt\Yii2\Oauth2\Interfaces\UserOTPInterface;
use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;

/**
 * class OTPGrantForm
 * Use this form to generate new access token for client by otp code that will send to user mobile or email.
 *
 * @property string                          $grant_type
 * @property string                          $scope
 * @property string                          $client_id
 * @property string                          $client_secret
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property null|UserModelInterface         $user
 * @property null|RefreshTokenModelInterface $token
 */
class OTPCodeGrantForm extends AuthCodeGrantForm
{
    /**
     * Validate OTP Code.
     *
     * @param $attribute
     */
    public function validateCode($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validateOTPCode($this->$attribute)) {
                $this->addError($attribute, 'OTP code is not valid.');
            }
        }
    }

    /**
     * Send OPT code to user.
     *
     * @return bool
     */
    public function sendCode(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();

        if ($user) {
            $user->sendOTPCode($user->generateOTPCode());
            return true;
        }

        return false;
    }
}
