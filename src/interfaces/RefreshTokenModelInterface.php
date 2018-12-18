<?php

namespace aminkt\yii2\oauth2\interfaces;

/**
 * Interface RefreshTokenModelInterface
 *
 * @package rest\models
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
interface RefreshTokenModelInterface
{
    /**
     * Generate new refresh token.
     * This methdo do not save generated token in db. user save method to save this.
     *
     * @param integer     $userId User id who we want generate token for him.
     * @param string|null $ip     Client ip address. If not setted get from request header.
     * @param array|null  $agent  Agent data. this data will hasht and store in database.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function generate($userId, $ip = null, $agent = null);

    /**
     * Generate and set token.
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function generateToken();

    /**
     * Return related user model.
     *
     * @return \aminkt\yii2\oauth2\interfaces\UserModelInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUser();

    /**
     * Return related user id.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUserId();

    /**
     * Return current token id if generated and saved into database.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId();

    /**
     * Set user id.
     *
     * @param $id
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setUserId($id);

    /**
     * Return agent hash.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getAgentHash();

    /**
     * Return expire time
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getExpireTime();

    /**
     * Return update time.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUpdateTime();

    /**
     * Return create time.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */

    public function getCreateTime();

    /**
     * Retrurn saved refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getToken();

    /**
     * Return user ip relaetd to this refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getIp();

    /**
     * Set client ip.
     *
     * @param string $ip
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setIp($ip);

    /**
     * Compare that entered agent is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isAgentValid($agent);

    /**
     * Set agent hash.
     *
     * @param array $agent
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setAggent($agent);

    /**
     * Check if current refresh token is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isTokenValid();
}