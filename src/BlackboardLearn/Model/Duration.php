<?php namespace BlackboardLearn\Model;


use BlackboardLearn\Exception\DateRangeInvalidException;
use BlackboardLearn\Exception\InvalidArgumentException;
use BlackboardLearn\Utils\InitWithStdClass;
use \DateTime;
use JsonSerializable;

class Duration implements JsonSerializable, InitWithStdClass
{

    const DURATION_TYPE_CONTINUOUS = 'Continuous';
    const DURATION_TYPE_DATERANGE = 'DateRange';
    const DURATION_TYPE_FIXEDNUMDAYS = 'FixedNumDays';

    /** @var string $type */
    protected $type;
    /** @var \DateTime $start */
    protected $start;
    /** @var \DateTime $end */
    protected $end;
    /** @var int $daysOfUse */
    protected $daysOfUse;

    private function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return Duration
     */
    public static function createContinuousDuration()
    {
        return new self(self::DURATION_TYPE_CONTINUOUS);
    }

    /**
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return Duration
     * @throws DateRangeInvalidException
     */
    public static function createDateRangeDuration(\DateTime $start = null, \DateTime $end = null)
    {
        if($start === null && $end === null) {
            throw new DateRangeInvalidException('DateRange duration should have at least a start or an end date!');
        }

        $duration = new self(self::DURATION_TYPE_DATERANGE);

        if($start) {
            $duration->setStart($start);
        }

        if($end) {
            $duration->setEnd($end);
        }

        return $duration;
    }

    /**
     * @param int $daysOfUse
     * @return Duration
     */
    public static function createFixedNumDaysDuration($daysOfUse)
    {
        $duration = new self(self::DURATION_TYPE_FIXEDNUMDAYS);
        $duration->setDaysOfUse((int) $daysOfUse);
        return $duration;
    }

    /**
     * Returns the Duration type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     * @return Duration
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     * @return Duration
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return int
     */
    public function getDaysOfUse()
    {
        return $this->daysOfUse;
    }

    /**
     * @param int $daysOfUse
     * @return Duration
     */
    public function setDaysOfUse($daysOfUse)
    {
        $this->daysOfUse = (int) $daysOfUse;
        return $this;
    }



    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $simple_object_to_be_turned_into_json = new \stdClass;
        $simple_object_to_be_turned_into_json->type = $this->getType();

        if($this->getType() === self::DURATION_TYPE_DATERANGE && $this->getStart()) {
            $simple_object_to_be_turned_into_json->start = $this->getStart()->format(\DateTime::ATOM);
        }

        if($this->getType() === self::DURATION_TYPE_DATERANGE && $this->getEnd()) {
            $simple_object_to_be_turned_into_json->end = $this->getEnd()->format(\DateTime::ATOM);
        }

        if($this->getType() === self::DURATION_TYPE_FIXEDNUMDAYS && $this->getDaysOfUse()) {
            $simple_object_to_be_turned_into_json->daysOfUse = $this->getDaysOfUse();
        }

        return $simple_object_to_be_turned_into_json;
    }

    public static function initWithStdClass(\stdClass $stdObj)
    {
        if($stdObj->type === self::DURATION_TYPE_CONTINUOUS) {
            return self::createContinuousDuration();
        }

        if($stdObj->type === self::DURATION_TYPE_DATERANGE) {
            $start = \DateTime::createFromFormat('Y-m-d\TH:i:s.000\Z', $stdObj->start) ?: null;
            $end = \DateTime::createFromFormat('Y-m-d\TH:i:s.000\Z', $stdObj->end) ?: null;
            return self::createDateRangeDuration($start, $end);
        }

        if($stdObj->type === self::DURATION_TYPE_FIXEDNUMDAYS) {
            return self::createFixedNumDaysDuration($stdObj->daysOfUse);
        }

        throw new InvalidArgumentException("StdClass Object could not be converted to a Duration Object");
    }
}