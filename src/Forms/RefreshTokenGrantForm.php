<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Forms;

use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;

/**
 * class AccessTokenForm
 * Use this form to generate new access token for client by refresh token.
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property string $grant_type
 * @property string $scope
 * @property string $client_id
 * @property string $client_secret
 *
 * @property null|UserModelInterface         $user
 * @property null|RefreshTokenModelInterface $token
 */
class RefreshTokenGrantForm extends GrantForm
{
    const SCENARIO_REVOKE_TOKEN = 'revoke_token';

    public $refresh_token;
    public $user_id;

    /** @var RefreshTokenModelInterface|null */
    private $_token;
    /** @var UserModelInterface|null $_user */
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['refresh_token', 'user_id'], 'required'],
            [['refresh_token', 'user_id'], 'trim'],
            [['refresh_token'], 'validateToken']
        ]);
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_REVOKE_TOKEN => ['refresh_token', 'user_id']
        ]);
    }

    /**
     * Validates the token.
     * This method serves as the inline validation for token.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateToken($attribute)
    {
        if (!$this->hasErrors()) {
            $token = $this->getRefreshToken();
            if (!$token || !$token->isTokenValid()) {
                $this->addError($attribute, 'Refresh token is not valid.');
            } else {
                $this->_user = $token->getUser();
                if (!$this->_user) {
                    $this->addError($attribute, 'Token is broken.');
                }
            }
        }
    }

    /**
     * Finds refresh token.
     *
     * @return RefreshTokenModelInterface|null
     */
    protected function getRefreshToken(): ?RefreshTokenModelInterface
    {
        if ($this->_token === null) {
            /** @var RefreshTokenModelInterface $model */
            $model = Oauth2::getInstance()->refreshTokenModelClass;
            $this->_token = $model::findRefreshToken((int) $this->user_id, $this->refresh_token);
        }

        return $this->_token;
    }

    /**
     * Calling this method to revoke your refresh token.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function revokeToken(): bool
    {
        if ($this->validate()) {
            $token = $this->getRefreshToken();
            $token->revokeToken();
            return true;
        }

        return false;
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
        if ($this->_user === null and $token = $this->getRefreshToken()) {
            $this->_user = $token->getUser();
        }

        return $this->_user;
    }

    /**
     * Generate and return access token.
     *
     * @return null|array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getAccessToken(): ?array
    {
        if ($this->validate()) {
            $refreshToken = $this->getRefreshToken();
            $accessToken = $this->getUser()->generateAccessToken();
            return [
                'token_type' => 'Bearer',
                'access_token' => $accessToken->getJwtToken(),
                'expire_in' => $accessToken->getJwtPayload()['exp'],
                'refresh_token' => $refreshToken->getToken()
            ];
        }

        return null;
    }
}
