<?php

namespace Aminkt\Yii2\Oauth2\Traits;

use DateTime;
use Yii;

/**
 * Trait RefreshTokenTrait
 * This trait implement some use full methods to add into RefreshToken models.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $agent
 * @property string $ip
 * @property string $token
 * @property string $expire_at
 * @property string $updated_at
 * @property string $created_at
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
trait RefreshTokenTrait
{
    public static function generate(int $userId, ?string $ip = null, ?array $agent = null): string
    {
        $model = new static();
        $model->setUserId($userId);

        if (!$ip) {
            $ip = Yii::$app->getRequest()->getUserIP();
        }

        $model->setIp($ip)
            ->setAgent($agent)
            ->generateToken();

        return $model;
    }

    public function setUserId($id): self
    {
        $this->user_id = $id;
        return $this;
    }

    public function setAgent(string $agent): self
    {
        $this->agent = $agent;
        return $this;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function isAgentValid(array $agent): bool
    {
        return true;
    }

    public function isTokenValid(): bool
    {
        $expireTimeStamp = strtotime($this->getExpireTime());
        return $expireTimeStamp >= time();
    }

    public function getExpireTime(): DateTime
    {
        return DateTime::createFromFormat($this->expire_at, 'php:d-m-Y H:i:s');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAgent(): string
    {
        return $this->agent;
    }

    public function getUpdateTime(): DateTime
    {
        return DateTime::createFromFormat($this->updated_at, 'php:d-m-Y H:i:s');
    }

    public function getCreateTime(): DateTime
    {
        return DateTime::createFromFormat($this->created_at, 'php:d-m-Y H:i:s');
    }
}
