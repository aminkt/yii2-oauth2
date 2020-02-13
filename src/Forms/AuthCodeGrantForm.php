<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Forms;

use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;

/**
 * class UserCredentialsGrantForm
 * Use this form to generate new access token for client by username and password.
 *
 * @property string $grant_type
 * @property string $scope
 * @property string $client_id
 * @property string $client_secret
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property null|UserModelInterface         $user
 * @property null|RefreshTokenModelInterface $token
 */
class AuthCodeGrantForm extends AbstractUserAccessTokenGrantForm
{
    const SCENARIO_SEND_CODE = 'send_code';

    public $code;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['code'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['code'], 'trim'],
            [['code'], 'validateCode', 'on' => self::SCENARIO_DEFAULT],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_SEND_CODE => ['username']
        ]);
    }

    /**
     * Validates password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateCode($attribute)
    {
        $this->addError($attribute, 'Validation not implemented for code.');
    }
}
