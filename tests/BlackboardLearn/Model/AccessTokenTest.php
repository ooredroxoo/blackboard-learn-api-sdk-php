<?php

use BlackboardLearn\Model\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
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
}
