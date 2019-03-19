<?php namespace BlackboardLearn\Model;


use BlackboardLearn\Exception\InvalidArgumentException;

class ClientCredentials
{
    /** @var string $client_key */
    protected $client_key;
    /** @var string $client_secret */
    protected $client_secret;

    /**
     * ClientCredentials constructor.
     * @param string $client_key
     * @param string $client_secret
     * @throws InvalidArgumentException
     */
    public function __construct($client_key, $client_secret)
    {
        if(!isset($client_key, $client_secret)) {
            throw new InvalidArgumentException('Client Credentials expect a key and a secret!');
        }

        $this->client_key = $client_key;
        $this->client_secret = $client_secret;
    }

    /**
     * @return string
     */
    public function getClientKey()
    {
        return $this->client_key;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

}