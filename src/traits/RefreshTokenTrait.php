<?php

namespace aminkt\yii2\oauth2\traits;

/**
 * Trait RefreshTokenTrait
 * This trait implement some usefull methods to add into RefreshToken models.
 *
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
trait RefreshTokenTrait
{
    /**
     * @inheritdoc
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function generate($userId, $ip = null, $agent = null)
    {
        $model = new static();
        $model->setUserId($userId);
        if (!$ip) {
            $ip = \Yii::$app->getRequest()->getUserIP();
        }
        $model->setIp($ip)
            ->setAggent($agent)
            ->generateToken();

        return $model;
    }

    /**
     * @inheritdoc
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    /**
     * Set agent hash.
     *
     * @param array $agent
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setAggent($agent)
    {
        $this->agent_hash = md5(base64_encode(json_encode($agent)));
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return static
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Retrurn saved refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Return user ip relaetd to this refresh token.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Compare that entered agent is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isAgentValid($agent)
    {
        return true;
    }

    /**
     * Check if current refresh token is valid or not.
     *
     * @return boolean
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isTokenValid()
    {
        $expiteTimeStamp = strtotime($this->getExpireTime());
        return $expiteTimeStamp >= time();
    }

    /**
     * Return related user id.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Return current token id if generated and saved into database.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return agent hash.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getAgentHash()
    {
        return $this->agent_hash;
    }

    /**
     * Return expire time
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getExpireTime()
    {
        return $this->expire_in;
    }

    /**
     * Return update time.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getUpdateTime()
    {
        return $this->updated_at;
    }

    /**
     * Return create time.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getCreateTime()
    {
        $this->created_at;
    }
}