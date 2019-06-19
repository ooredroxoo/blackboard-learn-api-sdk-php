<?php


use BlackboardLearn\Utils\ExternalIdParameter;
use PHPUnit\Framework\TestCase;

class ExternalIdParameterTest extends TestCase
{

    /** @test */
    public function externalId_parameter_name_should_be_externalId()
    {
        $externalId = new ExternalIdParameter('id_123');
        $this->assertEquals('id_123', $externalId->getValue(), "ExternalId is set wrongly");
        $this->assertEquals('externalId', $externalId->getName(), "ExternalId name is set wrongly");
        $this->assertEquals('externalId=id_123', (string) $externalId, "ExternalId string conversion is incorrect!");
    }
}
