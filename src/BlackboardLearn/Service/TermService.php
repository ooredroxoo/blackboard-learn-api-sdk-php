<?php namespace BlackboardLearn\Service;

use BlackboardLearn\Model\AccessToken;
use BlackboardLearn\Model\Availability;
use BlackboardLearn\Model\Duration;
use BlackboardLearn\Model\Term;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TermService
{
    const BASEURL = '/learn/api/public/v1/terms';

    /** @var string $api_url - Blackboard API URL */
    protected $api_url;
    /** @var Client $client - HTTP Client */
    protected $client;
    /** @var AccessToken $access_token - Access Token */
    protected $access_token;

    public function __construct(Client $client, AccessToken $access_token, $api_url)
    {
        $this->client = $client;
        $this->access_token = $access_token;
        $this->api_url = $api_url;
    }

    public function getTerms(array $parameters)
    {
        $url = $this->api_url . self::BASEURL;
        if (count($parameters) > 0) {
            $url .= "?" . implode('&', $parameters);
        }

        try {
            $httpClient = $this->client;
            $response = $httpClient->request('GET', $url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->access_token->getAccessToken()}",
                ]
            ]);

            $json = json_decode($response->getBody()->getContents());
            if(!$json) {
                return null;
            }

            $terms = [];
            foreach ($json->results as $result) {
                $terms[] = Term::initWithStdClass($result);
            }

            return $terms;

        } catch (ClientException $exception) {

        }

    }

    public function getTerm()
    {

    }

    public function createTerm()
    {

    }

    public function deleteTerm()
    {

    }

    public function updateTerm()
    {

    }
}