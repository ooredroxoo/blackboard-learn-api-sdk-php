<?php


use BlackboardLearn\Utils\OffsetParameter;
use PHPUnit\Framework\TestCase;

class OffsetParameterTest extends TestCase
{

    /** @test */
    public function limit_parameter_name_should_be_limit()
    {
        $offset = new OffsetParameter(1);
        $this->assertEquals(1, $offset->getValue(), "Offset is set wrongly");
        $this->assertEquals('offset', $offset->getName(), "offset name is set wrongly");
        $this->assertEquals('offset=1', (string) $offset, "offset string conversion is incorrect!");
    }
}
