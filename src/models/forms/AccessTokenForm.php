<?php

namespace aminkt\yii2\oauth2\models\forms;

use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use aminkt\yii2\oauth2\Module;
use yii\base\Model;

/**
 * class AccessTokenForm
 * Use this form to generate new access token for client.
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property null|UserModelInterface         $user
 * @property null|RefreshTokenModelInterface $token
 */
class AccessTokenForm extends Model
{
    public $refresh_token;
    public $user_id;

    /** @var RefreshTokenModelInterface|null */
    private $_token;
    /** @var UserModelInterface|null $_user */
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $userModelClass = Module::getInstance()->userModelClass;
        return [
            // username and password are both required
            [['refresh_token', 'user_id'], 'required'],
            [['refresh_token', 'user_id'], 'trim'],
            [['user_id'], 'exist', 'targetClass' => $userModelClass, 'targetAttribute' => ['user_id' => 'id']],
            [['refresh_token'], 'validateToken']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $token = $this->getToken();
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
    protected function getToken()
    {
        if ($this->_token === null) {
            /** @var RefreshTokenModelInterface $model */
            $model = Module::getInstance()->refreshTokenModelClass;
            $this->_token = $model::findOne([
                'user_id' => $this->user_id,
                'token' => $this->refresh_token
            ]);
        }

        return $this->_token;
    }

    /**
     * Generate new access token.
     *
     * @return bool|array whether the user is logged in successfully
     */
    public function generateToken()
    {
        if ($this->validate()) {
            $payload = $this->getUser()->payloadCreator();
            $token = $this->getUser()->generateToken($payload);
            return [
                "token_type" => "Bearer",
                "access_token" => $token,
                "expire_in" => $payload['exp'],
                "refresh_token" => $this->getToken()->token
            ];
        }

        return false;
    }

    /**
     * Calling this method to revoke your refresh token.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function revokeToken(){
        if ($this->validate()) {
            $token = $this->getToken();
            return $token->delete();
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
    protected function getUser()
    {
        if ($this->_user === null and $token = $this->getToken()) {
            $this->_user = $token->getUser();
        }

        return $this->_user;
    }
}
