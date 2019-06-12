<?php


use BlackboardLearn\Service\TermService;
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
}
