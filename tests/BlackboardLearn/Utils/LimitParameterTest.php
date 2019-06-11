<?php


use BlackboardLearn\Utils\LimitParameter;
use PHPUnit\Framework\TestCase;

class LimitParameterTest extends TestCase
{
    /** @test */
    public function limit_parameter_name_should_be_limit()
    {
        $limit = new LimitParameter(10);
        $this->assertEquals(10, $limit->getValue(), "Limit was set incorrectly.");
        $this->assertEquals('limit', $limit->getName(), "limit name is incorrect.");
        $this->assertEquals('limit=10', (string) $limit, "Limit string conversion is wrong.");
    }
}
