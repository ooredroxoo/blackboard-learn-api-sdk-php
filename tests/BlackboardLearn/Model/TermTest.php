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

    /** @test */
    public function term_should_implement_init_with_stdClass_interface()
    {
        $term = new Term();
        $this->assertInstanceOf(\BlackboardLearn\Utils\InitWithStdClass::class, $term);
    }

    /** @test */
    public function term_should_convert_stdClass_to_term_object_if_valid()
    {
        $jsonData = '
        {
          "id": "_465_1",
          "externalId": "b4ff51ad70cd4e698eb5577d6606afd5asd",
          "dataSourceId": "_2_1",
          "name": "1/2019",
          "description": "<p>Fall 2019</p>",
          "availability": {
            "available": "Yes",
            "duration": {
              "type": "Continuous"
            }
          }
        }';

        $stdClass = json_decode($jsonData);
        $term = Term::initWithStdClass($stdClass);
        $this->assertEquals('_465_1', $term->getId(), "TermId was not set!");
        $this->assertEquals('b4ff51ad70cd4e698eb5577d6606afd5asd', $term->getExternalId(), "Term ExternalId was not set!");
        $this->assertEquals('_2_1', $term->getDataSourceId(), "Term DataSourceId was not set!");
        $this->assertEquals('1/2019', $term->getName(), "Term's name was not set");
        $this->assertEquals('<p>Fall 2019</p>', $term->getDescription(), "Term description was not set!");

        $availability = \BlackboardLearn\Model\Availability::initWithStdClass($stdClass->availability);
        $this->assertEquals($availability, $term->getAvailability());
    }
}
