<?php

namespace Aminkt\Yii2\Oauth2\Forms;

use RuntimeException;
use yii\base\Model;
use yii\web\BadRequestHttpException;

/**
 * Class GrantFormFactory
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2\Forms
 *
 * @property null|\Aminkt\Yii2\Oauth2\Forms\GrantForm $form
 */
class GrantFormFactory extends Model
{
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';
    const GRANT_TYPE_ALL = [
        self::GRANT_TYPE_PASSWORD,
        self::GRANT_TYPE_REFRESH_TOKEN
    ];

    public $grant_type;

    private $_formData = [];
    private $_formName = null;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['grant_type', 'required'],
            ['grant_type', 'in', 'range' => self::GRANT_TYPE_ALL]
        ];
    }

    /**
     * @inheritDoc
     */
    public function load($data, $formName = null)
    {
        $this->_formData = $data;
        $this->_formName = $formName;

        return parent::load($data, $formName);
    }

    /**
     * Return related GrantForm based on grant_type.
     *
     * @return \Aminkt\Yii2\Oauth2\Forms\GrantForm|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     */
    public function getForm(): ?GrantForm
    {
        if ($this->validate()) {
            switch ($this->grant_type) {
                case self::GRANT_TYPE_PASSWORD:
                    $grantForm = new UserCredentialsGrantForm();
                    break;
                case self::GRANT_TYPE_REFRESH_TOKEN:
                    $grantForm = new RefreshTokenGrantForm();
                    break;
                default:
                    throw new RuntimeException('Can not find related GrantForm');
            }

            if (!$grantForm->load($this->_formData, $this->_formName)) {
                throw new BadRequestHttpException("Can't load Grant form.");
            }

            return $grantForm;
        }

        return null;
    }
}
