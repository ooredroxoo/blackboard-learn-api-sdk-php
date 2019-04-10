<?php

use BlackboardLearn\Model\ClientCredentials;
use PHPUnit\Framework\TestCase;

class ClientCredentialsTest extends TestCase
{
    /** @test
     * @throws \BlackboardLearn\Exception\InvalidArgumentException
     */
    public function client_credentials_should_be_initialized_with_client_key_and_secret()
    {
        $client_key = 'key';
        $client_secret = 'secret';
        $client_credentials = new ClientCredentials($client_key, $client_secret);

        $this->assertEquals($client_key, $client_credentials->getClientKey());
        $this->assertEquals($client_secret, $client_credentials->getClientSecret());

        /**
         * The following lines are put in place to assert some validation with PHP 5.6 where
         * we can't use type hint for strings
         */
        $this->expectException(\BlackboardLearn\Exception\InvalidArgumentException::class);
        $client_credentials = new ClientCredentials($client_key, null);
        if($client_credentials) {
            $this->addWarning('This should not have been set!');
        }

        $this->expectException(\BlackboardLearn\Exception\InvalidArgumentException::class);
        $client_credentials = new ClientCredentials(null, $client_secret);
        if($client_credentials) {
            $this->addWarning('This should not have been set!');
        }
    }
}
