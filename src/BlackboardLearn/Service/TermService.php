<?php namespace BlackboardLearn\Service;

use BlackboardLearn\Exception\BadRequestException;
use BlackboardLearn\Exception\InvalidResponseException;
use BlackboardLearn\Exception\NotFoundException;
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

    public function getTerms(array $parameters = [])
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
                $responseBody = $response->getBody()->getContents();
                throw new InvalidResponseException("The response could not be converted to JSON! Response Body: {$responseBody}");
            }

            $terms = [];
            foreach ($json->results as $result) {
                $terms[] = Term::initWithStdClass($result);
            }

            return $terms;

        } catch (ClientException $exception) {
            $this->handle400Exception($exception);
            throw $exception;
        }

    }

    public function getTerm(Term $term)
    {
        $url = $this->api_url . self::BASEURL . "/{$term->getId()}";

        try {
            $httpClient = $this->client;
            $response = $httpClient->request('GET', $url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->access_token->getAccessToken()}",
                ]
            ]);

            $json = json_decode($response->getBody()->getContents());
            if(!$json) {
                $responseBody = $response->getBody()->getContents();
                throw new InvalidResponseException("The response could not be converted to JSON! Response Body: {$responseBody}");
            }

            return Term::initWithStdClass($json);
        } catch (ClientException $exception) {
            $this->handle400Exception($exception);
            $this->handle404Exception($exception);
            throw $exception;
        }
    }

    public function createTerm(Term $term)
    {

        return $this->createOrUpdateMethod($term, 'POST');
    }

    public function deleteTerm(Term $term)
    {
        $url = $this->api_url . self::BASEURL . "/{$term->getId()}";

        try {
            $httpClient = $this->client;
            $response = $httpClient->request('DELETE', $url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->access_token->getAccessToken()}",
                ]
            ]);

            if($response->getStatusCode() === 204) {
                return true;
            }

            return false;

        } catch (ClientException $exception) {
            $this->handle400Exception($exception);
            $this->handle404Exception($exception);
            throw $exception;
        }
    }

    public function updateTerm(Term $term)
    {
        return $this->createOrUpdateMethod($term, 'PATCH');
    }

    private function createOrUpdateMethod(Term $term, $httpMethod)
    {
        $url = $this->api_url . self::BASEURL;
        if($httpMethod === 'PATCH') {
            $url .= "/{$term->getId()}";
        }

        try {
            $httpClient = $this->client;
            $response = $httpClient->request($httpMethod, $url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->access_token->getAccessToken()}",
                ],
                'json' => $term
            ]);

            $json = json_decode($response->getBody()->getContents());
            if(!$json) {
                $responseBody = $response->getBody()->getContents();
                throw new InvalidResponseException("The response could not be converted to JSON! Response Body: {$responseBody}");
            }

            return Term::initWithStdClass($json);

        } catch (ClientException $exception) {
            $this->handle400Exception($exception);
            throw $exception;

        }
    }

    private function handle400Exception($exception)
    {
        if($exception->getCode() === 400) {
            $responseBody = $exception->getResponse()->getBody();
            $responseMessageJson = json_decode($responseBody);
            $message = $responseMessageJson->message ? $responseMessageJson->message . ': ' . $responseBody: 'BadRequest';
            throw new BadRequestException($message);
        }
    }

    private function handle404Exception($exception)
    {
        if($exception->getCode() === 404) {
            $message = 'Term not found!';
            throw new NotFoundException($message);
        }
    }
}