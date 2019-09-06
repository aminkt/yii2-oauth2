<?php
declare(strict_types=1);

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
    public static function findClient($clientId): ?ClientModelInterface;

    /**
     * Return client id.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId();

    /**
     * Return client description to generate refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getClientDescription(): string;
}
