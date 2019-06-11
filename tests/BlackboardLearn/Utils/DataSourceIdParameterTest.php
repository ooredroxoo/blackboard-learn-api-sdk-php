<?php


use BlackboardLearn\Utils\DataSourceIdParameter;
use PHPUnit\Framework\TestCase;

class DataSourceIdParameterTest extends TestCase
{
    /** @test */
    public function dataSourceId_parameter_name_should_be_dataSourceId()
    {
        $externalId = new DataSourceIdParameter('system_20190510');
        $this->assertEquals('system_20190510', $externalId->getValue(), "dataSourceId is set wrongly");
        $this->assertEquals('dataSourceId', $externalId->getName(), "dataSourceId name is set wrongly");
        $this->assertEquals('dataSourceId=system_20190510', (string) $externalId, "dataSourceId string conversion is incorrect!");
    }
}
