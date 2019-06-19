<?php


use BlackboardLearn\Utils\QueryParameter;

class QueryParameterTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function should_accept_a_name_and_parameter()
    {
        $param = new QueryParameter("nome", "valor");
        $this->assertEquals("nome", $param->getName(), "Name was not set correctly");
        $this->assertEquals("valor", $param->getValue(), "Value was not set correctly");
        $this->assertEquals("nome=valor", (string) $param, "__toString method should return name=value");
    }
}
