<?php

namespace Aminkt\Yii2\Oauth2\Actions;

use Aminkt\Yii2\Oauth2\Forms\GrantFormFactory;
use Aminkt\Yii2\Oauth2\Forms\RefreshTokenGrantForm;
use Yii;
use yii\rest\Action;
use yii\web\BadRequestHttpException;

/**
 * Class RevokeRefreshTokenAction
 * This action will help you to revoke access_token based your refresh_token and user_id.
 *
 * @package aminkt\yii2\oauth2\Actions
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class RevokeRefreshTokenAction extends Action
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
        $form = new RefreshTokenGrantForm();

        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            if ($form->revokeToken()) {
                return ['Refresh token revoked.'];
            } else {
                Yii::$app->response->setStatusCode(400);
                return $form->getErrors();
            }
        }

        throw new BadRequestHttpException();
    }
}
