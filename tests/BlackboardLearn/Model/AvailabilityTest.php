<?php

use BlackboardLearn\Model\Availability;
use PHPUnit\Framework\TestCase;

class AvailabilityTest extends TestCase
{

    /** @test */
    public function is_available_should_return_true_if_availability_has_value_yes()
    {
        $available = new Availability(Availability::AVAILABILITY_AVAILABLE);
        $this->assertTrue($available->isAvailable(), 'Is available should return true when Availability has been initialized with a Yes value!');
    }

    /** @test */
    public function is_available_should_return_false_if_availability_has_value_yes()
    {
        $available = new Availability(Availability::AVAILABILITY_UNAVAILABLE);
        $this->assertFalse($available->isAvailable(), 'Is available should return false when Availability has been initialized with a No value!');
    }

    /** @test */
    public function availability_shoul_be_initialized_only_with_yes_or_no_values()
    {
        $available = new Availability(Availability::AVAILABILITY_AVAILABLE);
        $unavailable = new Availability(Availability::AVAILABILITY_UNAVAILABLE);

        $this->expectException(\BlackboardLearn\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Availability should be initialized only with yes or no as values.");
        $otherAvailability = new Availability('OtherValue');
    }

    /** @test */
    public function availability_should_be_json_serializable()
    {
        $expectedJsonObject = new \stdClass;
        $expectedJsonObject->available = 'Yes';
        $actualJsonObject = new Availability(Availability::AVAILABILITY_AVAILABLE);
        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject), 'Availability should be JSON serializable to be used with our REST API.');

        $expectedJsonObject = null;
        $actualJsonObject = null;

        $expectedJsonObject = new \stdClass;
        $expectedJsonObject->available = 'No';
        $actualJsonObject = new Availability(Availability::AVAILABILITY_UNAVAILABLE);
        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject), 'Availability should be JSON serializable to be used with our REST API.');
    }
}
