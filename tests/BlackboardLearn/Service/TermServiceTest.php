<?php


use BlackboardLearn\Service\TermService;
use BlackboardLearn\Model\Term;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TermServiceTest extends TestCase
{
    /** @test */
    public function when_terms_are_found_they_should_be_returned_as_array()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"results":[{"id":"_123_1","externalId":"id_123","dataSourceId":"_123_1"
            ,"name":"1st Term","description":"1st term description","availability":{"available":"Yes","duration":{"type":
            "DateRange","start":"2017-01-01T02:00:00.000Z","end":"2217-10-02T02:59:59.000Z"}}},{"id":"_456_1",
            "externalId":"id_456","dataSourceId":"_456_1","name":"2nd Term","availability":{"available":"Yes","duration":{"type":"Continuous"}}}]}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('123');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $terms = $termService->getTerms();

        $this->assertCount(2, $terms);
        $this->assertEquals("_123_1", $terms[0]->getId());
        $this->assertEquals("_456_1", $terms[1]->getId());
    }

    /** @test */
    public function should_throw_exception_if_response_is_not_json_when_trying_to_list_terms()
    {

        $this->expectException(\BlackboardLearn\Exception\InvalidResponseException::class);

        $mock = new MockHandler([
            new Response(200, [], '{asd => asd}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('123');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $termService->getTerms();
    }

    /** @test */
    public function should_return_empty_array_if_response_returns_no_terms_when_trying_to_retrieve_terms()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"results":[]}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('234');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $terms = $termService->getTerms();

        $this->assertCount(0, $terms);
    }

    /** @test */
    public function should_throw_exception_when_sending_an_invalid_parameter()
    {
        $this->expectException(\BlackboardLearn\Exception\BadRequestException::class);
        $mock = new MockHandler([
            new Response(400, [], '{"status":400,"message":"Paging limit may not exceed 200","extraInfo":"hash"}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('234');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $termService->getTerms();
    }

    /** @test */
    public function should_return_a_term_when_successfuly_trying_to_create_a_new_term()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"id": "_760_1","externalId": "api_testing","dataSourceId": "_2_1",
              "name": "API Test","description": "<p>This is a API Test</p>","availability": {
                "available": "Yes","duration": {"type": "Continuous"}}}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('345');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test")
            ->setDescription("<p>This is a API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $newTerm = $termService->createTerm($term);

        $this->assertEquals('_760_1', $newTerm->getId());
        $this->assertEquals($term->getName(), $newTerm->getName());
        $this->assertEquals($term->getDescription(), $newTerm->getDescription());
    }


    /** @test */
    public function should_throw_exception_if_response_is_not_json_when_trying_to_create_term()
    {

        $this->expectException(\BlackboardLearn\Exception\InvalidResponseException::class);

        $mock = new MockHandler([
            new Response(200, [], '{error => error}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('456');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test #2")
            ->setDescription("<p>This is a API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $termService->createTerm($term);
    }

    /** @test */
    public function should_throw_bad_request_exception_when_sending_an_invalid_term()
    {
        $this->expectException(\BlackboardLearn\Exception\BadRequestException::class);
        $mock = new MockHandler([
            new Response(400, [], '{"status": 400,"message": "REASON","extraInfo": "3f1d84b3870f4477a906a28a67f5e091"}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('567');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test #2")
            ->setDescription("<p>This is a API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $termService->createTerm($term);
    }



    /** @test */
    public function should_return_a_term_when_successfuly_trying_to_update_a_term()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"id": "_760_1","externalId": "api_testing","dataSourceId": "_2_1",
              "name": "API Test #3","description": "<p>This is a API Test</p>","availability": {
                "available": "Yes","duration": {"type": "Continuous"}}}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('567');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test #3")
            ->setDescription("<p>This is a API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $updated = $termService->updateTerm($term);

        $this->assertEquals('_760_1', $updated->getId());
        $this->assertEquals($term->getName(), $updated->getName());
        $this->assertEquals($term->getDescription(), $updated->getDescription());
    }


    /** @test */
    public function should_throw_exception_if_response_is_not_json_when_trying_to_update_term()
    {

        $this->expectException(\BlackboardLearn\Exception\InvalidResponseException::class);

        $mock = new MockHandler([
            new Response(200, [], '{error => error}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('789');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test #4")
            ->setDescription("<p>This is a API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $termService->updateTerm($term);
    }

    /** @test */
    public function should_throw_bad_request_exception_when_updating_an_invalid_term()
    {
        $this->expectException(\BlackboardLearn\Exception\BadRequestException::class);
        $mock = new MockHandler([
            new Response(400, [], '{"status": 400,"message": "REASON","extraInfo": "3f1d84b3870f4477a906a28a67f5e0876"}')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $accessToken = new \BlackboardLearn\Model\AccessToken();
        $accessToken->setAccessToken('890');

        $termService = new TermService($client, $accessToken, 'http://blackboard.com');
        $term = new Term();
        $term->setName("API Test #5")
            ->setDescription("<p>Another API Test</p>")
            ->setDataSourceId("_2_1")
            ->setExternalId('api_testing');
        $termService->updateTerm($term);
    }
}
