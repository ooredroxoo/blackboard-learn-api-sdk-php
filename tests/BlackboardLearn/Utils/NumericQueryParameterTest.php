<?php


use BlackboardLearn\Utils\NumericQueryParameter;
use PHPUnit\Framework\TestCase;

class NumericQueryParameterTest extends TestCase
{
    /** @test */
    public function should_throw_exception_if_value_is_not_a_number()
    {
        $this->expectException(\BlackboardLearn\Exception\InvalidArgumentException::class, "A exception should be throwed when value is not a number.");
        $numericParam = new NumericQueryParameter("limit", "abc");
    }

    /** @test */
    public function should_display_number_equals_value_when_cast_to_string()
    {
        $numericParam = new NumericQueryParameter("limit", 10);
        $this->assertEquals("limit=10", (string)$numericParam, "String conversion is incorrect");
    }
}
