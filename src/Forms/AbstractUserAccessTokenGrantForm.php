<?php


namespace Aminkt\Yii2\Oauth2\Forms;


use Aminkt\Yii2\Oauth2\Interfaces\UserCredentialInterface;
use Aminkt\Yii2\Oauth2\Interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Interfaces\UserOTPInterface;
use Aminkt\Yii2\Oauth2\Oauth2;

abstract class AbstractUserAccessTokenGrantForm extends GrantForm
{
    public $username;

    /** @var UserOTPInterface|null $_user */
    private $_user;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['username'], 'required'],
            [['username'], 'trim']
        ]);
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
            $refreshToken = $user->generateRefreshToken($this->client);
            return [
                'token_type' => 'Bearer',
                'expires_in' => date('Y-m-d H:i:s', $accessToken->getJwtPayload()['exp']),
                'access_token' => $accessToken->getJwtToken(),
                'refresh_token' => $refreshToken->getToken(),
            ];
        }

        return null;
    }

    /**
     * Return user from RefreshToken model.
     *
     * @return UserCredentialInterface|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getUser(): ?UserModelInterface
    {
        if ($this->_user === null) {
            /** @var UserCredentialInterface $userClass */
            $userClass = Oauth2::getInstance()->userModelClass;
            $this->_user = $userClass::findUserByUsername($this->username);
        }

        return $this->_user;
    }
}