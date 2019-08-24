<?php

namespace Aminkt\Yii2\Oauth2\Interfaces;

/**
 * Interface ClientModelInterface
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Aminkt\Yii2\Oauth2\Interfaces
 *
 */
interface ClientModelInterface
{
    /**
     * Validate if client secret is valid or not.
     *
     * @param string $secret
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isValidSecret(string $secret): bool;

    /**
     * Return client object by client id.
     *
     * @param int|string $clientId
     *
     * @return ClientModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function findClient($clientId): ?self;
}
