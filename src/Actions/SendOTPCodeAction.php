<?php
declare(strict_types=1);

namespace Aminkt\Yii2\Oauth2\Actions;

use Aminkt\Yii2\Oauth2\Forms\OTPCodeGrantForm;
use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;

/**
 * Class SendOTPCodeAction
 * This action will help you to send otp code to user.
 *
 * @package aminkt\yii2\oauth2\Actions
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class SendOTPCodeAction extends Action
{
    /**
     * Return token if valid or not show error messages.
     *
     * @return array|bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $form = new OTPCodeGrantForm();
        $form->setScenario(OTPCodeGrantForm::SCENARIO_SEND_CODE);

        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            if ($form->sendCode()) {
                return ['OTP code sent to user.'];
            } else {
                Yii::$app->response->setStatusCode(400);
                return $form->getErrors();
            }
        }

        throw new BadRequestHttpException();
    }
}
