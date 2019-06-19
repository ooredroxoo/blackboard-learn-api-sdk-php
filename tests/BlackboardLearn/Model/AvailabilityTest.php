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

    /** @test */
    public function when_json_encoded_duration_should_not_be_displayed_if_isnt_set()
    {
        $expectedJsonObject = new \stdClass;
        $expectedJsonObject->available = 'Yes';
        $actualJsonObject = new Availability(Availability::AVAILABILITY_AVAILABLE);
        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject), 'Availability should not display duration if it wasn`t set with in!');

        $expectedJsonObject = null;
        $actualJsonObject = null;

        $continuousDuration = \BlackboardLearn\Model\Duration::createContinuousDuration();
        $expectedJsonObject = new \stdClass;
        $expectedJsonObject->available = 'Yes';
        $expectedJsonObject->duration = $continuousDuration;
        $actualJsonObject = new Availability(Availability::AVAILABILITY_AVAILABLE, $continuousDuration);
        $this->assertEquals(json_encode($expectedJsonObject), json_encode($actualJsonObject), 'Availability should display duration if it was set.');
    }

    /** @test */
    public function availability_should_implement_init_with_stdClass_interface()
    {
        $availability = new Availability(Availability::AVAILABILITY_AVAILABLE, \BlackboardLearn\Model\Duration::createContinuousDuration());
        $this->assertInstanceOf(\BlackboardLearn\Utils\InitWithStdClass::class, $availability, "Availability should implement InitWithStdClass");
    }

    /** @test */
    public function availability_should_be_initialized_with_stdClass_object()
    {
        $stdClass = new \stdClass();
        $stdClass->available = 'Yes';
        $stdClass->duration = new stdClass();
        $stdClass->duration->type = 'Continuous';

        $availability = Availability::initWithStdClass($stdClass);
        $this->assertEquals(true, $availability->isAvailable());
        $this->assertEquals(\BlackboardLearn\Model\Duration::createContinuousDuration(), $availability->getDuration());
    }

    /** @test */
    public function availability_should_throw_exception_if_receive_incorrect_stdClass_object()
    {
        $this->expectException(\BlackboardLearn\Exception\InvalidArgumentException::class);
        $stdClass = new \stdClass();
        $stdClass->available = true;
        $stdClass->duration = new stdClass();
        $stdClass->duration->type = 'Continuous';
        Availability::initWithStdClass($stdClass);
    }
}
