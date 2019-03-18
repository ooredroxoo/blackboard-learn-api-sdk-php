<?php

use BlackboardLearn\Model\Term;
use PHPUnit\Framework\TestCase;

class TermTest extends TestCase
{
    /** @test */
    public function when_json_encoding_should_throw_exception_if_required_data_is_not_set()
    {
        $this->expectException(\BlackboardLearn\Exception\IllegalJsonSerializationStateException::class);
        $term = new Term();
        $term->setDescription('Something');
        json_encode($term);
    }

    /** @test */
    public function when_json_encoded_should_omit_undefined_optional_fields()
    {
        $expectedJsonObject = new \stdClass;
        $expectedJsonObject->name = 'Some term';
        $expectedJsonObject->externalId = 'externalId';

        $actualJsonObject = new Term();
        $actualJsonObject->setName('Some term')
            ->setExternalId('externalId');

        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject));

        $availability = new \BlackboardLearn\Model\Availability(\BlackboardLearn\Model\Availability::AVAILABILITY_AVAILABLE);

        $expectedJsonObject->description = 'Some description!';
        $expectedJsonObject->dataSourceId = 'someDataSourceId';
        $expectedJsonObject->id = '123';
        $expectedJsonObject->availability = $availability;

        $actualJsonObject->setDescription('Some description!')
            ->setDataSourceId('someDataSourceId')
            ->setId('123')
            ->setAvailability($availability);

        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject));
    }
}
