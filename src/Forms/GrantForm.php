<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Forms;

use Aminkt\Yii2\Oauth2\Interfaces\ClientModelInterface;
use aminkt\yii2\oauth2\interfaces\RefreshTokenModelInterface;
use aminkt\yii2\oauth2\interfaces\UserModelInterface;
use Aminkt\Yii2\Oauth2\Oauth2;
use yii\base\Model;

/**
 * class GrantForm
 * GrantForm used in other grant forms as abstract class to grant access to issuer.
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 *
 * @property null|UserModelInterface                             $user
 * @property null|array                                          $accessToken
 * @property ClientModelInterface $client
 * @property null|RefreshTokenModelInterface                     $token
 */
abstract class GrantForm extends Model
{
    public $grant_type;
    public $client_id;
    public $client_secret;
    public $scope;

    /** @var ClientModelInterface|null $_user */
    private $_client;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['grant_type', 'client_id', 'client_secret'], 'required'],
            [['client_id', 'client_secret', 'grant_type'], 'trim'],
            [['client_secret', 'grant_type'], 'string'],
            [['scope'], 'each', 'rule' => ['string']],
            [['client_secret'], 'validateClientSecret'],
        ];
    }

    /**
     * Validates client secret.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateClientSecret($attribute)
    {
        if (!$this->hasErrors()) {
            $client = $this->getClient();
            if (!$client || !$client->isValidSecret($this->$attribute)) {
                $this->addError($attribute, 'Client or Client secret is not valid.');
            }
        }
    }

    /**
     * Return client object.
     *
     * @return \Aminkt\Yii2\Oauth2\Interfaces\ClientModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getClient(): ClientModelInterface
    {
        if ($this->_client === null) {
            /** @var ClientModelInterface $model */
            $model = Oauth2::getInstance()->clientModelClass;
            $this->_client = $model::findClient($this->client_id);
        }

        return $this->_client;
    }

    /**
     * Generate and return access token.
     *
     * @return null|array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract public function getAccessToken(): ?array;
}
