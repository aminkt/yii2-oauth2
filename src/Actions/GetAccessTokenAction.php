<?php

namespace Aminkt\Yii2\Oauth2\Actions;

use Aminkt\Yii2\Oauth2\Forms\GrantFormFactory;
use Yii;
use yii\rest\Action;
use yii\web\BadRequestHttpException;

/**
 * Class GetAccessTokenAction
 * This action will help you to get new access_token based your grant_type.
 *
 * @package aminkt\yii2\oauth2\Actions
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class GetAccessTokenAction extends Action
{
    /**
     * Return token if valid or not show error messages.
     *
     * @return array|bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $form = new GrantFormFactory();

        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            if ($grantForm = $form->getForm() and $token = $grantForm->getAccessToken()) {
                return $token;
            } else {
                Yii::$app->response->setStatusCode(400);
                return array_merge($form->getErrors(), $grantForm->getErrors());
            }
        }

        throw new BadRequestHttpException();
    }
}
