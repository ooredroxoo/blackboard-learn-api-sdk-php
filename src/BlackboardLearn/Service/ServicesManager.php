<?php


namespace BlackboardLearn\Service;


use BlackboardLearn\Exception\HTTPUnauthorizedException;
use BlackboardLearn\Model\AccessToken;
use BlackboardLearn\Model\ClientCredentials;
use GuzzleHttp\Client;

class ServicesManager
{
    /** @var AccessToken */
    protected $accessToken;
    protected $apiURL;

    protected function __construct(AccessToken $accessToken, $url)
    {
        $this->accessToken = $accessToken;
        $this->apiURL = $url;
    }

    public static function initWithClientCredentials(ClientCredentials $clientCredentials, $url)
    {
        $oauth = new OAuthService(new Client(), $url);
        $accessToken = $oauth->getTokenWithClientCredentials($clientCredentials);
        if($accessToken) {
            return new ServicesManager($accessToken, $url);
        }
        throw new HTTPUnauthorizedException("Could not connect with the API");
    }

    public function getTermService()
    {
        return new TermService(new Client(), $this->accessToken, $this->apiURL);
    }
}