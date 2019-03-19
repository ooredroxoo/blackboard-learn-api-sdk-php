<?php

use BlackboardLearn\Model\Duration;

class DurationTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function method_create_with_continuous_duration_should_create_a_continuous_duration_object()
    {
        $duration = Duration::createContinuousDuration();
        $this->assertEquals('Continuous', $duration->getType());
    }

    /** @test */
    public function method_create_with_daterange_duration_should_create_a_daterange_duration_object()
    {
        $duration = Duration::createDateRangeDuration(\DateTime::createFromFormat('Y-m-d H:i:s', '2025-12-25 00:00:00'));
        $this->assertEquals('DateRange', $duration->getType());
    }

    /** @test */
    public function method_create_with_fixed_num_days_duration_should_create_a_fixed_num_days_duration_object()
    {
        $duration = Duration::createFixedNumDaysDuration(180);
        $this->assertEquals('FixedNumDays', $duration->getType());
        $this->assertEquals(180, $duration->getDaysOfUse());
    }

    /** @test */
    public function method_create_with_daterange_duration_can_accept_either_a_start_or_an_end_date_but_cannot_be_created_without_a_least_one_of_them()
    {
        $format = 'Y-m-d H:i:s';
        $startDate = '2000-12-25 00:00:00';
        $endDate = '2025-12-25 00:00:00';
        $duration = Duration::createDateRangeDuration(\DateTime::createFromFormat($format, $startDate));
        $this->assertEquals($startDate, $duration->getStart()->format($format));

        $duration = Duration::createDateRangeDuration(null, \DateTime::createFromFormat($format, $endDate));
        $this->assertEquals($endDate, $duration->getEnd()->format($format));

        $duration = Duration::createDateRangeDuration(\DateTime::createFromFormat($format, $startDate), \DateTime::createFromFormat($format, $endDate));
        $this->assertEquals($startDate, $duration->getStart()->format($format));
        $this->assertEquals($endDate, $duration->getEnd()->format($format));

        $this->expectException(\BlackboardLearn\Exception\DateRangeInvalidException::class);
        $duration = Duration::createDateRangeDuration();
    }

    /** @test */
    public function should_be_json_serializable()
    {
        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'Continuous';
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createContinuousDuration());
        $this->assertEquals($expected_json, $actual_json);
    }

    /** @test */
    public function when_continuous_duration_type_is_json_encoded_it_should_not_have_dates_or_days_of_use()
    {
        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'Continuous';
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createContinuousDuration());
        $this->assertEquals($expected_json, $actual_json);
    }

    /** @test */
    public function when_date_range_duration_type_is_json_encoded_it_should_have_at_least_start_or_end_date_and_have_no_days_of_use()
    {
        $startDate = '2000-12-25 00:00:00';
        $endDate = '2025-12-25 00:00:00';

        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'DateRange';
        $expected_duration_obj->start = \DateTime::createFromFormat('Y-m-d H:i:s', $startDate)->format(\DateTime::ATOM);
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createDateRangeDuration(\DateTime::createFromFormat('Y-m-d H:i:s', $startDate)));
        $this->assertEquals($expected_json, $actual_json);

        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'DateRange';
        $expected_duration_obj->end = \DateTime::createFromFormat('Y-m-d H:i:s', $endDate)->format(\DateTime::ATOM);
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createDateRangeDuration(null, \DateTime::createFromFormat('Y-m-d H:i:s', $endDate)));
        $this->assertEquals($expected_json, $actual_json);

        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'DateRange';
        $expected_duration_obj->start = \DateTime::createFromFormat('Y-m-d H:i:s', $startDate)->format(\DateTime::ATOM);
        $expected_duration_obj->end = \DateTime::createFromFormat('Y-m-d H:i:s', $endDate)->format(\DateTime::ATOM);
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createDateRangeDuration(\DateTime::createFromFormat('Y-m-d H:i:s', $startDate), \DateTime::createFromFormat('Y-m-d H:i:s', $endDate)));
        $this->assertEquals($expected_json, $actual_json);
    }

    /** @test */
    public function when_fixed_num_days_duration_type_is_json_encoded_it_should_have_days_of_use()
    {
        $expected_duration_obj = new \stdClass();
        $expected_duration_obj->type = 'FixedNumDays';
        $expected_duration_obj->daysOfUse = 180;
        $expected_json = json_encode($expected_duration_obj);
        $actual_json = json_encode(Duration::createFixedNumDaysDuration(180));
        $this->assertEquals($expected_json, $actual_json);
    }
}
