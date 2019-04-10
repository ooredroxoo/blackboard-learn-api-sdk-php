<?php

use BlackboardLearn\Model\AccessToken;
use BlackboardLearn\Service\OAuthServiceInterface;
use BlackboardLearn\Model\ClientCredentials;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    const SIMPLE_TOKEN = 'a_simple_token';

    /** @test */
    public function access_token_should_allow_its_type_to_be_setted_and_retrieved()
    {
        $access_token = new AccessToken();
        $access_token->setTokenType('bearer');
        $this->assertEquals('bearer', $access_token->getTokenType());
    }

    /** @test */
    public function access_token_should_allow_its_access_token_to_be_set_and_retrieved()
    {
        $access_token = new AccessToken();
        $access_token->setAccessToken('8KGOFdBEbJpPyBcDEHYyvHs6AxCQAsDL');
        $this->assertEquals('8KGOFdBEbJpPyBcDEHYyvHs6AxCQAsDL', $access_token->getAccessToken());
    }

    /** @test */
    public function access_token_should_track_its_own_expiration()
    {
        $access_token = new AccessToken();
        $access_token->setExpiresIn(100);
        $this->assertTrue($access_token->isValid());

        $access_token = new AccessToken();
        $access_token->setExpiresIn(-100);
        $this->assertFalse($access_token->isValid());
    }

    /** @test */
    public function access_token_should_renew_itself_if_it_has_oauth_service()
    {
        $access_token = $this->createMock(AccessToken::class);
        $access_token->method('getAccessToken')
            ->willReturn(self::SIMPLE_TOKEN);

        $clientCredentials = $this->createMock(ClientCredentials::class);

        $service = $this->createMock(OAuthServiceInterface::class);
        $service->method('getTokenWithClientCredentials')
            ->willReturn($access_token);

        $access_token = new AccessToken();
        $access_token->setExpiresIn(-100)
            ->setOauthService($service)
            ->setOauthClientCredentials($clientCredentials);
        $this->assertEquals(self::SIMPLE_TOKEN, $access_token->getAccessToken());
    }
}