<?php

namespace Aminkt\Yii2\Oauth2\Actions;

use Aminkt\Yii2\Oauth2\Forms\AccessTokenForm;
use Yii;
use yii\rest\Action;
use yii\web\BadRequestHttpException;

/**
 * Class ActionRevokeSession
 * By this action you can revoke your refresh token so after some minute generated access_token become invalid and the
 * client cant get new access_token and session will be terminate.
 *
 * @package aminkt\yii2\oauth2\actions
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class ActionRevokeSession extends Action
{
    /**
     * Revoke refresh token.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $form = new AccessTokenForm();

        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            if ($form->revokeToken()) {
                return ['message' => 'Your token revoked successfully.'];
            } else {
                Yii::$app->response->setStatusCode(400);
                return $form->getErrors();
            }
        }

        throw new BadRequestHttpException('Please send your refresh_token and user_id body of your post request');
    }
}
