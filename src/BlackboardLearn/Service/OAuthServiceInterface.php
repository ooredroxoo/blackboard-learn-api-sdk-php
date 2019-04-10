<?php namespace BlackboardLearn\Service;

use BlackboardLearn\Model\AccessToken;
use BlackboardLearn\Model\ClientCredentials;

interface OAuthServiceInterface {

    /**
     * @param ClientCredentials $clientCredentials
     * @return AccessToken
     */
    public function getTokenWithClientCredentials(ClientCredentials $clientCredentials);

}