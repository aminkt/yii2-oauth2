<?php

namespace Aminkt\Yii2\Oauth2\Forms;

use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;

/**
 * class UserCredentialsGrantForm
 * Use this form to generate new access token for client by username and password.
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property null|UserModelInterface         $user
 * @property null|RefreshTokenModelInterface $token
 */
class UserCredentialsGrantForm extends GrantForm
{
    public $username;
    public $password;

    /** @var UserModelInterface|null $_user */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            [['password'], 'validatePassword']
        ]);
    }

    /**
     * Validates password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'User credential is not valid.');
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): ?array
    {
        if (!$this->validate()) {
            return null;
        }

        $user = $this->getUser();

        if ($user) {
            $accessToken = $user->generateAccessToken();
            $refreshToken = $user->generateRefreshToken();

            return [
                'token_type' => 'Bearer',
                'expires_in' => $accessToken->getJwtPayload()['exp'],
                'access_token' => $accessToken->getJwtToken(),
                'refresh_token' => $refreshToken->getToken(),
            ];
        }

        return null;
    }

    /**
     * Return user from RefreshToken model.
     *
     * @return UserModelInterface|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getUser(): ?UserModelInterface
    {
        if ($this->_user === null) {
            /** @var UserModelInterface $userClass */
            $userClass = Oauth2::getInstance()->userModelClass;
            $this->_user = $userClass::findUserByUsername($this->username);
        }

        return $this->_user;
    }
}
