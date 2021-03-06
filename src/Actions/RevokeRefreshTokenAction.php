<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Actions;

use Aminkt\Yii2\Oauth2\Forms\RefreshTokenGrantForm;
use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;

/**
 * Class GetAccessTokenAction
 * This action will help you to revoke old refresh_token based.
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
        $form->scenario = RefreshTokenGrantForm::SCENARIO_REVOKE_TOKEN;

        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            if ($form->revokeToken()) {
                return ['Access token revoked.'];
            } else {
                Yii::$app->response->setStatusCode(400);
                return $form->getErrors();
            }
        }

        throw new BadRequestHttpException();
    }
}
