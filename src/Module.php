<?php

namespace aminkt\yii2\oauth2;

/**
 * Class Module
 * This module can be used to handle oauth2.
 *
 * @package aminkt\yii2\oauth2
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class Module extends \yii\base\Module
{
    public $userModelClass = null;
    public $refreshTokenModelClass = null;

    /**
     * @inheritdoc
     *
     * @return self
     *
     * @author Amin Keshavarz <amin@keshavarz.pro>
     */
    public static function getInstance()
    {
        if (parent::getInstance())
            return parent::getInstance();

        return \Yii::$app->getModule('oauth');
    }

}