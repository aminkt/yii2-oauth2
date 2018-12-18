<?php

namespace aminkt\yii2\oauth2\actions;


use aminkt\yii2\oauth2\models\forms\AccessTokenForm;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * Class ActionGetAccessTokenByRefreshToken
 * This action will help you to get new access_token based your refresh token and user_id.
 *
 * @package aminkt\yii2\oauth2\actions
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class ActionGetAccessTokenByRefreshToken extends \yii\base\Action
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
        $form = new AccessTokenForm();

        if($form->load(\Yii::$app->getRequest()->post(), '')) {
            if ($token = $form->generateToken()) {
                return $token;
            } else {
                \Yii::$app->response->setStatusCode(400);
                return $form->getErrors();
            }
        }

        throw new BadRequestHttpException("Plase send your refresh_token and user_id body of your post request");
    }
}