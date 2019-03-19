<?php namespace BlackboardLearn\Model;


class AccessToken
{
    /** @var string $access_token */
    protected $access_token;
    /** @var string $token_type */
    protected $token_type;
    /** @var integer $expires_in */
    protected $expires_in;
    /** @var integer $epoch_created */
    protected $epoch_created;
    /** @var $epoch_expired */
    protected $epoch_expired;

    public function __construct()
    {
        $this->epoch_created = time();
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     * @return AccessToken
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->token_type;
    }

    /**
     * @param string $token_type
     * @return AccessToken
     */
    public function setTokenType($token_type)
    {
        $this->token_type = $token_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->epoch_expired - $this->epoch_created;
    }

    /**
     * @param int $expires_in
     * @return AccessToken
     */
    public function setExpiresIn($expires_in)
    {
        $this->expires_in = $expires_in;
        $this->epoch_expired = $this->epoch_created + $expires_in;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->getExpiresIn() > 0;
    }
}