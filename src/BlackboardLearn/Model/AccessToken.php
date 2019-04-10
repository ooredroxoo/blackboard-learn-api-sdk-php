<?php namespace BlackboardLearn\Model;


use BlackboardLearn\Service\OAuthServiceInterface;

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

    /** @var OAuthServiceInterface $oauthService - OAuthService to be called in to renew access token. */
    protected $oauthService;
    /** @var ClientCredentials $oauthClientCredentials - Client credentials to be used in order to renew access token. */
    protected $oauthClientCredentials;

    public function __construct()
    {
        $this->epoch_created = time();
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        if(!$this->isValid() && $this->oauthService !== null) {
            $this->renewAccessToken();
        }

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

    /**
     * @param OAuthServiceInterface $oauthService
     * @return AccessToken
     */
    public function setOauthService(OAuthServiceInterface $oauthService)
    {
        $this->oauthService = $oauthService;
        return $this;
    }

    /**
     * @param ClientCredentials $oauthClientCredentials
     * @return AccessToken
     */
    public function setOauthClientCredentials(ClientCredentials $oauthClientCredentials)
    {
        $this->oauthClientCredentials = $oauthClientCredentials;
        return $this;
    }

    private function renewAccessToken()
    {
        // Selects method for renewing access token.
        if($this->oauthClientCredentials !== null) {
            // Get new info.
            $access_token = $this->oauthService->getTokenWithClientCredentials($this->oauthClientCredentials);
            // Resets created time.
            $this->epoch_created = time();
            // Resets expiration
            $this->setExpiresIn($access_token->getExpiresIn());
            // Overwrite the old access token with the new one.
            $this->setAccessToken($access_token->getAccessToken());
        }
    }
}