<?php

use BlackboardLearn\Model\ClientCredentials;

interface OAuthServiceInterface {

    public function getTokenWithClientCredentials(ClientCredentials $clientCredentials);

}