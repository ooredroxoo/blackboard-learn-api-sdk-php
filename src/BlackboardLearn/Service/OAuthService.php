<?php namespace BlackboardLearn\Service;

use BlackboardLearn\Exception\HTTPUnauthorizedException;
use BlackboardLearn\Model\AccessToken;
use BlackboardLearn\Model\ClientCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OAuthService implements OAuthServiceInterface
{
    /** @var Client $client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param ClientCredentials $clientCredentials
     * @return AccessToken
     * @throws HTTPUnauthorizedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenWithClientCredentials(ClientCredentials $clientCredentials)
    {
        try {
            $httpClient = $this->client;
            $authBasic = base64_encode($clientCredentials->getClientKey() . ':' . $clientCredentials->getClientSecret());
            $response = $httpClient->request('POST', '/learn/api/public/v1/oauth2/token', [
                'headers' => [
                    'Authorization' => "Basic {$authBasic}",
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $json = json_decode($response->getBody()->getContents());
            if($json) {
                $access_token = new AccessToken();
                $access_token->setTokenType($json->token_type)
                    ->setAccessToken($json->access_token)
                    ->setExpiresIn($json->expires_in);
                return $access_token;
            }
            return null;
        } catch (ClientException $exception) {
            if($exception->getCode() === 401) {
                $json = json_decode($exception->getResponse()->getBody()->getContents());
                throw new HTTPUnauthorizedException($json->error_description);
            }
        }
    }

}