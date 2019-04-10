<?php

use BlackboardLearn\Service\OAuthService;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class OAuthServiceTest extends TestCase
{
    /** @test */
    public function should_throw_exception_if_initialized_without_a_client()
    {
        $this->expectException(ArgumentCountError::class);
        $oauth = new OAuthService();
        if($oauth) {
            $this->addWarning('The oauth service should not be able to be initialized without a service!');
        }
    }

    /** @test */
    public function should_return_access_token_when_authorized_with_client_credentials()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"access_token":"umtokennaresposta","token_type":"bearer","expires_in":3276}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $client_credentials = new \BlackboardLearn\Model\ClientCredentials('123', '123');

        $oauth_service = new OAuthService($client);
        $access_token = $oauth_service->getTokenWithClientCredentials($client_credentials);

        $this->assertInstanceOf(\BlackboardLearn\Model\AccessToken::class, $access_token);
        $this->assertEquals('umtokennaresposta', $access_token->getAccessToken());
    }

    /** @test */
    public function should_throw_unauthorized_exception_if_client_credentials_are_wrong()
    {
        $mock = new MockHandler([
            new Response(401, [], '{"error":"invalid_client","error_description":"Invalid credentials"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $client_credentials = new \BlackboardLearn\Model\ClientCredentials('456', '456');

        $this->expectException(\BlackboardLearn\Exception\HTTPUnauthorizedException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $oauth_service = new OAuthService($client);
        $access_token = $oauth_service->getTokenWithClientCredentials($client_credentials);

        if($access_token) {
            $this->addWarning('The access token should not have been returned!');
        }

    }

}
